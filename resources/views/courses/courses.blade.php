@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.course.my_courses'))

@section('after-styles')

@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.course.my_courses')}}</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="courses-table" class="table table-condensed table-hover " width="100%">
                                <thead>
                                <tr>
                                    <th data-priority="1">{{ trans('labels.courses.abbrv') }}</th>
                                    <th data-priority="4">{{ trans('labels.courses.name') }}</th>
                                    <th data-priority="3">{{ trans('labels.general.role') }}</th>
                                    <th data-priority="2">{{ trans('labels.general.actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('after-scripts')

     <script>

        $(function() {

            dt = $('#courses-table').DataTable({

                ajax: {
                    url: '{!! url('mycourses.data') !!}',
                    data: function (d) {
                    }
                },

                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

                //Add the buttons to download the table data.
                buttons: ['excelHtml5', 'pdf', 'copy'],

                // This is needed for bootstrap 4 buttons to be inline with search
                // dom:"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-5'i><'col-sm-7'p>>",

                dom: '<"top"fB>rt<"bottom"lp><"clear">',

                language: {emptyTable: "{{trans('labels.courses.you_no_courses')}}"},

                responsive: true,

                columns: [
                    { data: 'abbrv',     name: 'abbrv',     responsivePriority : 1 },
                    { data: 'name',      name: 'name',      responsivePriority : 4 },
                    { data: 'role_name', name: 'role_name', responsivePriority : 3 },
                    { data: 'actions',   name: 'actions',   responsivePriority : 2, orderable: false, searchable: false},
                ],

                order: [[0, 'asc']],

            });

        });

    </script>
@stop