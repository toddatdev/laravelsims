@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.course.create'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.course.title') }}
    </h4>
@endsection

@section('content')
    <section class="content">
        {{ Form::open(['url' => '/courses', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.course.create') }}</h3>
                        <div class="float-right">
                            @include('courses.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">
                        @include('courses.partial-form')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('active_courses', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>

        {{ Form::close() }}
    </section>

@stop