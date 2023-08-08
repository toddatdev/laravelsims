@extends('frontend.layouts.app')

@section ('title', trans('labels.scheduling.scheduling') . ' | ' . trans('labels.scheduling.another_event'))

@section('content')
<style>
    .alert-err {
        padding: 5px;
        background-color: rgb(242,222,222);
        color: rgb(184,77,95);
        margin-bottom: 10px;
    }
    .closebtn {
        margin-left: 15px;
        color: white;
        font-weight: bold;
        float: right;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        transition: 0.3s;
    }

    .closebtn:hover {
        color: black;
    }
</style>

{{-- Error Msg Container --}}
<div class="error-storage" style="display: none";></div>

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="pull-left">{{ trans('labels.scheduling.scheduling') . ' - ' . trans('labels.scheduling.another_event') }}</h4>
                    <div class="pull-right">
                        <i onclick="window.location = '/calendar?date=' + moment($('#datepicker').val()).format('Y-MM-DD')" class="fad fa-2x fa-calendar addIcon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('navs.frontend.scheduling.redirect') }}"></i>
                    </div>
                    <br clear="both" />
                </div>

                <div class="panel-body">
                    {{ Form::open(['url' => '/courseInstance/main/store', 'id'=>'anotherEventForm', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                    @include('courseInstance.main.partials.partial-form')
                </div>

                <div class="panel-footer clearfix">
                    <div class="pull-left">
                        {{ link_to_route('frontend.user.dashboard', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                    </div>
                    <div class="pull-right">
                        {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md create-event-submit', 'name'=>'submit', 'value'=>'save', 'id' => 'save_event']) }}
                    </div>
                </div>

            </div>
            {{-- Day Pilot Grid --}}
            <div class="row marginTop15 marginBottom15">
                <div class="col-md-12" >
                    @include('courseInstance.main.partials.partial-grid')                       
                </div>
            </div>
        </div>
    </div>

@endsection

@section('after-scripts')

@endsection