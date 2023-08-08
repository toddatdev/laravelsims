@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('page-header')
    {{--Only display breadcrumbs if coming from My Courses--}}
    <div class="row">
        <div class="col-lg-9">
            <h4>{{ $courseContent->menu_title }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                {{--                @if (strpos(url()->previous(), '/courses/') !== false)--}}
                {{--                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$courseContent->course_id, trans('menus.backend.course.title'), ['class' => '']) }}</li>--}}
                {{--                @elseif (strpos(url()->previous(), '/course/content') !== false)--}}
                {{--                @endif--}}
                <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', $courseContent->course->abbrv, ['class' => '']) }}</li>
{{--                <li class="breadcrumb-item active">{{ $courseContent->menu_title }}</li>--}}
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')

    <form class="card" name=pageform" method="post"
          action="/course/content/video/{{ $courseContent->courseFile->id ?? '' }}">
        @method('PUT')
        @csrf
        <div class="card-header">
            <h3 class="card-title">{{ $courseContent->menu_title }}</h3>
            <div class="float-right">
                <strong>Status:</strong>
                @switch($courseContent->publishedStatus)
                    @case(1)
                    {{ trans('labels.course_curriculum.not-published') }}
                    @break
                    @case(2)
                    {{ trans('labels.course_curriculum.same-published') }}
                    @break
                    @case(3)
                    {{ trans('labels.course_curriculum.older-published') }}
                    @break
                @endswitch
                |
                <a href="/course/content/{{ $courseContent->id }}/video/publish">
                    <button type="button"
                            class="publishbtn btn btn-primary btn-sm {{ Session::get('saved') ?? ''}}">
                        {{ trans('buttons.curriculum.publish') }}
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($courseContent->courseFile)
                <div class="text-center mt-5 pt-3">
                    @if(strtolower(getFileExtension($courseContent->courseFile->links)) === 'mp4')
                        <video width="720" height="380" controls>
                            <source src="{{$courseContent->courseFile->links ?? ''}}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif(strtolower(getFileExtension($courseContent->courseFile->links)) === 'pdf')
                        <iframe width="730px" height="1000px" class="doc"
                                src="{{$courseContent->courseFile->links}}" frameborder="0"></iframe>
                    @elseif(isOfficeExtension($courseContent->courseFile->links))
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{$courseContent->courseFile->links}}"
                                width="700" height="780" style="border: none;"></iframe>
                    @elseif($courseContent->contentType->id == 6)
                        <iframe src="{{$courseContent->courseFile->links}}"
                                style="border: none;" width="100%" height="700"></iframe>
                    @else
                        <a class="btn btn-default" href="{{$courseContent->courseFile->links}}">Download File</a>
                    @endif
                </div>
            @endif
        </div>
    </form>
    {{--    <h4 id="heading">--}}
    {{--        @if(session('breadCrumbPage') == 'My Courses')--}}
    {{--            <a href="/mycourses">{{trans('menus.backend.course.my_courses')}}</a> >--}}
    {{--        @else--}}
    {{--            <a href="/courses/active?id={{$courseContent->course_id}}">{{trans('menus.backend.course.courses')}}</a> >--}}
    {{--        @endif--}}
    {{--        <a href="/course/content/{{$courseContent->course_id}}/edit">{{ $courseContent->course->name }}--}}
    {{--            ({{ $courseContent->course->abbrv }}) {{trans('menus.backend.course.curriculum')}}</a> >--}}
    {{--        @if($courseContent->content_type_id === 2)--}}
    {{--            {{trans('menus.backend.course.edit_page')}}--}}
    {{--        @elseif($courseContent->content_type_id === 3)--}}
    {{--            {{trans('menus.backend.course.edit_video')}}--}}
    {{--        @endif--}}
    {{--    </h4>--}}
    {{--    <div class="panel">--}}
    {{--        <div class="panel panel-default">--}}
    {{--            <div class="panel-heading d-flex justify-content-between align-items-center">--}}
    {{--                <div class="h4">{{ $courseContent->menu_title }}</div>--}}
    {{--                <div>Status:--}}
    {{--                    @switch($courseContent->publishedStatus)--}}
    {{--                        @case(1)--}}
    {{--                        {{ trans('labels.course_curriculum.not-published') }}--}}
    {{--                        @break--}}
    {{--                        @case(2)--}}
    {{--                        {{ trans('labels.course_curriculum.same-published') }}--}}
    {{--                        @break--}}

    {{--                        @case(3)--}}
    {{--                        {{ trans('labels.course_curriculum.older-published') }}--}}
    {{--                        @break--}}
    {{--                    @endswitch--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="panel-body">--}}

    {{--            </div>--}}
    {{--        </div>--}}
@endsection

