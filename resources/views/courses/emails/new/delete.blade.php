@extends('frontend.layouts.app')

@section ('title', 'Delete Email')

{{--@section('page-header')--}}
{{--    <h1>--}}
{{--        {{ trans('navs.frontend.event-emails.delete_options') }}--}}
{{--    </h1>--}}
{{--@endsection--}}

@section('page-header')
    <div class="row">
        <div class="col-lg-7">
            <h4> {{ trans('navs.frontend.event-emails.delete_options') }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-5">
            <ol class="breadcrumb float-sm-right">
                @if (strpos(url()->previous(), 'mycourses') !== false)
                    <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                @elseif (strpos(url()->previous(), '/courses/') !== false)
                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$email->course->id, trans('menus.backend.course.title'), ['class' => '']) }}</li>
                @endif
                <li class="breadcrumb-item">{{ link_to('/courses/courseInstanceEmails?type=2', trans('navs.frontend.course-emails.manage'), ['class' => '']) }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')
    <section class="content">
        {!! Form::model($email, ['class' => 'form-horizontal','method' => 'POST', 'action' => ['Course\CourseEmailsController@remove']]) !!}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $email->course->name }} ({{ $email->course->abbrv }})</h3>

                        <div class="float-right">
                            @include('courses.emails.new.partial-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        <h3>{{ $email->label }}</h3>

                        {!! Form::model($email, ['class' => 'form-horizontal','method' => 'POST', 'action' => ['Course\CourseEmailsController@remove']]) !!}

                        {{-- ID --}}
                        {{ Form::hidden('course_email_id', $email->id) }}

                        <div class="form-group">
                            <div class="row">
                                {{-- Option 1 --}}
                                {!! Form::label('option_1', 'Only Delete This Template', ['class' => 'col-lg-4 control-label']) !!}
                                <div class="col-lg-8">
                                    {{ Form::radio('option', '1', false, ['class' => 'form-radio-input']) }}
                                    {{ Form::label('option_1-msg', 'Select if only this template should be deleted', ['class' => 'radio-inline']) }}
                                </div>
                            </div>

                            <div class="row">
                                {{-- Option 2 --}}
                                {!! Form::label('option_2', 'Delete Unedited Event Emails ['. $uneditCount .']', ['class' => 'col-lg-4 control-label']) !!}
                                <div class="col-lg-8">
                                    {{ Form::radio('option', '2', false, ['class' => 'form-check-input']) }}
                                    {{ Form::label('option_2-msg', 'Select if all unedited instances of this template should be deleted', ['class' => 'radio-inline']) }}
                                </div>
                            </div>

                            <div class="row">
                                {{-- Option 3 --}}
                                {!! Form::label('option_3', 'Delete Every Instance ['. $allCount .']', ['class' => 'col-lg-4 control-label']) !!}
                                <div class="col-lg-8">
                                    {{ Form::radio('option', '3', false, ['class' => 'form-check-input']) }}
                                    {{ Form::label('option_3-msg', 'Select if every instance of this template should be deleted', ['class' => 'radio-inline']) }}
                                </div>
                            </div>
                        </div>
                        {{-- Type for Return --}}
                        {{ Form::hidden('type', app('request')->input('type')) }}
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('courseInstanceEmails.index', trans('buttons.general.cancel'), ['type' => '3'], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
{{--                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-sm']) }}--}}
                            {{ Form::submit(trans('buttons.backend.siteEmails.delete'), ['class' => 'btn btn-success btn-md']) }}

                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div>
            </div>
        </div>
    </section>

@endsection