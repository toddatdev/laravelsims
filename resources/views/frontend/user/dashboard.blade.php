@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.dashboard'))

@php ($today = \Carbon\Carbon::now()->timezone(session('timezone'))->toDateString())
@php ($today7 = \Carbon\Carbon::now()->timezone(session('timezone'))->addDays(7)->toDateString())

{{ Html::style("/css/jquery-ui/jquery-ui.css") }}
{{ Html::style("/css/jquery-ui/jquery-ui-timepicker-addon.css") }}

<style>

    table.dataTable.no-footer {
        border-bottom: 0;
    }

</style>

@section('content')
    <section class="content">

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="row">
            <div class="col-12">

                <!-- today's schedule -->
                @permission('scheduling')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ Session::get('site_abbrv') }} {{ trans('navs.frontend.scheduling.sim_center_schedule') }} - {{ Carbon\Carbon::parse(now())->timezone(session('timezone'))->format('l, F d, Y') }}</h3>
                        <div class="float-right">
                            <input type="text" class="search form-control" placeholder="Search..." id="schedule-search">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            {{--  2020-11-17 mitcks the data order here disables default behavior so that it can be turned off for action column - workaround for bootstrap-4--}}
                            <table id="schedule-table" class="table table-hover dt-responsive nowrap" data-order="[]" width="100%">
                                <thead>
                                <th>{{ trans('labels.general.time') }}</th>
                                <th>{{ trans('labels.shared.course') }}</th> {{--course abbrv--}}
                                <th></th> {{--images--}}
                                <th>{{ trans('labels.calendar.initial_meeting_room') }}</th>
                                <th></th> {{--actions--}}
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            @endauth <!-- /scheduling -->

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.my_events') }}</h3>

                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    {{--spacing--}}
                                </div>
                                <div class="col-sm">
                                    <select class="form-control" id="myclasses-days" >
                                        <option value="7">{{ trans('labels.dashboard.next_7') }}</option>
                                        <option value="14">{{ trans('labels.dashboard.next_14') }}</option>
                                        <option value="30">{{ trans('labels.dashboard.next_30') }}</option>
                                        <option value="1">{{ trans('labels.dashboard.next_3months') }}</option>
                                        <option value="0">{{ trans('labels.dashboard.custom_date_range') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <input type="text" class="search form-control " id="myclasses-search" placeholder="Search...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row" id="custom_range" style="display:none;">
                            <form class="form-inline">
                                <div class="form-group row">
                                    <label class="col-lg-2 control-label text-md-right">{{ trans('labels.dashboard.start') }}</label>
                                    <input type="date" class="date-select form-control col-lg-4 col-xs-2" id="myclasses-start" placeholder="{{ trans('labels.general.date') }}" value="{{ $today }}">

                                    <label class="col-lg-2 control-label text-md-right">{{ trans('labels.dashboard.end') }}</label>
                                    <input type="date" class="date-select form-control col-lg-4 col-xs-2" id="myclasses-end" placeholder="{{ trans('labels.general.date') }}" value="{{ $today7 }}">
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            {{--  2020-11-17 mitcks the data order here disables default behavior so that it can be turned off for action column - workaround for bootstrap-4--}}
                            <table id="myclasses-table" class="table table-hover dt-responsive nowrap" data-order="[]" width="100%">
                                <thead>
                                    <th>{{ trans('labels.general.date') }}</th>
                                    <th>{{ trans('labels.general.time') }}</th>
                                    <th>{{ trans('labels.shared.course') }}</th>
                                    <th>{{ trans('labels.calendar.initial_meeting_room') }}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- my enrollment requests -->
                {{--if user is on waitlist for any events display them here--}}
                @if (!$enrollmentRequests->isEmpty())
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title">{{ trans('navs.frontend.my-enrollment-requests') }}</h3>
                        </div>
                        <div class="card-body">
                            {{--                            <ul class="list-group list-group-flush">--}}

                            {{--loop through events--}}
                            @foreach($enrollmentRequests as $enrollmentRequest)

                                @if($enrollmentRequest->status_id == 5)
                                    <blockquote class="quote-danger bg-light">
                                @else
                                   <blockquote class="quote-primary bg-light">
                                @endif

                                @if($enrollmentRequest->event->isParkingLot())
                                    @if($enrollmentRequest->status_id==3)
                                        {!! trans('strings.frontend.self_parked',
                                        ['eventName' => $enrollmentRequest->event->CourseNameAndAbbrv,
                                         'createdAt' => $enrollmentRequest->DisplayCreatedAt,
                                         'siteEmail' => $site->email,
                                         'eventDate' => $enrollmentRequest->event->DisplayDateStartEndTimes,
                                         'eventId' => $enrollmentRequest->event->id]) !!}
                                    @else
                                        {!! trans('strings.frontend.parked_from_event',
                                        ['eventName' => $enrollmentRequest->event->CourseNameAndAbbrv,
                                         'createdAt' => $enrollmentRequest->DisplayCreatedAt,
                                         'siteEmail' => $site->email,
                                         'eventDate' => $enrollmentRequest->event->DisplayDateStartEndTimes,
                                         'eventId' => $enrollmentRequest->event->id]) !!}
                                    @endif
                                @else
                                   @if($enrollmentRequest->status_id==5)
                                       {!! trans('strings.frontend.pending_payment_request',
                                      ['eventName' => $enrollmentRequest->event->DisplayEventName,
                                      'createdAt' => $enrollmentRequest->DisplayCreatedAt,
                                      'siteEmail' => $site->email,
                                      'eventDate' => $enrollmentRequest->event->DisplayDateStartEndTimes,
                                      'eventUserId' => $enrollmentRequest->id,
                                      'eventUserPaymentId' => $enrollmentRequest->eventUserPayment->id]) !!}
                                   @else
                                       {!! trans('strings.frontend.waitlist_request',
                                       ['eventName' => $enrollmentRequest->event->DisplayEventName,
                                       'createdAt' => $enrollmentRequest->DisplayCreatedAt,
                                       'siteEmail' => $site->email,
                                       'eventDate' => $enrollmentRequest->event->DisplayDateStartEndTimes,
                                       'eventId' => $enrollmentRequest->event->id]) !!}
                                   @endif
                                @endif

                                </blockquote>

                            @endforeach
                        </div>
                    </div>
            @endif <!-- my enrollment requests -->

            </div>
        </div>


        <!-- dashboard modals -->

        <!-- ajax modal -->
        <div class="modal" id="modal" tabindex="-1" role="dialog" labelledby="remoteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-body" >
                            <h3 class="push-down-20" >Loading..</h3>
                            <div class="progress progress-striped active">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- catalog modal -->
        <div class="modal" id="modal-catalog" tabindex="-1" role="dialog" labelledby="remoteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-body" >
                            <h3 class="push-down-20" >Loading..</h3>
                            <div class="progress progress-striped active">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('after-scripts')

    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}
    {{ Html::script("/js/modernizr.js") }}
    {{ Html::script("/js/jquery-ui.js") }}
    {{ Html::script("/js/jquery-ui-timepicker-addon.js") }}
    {{ Html::script("/js/datetime.js") }}

    {{--for latest pro font awesome icons--}}
    {{--<script src="https://kit.fontawesome.com/ede0b991ce.js" crossorigin="anonymous"></script>--}}

    <script>

        $(function() {

            // set myclasses datatable to 7 days
            initMyClassesTable(7);

            // set schedule-table datatable
            initScheduleTable();

            //mitcks(2021-01-14) - commented out these sections related to the sweet alerts for deleting
            // because the delete button no longer appears on this page

            // var eventToDelete;
            // var content;

            //mitcks: set the name of the event being deleted to use in sweet alert
            {{--$('#schedule-table').on('click', 'tbody tr', function () {--}}
            {{--    var row = scheduleTable.rows($(this)).data();--}}
            {{--    eventToDelete = row[0]['course_name'];--}}
            {{--    //mitcks: this is here to pass html to the sweet alert so event name is blue--}}
            {{--    content = document.createElement('div');--}}
            {{--    content.innerHTML = "{{ trans('alerts.general.confirm_delete_content') }}"+'</br><span style="font-weight:bolder; color:blue;">'+ eventToDelete +'</span>';--}}
            {{--} );--}}

            //mitcks: set the name of the event being deleted to use in sweet alert
            {{--$('#myclasses-table').on('click', 'tbody tr', function () {--}}
            {{--    var row = myClassesTable.rows($(this)).data();--}}
            {{--    eventToDelete = row[0]['course_name'];--}}
            {{--    //mitcks: this is here to pass html to the sweet alert so event name is blue--}}
            {{--    content = document.createElement('div');--}}
            {{--    content.innerHTML = "{{ trans('alerts.general.confirm_delete_content') }}"+'</br><span style="font-weight:bolder; color:blue;">'+ eventToDelete +'</span>';--}}
            {{--} );--}}

            // delete wall for delete event in schedule datatable
            {{--$("body").on("click", "a[name='delete_event']", function(e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var href = $(this).attr("href");--}}
            {{--    swal({--}}
            {{--        title: "{{ trans('alerts.general.confirm_delete') }}",--}}
            {{--        content: content, //this is set above so it can include html--}}
            {{--        icon: "warning",--}}
            {{--        buttons: true,--}}
            {{--        dangerMode: true,--}}
            {{--    })--}}
            {{--        .then(function(isConfirmed) {--}}
            {{--            if (isConfirmed) {--}}
            {{--                window.location.href = href;--}}
            {{--            } else {--}}
            {{--            }--}}
            {{--        });--}}
            {{--});--}}


            // search boxes attached to datatable
            $('#schedule-search').keyup(function(){
                scheduleTable.search($(this).val()).draw();
            })

            $('#myclasses-search').keyup(function(){
                myClassesTable.search($(this).val()).draw();
            })


            //mitcks(2021-01-14) - commented this out because there are no longer any modals opened from this page
            // allow tooltip on modal button
            // $('[data-tooltip="tooltip"]').tooltip();
            //
            // // modal refresh fix
            // $(document).on('hidden.bs.modal', function (e) {
            //     $(e.target).removeData('bs.modal')
            //         .find(".modal-content").html('<form action="" method="post"><div class="modal-body" ><h3 class="push-down-20" >Loading..</h3><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div></div></form>');
            // });

        });


        // day dropdown changes - reload datatable
        $('#myclasses-days').on('change', function() {

            var days = $('#myclasses-days').val();
            var start_date = $('#myclasses-start').val();
            var end_date = $('#myclasses-end').val();

            // if date range (0)
            if (days == 0) {
                $('#custom_range').show();
                $('#myclasses-start').show();
                $('#myclasses-end').show();
                myClassesTable.clear();
                myClassesTable.destroy();
                myClassesTable = null;
                initMyClassesTable(days, start_date, end_date);
            } else {

                $('#custom_range').hide();
                $('#myclasses-start').hide();
                $('#myclasses-end').hide();

                myClassesTable.clear();
                myClassesTable.destroy();
                myClassesTable = null;

                initMyClassesTable(days);
            }
        });


        // reload datatable when start/end date ranges change
        $('#myclasses-start, #myclasses-end').on('change', function() {
            var days = $('#myclasses-days').val();
            var start_date = $('#myclasses-start').val();
            var end_date = $('#myclasses-end').val();

            $('#myclasses-start').show();
            $('#myclasses-end').show();
            myClassesTable.clear();
            myClassesTable.destroy();
            myClassesTable = null;
            initMyClassesTable(days, start_date, end_date);
        });

        // init myclasses-table datatable
        function initMyClassesTable(date_type, start_date = null, end_date = null) {

            // console.log('date_type:' + date_type);
            // console.log('start_date:' + start_date);
            // console.log('end_date:' + end_date);

            myClassesTable = $('#myclasses-table').DataTable({
                destroy: true,
                lengthChange: false,
                paging: false,
                sDom: '',
                ajax: {
                    url: '{!! url('myclassestable.data') !!}',
                    data: function (d) {
                        d.date_type = date_type;
                        d.start_date = start_date;
                        d.end_date = end_date;
                    }
                },

                language: {emptyTable: "{{trans('labels.calendar.no_data_my_events')}}"},

                responsive: true,
                columns: [
                    { data: 'date', name: 'date'},
                    { data: 'time', name: 'time'},
                    { data: 'courses.abbrv', name: 'courses.abbrv', "render": function (data, type, full) {
                            return '<span data-toggle="tooltip" title="' + full.course_name + '">' + data + '</span>';
                        }
                    },
                    { data: 'building_location_room', name: 'building_location_room', responsivePriority : 4, "render": function (data, type, full) {
                            return '<span data-toggle="tooltip" title="' + full.event_rooms + '">' + data + '</span>';
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    { data: 'course_name', name: 'course_name', visible:false, width: "0px" }, //for search
                    { data: 'start_time_only', name: 'start_time_only', visible:false}, //for date sort
                    { data: 'end_time_only', name: 'end_time_only', visible:false}, //for date sort
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 2 },
                    { orderData: [6,7], targets: 1} //order by hidden columns 6 and 7 instead of formatted column 0
                ]
            });
        }

        // init schedule-table datatable
        function initScheduleTable() {

            scheduleTable = $('#schedule-table').DataTable({
                lengthChange: false,
                paging: false,
                info: false,
                sDom: '',
                ajax: {
                    url: '{!! url('day.data') !!}', // uses same calendar day view datatable
                    data: function (d) {
                        d.start_date = '{{ $today }}';
                        d.location = 'all';
                    }
                },

                language: {emptyTable: "{{trans('labels.calendar.no_data_today')}}"},

                responsive: true,

                columns: [
                    { data: 'time', name: 'time' },
                    { data: 'courses.abbrv', name: 'courses.abbrv', "render": function (data, type, full) {
                            return '<span data-toggle="tooltip" title="' + full.course_name + '">' + data + '</span>';
                        }
                    },
                    { data: 'images', name: 'images', orderable: false, searchable: false },
                    { data: 'building_location_room', name: 'building_location_room', "render": function (data, type, full) {
                            return '<span data-toggle="tooltip" title="' + full.event_rooms + '">' + data + '</span>';
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, width: "90px" },
                    {data: 'start_time', name: 'start_time', visible:false}, //for date sort
                    {data: 'end_time', name: 'end_time', visible:false}, //for date sort
                ],

                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 },
                    { orderData: [5,6], targets: 0} //order by hidden columns 5 and 6 instead of formatted column 0
                ],


            });
        }

    </script>

@endsection
