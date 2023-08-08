@extends('backend.layouts.app')

@section ('title', trans('menus.backend.resource.title') . ' | ' . trans('menus.backend.resource.create'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.resource.title') }}
    </h4>
@endsection

@section('content')

    {{ Html::script("js/jquery.js") }}

    {{ Form::model($resource, ['route' => ['resources.store', $resource], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resource.create') }}</h3>
                        <div class="float-right">
                            @include('resources.partial-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        @include('resources.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('all_resources', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-sm']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ Form::close() }}

@stop

