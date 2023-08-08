@extends('frontend.layouts.print-preview')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h4>{{ $courseContent->menu_title }}</h4>
            <small>{{ $courseContent->course->name }}</small>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-block" onclick="window.print()">
                {{ trans('buttons.qse.print') }}
            </button>
        </div>
    </div>
    <hr/>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title d-flex">
                {{ trans('labels.qse.questions') }}
                @if($qse->qse_type_id == 1)
                    <div class="form-group form-check ml-3">
                        <input type="checkbox" class="form-check-input" id="show_answers" name="show_answers">
                        <label class="form-check-label" for="show_answers">
                            {{ trans('labels.qse.show_correct_answers') }}
                        </label>
                    </div>
                @endif
                <div class="form-group form-check ml-3">
                    <input type="checkbox" class="form-check-input" id="show_retired">
                    <label class="form-check-label" for="show_retired">
                        {{ trans('labels.qse.show_retired_questions') }}
                    </label>
                </div>
            </h4>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm">
                <tbody id="questions-sortable-list">
                @php
                    $qn = 1;
                @endphp
                @foreach($qse->qseQuestions as $q)
                    <tr id="{{ $q->id }}" class="{{ $q->retired_date != null ? 'table-active retired d-none' : '' }}">
                        <td class="text-center" style="width: 80px">
                            {{$q->retired_date != null ? '' : ($qn++) . '.'}}
                        </td>
                        <td class="align-middle">
                            <div class="qse-question-text">
                                {!! $q->text !!}
                            </div>
                            @if($qse->qse_type_id == 1)
                                <ol class="fa-ul" style="list-style-type: upper-alpha !important;">
                                    @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $ans)
                                        <li>
                                            <span class="fa-li answers d-none" style="left: -3em !important;">
                                                <i class="fas fa-{{ $ans->correct ? 'check text-success' : 'times' }}"></i>
                                            </span>
                                            {{ $ans->text }}
                                            @if($ans->feedback)
                                                <div class="alert alert-light mt-2">{!! $ans->feedback !!}</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                @if($q->answer_type_id == 10)
                                    <div class="answers d-none">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                   id="yes_{{$q->id}}" value="yes">
                                            <label class="form-check-label" for="yes_{{$q->id}}">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                   id="no_{{$q->id}}" value="no">
                                            <label class="form-check-label" for="no_{{$q->id}}">No</label>
                                        </div>
                                    </div>
                                @elseif($q->answer_type_id == 4)
                                    <div class="answers d-none">
                                        <textarea class="form-control" rows="3" disabled></textarea>
                                    </div>
                                @elseif($q->answer_type_id == 7)
                                    @php(list($lscl, $lscc, $lscr) = array_pad(explode(',', $q->likert_caption), 3, null))
                                    <div class="answers d-none py-3">
                                        <div class="d-flex justify-content-between">
                                            <label>{{ $lscl }}</label>
                                            <label>{{ $lscc }}</label>
                                            <label>{{ $lscr }}</label>
                                        </div>
                                        <div class="d-block p-2 my-2"
                                             style="background-image: linear-gradient(to right, red, green);"></div>
                                        <div class="d-flex justify-content-between mb-3">
                                            @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $answer)
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                           type="radio"
                                                           name="name_{{$q->id}}_radio"
                                                           id="radio_q{{$q->id}}_a{{$answer->id}}"
                                                           value="{{$answer->id}}"
                                                    />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($q->answer_type_id == 1)
                                    <div class="answers d-none py-3">
                                        <table class="table table-striped table-sm">
                                            <tbody>
                                            @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $answer)
                                                <tr>
                                                    <td style="width: 30px" class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                   type="checkbox"
                                                                   name="q_{{$q->id}}answer[]"
                                                                   value="{{$answer->id}}"
                                                                   id="checkbox_q{{$q->id}}_a{{$answer->id}}"
                                                                   data-q-id="{{$q->id}}"
                                                            >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <label class="form-check-label"
                                                               for="checkbox_q{{$q->id}}_a{{$answer->id}}">
                                                            {{ $answer->text }}
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif($q->answer_type_id == 2)
                                    <div class="answers d-none py-3">
                                        <table class="table table-striped table-sm">
                                            <tbody>
                                            @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $answer)
                                                <tr>
                                                    <td style="width: 30px"
                                                        class="text-center">{{ range('A', 'Z')[$loop->index] }}.
                                                    </td>

                                                    <td style="width: 30px" class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                   type="radio"
                                                                   name="q_{{$qse->id}}_{{$q->id}}_answer"
                                                                   id="radio_q{{$q->id}}_a{{$answer->id}}"
                                                                   value="{{$answer->id}}"
                                                                   data-q-id="{{$q->id}}"
                                                            >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <label class="form-check-label"
                                                               for="radio_q{{$q->id}}_a{{$answer->id}}">
                                                            {{ $answer->text }}
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif($q->answer_type_id == 3)
                                    <div class="answers d-none py-3">
                                        <div class="form-group">
                                            <ul>
                                                @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $answer)
                                                    <li>{{ $answer->text }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @elseif($q->answer_type_id == 8)
                                    <div class="answers d-none py-3">
                                        <p class="mb-0 text-center">1</p>
                                        <div class="d-flex">
                                            1 <input type="range" class="form-control-range mx-1"
                                                     id="answer_{{$q->id}}_range" min="1" max="10" value="1"/> 10
                                        </div>
                                    </div>
                                @else
                                    <ol class="fa-ul" style="list-style-type: upper-alpha !important;">
                                        @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $ans)
                                            <li>
                                            <span class="fa-li answers d-none" style="left: -3em !important;">
                                                <i class="fas fa-{{ $ans->correct ? 'check text-success' : 'times' }}"></i>
                                            </span>
                                                {{ $ans->text }}
                                                @if($ans->feedback)
                                                    <div class="alert alert-light mt-2">{!! $ans->feedback !!}</div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script>
        $(function () {
            @if($qse->qse_type_id == 1)
            $('#show_answers').change(function (e) {
                if ($(this).is(':checked')) {
                    $('#questions-sortable-list').find('.answers').removeClass('d-none')
                } else {
                    $('#questions-sortable-list').find('.answers').addClass('d-none')
                }
            });
            @else
            $('#questions-sortable-list').find('.answers').removeClass('d-none');
            @endif

            $('#show_retired').change(function (e) {
                if ($(this).is(':checked')) {
                    $(this).parent('label').addClass('btn-secondary');
                    $(this).parent('label').removeClass('btn-outline-secondary');
                    $('#questions-sortable-list').find('.retired').removeClass('d-none')
                } else {
                    $(this).parent('label').removeClass('btn-secondary');
                    $(this).parent('label').addClass('btn-outline-secondary');
                    $('#questions-sortable-list').find('.retired').addClass('d-none')
                }
            })

        })
    </script>
@endsection