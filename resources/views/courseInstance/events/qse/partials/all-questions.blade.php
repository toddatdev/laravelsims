@foreach($questions->get() as $q)
    <form class="qse-question-{{$child->qse->id}}-submit-answer-all" method="POST" action="#"
          id="qse-question-{{$child->qse->id}}-submit-answer-{{$q}}-all"
          data-presentation-type-id="{{ $child->qse->presentation_type_id }}"
          data-event-id="{{ $event->id }}"
          data-qse-id="{{ $child->qse->id }}"
          @if($incompleteQuiz)
          data-event-user-qse-id="{{$incompleteQuiz->id}}"
            @endif
    >
        @csrf
        @php
            $correctAnswersCount = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($q->id)->correct()->count();
            $questionAnswers = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($q->id)->get();
            $q_answer_key = "q_answer_".($correctAnswersCount > 1 ? 'checkbox' : 'radio')."_" . $q->id;
        @endphp

        <div class="card qse-question{{$child->qse->id}}-all">
            @if($child->qse->qse_type_id == 1)
                <div class="card-header bg-light">
                    <h4 class="card-title font-weight-bold">{{ trans('labels.qse.question_no', ['no' => $loop->index + 1]) }}</h4>
                </div>
            @endif
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

                <input type="hidden" name="q_id" value="{{ $q->id }}"/>
                <input type="hidden" name="q_answer_key" value="{{ $q_answer_key }}" id="q_answer_key_{{$q->id}}"/>
                <input type="hidden" name="event_id" value="{{ $event->id }}"/>
                <input type="hidden" name="qse_type_id" value="{{ $child->qse->qse_type_id }}"/>
                @if($child->qse->qse_type_id !== 1)
                    @if($child->qse->qse_type_id == 4)
                        <input type="hidden" name="evaluatee_id" value="{{ $eventUser->id }}"/>
                        <input type="hidden" name="evaluatee_roles" value="{{ $child->qse->evaluatee_roles}}"/>
                    @endif
                    @php(list($lscl, $lscc, $lscr) = array_pad(explode(',', $q->likert_caption), 3, null))
                    @switch($q->answer_type_id)
                        @case(1)
                        <script>
                            $(function() {
                                $('#q_answer_key_{{$q->id}}').val("{{str_replace('radio', 'checkbox', $q_answer_key)}}");
                            });
                        </script>
                        {!! trans('labels.qse.select_any_one', ['any_one' => trans('labels.qse.any') ]) !!}
                        <br/>
                        <table class="table table-striped table-sm">
                            <tbody>
                            @foreach($questionAnswers as $answer)
                                <tr>
                                    <td style="width: 30px" class="text-center">{{ range('A', 'Z')[$loop->index] }}.</td>
                                    <td style="width: 30px" class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="{{str_replace('radio', 'checkbox', $q_answer_key)}}"
                                                   value="{{$answer->id}}"
                                                   id="checkbox_q{{$q->id}}_a{{$answer->id}}"
                                                   onchange="submitForm(this.form)"
                                                   data-q-id="{{$q->id}}"
                                                    {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <label class="form-check-label" for="checkbox_q{{$q->id}}_a{{$answer->id}}">
                                            {{ $answer->text }}
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @break
                        @case(2)
                        {!! trans('labels.qse.select_any_one', ['any_one' => trans('labels.qse.one')]) !!}
                        <br/>
                        <table class="table table-striped table-sm">
                            <tbody>
                            @foreach($questionAnswers as $answer)
                                <tr>
                                    <td style="width: 30px" class="text-center">{{ range('A', 'Z')[$loop->index] }}.</td>
                                    <td style="width: 30px" class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="{{$q_answer_key}}"
                                                   id="radio_q{{$q->id}}_a{{$answer->id}}"
                                                   value="{{$answer->id}}"
                                                   onchange="submitForm(this.form)"
                                                   data-q-id="{{$q->id}}"
                                                    {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <label class="form-check-label" for="radio_q{{$q->id}}_a{{$answer->id}}">
                                            {{ $answer->text }}
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @break
                        @case(3)
                        {!! trans('labels.qse.select_any_one', ['any_one' => trans('labels.qse.one')]) !!}
                        <br/>
                        <div class="form-group">
                            <select class="form-control" name="select_{{$q_answer_key}}">
                                <option value=""></option>
                                @foreach($questionAnswers as $answer)
                                    <option value="{{ $loop->index }}" {{ in_array($answer->id, $checkAnswerIDs) ? 'selected' : '' }}>{{ $answer->text }}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach($questionAnswers as $answer)
                            <div class="form-check d-none">
                                <input class="form-check-input"
                                       type="radio"
                                       name="{{$q_answer_key}}"
                                       id="radio_q{{$q->id}}_a{{$answer->id}}"
                                       value="{{$answer->id}}"
                                       data-q-id="{{$q->id}}"
                                        {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                >
                            </div>
                        @endforeach
                        <script>
                            $(function () {
                                $("select[name=select_{{$q_answer_key}}]").change(function(e){
                                    let val = $(this).find(':selected').val();

                                    if (val !== ""){
                                        $($('input[type=radio][name={{$q_answer_key}}]')[val]).attr('checked', true);
                                        submitForm(this.form);
                                    }

                                });
                            });
                        </script>
                        @break
                        @case(4)
                        <textarea class="form-control" rows="3" name="textarea_{{$q_answer_key}}"></textarea>
                        @foreach($questionAnswers as $answer)
                            <div class="form-check d-none">
                                <input class="form-check-input"
                                       type="radio"
                                       name="{{$q_answer_key}}"
                                       id="radio_q{{$q->id}}_a{{$answer->id}}"
                                       value="{{$answer->id}}"
                                       data-q-id="{{$q->id}}"
                                        {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                >
                            </div>
                        @endforeach
                        <script>
                            $(function () {
                                $("textarea[name=textarea_{{$q_answer_key}}]").focusout(function(e){
                                    let val = $(this).val();

                                    if (val !== ""){
                                        $($('input[type=radio][name={{$q_answer_key}}]')[0]).attr('checked', true);
                                        submitForm(this.form, val);
                                    }

                                });
                            });
                        </script>
                        @break
                        @case(7)
                        <div class="d-flex justify-content-between">
                            <label>{{ $lscl }}</label>
                            <label>{{ $lscc }}</label>
                            <label>{{ $lscr }}</label>
                        </div>
                        <div class="d-block p-2 my-1" style="background-image: linear-gradient(to right, red, green);"></div>
                        <div class="d-flex justify-content-between mb-3">
                            @foreach($questionAnswers as $answer)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="{{$q_answer_key}}"
                                           id="radio_q{{$q->id}}_a{{$answer->id}}"
                                           value="{{$answer->id}}"
                                           onchange="submitForm(this.form)"
                                           data-q-id="{{$q->id}}"
                                            {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                    >
                                </div>
                            @endforeach
                        </div>
                        @break
                        @case(8)
                        <p class="mb-0 text-center" id="answer_{{$q_answer_key}}_range_label">1</p>
                        <div class="d-flex">
                            1 <input
                                    type="range"
                                    class="form-control-range mx-1"
                                    id="answer_{{$q->id}}_range"
                                    min="1"
                                    max="10"
                                    name="range_{{$q_answer_key}}"
                                    data-q-id="{{$q->id}}"
                                    value="1"
                            /> 10
                        </div>
                        @foreach($questionAnswers as $answer)
                            <div class="form-check d-none">
                                <input class="form-check-input"
                                       type="radio"
                                       name="{{$q_answer_key}}"
                                       id="radio_q{{$q->id}}_a{{$answer->id}}"
                                       value="{{$answer->id}}"
                                       data-q-id="{{$q->id}}"
                                        {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                >
                            </div>
                        @endforeach
                        <script>
                            $(function () {
                                $("input[type=range][name=range_{{$q_answer_key}}]").change(function(e){
                                    let val = $(this).val();
                                    $('#answer_{{$q_answer_key}}_range_label').text(val);
                                    $($('input[type=radio][name={{$q_answer_key}}]')[val - 1]).attr('checked', true);
                                    submitForm(this.form)
                                });
                            });
                        </script>
                        @break
                        @case(10)
                        {!! trans('labels.qse.select_any_one', ['any_one' => trans('labels.qse.one')]) !!}
                        <br/>
                        <div class="form-group">
                            <select class="form-control" name="select_{{$q_answer_key}}">
                                <option value=""></option>
                                @foreach($questionAnswers as $answer)
                                    <option value="{{ $loop->index }}">{{ $answer->text }}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach($questionAnswers as $answer)
                            <div class="form-check d-none">
                                <input class="form-check-input"
                                       type="radio"
                                       name="{{$q_answer_key}}"
                                       id="radio_q{{$q->id}}_a{{$answer->id}}"
                                       value="{{$answer->id}}"
                                       data-q-id="{{$q->id}}"
                                        {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                >
                            </div>
                        @endforeach
                        <script>
                            $(function () {
                                $("select[name=select_{{$q_answer_key}}]").change(function(e){
                                    let val = $(this).find(':selected').val();

                                    if (val !== ""){
                                        $($('input[type=radio][name={{$q_answer_key}}]')[val]).attr('checked', true);
                                        submitForm(this.form);
                                    }

                                });
                            });
                        </script>
                        @break
                    @endswitch
                @else
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
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="{{$q_answer_key}}"
                                                   value="{{$answer->id}}"
                                                   id="checkbox_q{{$q->id}}_a{{$answer->id}}"
                                                   onchange="submitForm(this.form)"
                                                   data-q-id="{{$q->id}}"
                                                    {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                            >
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
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="{{$q_answer_key}}"
                                                   id="radio_q{{$q->id}}_a{{$answer->id}}"
                                                   value="{{$answer->id}}"
                                                   onchange="submitForm(this.form)"
                                                   data-q-id="{{$q->id}}"
                                                    {{ in_array($answer->id, $checkAnswerIDs) ? 'checked' : '' }}
                                            >
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
                @endif
            </div>
        </div>
    </form>
@endforeach