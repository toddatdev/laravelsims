@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.course.enrollment-requests'))

@section('after-styles')

    <style>
        /* centers DataTable buttons and bumps them down so not so close to bottom border */
        .dataTables_wrapper .dt-buttons {
            float:none;
            text-align:center;
            padding-top: 10px;
        }

    </style>
@stop

@section('page-header')

    {{--used for alert messages--}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! $message !!}</strong>
        </div>
    @endif

@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.course.enrollment-requests')}}</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            {{--CSS bootstrap classes used:--}}
                            {{--small: used for font size, only necessary on DataTables with many columns you are trying to fit on screen--}}
                            {{--table: adds borders--}}
                            {{--table-condensed: cuts cell padding in half--}}
                            {{--table-hover: adds hover effect to rows--}}
                            {{--CSS custom classes used:--}}
                            {{--indent_first_child_2: located in sims.css, indents child rows, should only be used on tables with 2 level grouping--}}
                            <table id="my-waitlist-table" class="dataTables table table-condensed table-hover indent_first_child_2" >
                                <thead>
                                <tr>
                                    {{--this first th is not displayed because the DataTable is grouped by course name,
                                        however you still need it here as a placeholder or the DataTable will not load correctly --}}
                                    <th>{{ trans('labels.general.name') }}</th>
                                    <th>{{ trans('labels.scheduling.course') }}</th>
                                    <th>{{ trans('labels.enrollment.event-date') }}</th>
                                    <th>{{ trans('labels.general.role') }}</th>
                                    <th>{{ trans('labels.scheduling.req_date') }}</th>
                                    <th>{{ trans('labels.event.notes') }}</th>
                                    <th>{{ trans('labels.general.actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="historyModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

    </section>


@endsection

@section('after-scripts')

    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}
    <script>

        $(document).ready(function() {

            // Build WAITLIST DataTable
            waitlistTable = $('#my-waitlist-table').DataTable({
                //this ajax call gets the data from a URL/route defined in web.php,
                // that route point to a function in a controller (in this case it is the myCoursesWaitlistTableData in EventUsersController.php)
                // that gets the data from the database and returns it in a DataTable format
                ajax: {
                    url: '{!! route('mycourses.waitlist.table') !!}',
                    data: function (d) {
                    }
                },
                // set the format for table and surrounding functionality
                //dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>><r>tip', //buttons at top
                dom: '<"top"f>rt<"bottom"B><"clear">', //buttons at bottom, no paging
                //paging set to false
                paging: false,

                //this adds the word "Search..." as placeholder in search box, and "No data available" empty message
                language: {emptyTable: "{!!trans('labels.general.no_data')!!}",
                    searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}",
                    search: ""},
                searching: true,

                //this collapses the table for small screens
                responsive: true,

                //setup columns, grouping column should be first, note the array starts with 0 when referencing columns
                columns: [
                    {data: 'courseNameAbbrv', name: 'courseNameAbbrv', searchable:true}, // used for grouping header
                    {data: 'name', name: 'name', searchable:false}, // user's full name and email, search is false because instead the search is done on idividual fields below
                    {data: 'event_date', name: 'event_date', searchable:true},
                    {data: 'role', name: 'role', searchable:true},
                    {data: 'request_date', name: 'request_date', searchable:true},
                    {data: 'request_notes', name: 'request_notes', searchable:true},
                    {data: 'actions', name: 'actions', searchable:false},
                    {data: 'firstName', name: 'firstName', searchable:true}, //for search only
                    {data: 'lastName', name: 'lastName', searchable:true}, //for search only
                    {data: 'email', name: 'email', searchable:true}, //for search only
                ],

                columnDefs: [
                    {'visible': false, "targets": [0,2,7,8,9]}, // courseNameAbbrv (group header) and search columns hidden
                    { width: 150, targets: 5 }, //set width of notes column so longer note doesn't force the other columns to wrap
                    { width: 175, targets: 6 }, //set with of actions column to avoid wrapping (nowrap class does not work)
                ],

                order: [[0, 'asc'],[2, 'asc'] ], //by course for grouping, then by date submitted

                //this selects which column to use for grouping, works best if this is the first column (position 0)
                rowGroup: {
                    dataSrc: [ 'courseNameAbbrv', 'event_date' ],
                },

                //set which buttons appear and what columns are exported
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: false,
                        exportOptions: {
                            columns: [0,1,2,3,4,5,7,8]
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: false,
                        exportOptions: {
                            columns: [0,1,2,3,4,5,7,8]
                        }
                    },
                    {
                        extend: 'copy',
                        footer: false,
                        exportOptions: {
                            columns: [0,1,2,3,4,5,7,8]
                        }
                    }
                ],
            });

        });

        $("#historyModal").on('show.bs.modal', function (e) {
            var triggerLink = $(e.relatedTarget);
            var id = triggerLink.data("id");

            $(this).find(".modal-body").load("/event/users/history/"+id);
        });

        // DELETE USER FROM WAITLIST SWEET ALERT
        // delete_wailist is the name of the button defined in EventUser model
        $("body").on("click", "a[name='delete_wailist']", function(e) {

            e.preventDefault();
            var href = $(this).attr("href");

            //these are set where the delete button is created in the EventUser model
            var fullname = $(this).data('fullname');
            var eventdate = $(this).data('eventdate');

            //set alert content here so it can contain html
            var content = document.createElement('div');
            content.innerHTML = "{{ trans('alerts.frontend.eventusers.deleteConfirmPart1') }}"+'<h4 style="font-weight:bolder; color:blue;">'+ fullname +'</h4>' + " {{ trans('alerts.frontend.eventusers.myWaitlistDeleteConfirmPart2') }}" + eventdate;

            swal({
                title: "{{ trans('alerts.general.confirm_delete') }}",
                content: content, //this is set above so it can include html
                icon: "warning",
                buttons: true,
                showCancelButton: true,
                dangerMode: true,
                confirmButtonColor: "#DD6B55",
                }).then(function(confirmed) {
                    if (confirmed)
                        window.location = href;
            });
        })

        // PARK USER USER SWEET ALERT (this function is OK here instead of partial-waitlist because if a user has access to one, they have access to both)
        $("body").on("click", "a[name='park_button']", function(e) {
            e.preventDefault();
            var href = $(this).attr('href');

            //these are set where the delete button is created in the EventUser model
            var fullname = $(this).data('fullname');
            var eventdate = $(this).data('eventdate');
            var waitlisttext = $(this).data('waitlisttext');

            var content = document.createElement('div');
            content.innerHTML = "{{ trans('alerts.frontend.eventusers.parkConfirmPart1') }}"+'<h4 style="font-weight:bolder; color:blue;">'+ fullname +'</h4>' + " {{ trans('alerts.frontend.eventusers.parkConfirmPart2') }}" + waitlisttext + eventdate;

            swal({
                title: "{{ trans('labels.event.parkingLot') }}",
                content: content, //this is set above so it can include html
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(confirmed) {
                if (confirmed)
                    window.location = href;
            });
        });

    </script>
@stop