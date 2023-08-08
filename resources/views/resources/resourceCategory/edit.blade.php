@extends('backend.layouts.app')

@section ('title', trans('menus.backend.resourceCategory.title') . ' | ' . trans('menus.backend.resourceCategory.edit'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.resourceCategory.title') }}
    </h4>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    {{ Form::model($resourceCategory, ['route' => ['update_resource_category', $resourceCategory], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resourceCategory.edit') }}</h3>
                    </div>

                    <div class="card-body">
                        @include('resources.resourceCategory.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('resource_category_index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
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