@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.course.manage_courses') . ' | ' . trans('labels.scheduling.schedule_request'))

{{-- UI css--}}
{{ Html::style("css/jquery-ui/jquery-ui.css") }}
{{ Html::style("css/schedule-request.css") }}

<!-- DayPilot library -->
<script src="{{ asset('js/daypilot/daypilot-all.min.js')}} "></script>

{{-- jQuery --}}
{{ Html::script("/js/jquery.js") }}

{{-- Moment JS --}}
{{ Html::script("/js/moment-with-locales.js") }}

{{-- UI js --}}
{{ Html::script("/js/jquery-ui.js") }}

{{-- <script type="text/javascript" src="{{ asset('/js/jquery-ui-timepicker-addon.js') }}"></script> --}}

{{-- Should Fix Slider UI for mobile devices --}}
{{ Html::script("/js/jquery.ui.touch-punch.min.js") }}

@section('content')
    <section class="content">
        {{ Form::open(['url' => route('scheduleRequest.store'), 'method' => 'post', 'class' => 'form-horizontal', 'name' => 'scheduleRequestForm']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.scheduling.schedule_request') }}</h3>
                    </div>

                    <div class="card-body">
                        @include('courseInstance.scheduleRequest.partial-create')
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                             {{ link_to_route('my_pending_requests', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Filters --}}
                        @include('courseInstance.scheduleRequest.partial-filters')

                        {{-- Day Pilot Scheduler --}}
{{--                        <div class="row marginTop15 marginBottom15">--}}
{{--                            <div class="col-md-12" >--}}
                                <div id="dp"></div>
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>

<script>
    // "use strict"; // wont be able to get Session Business hours if using `strict` Due to Javascript's Leading 0 rule
    // Loctions, for left side of grid
    var locationsAndResources = {!! $locationsAndResources !!}; 
    var dupsURL;
    var dupsDate;
    var template_id;
    var bStart;
    var bEnd;
    var dateChange = false;
    @if (isset($scheduleRequest))
        dupsURL = '{!! $currentUrl !!}';
        dupsDate = '{!! \Carbon\Carbon::parse($scheduleRequest->start_time)->format("Y-m-d") !!}';
        template_id = '{!! $scheduleRequest->template_id !!}';
        bStart = '{!! \Carbon\Carbon::parse($scheduleRequest->start_time)->format("H:i:s") !!}'
        bEnd = '{!! \Carbon\Carbon::parse($scheduleRequest->end_time)->format("H:i:s") !!}'
    @else 
        bStart = {{ Session::get('business_start_hour') }};
        bEnd = {{ Session::get('business_end_hour') }};
    @endif

    // declare Day Pilot
    var dp = new DayPilot.Scheduler("dp");

    function _init() {

        // dp config
        // dp.startDate = new DayPilot.Date('2019-07-26');

        if (dupsURL === 'duplicateEvent' || dupsURL === 'anotherEvent') {
            dp.startDate = new DayPilot.Date(dupsDate)

        }else {
            bStart = bStart < 10 ? '0' + bStart.toString() + ':00:00' : bStart.toString() + ':00:00';
            bEnd = bEnd < 10 ? '0' + bEnd.toString() + ':00:00' : bEnd.toString() + ':00:00';
        }
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

        // Setting line default to business day

        // get event for current date
        // use date for ajax 
        let date = dp.startDate.value // this is set by Day Pilot by default, we don't need to include the attrb but we can still use it.

        // format
        date = date.slice(0, 10); // yyyy-mm-dd

        // Setting vert lines to default Bus. Hours

        dp.separators = [{color:"red", location: date + "T" + bStart}, {color: "red", location: date + "T" + bEnd}];

        $.get("/courseInstance/getEventsAndForDate/".concat(date), function (res) {
            // console.log(res);
            
            // console.log('Loading events for ' + date);
            
            dp.update({events: addEventSetupAndTeardownTimes(res)});
        }).then(function() {
            applyRowFilters()
        });
        
        // generate
        dp.init();

    }

    // load
    _init()

    // store template 
    var $template = $( "#template_id" );
    
    // Apply Template on S_R Dups    
    
    if (dupsURL === 'duplicateEvent' || dupsURL === 'anotherEvent') {
        if (template_id) {
            dateChange = true;//this prevents user edited values from being overwritten by template
            $template.trigger( "change" );
        }
        
        // setTimeout("$template.trigger( 'change' )", 3000);
    }

    // store values of time slider
    var values = $("#slider-range").slider("option", "values");

    // update on input change
    $('#datepicker').on('change', function () {

        // Need to set the blade form hidden input here. If another onSelected event in partial this won't work
        $("input[name='eventDate']").val(moment($(this).val()).format("Y-MM-DD"));          
        // Gets the Date to look for Events, for a given day
        dp.startDate = moment($(this).val()).format("Y-MM-DD");

        $.get("/courseInstance/getEventsAndForDate/".concat(dp.startDate), function (res) {
            // console.log('Loading events for ' + dp.startDate);
            // Gets the last set values of slider when we update the grid for new date

            // format to remove the T and avoid "double" daypilot formatting below for line1 and line2
            date = dp.startDate.toString().slice(0, 10); // yyyy-mm-dd

            var hours1 = Math.floor(values[0] / 60);
            var minutes1 = values[0] - (hours1 * 60);
            hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
            minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();       
            var line1 = date + 'T' + hours1 + ':' +  minutes1 + ':00';

            var hours2 = Math.floor(values[1] / 60);
            var minutes2 = values[1] - (hours2 * 60);
            hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
            minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
            var line2 = date + 'T' + hours2 + ':' +  minutes2 + ':00';

            // Update Grid
            dp.update({events: addEventSetupAndTeardownTimes(res), startDate: dp.startDate, separators: [{color: 'red', location: line1}, {color: 'red', location: line2}]});

        }).then(function() {
            applyRowFilters()
        });

        // apply template to new date, if template has been selected
        if ($template.val()) {
            dateChange = true; //this prevents user edited values from being overwritten by template
            $template.trigger( "change" );
        }
    });

    function addEventSetupAndTeardownTimes(arr) {
        var jsonObj = [];
        for (let i = 0; i < arr.length; i++) {                        
            // adds starting and ending color
            arr[i].areas = [];                      
            if (arr[i].setup != 0) {
                
                let setup = new DayPilot.Date(arr[i].start).addMinutes(-Math.abs(arr[i].setup));  

                // new start time w/ added setup
                arr[i].start = setup;
                
                var tmp = arr[i].barColor;

                arr[i].areas.push({
                    left: 0,
                    // to get width divide setup time by the duration increments per hour then times by the cell width 
                    w: ((arr[i].setup / dp.cellDuration) * dp.cellWidth),
                    style: "height:4px; background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
                    // style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);-ms-repeating-linear-gradient(45deg, ").concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);-o-repeating-linear-gradient(45deg, ").concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);-moz-repeating-linear-gradient(45deg, ").concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);-webkit-repeating-linear-gradient(45deg, ").concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
                    // style: "height:4px;background: repeating-linear-gradient(45deg, #1066a8, #1066a8, 2.5px, #ef9957 2.5px, #ef9957 5px);-ms-repeating-linear-gradient(45deg, #1066a8, #1066a8, 2.5px, #ef9957 2.5px, #ef9957 5px);-o-repeating-linear-gradient(45deg, #1066a8, #1066a8, 2.5px, #ef9957 2.5px, #ef9957 5px);-moz-repeating-linear-gradient(45deg, #1066a8, #1066a8, 2.5px, #ef9957 2.5px, #ef9957 5px);-webkit-repeating-linear-gradient(45deg, #1066a8, #1066a8, 2.5px, #ef9957 2.5px, #ef9957 5px);"
                });                        
            }

            // Doing some for teardown ...
            if (arr[i].teardown != 0) {
                let teardown = new DayPilot.Date(arr[i].end).addMinutes(arr[i].teardown);
                
                arr[i].areas.push({
                    right: 0,
                    w: ((arr[i].teardown / dp.cellDuration) * dp.cellWidth),
                    style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
                    // style: `height:4px;background: repeating-linear-gradient(45deg, ${tmp}, ${tmp}, 2.5px, ${invert(tmp)} 2.5px, ${invert(tmp)} 5px);-ms-repeating-linear-gradient(45deg, ${tmp}, ${tmp}, 2.5px, ${invert(tmp)} 2.5px, ${invert(tmp)} 5px);-o-repeating-linear-gradient(45deg, ${tmp}, ${tmp}, 2.5px, ${invert(tmp)} 2.5px, ${invert(tmp)} 5px);-moz-repeating-linear-gradient(45deg, ${tmp}, ${tmp}, 2.5px, ${invert(tmp)} 2.5px, ${invert(tmp)} 5px);-webkit-repeating-linear-gradient(45deg, ${tmp}, ${tmp}, 2.5px, ${invert(tmp)} 2.5px, ${invert(tmp)} 5px);`,
                });
                arr[i].end = teardown;
            }
            jsonObj.push(arr[i]);
        }
        // return area;
        return jsonObj;
    }

    /**
     * HELPER FUNCTIONS
    */
   
    // Takes RGB String gets inverse and returns string back
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

    /**
     * Handle Vertical Lines in time slider move
    */
    $("#slider-range").on("slide", function(event, ui) {
        // get date of current grid to know where to place line
        var gridDate = moment($('#datepicker').val()).format("Y-MM-DD");        

        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);
        hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
        minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();       
        var line1 = gridDate + 'T' + hours1 + ':' +  minutes1 + ':00';

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);
        hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
        minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
        var line2 = gridDate + 'T' + hours2 + ':' +  minutes2 + ':00';

        // Update Grid
        dp.update({separators: [{color: 'red', location: line1}, {color: 'red', location: line2}]});    
    }); 


    /*
     * Filtering   
    */

    // Apply Filters
    function applyRowFilters() {

        // console.log ('applying filters');

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

    // Reset Fiters
    function resetRowFilters() {

        //mitcks 2020-07-20: clear values stored in local storage so they do not "stick" when filters reset
        localStorage.removeItem('search_text');
        localStorage.removeItem('events_filter');
        localStorage.removeItem('phys_filter');

        //mitcks 2021-04-07 DO NOT reset ALL type=radio here because it also resets the sims_spec_needed radio buttons
        var $eventRadios = $('input:radio[name=events_filter]');
        $($eventRadios).each(function() {
            $(this).prop('checked', false);
        });

        var $physicalRadios = $('input:radio[name=phys_filter]');
        $($physicalRadios).each(function() {
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
    $("#resetFilters").click(function() {
        resetRowFilters();
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


