@extends('frontend.layouts.print-preview')

@section('content')

    @foreach($eventUserQSEs as $eventUserQse)
        @php
            $result = $eventUserQse->result;
        @endphp
        <h5 class="text-bold">{{ trans('labels.qse.score_for_this_quiz', ['score' => $result['correct'], 'total' => $result['total'], 'percent' => round(($result['correct'] / $result['total']) * 100)]) }}</h5>
        <p>{!! $eventUserQse->FormattedCreatedAtTz !!}</p>
        @php
            $eventUserQSEAttemptQuestions = \App\Models\CourseContent\QSE\EventUserQSEAnswer::where('event_user_qse_id', $eventUserQse->id)->distinct('course_qse_question_id')->pluck('course_qse_question_id');
        @endphp
        @foreach(\App\Models\CourseContent\QSE\QSEQuestion::byQSE($eventUserQse->qse->id)->whereIn('id', $eventUserQSEAttemptQuestions)->published()->get() as $question)
            @php
                $correctAnswersCount = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($question->id)->correct()->count();
                $correctAnswersIds = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($question->id)->correct()->pluck('id');
                $questionAnswers = \App\Models\CourseContent\QSE\QSEQuestionAnswer::byQuestion($question->id)->get();

                $q_answer_key = "q_answer_" . ($correctAnswersCount > 1 ? 'checkbox' : 'radio') . $eventUserQse->id . "_" . $question->id;

                $correctAttemptCount = \App\Models\CourseContent\QSE\EventUserQSEAnswer::where([
                                                'event_user_qse_id' => $eventUserQse->id,
                                                'course_qse_question_id' => $question->id,
                                            ])->whereIn('qse_question_answer_id', $correctAnswersIds)->count();
                $inCorrectAttemptCount = \App\Models\CourseContent\QSE\EventUserQSEAnswer::where([
                                                'event_user_qse_id' => $eventUserQse->id,
                                                'course_qse_question_id' => $question->id,
                                            ])->whereNotIn('qse_question_answer_id', $correctAnswersIds)->count();
            @endphp
            <div class="card">
                <div class="card-header">
                    <p class="card-title">
                        {{ trans('labels.qse.question_no', ['no' => $loop->index + 1]) }}
                        <span class="badge badge-{{$correctAnswersCount == $correctAttemptCount ? 'success' : 'danger'}} ml-3"
                              id="rr-{{$eventUserQse->id}}-{{$question->id}}">
                                                @if($correctAnswersCount == $correctAttemptCount)
                                {{ trans('alerts.frontend.qse.results_report_correct') }}
                            @elseif($correctAttemptCount > 0 || $inCorrectAttemptCount > 0)
                                {{ trans('alerts.frontend.qse.results_report_incorrect') }}
                            @else
                                {{ trans('alerts.frontend.qse.results_report_incorrect_no_answer_selected') }}
                            @endif
                                            </span>
                    </p>
                </div>
                <div class="card-body">
                    {!! $question->text !!}
                    <br/>

                    <table class="table table-striped table-sm">
                        <tbody>
                        @foreach($questionAnswers as $answer)
                            @php
                                $checked = \App\Models\CourseContent\QSE\EventUserQSEAnswer::where([
                                                'event_user_qse_id' => $eventUserQse->id,
                                                'course_qse_question_id' => $question->id,
                                                'qse_question_answer_id' => $answer->id,
                                            ])->exists();
                            @endphp

                            <tr>
                                <td style="width: 45px" class="text-center">
                                    {{ range('A', 'Z')[$loop->index] }}.
                                </td>
                                @if($correctAnswersCount > 1)
                                    <td style="width: 35px" class="text-center">
                                        <input class="form-check-input" type="checkbox"
                                               name="{{$q_answer_key}}"
                                               value="{{$answer->id}}"
                                               id="checkbox_q{{$question->id}}_a{{$answer->id}}"
                                               disabled {{ $checked ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <label class="form-check-label"
                                               for="checkbox_q{{$question->id}}_a{{$answer->id}}">
                                            {{ $answer->text }}
                                            @if($answer->correct)
                                                <span class="badge badge-success">{{ trans('alerts.frontend.qse.results_report_correct') }}</span>
                                            @elseif(!$answer->correct && $checked)
                                                <span class="badge badge-danger">{{ trans('alerts.frontend.qse.results_report_incorrect') }}</span>
                                            @endif

                                            @if($answer->feedback)
                                                <div class="alert alert-light mt-2" role="alert">
                                                    {!! $answer->feedback !!}
                                                </div>
                                            @endif
                                        </label>
                                    </td>
                                @else
                                    <td style="width: 35px" class="text-center">
                                        <input class="form-check-input" type="radio"
                                               name="{{$q_answer_key}}"
                                               id="radio_q{{$question->id}}_a{{$answer->id}}"
                                               value="{{$answer->id}}"
                                               disabled {{ $checked ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <label class="form-check-label"
                                               for="radio_q{{$question->id}}_a{{$answer->id}}">
                                            {{ $answer->text }}
                                            @if($answer->correct)
                                                <span class="badge badge-success">{{ trans('alerts.frontend.qse.results_report_correct') }}</span>
                                            @elseif(!$answer->correct && $checked)
                                                <span class="badge badge-danger">{{ trans('alerts.frontend.qse.results_report_incorrect') }}</span>
                                            @endif

                                            @if($answer->feedback)
                                                <div class="alert alert-light mt-2" role="alert">
                                                    {!! $answer->feedback !!}
                                                </div>
                                            @endif
                                        </label>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
    @endforeach
@endsection

@section('after-scripts')
@endsection