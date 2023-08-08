<div class="form-group">
    {{ Form::label('schedule.addClass.InstructorReport', trans('schedule.addClass.InstructorReport'), ['class' => 'col-lg-2 control-label']) }}
    <div class="col-lg-1">
        {{Form::number('schedule_addClass_InstructorReport', null, ['class' => 'form-control', "step" => "15","min" => "0", "max" => "120"])}}
    </div>
    <div class="col-md-2">
        <div class="form-check form-check-inline">
            <label class="radio-inline">
                {{ Form::radio('instructorReportBA', 'Before', true) }}<strong>Before</strong>
            </label>
            <label class="radio-inline">
                {{ Form::radio('instructorReportBA', 'After',  false) }}<strong>After</strong>
            </label>
        </div>
    </div>

    {{ Form::label('schedule.addClass.InstructorLeave', trans('schedule.addClass.InstructorLeave'), ['class' => 'col-lg-2 control-label']) }}
    <div class="col-lg-1">
        {{Form::number('schedule_addClass_InstructorLeave', null, ['class' => 'form-control',"step" => "15","min" => "0", "max" => "120"])}}
    </div><!--col-lg-10-->

    <div class="col-md-2">
        <div class="form-check form-check-inline">
            <label class="radio-inline">
                {{ Form::radio('instructorLeaveBA', 'Before',  false) }}<strong>Before</strong>
            </label>
            <label class="radio-inline">
                {{ Form::radio('instructorLeaveBA', 'After', true) }}<strong>After</strong>
            </label>
        </div>
    </div>

</div>

@section('after-scripts')
@parent

<script>

    function initFacReportAndLeave(callback, options) {
        options = options || false;
        if(options) {
            (options.hasOwnProperty('fac_leave')) ? __setFacLeave(options.fac_leave) : __setFacLeave(0);
            (options.hasOwnProperty('fac_report')) ? __setFacReport(options.fac_report) : __setFacReport(0);
       }

    }
    function __setFacLeave(time) {
        if(time >= 0) {
            $("[name='schedule_addClass_InstructorLeave']").val(time);
            __setLeaveRadios("Before");
        } else {
            $("[name='schedule_addClass_InstructorLeave']").val(Math.abs(time));
            __setLeaveRadios("After");
        }
    }
    function __setFacReport(time) {
        if(time >= 0) {
           $("[name='schedule_addClass_InstructorReport']").val(time);
            __setReportRadios('Before');
        } else {
           $("[name='schedule_addClass_InstructorReport']").val(Math.abs(time));
            __setReportRadios('After');
        }
    }
    function __setReportRadios(which) {
        let reportRadios = $("[name='instructorReportBA']");
        for(var i = 0; i < reportRadios.length; ++i) {
            let thisElement = $(reportRadios[i]);
            if(thisElement.val() == which)
                thisElement.prop('checked', true);
            else
                thisElement.prop('checked', false);
        }
    }
    function __setLeaveRadios(which) {
        let leaveRadios = $("[name='instructorLeaveBA']");
        for(var i = 0; i < leaveRadios.length; ++i) {
            let thisElement = $(leaveRadios[i]);
            if(thisElement.val() == which)
                thisElement.prop('checked', false);
            else
                thisElement.prop('checked', true);
        }
    }

    $("[name='schedule_addClass_InstructorReport']").change(function(e) {
        adjustFacTime(e);
    });
    $("[name='schedule_addClass_InstructorLeave']").change(function(e) {
        adjustFacTime(e);
    });
    function adjustFacTime(e) {
        if(e.target.value != "15" && e.target.value != "00" && e.target.value != "30" && e.target.value != "45") {
            if(e.target.value > 0 && e.target.value <= 14) {
                e.target.value = 15;
            } else if(e.target.value > 15 && e.target.value <= 29) {
                e.target.value = 30;
            } else if(e.target.value > 30 && e.target.value <= 44) {
                e.target.value = 45;
            } else
                e.target.value = 45;

            $(e.target).val(e.target.value);
        }
    }


</script>
@endsection
