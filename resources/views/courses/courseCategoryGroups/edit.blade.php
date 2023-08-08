@extends('backend.layouts.app')

@section ('title', trans('menus.backend.courseCategoryGroup.title') . ' | ' . trans('menus.backend.courseCategoryGroup.edit'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.courseCategoryGroup.title') }}
    </h1>
@endsection

@section('content')
    <section class="content">
        {{ Form::model($courseCategoryGroup, ['route' => ['update_course_category_group', $courseCategoryGroup], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.courseCategoryGroup.edit') }}</h3>
                    </div>

                    <div class="card-body">
                        @include('courses.courseCategoryGroups.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('all_course_groups', trans('buttons.general.cancel'), [app('request')->course], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::hidden('url',URL::previous()) }}
        </div>
        {{ Form::close() }}
    </section>

@stop