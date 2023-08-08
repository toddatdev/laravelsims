<style>
    [data-toggle="collapse"] .fa:before {
        content: "\f139";
    }

    [data-toggle="collapse"].collapsed .fa:before {
        content: "\f13a";
    }
</style>

<div class="container-fluid">
    {{--COURSE, TEMPLATE ABBRV ROW--}}
    <div class="form-inline">
        {{ Form::label('course', trans('labels.scheduling.course'), ['class' => 'control-label required mb-3 mr-sm-2']) }}
        @isset($courseInstance)
            {{--read only when course cannot be changed, e.g. duplicate or add another event to instance--}}
            {{ Form::select('course_id', $courses->pluck('AbbrvVirtual', 'id'), $courseId, ['disabled' => true, 'class' => 'form-control mb-3 mr-sm-5', 'placeholder' => trans('labels.general.select'), 'data-dependent' => 'template_id', 'id' => 'course_id']) }}
            {{ Form::hidden('course_id', $courseId) }}
            @if (Request::is('*anotherEvent*'))
                {{ Form::hidden('course_instance_id', $courseInstance->id) }}
            @endif
        @else
            {{ Form::select('course_id', $courses->pluck('AbbrvVirtual', 'id'), $courseId, ['class' => 'form-control mb-3 mr-sm-5', 'placeholder' => trans('labels.general.select'), 'data-dependent' => 'template_id', 'id' => 'course_id']) }}
        @endisset

        {{ Form::label('template', trans('labels.scheduling.template'), ['class' => 'control-label mb-2 mr-sm-2']) }}
        {{--check to see if course_id was passed in, set template list if yes--}}
        @if($courseId != 0)
            {{ Form::select('template_id', $templateList->pluck('name', 'id'), $templateId, ['class' => 'form-control mb-3 mr-sm-5', 'id' => 'template_id', 'placeholder' => trans('labels.general.select')]) }}
        @else
            {{--Note the template_id select is passed empty array because it is filled in below with js/ajax when course_id selected--}}
            {{ Form::select('template_id', [], $templateId, ['class' => 'form-control mb-3 mr-sm-5', 'id' => 'template_id', 'placeholder' => trans('labels.general.select')]) }}
        @endif
{{--                commenting this out - as it no longer applies due to template compare feature--}}
{{--                <span id="template_warning" class="badge badge-pill"></span>--}}
{{--        <div class="form-group col-lg-4">--}}
        {{ Form::label('abbrv', trans('labels.general.abbrv'), ['class' => 'control-label mb-3 mr-sm-2']) }}
        {{ Form::input('abbrv', 'abbrv', $abbrv, ['id'=>'abbrv', 'maxLength' => '30', 'class' => 'form-control mb-3', 'placeholder' => trans('labels.courses.abbrv')]) }}
    </div>

    {{--EVENT DATE, NUM PARTICIPANTS ROW--}}
    <div class="form-inline">
        {{--single date--}}
        {{ Form::label('eventDate', trans('labels.scheduling.event_date'), ['class' => 'control-label required mb-2 mr-2']) }}
        {{ Form::hidden('selectDate',  \Carbon\Carbon::parse($date)->format('Y-m-d'), ['class' => 'form-control', 'id' => 'selectDate']) }}
        {{ Form::text('single_date', \Carbon\Carbon::parse($date)->format('m-d-Y'), ['class' => 'form-control mb-2 mr-sm-5', 'style'=>'width:100px;', 'id' => 'single_date' , 'maxLength' => '1000']) }}

{{--2020-07-30 MITCKS - OLD DATE PICKER - LEAVING THIS HERE FOR NOW IN CASE THERE IS A NEW TO REVERT--}}
{{--                @if ($agent->browser() === 'Firefox')--}}
{{--                    <div><input type="date" id="datepicker" class="form-control"></div>--}}
{{--                @elseif ($agent->browser() === 'Safari')--}}
{{--                    @if ($agent->isMobile() == 1)--}}
{{--                        <div><input type="date" id="datepicker" class="form-control"></div>--}}
{{--                    @else--}}
{{--                        <div><input type="text" id="datepicker" class="form-control"></div>--}}
{{--                    @endif--}}
{{--                @else--}}
{{--                    <div><input type="text" id="datepicker" class="form-control"></div>--}}
{{--                @endif--}}

        {{--class size--}}
        {{ Form::label('numParticipants', trans('labels.scheduling.num_participants'), ['class' => 'control-label mb-2 mr-2 required']) }}
        {{ Form::number('class_size', $classSize, ['id' => 'class_size', 'style'=>'width:75px;', 'class' => 'form-control mb-2 mr-sm-5', 'placeholder' => '0', "min" => "0"]) }}

        {{--2020-07-30 MITCKS - I added recurrence here on far right rather than next to the event date because it is hidden on edit--}}
        {{--recurrence dates--}}
        @if (!Request::is('*editEvent*'))
        {{ Form::label('recurrence_dates', trans('labels.event.recurrence_dates'), ['class' => 'control-label mb-2 mr-sm-2']) }}
        {{ Form::text('recurrence_dates', null, ['id' => 'recurrence_dates', 'class' => 'form-control date mb-2', 'style'=>'width:300px;', 'placeholder' => trans('labels.event.recurrence_dates') , 'maxLength' => '1000']) }}
        @endif
    </div>

    {{-- TIME ROW --}}
    <div class="form-inline">
        {{ Form::label('times', trans('schedule.addClass.times'), ['class' => 'control-label required']) }}
        <div class="col-lg-10">
            <div class="form-group row">
                <div class="col-sm-2">
                    {{ Form::label('setup_time', trans('schedule.addClass.setup')) }}
                    {{ Form::select('setup_time', Session::get('minutes'), $setupTime, ['class' => 'form-control', 'id' => 'setup_time']) }}
                </div>
                {{-- Slider --}}
                <div class="col-sm-5">
                    {{ Form::label('range', trans('labels.scheduling.event_time')) }}
                    @include('courseInstance.main.partials.partial-slider_time')
                </div>
                <div class="col-sm-2">
                    {{ Form::label('teardown_time', trans('schedule.addClass.teardown')) }}
                    {{ Form::select('teardown_time', Session::get('minutes'), $teardownTime, ['class' => 'form-control', 'id' => 'teardown_time']) }}
                </div>
            </div>
        </div>
    </div>

    {{--INSTRUCTOR REPORT/LEAVE--}}
    <div class="form-inline">
        {{ Form::label('schedule.addClass.InstructorReport', trans('schedule.addClass.InstructorReport'), ['class' => 'control-label mb-3 mr-sm-2']) }}
        @if($instructorReport > 0)
            {{ Form::select('instructor_report', Session::get('minutes'), $instructorReport, ['class' => 'form-control mb-3', 'id' => 'instructor_report']) }}
            {{ Form::select('instructor_report_BA', ['B' => 'Before Start Time', 'A' => 'After Start Time'], 'A', ['class' => 'form-control mb-3 mr-sm-5', 'id' => 'instructor_report_BA' ]) }}
        @else
            <?php
            $instructorReport = abs($instructorReport);
            ?>
            {{ Form::select('instructor_report', Session::get('minutes'), $instructorReport, ['class' => 'form-control mb-3', 'id' => 'instructor_report']) }}
            {{ Form::select('instructor_report_BA', ['B' => 'Before Start Time', 'A' => 'After Start Time'], 'B', ['class' => 'form-control mb-3 mr-sm-5', 'id' => 'instructor_report_BA']) }}
        @endif

        <span>&nbsp;&nbsp;&nbsp;</span>

        {{ Form::label('schedule.addClass.InstructorLeave', trans('schedule.addClass.InstructorLeave'), ['class' => 'control-label mb-3 mr-sm-2']) }}
        <span>&nbsp;</span>
        @if($instructorLeave >= 0)
            {{ Form::select('instructor_leave', Session::get('minutes'), $instructorLeave, ['class' => 'form-control mb-3', 'id' => 'instructor_leave']) }}
            {{ Form::select('instructor_leave_BA', ['B' => 'Before End Time', 'A' => 'After End Time'], 'A', ['class' => 'form-control mb-3', 'id' => 'instructor_leave_BA']) }}
        @else
            <?php
            $instructorLeave = abs($instructorLeave);
            ?>
            {{ Form::select('instructor_leave', Session::get('minutes'), $instructorLeave, ['class' => 'form-control mb-3', 'id' => 'instructor_leave']) }}
            {{ Form::select('instructor_leave_BA', ['B' => 'Before End Time', 'A' => 'After End Time'], 'B', ['class' => 'form-control mb-3', 'id' => 'instructor_leave_BA']) }}
        @endif
    </div>

    {{--IMR, COLOR, SIMS SPECIALIST, SPECIAL REQUIREMENTS--}}
    <div class="form-inline">
        {{ Form::label('Initial Meeting Room', trans('schedule.addClass.room'), ['class' => 'control-label required mb-3 mr-sm-2']) }}
        {{ Form::select('initial_meeting_room', $IMR_List, $initialMeetingRoom, ['class' => 'form-control mb-3 mr-sm-5 fieldToplotDP', 'id' => 'initial_meeting_room', 'placeholder' => trans('schedule.addClass.selectRoom')]) }}

        <div class=" form-inline mb-3 mr-sm-2"> {{--needed to add extra dive to line up color--}}
            {{ Form::label('html_color', trans('labels.general.color'), ['class' => 'mr-sm-2 control-label']) }}
            @if(empty($color))
                {{ Form::input('html_color', 'html_color', null, ['class' => 'spectrum_html_color ', 'id' => 'html_color']) }}
            @else
                {{ Form::input('html_color', 'html_color', $color, ['class' => 'spectrum_html_color ', 'id' => 'html_color']) }}
            @endif
            <span id='spectrum-text' class='palette-label mb-3 mr-sm-5'></span>
        </div>
        {{--Do not display if nothing in status lookup table--}}
        @if(!$statusTypes->isEmpty())
            {{ Form::label('html_color', trans('labels.scheduling.status'), ['class' => 'mb-3 mr-sm-2 control-label']) }}
            <div class="col-lg-2" style="white-space: nowrap">
                {{ Form::select('status_type_id', $statusTypes->pluck('description', 'id'), $statusTypeId, ['class' => 'form-control mb-3 mr-sm-2', 'placeholder' => trans('labels.general.select'), 'id' => 'status_id']) }}
            </div>
        @endif

        <div class="mb-3 mr-sm-2" style="white-space: nowrap">
            @if ($simsSpecNeeded == 1)
                {{ Form::checkbox('sims_spec_needed', 1, true, array('id'=>'sims_spec_needed'))}}
            @else
                {{ Form::checkbox('sims_spec_needed', 1, false, array('id'=>'sims_spec_needed'))}}
            @endif
            <span style="padding-right:20px" class="simptip-position-top simptip-smooth" data-tooltip="{{trans('labels.scheduling.sim_spec')}}">
                <i class="fas fa-lg fa-running" data-toggle="tooltip" data-placement="top"></i>
            </span>

            @if ($specialRequirements == 1)
                {{ Form::checkbox('special_requirements', 1, true, array('id'=>'special_requirements'))}}
            @else
                {{ Form::checkbox('special_requirements', 1, false, array('id'=>'special_requirements'))}}
            @endif
            <span class="simptip-position-top simptip-smooth" data-tooltip="{{trans('labels.scheduling.special_requirements')}}">
                <i class="fas fa-lg fa-star" data-toggle="tooltip" data-placement="top"></i>
            </span>
        </div>

    </div>

    {{-- If this is a request, show the requested location and number of rooms --}}
    @if (Request::is('*fromRequest*'))

        <div class="form-inline">

                {{ Form::label('schedule.addClass.requestInfo', trans('schedule.addClass.requestInfo'), ['class' => 'control-label mb-2 mr-2']) }}
                <div class="col-lg-8">
                    <span style="color:blue"> {!! $scheduleRequest->requestedBy() !!} : {{$scheduleRequest->Location->getBuildingLocationLabelAttribute()}} : {{$scheduleRequest->num_rooms}}
                        @if ($scheduleRequest->num_rooms == 1)
                            {{trans('labels.scheduling.room') }}
                        @else
                            {{trans('labels.scheduling.rooms') }}
                        @endif
                    </span>
                    @if ($scheduleRequest->notes != "")
                        <span style="color:red">&nbsp;&nbsp;{{ trans('labels.scheduling.special_below') }}</span>
                    @endif

                    {{-- display icon for comments if they exist--}}
                    @if ($scheduleRequest->comments(true))

                        <span class="simptip-position-top simptip-smooth" data-tooltip="View Comments">
                            <a href="#commentsModal" data-toggle="modal" data-target="#commentsModal">
                                <i class="fad fa-lg fa-comments editIcon" data-placement="top"></i>
                            </a>
                        </span>

                        <!-- Comments Modal -->
                        <div class="modal fade" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="pull-left" id="ModalLabel">{{trans('labels.scheduling.comments')}}</h4>
                                        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {!! $scheduleRequest->comments(true) !!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            @endif

        </div>

<!-- advanced options accordion panel -->
<div id="accordion">
    <div class="card card-default">

        <div class="card-header collapsed" data-toggle="collapse" data-target="#collapseOne">
            <h4 class="card-title">
                <span class="accordion-toggle">
                    <i class="fa" aria-hidden="true"></i> {{ trans('labels.event.notes') }}
                </span>
            </h4>
        </div>

        <div id="collapseOne"
                {{-- if there are internal comments, show them --}}
        @if ($internalComments != "")
            > {{-- Not collapsed. Just close the div--}}
            @else
                class="card-collapse collapse">
            @endif

            <div class="card-body">

                <div class="form-group row">
                    {{ Form::label('public_comments', trans('labels.event.public_notes'), ['class' => 'col-lg-1 control-label']) }}
                    <div class="col-lg-11">
                        {{ Form::text('public_comments', $publicComments, ['class' => 'form-control', 'placeholder' => trans('labels.scheduling.pub_comments') , 'maxLength' => '50']) }}
                    </div>
{{--                    <div class="col-sm-1 text-left">--}}
{{--                        <i class="fas fa-question-circle fa-lg text-muted" title="{{ trans('labels.scheduling.pub_comments_help') }}"></i>--}}
{{--                    </div>--}}
                </div>

                <div class="form-group row">
                    {{ Form::label('internal_comments', trans('labels.event.internal_notes'), ['class' => 'col-lg-1 control-label']) }}
                    <div class="col-lg-11">
                        {{ Form::textarea('internal_comments', $internalComments, ['id'=>'internal_comments', 'class' => 'form-control mce', 'placeholder' => trans('labels.scheduling.internal_comments')]) }}
                    </div>
{{--                    <div class="col-sm-1 text-left">--}}
{{--                        <i class="fas fa-question-circle fa-lg text-muted" title="{{ trans('labels.scheduling.internal_comments_help') }}"></i>--}}
{{--                    </div>--}}
                </div>

                {{-- Adding User Create Resource Events to Submission --}}
                {{ Form::hidden('resource_events', null, ['id' => 'resource_events']) }}

                {{-- Input Field to catch all events to delete --}}
                {{ Form::hidden('delete_event_arr', null, ['id' => 'delete_event_arr']) }}

                @if (Request::is('*fromRequest*'))
                    {{--This hidden request_id is used in JS below to prevent the default request values
                        from being overwritten by the template values--}}
                    {{ Form::hidden('request_id', $scheduleRequest->id, ['id' => 'request_id']) }}
                    {{--This is used to prepend the special request notes to template notes on template change--}}
                    @if ($scheduleRequest->notes != null)
                        {{ Form::hidden('request_notes', '<p>***Special Request:<br>'.$scheduleRequest->notes.'<br>***</p>', ['id' => 'request_notes'] ) }}
                    @else
                        {{ Form::hidden('request_notes', '', ['id' => 'request_notes'] ) }}
                    @endif
                @endif

            </div>
        </div> <!-- / advanced options panel collapse -->

    </div>
</div>

{{-- @section('after-scripts') --}}

{{ Html::script("/js/modernizr.js") }}
{{ Html::script("/js/jquery-ui.js") }}
{{ Html::script("/js/jquery-ui-timepicker-addon.js") }}
{{ Html::script("/js/datetime.js") }}

{{--Tiny MCE Editor for Internal Comments--}}
<script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>

{{--recurrence multi-date picker--}}
{{ Html::script("/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js") }}
{{ Html::style("/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css") }}

<script>

    $('#single_date').datepicker({
        multidate: false,
        autoclose: true,
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        useCurrent: false,
        orientation: "bottom",
    }).on('changeDate', function(e) {
        //console.log('changeDate event trigger');
        //this gives .datePicker is not a function error
        // $('#recurrence_dates').datepicker( 'setStartDate', $(this).val());

    });

    $('#recurrence_dates').datepicker({
        multidate: true,
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        orientation: "bottom",
        //defaultViewDate: '01-31-2021',

    });

</script>


{{--sample code from https://programmingpot.com/laravel/dependent-droop-down-in-laravel/--}}
<script>

    //SET INTERNAL COMMENTS ON TEMPLATE CHANGE
    // ===============================================================================================================
    // mitcks 2020-04-06 this needs to be done here inside the document ready because if you try to set it with the rest of
    // the request/template default values below the tiny mce editor isn't created yet and it will fail
    // I tried having the entire section below in doc.ready but the template grid resources do not load correctly
    // ===============================================================================================================
    $(document).ready(function() {

        //reset any filters in local storage when you open page
        resetRowFilters();

        //turn off autocomplete for recurrence date input so it doesn't hide the datepicker
        $('#recurrence_dates').attr('autocomplete','off');
        $('#single_date').attr('autocomplete','off');

        $('#template_id').on('change', function(e) {

            resetRowFilters();

            var templateID = $(this).val();

            //place values from form fields in local storage to use on template compare
            //todo: (mitcks) this could also be done with a json string, but would still have to loop through all the keys to set values - check to see if that would be more efficient
            localStorage['course_id'] = $('#course_id').val();
            localStorage['headingText'] = $('#course_id option:selected').text() + ' ' + moment($('#selectDate').val()).format('MM/DD/YYYY');
            localStorage['currLoc'] = $(location).attr('href');
            @isset($event)
                localStorage['event_id'] = {{ $event->id }};
            @else
                localStorage['event_id'] = 0;
            @endisset
            localStorage['start_date'] = $('#selectDate').val();
            localStorage['initial_meeting_room'] = $('#initial_meeting_room').val();
            localStorage['abbrv'] = $('#abbrv').val();
            localStorage['class_size'] = $('#class_size').val();
            localStorage['setup_time'] = $('#setup_time').val();
            localStorage['start_time'] = $('#start_time_id').val();
            localStorage['end_time'] = $('#end_time_id').val();
            localStorage['teardown_time'] = $('#teardown_time').val();
            localStorage['instructor_report'] = $('#instructor_report').val();
            localStorage['instructor_report_BA'] = $('#instructor_report_BA').val();
            localStorage['instructor_leave'] = $('#instructor_leave').val();
            localStorage['instructor_leave_BA'] = $('#instructor_leave_BA').val();
            localStorage['html_color'] = $('#html_color').val();
            if ($('#sims_spec_needed').is(":checked"))
            {
                localStorage['sims_spec_needed'] = 1;
            }
            else
            {
                localStorage['sims_spec_needed'] = 0;
            }
            if ($('#special_requirements').is(":checked"))
            {
                localStorage['special_requirements'] = 1;
            }
            else
            {
                localStorage['special_requirements'] = 0;
            }
            localStorage['public_comments'] = $('#public_comments').val();
            localStorage['internal_comments'] = tinyMCE.activeEditor.getContent();

            //TODO: Need to figure out how to deal with IMR on create and then we could remove this eventId check here and use templateCompare on create too
            //This redirect to compare template values should only occur when event_id is available
            @isset($event)
                if(templateID != '') //in case they select "Select..." don't try to compare
                {
                    window.location.href = "/courseInstance/main/templateCompare/" + templateID + "/{{$event->id}}";
                }
            @endisset

            //get html page name
            var href = document.location.href;
            var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);

            //if the url is templateApply, then they are editing and selecting which values they want to use, so ignore all this
            if (lastPathSegment != 'templateApply') {

                var scheduleRequestID = $('#request_id').val();
                let selectedDate = moment($('#single_date').val(), 'MM-DD-YYYY').format('Y-MM-DD');

                // This is used to prepend the request notes from hidden field to the internal notes below
                var requestNotes = "";
                if ($('#request_notes').val() != null) {
                    requestNotes = $('#request_notes').val();
                }

                //this should only be done on create, not edit
                if (templateID) {

                    $.ajax({
                        url: '/findDefaultTemplateValues/' + templateID + '/' + scheduleRequestID+'/'+selectedDate,
                        type: "GET",
                        data: {"_token": "{{ csrf_token() }}"},
                        dataType: "json",
                        success: function (data) {

                            if (data.templateOptions) {

                                $.each(data.templateOptions, function (key, value) {
                                    if (key == 'internalComments') {
                                        if (value != null) {
                                            var activeEditor = tinyMCE.get('internal_comments');
                                            var content = requestNotes + value;
                                            activeEditor.setContent(content);
                                        }
                                    }
                                }); //$.each(data.templateOptions, function (key, value)
                            } //if (data.templateOptions)
                        } //success: function
                    }).then(function() {
                        //set filter to scheduled (filter is reset first up above with resetRowFilters)
                        localStorage['events_filter'] = 'Scheduled';
                        //note it is important to apply the filters here AFTER the then statement, otherwise they
                        // get applied before all the events load and rows can be hidden incorrectly
                        applyRowFilters()
                    });
                } //if(templateID)
            } //if(templateApply != 'templateApply')
        }); //$('#template_id').on('change', function(e)
    }); //$(document).ready(function()


    // ===============================================================================================================
    // mitcks 2020-04-03 I think Tanner has document.ready commented out because otherwise it does not
    // load the default template resources on page load, however this was also causing template values
    // to overwrite values from a schedule request like start/end times (where request should take precedence).  I tried uncommenting this
    // and that fixed one problem, but created a second problem of default template resources not loading in the grid
    // so I took a different approach and edited the findDefaultTemplateValues function to take schedule request into consideration
    // ===============================================================================================================
    // $(document).ready(function() {

    //SET DEFAULT VALUES FROM COURSE OPTIONS, SCHEDULE REQUEST AND TEMPLATE (as applicable)
    $('#course_id').on('change', function() {

        var courseID = $(this).val();
        if(courseID) {

            $.ajax({
                url: '/findDefaultValuesWithCourseID/'+courseID+'/1', //1 since we are limiting locations just to what they can schedule
                type: "GET",
                data : {"_token":"{{ csrf_token() }}"},
                dataType: "json",
                success:function(data) {
                    // console.log(data.templates);
                    // console.log(data.courseOptions);

                    //populate template dropdown
                    if(data.templates){

                        $('#template_id').empty();
                        $('#template_id').focus;
                        $('#template_id').append('<option value="">{{ trans('labels.general.select') }}</option>');
                        $.each(data.templates, function(key, value){
                            $('select[name="template_id"]').append('<option value="'+ value.id +'">' + value.name+ '</option>');
                        });
                    }else{
                        $('#template_id').empty();
                    }

                    //populate fields from course_options
                    if(data.courseOptions) {

                        //initializing these here so they can be used for separator lines after the loop through
                        // course options, if values exist in course options they are updated there
                        var setupTime = 0;
                        var tearDownTime = 0;

                        $.each(data.courseOptions, function (key, value) {
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

                            if(key == 'instructorReport')
                            {
                                $('#instructor_report').val(Math.abs( value));
                                if(value < 0)
                                {
                                    $('#instructor_report_BA').val('B');
                                }
                                else if(value == 0)
                                {
                                    $('#instructor_report_BA').val('B');
                                }
                                else
                                {
                                    $('#instructor_report_BA').val('A');
                                }
                            }

                            if(key == 'instructorLeave')
                            {
                                $('#instructor_leave').val(Math.abs( value));
                                if(value < 0)
                                {
                                    $('#instructor_leave_BA').val('B');
                                }
                                else
                                {
                                    $('#instructor_leave_BA').val('A');
                                }
                            }

                            if(key == 'color')
                            {
                                $("#html_color").spectrum("set", value);
                            }
                        });

                        //(2020-07-09 mitcks) on course change update course abbrv text on newly selected grid resources
                        // stored_events is an array of new events that will need stored upon submit (it is defined in partial-grid)
                        //console.log('init on course change:'+stored_events.length );
                        for (let i = 0; i < stored_events.length; i++) {
                            //console.log(stored_events[i]);

                            var resourceToUpdate = stored_events.filter(function(obj) {

                                resources = dp.rows.find(obj.resource).events.all();
                                //console.log('resources:'+ resources);
                                if (resources.length > 0) {
                                    for (let i = 0; i < resources.length; i++) {
                                        obj.text = $("#course_id option:selected").html();
                                        if(obj.isIMR == 1)
                                        {obj.text += " (IMR)";}
                                    }
                                    dp.update()
                                }
                            });
                        }

                        //update grid separator lines
                        var gridDate = moment($('#selectDate').val()).format("Y-MM-DD");

                        var hours1 = Math.floor(slider_values[0] / 60);
                        var minutes1 = slider_values[0] - (hours1 * 60);
                        hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
                        minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();
                        var line1 = gridDate + 'T' + hours1 + ':' +  minutes1 + ':00';

                        var hours2 = Math.floor(slider_values[1] / 60);
                        var minutes2 = slider_values[1] - (hours2 * 60);
                        hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
                        minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
                        var line2 = gridDate + 'T' + hours2 + ':' +  minutes2 + ':00';

                        //convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
                        var setupSeconds = setupTime*60;
                        var setupLine = moment(hours1 + ':' +  minutes1 + ':00', "HH:mm:ss").subtract(setupSeconds, 'seconds').format("HH:mm:ss");
                        var teardownSeconds = tearDownTime*60;
                        var teardownLine = moment(hours2 + ':' +  minutes2 + ':00', "HH:mm:ss").add(teardownSeconds, 'seconds').format("HH:mm:ss");

                        dp.update({separators: [{color:"blue", location: gridDate + "T" + setupLine}, {color:"blue", location: gridDate + "T" + teardownLine}, {color: 'red', location: line1}, {color: 'red', location: line2}]});
                    }
                }
            }).then(function() {
                //filters do not need to be set here on course change
            });
        }
        else{
            //no course ID was passed
            $('#template_id').empty();
        }
    });

    // Declare Access to Slider here so accessible when inside template change func.
    $slider = $('#slider-range');


    $('#template_id').on('change', function(e) {

        // console.log('the template has changed');

        //get html page name
        var href = document.location.href;
        var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);

        var templateID = $(this).val();

        //the presence of a scheduleRequestID alters the data returned from the ajax call below
        var scheduleRequestID =  $('#request_id').val();
        let selectedDate = moment($('#single_date').val(), 'MM-DD-YYYY').format('Y-MM-DD');

        //alert('request_id'+scheduleRequestID);

        var startTimeDelta = 0;
        var endTimeDelta = 0;

        var templateApplySelectedResources = 'eventOnly'; //(mitcks) set to this by default in case not from templateApply page

        @isset($resources)
            @if($resources == 'template_resources')
                templateApplySelectedResources = 'templateOnly';
            @elseif($resources == 'event_resources')
                templateApplySelectedResources = 'existingOnly';
            @elseif($resources == 'merge_resources')
                templateApplySelectedResources = 'templateAndEvent';
            @endif
        @endisset

        //TODO: (mitcks) leaving this here and commented out for right now until this is better tested
        //if the url is templateApply, then they are editing and selecting which values they want to use, so ignore all this
        //if(templateID && lastPathSegment != 'templateApply') {
        if(templateID) {

            //data loaded via ajax from Http/Controllers/CourseInstance/CourseInstanceController.php
            // function findDefaultTemplateValues
            // schedule_request values for classSize, startTime, endTime, simsSpecNeeded override template values (this is done in controller)
            $.ajax({
                url: '/findDefaultTemplateValues/'+templateID+'/'+scheduleRequestID+'/'+selectedDate,
                type: "GET",
                data : {"_token":"{{ csrf_token() }}"},
                dataType: "json",
                success:function(data) {

                    //initializing these here so they can be used for separator lines after the loop through
                    // template, if values exist in template they are updated there
                    var setupTime = {{$setupTime}};
                    var tearDownTime = {{$teardownTime}};

                    //populate fields from template (unless they came from templateApply or fromRequest)
                    if(data.templateOptions && lastPathSegment != 'templateApply' && lastPathSegment != 'fromRequest') {

                        $.each(data.templateOptions, function (key, value) {

                            if(key == 'abbrv')
                            {
                                $('#abbrv').val(value);
                            }

                            if(key == 'numParticipants')
                            {
                                $('#class_size').val(value);
                            }

                            if(key == 'startTime')
                            {
                                // Extract Start Time and Format for HTML
                                let hour = value.slice(0,2);
                                let hour_num = Number(hour);

                                let min = value.slice(3,5);
                                let min_num = Number(min) / 60;

                                // update html text
                                var time_str = (hour_num < 12) ? (hour + ':' + min + ' AM') : ((hour_num == 12) ? (hour + ':' + min + ' PM' ) : ((hour - 12) + ':' + min + ' PM'));

                                $('.slider-time').html(time_str);

                                // Change Slider Start Time
                                let time = parseFloat(hour_num + min_num) * 60;
                                $slider.slider("values", 0, time);

                                // Set Form Field
                                $("input[name='start_time']").val(moment(hour + ':' + min, 'LT').format('HH:mm'));

                                // This Stores slider value if date is changed
                                slider_values[0] = time;

                                slider_values = $slider.slider('option', 'values');

                            }

                            if(key == 'endTime')
                            {
                                // Extract End Time and Format for HTML
                                let hour = value.slice(0,2);
                                let hour_num = Number(hour);

                                let min = value.slice(3,5);
                                let min_num = Number(min) / 60;

                                // update html text
                                var time_str = (hour_num < 12) ? (hour + ':' + min + ' AM') : ((hour_num == 12) ? (hour + ':' + min + ' PM' ) : ((hour - 12) + ':' + min + ' PM'));

                                $('.slider-time2').html(time_str);

                                // Change Slider End Time
                                let time = parseFloat(hour_num + min_num) * 60;
                                $slider.slider("values", 1, time);

                                // Set Form Field
                                $("input[name='end_time']").val(moment(hour + ':' + min, 'LT').format('HH:mm'));

                                // This Stores slider value if date is changed
                                slider_values[1] = time;
                                slider_values = $slider.slider('option', 'values');
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

                            if(key == 'instructorReport')
                            {
                                $('#instructor_report').val(Math.abs( value));
                                if(value < 0)
                                {
                                    $('#instructor_report_BA').val('B');
                                }
                                else if(value == 0)
                                {
                                    $('#instructor_report_BA').val('B');
                                }
                                else
                                {
                                    $('#instructor_report_BA').val('A');
                                }
                            }

                            if(key == 'instructorLeave')
                            {
                                $('#instructor_leave').val(Math.abs( value));
                                if(value < 0)
                                {
                                    $('#instructor_leave_BA').val('B');
                                }
                                else
                                {
                                    $('#instructor_leave_BA').val('A');
                                }
                            }

                            if(key == 'color')
                            {
                                $("#html_color").spectrum("set", value);
                            }

                            if(key == 'simsSpecNeeded')
                            {
                                if(value == 1)
                                {
                                    $("#sims_spec_needed").prop("checked", true);
                                }
                                else
                                {
                                    $("#sims_spec_needed").prop("checked", false);
                                }
                            }

                            if(key == 'specialRequirements')
                            {
                                if(value == 1)
                                {
                                    $("#special_requirements").prop("checked", true);
                                }
                                else
                                {
                                    $("#special_requirements").prop("checked", false);
                                }
                            }

                            if(key == 'initialMeetingRoom')
                            {
                                $('#initial_meeting_room').val(value);
                            }

                            if(key == 'publicComments')
                            {
                                $('#public_comments').val(value);
                            }

                            //mitcks 2020-04-06: having issues trying to set internal comments here, setting the text area does not work
                            // and trying to use .setContent on the tinyMCE editor does not work because the editor isn't loaded
                            // yet when this code is executed.  Moved this section into the $document.ready above
                            //if(key == 'internalComments')
                            //{
                            // }

                            if(key == 'startTimeDelta')
                            {
                                startTimeDelta = value;
                            }

                            if(key == 'endTimeDelta')
                            {
                                endTimeDelta = value;
                            }
                        });
                    }

                    //update grid separator lines
                    var gridDate = moment($('#selectDate').val()).format("Y-MM-DD");

                    var hours1 = Math.floor(slider_values[0] / 60);
                    var minutes1 = slider_values[0] - (hours1 * 60);
                    hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
                    minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();
                    var line1 = gridDate + 'T' + hours1 + ':' +  minutes1 + ':00';

                    var hours2 = Math.floor(slider_values[1] / 60);
                    var minutes2 = slider_values[1] - (hours2 * 60);
                    hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
                    minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
                    var line2 = gridDate + 'T' + hours2 + ':' +  minutes2 + ':00';

                    //convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
                    var setupSeconds = setupTime*60;
                    var setupLine = moment(hours1 + ':' +  minutes1 + ':00', "HH:mm:ss").subtract(setupSeconds, 'seconds').format("HH:mm:ss");
                    var teardownSeconds = tearDownTime*60;
                    var teardownLine = moment(hours2 + ':' +  minutes2 + ':00', "HH:mm:ss").add(teardownSeconds, 'seconds').format("HH:mm:ss");

                    dp.update({separators: [{color:"blue", location: gridDate + "T" + setupLine}, {color:"blue", location: gridDate + "T" + teardownLine}, {color: 'red', location: line1}, {color: 'red', location: line2}]});

                    //alert('start delta'+ startTimeDelta);
                    //alert('end delta'+ endTimeDelta);

                    // recalc overlaps, prevents counting duplicate overlaps if template was changed
                    overlapCounter = overlapCounter - curTemplateOverlaps;

                    // reset current template overlap counter to zero
                    curTemplateOverlaps = 0;

                    // Events for a given date
                    dp.events.list = dp.events.list.filter(function(obj) {
                        return obj.template_event != true;
                    });

                    //  arr to store new events
                    var grid_events = dp.events.list;

                    // clear out out existing values if a new template is selected
                    if(lastPathSegment != 'templateApply' || templateApplySelectedResources == 'templateOnly')
                    {
                        // console.log('clearing out existing resources');
                        stored_events = stored_events.filter(function(obj) {
                            return obj.template_event != true;
                        });
                    }

                    if(lastPathSegment != 'templateApply' || templateApplySelectedResources != 'existingOnly') {
                        // Get Resources associated with template
                        var resources = data.templateOptions.resources;

                        for (var i = 0; i < resources.length; i++) {

                            resources[i].areas = [];

                            // Bar Color
                            let color = $('#html_color').val();

                            // Setup
                            resources[i].areas.push({
                                left: 0,
                                w: ((resources[i].setup_time / dp.cellDuration) * dp.cellWidth),
                                style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(color, ", ").concat(color, ", 2.5px, ").concat(invert(color), " 2.5px, ").concat(invert(color), " 5px);"),
                            });

                            // Build Start Time
                            // mitcks 2020-04-06: startTimeDelta added here to shift grid resources to reflect schedule request start time (if it varies from template)
                            let startTime = new DayPilot.Date(gridDateForTemplate + 'T' + resources[i].start_time).addMinutes(-Math.abs(resources[i].setup_time)).addMinutes(startTimeDelta);

                            // teardown
                            resources[i].areas.push({
                                right: 0,
                                w: ((resources[i].teardown_time / dp.cellDuration) * dp.cellWidth),
                                style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(color, ", ").concat(color, ", 2.5px, ").concat(invert(color), " 2.5px, ").concat(invert(color), " 5px);"),
                            });

                            // Build Event End Time
                            // mitcks 2020-04-06: endTimeDelta added here to shift grid resources to reflect schedule request end time (if it varies from template)
                            let endTime = new DayPilot.Date(gridDateForTemplate + 'T' + resources[i].end_time).addMinutes(resources[i].teardown_time).addMinutes(endTimeDelta);

                            //build text string to include IMR if applicable
                            var text = $("#course_id option:selected").text();
                            if(resources[i].isIMR == 1)
                            {
                                text = text + " (IMR)";
                            }

                            // Construct New Event For Grid
                            var e = {
                                template_resource_id: resources[i].id,
                                flag: true, // Creating a flag to remove these from events arr when picking a new template
                                start: startTime,
                                end: endTime,
                                non_existing: true,
                                template_event: true,
                                isIMR: resources[i].isIMR,
                                setup: resources[i].setup_time,
                                teardown: resources[i].teardown_time,
                                id: DayPilot.guid(),
                                resource: resources[i].resource_id.toString(),
                                original_resource: resources[i].resource_id.toString(),
                                text: text,
                                backColor: 'rgb(97, 235, 52)',
                                barColor: $('#html_color').val(),
                                areas: resources[i].areas, // renders our color
                                resource_identifier_type: resources[i].resource_identifier_type,
                            };

                            // Get Grid resource Event is attempting to add to
                            var matchingResource = grid_events.filter(function (obj) {
                                return obj.resource == e.resource;
                            });

                            if (matchingResource.length > 0) {
                                for (var j = 0; j < matchingResource.length; j++) {

                                    var main_overlap = DayPilot.Util.overlaps(matchingResource[j].start, matchingResource[j].end, e.start, e.end);
                                    if (main_overlap) {
                                        overlapCounter++;
                                        curTemplateOverlaps++;
                                        e.backColor = 'rgb(219, 92, 92)';
                                        // dp.message('Conflicts Below are Red');

                                        {{--if (e.resource_identifier_type === 2) {--}}

                                        {{--    $.ajax({--}}
                                        {{--        url: "/courseInstance/getResourceByCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_category_id, "/").concat(matchingResource[j].location_id),--}}
                                        {{--        type: "GET",--}}
                                        {{--        data: {"_token": "{{ csrf_token() }}"},--}}
                                        {{--        dataType: "json",--}}
                                        {{--        // async: false,--}}
                                        {{--        success: function (res) {--}}
                                        {{--            var category_matches = res.resourceList;--}}

                                        {{--            for (let x = 0; x < category_matches.length; x++) {--}}

                                        {{--                let id = category_matches[x].id.toString();--}}
                                        {{--                let category_matches_events = dp.rows.find(id).events.all();--}}

                                        {{--                if (category_matches_events.length > 0) {--}}

                                        {{--                    for (let y = 0; y < category_matches_events.length; y++) {--}}
                                        {{--                        let category_overlap = DayPilot.Util.overlaps(category_matches_events[y].data.start, category_matches_events[y].data.end, e.start, e.end);--}}
                                        {{--                        if (category_overlap) {--}}
                                        {{--                            // only count if this isn't this first (same) resource, prevents overlapCounter from getting iincrmented twice--}}
                                        {{--                            if (y != 0) {--}}
                                        {{--                                overlapCounter++;--}}
                                        {{--                                curTemplateOverlaps++;--}}
                                        {{--                            }--}}
                                        {{--                            break;--}}
                                        {{--                        } else {--}}
                                        {{--                            if (y == category_matches_events.length - 1) {--}}
                                        {{--                                e.resource = id;--}}
                                        {{--                                e.backColor = 'rgb(97, 235, 52)';--}}
                                        {{--                                overlapCounter--;--}}
                                        {{--                                curTemplateOverlaps--;--}}
                                        {{--                                dp.update();--}}
                                        {{--                            }--}}
                                        {{--                        }--}}
                                        {{--                    }--}}
                                        {{--                } else {--}}
                                        {{--                    e.resource = id;--}}
                                        {{--                    e.backColor = 'rgb(97, 235, 52)';--}}
                                        {{--                    overlapCounter--;--}}
                                        {{--                    curTemplateOverlaps--;--}}
                                        {{--                    dp.update();--}}
                                        {{--                    break;--}}
                                        {{--                }--}}

                                        {{--            }--}}
                                        {{--        },--}}
                                        {{--        error: function (jqXHR, textStatus, errorThrown) {--}}
                                        {{--            console.log(textStatus, errorThrown);--}}
                                        {{--        }--}}
                                        {{--    });--}}
                                        {{--} else if (e.resource_identifier_type === 3) {--}}

                                        {{--    $.ajax({--}}
                                        {{--        url: "/courseInstance/getResourceBySubCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_sub_category_id, "/").concat(matchingResource[j].location_id),--}}
                                        {{--        type: "GET",--}}
                                        {{--        data: {"_token": "{{ csrf_token() }}"},--}}
                                        {{--        dataType: "json",--}}
                                        {{--        // async: false,--}}
                                        {{--        success: function (res) {--}}

                                        {{--            var subcategory_matches = res.resourceList;--}}

                                        {{--            for (let x = 0; x < subcategory_matches.length; x++) {--}}

                                        {{--                let id = subcategory_matches[x].id.toString();--}}
                                        {{--                let subcategory_matches_events = dp.rows.find(id).events.all();--}}

                                        {{--                if (subcategory_matches_events.length > 0) {--}}

                                        {{--                    for (let y = 0; y < subcategory_matches_events.length; y++) {--}}
                                        {{--                        let subcategory_overlap = DayPilot.Util.overlaps(subcategory_matches_events[y].data.start, subcategory_matches_events[y].data.end, e.start, e.end);--}}
                                        {{--                        if (subcategory_overlap) {--}}
                                        {{--                            // only count if this isn't this first (same) resource, prevents overlapCounter from getting iincrmented twice--}}
                                        {{--                            if (y != 0) {--}}
                                        {{--                                overlapCounter++;--}}
                                        {{--                                curTemplateOverlaps++;--}}
                                        {{--                            }--}}
                                        {{--                            break;--}}
                                        {{--                        } else {--}}
                                        {{--                            if (y == subcategory_matches_events.length - 1) {--}}
                                        {{--                                e.resource = id;--}}
                                        {{--                                e.backColor = 'rgb(97, 235, 52)';--}}
                                        {{--                                overlapCounter--;--}}
                                        {{--                                curTemplateOverlaps--;--}}
                                        {{--                                dp.update();--}}
                                        {{--                            }--}}
                                        {{--                        }--}}
                                        {{--                    }--}}
                                        {{--                } else {--}}
                                        {{--                    e.resource = id;--}}
                                        {{--                    e.backColor = 'rgb(97, 235, 52)';--}}
                                        {{--                    overlapCounter--;--}}
                                        {{--                    curTemplateOverlaps--;--}}
                                        {{--                    dp.update();--}}
                                        {{--                    break;--}}
                                        {{--                }--}}
                                        {{--            }--}}
                                        {{--        },--}}
                                        {{--        error: function (jqXHR, textStatus, errorThrown) {--}}
                                        {{--            console.log(textStatus, errorThrown);--}}
                                        {{--        }--}}
                                        {{--    });--}}
                                        {{--}--}}
                                    } else {
                                        if (j == matchingResource.length - 1) {
                                            //console.log('could move here');
                                            overlapCounter--;
                                            curTemplateOverlaps--;
                                        }
                                    }
                                }
                            }
                            // Push to Storage Arrays
                            grid_events.push(e);
                            stored_events.push(e);
                        }
                    }
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
        }
        $("#template_warning").text("Selecting a new template will erase changes made to these resources.");

    });

    // Prevent non-numeric characters being added to input
    $('#class_size').on('keyup', function(e) {
        let input = $(this).val();
        $(this).val(input.replace(/[^0-9]+/g, ''));
    });


    // });

    tinymce.init({
        selector: '#internal_comments',
        forced_root_block : false,
        menubar: false,
        plugins: [
            'advlist autolink lists link charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime table paste code help wordcount'
        ],
        toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor  ' +
        '| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link code | table',
    });

</script>

@section('after-scripts')

    {{-- include the Spectrum color picker CSS, JavaScript files and JQuery. --}}
    {{ Html::style("css/jquery-ui/jquery-ui.css") }}
    {{ Html::style("/css/spectrum/spectrum.css") }}
    {{ Html::style("/css/spectrum/larasim-spectrum.css") }}
    {{ Html::script("/js/spectrum/spectrum.js") }}
    {{ Html::script("/js/spectrum/larasim-spectrum.js") }}

    {{-- include the Spectrum colorpicker code and configuration. --}}
    <script>
        $(".spectrum_html_color").spectrum({

            //Give us the side palette with the squares as well as the full spectrum color picker.
            showPalette: true,

            //put the input box in so we can type in it.
            showInput: true,

            //Allows the input field to be empty, and gives us a big X to clear it.
            allowEmpty: true,

            //Prefer hexidecimal because that is what we started with, allows all other formats though.
            preferredFormat: "rgb",

            //Use the specific palette
            palette : fullPalette,

            //Have a slide to change the RGBA Alpha value (from 0 to 1)
            showAlpha: true,

            //Keep the local storage colors for this object
            localStorageKey : "spectrum.spectrum_html_color",

            //Can't keep more than 24 colors in addition to the standard palette (which is 40) in your localStorage. This is PLENTY!
            maxSelectionSize : 24,

            //Shows the initial color in a box next to the newly selected color for comparison.
            showInitial: true,

            //We will put the trans() call in here for the button text for multiple languages.
            chooseText : "Choose",
            cancelText : "Cancel",

            //If they click outside of the selector, it will not change the value.  If they get confused, they can click out, non-destructively.
            clickoutFiresChange : false,
            clickoutFiresChange : false,

        });
    </script>

@endsection