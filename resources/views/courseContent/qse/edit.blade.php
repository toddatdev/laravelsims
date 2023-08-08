@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('after-styles')
    <style>
        .highlight {
            background-color: #eeeeee !important;
        }
    </style>
@stop
@section('page-header')
    {{--Only display breadcrumbs if coming from My Courses--}}
    <div class="row">
        <div class="col-lg-9">
            <h4>{{ $courseContent->menu_title }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                {{--                @if (strpos(url()->previous(), '/courses/') !== false)--}}
                {{--                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$courseContent->course_id, trans('menus.backend.course.title'), ['class' => '']) }}</li>--}}
                {{--                @elseif (strpos(url()->previous(), '/course/content') !== false)--}}
                {{--                    <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', trans('menus.backend.course.curriculum'), ['class' => '']) }}</li>--}}
                {{--                @endif--}}
                <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', $courseContent->course->abbrv, ['class' => '']) }}</li>
                {{--                <li class="breadcrumb-item active">{{ $courseContent->menu_title }}</li>--}}
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $courseContent->course->name }}</h3>
            <div class="float-right">
                <strong>Status:</strong>
                @switch($courseContent->publishedStatus)
                    @case(1)
                    {{ trans('labels.course_curriculum.not-published') }}
                    @break
                    @case(2)
                    {{ trans('labels.course_curriculum.same-published') }}
                    @break
                    @case(3)
                    {{ trans('labels.course_curriculum.older-published') }}
                    @break
                @endswitch
                |
                <a href="/course/content/{{ $courseContent->id }}/qse/publish">
                    <button type="button" class="publishbtn btn btn-primary btn-sm"
                            @if($courseContent->qseCanBePublish() == 0)
                            disabled
                            @elseif($courseContent->publishedStatus == 2)
                            disabled
                            @endif
                    >
                        {{ trans('buttons.curriculum.publish') }}
                    </button>
                </a>
                <button type="button"
                        class="btn btn-success btn-sm" id="btn-save-qse">
                    {{ trans('labels.general.buttons.save') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="form-qse" method="post" action="{{ route('qse-update', $courseContent->id) }}">
                <div class="alert alert-danger d-none" role="alert">
                </div>
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ trans('labels.qse.availability') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="activation_type" class="col-12 col-md-2 col-lg-2 col-form-label text-md-right">
                                {{ trans('labels.qse.activation') }}
                            </label>
                            <div class="col-12 col-md-3 col-lg-2">
                                <select class="form-control" id="activation_type" name="activation_type_id">
                                    @foreach(\App\Models\CourseContent\QSE\ActivationType::all() as $key => $activationType)
                                        <option value="{{ $activationType->id }}" {{ $key == 0 || $qse->activation_type_id == $activationType->id ? 'selected' : '' }}>{{ $activationType->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 {{ $qse->activation_type_id == 2 ? 'd-none' : ''}}"
                                 id="showIfActivationIsManual">
                                <select class="form-control" id="activation_state" name="activation_state">
                                    <option value="0" {{ $qse->activation_state == 0 || $qse->activation_state == null ? 'selected' : '' }}>{{ trans('labels.qse.disabled_by_default') }}</option>
                                    <option value="1" {{ $qse->activation_state == 1 && $qse->activation_state != null ? 'selected' : '' }}>{{ trans('labels.qse.available_by_default') }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-7 col-lg-8 {{ $qse->activation_type_id == 1 ? 'd-none' : ''}}"
                                 id="showIfActivationIsAutomatic">
                                <div class="row mt-3 mt-md-0 mt-lg-0">
                                    <div class="col-6 col-md-3 col-lg-3">
                                        @php
                                            $minutes = $qse->minutes < 0 ? $qse->minutes * -1 : $qse->minutes;
                                            $time_type = 'minutes';
                                            if ($minutes >= 60 && $minutes < 1440) {
                                                $minutes = $minutes / 60;
                                                $time_type = 'hours';
                                            } elseif ($minutes >= 1440) {
                                                $minutes = $minutes / 1440;
                                                $time_type = 'days';
                                            }
                                        @endphp
                                        <input type="number"
                                               class="form-control"
                                               id="minutes"
                                               name="minutes"
                                               value="{{ (int)$minutes }}"
                                               min="0"
                                        />
                                    </div>
                                    <div class="col-6 col-md-3 col-lg-3">
                                        <select class="form-control" id="time_type" name="time_type">
                                            <option value="minutes" {{ $time_type == 'minutes' || $qse->minutes == null ? 'selected' : '' }}>{{ trans('labels.qse.minutes') }}</option>
                                            <option value="hours" {{ $time_type == 'hours' && $qse->minutes != null ? 'selected' : '' }}>{{ trans('labels.qse.hours') }}</option>
                                            <option value="days" {{ $time_type == 'days' && $qse->minutes != null ? 'selected' : '' }}>{{ trans('labels.qse.days') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-3 col-lg-3 mt-3 mt-md-0 mt-lg-0">
                                        <select class="form-control" id="before_or_after" name="before_or_after">
                                            <option value="before" {{ $qse->minutes < 0 || $qse->minutes == null ? 'selected' : '' }}>{{ trans('labels.qse.before') }}</option>
                                            <option value="after" {{ $qse->minutes >= 0 && $qse->minutes != null ? 'selected' : '' }}>{{ trans('labels.qse.after') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-3 col-lg-3 mt-3 mt-md-0 mt-lg-0">
                                        <select class="form-control" id="automatic_activation_time"
                                                name="automatic_activation_time">
                                            <option value="S" {{ $qse->automatic_activation_time == 'S' || $qse->automatic_activation_time == null ? 'selected' : ''}}>{{ trans('labels.qse.start_time') }}</option>
                                            <option value="E" {{ $qse->automatic_activation_time == 'E' ? 'selected' : ''}}>{{ trans('labels.qse.end_time') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($qse->qse_type_id == 1)
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ trans('labels.qse.advanced_options') }}</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        id="collapse-advance-options"><i
                                            class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">

                            <div class="form-group row">
                                <label for="feedback_type"
                                       class="col-12 col-md-3 col-lg-3 col-form-label text-md-right">
                                    {{ trans('labels.qse.participant_feedback') }}
                                </label>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <select class="form-control" id="feedback_type" name="feedback_type_id">
                                        @foreach(\App\Models\CourseContent\QSE\FeedbackType::all() as $key => $feedbackType)
                                            <option value="{{ $feedbackType->id }}" {{ $key == 0 || $qse->feedback_type_id == $feedbackType->id ? 'selected' : '' }}>{{ $feedbackType->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="threshold" class="col-12 col-md-3 col-lg-3 col-form-label text-md-right">
                                    {{ trans('labels.qse.threshold') }}
                                </label>
                                <div class="col-12 col-md-2 col-lg-2">
                                    <input type="number" class="form-control" id="threshold" name="threshold"
                                           value="{{ $qse->threshold }}" min="0" max="100">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="allow_multiple_submits"
                                       class="col-6 col-md-3 col-form-label text-md-right align-self-center">
                                    {{ trans('labels.qse.allow_multiple_submits') }}
                                </label>
                                <div class="col-6 col-md-9 align-self-center">
                                    <input type="hidden" name="allow_multiple_submits"
                                           value="{{ $qse->allow_multiple_submits ? 1 : 0}}"/>
                                    <input type="checkbox" class="custom-checkbox ajax-form-checkbox"
                                           id="allow_multiple_submits"
                                           name="allow_multiple_submits_ajax_form_checkbox" {{ $qse->allow_multiple_submits ? 'checked' : '' }} />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="random"
                                       class="col-6 col-md-3 col-form-label text-md-right align-self-center">
                                    {{ trans('labels.qse.randomized_order') }}
                                </label>
                                <div class="col-6 col-md-9 align-self-center">
                                    <input type="hidden" name="random" value="{{ $qse->random ? 1 : 0}}"/>
                                    <input type="checkbox" class="custom-checkbox  ajax-form-checkbox" id="random"
                                           name="random_ajax_form_checkbox" {{ $qse->random ? 'checked' : '' }} />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="presentation_type_id"
                                       class="col-12 col-md-3 col-lg-3 col-form-label text-md-right">
                                    {{ trans('labels.qse.display_questions') }}
                                </label>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <select class="form-control" id="presentation_type_id" name="presentation_type_id">
                                        @foreach(\App\Models\CourseContent\QSE\PresentationType::all() as $key => $presentationType)
                                            <option value="{{ $presentationType->id }}" {{ $key == 0 || $qse->presentation_type_id == $presentationType->id ? 'selected' : '' }}>{{ $presentationType->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ trans('labels.qse.instructions') }}</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        id="collapse-advance-options">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0" style="display: none;">
                            <textarea id="instructionsTinyMCE" name="instructions">{{$qse->instructions}}</textarea>
                        </div>
                    </div>
                @endif
                @if($qse->qse_type_id == 4)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ trans('labels.qse.evaluation_settings') }}</h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        id="collapse-advance-options">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="">

                            <div class="form-group row">
                                <label for="feedback_type"
                                       class="col-12 col-md-3 col-lg-3 col-form-label text-md-right">
                                    {{ trans('labels.qse.evaluatees') }}
                                </label>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <select class="form-control" id="evaluatee_roles" name="evaluatee_roles[]" multiple
                                            required>
                                        @foreach($roles as $key => $role)
                                            <option value="{{ $key }}" {{ $qse->evaluatee_roles && str_contains($qse->evaluatee_roles, $key) ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
            @if(Session::has('status'))
                <div class="alert alert-success">
                    {{session('status')}}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title d-flex">
                        {{ trans('labels.qse.questions') }}
                        <div class="form-group form-check ml-3">
                            <input type="checkbox" class="form-check-input" id="show_answers" name="show_answers">
                            <label class="form-check-label" for="show_answers">
                                {{ trans('labels.qse.show_answers') }}
                            </label>
                        </div>
                    </h4>
                    <div class="card-tools">
                        <button type="button"
                                class="btn btn-primary btn-sm btn-add-question"
                                id="addOrEditQuestionShowModalBtn"
                        >
                            {{ trans('buttons.qse.add_question') }}
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <tbody id="questions-sortable-list">
                        @php
                            $qn = 1;
                        @endphp
                        @foreach($qse->qseQuestions as $q)
                            <tr id="{{ $q->id }}" class="{{ $q->retired_date != null ? 'table-active' : '' }}">
                                <td class="align-middle" style="cursor: pointer; width: 50px">
                                    <i class="fas fa-grip-vertical"></i>
                                </td>
                                @if($qse->qse_type_id == 1)
                                    <td class="text-center">
                                        @if($q->answer_type_id === 5)
                                            <h1>{{$q->retired_date != null ? '' : ($qn++) . '.'}}</h1>
                                        @elseif($q->answer_type_id === 6)
                                            <h2>{{$q->retired_date != null ? '' : ($qn++) . '.'}}</h2>
                                        @else
                                            {{$q->retired_date != null ? '' : ($qn++) . '.'}}
                                        @endif
                                    </td>
                                @endif
                                <td class="align-middle">
                                    <div class="qse-question-text">
                                        @if($q->answer_type_id === 5)
                                            <h1>{!! $q->text !!}</h1>
                                        @elseif($q->answer_type_id === 6)
                                            <h2>{!! $q->text !!}</h2>
                                        @else
                                            {!! $q->text !!}
                                        @endif
                                    </div>
                                    @if($qse->qse_type_id == 1)
                                        <ol class="answers d-none fa-ul"
                                            style="list-style-type: upper-alpha !important;">
                                            @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $ans)
                                                <li>
                                                    <span class="fa-li" style="left: -3em !important;">
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
                                                    <input class="form-check-input" type="radio"
                                                           name="inlineRadioOptions" id="yes_{{$q->id}}" value="yes">
                                                    <label class="form-check-label" for="yes_{{$q->id}}">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="inlineRadioOptions" id="no_{{$q->id}}" value="no">
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
                                                <div class="d-none" id="editable-{{$q->id}}">
                                                    <div class="form-group">
                                                        <label>{{ trans('labels.qse.answer') }}</label>
                                                        <div class="d-flex justify-content-between">
                                                            <input class="form-control" name="likert_caption[0]"
                                                                   id="likert_caption0" style="width: 130px"
                                                                   value="{{ $lscl }}"
                                                                   placeholder="{{ trans('labels.qse.left_caption') }}"
                                                                   required maxlength="30"/>
                                                            <input class="form-control" name="likert_caption[1]"
                                                                   id="likert_caption1" style="width: 130px"
                                                                   value="{{ $lscc }}"
                                                                   placeholder="{{ trans('labels.qse.center_caption') }}"
                                                                   required maxlength="30"/>
                                                            <input class="form-control" name="likert_caption[2]"
                                                                   id="likert_caption2" style="width: 130px"
                                                                   value="{{ $lscr }}"
                                                                   placeholder="{{ trans('labels.qse.right_caption') }}"
                                                                   required maxlength="30"/>
                                                        </div>
                                                        <div class="d-block p-2 my-2"
                                                             style="background-image: linear-gradient(to right, red, green);"></div>
                                                        @php($totalQ = $q->qseQuestionAnswers->count())
                                                        <div class="form-inline">
                                                            <label for="likert_scale">{{ trans('labels.qse.likert_scale') }}</label>
                                                            <select class="form-control ml-2" name="likert_scale"
                                                                    id="likert_scale">
                                                                <option value="4" {{ $totalQ == 4 ? 'selected' : '' }}>
                                                                    1-4
                                                                </option>
                                                                <option value="5" {{ $totalQ == 5 ? 'selected' : '' }}>
                                                                    1-5
                                                                </option>
                                                                <option value="7" {{ $totalQ == 7 ? 'selected' : '' }}>
                                                                    1-7
                                                                </option>
                                                                <option value="8" {{ $totalQ == 8 ? 'selected' : '' }}>
                                                                    1-8
                                                                </option>
                                                                <option value="9" {{ $totalQ == 9 ? 'selected' : '' }}>
                                                                    1-9
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
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
                                                    <select class="form-control">
                                                        @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $answer)
                                                            <option value="{{ $answer->id }}">{{ $answer->text }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif($q->answer_type_id == 8)
                                            <div class="answers d-none py-3">
                                                <p class="mb-0 text-center">1</p>
                                                <div class="d-flex">
                                                    1 <input type="range" class="form-control-range mx-1"
                                                             id="answer_{{$q->id}}_range" min="1" max="10" value="1"/>
                                                    10
                                                </div>
                                            </div>
                                        @else
                                            <ol class="answers d-none fa-ul"
                                                style="list-style-type: upper-alpha !important;">
                                                @foreach($q->qseQuestionAnswers()->orderBy('display_order')->get() as $ans)
                                                    <li>
                                                    <span class="fa-li" style="left: -3em !important;">
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
                                <td class="align-middle" style="width: 50px">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm py-0 bop" type="button"
                                                id="dropdownMenuButton{{$q->id}}"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuButton">
                                            <a
                                                    class="dropdown-item"
                                                    href="{{ route('qse-question-duplicate', $q->id) }}"
                                                    onclick="
                                                            event.preventDefault();
                                                            if (confirm('{{ trans('alerts.frontend.qse.are_you_sure_you_want_to_duplicate') }}'))
                                                            document.getElementById('duplicate-question-{{$q->id}}').submit()
                                                            "
                                            >
                                                <i class="fas fa-clone mr-1"></i> {{ trans('buttons.qse.duplicate') }}
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('qse-question-duplicate', $q->id) }}"
                                                  class="d-none" id="duplicate-question-{{$q->id}}">
                                                {{ csrf_field() }}
                                            </form>
                                            @if($q->publish_date === null)
                                                <a class="dropdown-item question-edit" href="#"
                                                   data-q-id="{{ $q->id }}"
                                                   data-q-text="{{ $q->text }}"
                                                   data-answer-type-id="{{ $q->answer_type_id }}"
                                                >
                                                    <i class="fas fa-pencil-alt mr-1"></i> {{ trans('buttons.qse.edit') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger"
                                                   href="{{ route('qse-question-delete', $q->id) }}"
                                                   onclick="
                                                           event.preventDefault();
                                                           if (confirm('{{ trans('alerts.frontend.qse.are_you_sure_you_want_to_delete') }}'))
                                                           document.getElementById('delete-question-{{$q->id}}').submit()
                                                           "
                                                >
                                                    <i class="fas fa-trash-alt mr-1"></i> {{ trans('buttons.qse.delete') }}
                                                </a>
                                                <form method="POST" action="{{ route('qse-question-delete', $q->id) }}"
                                                      class="d-none" id="delete-question-{{$q->id}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                </form>
                                            @elseif($q->retired_date == null)
                                                <a class="dropdown-item"
                                                   href="{{ route('qse-question-retire-or-activate', $q->id) }}"
                                                   onclick="event.preventDefault(); document.getElementById('qse-question-retire-{{$q->id}}').submit()"
                                                >
                                                    <i class="fas fa-pause mr-1"></i> {{ trans('buttons.qse.retire') }}
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('qse-question-retire-or-activate', $q->id) }}"
                                                      class="d-none" id="qse-question-retire-{{$q->id}}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="retire_or_activate" value="retire"/>
                                                </form>
                                            @elseif($q->retired_date != null)
                                                <a class="dropdown-item"
                                                   href="{{ route('qse-question-retire-or-activate', $q->id) }}"
                                                   onclick="event.preventDefault(); document.getElementById('qse-question-activate-{{$q->id}}').submit()"
                                                >
                                                    <i class="fas fa-play mr-1"></i> {{ trans('buttons.qse.activate') }}
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('qse-question-retire-or-activate', $q->id) }}"
                                                      class="d-none" id="qse-question-activate-{{$q->id}}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="retire_or_activate" value="activate"/>
                                                </form>
                                            @endif

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <form class="modal fade" id="addOrEditQuestionModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
          aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        @csrf
        <input type="hidden" name="course_qse_id" value="{{ $qse->id }}">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" role="alert">
                    </div>
                    @if($qse->qse_type_id != 1)
                        <div class="form-group">
                            <label for="qse_answer_type">{{ trans('labels.qse.select_answer_type') }}</label>
                            <select class="form-control" id="qse_answer_type" name="answer_type_id">
                                <option>{{ trans('labels.qse.select_answer_type') }}</option>
                                @foreach(\App\Models\CourseContent\QSE\QSEAnswerType::all() as $qseAnswerType)
                                    <option value="{{ $qseAnswerType->id }}">{{ $qseAnswerType->abbrv }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="d-flex justify-content-between">
                            <div class="w-100">{{ trans('labels.qse.question') }}</div>
                            <div class="invalid-feedback d-inline-block text-right" id="question-text-error">
                            </div>
                        </label>
                        <textarea id="questionTinyMCE" name="text"></textarea>
                    </div>
                    <div id="content_by_answer_type" class="p-2">
                    </div>
                    @if($qse->qse_type_id == 1)
                        <div class="d-flex justify-content-between">
                            <label>{{ trans('labels.qse.answers') }}</label>
                            <div id="ajaxLoading">

                            </div>
                        </div>
                        <table id="table-answers"
                               class="table table-condensed table-hover table-sm table-striped w-100">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center" style="width: 50px"><i class="fas fa-sort"></i></th>
                                <th scope="col" class="text-center"
                                    style="max-width: 300px;">{{ trans('labels.qse.text') }}</th>
                                <th scope="col" class="text-center"
                                    style="width: 80px;">{{ trans('labels.qse.correct') }}</th>
                                <th scope="col" class="text-center"
                                    style="width: 100px;">{{ trans('labels.qse.action') }}</th>
                            </tr>
                            </thead>

                            <tbody id="modal-answers-sortable">
                            </tbody>
                        </table>
                        <div class="d-flex">
                            <a href="#" class="btn btn-secondary ml-auto btn-sm btn-modal-add-another-answer">
                                {{ trans('buttons.qse.add_another_answer') }}
                            </a>
                        </div>
                    @elseif($qse->qse_type_id == 2 || $qse->qse_type_id == 3 || $qse->qse_type_id == 4)
                        <div id="ati_123" class="d-none">
                            <div class="d-flex justify-content-between">
                                <label>{{ trans('labels.qse.answers') }}</label>
                                <div id="ajaxLoading">

                                </div>
                            </div>
                            <table id="table-answers"
                                   class="table table-condensed table-hover table-sm table-striped w-100">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px"><i class="fas fa-sort"></i>
                                    </th>
                                    <th scope="col" class="text-center"
                                        style="max-width: 300px;">{{ trans('labels.qse.text') }}</th>
                                    <th scope="col" class="text-center"
                                        style="width: 100px;">{{ trans('labels.qse.action') }}</th>
                                </tr>
                                </thead>

                                <tbody id="modal-answers-sortable">
                                </tbody>
                            </table>
                            <div class="d-flex">
                                <a href="#" class="btn btn-secondary ml-auto btn-sm btn-modal-add-another-answer">
                                    {{ trans('buttons.qse.add_another_answer') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-warning btn-sm btn-close"
                            data-dismiss="modal">{{ trans('buttons.qse.close') }}</button>
                    <button type="button"
                            class="btn btn-success btn-sm btn-su-qse-question">{{ trans('labels.general.buttons.submit') }}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('after-scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    {{ Html::script("/js/jquery-ui.js") }}

    {{ Html::script("/js/tinymce/tinymce.min.js") }}
    {{ Html::script("/js/jquery.shorten.js") }}
    <script>
        let isSomethingChanged = false;
    </script>
    <script>
        tinymce.init({
            selector: '#questionTinyMCE',
            mode: "textareas",
            forced_root_block: false,
            editor_selector: "mce",
            browser_spellcheck: true,
            autosave_interval: "30s",
            autosave_retention: "30m",
            menubar: false,
            height: "300",
            branding: false,
            plugins: [
                'advlist autolink autosave lists link charmap print preview anchor ',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table paste code help wordcount image'
            ],

            toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify alignnone | bullist numlist | removeformat | link code' +
                '| image | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',

            automatic_uploads: true,
            images_upload_url: "/course/content/{{$courseContent->course_id}}/uploadimage",
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                //input.setRequestHeader('X-CSRF-TOKEN',{{ csrf_token() }});
                input.setAttribute('type', 'hidden');
                input.setAttribute('value', '{{ csrf_token() }}');
                input.setAttribute('enctype', 'multipart/form-data');
                input.setAttribute('name', 'image');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {title: file.name});
                    };
                };
                input.click();
            }
        });

        tinymce.init({
            selector: '#instructionsTinyMCE',
            mode: "textareas",
            forced_root_block: false,
            editor_selector: "mce",
            browser_spellcheck: true,
            autosave_interval: "30s",
            autosave_retention: "30m",
            menubar: false,
            height: "300",
            branding: false,
            plugins: [
                'advlist autolink autosave lists link charmap print preview anchor ',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table paste code help wordcount image'
            ],

            toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify alignnone | bullist numlist | removeformat | link code' +
                '| image | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',

            automatic_uploads: true,
            images_upload_url: "/course/content/{{$courseContent->course_id}}/uploadimage",
            file_picker_types: 'image',
            setup: function (ed) {
                ed.on('keyup', function (e) {
                    isSomethingChanged = true;
                });
            },
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                //input.setRequestHeader('X-CSRF-TOKEN',{{ csrf_token() }});
                input.setAttribute('type', 'hidden');
                input.setAttribute('value', '{{ csrf_token() }}');
                input.setAttribute('enctype', 'multipart/form-data');
                input.setAttribute('name', 'image');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {title: file.name});
                    };
                };
                input.click();
            }
        });
    </script>
    <script>

        $(document).ready(function () {
            let questionModalAnswers = $('#modal-answers-sortable');
            let btnSaveQSE = $('#btn-save-qse');
            let btnAddAnotherAnswer = $('.btn-modal-add-another-answer');
            let formQSE = $('#form-qse');
            let formModal = $('form#addOrEditQuestionModal');
            let activationType = $('select#activation_type');
            let showIfActivationIsAutomatic = $('div#showIfActivationIsAutomatic');
            let showIfActivationIsManual = $('div#showIfActivationIsManual');

            function generateQuestionAnswerRowInModal(rowId = new Date().getTime(), dt = {}) {
                const transFeedback = '{{ trans('labels.qse.feedback') }}';
                const transDelete = '{{ trans('buttons.qse.delete') }}';
                const answerIdEl = dt.id ? `<input type="hidden" name="answerIds[]" value="${dt.id}"/>` : '';
                return `
                        <tr id="masr-${rowId}">
                            <td colspan="4">
                                <table class="table table-borderleqsess w-100 mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="align-middle text-center" style="width: 50px; cursor: pointer">
                                                ${answerIdEl}
                                                <i class="fas fa-grip-vertical"></i>
                                            </td>
                                            <td class="align-middle text-center" style="max-width: 300px;">
                                                <input type="text" class="form-control form-control-sm" name="answerTexts[]" required id="masr-i1t-${rowId}" value="${dt.text ? dt.text : ''}"/>
                                                @if($qse->qse_type_id == 1)
                <div id="masr-i1te-${rowId}" class="invalid-feedback d-block text-left">

                                                    </div>
                                                @endif
                </td>
@if($qse->qse_type_id == 1)
                <td class="align-middle text-center" style="width: 80px;">
                    <input type="hidden" name="answerCorrects[]" value="${dt.correct ? 1 : 0}" />
                                                    <input type="checkbox" class="custom-checkbox ajax-form-checkbox" name="ajaxFormCheckbox[]" ${dt.correct ? 'checked' : ''} />
                                                </td>
                                            @endif
                <td class="align-middle text-center" style="width: 100px;">
@if($qse->qse_type_id == 1)
                <button class="btn btn-outline-dark btn-sm masr-btn-action" id='masr-btn-${rowId}-feedback' data-row-id="masr-${rowId}" data-toggle="tooltip" data-placement="top" title="${transFeedback}">
                                                        <i class="fa fa-comment" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                <button class="btn btn-danger btn-sm masr-btn-action" id='masr-btn-${rowId}-delete' data-row-id="masr-${rowId}" ${dt.id ? 'data-answer-id="' + dt.id + '"' : ''} data-toggle="tooltip" data-placement="top" title="${transDelete}">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="d-none">
                                            <td colspan="4">
                                                <label for="masr-i2f-${rowId}" class="d-flex justify-content-between">
                                                    ${transFeedback}
                                                    <div id="masr-i2fe-${rowId}" class="invalid-feedback d-block text-right ml-3">
                                                    </div>
                                                </label>
                                                <textarea name="answerFeedbacks[]" id="masr-i2f-${rowId}">${dt.feedback ? dt.feedback : ''}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    `;
            }

            activationType.on('change', function (e) {
                let val = parseInt($(this).val());

                if (val === 1) {
                    showIfActivationIsManual.removeClass('d-none');

                    showIfActivationIsAutomatic.find('input#minutes').removeAttr('required');
                    showIfActivationIsAutomatic.addClass('d-none');
                } else if (val === 2) {
                    showIfActivationIsManual.addClass('d-none');

                    showIfActivationIsAutomatic.find('input#minutes').attr('required', true);
                    showIfActivationIsAutomatic.removeClass('d-none');
                }
            });

            $('#show_answers').change(function (e) {
                if ($(this).is(':checked')) {
                    $('#questions-sortable-list').find('.answers').removeClass('d-none')
                } else {
                    $('#questions-sortable-list').find('.answers').addClass('d-none')
                }
            })

            /**
             * QSE Questions display order management
             */
            $('#questions-sortable-list').sortable({
                cursor: 'grabbing',
                placeholder: "highlight",
                axis: 'y',
                start: function (e, ui) {
                    ui.placeholder.height(ui.helper[0].scrollHeight);
                },
                stop: function () {
                    $.map($(this).find('tr'), function (el) {
                        $.ajax({
                            url: `{{ route('qse-question-update-display-order', '%id') }}`.replace('%id', el.id),
                            type: 'POST',
                            data: {
                                display_order: $(el).index()
                            },
                        });
                    });
                }
            });

            /**
             * QSE Question Answers display order management
             */
            questionModalAnswers.sortable({
                cursor: 'grabbing',
                placeholder: "highlight",
                axis: 'y'
            });

            btnSaveQSE.click(function (e) {
                if (formQSE[0].reportValidity()) {
                    tinymce.triggerSave();
                    $(this).prepend('<i class="fa fa-spinner fa-spin mr-1"></i>').attr('disabled', true);
                    formQSE.submit();
                }
            });
            formQSE.on('keyup change paste', 'input, select, textarea', function () {
                isSomethingChanged = true;
            });
            formQSE.submit(function (e) {
                e.preventDefault();
                let url = $(this).attr('action');
                let alertDanger = $(this).find('div.alert-danger');

                $.ajax({
                    url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (data, textStatus, jqXHR) {
                        btnSaveQSE.text('Saved Successfully!');
                        isSomethingChanged = false;
                        setTimeout(function () {
                            // Buttons revert back to their original state
                            btnSaveQSE.text('Save').removeAttr('disabled');
                        }, 2000);

                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                        alertDanger.removeClass('d-none');
                        // Buttons revert back to their original state
                        btnSaveQSE.text('Save').removeAttr('disabled');

                        if (jqXHR.status === 0) {
                            alertDanger.text('Not connected.\nPlease verify your network connection.');
                        } else if (jqXHR.status === 404) {
                            alertDanger.text('The requested page not found. [404]');
                        } else if (jqXHR.status === 500) {
                            alertDanger.text('Internal Server Error [500].');
                        } else if (jqXHR.status === 422) {
                            let errors = jqXHR.responseJSON.errors;
                            alertDanger.text(jqXHR.responseJSON.message)
                        } else if (textStatus === 'parsererror') {
                            alertDanger.text('Requested JSON parse failed.');
                        } else if (textStatus === 'timeout') {
                            alertDanger.text('Time out error.');
                        } else if (textStatus === 'abort') {
                            alertDanger.text('Ajax request aborted.');
                        } else {
                            alertDanger.text('Uncaught Error.\n' + jqXHR.responseText);
                        }

                    }
                });
            });

            /**
             * Add new row in answers list with unique row id.
             */
            btnAddAnotherAnswer.click(function (e) {
                e.preventDefault();

                let rowId = new Date().getTime(); // Get time in seconds

                questionModalAnswers.append(generateQuestionAnswerRowInModal(rowId));

                @if($qse->qse_type_id == 1)
                tinymce.EditorManager.execCommand('mceAddEditor', true, `masr-i2f-${rowId}`);
                @endif
            });

            $(document).on('change', 'input[type="checkbox"].ajax-form-checkbox', function (e) {
                if ($(this).is(':checked')) {
                    $(this).siblings('input[type="hidden"]').val('1');
                } else {
                    $(this).siblings('input[type="hidden"]').val('0');
                }
            });

            /**
             * Delete the answer from question in model
             */
            $(document).on('click', '.masr-btn-action', function (e) {
                e.preventDefault();

                let id = $(this).attr('id');

                // Confirm deletion
                if (id.includes('delete') && confirm('Are you sure you want to delete?')) {
                    let rowId = $(this).data('row-id');         // Get answer row id
                    let answerId = $(this).data('answer-id');   // Get answer id it can be null/undefined

                    // Set loading to button and also set disable unable request not process

                    console.log(answerId)
                    // Check if answer id not null/undefined deletion ajax request will be sent to server to delete the
                    // answer from server
                    if (answerId) {
                        // Send ajax request
                        $.ajax({
                            url: `{{ route('qse-question-answer-delete', '%id') }}`.replace('%id', answerId),
                            method: 'POST',
                            data: {
                                _method: 'DELETE',
                            },
                            success: function (data, textStatus, jqXHR) {
                                // tinymce.remove(`#masr-i2f-${rowId}`);
                                // If request is successful then remove the from from DOM
                                $(`#${rowId}`).remove();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                // If there is an error just set back button text to delete and remove disabled
                                // attribute
                                $(this).text('Delete');
                                $(this).removeAttr('disabled');
                            }
                        })
                    } else {
                        // tinymce.remove(`#masr-i2f-${rowId}`);
                        // Just simply remove the row from DOM because answer doesn't have the id and their record is
                        // not exists in the database.
                        $(`#${rowId}`).remove();
                    }

                } else if (id.includes('feedback')) {
                    $(this).toggleClass('active');
                    $(this).parent('td').parent('tr').siblings('tr').toggleClass('d-none');
                }
            });

            $('a.question-edit').on('click', function (e) {
                if (isSomethingChanged) {
                    if (confirm('{{ trans('alerts.frontend.qse.unsaved_changes_alert') }}')) {
                    }
                    e.preventDefault();
                    return;

                } else {
                    formModal.modal('show');
                }
                let qId = $(this).data('q-id');

                formModal.prepend('<input type="hidden" name="_method" value="PUT" id="inputMethodPUT"/>');
                formModal.attr('data-q-id', qId)
                formModal.data('answer-type-id', $(this).data('answer-type-id'))

                tinymce.get('questionTinyMCE').setContent($(this).data('q-text'))
                let answerTypeId = $(this).data('answer-type-id')
                $('select#qse_answer_type option').each(function () {
                    if (parseInt($(this).val()) === parseInt(answerTypeId)) {
                        $(this).attr('selected', true);
                    }
                });

                $(this).find('div.invalid-feedback').text('');
                let alertDanger = formModal.find('div.alert-danger');
                let submitBtn = formModal.find('button.btn-su-qse-question');
                let closeBtn = formModal.find('button.btn-close');

                // Set loading to button and also set disable unable request not process
                submitBtn.prepend('<i class="fa fa-spinner fa-spin mr-1"></i>').attr('disabled', true);
                closeBtn.attr('disabled', true);

                alertDanger.addClass('d-none');

                // Buttons revert back to their orignal state
                submitBtn.text('{{ trans('labels.general.buttons.submit') }}').removeAttr('disabled');
                closeBtn.removeAttr('disabled');

                $.ajax({
                    url: `{{ route('qse-question-answers') }}?qqi=${qId}`,
                    success: function (data, textStatus, jqXHR) {
                        // Buttons revert back to their original state
                        submitBtn.text('{{ trans('labels.general.buttons.submit') }}').removeAttr('disabled');
                        closeBtn.removeAttr('disabled');
                        questionModalAnswers.html('');

                        data.map(dt => {
                            let rowId = new Date().getTime(); // Get time in seconds
                            questionModalAnswers.append(generateQuestionAnswerRowInModal(rowId, dt));
                            tinymce.EditorManager.execCommand('mceAddEditor', true, `masr-i2f-${rowId}`);
                        });

                        if (data.length === 0) {
                            btnAddAnotherAnswer.trigger('click');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                        alertDanger.removeClass('d-none');

                        // Buttons revert back to their original state
                        submitBtn.text('{{ trans('labels.general.buttons.submit') }}').removeAttr('disabled');
                        closeBtn.removeAttr('disabled');

                        if (jqXHR.status === 0) {
                            alertDanger.text('Not connected.\nPlease verify your network connection.');
                        } else if (jqXHR.status === 404) {
                            alertDanger.text('The requested page not found. [404]');
                        } else if (jqXHR.status === 500) {
                            alertDanger.text('Internal Server Error [500].');
                        } else if (textStatus === 'parsererror') {
                            alertDanger.text('Requested JSON parse failed.');
                        } else if (textStatus === 'timeout') {
                            alertDanger.text('Time out error.');
                        } else if (textStatus === 'abort') {
                            alertDanger.text('Ajax request aborted.');
                        } else {
                            alertDanger.text('Uncaught Error.\n' + jqXHR.responseText);
                        }

                    }
                })

            });

            /**
             * On click Submit button in modal submit the form using their ID
             */
            $('.btn-su-qse-question').click(function (e) {
                e.preventDefault();
                tinymce.triggerSave();
                if (formModal[0].reportValidity())
                    formModal.submit();
            });

            $('#addOrEditQuestionShowModalBtn').on('click', function (e) {
                if (isSomethingChanged) {
                    if (confirm('{{ trans('alerts.frontend.qse.unsaved_changes_alert') }}')) {
                    }
                } else {
                    $('#addOrEditQuestionModal').modal('show');
                }
            });

            /**
             * Action performed by .btn-su-qse-question will be trigger there
             */
            formModal.submit(function (e) {
                e.preventDefault();
                let qId = $(this).data('q-id');

                $(this).find('div.invalid-feedback').text('');
                let alertDanger = $(this).find('div.alert-danger');
                let submitBtn = $(this).find('button.btn-su-qse-question');
                let closeBtn = $(this).find('button.btn-close');

                // Set loading to button and also set disable unable request not process
                submitBtn.prepend('<i class="fa fa-spinner fa-spin mr-1"></i>').attr('disabled', true);
                closeBtn.attr('disabled', true);

                alertDanger.addClass('d-none');

                $.ajax({
                    url: qId ? `{{ route('qse-question-update', '%question%' ) }}`.replace('%question%', qId) : '{{ route('qse-question-store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (data, textStatus, jqXHR) {
                        // Buttons revert back to their original state
                        submitBtn.text('Submit').removeAttr('disabled');
                        closeBtn.removeAttr('disabled');

                        window.location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alertDanger.removeClass('d-none');

                        // Buttons revert back to their original state
                        submitBtn.text('Submit').removeAttr('disabled');
                        closeBtn.removeAttr('disabled');

                        if (jqXHR.status === 0) {
                            alertDanger.text('Not connected.\nPlease verify your network connection.');
                        } else if (jqXHR.status === 404) {
                            alertDanger.text('The requested page not found. [404]');
                        } else if (jqXHR.status === 500) {
                            alertDanger.text('Internal Server Error [500].');
                        } else if (jqXHR.status === 422) {
                            let errors = jqXHR.responseJSON.errors;
                            alertDanger.text(jqXHR.responseJSON.message)
                            Object.keys(errors).forEach(function (err) {
                                console.log(err)
                                if (err.includes('answerTexts.') || err.includes('answerFeedbacks.') || err.includes('answerCorrects')) {
                                    let errAnswerInput = err.split('.')[0];
                                    let errAnswerIndex = parseInt(err.split('.')[1]);

                                    if (err.includes('answerTexts.')) {
                                        $($('input[name="answerTexts[]"]')[errAnswerIndex])
                                            .siblings('div.invalid-feedback').text(errors[err][0].replace(err, 'answer text'))
                                    } else if (err.includes('answerFeedbacks.')) {
                                        $($('textarea[name="answerFeedbacks[]"]')[errAnswerIndex])
                                            .siblings('label').find('div.invalid-feedback').text(errors[err][0].replace(err, 'answer feedback'))
                                    } else if (err.includes('answerCorrects')) {
                                        alertDanger.text(errors[err][0]);
                                    }
                                } else if (err === 'text') {
                                    $('#question-text-error').text(errors[err][0].replace(err, 'question'));
                                }

                            });
                        } else if (textStatus === 'parsererror') {
                            alertDanger.text('Requested JSON parse failed.');
                        } else if (textStatus === 'timeout') {
                            alertDanger.text('Time out error.');
                        } else if (textStatus === 'abort') {
                            alertDanger.text('Ajax request aborted.');
                        } else {
                            alertDanger.text('Uncaught Error.\n' + jqXHR.responseText);
                        }

                    }
                });
            }).on('show.bs.modal', function (e) {


                formModal.find('input#inputMethodPUT').remove();

                @if($qse->qse_type_id == 1)
                btnAddAnotherAnswer.trigger('click');
                @else
                setTimeout(() => {
                    let qId = formModal.data('q-id');
                    let qati = formModal.data('answer-type-id');
                    let ati_123 = $('#ati_123');
                    ati_123.addClass('d-none');
                    ati_123.find('input[name^=answerTexts]').removeAttr('required');

                    if (qati === 7) {
                        let editableLikert = $(`div#editable-${qId}`);
                        if (editableLikert.length) {

                            $('#content_by_answer_type').html(editableLikert.html());
                        }
                    } else if (qati === 1 || qati === 2 || qati === 3) {
                        ati_123.removeClass('d-none')
                        ati_123.find('input[name^=answerTexts]').attr('required', true);
                    }


                }, 1000);
                @endif
            }).on('hide.bs.modal', function (e) {
                $(this).removeAttr('data-q-id');
                formModal[0].reset();
            });

            $(document).on('show.bs.dropdown', '.dropdown', function (e) {
                $(this).find('button.bop').addClass('active');
            });
            $(document).on('hide.bs.dropdown', '.dropdown', function (e) {
                $(this).find('button.bop').removeClass('active');
            });

            $(".qse-question-text").shorten({
                showChars: 200,
                moreText: '{{ trans('buttons.qse.read_more') }}',
                lessText: '{{ trans('buttons.qse.read_less') }}'
            });

            $(document).on('focusin', function (e) {
                if ($(e.target).closest(".tox-textfield").length || $(e.target).closest(".tox-textarea").length) {
                    e.stopImmediatePropagation();
                }
            });

            $('input[type=range]').change(function (e) {
                let v = $(this).val();
                $(this).parent('div').siblings('p').text(v);
            });

            @if($qse->qse_type_id != 1)
            $('#qse_answer_type').on('change', function (e) {
                let contentByAnswerType = $('#content_by_answer_type')
                let ati_123 = $('#ati_123');
                contentByAnswerType.text('')
                ati_123.addClass('d-none');
                ati_123.find('tr').remove();
                switch (parseInt($(this).find(':selected').val())) {
                    case 1:
                    case 2:
                    case 3:
                        btnAddAnotherAnswer.trigger('click');
                        ati_123.removeClass('d-none');
                        ati_123.find('input[name^=answerTexts]').attr('required', true);
                        break;
                    case 7:
                        contentByAnswerType.html(`
                                <div class="form-group">
                                    <label>{{ trans('labels.qse.answer') }}</label>
                                    <div class="d-flex justify-content-between">
                                        <input class="form-control" name="likert_caption[0]" id="likert_caption0" style="width: 130px" placeholder="{{ trans('labels.qse.left_caption') }}" required maxlength="30"/>
                                        <input class="form-control" name="likert_caption[1]" id="likert_caption1" style="width: 130px" placeholder="{{ trans('labels.qse.center_caption') }}" required maxlength="30"/>
                                        <input class="form-control" name="likert_caption[2]" id="likert_caption2" style="width: 130px" placeholder="{{ trans('labels.qse.right_caption') }}" required maxlength="30"/>
                                    </div>
                                    <div class="d-block p-2 my-2" style="background-image: linear-gradient(to right, red, green);"></div>
                                    <div class="form-inline">
                                          <label for="likert_scale">{{ trans('labels.qse.likert_scale') }}</label>
                                          <select class="form-control ml-2" name="likert_scale" id="likert_scale">
                                                <option value="4">1-4</option>
                                                <option value="5">1-5</option>
                                                <option value="7">1-7</option>
                                                <option value="8">1-8</option>
                                                <option value="9">1-9</option>
                                          </select>
                                    </div>
                                </div>
                             `)
                        break;
                }
            });
            @endif
        });
    </script>
@stop
