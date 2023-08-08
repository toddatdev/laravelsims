@extends('backend.layouts.app')

@section ('title', trans('menus.backend.resource.title') . ' | ' . trans('menus.backend.resource.edit'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.resource.title') }}
    </h4>
@endsection

@section('content')

{{ Form::open(['route' => ['resource.delete', $resource->id], 'method' => 'delete']) }}

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">{{ trans('menus.backend.resource.delete') }}</h3>
    </div>
    <div class="card-body">
        <strong>{{ 'Are you sure you want to delete '. $resource->abbrv .' ('. $resource->description .')?' }}</strong>

    </div>
    <div class="card-footer">
        <div class="float-left">
            {{ link_to_route('active_resources', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md ml-20']) }}
        </div>
        <div class="float-right">
            {{ Form::submit(trans('buttons.general.crud.delete'), ['class' => 'btn btn-success btn-md ml-2']) }}
        </div>
    </div>

{{ Form::close() }}

@stop