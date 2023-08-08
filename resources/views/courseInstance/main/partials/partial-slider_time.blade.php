{{-- Script Dependencies --}}
    {{-- jQuery --}}
    {{ Html::script("/js/jquery.js") }}

    {{-- UI css--}}
    {{ Html::style("css/jquery-ui/jquery-ui.css") }}

    {{-- jQuery UI for Time Slider --}}
    {{ Html::script("/js/jquery-ui.js") }}

    {{-- Moment JS --}}
    {{ Html::script("/js/moment-with-locales.js") }}

    {{-- jQuery UI for Time Slider Mobile Add on --}}
    {{ Html::script("/js/jquery.ui.touch-punch.min.js") }}
    {{-- End --}}

{{-- Declare Slider Div --}}
<div id="time-range">
    <div class="sliders_step1">
        <div id="slider-range"></div>
    </div>
    <p>Start: <span class="slider-time"></span> -  End: <span class="slider-time2"></span></p> 
</div>

{{-- DB Fields --}}
{{ Form::hidden('start_time', $startTime, ['id' => 'start_time_id']) }}
{{ Form::hidden('end_time', $endTime, ['id' => 'end_time_id']) }}

<script>

    /*
     * Handles Time Slider and Time Inputs
    */
    // Storing slider init values
    var slider_vals = [];

    @if (isset($startTime))

        var eventStartTime = '{!! $startTime !!}';
        if (eventStartTime.slice(0,2) < 10 && eventStartTime.slice(0,2) >= 1) {
            $('.slider-time').html(eventStartTime.slice(1, eventStartTime.length) + ' AM');
        }
        else if (eventStartTime.slice(0,2) >= 10 && eventStartTime.slice(0,2) < 12) {
            $('.slider-time').html(eventStartTime + ' AM');
        }
        else if (eventStartTime.slice(0,2) > 12) {
            $('.slider-time').html(parseInt(eventStartTime.slice(0,2) - 12) + eventStartTime.slice(2,5) + ' PM');
        }
        else if (eventStartTime.slice(0,2) == 12) {
            $('.slider-time').html(eventStartTime + ' PM');
        }
        else if (eventStartTime.slice(0,2) === '00') {
            $('.slider-time').html('12' + eventStartTime.slice(2,5) + ' AM');
        }

    @endif

    @if (isset($endTime))

        // End Time HTML for Slider
        var eventEndTime =  '{!! $endTime !!}';
        if (eventEndTime.slice(0,2) < 10 && eventEndTime.slice(0,2) >= 1) {
            $('.slider-time2').html(eventEndTime.slice(1, eventEndTime.length) + ' AM');
        }
        else if (eventEndTime.slice(0,2) > 12) {
            $('.slider-time2').html(parseInt(eventEndTime.slice(0,2) - 12) + eventEndTime.slice(2,5) + ' PM');
        }
        else if (eventEndTime.slice(0,2) >= 10 && eventEndTime.slice(0,2) < 12) {
            $('.slider-time2').html(eventEndTime + ' AM');
        }
        else if (eventEndTime.slice(0,2) == 12) {
            $('.slider-time2').html(eventEndTime + ' PM');
        }
        else if (eventEndTime.slice(0,2) === '00') {
            $('.slider-time2').html('12' + eventEndTime.slice(2,5) + ' AM');
        }

    @endif

    // Set Init Values
    {{--slider_vals = [{{ Session::get('business_start_hour') }} * 60, {{ Session::get('business_end_hour') }} * 60];--}}
    // to init sliders value 1
    let hour1 = parseInt(eventStartTime.slice(0,2));
    let min1 = parseInt(eventStartTime.slice(3,5));
    min1 = min1 / 60;

    // to init sliders value 2
    let hour2 = parseInt(eventEndTime.slice(0,2));
    let min2 = parseInt(eventEndTime.slice(3,5));
    min2 = min2 / 60;

    // Set Slider Value
    slider_vals = [(hour1 + min1) * 60, (hour2 + min2) * 60];

    // create Slider
    $("#slider-range").slider({
        range: true,
        min: 0, // 12:00 AM
        max: 1440, // 11:59 PM
        step: 15, // min increments
        values: slider_vals,
        slide: function (e, ui) {
        
        // Stuff for UI, time 1      
        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);

        // Update Input value
        $("input[name='start_time']").val(moment(hours1 + ':' + minutes1, 'LT').format('HH:mm'));

        // Prepare Time 1 for HTML
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

        // Stuff for UI, time 2
        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);
        
        // Need to update this input field again, this submits the evnt slide change 
        $("input[name='end_time']").val(moment(hours2 + ':' + minutes2, 'LT').format('HH:mm'));
        
        // Prepare Time 1
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

/*
 * Handle jQuery UI Date Picker
*/
{{--var init_date = '{{ $date }}';--}}

// Diff User Agents
{{--@if ($agent->browser() === 'Firefox')--}}
{{--    init_date = moment(init_date).format("Y-MM-DD");--}}
{{--    $('#datepicker').val(init_date)--}}
{{--@elseif ($agent->browser() === 'Safari')--}}
{{--    @if ($agent->isMobile() == 1)--}}
{{--        init_date = moment(init_date).format("Y-MM-DD");--}}
{{--        $('#datepicker').val(init_date)       --}}
{{--    @else--}}
{{--        init_date = moment(init_date).format("Y-MM-DD");--}}
{{--        $('#datepicker').datepicker({ --}}
{{--            dateFormat: "yy-mm-dd"--}}
{{--        }).datepicker("setDate",  init_date);     --}}
{{--    @endif--}}
{{--@else--}}
{{--    init_date = moment(init_date).format("MM-DD-Y");--}}
{{--    $('#datepicker').datepicker({ --}}
{{--        dateFormat: "mm-dd-yy"--}}
{{--    }).datepicker("setDate",  init_date); // update input with current date on page load   --}}
{{--@endif--}}

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