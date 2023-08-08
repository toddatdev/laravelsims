@extends('backend.layouts.app')

@section ('title', trans('menus.backend.siteEmails.edit'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.siteEmails.edit') }}
    </h1>
@endsection

@section('content')
    {!! Form::model($siteEmails, ['class' => 'form-horizontal','method' => 'PATCH', 'action' => ['Site\SiteEmailsController@update', $siteEmails->id]]) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.siteEmails.edit') }}</h3>
            <div class="box-tools pull-right">
                @include('sites.emails.partial-header-buttons')
            </div>
        </div>

        
        @include('sites.emails.partial-form', ['submitButton' => trans('buttons.general.crud.update')])
        
    </div>

    {!! Form::close() !!}
@endsection