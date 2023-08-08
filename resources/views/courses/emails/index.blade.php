@extends('backend.layouts.app')

@section('title', trans('menus.backend.courseEmails.title') . $course_name[0]->abbrv )

@section('after-styles')
@endsection

@section('page-header')
    <h4>
        {{ trans('menus.backend.courseEmails.title') . $course_name[0]->abbrv }}
    </h4>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
        <h3 class="box-title">{{ trans('menus.backend.courseEmails.'.$courseEmails->title) }}</h3>
            <div class="box-tools pull-right">
                @include('courses.emails.partial-header-buttons')
            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table id="courseEmails-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.courseEmails.label') }}</th>
                            <th>{{ trans('labels.courseEmails.email_type') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>                            
                        </tr>                        
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script>
        $(function () {
            "use strict";
            var dt;

            // get data from response
            var res = {!! json_encode($courseEmails) !!};

            // arr of objects to build Datatable is here
            var courseEmails = res.original.data;


            // Creates DataTables
            // @Params - Array of Objects
            function init(data) {
                dt = $('#courseEmails-table').DataTable({
                    ordering: true,
                    destroy: true,
                    info: true,
                    pageLength: 10,
                    data: data,
                    columns: [
                        { data: "label" },
                        { data: "email_type_id" },
                        { data: "actions", name: "actions", orderable: false, searchable: false }, 
                    ],
                    language: {search: "", searchPlaceholder: "Search..."},
                });
            };
            
            // load into Data Tables
            init(courseEmails); 

        });
    </script>

@endsection