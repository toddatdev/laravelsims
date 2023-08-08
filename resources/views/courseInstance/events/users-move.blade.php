@extends('frontend.layouts.app')

@section('before-styles')
    {{ Html::style("/css/jquery-ui/jquery-ui.css") }}
    {{ Html::style("/css/jquery-ui/jquery-ui-timepicker-addon.css") }}
@endsection

@section ('title', trans('labels.scheduling.move_user'))

@section('page-header')
    <div class="row">
        <div class="col-lg-9">
            <h4>{{trans('labels.scheduling.move_user')}}</h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                @if (strpos(url()->previous(), 'waitlist') !== false)
                    <li class="breadcrumb-item">{{ link_to('/mycourses/waitlist', trans('navs.frontend.course.enrollment-requests'), ['class' => '']) }}</li>
                @elseif (strpos(url()->previous(), '/courses/') !== false)
                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$event->courseInstance->course->id, trans('menus.backend.course.title'), ['class' => '']) }}</li>
                @endif
                <li class="breadcrumb-item active">{{ trans('labels.scheduling.move_user') }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! $message !!}</strong>
        </div>
    @endif

    <div id="error-alert-block" class="alert alert-danger alert-block" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong id="error-alert-message"></strong>
    </div>

@endsection

@section('content')

    @php ($today = \Carbon\Carbon::now()->toDateString())
    @php ($today14 = \Carbon\Carbon::now()->addDays(14)->toDateString())

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $event->getCourseNameAndAbbrvAttribute() }}</h3>
                    </div>
                    <div class="card-body">
                        @if($event->isParkingLot())
                            <h5>{!! trans('labels.scheduling.move_parking_lot_to_date', ['user' =>$move_user->NameEmail]) !!}</h5>
                        @else
                            @if($tab == 'waitlist' OR $tab == 'mycourses')
                                <h5>{!! trans('labels.scheduling.move_date_waitlist', ['date' => $event->DisplayStartDate, 'user' =>$move_user->NameEmail]) !!}</h5>
                            @else
                                <h5>{!! trans('labels.scheduling.move_date_selected', ['date' => $event->DisplayStartDate, 'user' =>$move_user->NameEmail]) !!}</h5>
                            @endif
                        @endif

                        {{ Form::open(['route' => 'event.user.move', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'move-form']) }}
                            <!-- date filter -->
                            <div id="date-filter">
                                <div class="container-fluid row">

                                    <div class="col-sm-4 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            {{ Form::label('start-date', trans('labels.general.start_date')) }}
                                            <input type="date" class="form-control" id="start-date" placeholder="{{ trans('labels.general.date') }}" value="{{ $today }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            {{ Form::label('end-date', trans('labels.general.end_date')) }}
                                            <input type="date" class="form-control" id="end-date" placeholder="{{ trans('labels.general.date') }}" value="{{ $today14 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- class datatable html for where to move user selection -->
                            <div class="table-responsive">
                                <table id="users-move-table" class="table table-condensed table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="small">{{ trans('labels.general.date') }}</th>
                                        <th class="small">sort date</th> {{--hidden column for date sort--}}
                                        <th class="small">{{ trans('labels.calendar.location') }}</th>
                                        <th class="text-center small">{{ trans('labels.event.class_size') }}</th>
                                        <th class="text-center small">{{ trans('labels.event.enrolled') }}</th>
                                        <th class="text-center small">{{ trans('labels.event.waitlisted') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="card-footer">
                                <div class="float-left">
                                    @if($tab == 'mycourses')
                                        {{ link_to_route('mycourses.waitlist', trans('buttons.general.cancel'), "", ['class' => 'btn btn-warning btn-md']) }}
                                    @else
                                        {{ link_to_route('event_dashboard', trans('buttons.general.cancel'), [$event_user->event_id, $tab], ['class' => 'btn btn-warning btn-md']) }}
                                    @endif
                                </div>
                                <div class="float-right">
                                    {{ Form::submit(trans('labels.scheduling.move_user'), ['class' => 'btn btn-success btn-md', 'id' => 'submit_button']) }}
                                </div>
                            </div>

                            {{-- hidden fields for submission --}}
                            {{ Form::hidden('event_user_id', $event_user->id, ['id' => 'event_user_id']) }}
                            {{ Form::hidden('event_id', $event_user->event_id, ['id' => 'event_id']) }}
                            {{ Form::hidden('event_move_id', null, ['id' => 'event_move_id']) }}
                            {{ Form::hidden('tab', $tab, array('id' => 'tab')) }}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('after-scripts')

    {{ Html::script("/js/modernizr.js") }}
    {{ Html::script("/js/jquery-ui.js") }}
    {{ Html::script("/js/jquery-ui-timepicker-addon.js") }}
    {{ Html::script("/js/datetime.js") }}
    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}

    <script>

        var courseLocation;
        var courseDate;
        var courseTime;

        $(function() {
            var start_date = $('#start-date').val();
            var end_date = $('#end-date').val();
            var event_id = $('#event_id').val();

            initUsersMoveTable(start_date, end_date, event_id);

            // select a datatable item
            $('#users-move-table tbody').on( 'click', 'tr', function () {
                actionSelected();
            });


        });
        // when an item is selected in the table, get it's information for storage and move wall notification
        // this requires a setTimeout function or else it wont register
        function actionSelected() {

            setTimeout(function(){
                var idx = usersMoveTable.cell('.selected', 0).index();
                var data = usersMoveTable.rows(idx.row).data();

                // set the event_move_id (event to move user to)
                $('#event_move_id').val(data[0]['event_id']);

                // set these so we can see them in the moving (are you sure) wall
                courseLocation = data[0]['location'];
                courseDate = data[0]['date'];
                courseTime = data[0]['time'];
            }, 50);
        }


        // submit button display SWAL warnings
        $('#submit_button').click(function(e) {

            event.preventDefault();

            // if no event is selected warn user that a date must be selected
            if (courseDate == undefined) {
                swal({
                    title: "{{ trans('alerts.frontend.eventusers.select_class_wall') }}",
                    text: "{{ trans('alerts.frontend.eventusers.select_class_text_wall') }}",
                    icon: "warning",
                    buttons: {cancel: "OK"},
                    dangerMode: true,
                })

            // move user confirmation alert
            } else {

                var content = document.createElement('div');

                //parking lot to event
                @if($event->isParkingLot())

                    content.innerHTML = "{!! trans('alerts.frontend.eventusers.moveParkingLotToEvent', ['name'=>"<h4 style='font-weight:bolder; color:blue;'>".$move_user->name."</h4>"]) !!}"

                @else
                    //event to event
                    @if($tab == 'roster')

                        content.innerHTML = "{!! trans('alerts.frontend.eventusers.moveEventToEvent', ['name'=>"<h4 style='font-weight:bolder; color:blue;'>".$move_user->name."</h4>", 'fromDate'=>$event->DisplayStartDate]) !!}"

                    //waitlist to event
                    @elseif($tab == 'waitlist' OR $tab == 'mycourses')

                        content.innerHTML = "{!! trans('alerts.frontend.eventusers.moveWaitListToEvent', ['name'=>"<h4 style='font-weight:bolder; color:blue;'>".$move_user->name."</h4>", 'fromDate'=>$event->DisplayStartDate]) !!}"

                    @endif

                @endif

                //the courseDate is appended here because it cannot be passed into a trans like the php variables
                content.innerHTML += "<p><span style='color:green'>" + courseDate + "</span>?</p>";


                swal({
                    title: "{{ trans('labels.scheduling.move_user') }}",
                    content: content, //this is set above so it can include html
                    icon: "warning",
                    buttons: true,
                    showCancelButton: true,
                    dangerMode: true,
                    confirmButtonColor: "#DD6B55",
                }).then(function(confirmed) {
                    if (confirmed)
                        $('#move-form').submit();
                });

            }
        });


        // day dropdown changes - reload datatable
        $('#start-date, #end-date').on('change', function() {
            start_date = $('#start-date').val();
            end_date = $('#end-date').val();
            event_id = $('#event_id').val();
            usersMoveTable.clear();
            usersMoveTable.destroy();
            usersMoveTable = null;

            initUsersMoveTable(start_date, end_date, event_id);
        });


        // initUsersMove datatable
        function initUsersMoveTable(start_date = null, end_date = null, event_id = null) {
            usersMoveTable = $('#users-move-table').DataTable({
                ajax: {
                    url: '{!! url('usersmovetable.data') !!}',
                    data: function (d) {
                        d.start_date = start_date;
                        d.end_date = end_date;
                        d.event_id = event_id;
                    }
                },
                select: 'single',
                lengthChange: false,
                paging: false,

                dom: '<"top">rt<"bottom"lp><"clear">',

                responsive: true,

                language: {emptyTable: "{!!trans('labels.event.no_events')!!}",
                           searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}",
                           search: ""},

                columns: [
                    { data: 'event_id', name: 'event_id', visible:false },
                    { data: 'date', name: 'date', responsivePriority : 1},
                    { data: 'date_sort', name: 'date_sort', responsivePriority : 6},
                    { data: 'location', name: 'location', responsivePriority : 2},
                    { data: 'class_size', name: 'size', responsivePriority : 3, className: 'dt-body-center'},
                    { data: 'num_enrolled', name: 'enrolled', responsivePriority : 4, className: 'dt-body-center'},
                    { data: 'num_waitlist', name: 'waitlisted', responsivePriority : 5, className: 'dt-body-center'},
                ],
                columnDefs: [
                    { 'orderData':[2], 'targets': [1] }, //sort date by date_sort column and hide date_sort
                    {
                        'targets': [2],
                        'visible': false,
                        'searchable': false
                    },
                    { width: 400, targets: 1 } //this is here because otherwise on date change the column gets unnecessarily narrow on reload
                ],

            });
        }

    </script>

@endsection