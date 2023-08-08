@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.course.edit'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.course.title') }}
    </h4>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}
                {{--ksm: commented the next line out, but left here as an example of how to display a link if needed--}}
                {{--{{ link_to_route('all_courses', trans('menus.backend.course.view-all')) }}--}}
            </strong>
        </div>
    @endif

    <section class="content">
        {{ Form::model($course, ['route' => ['update_course', $course], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.course.edit') }}</h3>
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
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>

        {{ Form::close() }}

    </section>

@stop

