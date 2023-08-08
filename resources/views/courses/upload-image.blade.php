@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.course.upload'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.course.title') }}
    </h1>
@endsection

@section('content')

    {{--These two includes are needed to format the image file input--}}
    {{ Html::script("js/jquery.js") }}
    <script type="text/javascript" src="{{ asset('/js/bootstrap-filestyle.min.js') }}"></script>

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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{ Form::model($course, ['route' => ['update_course_image', $course], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.course.upload') }} for {{$course->abbrv}}</h3>
                        <div class="float-right">
                            @include('courses.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-5">
                                <input type="file" name="image" class="filestyle" data-text="Choose File" data-btnClass="btn-primary"/>
                            </div>
                        </div>

                        <div class="mt-10">
                            @if($course->catalog_image)
                                <img src="{{URL::to($course->catalog_image)}}" class="img-thumbnail" width="350">
                            @else
                                <div class="alert alert-info alert-block">An image has not been uploaded yet for this course.</div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            <button type="button" class="btn btn-warning btn-md" onclick="window.location='{{ URL::previous() }}'">{{trans('buttons.general.cancel')}}</button>
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('labels.courses.upload_image'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>

@stop

