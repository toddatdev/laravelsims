@extends('frontend.layouts.app')

@php ($today = \Carbon\Carbon::now())

@section ('title', trans('navs.frontend.course.manage_courses') . ' | ' . trans('labels.scheduling.pending_requests'))

@section('page-header')
@endsection

@section('content')
    {{ Html::style("css/schedule-request.css") }}

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" id="heading">{{ trans('labels.scheduling.pending_requests') }}</h3>
                        <div class="float-right">
                            @include('courseInstance.scheduleRequest.partial-header-buttons')
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- date filter for approved, denied, all views -->
                        <div id="date-filter" style="display:none;">
                            <div class="container-fluid row align-items-end">
                                <div class="col-lg-3 text-lg-right">
                                    <div class="form-group">
                                        {{ Form::label('event-date-filter', trans('labels.scheduling.event_date_filter')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        {{ Form::label('start-date', trans('labels.general.start_date')) }}
                                        <div class="input-group date">
                                            {{ Form::text('start-date', \Carbon\Carbon::parse(now()->subDays(90))->format('m-d-Y'), ['class' => 'form-control', 'id' => 'start-date' , 'maxLength' => '1000']) }}
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        {{ Form::label('end-date', trans('labels.general.end_date')) }}
                                        <div class="input-group date">
                                            {{ Form::text('end-date', \Carbon\Carbon::parse(now())->format('m-d-Y'), ['class' => 'form-control', 'id' => 'end-date' , 'maxLength' => '1000']) }}
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <!-- hidden label bumps button down - could be more responsive -->
                                        <label style="visibility:hidden"></label>
                                        <button id="date-filter-button" class="form-control btn btn-primary filter">{{ trans('buttons.general.filter') }}</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <!-- datatable table -->
                        <div class="table-responsive">
                            <table id="requests-table" class="table table-condensed table-hover dt-responsive nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">{{ trans('labels.scheduling.course') }}</th>
                                        <th class="all">{{ trans('labels.scheduling.event_time') }}</th>
                                        <th></th>
                                        <th></th>
                                        <th>{{ trans('labels.calendar.location') }}</th>
                                        <th>{{ trans('labels.scheduling.req_date') }}</th>
                                        <th class="text-center">{{ trans('labels.scheduling.status') }}</th>
                                        <th class="all">{{ trans('labels.general.actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">

                            <!-- when no request is clicked (visible on load) -->
                            <div class="panel panel-default" id="no-schedule-view">
                                <div class="panel-body">
                                    <h5 class="text-center">{{ trans('labels.scheduling.requests_please_select') }}</h5>
                                </div>
                            </div>

                            <!-- dp view (hidden on load - display:none;)-->
                            <div id="dp-view" style="display:none;">
                                <!-- include partial-filter for dp -->
                                @include('courseInstance.scheduleRequest.partial-filters')

                                <!-- include dp - daypilot -->
                                <!-- need marginTop15 marginBottom15 for partial-filter to display correctly -->
                                <div class="row marginTop15 marginBottom15">
                                    <div class="col-md-12" >
                                        <div id="dp"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="commentsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

    </section>

@stop


@section('after-scripts')

    {{ Html::script("/js/daypilot/daypilot-all.min.js") }}
    {{ Html::script("/js/moment-with-locales.js") }}
    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}
    {{-- {{ Html::script("/js/daypilot_daily_global.js") }} --}}
    {{ Html::script("/js/tinymce/tinymce.min.js") }}

    {{--date picker--}}
    {{ Html::script("/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js") }}
    {{ Html::style("/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css") }}

    <script>

        var dt;
        let status;
        let start_date;
        let end_date;

        $(function() {

            //apply date picker to start and end date
            $('.input-group.date').datepicker({
                multidate: false,
                autoclose: true,
                format: 'mm-dd-yyyy',
                todayBtn: true,
                todayHighlight: true
            });


            // sets default view to display if requested by url
            status = '{{ $requestType }}';

            if (status == 'pending') {
                pending();
            } else if (status == 'approved') {
                approved();
            } else if (status == 'denied') {
                denied();
            } else if (status == 'all') {
                all();
            }

            // select a datatable item
            $('#requests-table tbody').on( 'click', 'tr', function () {
                actionSelected();
            });

        });


        // header buttons trigger their specific view functions
        $('#pending').click(function() {
            pending();
        });

        $('#approved').click(function() {
            approved();
        });

        $('#denied').click(function() {
            denied();
        });

        $('#all').click(function() {
            all();
        });

        $('#date-filter-button').click(function() {
            dateFilter();
        });

        // header buttons for mobile
        $('#pending-mobile').click(function() {
            pending();
        });

        $('#approved-mobile').click(function() {
            approved();
        });

        $('#denied-mobile').click(function() {
            denied();
        });

        $('#all-mobile').click(function() {
            all();
        });

        $("body").on("click", "a[name='delete']", function(e) {
            e.preventDefault();
            //Recuperate href value
            var href = $(this).attr('href');
            var message = $(this).data('confirm');
            var abbrv = $(this).data('abbrv');
            var start_date = $(this).data('date');

            //pop up
            swal({
                title: "{{ trans('labels.scheduling.requests_delete_wall') }} " + abbrv + " on " + start_date + "?",
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(function(isConfirmed) {
                if (isConfirmed) {
                    window.location.href = href;
                } else {
                }
            });
        });

        // submit buttons for comments
        $(document).on("submit", "form", function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            var form_action = form.attr("action");
            var data = $(this).serialize();
            form.find('span.preloader').show();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                dataType: 'json',
                type:'POST',
                url: form_action,
                data: data,
            }).done(function(data){
                form.find('span.preloader').hide();
                tinyMCE.activeEditor.setContent('');
                form.next().prepend('<hr><p class="comment-header">'+ data.name+' commented '+data.date_time+'</p><p>'+data.comment+'</p>');
            });
            return  false;
        });

        // load grid on datatable selected item
        function actionSelected() {

            setTimeout(function(){
                $('#no-schedule-view').hide();
                $('#dp-view').show();
                var idx = dt.cell('.selected', 0).index();               
                var data = dt.rows( idx.row ).data();
                var dateFormatted = moment(data[0]['date']).format('YYYY-MM-DD');
                var courseAbbrv = data[0]['course.abbrv'];
                var templateId = data[0]['template_id'];
                var startTime = data[0]['start_time'];
                var endTime = data[0]['end_time'];
                var setupMinutes = data[0]['setup_minutes'];
                var tearDownMinutes = data[0]['teardown_minutes'];
                _init(dateFormatted, startTime, endTime, setupMinutes, tearDownMinutes, templateId, courseAbbrv);

                dp.update();
            }, 1000);
        }

        //pending requests
        function pending() {
            //change title / panel heading and hide the filter for pending view
            document.title = "{{ trans('labels.scheduling.my_pending_requests') }}"
            $('#heading').text("{{ trans('labels.scheduling.my_pending_requests') }}");
            $('#date-filter').hide();

            // hide the grid
            $('#no-schedule-view').show();
            $('#dp-view').hide();

            //dont submit date for start/end as pending is show all
            start_date = null;
            end_date = null;
            status = 'pending';
            if (!dt) {
                initMyRequestTable();
            } else {
                dt.clear();
                dt.destroy();
                dt = null;
                initMyRequestTable();
            }
        }

        //approved requests
        function approved() {
            document.title = "{{ trans('labels.scheduling.my_approved_requests') }}"
            $('#heading').text("{{ trans('labels.scheduling.my_approved_requests') }}");
            $('#date-filter').show();

            // hide the grid
            $('#no-schedule-view').show();
            $('#dp-view').hide();

            start_date = moment($('#start-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            end_date = moment($('#end-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');

            status = 'approved';
            if (!dt) {
                initMyRequestTable();
            } else {
                dt.clear();
                dt.destroy();
                dt = null;
                initMyRequestTable();
            }
        }

        // denied requests
        function denied() {
            document.title = "{{ trans('labels.scheduling.my_denied_requests') }}"
            $('#heading').text("{{ trans('labels.scheduling.my_denied_requests') }}");
            $('#date-filter').show();

            // hide the grid
            $('#no-schedule-view').show();
            $('#dp-view').hide();

            start_date = moment($('#start-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            end_date = moment($('#end-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');

            status = 'denied';
            if (!dt) {
                initMyRequestTable();
            } else {
                dt.clear();
                dt.destroy();
                dt = null;
                initMyRequestTable();
            }
        }

        //all requests
        function all() {
            document.title = "{{ trans('labels.scheduling.all_requests') }}"
            $('#heading').text("{{ trans('labels.scheduling.all_requests') }}");
            $('#date-filter').show();

            // hide the grid
            $('#no-schedule-view').show();
            $('#dp-view').hide();

            start_date = moment($('#start-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            end_date = moment($('#end-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');

            status = null;
            if (!dt) {
                initMyRequestTable();
            } else {
                dt.clear();
                dt.destroy();
                dt = null;
                initMyRequestTable();
            }
        }

        // date/time filter search
        function dateFilter() {

            start_date = moment($('#start-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            end_date = moment($('#end-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');

            dt.clear();
            dt.destroy();
            dt = null;
            initMyRequestTable();
        }


        //init the request datatable
        function initMyRequestTable() {

            dt = $('#requests-table').DataTable({
                ajax: {
                    url: '{!! url("userRequests.data") !!}',
                    data: function (d) {
                    d.start_date = start_date;
                    d.end_date = end_date;
                    d.status = status;
                    }
                },
                select: 'single',
                bFilter: true,
                buttons: false,
                order: [[ 5, "desc" ]],

                //customize no data message
                language: {emptyTable: "{{trans('labels.scheduling.no_data')}}"},

                //Add an "All" to the "Show entries" pulldown menu
                pageLength: 10,
                "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],

                //Set the format for table and surrounding functionality
                dom: '<"top"Bf>rt<"bottom"lp><"clear">',

                responsive: true,
                columns: [
                    { data: 'course.abbrv',    name: 'course.abbrv', responsivePriority: 1 },
                    { data: 'event_time',    name: 'event_time',   responsivePriority: 2},
                    { data: 'start_time', name: 'start_time', visible: false, searchable: false },
                    { data: 'end_time', name: 'end_time', visible: false, searchable: false },
                    { data: 'location.abbrv',  name: 'location.abbrv', responsivePriority: 4 },
                    { data: {
                        _:    "requested_date",
                        sort: "requested_timestamp",
                        name: 'requested_date'
                        }, responsivePriority: 6
                    },
                    { data: 'status',  name: 'status', orderable: false, searchable: false , responsivePriority: 7},

                    // Put in the action buttons on the rightmost column
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, responsivePriority: 3},
                ],
                columnDefs: [
                    { targets: [6], className: 'dt-body-center'},
                    { targets: 1, orderData: [2,3]} //sort event date time by hidden columns start_time, end_time
                ],
            });
        }

        $("#commentsModal").on('show.bs.modal', function (e) {
            var triggerLink = $(e.relatedTarget);
            var id = triggerLink.data("id");

            $(this).find(".modal-body").load("/myScheduleRequest/request/"+id);
        });

        $('#commentsModal').on('hidden.bs.modal', function () {
            location.reload();
        })

        //apply the editor to the comments field, used by modal
        function applyMCE() {
            tinyMCE.init({
                mode : "textareas",
                forced_root_block : false,
                editor_selector : "mce",
                browser_spellcheck: true,
                menubar: false,
                height: "120",
                branding: false,
                plugins: [
                    'advlist autolink lists link charmap print preview anchor textcolor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime table paste code help wordcount'
                ],
                toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor  | bullist numlist | removeformat | link code',
            });
        }

        var locationsAndResources = {!! $locationsAndResources !!}; 
        var dp = new DayPilot.Scheduler("dp");

        // Day Pilot initializer
        // @params - Data Table Row Selected Date
        function _init(date, startTime, endTime, setupMinutes, tearDownMinutes, templateID, courseAbbrv) {

            dp.startDate = date;
            dp.treeEnabled = true;
            dp.treePreventParentUsage = true;     
            dp.businessBeginsHour = {{ Session::get('business_start_hour') }};       
            dp.businessEndsHour = {{ Session::get('business_end_hour') }};
            dp.crosshairType = "Full";
            dp.autoScroll = "Disabled";
            dp.scrollX = 550; // Set view focus for times. By Default focus is from far left to right
            dp.timeHeaders =  [{groupBy: "Day", format: "dddd MMMM d, yyyy" }, {groupBy: "Hour"}];
            dp.scale = "CellDuration";
            dp.cellDuration = 15;
            dp.cellWidthSpec = 'Auto';
            dp.cellWidthMin = 20;
            dp.eventStackingLineHeight = 50;
            dp.eventArrangement = "SideBySide";
            dp.allowEventOverlap = true;
            dp.resources = locationsAndResources;
            dp.eventMoveHandling = false; // disable event moving
            dp.eventResizeHandling = false; // disable event resize
            dp.floatingEvents = false;
            $.get("/courseInstance/getEventsAndForDate/".concat(date), function (res) {
                // console.log('This should run first! Loading events for ' + date);
                dp.update({events: addEventSetupAndTeardownTimes(res)});
            }).then(function() {

                localStorage['events_filter'] = 'Scheduled';
                applyRowFilters()

                var startTimeLine = date + "T" + moment(startTime).format("HH:mm:ss");
                var endTimeLine = date + "T" + moment(endTime).format("HH:mm:ss");

                //convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
                var setupSeconds = setupMinutes*60;
                var setupLine = date + "T" + moment(startTime).subtract(setupSeconds, 'seconds').format("HH:mm:ss");
                var teardownSeconds = tearDownMinutes*60;
                var teardownLine = date + "T" + moment(endTime).add(teardownSeconds, 'seconds').format("HH:mm:ss");

                var templateColor = 'rgb(0, 0, 0)';

                dp.update({separators: [{color: 'blue', location: setupLine}, {color: 'blue', location: teardownLine}, {color: 'red', location: startTimeLine}, {color: 'red', location: endTimeLine}]});

                //If a template was selected, display those resources on grid
                if(templateID !== null) {
                    $.ajax({
                        url: '/findDefaultTemplateValues/' + templateID + '/0/' + date,
                        type: "GET",
                        data: {"_token": "{{ csrf_token() }}"},
                        dataType: "json",
                        success: function (res) {
                            //console.log(res);
                            // console.log('This should run second! Loading events for ' + date + ' template:' + templateID);

                            // Get template color
                            $.each(res.templateOptions, function (key, value) {

                                if(key == 'color')
                                {
                                    templateColor = value;
                                }

                            });

                            /*
                             * Checking if Template Resources will have any conflicts with the real events on that date
                            */

                            // Get Resources associated with template
                            var resources = res.templateOptions.resources;
                            // console.log(resources);
                            // remove flagged events
                            dp.events.list = dp.events.list.filter(function (obj) {
                                return obj.flag != true;
                            });

                            //  arr to store new events
                            var events = dp.events.list;
                            // console.log(events);

                            // Get Grid Date
                            var gridDate = date;

                            var counter = 0;

                            for (var i = 0; i < resources.length; i++) {
                                // areas arr to store setup & teardown times
                                resources[i].areas = [];

                                // add Setup time and color
                                if (resources[i].setup_time != 0) {
                                    let color = 'rgb(255, 255, 255)';
                                    resources[i].areas.push({
                                        left: 0,
                                        w: ((resources[i].setup_time / dp.cellDuration) * dp.cellWidth),
                                        style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(color, ", ").concat(color, ", 2.5px, ").concat(invert(color), " 2.5px, ").concat(invert(color), " 5px);"),
                                    });
                                }

                                // add TearDown time and color
                                if (resources[i].teardown_time != 0) {
                                    let color = 'rgb(255, 255, 255)';
                                    resources[i].areas.push({
                                        right: 0,
                                        w: ((resources[i].teardown_time / dp.cellDuration) * dp.cellWidth),
                                        style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(color, ", ").concat(color, ", 2.5px, ").concat(invert(color), " 2.5px, ").concat(invert(color), " 5px);"),
                                    });
                                }

                                // Build Event Start Time
                                let startTime = new DayPilot.Date(gridDate + 'T' + resources[i].start_time).addMinutes(-Math.abs(resources[i].setup_time));

                                // Build Event End Time
                                let endTime = new DayPilot.Date(gridDate + 'T' + resources[i].end_time).addMinutes(resources[i].teardown_time);

                                // Build new event for Grid
                                let e = {
                                    flag: true, // Creating a flag to remove these from events arr when picking a new template
                                    start: startTime,
                                    end: endTime,
                                    id: resources[i].id,
                                    resource: resources[i].resource_id.toString(),
                                    text: courseAbbrv, // set event name to course selected from dropdown
                                    backColor: 'rgb(97, 235, 52)',
                                    barColor: templateColor,
                                    areas: resources[i].areas, // renders our color
                                    resource_identifier_type: resources[i].resource_identifier_type,
                                };
                                // console.log(e);


                                // Get Grid resource Event is attempting to add to
                                var matchingResource = events.filter(function (obj) {
                                    return obj.resource == e.resource;
                                });

                                // Need to check for event time overlaps
                                if (matchingResource.length > 0) {
                                    for (var j = 0; j < matchingResource.length; j++) {
                                        let main_overlap = DayPilot.Util.overlaps(matchingResource[j].start, matchingResource[j].end, e.start, e.end);
                                        // console.log(matchingResource[j].start, matchingResource[j].end, e.start, e.end);
                                        if (main_overlap) {
                                            // set evnt color
                                            e.backColor = 'rgb(219, 92, 92)';
                                            dp.message('Conflicts Below are Red');


                                            // conflict here check resource type id to see if we can move different category or subcategory
                                            if (e.resource_identifier_type === 2) {
                                                $.ajax({
                                                    url: "/courseInstance/getResourceByCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_category_id, "/").concat(matchingResource[j].location_id),
                                                    type: "GET",
                                                    data: {"_token": "{{ csrf_token() }}"},
                                                    dataType: "json",
                                                    success: function (res) {

                                                        var category_matches = res.resourceList;

                                                        for (let x = 0; x < category_matches.length; x++) {

                                                            let id = category_matches[x].id.toString();
                                                            // Find events for each matching category resource
                                                            let category_matches_events = dp.rows.find(id).events.all();

                                                            if (category_matches_events.length > 0) {

                                                                var counter = category_matches_events.length - 1;

                                                                for (let y = 0; y < category_matches_events.length; y++) {

                                                                    let category_overlap = DayPilot.Util.overlaps(category_matches_events[y].data.start, category_matches_events[y].data.end, e.start, e.end);
                                                                    // console.log({
                                                                    //     row: x,
                                                                    //     counter: counter,
                                                                    //     loop: y,

                                                                    //     // overlaps: o,
                                                                    //     // color: e.backColor,
                                                                    //     overlap: category_overlap,
                                                                    //     event: e.text,
                                                                    //     resource: id,
                                                                    //     event_id: e.id,
                                                                    //     // flag: category_matches_events[y].data.flag,
                                                                    //     resource_start: category_matches_events[y].data.start,
                                                                    //     resource_end: category_matches_events[y].data.end,
                                                                    //     event_start: e.start,
                                                                    //     event_end: e.end,
                                                                    // }

                                                                    if (category_overlap) {
                                                                        // Conflict in this category, looping over rest of events is no longer needed.
                                                                        break;
                                                                    } else {
                                                                        if (y == category_matches_events.length - 1) {
                                                                            e.resource = id;
                                                                            e.backColor = 'rgb(97, 235, 52)';
                                                                            dp.update();
                                                                        }
                                                                    }
                                                                }
                                                            } else { // Category Matching Resource has no Events, just move here
                                                                e.resource = id;
                                                                e.backColor = 'rgb(97, 235, 52)';
                                                                dp.update();
                                                                break;
                                                            }
                                                        }
                                                    },
                                                    error: function (jqXHR, textStatus, errorThrown) {
                                                        console.log(textStatus, errorThrown);
                                                    }
                                                });
                                            } else if (e.resource_identifier_type === 3) {
                                                $.ajax({
                                                    url: "/courseInstance/getResourceBySubCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_sub_category_id, "/").concat(matchingResource[j].location_id),
                                                    type: "GET",
                                                    data: {"_token": "{{ csrf_token() }}"},
                                                    dataType: "json",
                                                    success: function (res) {
                                                        var subcategory_matches = res.resourceList;
                                                        for (let x = 0; x < subcategory_matches.length; x++) {

                                                            let id = subcategory_matches[x].id.toString();
                                                            // Find events for each matching category resource
                                                            let subcategory_matches_events = dp.rows.find(id).events.all();
                                                            if (subcategory_matches_events.length > 0) {
                                                                for (let y = 0; y < subcategory_matches_events.length; y++) {
                                                                    let subcategory_overlap = DayPilot.Util.overlaps(subcategory_matches_events[y].data.start, subcategory_matches_events[y].data.end, e.start, e.end);

                                                                    // console.log({
                                                                    //     row: x,
                                                                    //     loop: y,
                                                                    //     event: e.text,
                                                                    //     color: e.backColor,
                                                                    //     overlap: subcategory_overlap,
                                                                    //     resource: id,
                                                                    //     event_id: e.id,
                                                                    //     flag: subcategory_matches_events[y].data.flag,
                                                                    //     resource_start: subcategory_matches_events[y].data.start,
                                                                    //     resource_end: subcategory_matches_events[y].data.end,
                                                                    //     event_start: e.start,
                                                                    //     event_end: e.end
                                                                    // });
                                                                    if (subcategory_overlap) {
                                                                        // Conflict in this sub category, looping over rest of events is no longer needed.
                                                                        break;
                                                                    } else {
                                                                        if (y == subcategory_matches_events.length - 1) {
                                                                            e.resource = id;
                                                                            e.backColor = 'rgb(97, 235, 52)';
                                                                            dp.update();
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                e.resource = id;
                                                                e.backColor = 'rgb(97, 235, 52)';
                                                                dp.update();
                                                                break;
                                                            }
                                                        }
                                                    },
                                                    error: function (jqXHR, textStatus, errorThrown) {
                                                        console.log(textStatus, errorThrown);
                                                    }
                                                });
                                            }
                                        } else {
                                            // only show if we know no more events for that resource will be checked
                                            if ((matchingResource.length - 1) - j != matchingResource.length - 1) {
                                                // console.log('could poss move here in main');
                                                // break;
                                            }
                                        }
                                    }
                                } else {
                                    // The main resource is available
                                    //  console.log(e);
                                }
                                events.push(e);
                            }
                            dp.update({events: events})
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    }).then(function () {
                        //set filter to scheduled (filter is reset first up above with resetRowFilters)
                        localStorage['events_filter'] = 'Scheduled';
                        //note it is important to apply the filters here AFTER the then statement, otherwise they
                        // get applied before all the events load and rows can be hidden incorrectly
                        applyRowFilters()
                    });
                }

                dp.init();
            });


        }

        function addEventSetupAndTeardownTimes(arr) {
            var jsonObj = [];

            for (let i = 0; i < arr.length; i++) {
                arr[i].areas = []; 
                
                var tmp = arr[i].barColor;
                
                let setup = new DayPilot.Date(arr[i].start).addMinutes(-Math.abs(arr[i].setup));  
                arr[i].start = setup;
                arr[i].areas.push({
                    left: 0,
                    w: ((arr[i].setup / dp.cellDuration) * dp.cellWidth),
                    style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
                });                        


                let teardown = new DayPilot.Date(arr[i].end).addMinutes(arr[i].teardown);
                arr[i].areas.push({
                    right: 0,
                    w: ((arr[i].teardown / dp.cellDuration) * dp.cellWidth),
                    style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),                    
                });
                arr[i].end = teardown;

                jsonObj.push(arr[i]);
            }

            return jsonObj;
        }

            function invert(rgb) {       
                if (!rgb) {
                    return 'rgb(239, 153, 87)';
                }else {
                    if (rgb.slice(0,4) === 'rgba') {
                        rgb = rgb.slice(5, rgb.length-1);
                    }else {
                        rgb = rgb.slice(4, rgb.length-1);
                    }
                    
                    var arr = rgb.split(',');

                    var r = (255 - arr[0])
                    var g = (255 - arr[1])
                    var b = (255 - arr[2])
                    return "rgb(".concat(r, ", ").concat(g, ", ").concat(b, ")");
                }
            }

            /*
            * Filtering
            */

            // Apply Filters
            function applyRowFilters() {

                //console.log ('applying filters');

                //grab new value here if there is one (the key up function wasn't always working)
                if($("input[name=searchRows]").val() !== undefined)
                {
                    localStorage['search_text'] = $("input[name=searchRows]").val();
                }

                // set value of search input if value in local storage already (this is to make search "sticky" when switching days)
                if (localStorage['search_text'] !== undefined) {
                    $("input[name=searchRows]").val(localStorage['search_text']);
                }

                if (localStorage['events_filter'] != null) {
                    $('input:radio[name=events_filter][value="'+ localStorage['events_filter']+'"]').prop('checked', true);
                }

                if (localStorage['phys_filter'] != null) {
                    $('input:radio[name=phys_filter][value="'+ localStorage['phys_filter']+'"]').prop('checked', true);
                }

                var resourceType = null;
                if (localStorage['phys_filter'] !== undefined)
                {
                    resourceType = localStorage['phys_filter'];
                }

                var availabilityType = null;
                if (localStorage['events_filter'] !== undefined)
                {
                    availabilityType = localStorage['events_filter'];
                }

                //set this to '' here because otherwise the ToUpper function errors on null/undefined
                var searchString = '';
                if (localStorage['search_text'] !== undefined)
                {
                    searchString = localStorage['search_text'];
                }

                // console.log ("resourceType: " + resourceType);
                // console.log ("availabilityType: " + availabilityType);
                // console.log ("searchString: " + searchString);

                if(availabilityType === 'Scheduled')
                {
                    if(resourceType === 'Room')
                    {
                        var query = {name: 'search_rooms_scheduled', value: searchString};
                    }
                    if(resourceType === 'Equipment')
                    {
                        var query = {name: 'search_equipment_scheduled', value: searchString};
                    }
                    if(resourceType === 'Personnel')
                    {
                        var query = {name: 'search_personnel_scheduled', value: searchString};
                    }
                    if(resourceType === null)
                    {
                        var query = {name: 'search_scheduled', value: searchString};
                    }
                }

                if(availabilityType === 'Available')
                {
                    if(resourceType === 'Room')
                    {
                        var query = {name: 'search_rooms_available', value: searchString};
                    }
                    if(resourceType === 'Equipment')
                    {
                        var query = {name: 'search_equipment_available', value: searchString};
                    }
                    if(resourceType === 'Personnel')
                    {
                        var query = {name: 'search_personnel_available', value: searchString};
                    }
                    if(resourceType === null)
                    {
                        var query = {name: 'search_available', value: searchString};
                    }
                }

                if(availabilityType === null)
                {
                    if(resourceType === 'Room')
                    {
                        var query = {name: 'search_rooms_all', value: searchString};
                    }
                    if(resourceType === 'Equipment')
                    {
                        var query = {name: 'search_equipment_all', value: searchString};
                    }
                    if(resourceType === 'Personnel')
                    {
                        var query = {name: 'search_personnel_all', value: searchString};
                    }
                    if(resourceType === null)
                    {
                        var query = {name: 'search_all', value: searchString};
                    }
                }

                dp.rows.filter(query);

            }

            // Reset Filters
            function resetRowFilters() {

                //mitcks 2020-07-20: clear values stored in local storage so they do not "stick" when filters reset
                localStorage.removeItem('search_text');
                localStorage.removeItem('events_filter');
                localStorage.removeItem('phys_filter');

                $("input[type=radio]").each(function() {
                    $(this).prop('checked', false);
                });
                dp.events.filter(null);
                dp.rows.filter(null);

                $("input[name=searchRows]").each(function() {
                    $(this).val('');
                });
                return;
            }

            // Reset Event
            $("#resetFilters").on('click',function(e) {
                resetRowFilters();
                e.preventDefault();
            });

            // search Event
            $("#filter").on('keyup', function() {
                //mitcks 2020-07-20 place in local storage to maintain search text when date changes
                localStorage['search_text'] = $(this).val();
            });

            // Radio Buttons
            $('body').on('click', 'input[type="radio"]', function (evnt) {
                //mitcks 2020-07-20 place in local storage to maintain filter when date changes
                localStorage[$(this).attr('name')] = $(this).val();
            });

            // Event Handler
            dp.onEventFilter = function(args) {
                if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                    args.visible = false;
                }
            };

            // Event Move Handler
            dp.onEventMoved = function (args) {
                if (args.e.data.template_event) {
                    args.e.data.backColor = 'rgb(97, 235, 52)';
                    dp.update();   
                }
            };

            // Row Handler    
            dp.onRowFilter = function(args) {

                // Search String & Room & Scheduled
                if (args.filter.name == 'search_rooms_scheduled') {

                    if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Room') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Room & All (scheduled and available)
                if (args.filter.name == 'search_rooms_all') {

                    if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Room') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Equipment & All (scheduled and available)
                if (args.filter.name == 'search_equipment_all') {

                    if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Equipment') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search Personnel & Equipment & All (scheduled and available)
                if (args.filter.name == 'search_personnel_all') {

                    if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Personnel') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                //search_all (search string and all types and availability)
                if (args.filter.name == 'search_all') {

                    if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1)  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Scheduled (no physical filter)
                if (args.filter.name == 'search_scheduled') {

                    if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Room & Available
                if (args.filter.name == 'search_rooms_available') {

                    if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Room') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Available (no physical filter)
                if (args.filter.name == 'search_available') {

                    if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Equipment & Scheduled
                if (args.filter.name == 'search_equipment_scheduled') {

                    if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Equipment') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Equipment & Available
                if (args.filter.name == 'search_equipment_available') {

                    if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Equipment') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Personnel & Scheduled
                if (args.filter.name == 'search_personnel_scheduled') {

                    if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Personnel') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

                // Search String & Personnel & Available
                if (args.filter.name == 'search_personnel_available') {

                    if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Personnel') === -1 ) )  {
                        args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
                    }
                }

            };  

    </script>


@endsection
