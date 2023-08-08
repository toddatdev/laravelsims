@extends('frontend.layouts.app')

@section ('title', 'Pending Enrollments')


@section('page-header')
    <h1>Pending Enrollments</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            {{-- <div class="panel-heading">
        
                {{ Form::label('course', 'Course ', ['class' => 'col-lg-2 control-label']) }}
                <div class="col-lg-4">
                    {!! Form::select('course', $allowedCourses, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select'), 'id' => 'course']) !!}
                </div>

                <div class="clearfix"></div>
            </div> --}}
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="request-table" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('labels.enrollment.name') }}</th>
                                <th>{{ trans('labels.enrollment.comments' )}}</th>
                                <th>{{ trans('labels.enrollment.course') }}</th>
                                <th>{{ trans('labels.enrollment.event-date') }}</th>
                                <th>{{ trans('labels.enrollment.where') }}</th>
                                <th>{{ trans('labels.enrollment.role') }}</th>
                                <th>{{ trans('labels.enrollment.requested') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th> 
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>
    
@endsection

@section('after-scripts')
    {{-- Load Datatable scritps --}}
    {{ Html::style("/css/DataTables/datatables.css") }}
    {{ Html::script("/js/DataTables/datatables.js") }}    
    <script>    
        $(function() {
            var dt;
            // let course_id = $('#course').val();          
            
            // First Load the DT for All on init
            init()
            
            // Reload table on any changes
            // $("#course").on('change', function() {
            //     if (!dt) {
            //         course_id = $(this).val();
            //         init();                        
            //     }else {
            //         dt.clear();
            //         dt.destroy();
            //         dt = null;
            //         course_id = $(this).val();
            //         init();                                          
            //     }                
            // });

            function init() {
                dt = $('#request-table').DataTable({
                    serverSide: true,
                    ajax: {
                        url: '{!! url("requestPending.data") !!}',
                        // data: function (d) {
                        //     d.course_id = course_id;
                        // }
                    },
                    language: {search: "", searchPlaceholder: "Search..."},
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    columns: [
                        { data: "name" },
                        { data: "comments", name: "comments", orderable: false, searchable: false },
                        { data: "course" },
                        { data: "event_date" },
                        { data: "where" },
                        { data: "role_name" },
                        { data: "requested_time" },
                        { data: "actions", name: "actions", orderable: false, searchable: false }, 
                    ],
                    ordering: true,
                    destroy: true,
                    info: true,
                    pageLength: 10,
                });
            }
        });
    </script>
@endsection