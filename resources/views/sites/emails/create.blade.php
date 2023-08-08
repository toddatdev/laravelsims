@extends('backend.layouts.app')

@section ('title', trans('menus.backend.siteEmails.create'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.siteEmails.create') }}
    </h1>
@endsection

@section('content')
    {!! Form::open(['url' => 'admin/site/emails', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.siteEmails.create') }}</h3>
            <div class="box-tools pull-right">
                @include('sites.emails.partial-header-buttons')
            </div>
        </div>

        
        @include('sites.emails.partial-form', ['submitButton' => trans('buttons.general.crud.create')])
        
    </div>
    {!! Form::close() !!}
@endsection