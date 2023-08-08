<div id="instructions-{{ $child->id }}-one">
    @if((count($eventUserQSEs) == 0 || $child->qse->allow_multiple_submits) && $child->qse->instructions)
        <h5><i class="far fa-info-square text-muted"></i> {{ trans('labels.qse.instructions') }}</h5>
        <p>{!! $child->qse->instructions !!}</p>
    @endif
    @if(count($eventUserQSEs) == 0 || $child->qse->allow_multiple_submits)
        <div class="d-flex">
            <button
                class="btn btn-primary mx-auto"
                data-toggle="modal"
                data-target="#beginModal{{ $child->id }}-one"
                onclick="$('#beginModal{{ $child->id }}-one').detach().appendTo('body')"
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
@include('courseInstance.events.qse.partials.results', ['quiz_type' => 'one'])

<div class="modal fade" id="beginModal{{ $child->id }}-one" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="beginModal{{ $child->id }}-oneLabel">{{ trans('labels.qse.attention') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ trans('labels.qse.attention_text') }}
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-primary"
                        data-dismiss="modal"
                        id="btn-{{ $child->id }}-begin-one"
                        @if($incompleteQuiz)
                        onclick="$('#beginModal{{ $child->id }}-one').modal('hide');
                                $('#questions-section-{{ $child->id }}-one').removeClass('d-none');
                                $('#instructions-{{ $child->id }}-one').addClass('d-none');
                                $('#results-{{ $child->id }}-one').addClass('d-none');"
                        @endif
                >
                    {{ trans('buttons.qse.begin') }}
                </button>
            </div>
        </div>
    </div>
</div>
<section id="questions-section-{{ $child->id }}-one" class="d-none">
    @if($child->qse->instructions)
        <h5 class="font-weight-bold">{{ trans('labels.qse.instructions') }}</h5>
        <p>{!! $child->qse->instructions !!}</p>
    @endif

    @php
        $questions = \App\Models\CourseContent\QSE\QSEQuestion::byQSE($child->qse->id, $child->qse->random)->published()->active();
        if ($incompleteQuiz) {
            $questions = $questions->whereNotIn('id', $incompleteQuiz->eventUserQSEAnswers()->pluck('course_qse_question_id'));
        }
    @endphp
    @foreach($questions->get() as $q)
        <form
                method="POST"
                action="#"
                class="{{ $loop->index == 0 ? '' : 'd-none' }} question-form-{{ $child->qse->id }}-one"
        >
            @php
                $correctAnswersCount = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($q->id)->correct()->count();
                $questionAnswers = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($q->id)->get();
                $q_answer_key = "q_answer_".($correctAnswersCount > 1 ? 'checkbox' : 'radio')."_" . $q->id;
            @endphp

            <input type="hidden" name="q_id" value="{{ $q->id }}"/>
            <input type="hidden" name="q_answer_key" value="{{ $q_answer_key }}"/>

            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="card-title font-weight-bold">{{ trans('labels.qse.question_no', ['no' => "$loop->iteration / $loop->count"]) }}</h4>
                </div>
                <div class="card-body">
                    @if($q->answer_type_id == 5)
                        <h1>{!! $q->text !!}</h1>
                    @elseif($q->answer_type_id == 6)
                        <h2>{!! $q->text !!}</h2>
                    @else
                        {!! $q->text !!}
                    @endif
                    <br/>
                    <br/>
                    {!! trans('labels.qse.select_any_one', ['any_one' => $correctAnswersCount > 1 ? trans('labels.qse.any') : trans('labels.qse.one')]) !!}
                    <br/>
                    <table class="table table-striped table-sm">
                        <tbody>
                        @foreach($questionAnswers as $answer)
                            <tr>
                                <td style="width: 30px" class="text-center">{{ range('A', 'Z')[$loop->index] }}.</td>
                                @if($correctAnswersCount > 1)
                                    <td style="width: 30px" class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="{{$q_answer_key}}"
                                                   value="{{$answer->id}}" id="checkbox_q{{$q->id}}_a{{$answer->id}}">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="form-check-label" for="checkbox_q{{$q->id}}_a{{$answer->id}}">
                                            {{ $answer->text }}
                                        </label>
                                    </td>
                                @else
                                    <td style="width: 30px" class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="{{$q_answer_key}}"
                                                   id="radio_q{{$q->id}}_a{{$answer->id}}" value="{{$answer->id}}">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="form-check-label" for="radio_q{{$q->id}}_a{{$answer->id}}">
                                            {{ $answer->text }}
                                        </label>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-success btn-sm ml-auto" id="qse-question-submit-{{$child->id}}">
                    {{ trans('buttons.qse.submit_answer') }}
                </button>
            </div>
        </form>
    @endforeach
