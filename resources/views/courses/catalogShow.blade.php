@extends('frontend.layouts.public')

@section ('title', trans('navs.frontend.course.catalog'))

@section('after-styles')
    <style>
        .panel-default > .panel-heading-custom {
            background: {{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(1) }};
            color: {{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) }}; }
    </style>
@stop

@section('page-header')
    {{--Only display breadcrumbs if coming from My Courses--}}
        <div class="row">
            <div class="col-lg-9">
                 <h4>{{ $course->name }} ({{ $course->abbrv }})</h4>
            </div><!-- /.col -->
            <div class="col-lg-3">
                <ol class="breadcrumb float-sm-right">
                    @if (strpos(url()->previous(), 'mycourses') !== false)
                        <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                    @elseif (strpos(url()->previous(), 'catalog') !== false)
                        <li class="breadcrumb-item">{{ link_to('/courses/catalog', trans('navs.frontend.course.catalog'), ['class' => '']) }}</li>
                    @endif
                    <li class="breadcrumb-item active">{{ $course->abbrv }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-secondary">
                            {{trans('labels.courses.upcoming_dates')}}
                            <div class="col-md-4 float-right text-right">
                                {{--enroll request button, hide if option set OR if no upcoming dates, mitcks: had to do a nested IF here because OR operator would not work?  Not sure why.--}}
                                @if(null !== $course->upcomingClassDates())
                                    @if(!$course->isOptionChecked(6))
                                        @if($course->isOptionChecked(1))
                                            {{ link_to_route('enrollRequest',
                                            trans('navs.frontend.course.auto_enroll'),
                                            ['course_id'=>$course->id, ],
                                            ['class' => 'btn btn-success btn-sm']) }}
                                        @else
                                            {{ link_to_route('enrollRequest',
                                            trans('navs.frontend.course.request_enroll'),
                                            ['course_id'=>$course->id, ],
                                            ['class' => 'btn btn-success btn-sm']) }}
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item">
                            @if(null !== $course->upcomingClassDates())
                                {!! $course->upcomingClassDates() !!}
                            @else
                                @if(!$course->isOptionChecked(6))
                                    {!! trans('labels.courses.no_upcoming_dates') !!}&nbsp;{!! trans('labels.courses.self_park', ['course_id'=>$course->id]) !!}
                                @else
                                    {!! trans('labels.courses.no_upcoming_dates') !!}
                                @endif
                            @endif
                        </li>
                        <li class="list-group-item bg-secondary">{{trans('labels.courses.author_name')}}</li>
                        <li class="list-group-item">{{ $course->author_name }}</li>
                        <li class="list-group-item bg-secondary">{{trans('labels.courses.about_course')}}</li>
                        <li class="list-group-item">
                            @if($course->catalog_image)
                                <img src="{{URL::to($course->catalog_image)}}"
                                     class="img-thumbnail float-right img-responsive" >
                            @else
{{--                                2021-02-05 mitcks: Leaving this here as a placeholder in case JL decides to setup default catalog images per site--}}
{{--                                <img src="https://larasims-test-bucket-1.s3.amazonaws.com/site-1/CourseCatalogImages/11.jpg"--}}
{{--                                     class="img-thumbnail pull-right img-responsive" >--}}
                            @endif
                            {!! $course->catalog_description !!}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('after-scripts')

@endsection

