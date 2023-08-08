<!--slider div -->
<div id="slider"></div>

<!-- start and end time value display -->
<div class="noUi-values col-sm-4 col-lg-6"><b>{{ trans('labels.scheduling.start_time') }}:</b> <span name="start_time_display"></span></div>
<div class="noUi-values col-sm-4 col-lg-6"><b>{{ trans('labels.scheduling.end_time') }}:</b> <span name="end_time_display"></span></div>


<!-- hidden start/end fields modified by noUiSlider -->
{{--mitcks 5/13/19 - added these if statements because I changed the template pages to get the startTime from controller but this broke edit event
  later if we can go back and fix edit event, then this could be simplified--}}
@if (isset($startTime))
  {{ Form::hidden('start_time', $startTime, ['id' => 'start_time_id']) }}
@else
{{ Form::hidden('start_time', null,['id' => 'start_time_id']) }}
@endif
@if (isset($endTime))
  {{ Form::hidden('end_time', $endTime, ['id' => 'end_time_id']) }}
@else
  {{ Form::hidden('end_time', null,['id' => 'end_time_id']) }}
@endif


@section('after-scripts')
@parent

<script>


  $("#start_time_id").change(function(evt) {
        let val = evt.target.value.split(":");
        let mins = ((parseInt(val[0])*60)+parseInt(val[1]));
        window.slider.set([mins, null]);
        console.log("Start ", mins);
  });
  $("#end_time_id").change(function(evt) {
        let val = evt.target.value.split(":");
        let mins = ((parseInt(val[0])*60)+parseInt(val[1]));
        window.slider.set([null, mins]);
        console.log("End ", mins);
  });



  $(document).ready(function() {

    /* Set start/end time slider handles if the start/end_time_id hidden
       fields already have times in them (this happens when validation fails). */
    let start_hour = $("#start_time_id").val();
    let end_hour = $("#end_time_id").val();

    if(start_hour) {
      let val = $("#start_time_id").val().split(":");
      let mins = ((parseInt(val[0])*60)+parseInt(val[1]));
      start_hour = mins;

      val = $("#end_time_id").val().split(":");
      mins = ((parseInt(val[0])*60)+parseInt(val[1]));
      end_hour = mins;

    } else {
      // set the start/end hours to business if there is are no times from above
      start_hour = hoursToMinutes({{ Session::get('business_start_hour') }});
      end_hour = hoursToMinutes({{ Session::get('business_end_hour')  }});
    }

    //init slider
    createSlider([start_hour, end_hour]);
  }); //document ready


  // create slider - noUiSlider setup
  function createSlider(vals) {

    // 0 = start of day
    // 1440 = maximum minutes in a day
    // step: 15 = amount of minutes to step by
    var initialStartMinute = 0, initialEndMinute = 1440, step = 15;

    try {
      slider.noUiSlider.destroy()
    }
    catch(e) {
    }

    $("#slider").empty();

    var slider = document.getElementById("slider");

    slider.style.height = '20px';
    slider.style.margin = '0 auto 12px';

    if(vals.length == 4) {
      connectVals = [false, true, false, true, false];
    }
    else {
      connectVals = true;
    }

    window.slider = noUiSlider.create(slider, {
      start: vals,
      connect: connectVals,
      step: step,
      range: {
        'min': initialStartMinute,
        'max': initialEndMinute
      },
      tooltips: true,
      format: sliderFormat,
      direction: 'ltr', // put '0' at the left of the slider
      orientation: 'horizontal', // orient the slider horizontal
    });


    // slider handle move - update inputs/display values
    window.slider.on('update',function(values,handle){

      //start time handle
      if(handle === 0) {
        // using moment(string, 'LT') parses 'hh:mm A' into whatever .format()
        $("input[name='start_time']").val(moment(values[0], 'LT').format('HH:mm'));
        $("span[name='start_time_display']").html(moment(values[0], 'LT').format('h:mm A'));
        if(typeof handleVerticalLines == 'function')
          handleVerticalLines();
      }

      //end time handle
      if(handle === 1) {
        $("input[name='end_time']").val(moment(values[1], 'LT').format('HH:mm'));
        $("span[name='end_time_display']").html(moment(values[1], 'LT').format('h:mm A'));
        if(typeof handleVerticalLines == 'function')
            handleVerticalLines();
      }

    });

  }


  // convert minutes to hours for start/end times
  var hoursToMinutes = function(value){
    ret = value * 60;
    return ret;
  };


  // format the slider tooltips
  var sliderFormat = {
    to: function(val) {

      val = Math.ceil(val);
      let hours = convertToHour(val);
      let minutes = convertToMinute(val,hours);

      return moment(formatHoursAndMinutes(hours,minutes), 'HH:mm').format('h:mm A');
    },
    from: function(val) {
      return val;
    }
  };

  // functions convert the values back to readable time for the tooltips
  var convertToHour = function(value){
    return Math.floor(value / 60);
  };

  var convertToMinute = function(value,hour){
    ret = value - hour * 60;
    if(ret !== 15 && ret !== 0 ) {
      // cool hack to round to the nearest n
      i = ret, n = 15;
      ret = ((i % n) > n/2) ? i + n - i%n : i - i%n;
    }
    return ret;
  };

  var formatHoursAndMinutes = function(hours,minutes){
    if(hours.toString().length == 1) hours = '0' + hours;
    if(minutes.toString().length == 1) minutes = '0' + minutes;
    return hours+':'+minutes;
  };

</script>

@endsection
