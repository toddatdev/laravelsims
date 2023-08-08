@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseEmails.create'))

@section('page-header')
    <div class="row">
        <div class="col-lg-4">
            <h4>{{ trans('menus.backend.courseEmails.create-e') }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-8">
            <ol class="breadcrumb float-sm-right">
                @if (Session::get('breadcrumbLevel1') == 'mycourses')
                    <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                @elseif (Session::get('breadcrumbLevel1') == 'courses')
                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$course->id, trans('menus.backend.course.title'), ['class' => '']) }}</li>
                @endif
                <li class="breadcrumb-item">{{ link_to('/courses/courseInstanceEmails?type=3', trans('navs.frontend.course-emails.manage'), ['class' => '']) }}</li>
                <li class="breadcrumb-item active">{{ $course->abbrv }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')
    <section class="content">
        {!! Form::open(['url' => 'courses/courseInstanceEmails', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            @if(session('clonedEmail'))
                                {{ session('name') }}
                            @else
                                {{ $name }}
                            @endif
                        </h3>
                        <div class="float-right">
{{--                            @include('courses.emails.new.partial-header-buttons')--}}
                        </div>
                    </div>

                    <div class="card-body">
                        @include('courses.emails.new.partials.event')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('courseInstanceEmails.index', trans('buttons.general.cancel'), ['type' => '3'], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-sm']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection