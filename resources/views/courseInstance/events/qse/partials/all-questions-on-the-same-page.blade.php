<div id="instructions-{{ $child->id }}-all">
    @if((count($eventUserQSEs) == 0 || $child->qse->allow_multiple_submits) && $child->qse->instructions)
        <h5><i class="far fa-info-square text-muted"></i> {{ trans('labels.qse.instructions') }}</h5>
        <p>{!! $child->qse->instructions !!}</p>
    @endif
    @if(count($eventUserQSEs) == 0 || $child->qse->allow_multiple_submits)
        <div class="d-flex">
            <button
                class="btn btn-primary mx-auto"
                id="btn-take-{{ $child->id }}-quiz-all"
                @if($incompleteQuiz)
                    onclick="$('#questions-form-{{ $child->id }}-all').removeClass('d-none'); $('#instructions-{{ $child->id }}-all').addClass('d-none'); $('#results-{{ $child->id }}-all').addClass('d-none'); "
                @endif
                {{ $isTakeQuizDisabled ? 'disabled' : '' }}
            >
                @if($child->qse->qse_type_id == 1)
                    {{ $incompleteQuiz ? trans('buttons.qse.resume_quiz') : trans('buttons.qse.take_the_quiz') }}
                @else
                    {{ trans('buttons.qse.complete_the_qse', ['qse' => $child->menu_title]) }}
                @endif
            </button>
        </div>
    @endif
</div>

@if($isTakeQuizDisabled)
    <p class="mb-0 text-center">{{ $disabledTakeQuizMessage }}</p>
@endif

@include('courseInstance.events.qse.partials.results', ['quiz_type' => 'all'])

<div id="questions-form-{{ $child->id }}-all" class="d-none">
    @if($child->qse->instructions)
        <h3 class="font-weight-bold">{{ trans('labels.qse.instructions') }}</h3>
        <p>{!! $child->qse->instructions !!}</p>
    @endif
    <hr/>
    @php
        $questions = \App\Models\CourseContent\QSE\QSEQuestion::byQSE($child->qse->id, $child->qse->random)->published()->active();
        $checkAnswerIDs = [];
        if ($incompleteQuiz) {
            $checkAnswerIDs = $incompleteQuiz->eventUserQSEAnswers()->pluck('qse_question_answer_id')->toArray();
        }
    @endphp
    @if($child->qse->qse_type_id == 4)
        @php

            $arrayRoles = explode(',', $child->qse->evaluatee_roles);
            $eventUsers = \App\Models\CourseInstance\EventUser::whereIn('role_id', $arrayRoles)
                            ->where('event_id', $event->id)
                            ->where('user_id', '<>', auth()->user()->id)
                            ->where('status_id', 1)
                            ->join('users', 'users.id', '=', 'user_id')
                            ->orderBy('users.first_name')
                            ->orderBy('users.last_name')
                            ->get();
        @endphp
        @foreach($eventUsers as $eventUser)
            <div class="card">
                <div class="card-header">
                    {{ trans('labels.qse.eval_of_person', ['qse' => $child->menu_title, 'person' => "{$eventUser->first_name} {$eventUser->last_name}"])}}
                </div>
                <div class="card-body">
                    @include('courseInstance.events.qse.partials.all-questions')
                </div>
            </div>
        @endforeach
    @else
        @include('courseInstance.events.qse.partials.all-questions')
    @endif
    <hr/>
    <div class="d-flex justify-content-end align-items-center">
        <p class="text-muted mb-0">
            @if($incompleteQuiz)
                {{ trans('labels.qse.last_saved_at', ['at' => count($incompleteQuiz->eventUserQSEAnswers) ? last($incompleteQuiz->eventUserQSEAnswers)[0]->created_at->format('Y-m-d H:i a') : $incompleteQuiz->created_at->format('Y-m-d H:i a')]) }}
            @endif
        </p>
        @if($incompleteQuiz)
            <span class="mx-1">|</span>
        @endif
        <button type="submit"
                class="btn btn-success btn-sm"
                id="qse-submit-{{$child->id}}-quiz-all"
                data-presentation-type-id="{{ $child->qse->presentation_type_id }}"
                data-event-id="{{ $event->id }}"
                data-qse-id="{{ $child->qse->id }}"
                @if($incompleteQuiz)
                    data-event-user-qse-id="{{$incompleteQuiz->id}}"
                @endif
        >
            {{ trans('buttons.qse.submit_quiz') }}
        </button>
    </div>
</div>

<script>
    function submitForm(form, written_response = '') {
        form = $(form);

        let tempData = {
            presentation_type_id: form.data('presentation-type-id'),
            event_id: form.data('event-id'),
            qse_id: form.data('qse-id'),
            event_user_qse_id: form.data('event-user-qse-id'),
            written_response
        };

        $.map(form.serializeArray(), function (n, i) {
            if (tempData[n['name']] instanceof Array) {
                tempData[n['name']].push(n['value'])
            } else if (n['name'] in tempData) {
                tempData[n['name']] = [tempData[n['name']], n['value']]
            } else {
                tempData[n['name']] = n['value'];
            }
        });

        $.ajax({
            url: "{{ route('qse-submit-answer') }}",
            method: 'POST',
            data: tempData,
            success: function (data, textStatus, jqXHR) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    }

    $(function () {

        $('#qse-submit-{{$child->id}}-quiz-all').on('click', function (e) {
            e.preventDefault();
            let btn = $(this);

            let tempData = {
                presentation_type_id: btn.data('presentation-type-id'),
                event_id: btn.data('event-id'),
                qse_id: btn.data('qse-id'),
                event_user_qse_id: btn.data('event-user-qse-id'),
                qse_type_id: {{ $child->qse->qse_type_id }},
                evaluatee_roles: '{{ $child->qse->evaluatee_roles }}'
            };
            console.log(tempData);
            btn.attr('disabled', true);
            btn.text('{{ trans('buttons.qse.submitting') }}')
            $.ajax({
                url: "{{ route('qse-submit-quiz') }}",
                method: 'POST',
                data: tempData,
                success: function (_data, textStatus, jqXHR) {
                    window.location = '{{ route('event_dashboard', ['id' => $event->id, 'tab' => strtolower($viewerType->description), 'qs' => "cc-$courseContent->id-$child->id"]) }}';
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                }
            });
        });

        @if(!$incompleteQuiz)
            $('#btn-take-{{ $child->id }}-quiz-all').on('click', function (e) {
                e.preventDefault();

                let tempData = {
                    presentation_type_id: {{ $child->qse->presentation_type_id }},
                    event_id: {{ $event->id }},
                    qse_id: {{ $child->qse->id }},
                };

                let begin = $(this);
                begin.attr('disabled', true);
                begin.text('{{ trans('buttons.qse.please_wait') }}');

                $.ajax({
                    url: "{{ route('qse-take-quiz') }}",
                    method: 'POST',
                    data: tempData,
                    success: function (_data, textStatus, jqXHR) {
                        $('#qse-submit-{{$child->id}}-quiz-all').attr('data-event-user-qse-id', _data.id);
                        $('#questions-form-{{ $child->id }}-all').find('form').each(function (){
                            $(this).attr('data-event-user-qse-id', _data.id)
                        });
                        $('#questions-form-{{ $child->id }}-all').removeClass('d-none');
                        $('#instructions-{{ $child->id }}-all').addClass('d-none');
                        $('#results-{{ $child->id }}-all').addClass('d-none');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                        begin.removeAttr('disabled');
                        begin.text('{{ trans('buttons.qse.take_quiz') }}');
                    }
                });
            });
        @endif
    });
</script>