@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.course.view-course'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.course.title') }}
    </h4>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.course.view-course') }}</h3>
                        <div class="float-right">
                            @include('courses.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>{{ trans('labels.courses.abbrv') }}</th>
                                <td>{{ $course->abbrv }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.name') }}</th>
                                <td>{{ $course->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.author_name') }}</th>
                                <td>{{ $course->author_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.virtual') }}</th>
                                <td>
                                    @if( $course->virtual == "1")
                                        <i class="fa fa-check-square fa-lg" data-toggle="tooltip" data-placement="top" size="5"></i>
                                    @else
                                        <i class="fa fa-square-o fa-lg" data-toggle="tooltip" data-placement="top"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.catalog_image') }}</th>
                                <td>
                                    @if($course->catalog_image)
                                        <img src="{{URL::to($course->catalog_image)}}" class="img-thumbnail mt-10" width="350">
                                    @else
                                        <span class="mt-10">An image has not been uploaded for this course.</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.catalog_description') }}</th>
                                <td>{!!html_entity_decode($course->catalog_description)!!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.created_at') }}</th>
                                <td>{{ $course->created_at }} ({{ $course->created_at->diffForHumans() }})</td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.created_by') }}</th>
                                <td>{{ $course->created_by }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('labels.courses.updated_at') }}</th>
                                <td>{{ $course->updated_at }} ({{ $course->updated_at->diffForHumans() }})</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop