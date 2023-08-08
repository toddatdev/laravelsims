<div class="form-group row">

    {{-- Course Selector --}}
    {{ Form::label('course', trans('labels.scheduling.course'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-2">
        @if (isset($scheduleRequest))
            {{ Form::select('course_id', $courses->pluck('abbrv', 'id'), $scheduleRequest->course_id, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select'), 'id' => 'course_id', 'disabled' => true]) }}
            {{--hidden input for course_id since select above is disabled (they cannot change when duplicating)--}}
            {{ Form::hidden('course_id', $scheduleRequest->course_id,['id' => 'course_id']) }}
        @else
            {{ Form::select('course_id', $courses->pluck('abbrv', 'id'), null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select'), 'id' => 'course_id']) }}        
        @endif
    </div>

    {{-- Template Option--}}
    {{ Form::label('template', trans('labels.scheduling.template'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-2">            
        @if (isset($scheduleRequest))        
            {{ Form::select('template_id', $templates->pluck('name', 'id'), $scheduleRequest->template_id, ['class' => 'form-control template-select', 'id' => 'template_id', 'placeholder' => trans('labels.general.select'), 'disabled' => false]) }}
        @else      
            {{ Form::select('template_id', $templates->pluck('name', 'id'), null, ['class' => 'form-control template-select', 'id' => 'template_id', 'placeholder' => trans('labels.general.select'), 'disabled' => true]) }}
        @endif
    </div>

    {{-- Location Selector --}}
    {{ Form::label('location_id', trans('labels.calendar.location'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-2">
        @if (isset($scheduleRequest))
            {{ Form::select('location_id', $locations, $scheduleRequest->location_id, ['class' => 'form-control', 'id' => 'location', 'placeholder' => trans('labels.general.select')]) }}
        @else
            {{ Form::select('location_id', $locations, null, ['class' => 'form-control', 'id' => 'location', 'placeholder' => trans('labels.general.select')]) }}
        @endif
    </div>
</div>

<div class="form-group row">

    {{-- Date Selector --}}
    {{ Form::label('eventDate', trans('labels.scheduling.event_date'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-2">
        @if (isset($scheduleRequest))
            {{ Form::hidden('eventDate', \Carbon\Carbon::parse($scheduleRequest->start_time)->format('Y-m-d'), ['class' => 'form-control', 'id' => 'date']) }} 
        @else
            {{ Form::hidden('eventDate', \Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'id' => 'date']) }}      
        @endif

        {{ Form::text('datepicker', \Carbon\Carbon::now()->format('m-d-Y'), ['class' => 'form-control mb-2 mr-sm-5', 'style'=>'width:100px;', 'id' => 'datepicker' , 'maxLength' => '1000']) }}

    </div>

    {{-- Time Selector --}}
    {{ Form::label('range', trans('labels.scheduling.event_time'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    @if (isset($scheduleRequest))
        {{ Form::hidden('start_time', \Carbon\Carbon::parse($scheduleRequest->start_time)->format('H:i'), ['id' => 'start_time_id']) }}
        {{ Form::hidden('end_time', \Carbon\Carbon::parse($scheduleRequest->end_time)->format('H:i'), ['id' => 'end_time_id']) }}  
    @else
        {{ Form::hidden('start_time', null,['id' => 'start_time_id']) }}
        {{ Form::hidden('end_time', null,['id' => 'end_time_id']) }} 
    @endif
    <div class="col-lg-6 col-md-6 col-xs-12">              
        <div id="time-range">                
            <div class="sliders_step1">
                <div id="slider-range"></div>
            </div>
            <p>Start: <span class="slider-time"></span> -  End: <span class="slider-time2"></span></p>
        </div>
    </div>
</div>

<div class="form-group row">
    
    {{-- Num of Rooms --}}
    {{ Form::label('numRooms', trans('labels.scheduling.num_rooms'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-1">
        @if (isset($scheduleRequest))
            {{ Form::number('num_rooms', $scheduleRequest->num_rooms, ['id' => 'numRooms', 'class' => 'form-control', 'placeholder' => '0', "min" => "1"]) }}
        @else
            {{ Form::number('num_rooms', 'num_rooms', ['id' => 'numRooms', 'class' => 'form-control', 'placeholder' => '0', "min" => "1"]) }}
        @endif
    </div>

    {{-- Num of Participants --}}
    {{ Form::label('numParticipants', trans('labels.scheduling.num_participants'), ['class' => 'col-lg-3 control-label required text-lg-right']) }}
    <div class="col-lg-1">
        @if (isset($scheduleRequest))
            {{ Form::number('class_size', $scheduleRequest->class_size, ['class' => 'form-control', 'placeholder' => '0', "min" => "1"]) }}
        @else
            {{ Form::number('class_size', 'class_size', ['class' => 'form-control', 'id' => 'class_size', 'placeholder' => '0', "min" => "1"]) }}

        @endif
    </div>

    {{-- Sim Specialist --}}
    {{ Form::label('simsSpecNeeded', trans('labels.scheduling.sim_spec').'?', ['class' => 'col-lg-3 col-sm-4 control-label required text-lg-right']) }}
    <div class="col-lg-2">
        @if (isset($scheduleRequest))
            @if($scheduleRequest->sims_spec_needed == 1)
                {{ trans('labels.general.yes')}} {{ Form::radio('sims_spec_needed', '1' , true, ['id' => 'sims_spec_yes', 'checked' => true]) }}
                {{ trans('labels.general.no')}}  {{ Form::radio('sims_spec_needed', '0' , false, ['id' => 'sims_spec_no', ]) }}
            @else
                {{ trans('labels.general.yes')}} {{ Form::radio('sims_spec_needed', '1' , true, ['id' => 'sims_spec_yes']) }}
                {{ trans('labels.general.no')}}  {{ Form::radio('sims_spec_needed', '0' , false, ['id' => 'sims_spec_no', 'checked' => true]) }}
            @endif
        @else
            {{ trans('labels.general.yes')}} {{ Form::radio('sims_spec_needed', '1' , true, ['id' => 'sims_spec_yes']) }}
            {{ trans('labels.general.no')}}  {{ Form::radio('sims_spec_needed', '0' , false, ['id' => 'sims_spec_no']) }}
        @endif
    </div>

</div>

{{-- Notes/Request --}}
<div class="form-group row">
    {{ Form::label('notes', trans('labels.scheduling.note_request'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-10">
        @if (isset($scheduleRequest))
            {{ Form::text('notes', $scheduleRequest->notes, ['class' => 'form-control', 'placeholder' => trans('labels.scheduling.note_request') , 'maxLength' => '4000']) }}
        @else
            {{ Form::text('notes', '', ['class' => 'form-control', 'placeholder' => trans('labels.scheduling.note_request') , 'maxLength' => '4000']) }}
        @endif          
    </div>
</div>

{{-- Hidden Group Request ID --}}
@if (Request::is('*anotherEvent*'))    
    {{ Form::hidden('group_request_id', $scheduleRequest->group_request_id) }}
@endif


{{--multi-date picker (same one used in create event)--}}
{{ Html::script("/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js") }}
{{ Html::style("/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css") }}

<script>

    // applies jquery UI to event date
    $('#datepicker').datepicker({
        multidate: false,
        autoclose: true,
        todayHighlight: true,
        useCurrent: false,
        orientation: "bottom",
    });

/*
 * Handles Time Slider and Time Inputs
*/ 
var dups_startTime;
var dups_endTime;
var dupsURL_times;
var slider_vals = [];
@if (isset($scheduleRequest))
    dupsURL_times = '{!! $currentUrl !!}';
    if (dupsURL_times === 'duplicateEvent' || dupsURL_times === 'anotherEvent') {
        dups_startTime = '{!! \Carbon\Carbon::parse($scheduleRequest->start_time)->format("H:i") !!}';
        
        if (dups_startTime.slice(0,2) < 10 && dups_startTime.slice(0,2) >= 1) {
            $('.slider-time').html(dups_startTime.slice(1, dups_startTime.length) + ' AM');            
        }

        else if (dups_startTime.slice(0,2) > 12) {
            $('.slider-time').html(parseInt(dups_startTime.slice(0,2) - 12) + dups_startTime.slice(2,5) + ' PM');            
        }
        
        else if (dups_startTime.slice(0,2) >= 10 && dups_startTime.slice(0,2) < 12 ) {
            $('.slider-time').html(dups_startTime + ' AM')
        }

        if (dups_startTime.slice(0,2) < 10 && dups_startTime.slice(0,2) >= 1) {
            $('.slider-time').html(dups_startTime.slice(1, dups_startTime.length) + ' AM');            
        }
        else if (dups_startTime.slice(0,2) == 12) {
            $('.slider-time').html(dups_startTime + ' PM');
        }
        else if (dups_startTime.slice(0,2) === '00') {            
            $('.slider-time').html('12' + dups_startTime.slice(2,5) + ' AM');
        }


        dups_endTime = '{!! \Carbon\Carbon::parse($scheduleRequest->end_time)->format("H:i") !!}';

        if (dups_endTime.slice(0,2) < 10 && dups_endTime.slice(0,2) >= 1) {
            $('.slider-time2').html(dups_endTime.slice(1, dups_endTime.length) + ' AM');            
        }
        else if (dups_endTime.slice(0,2) > 12) {
            $('.slider-time2').html(parseInt(dups_endTime.slice(0,2) - 12) + dups_endTime.slice(2,5) + ' PM');
        }
        else if (dups_endTime.slice(0,2) == 12) {
            $('.slider-time2').html(dups_endTime + ' PM');
        }
        else if (dups_endTime.slice(0,2) === '00') {            
            $('.slider-time2').html('12' + dups_endTime.slice(2,5) + ' AM');
        }
                
        else if (dups_endTime.slice(0,2) >= 10 && dups_endTime.slice(0,2) < 12 ) {
            $('.slider-time2').html(dups_endTime + ' AM')
        }
        
        // to init sliders values
        let hour1 = parseInt(dups_startTime.slice(0,2));
        let min1 = parseInt(dups_startTime.slice(3,5));
        min1 = min1 / 60;

        let hour2 = parseInt(dups_endTime.slice(0,2));
        let min2 = parseInt(dups_endTime.slice(3,5));
        min2 = min2 / 60;
        
        slider_vals = [(hour1 + min1) * 60, (hour2 + min2) * 60];                
    }
@else
    // Set deafult business hour times before slider event
    var bs = {{ Session::get('business_start_hour') }};
    var deafultStart = '';
    if (bs < 12) {
        // text needs to be AM
        deafultStart = bs + ':00 AM'
    } 
    else if (bs == 0) {
        deafultStart = '12:00 AM'
    }
    else if (bs == 12) {
        deafultStart = '12:00 PM'
    }else {
        // time will be in 24, sub the 12 off
        deafultStart = (bs-12) + ':00 PM'
    }

    // set HTML
    $('.slider-time').html(deafultStart)

    var be = {{ Session::get('business_end_hour') }};
    var deafultEnd = '';
    if (be < 12) {
        // text needs to be AM
        deafultEnd = be + ':00 AM'
    }
    else if (be == 0) {   
        deafultEnd = '12:00 AM'
    } 
    else if (be == 12) {
        deafultEnd = be + ':00 PM'
    } else {
        // time will be in 24, sub the 12 off
        deafultEnd = (be-12) + ':00 PM'
    }

    // Set HTML
    $('.slider-time2').html(deafultEnd);

    // to init sliders values
    slider_vals = [{{ Session::get('business_start_hour') }} * 60, {{ Session::get('business_end_hour') }} * 60];
@endif
    

// create Slider
$("#slider-range").slider({    
    range: true,
    min: 0, // 12:00 AM
    max: 1440, // 11:59 PM
    step: 15, // min increments
    values: slider_vals,
    slide: function (e, ui) {
        // Stuff for UI
        
        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);

        // Update Input value
        $("input[name='start_time']").val(moment(hours1 + ':' + minutes1, 'LT').format('HH:mm'));

        if (hours1.length == 1) hours1 = '0' + hours1;
        if (minutes1.length == 1) minutes1 = '0' + minutes1;
        if (minutes1 == 0) minutes1 = '00';
        if (hours1 >= 12) {
            if (hours1 == 12) {
                hours1 = hours1;
                minutes1 = minutes1 + " PM";
            } else {
                hours1 = hours1 - 12;
                minutes1 = minutes1 + " PM";
            }
        } else {
            hours1 = hours1;
            minutes1 = minutes1 + " AM";
        }
        if (hours1 == 0) {
            hours1 = 12;
            minutes1 = minutes1;
        }

        // Sets Time for span below the slider
        $('.slider-time').html(hours1 + ':' + minutes1);

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);
        
        // Need to update this input field again, this submits the evnt slide change 
        $("input[name='end_time']").val(moment(hours2 + ':' + minutes2, 'LT').format('HH:mm'));

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';
        if (hours2 >= 12) {
            if (hours2 == 12) {
                hours2 = hours2;
                minutes2 = minutes2 + " PM";
            } else if (hours2 == 24) {
                hours2 = 11;
                minutes2 = "59 PM";
            } else {
                hours2 = hours2 - 12;
                minutes2 = minutes2 + " PM";
            }
        } else {
            hours2 = hours2;
            minutes2 = minutes2 + " AM";
        }

        // Sets Time for span below the slider
        $('.slider-time2').html(hours2 + ':' + minutes2);
    }
});

@if (isset($scheduleRequest))
    // dont need to set here done in blade input 
@else
    // Set times values on Submit if values on the slider were not moved
    var values = $("#slider-range").slider("option", "values");
    var hours1 = Math.floor(values[0] / 60);
    var minutes1 = values[0] - (hours1 * 60);
    $("input[name='start_time']").val(moment(hours1 + ':' + minutes1, 'LT').format('HH:mm'));

    var hours2 = Math.floor(values[1] / 60);
    var minutes2 = values[1] - (hours2 * 60);
    $("input[name='end_time']").val(moment(hours2 + ':' + minutes2, 'LT').format('HH:mm'));
@endif

/*
 * Handles Date Input & Date Picker
*/

var dupsURL;
var dupsDate;
@if (isset($scheduleRequest))
dupsURL = '{!! $currentUrl !!}';

if (dupsURL === 'duplicateEvent' || dupsURL === 'anotherEvent') {
    dupsDate = '{!! \Carbon\Carbon::parse($scheduleRequest->start_time)->format("Y-m-d") !!}';
}
@endif

/*
 * Handles Loading Course Templates
*/

// Load Templates
$('#course_id').on('change', function () {
    var course_id = $(this).val();

    if (course_id) {
        $.ajax({
            type: 'get',
            url: '/findDefaultValuesWithCourseID/'+course_id+'/0', //0 since we are not limiting to locations -jl 2019-10-18 14:25
            data : {"_token": "{{ csrf_token() }}"},
            dataType: "json",
            success: function (res) {       
                // console.log(res);

                // populate Template Dropdown
                if (res.templates.length >= 1) {
                    $('#template_id').empty();
                    $('#template_id').removeAttr("disabled");
                    $('#template_id').focus();
                    $('#template_id').append('<option value="">{{ trans('labels.general.select') }}</option>');
                    $.each(res.templates, function(key, value){
                        $('select[name="template_id"]').append('<option value="'+ value.id +'">' + value.name+ '</option>');
                    });
                }else {
                    // clear out
                    $('#template_id').empty();
                    // Keep default Select Text. (Looks Better)_
                    $('#template_id').append('<option value="">{{ trans('labels.general.select') }}</option>');
                    // Disable input
                    $('#template_id').attr('disabled', 'disabled');
                }
            },
            // Handle ajax error
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);                
            }
        });
    }

});


/*
 * Handles Applying Template to Schedule Request
*/

// To access slider inside event function, declare outside.
$slider = $('#slider-range');

// Update Based on Template selected
$('#template_id').on('change', function () {

    resetRowFilters();

    var template_id = $(this).val();
    let selectedDate = moment($('#datepicker').val(), 'MM-DD-YYYY').format('Y-MM-DD');
    // console.log ('selected date:' + selectedDate);

    //initializing these here so they can be used for separator lines after the loop through template values
    var setupTime = 0;
    var tearDownTime = 0;
    var templateColor = 'rgb(0, 0, 0)';
    //set default from slider values, then change below from template if applicable
    var startTime = $('#start_time_id').val();
    var endTime = $('#end_time_id').val();
    // console.log ('startTime:' + startTime);
    // console.log ('endTime:' + endTime);

    $.ajax({
        url: '/findDefaultTemplateValues/'+template_id+'/0/'+selectedDate,
        type: "GET",
        data : {"_token": "{{ csrf_token() }}"},
        dataType: "json",
        success: function (res) {
            // console.log(res);
            
            // Update inputs w/ template data
            $.each(res.templateOptions, function (key, value) {

                if (key == 'location_id') {
                   $('#location').val(value);
                }

                //(mitcks 7-7-2020 do not change value on date change)
                if (key == 'startTime' && dateChange != true) {

                    startTime = value;

                    let hour = value.slice(0,2);
                    let hour_num = Number(hour);                
                
                    let min = value.slice(3,5);
                    let min_num = Number(min) / 60;

                    // update html text
                    var time_str = (hour_num < 12) ? (hour + ':' + min + ' AM') : ((hour_num == 12) ? (hour + ':' + min + ' PM' ) : ((hour - 12) + ':' + min + ' PM'));
                    $('.slider-time').html(time_str);

                    let time = parseFloat(hour_num + min_num) * 60;

                    // reset the slider start time value
                    values[0] = time;
                    $slider.slider("values", 0, time);

                    // reset values for slider
                    values = $slider.slider('option', 'values');

                    // Set Form Field
                    $("input[name='start_time']").val(moment(hour + ':' + min, 'LT').format('HH:mm'));

                }

                //(mitcks 7-7-2020 do not change value on date change)
                if (key == 'endTime' && dateChange != true) {

                    endTime = value;

                    // Need to adjust slider
                    let hour = value.slice(0,2);
                    let hour_num = Number(hour);
                    let min = value.slice(3,5);
                    let min_num = Number(min) / 60;

                    let time = parseFloat(hour_num + min_num) * 60;                
                    values[1] = time;
                    $slider.slider("values", 1, time);

                    values = $slider.slider('option', 'values');

                    var time_str = (hour_num < 12) ? (hour + ':' + min + ' AM') : ((hour_num == 12) ? (hour + ':' + min + ' PM' ) : ((hour - 12) + ':' + min + ' PM'));

                    $('.slider-time2').html(time_str);

                    // Set Form Field
                    $("input[name='end_time']").val(moment(hour + ':' + min, 'LT').format('HH:mm'));

                }

                //(mitcks 7-7-2020 do not change value on date change)
                if (key == 'num_rooms' && dateChange != true) {
                    // If Count is 0 do nothing    
                    value > 0 ? $('#numRooms').val(value) : '';
                }

                //(mitcks 7-7-2020 do not change value on date change)
                if (key == 'numParticipants' && dateChange != true) {
                    $('#class_size').val(value);
                }

                //(mitcks 7-7-2020 do not change value on date change)
                if (key == 'simsSpecNeeded' && dateChange != true) {
                    // Get radio input
                    var $radios = $('input:radio[name=sims_spec_needed]');
                    if (value == 1) {
                        // select that radio option. Both are prop checked true cause we want either one selected
                        $radios.filter('[value=1]').prop('checked', true);
                    } else {
                        $radios.filter('[value=0]').prop('checked', true);
                    }
                }

                if(key == 'setupTime')
                {
                    $('#setup_time').val(value);
                    setupTime = value;
                }

                if(key == 'teardownTime')
                {
                    $('#teardown_time').val(value);
                    tearDownTime = value;
                }

                if(key == 'color')
                {
                    templateColor = value;
                }

            });

            // console.log('setupTime:'+setupTime);
            // console.log('tearDownTime:'+tearDownTime);
            // console.log('startTime:'+startTime);
            // console.log('endTime:'+endTime);

            //convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
            var setupSeconds = setupTime*60;
            var setupLine = moment(startTime + ':00', "HH:mm:ss").subtract(setupSeconds, 'seconds').format("HH:mm:ss");
            var teardownSeconds = tearDownTime*60;
            var teardownLine = moment(endTime+ ':00', "HH:mm:ss").add(teardownSeconds, 'seconds').format("HH:mm:ss");
            var gridDate = moment($('#datepicker').val(), 'MM-DD-YYYY').format('Y-MM-DD');

            // console.log('template gridDate:'+ gridDate);
            // console.log('template setupLine:'+ setupLine);
            // console.log('template teardownLine:'+ teardownLine);
            // console.log('template startTime:'+ startTime);
            // console.log('template endTime:'+ endTime);

            dp.update({separators: [{color:"blue", location: gridDate + "T" + setupLine}, {color:"blue", location: gridDate + "T" + teardownLine}, {color: 'red', location: gridDate + "T" + startTime + ':00'}, {color: 'red', location: gridDate + "T" + endTime + ':00'}]});

            // console.log('dateChange:'+dateChange);

            /*
             * Checking if Template Resources will have any conflicts with the real events on that date
            */           

            // Get Resources associated with template
            var resources = res.templateOptions.resources;
            // console.log(resources);
            // remove flagged events
            dp.events.list = dp.events.list.filter(function(obj) {
                return obj.flag != true;
            });

            //  arr to store new events
            var events = dp.events.list;
            // console.log(events);
            
            
            // Get Grid Date
            var gridDate = moment($('#datepicker').val(), 'MM-DD-YYYY').format('Y-MM-DD');
            
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
                    text: $("#course_id option:selected").text(), // set event name to course selected from dropdown
                    backColor: 'rgb(97, 235, 52)',
                    barColor: templateColor,
                    areas: resources[i].areas, // renders our color
                    resource_identifier_type: resources[i].resource_identifier_type,
                };
                // console.log(e);
                
                
                // Get Grid resource Event is attempting to add to
                var matchingResource = events.filter(function(obj) {
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
                                    data : {"_token": "{{ csrf_token() }}"},
                                    dataType: "json",
                                    success: function (res) {

                                        var category_matches = res.resourceList;
                                        
                                        for (let x = 0; x < category_matches.length; x++) {
                                            
                                            let id = category_matches[x].id.toString();
                                            // Find events for each matching category resource
                                            let category_matches_events = dp.rows.find(id).events.all();
                                            
                                            if (category_matches_events.length > 0) {
     
                                                var counter = category_matches_events.length-1;                                            

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
                                                    }else {  
                                                        if (y == category_matches_events.length-1) {
                                                            e.resource = id;
                                                            e.backColor = 'rgb(97, 235, 52)';
                                                            dp.update();
                                                        }
                                                    }                                          
                                                }                           
                                            }else { // Category Matching Resource has no Events, just move here
                                                e.resource = id;
                                                e.backColor = 'rgb(97, 235, 52)';
                                                dp.update();
                                                break;                                                 
                                            }                                            
                                        }                                     
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus, errorThrown);
                                    }
                                });                                
                            }
                            else if(e.resource_identifier_type === 3) {
                                $.ajax({
                                    url: "/courseInstance/getResourceBySubCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_sub_category_id, "/").concat(matchingResource[j].location_id),
                                    type: "GET",
                                    data : {"_token": "{{ csrf_token() }}"},
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
                                                    }else {                                                                     
                                                        if (y == subcategory_matches_events.length-1) {
                                                            e.resource = id;
                                                            e.backColor = 'rgb(97, 235, 52)';
                                                            dp.update();
                                                        }
                                                    }                                                   
                                                }                           
                                            }else {
                                                e.resource = id;
                                                e.backColor = 'rgb(97, 235, 52)';
                                                dp.update();
                                                break;                                                 
                                            }                                            
                                        }                                     
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus, errorThrown);
                                    }
                                });
                            }
                        }
                        else {
                            // only show if we know no more events for that resource will be checked
                            if ((matchingResource.length - 1) - j !=  matchingResource.length - 1) {
                                // console.log('could poss move here in main');
                                // break;
                            }                                                                                 
                        }                                        
                    }  
                }else {
                    // The main resource is available
                    //  console.log(e);                    
                }
                events.push(e);                                      
            }
            dp.update({events: events})
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown); 
        }
    }).then(function() {
        //set filter to scheduled (filter is reset first up above with resetRowFilters)
        localStorage['events_filter'] = 'Scheduled';
        //note it is important to apply the filters here AFTER the then statement, otherwise they
        // get applied before all the events load and rows can be hidden incorrectly
        applyRowFilters()
    });
})

</script>

{{-- Styling for Slider --}}
<style>
.ui-slider-horizontal {
    height: 8px;
    background: #D7D7D7;
    border: 1px solid #BABABA;
    box-shadow: 0 1px 0 #FFF, 0 1px 0 #CFCFCF inset;
    clear: both;
    margin: 8px 0;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    border-radius: 6px;
}
.ui-slider {
    position: relative;
    text-align: left;
}
.ui-slider-horizontal .ui-slider-range {
    top: -1px;
    height: 100%;
}
.ui-slider .ui-slider-range {
    position: absolute;
    z-index: 1;
    height: 8px;
    font-size: .7em;
    display: block;
    border: 1px solid #5BA8E1;
    box-shadow: 0 1px 0 #AAD6F6 inset;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    -khtml-border-radius: 6px;
    border-radius: 6px;
    background: #81B8F3;
    background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0, 50% 100%, color-stop(0%, #A0D4F5), color-stop(100%, #81B8F3));
    background-image: -webkit-linear-gradient(top, #A0D4F5, #81B8F3);
    background-image: -moz-linear-gradient(top, #A0D4F5, #81B8F3);
    background-image: -o-linear-gradient(top, #A0D4F5, #81B8F3);
    background-image: linear-gradient(top, #A0D4F5, #81B8F3);
}
.ui-slider .ui-slider-handle {
    border-radius: 50%;
    background: #F9FBFA;
    background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0, 50% 100%, color-stop(0%, #C7CED6), color-stop(100%, #F9FBFA));
    background-image: -webkit-linear-gradient(top, #C7CED6, #F9FBFA);
    background-image: -moz-linear-gradient(top, #C7CED6, #F9FBFA);
    background-image: -o-linear-gradient(top, #C7CED6, #F9FBFA);
    background-image: linear-gradient(top, #C7CED6, #F9FBFA);
    width: 22px;
    height: 22px;
    -webkit-box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
    -moz-box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
    box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
    -webkit-transition: box-shadow .3s;
    -moz-transition: box-shadow .3s;
    -o-transition: box-shadow .3s;
    transition: box-shadow .3s;
}
.ui-slider .ui-slider-handle {
    position: absolute;
    z-index: 2;
    width: 22px;
    height: 22px;
    cursor: default;
    border: none;
    cursor: pointer;
}
.ui-slider .ui-slider-handle:after {
    content:"";
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    top: 50%;
    margin-top: -4px;
    left: 50%;
    margin-left: -4px;
    background: #30A2D2;
    -webkit-box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 #FFF;
    -moz-box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 white;
    box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 #FFF;
}
.ui-slider-horizontal .ui-slider-handle {
    top: -.5em;
    margin-left: -.6em;
}
.ui-slider a:focus {
    outline:none;
}
</style>


