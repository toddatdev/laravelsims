@extends('frontend.layouts.app')

@section('content')

    <!-- the calendar id allows the rest of this document to be loaded via ajax from calendar.blade.php -->
    <div id="calendar">

        <!-- datatable panel to display the day's events -->
        <div class="card-body">

            {{--These two hidden fields are used in calendar.js to pass translated strings for button text --}}
            {!! Form::hidden('display_notes_text', trans('buttons.calendar.display_notes')) !!}
            {!! Form::hidden('hide_notes_text', trans('buttons.calendar.hide_notes')) !!}

            <div class="table-responsive">
                <table id="day-table" class="table table-condensed dt-responsive" style="width:100%">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.general.time') }}</th>
                        <th>{{ trans('labels.calendar.start_time') }}</th>
                        <th>{{ trans('labels.calendar.end_time') }}</th>
                        <th></th>
                        <th></th> {{-- Color --}}
                        <th></th> {{-- Status --}}
                        <th></th> {{-- Specialist --}}
                        <th></th> {{-- Special requirements --}}
                        <th></th> {{-- Not Resolved --}}
                        <th>{{ trans('labels.calendar.course') }}</th>
                        <th>{{ trans('labels.calendar.location') }}</th>
                        <th>{{ trans('labels.calendar.initial_meeting_room') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                        {{-- For notes section (no header text required - it's in formatted text displayed),
                        class=none forces it into responsive child row --}}
                        <th class="none"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="card-body bg-gray-light">
            <!-- include partial-filter for dp -->
            @include('calendar.partial-filters')
        </div>
        <div>
            <!-- include dp - daypilot -->
            <div id="dp"></div>
        </div>

        <script>
            // allow tooltip for view
            $('[data-tooltip="tooltip"]').tooltip();

            var locationsAndResources = {!! $locationsAndResources !!};
            var dp = new DayPilot.Scheduler("dp");
            var start_date = '{{ $inputDay }}';
            var input_location = '{{ $location }}';

            function _init() {
                dp.startDate = start_date;
                dp.startDate = '{{ $inputDay }}';
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

                var date = start_date.slice(0, 10);

                $.get("/courseInstance/getEventsAndForDate/".concat(date), function (res) {
                    console.log('Loading events for ' + date);
                    dp.update({events: addEventSetupAndTeardownTimes(res)});
                    // console.log({events: addEventSetupAndTeardownTimes(res)});
                }).then(function() {
                    applyRowFilters()
                });
                //2020_07_22 mitcks: Tanner helped with the above then statement which prevents the filters
                // from being applied before the events are loaded (this was causing events to not display in grid)

                dp.init();

            }

            // Matt and/or Joel's initializer
            $(function () {

                var eventToDelete;
                var content;
                //mitcks: set the name of the event being deleted to use in sweet alert
                $('#day-table').on('click', 'tbody tr', function () {
                    var row = dayTable.rows($(this)).data();
                    eventToDelete = row[0]['course_name'];
                    //mitcks: this is here to pass html to the sweet alert so event name is blue
                    content = document.createElement('div');
                    content.innerHTML = "{{ trans('alerts.general.confirm_delete_content') }}"+'</br><span style="font-weight:bolder; color:blue;">'+ eventToDelete +'</span>';
                } );

                $("body").on("click", "a[name='delete_event']", function(e) {
                    e.preventDefault();
                    var href = $(this).attr("href");
                    swal({
                        title: "{{ trans('alerts.general.confirm_delete') }}",
                        content: content, //this is set above so it can include html
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then(function(isConfirmed) {
                            if (isConfirmed) {
                                window.location.href = href;
                            }
                        });
                });

                // init datatable for day
                initDay('{!! url('day.data') !!}', '{{trans('labels.calendar.no_data_day')}}');
                dp.startDate = start_date;

                _init();

            });

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
                    return "rgb(".concat(r, ", ").concat(g, ", ").concat(b, ")");;
                }
            }

            /*
            * Filtering   
            */

            // Apply Filters
            function applyRowFilters() {

                //console.log ('applying filters');

                //set value of search input if value in local storage already (this is to make search "sticky" when switching days)
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
                var query = {name: $(this).val(), type: $(this).attr('name'),  value: $(this).is(":checked") };

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
    </div>
@stop
