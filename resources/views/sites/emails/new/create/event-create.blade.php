@extends('backend.layouts.app')

@section ('title', trans('menus.backend.siteEmails.create'))

@section('page-header')
    <div class="row">
        <div class="col-12">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ link_to('/admin/site/emails?type=3', trans('navs.frontend.event-emails.manage_event'), ['class' => '']) }}</li>
                <li class="breadcrumb-item active">{{ trans('menus.backend.siteEmails.create-event') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <section class="content">
        {!! Form::open(['url' => 'admin/site/emails', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.siteEmails.create-event') }}</h3>
                        <div class="box-tools pull-right">
                            {{-- mitcks 2020-11-10 The buttons don't really make sense here - commenting out for now--}}
                            {{-- @include('sites.emails.new.partial-header-buttons')--}}
                        </div>
                    </div>
                    <div class="card-body">
                        @include('sites.emails.new.create.create-partials.event-create', ['submitButton' => trans('buttons.general.crud.create')])
                    </div>
                    <div class="card-footer">
                        <!-- Btn's -->
                        <div class="float-left">
                            {{ link_to_route('emails.index', trans('buttons.general.cancel'), ['type' => '3'], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@endsection