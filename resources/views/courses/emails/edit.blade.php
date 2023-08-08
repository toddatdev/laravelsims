@extends('backend.layouts.app')

@section ('title', trans('menus.backend.courseEmails.edit'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.courseEmails.edit') }}
    </h1>
@endsection

@section('content')
    {!! Form::model($courseEmails, ['class' => 'form-horizontal','method' => 'PATCH', 'action' => ['Course\CourseEmailsController@update', $courseEmails->id]]) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.courseEmails.edit') }}</h3>
            <div class="box-tools pull-right">
                @include('courses.emails.partial-header-buttons')
            </div>
        </div>

        
        @include('courses.emails.partial-form', ['submitButton' => trans('buttons.general.crud.update')])
        
    </div>

    {!! Form::close() !!}
@endsection