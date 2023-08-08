@php
    $eventUserQSE = count($eventUserQSEs) ? $eventUserQSEs[0] : null;
    $result = $eventUserQSE ? $eventUserQSE->result : null;
@endphp
<div class="" id="results-{{ $child->id }}-{{ $quiz_type }}">
    @if($child->qse->allow_multiple_submits && $eventUserQSE)
        <h5 class="mt-5"><i class="far fa-history text-muted"></i> {{ trans('labels.qse.attempt_history') }}</h5>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">{{ trans('labels.qse.attempt') }}</th>
                @if($child->qse->feedback_type_id == 2 || $child->qse->feedback_type_id == 3)
                    <th scope="col">{{ trans('labels.qse.score') }}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($eventUserQSEs as $euqs)
                <tr>
                    <td>
                        @if($child->qse->feedback_type_id == 3)
                            <a href="{{ route('qse-results-report', $euqs->id)}}"
                               data-toggle="modal"
                               data-target="#resultReport{{$euqs->id}}Modal">{{ trans('labels.qse.attempt_no', ['no' => $loop->count - $loop->index]) }}</a>
                        @else
                            {{ trans('labels.qse.attempt_no', ['no' => $loop->count - $loop->index]) }}
                        @endif
                        <span class="ml-2">{!! $euqs->FormattedCreatedAtTz !!}</span><span
                                class="ml-5 text-bold">{{ $loop->index == 0 ? trans('labels.qse.latest') : '' }}</span>
                    </td>
                    @if($child->qse->feedback_type_id == 2 || $child->qse->feedback_type_id == 3)
                        <td>
                            {{ trans('labels.qse.out_of', ['score' => $euqs->result['correct'], 'total' => $euqs->result['total'], 'percent' => round(($euqs->result['correct'] / $euqs->result['total']) * 100)])}}
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--        @if($child->qse->feedback_type_id != 3 )--}}
        {{--            <p class="pl-3"><i class="fa fa-info-circle text-muted"></i> {{ trans('labels.qse.correct_answers_are_not') }}</p>--}}
        {{--        @endif--}}
    @endif
    @if($eventUserQSE)
        @if(!$child->qse->allow_multiple_submits)
            <blockquote class="quote-success bg-light">
                <h5 class="text-success">{!! trans('alerts.frontend.qse.quiz_submitted_successfully')  !!}</h5>
                @endif
                @if($child->qse->qse_type_id == 1)
                    @if($child->qse->feedback_type_id == 1)
                        <p class="pl-3"><i
                                    class="fa fa-info-circle text-muted"></i> {{ trans('labels.qse.correct_answers_are_not') }}
                        </p>
                    @elseif(!$child->qse->allow_multiple_submits && ($child->qse->feedback_type_id == 2 || $child->qse->feedback_type_id == 3))
                        <h5 class="text-secondary">{{ trans('labels.qse.score_for_this_quiz', ['score' => $result['correct'], 'total' => $result['total'], 'percent' => round(($result['correct'] / $result['total']) * 100)]) }}</h5>
                        <p>{!! $eventUserQSE->FormattedCreatedAtTz !!}</p>
                        @if($child->qse->feedback_type_id == 3)
                            <a href="{{ route('qse-results-report', $eventUserQSE->id) }}"
                               class="btn btn-link font-weight-bold pl-0"
                               data-toggle="modal"
                               data-target="#resultReport{{$eventUserQSE->id}}Modal"
                               style="color: #007bff !important; font-size: 18px">
                                {{ trans('labels.qse.results_report') }}
                            </a>
                        @else
                            <p class="pl-3"><i
                                        class="fa fa-info-circle text-muted"></i> {{ trans('labels.qse.correct_answers_are_not') }}
                            </p>
                        @endif
                    @endif
                @endif
                @if(!$child->qse->allow_multiple_submits)
            </blockquote>
        @endif
    @endif
</div>