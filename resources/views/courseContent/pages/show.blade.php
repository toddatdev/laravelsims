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
                {{--                    <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', trans('menus.backend.course.curriculum'), ['class' => '']) }}</li>--}}
                {{--                @endif--}}
                <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', $courseContent->course->abbrv, ['class' => '']) }}</li>
{{--                <li class="breadcrumb-item active">{{ $courseContent->menu_title }}</li>--}}
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $courseContent->course->name }}</h3>
            <div class="float-right">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        Jump to... <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="max-height: 500px; overflow-y: scroll">
                        @foreach($menuCourseContent as $menuItem)
                            <a href="#" class="font-weight-bold dropdown-item">{{$menuItem->menu_title}}</a>
                            @if($status == 'preview')
                                @foreach($menuItem->unPublishedContentItems as $subMenuItem)
                                    <a href="/course/content/page/{{ $subMenuItem->id }}/preview" class="dropdown-item ml-2">{{$subMenuItem->menu_title}}</a>
                                @endforeach
                            @else
                                @foreach($menuItem->publishedContentItems as $subMenuItem)
                                    <a href="/course/content/page/{{ $subMenuItem->id }}" class="dropdown-item ml-2">{{$subMenuItem->menu_title}}</a>
                                @endforeach
                            @endif
                            <li role="separator" class="divider"></li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(count($menuCourseContent) > 0)
                <!-- Single button -->
{{--                <div class="d-flex justify-content-between">--}}
{{--                    <div>--}}
{{--                        <h1>{{ isset($item['module']) ? $item['module'] : ''}}</h1>--}}
{{--                        <h2>{{ isset($item['menu_title']) ? $item['menu_title'] : '' }}</h2>--}}
{{--                    </div>--}}
{{--                </div>--}}
                @isset($courseFile)
                    @if(strtolower(getFileExtension($courseFile->links ?? '')) === 'mp4')
                        <video width="720" height="380" controls style="margin: 0 auto;">
                            <source src="{{$courseFile->links ?? ''}}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif(strtolower(getFileExtension($courseFile->links ?? ''))   === 'pdf')
                        <iframe width="730px" height="1000px" class="doc"
                                src="{{$courseFile->links}}" frameborder="0"></iframe>
                    @elseif(isOfficeExtension($courseFile->links))
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{$courseFile->links}}"
                                width="700" height="780" style="border: none;"></iframe>
                    @elseif(strpos($courseFile->links, '.html') != FALSE)
                        <iframe src="{{$courseFile->links}}"
                                width="100%" height="780" style="border: none;"></iframe>
                    @else
                        <a class="btn btn-default" href="{{$courseFile->links}}">Download File</a>
                    @endif
                @endisset
                @if(!empty($page->text?? ''))
                    {!!  $page->text !!}
                @endif
            @else
                <p>Sorry! No record found related to course.</p>
            @endif
        </div>
        <div class="card-footer {{ count($menuCourseContent) > 0 ? 'd-flex justify-content-between' : 'd-none'}}">
            <div class="w-100">
                @isset($prev['content_id'])
                    <a href="/course/content/page/{{$prev['content_id']}}{{ $status == 'preview' ? '/preview': '' }}"
                       class="btn btn-primary btn-sm"
                       style="width:85px;"
                    >
                        <i class="mr-2 far fa-chevron-left"></i>Previous
                    </a>
                @endisset
            </div>
            <div class="w-100 text-right">
                @isset($next['content_id'])
                    <a href="/course/content/page/{{$next['content_id']}}{{ $status == 'preview' ? '/preview': '' }}"
                       class="btn btn-primary btn-sm"
                       style="width:85px;"
                    >
                        Next<i class="ml-2 far fa-chevron-right"></i>
                    </a>
                @endisset
            </div>
        </div>
    </div>
@endsection