@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.courseCategory.title'))

@section('page-header')
    <h1>{{ trans('menus.backend.courseCategory.title') }}</h1>
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ $course->abbrv }}</h3>
                        <div class="float-right">
                            @include('courses.partial-header-buttons-category')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-columns">
                            @foreach ($courseCategoryGroups as $courseCategoryGroup)

                                <li class="card">
                                    <div class="card-header bg-light">
                                        <h4 class="card-title">
                                            <label for="categoryGroup" class="" data-toggle="tooltip" title="{{ $courseCategoryGroup->description }}">{{ $courseCategoryGroup->abbrv }}</label>&nbsp;
                                        </h4>
                                        @if($courseCategoryGroup->abbrv != 'Catalog Filter')
                                            <span class="simptip-position-bottom simptip-smooth" data-tooltip="{{trans('buttons.backend.courses.editGroup')}}">
                                                <a href="/courses/courseCategoryGroups/edit/{{$courseCategoryGroup->id}}/{{$course->id}}" class="btn-xs">
                                                    <i class="fa fa-pencil-alt fa-lg text-primary"></i>
                                                </a>
                                            </span>
                                            {!! Form::open(['method'=>'DELETE', 'style'=>'display:inline-block', 'route'=>['courseCategoryGroups.destroy',$courseCategoryGroup->id]]) !!}
                                            <span class="simptip-position-bottom simptip-smooth" data-tooltip="{{trans('buttons.backend.courses.deleteGroup')}}">
                                                <button style="background-color: transparent; border:none" id="group" type="submit" class="fa fa-trash text-danger" onclick="return confirm('Are you sure you want to delete the {{$courseCategoryGroup->abbrv}} group? ALL of the categories associated with this group will also be deleted, not only for this course but for ALL courses.');"></button>
                                            </span>
                                            {!! Form::close() !!}
                                        @endif

                                        <span class="simptip-position-bottom simptip-smooth" data-tooltip="{{trans('buttons.backend.courses.addCategory')}}">
                                            <a href="/courses/courseCategory/create/{{$courseCategoryGroup->id}}/{{$course->id}}" class="btn-xs">
                                                <i class="fa fa-plus fa-lg text-success"></i>
                                            </a>
                                        </span>
                                    </div>

                                    <ul class="list-group list-group-flush">

                                        <?php $courseCategories = \App\Models\Course\CourseCategories::orderBy('abbrv')
                                            ->where('course_category_group_id', $courseCategoryGroup->id)
                                            ->get(); ?>
                                        @foreach ($courseCategories as $courseCategories)
                                            <li class="list-group-item">
                                                {{--lookup to see if the category checkbox should be checked--}}
                                                <?php $courseCategoryChecked = \App\Models\Course\CourseCategory::
                                                    where(['course_category_id' => $courseCategories->id,
                                                           'course_id' => $course->id])
                                                    ->exists(); ?>

                                                    @if( $courseCategoryChecked )
                                                        {{--delete--}}
                                                        {{--get the id that needs deleted--}}
                                                        <?php $courseCategoryID = \App\Models\Course\CourseCategory::
                                                        where(['course_category_id' => $courseCategories->id,
                                                            'course_id' => $course->id])
                                                            ->first()->id; ?>

                                                        {!! Form::open(['method'=>'DELETE', 'style'=>'display:inline-block', 'route'=>['CourseCategory.destroy', $courseCategoryID]]) !!}
                                                            {{ Form::checkbox($courseCategories->id, $courseCategories->id, true, ['onClick' => 'this.form.submit()']) }}
                                                    @else
                                                        {{--add--}}
                                                        {{ Form::open(['route' => ['store_course_category', $course->id, $courseCategories->id], 'style'=>'display:inline-block', 'class' => 'form-inline', 'method' => 'patch']) }}
                                                            {{ Form::checkbox($courseCategories->id, $courseCategories->id, false, ['onClick' => 'this.form.submit()']) }}
                                                    @endif

                                                {{ Form::close() }}

                                                <span title="{{ $courseCategories->description }}">{{ $courseCategories->abbrv }}</span>

                                                    <a href="/courses/courseCategory/edit/{{$courseCategories->id}}/{{$course->id}}" class="btn-xs">
                                                        <i class="fa fa-pencil-alt sm" data-toggle="tooltip" data-placement="top"
                                                           title="{{trans('buttons.backend.courses.editCategory')}}"></i>
                                                    </a>

                                                    {!! Form::open(['method'=>'DELETE', 'style'=>'display:inline-block', 'route'=>['CourseCategories.destroy',$courseCategories->id]]) !!}
                                                    <button id="category" style="background-color: transparent; border:none" data-toggle="tooltip" data-placement="top" title="{{trans('buttons.backend.courses.deleteCategory')}}" type="submit" class="fa fa-trash fa-xs no-border text-muted" onclick="return confirm('Are you sure you want to delete the {{$courseCategories->abbrv}} category? There may be other courses associated with this category.');"></button>
                                                    {!! Form::close() !!}

                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop