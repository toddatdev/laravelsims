@extends('backend.layouts.app')

@section ('title', trans('menus.backend.courseCategory.title') . ' | ' . trans('menus.backend.courseCategory.create'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.courseCategory.title') }}
    </h1>
@endsection

@section('content')

    <section class="content">
        {{ Form::open(['route' => ['store_course_categories', app('request')->group], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}

        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">{{ trans('menus.backend.courseCategory.create') }} for <strong>{{ $courseCategoryGroup->abbrv}}</strong> Group</h3>
                </div>

                <div class="card-body">
                    @include('courses.courseCategory.partial-form')
                </div>

                <div class="card-footer">
                    <div class="float-left">
                        {{ link_to_route('all_course_groups', trans('buttons.general.cancel'), [app('request')->course], ['class' => 'btn btn-warning btn-md']) }}
                    </div>
                    <div class="float-right">
                        {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                    </div>
                </div>
            </div>
        </div>
        {{ Form::hidden('url',URL::previous()) }}
        {{ Form::close() }}
    </section>
@stop