</section>

<script>
    $(function () {
        let data = {
            presentation_type_id: {{ $child->qse->presentation_type_id }},
            event_id: {{ $event->id }},
            qse_id: {{ $child->qse->id }},
        };

        @if($incompleteQuiz)
            data['event_user_qse_id'] = {{$incompleteQuiz->id}};
        @endif

        $(document).on('submit', '.question-form-{{ $child->qse->id }}-one', function (e) {
            e.preventDefault();
            console.log(data);
            let tempData = {...data};
            let form = $(this);
            let submit = form.find('button[type="submit"]');

            $.map(form.serializeArray(), function (n, i) {
                if (tempData[n['name']] instanceof Array) {
                    tempData[n['name']].push(n['value'])
                } else if (n['name'] in tempData) {
                    tempData[n['name']] = [tempData[n['name']], n['value']]
                } else {
                    tempData[n['name']] = n['value'];
                }
            });

            {{--if ($('.question-form-{{ $child->qse->id }}-one').length > 1) {--}}
            {{--    form.siblings('form').first().removeClass('d-none');--}}
            {{--    form.remove();--}}
            {{--    return;--}}
            {{--}--}}

            submit.attr('disabled', true);
            submit.text('{{ trans('buttons.qse.submitting') }}');

            $.ajax({
                url: "{{ route('qse-submit-answer') }}",
                method: 'POST',
                data: tempData,
                success: function (data, textStatus, jqXHR) {
                    if ($('.question-form-{{ $child->qse->id }}-one').length === 1) {
                        $.ajax({
                            url: "{{ route('qse-submit-quiz') }}",
                            method: 'POST',
                            data,
                            success: function (_data, textStatus, jqXHR) {
                                $('#instructions-{{ $child->id }}-one').addClass('d-none');
                                $('#questions-section-{{ $child->id }}-one').addClass('d-none');
                                window.location = '{{ route('event_dashboard', ['id' => $event->id, 'tab' => strtolower($viewerType->description), 'qs' => "cc-$courseContent->id-$child->id"]) }}';
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(jqXHR, textStatus, errorThrown);
                            }
                        });
                    } else {
                        form.siblings('form').first().removeClass('d-none');
                    }
                    form.remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                    submit.removeAttr('disabled');
                    submit.text('{{ trans('buttons.qse.submit_answer') }}');
                }
            });
        });

        $('#qse-submit-{{$child->id}}-quiz-one').on('click', function (e) {
            e.preventDefault();
            let btn = $(this);
            btn.attr('disabled', true);
            btn.text('{{ trans('buttons.qse.submitting') }}')
            $.ajax({
                url: "{{ route('qse-submit-quiz') }}",
                method: 'POST',
                data,
                success: function (_data, textStatus, jqXHR) {
                    $('#instructions-{{ $child->id }}-one').addClass('d-none');
                    $('#questions-section-{{ $child->id }}-one').addClass('d-none');
                    window.location = '{{ route('event_dashboard', ['id' => $event->id, 'tab' => strtolower($viewerType->description), 'qs' => "cc-$courseContent->id-$child->id"]) }}';
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                    btn.removeAttr('disabled');
                    btn.text('{{ trans('buttons.qse.submit_quiz') }}')
                }
            });
        });

        @if(!$incompleteQuiz)
        $('#btn-{{ $child->id }}-begin-one').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let begin = $(this);
            begin.attr('disabled', true);
            begin.text('{{ trans('buttons.qse.beginning') }}');

            $.ajax({
                url: "{{ route('qse-take-quiz') }}",
                method: 'POST',
                data,
                success: function (_data, textStatus, jqXHR) {
                    data['event_user_qse_id'] = _data.id;
                    $('#beginModal{{ $child->id }}-one').modal('hide');
                    $('#questions-section-{{ $child->id }}-one').removeClass('d-none');
                    $('#instructions-{{ $child->id }}-one').addClass('d-none');
                    $('#results-{{ $child->id }}-one').addClass('d-none');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                    begin.removeAttr('disabled');
                    begin.text('{{ trans('buttons.qse.begin') }}');
                }
            });
        });
        @endif
    });
</script>