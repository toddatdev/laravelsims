@extends('frontend.layouts.app')

@section('after-styles')
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <!-- site permissions panel -->
            <div class="panel panel-default">
                <div class="panel-heading"><h4>{{ trans('labels.accountpermissions.site_permissions') }}</h4></div>

                <div class="panel-body">

                        <div class="table-responsive">
                            <table id="site-table" class="table table-condensed table-hover dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{ trans('labels.accountpermissions.permission') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                </div>
            </div> <!-- panel -->


            <!-- course permissions panel -->
            <div class="panel panel-default">
                <div class="panel-heading"><h4>{{ trans('labels.accountpermissions.course_permissions') }}</h4></div>

                <div class="panel-body">

                        <div class="table-responsive">
                            <table id="course-table" class="table table-condensed table-hover dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{ trans('labels.accountpermissions.course') }}</th>
                                        <th>{{ trans('labels.accountpermissions.permission') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                </div>
            </div> <!-- panel -->


            <!-- event permissions panel -->
            <div class="panel panel-default">
                <div class="panel-heading"><h4>{{ trans('labels.accountpermissions.event_permissions') }}</h4></div>

                <div class="panel-body">
                        <div class="table-responsive">
                            <table id="event-table" class="table table-condensed table-hover dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{ trans('labels.accountpermissions.course') }}</th>
                                        <th>{{ trans('labels.accountpermissions.event') }}</th>
                                        <th>{{ trans('labels.accountpermissions.permission') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                </div>
            </div><!-- panel -->


        </div><!-- col-xs-12 -->
    </div><!-- row -->
@endsection


@section('after-scripts')


    <script>

        // load datatables on page load
        $(function() {
            initSiteTable();
            initCourseTable();
            initEventTable();
        });


        // init the site permissions datatable
        function initSiteTable() {
            dtSite = $('#site-table').DataTable({
                ajax: {
                    url: '{!! url("accountpermissions.data") !!}',
                    data: function (d) {
                    d.role_type = 1; // this sets the role_type in accountPermissionsTableData (AccountPermissionsController.php)
                    }
                },
                bFilter: true,
                buttons: false,
                destroy: true,
                //customize no data message
                language: {emptyTable: "{{trans('labels.accountpermissions.no_data')}}"},
                //Add an "All" to the "Show entries" pulldown menu
                pageLength: 10,
                "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
                //Set the format for table and surrounding functionality
                dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"><"col-sm-4"f>><r>tip',
                // dom: 'lfrtip', //"Classic" button layout
                columns: [
                    { data: 'permission_id', name: 'permission_id', visible: false },
                    { data: 'permission_name', name:'permission_name' },
                ],
            });
        }


        // init the course permissions datatable
        function initCourseTable() {
            dtCourse = $('#course-table').DataTable({
                ajax: {
                    url: '{!! url("accountpermissions.data") !!}',
                    data: function (d) {
                    d.role_type = 2;
                    }
                },
                bFilter: true,
                buttons: false,
                destroy: true,
                //customize no data message
                language: {emptyTable: "{{trans('labels.accountpermissions.no_data')}}"},
                //Add an "All" to the "Show entries" pulldown menu
                pageLength: 10,
                "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
                //Set the format for table and surrounding functionality
                dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"><"col-sm-4"f>><r>tip',
                // dom: 'lfrtip', //"Classic" button layout
                columns: [
                    { data: 'permission_id', name: 'permission_id', visible: false },
                    { data: 'course_name', name:'course_name' },
                    { data: 'permission_name', name:'permission_name' },
                ],
            });
        }


        // init the event permissions datatable
        function initEventTable() {
            dtEvent = $('#event-table').DataTable({
                ajax: {
                    url: '{!! url("accountpermissions.data") !!}',
                    data: function (d) {
                    d.role_type = 3;
                    }
                },
                bFilter: true,
                buttons: false,
                destroy: true,
                //customize no data message
                language: {emptyTable: "{{trans('labels.accountpermissions.no_data')}}"},
                //Add an "All" to the "Show entries" pulldown menu
                pageLength: 10,
                "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
                //Set the format for table and surrounding functionality
                dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"><"col-sm-4"f>><r>tip',
                // dom: 'lfrtip', //"Classic" button layout
                columns: [
                    { data: 'permission_id', name: 'permission_id', visible: false },
                    { data: 'course_name', name:'course_name' },
                    { data: 'event', name:'event' },
                    { data: 'permission_name', name:'permission_name' },
                ],
            });
        }

    </script>

@endsection