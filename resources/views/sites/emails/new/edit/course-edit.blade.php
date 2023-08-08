@extends('backend.layouts.app')

@section ('title', trans('menus.backend.siteEmails.edit-course'))

@section('page-header')
    <div class="row">
        <div class="col-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ link_to('/admin/site/emails?type=2', trans('navs.frontend.course-emails.manage_course'), ['class' => '']) }}</li>
                <li class="breadcrumb-item active">{{ trans('menus.backend.siteEmails.edit-course') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <section class="content">
        {!! Form::model($siteEmails, ['class' => 'form-horizontal','method' => 'PATCH', 'action' => ['Site\SiteEmailsController@update', $siteEmails->id]]) !!}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.siteEmails.edit-course') }}</h3>
                        <div class="box-tools float-right">
                            {{-- mitcks 2020-11-10 The buttons don't really make sense here - commenting out for now--}}
                            {{-- @include('sites.emails.new.partial-header-buttons')--}}
                        </div>
                    </div>
                    <div class="card-body">
                        @include('sites.emails.new.create.create-partials.course-create', ['submitButton' => trans('buttons.general.crud.update')])
                    </div>
                    <div class="card-footer">
                        <!-- Btn's -->
                        <div class="float-left">
                            {{ link_to_route('emails.index', trans('buttons.general.cancel'), ['type' => '2'], ['class' => 'btn btn-warning btn-sm']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@endsection