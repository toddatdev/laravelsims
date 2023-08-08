@extends('backend.layouts.app')

@section ('title', trans('menus.backend.resourceSubCategory.title') . ' | ' . trans('menus.backend.resourceSubCategory.edit'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.resourceCategory.title') }}
    </h4>
@endsection

@section('content')

    {{ Form::model($resourceSubCategory, ['route' => ['update_resource_subcategory', $resourceSubCategory], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resourceSubCategory.edit') }}</h3>
                    </div>

                    <div class="card-body">
                        @include('resources.resourceSubCategory.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('resource_category_index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>

                    {{ Form::hidden('url',URL::previous()) }}
                    {{ Form::hidden('resource_category_id', $resourceSubCategory->resource_category_id) }}

                </div>
            </div>
        </div>
    </section>
    {{ Form::close() }}

@stop