<div class="form-group" style="padding-bottom:-10px;">
    {{ Form::label('times', trans('schedule.addClass.times'), ['class' => 'col-lg-2 control-label required']) }}
    <div class="col-lg-6">
        <div class="form-group">
            <div class="col-sm-2">
                {{ Form::label('setup_time', trans('schedule.addClass.setup')) }}
                @if(isset($defaultSetupTime))
                    {{ Form::number('setup_time',$defaultSetupTime,["max"=>"120", "min"=>"0", "class"=>"form-control setup-teardown-vertical", "step"=>"15", "id"=>"setup", "value"=>"0"]) }}
                @else
                    {{ Form::number('setup_time',0,["max"=>"120", "min"=>"0", "class"=>"form-control setup-teardown-vertical", "step"=>"15", "id"=>"setup", "value"=>"0"]) }}
                @endif
            </div>
            <div class="col-sm-8">
                {{ Form::label('range', trans('labels.scheduling.event_time')) }}
                @include('courseInstance.scheduleRequest.partial-slider_time')
            </div>

            <div class="col-sm-2">
                {{ Form::label('teardown_time', trans('schedule.addClass.teardown')) }}
                @if(isset($defaultSetupTime))
                    {{ Form::number('teardown_time',$defaultTeardownTime,["max"=>"120", "min"=>"0", "class"=>"form-control setup-teardown-vertical", "step"=>"15", "id"=>"teardown"]) }}
                @else
                    {{ Form::number('teardown_time',0,["max"=>"120", "min"=>"0", "class"=>"form-control setup-teardown-vertical", "step"=>"15", "id"=>"teardown"]) }}
                @endif
            </div>
        </div>
    </div>
</div>

@section('after-scripts')
@parent
<script>
    function __adjustAmPm(e) {
        let newTime = parseInt(e.target.value.replace(':',''));
        if( (newTime >= 0000 && newTime <= 0059) ) {
            $(e.target).val("12:00");
            e.target.value == "12:00";
        } else if( (newTime >= 2300 && newTime <= 2359) ) {
            $(e.target).val("11:00");
            e.target.value == "11:00";
        }
        return newTime;
    }

    function _sttAdjustSetupTeardown(e) {
        let minutes = e.target.value;
        if(minutes != "15" && minutes != "0" && minutes != "30" && minutes != "45") {
            if(minutes >= 1 || minutes <= 14) {
                minutes = 15;
            } else if (minutes <= 16 && minutes <= 29) {
                minutes = 30;
            } else if (minutes <= 31 && minutes <= 44) {
                minutes = 45;
            } else {
                minutes = 45;
            }
        }
        $(e.target).val(minutes.toString());
    }
    function initSetupTeardownTimeSelector(callback) {
        var _selectedSetupTime = $('.setup-teardown-vertical[name="setup_time"]').val();
        var _selectedTeardownTime = $('.setup-teardown-vertical[name="teardown_time"]').val();
        var _selectedStartTime = $("[name='start_time']").val();
        var _selectedEndTime = $("[name='end_time']").val();
        $('.setup-teardown-vertical[name="setup_time"]').on("change", function(evnt){
            _sttAdjustSetupTeardown(evnt);
            _selectedSetupTime = $(evnt.target).val();
            callback({setup_time: _selectedSetupTime, teardown_time: _selectedTeardownTime, start_time: _selectedStartTime, end_time: _selectedEndTime}, evnt);
        });
        $('.setup-teardown-vertical[name="teardown_time"]').on("change", function(evnt){
            _sttAdjustSetupTeardown(evnt);
            _selectedTeardownTime = $(evnt.target).val();
            callback({setup_time: _selectedSetupTime, teardown_time: _selectedTeardownTime, start_time: _selectedStartTime, end_time: _selectedEndTime}, evnt);
        });
        $('[name="start_time"]').on("change", function(evnt){
            _selectedStartTime = $(evnt.target).val();
            startTimeHandler(evnt);
            callback({setup_time: _selectedSetupTime, teardown_time: _selectedTeardownTime, start_time: _selectedStartTime, end_time: _selectedEndTime}, evnt);
        });
        $('[name="end_time"]').on("change", function(evnt){
            _selectedEndTime = $(evnt.target).val();
            endTimeHandler(evnt);
            callback({setup_time: _selectedSetupTime, teardown_time: _selectedTeardownTime, start_time: _selectedStartTime, end_time: _selectedEndTime}, evnt);
        });
        callback({setup_time: _selectedSetupTime, teardown_time: _selectedTeardownTime, start_time: _selectedStartTime, end_time: _selectedEndTime}, false);
    }
    function __setSetupTeardownTime(element, value, trigger) {
        trigger = trigger || false;
        if(element == 'setup_time' || element == 'teardown_time') {
            $('.setup-teardown-vertical[name="'+element+'"]').val(value);
            if(trigger)
                $('.setup-teardown-vertical[name="'+element+'"]').trigger('change');
        } else {
            $("[name='"+element+"']").val(value);
            if(trigger)
                $("[name='"+element+"']").trigger('change');
        }
    }
    function __resetSetupTeardownTime() {

    }
    var lastEndTime = 0900;
    function endTimeHandler(e) {
        adjustInputMinutes(e);
        lastEndTime = __adjustAmPm(e);

        selectedEndTime = "T" + e.target.value + ":00";
        if( (new Date(selectedDate+""+selectedEndTime).getTime()/1000) < (new Date(selectedDate+""+selectedStartTime).getTime()/1000) ) {
            let newStartTime = parseInt(e.target.value.split(':'));
            newStartTime-2;
            if(newStartTime < 10)
                newStartTime = "0"+newStartTime;

            $('input[name="start_time"]').val(newStartTime+":00");
        }
    }
    var lastStartTime = 0900;
    function startTimeHandler(e) {
        adjustInputMinutes(e);
        lastStartTime = __adjustAmPm(e);

        selectedStartTime = "T" + e.target.value + ":00";
        if( (new Date(selectedDate+""+selectedEndTime).getTime()/1000) < (new Date(selectedDate+""+selectedStartTime).getTime()/1000) ) {
            let newEndTime = parseInt(e.target.value.split(':'));
            newEndTime++
            if(newEndTime < 10)
                newEndTime = "0"+newEndTime;

            $('input[name="end_time"]').val(newEndTime+":00");
        }
    }
    function adjustInputMinutes(e) {
        let splitTime = e.target.value.split(':');
        if(splitTime[1] != "15" && splitTime[1] != "00" && splitTime[1] != "30" && splitTime[1] != "45") {
            let mangledMinutes = "00";
            if(splitTime[1] > 0 && splitTime[1] < 15) {
                if(splitTime[1] < 15 && splitTime[1] > 7)
                    mangledMinutes = "15";
                else
                    mangledMinutes = "00";
            } else if(splitTime[1] > 15 && splitTime[1] < 30) {
                if(splitTime[1] > 15 && splitTime[1] < 23)
                    mangledMinutes = "15";
                else
                    mangledMinutes = "30";
            } else if(splitTime[1] > 30 && splitTime[1] < 45) {
                if(splitTime[1] > 30 && splitTime[1] < 37)
                    mangledMinutes = "30";
                else
                    mangledMinutes = "45";
            } else {
                mangledMinutes = "45";
            }

            e.target.value = splitTime[0]+":"+mangledMinutes;
            $(e.target).val(e.target.value);
        }
    }
</script>
@endsection
