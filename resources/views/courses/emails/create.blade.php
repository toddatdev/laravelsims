@extends('backend.layouts.app')

@section ('title', trans('menus.backend.courseEmails.create'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.courseEmails.create') }}
    </h1>
@endsection

@section('content')
    {!! Form::open(['url' => 'courses/courseInstanceEmails', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.courseEmails.create') }}</h3>
            <div class="box-tools pull-right">
                @include('courses.emails.partial-header-buttons')
            </div>
        </div>

        
        @include('courses.emails.partial-form', ['submitButton' => trans('buttons.general.crud.create')])
        
    </div>
    {!! Form::close() !!}
@endsection