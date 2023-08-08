@extends('frontend.layouts.app')

@section ('title', trans('labels.scheduling.scheduling') . ' | ' . trans('labels.scheduling.create_event'))

@section('content')
    {{ Html::style("css/schedule-request.css") }}

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
<section>
    {{ Form::open(['url' => '/courseInstance/main/store', 'id'=>'anotherEventForm', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">{{ trans('labels.scheduling.create_event') }}</h3>
                    <div class="float-right">
                        <i onclick="window.location = '/calendar?date=' + moment($('#datepicker').val()).format('Y-MM-DD')" class="fad fa-2x fa-calendar addIcon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('navs.frontend.scheduling.redirect') }}"></i>
                    </div>
                </div>

                <div class="card-body">
                    @include('courseInstance.main.partials.partial-form')
                </div>

                <div class="card-footer">
                    <div class="float-left">
                        {{ link_to(URL::previous(), trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-sm']) }}
                    </div><!--pull-left-->
                    <div class="float-right">
                        {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-sm create-event-submit', 'name'=>'submit', 'value'=>'save']) }}
                    </div><!--pull-right-->
                </div><!-- /.card-footer -->

                <div class="card-body">
                    {{-- Day Pilot Grid --}}
                    <div class="row marginTop15 marginBottom15">
                        <div class="col-md-12" >
                            @include('courseInstance.main.partials.partial-grid')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('after-scripts')

@endsection