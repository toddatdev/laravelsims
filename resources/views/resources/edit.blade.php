@extends('backend.layouts.app')

@section ('title', trans('menus.backend.resource.title') . ' | ' . trans('menus.backend.resource.edit'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.resource.title') }}
    </h4>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    {{ Form::model($resource, ['route' => ['resources.update', $resource], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resource.edit') }}</h3>
                        <div class="float-right">
                            @include('resources.partial-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        @include('resources.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('active_resources', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ Form::close() }}

@stop