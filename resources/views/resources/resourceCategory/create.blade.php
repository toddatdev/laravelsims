@extends('backend.layouts.app')

@section ('title', trans('menus.backend.resourceCategory.title') . ' | ' . trans('menus.backend.resourceCategory.create'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.resourceCategory.title') }}
    </h4>
@endsection

@section('content')

    {{ Form::open(['url' => '/ResourceCategory', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resourceCategory.create') }}</h3>
                    </div>

                    <div class="card-body">
                        @include('resources.resourceCategory.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('resource_category_index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                </div>
                {{ Form::hidden('url',URL::previous()) }}
            </div>
        </div>
    </section>
    {{ Form::close() }}

@stop