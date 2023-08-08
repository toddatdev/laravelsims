@extends('frontend.layouts.app')

@section ('title', trans('labels.scheduling.scheduling') . ' | ' . trans('labels.scheduling.another_event'))

@section('content')
<style>
    /*added this style to prevent table from stretching to 100% width*/
    .table-nonfluid {
        width: auto !important;
    }

    /*this is used to truncate and add ellipses to notes*/
    td.overflow div {
        /*background-color: white;*/
        width: 250px;
        height: 25px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
</style>

{{-- Error Msg Container --}}
<div class="error-storage" style="display: none";></div>

<section class="content">
    {{ Form::open(['url' => '/courseInstance/main/templateApply', 'id'=>'compareTemplateForm', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">{{ trans('labels.scheduling.template_compare') }} {{ Form::label('lblHeaderText', '', ['id'=>'lblHeaderText']) }}</h3>
                </div>

                <div class="card-body">
                    <p>{{ trans('labels.scheduling.template_values') }}</p>
                    <div class="table-responsive">
                        <table class="table table-hover table-responsive">
                            <thead>
                                <th></th>
                                <th colspan="2">Current Values</th>
                                <th colspan="2">{{$template->name}} {{trans('labels.scheduling.template')}}</th>
                                <th colspan="2"></th>
                            </thead>
                            <tbody>
                                {{--EVENT ABBRV--}}
                                <tr>
                                    <td nowrap>{{ Form::label('eventAbbrv', trans('labels.general.abbrv'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('abbrv', '', false, ['id'=>'current_abbrv']) }}</td>
                                    <td>{{ Form::label('lblCurrentAbbrvText', '', ['id'=>'lblCurrentAbbrvText']) }}</td>

                                    @isset($template->event_abbrv)
                                        <td>{{  Form::radio('abbrv', $template->event_abbrv, true, ['id'=>'template_abbrv']) }}</td>
                                        <td>{{$template->event_abbrv}}</td>
                                    @else
                                        {{--setting value to '' here because otherwise it kept returning the name (abbrv)?--}}
                                        <td>{{  Form::radio('abbrv', '', true) }}</td>
                                        <td>{{trans('labels.general.none')}}</td>
                                    @endisset
                                    <td colspan="2"></td>
                                </tr>

                                {{--NUMBER OF PARTICIPANTS--}}
                                <tr>
                                    <td nowrap>{{ Form::label('numParticipants', trans('labels.scheduling.num_participants'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('numParticipants', null, false, ['id'=>'currentNumParticipants']) }}&nbsp;</td>
                                    <td>{{ Form::label('lblCurrentNumParticipants', '', ['id'=>'lblCurrentNumParticipants']) }}</td>
                                    <td>{{ Form::radio('numParticipants', $template->class_size, true, ['id'=>'templateNumParticipants']) }}</td>
                                    <td>{{$template->class_size}}</td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--SETUP --}}
                                <tr>
                                    <td nowrap>{{ Form::label('setup_time', trans('schedule.addClass.setup'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('setup_time', null, false, ['id'=>'currentSetupTime']) }}&nbsp;</td>
                                    <td>{{ Form::label('lblCurrentSetupTime', '', ['id'=>'lblCurrentSetupTime']) }}</td>
                                    <td>{{ Form::radio('setup_time', $template->setup_time, true, ['id'=>'templateSetupTime']) }}</td>
                                    <td>{{$template->setup_time}}</td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--START TIME --}}
                                <tr>
                                    <td nowrap>{{ Form::label('start_time', trans('schedule.addClass.start'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('start_time', null, false, ['id'=>'currentStartTime']) }}&nbsp;</td>
                                    <td>{{ Form::label('lblCurrentStartTime', '', ['id'=>'lblCurrentStartTime']) }}</td>
                                    <td>{{  Form::radio('start_time', $template->start_time, true) }}</td>
                                    <td>{{\Carbon\Carbon::parse($template->start_time)->format('g:i a')}}</td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--END TIME --}}
                                <tr>
                                    <td nowrap>{{ Form::label('end_time', trans('schedule.addClass.end'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('end_time', null, false, ['id'=>'currentEndTime']) }}&nbsp;</td>
                                    <td>{{ Form::label('lblCurrentEndTime', '', ['id'=>'lblCurrentEndTime']) }}</td>
                                    <td>{{ Form::radio('end_time', $template->end_time, true) }}</td>
                                    <td>{{\Carbon\Carbon::parse($template->end_time)->format('g:i a')}}</td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--TEARDOWN --}}
                                <tr>
                                    <td nowrap>{{ Form::label('teardown_time', trans('schedule.addClass.teardown'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('teardown_time', null, false, ['id'=>'currentTeardownTime']) }}&nbsp;</td>
                                    <td>{{ Form::label('lblCurrentTeardownTime', '', ['id'=>'lblCurrentTeardownTime']) }}</td>
                                    <td>{{ Form::radio('teardown_time', $template->teardown_time, true, ['id'=>'templateTeardownTime']) }}</td>
                                    <td>{{$template->teardown_time}}</td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--INSTRUCTOR REPORT --}}
                                <tr>
                                    <td nowrap>{{ Form::label('instructor_report', trans('schedule.addClass.InstructorReport'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('instructor_report', null, false, ['id'=>'currentInstructorReport']) }}</td>
                                    <td>{{ Form::label('lblCurrentInstructorReport', '', ['id'=>'lblCurrentInstructorReport']) }}</td>
                                    <td>{{ Form::radio('instructor_report', $template->fac_report, true, ['id'=>'templateInstructorReport']) }}</td>
                                    <td>
                                        @if($template->fac_report > 0)
                                            {{$template->fac_report}} {{trans('labels.scheduling.minutes_after')}}
                                        @else
                                            {{abs($template->fac_report)}} {{trans('labels.scheduling.minutes_before')}}
                                        @endif
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--INSTRUCTOR LEAVE --}}
                                <tr>
                                    <td nowrap>{{ Form::label('instructor_leave', trans('schedule.addClass.InstructorLeave'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('instructor_leave', null, false, ['id'=>'currentInstructorLeave']) }}</td>
                                    <td>{{ Form::label('lblCurrentInstructorLeave', '', ['id'=>'lblCurrentInstructorLeave']) }}</td>
                                    <td>{{ Form::radio('instructor_leave', $template->fac_leave, true) }}</td>
                                    <td>
                                        @if($template->fac_leave > 0)
                                            {{$template->fac_leave}} {{trans('labels.scheduling.minutes_after')}}
                                        @else
                                            {{abs($template->fac_leave)}} {{trans('labels.scheduling.minutes_before')}}
                                        @endif
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--COLOR --}}
                                <tr>
                                    <td nowrap>{{ Form::label('html_color', trans('labels.general.color'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('html_color', null, false, ['id'=>'currentHtmlColor']) }}</td>
                                    <td>
                                        {{ Form::input('html_color', 'html_color', null, ['class' => 'spectrum_html_color', 'id' => 'lblHtmlColor']) }}
                                        <span id='spectrum-text' class='palette-label'></span>
                                    </td>
                                    <td>{{ Form::radio('html_color', $template->color, true) }}</td>
                                    <td>
                                        @if(empty($template->color))
                                            {{ Form::input('html_color', 'html_color', null, ['class' => 'spectrum_html_color', 'id' => 'html_color']) }}
                                        @else
                                            {{ Form::input('html_color', 'html_color', $template->color, ['class' => 'spectrum_html_color', 'id' => 'html_color']) }}
                                        @endif
                                        <span id='spectrum-text' class='palette-label'></span>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--SIMS SPECIALIST --}}
                                <tr>
                                    <td nowrap>{{ Form::label('sims_spec_needed', trans('labels.scheduling.sim_spec'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('sims_spec_needed', null, false, ['id'=>'currentSimsSpecNeeded']) }}</td>
                                    <td>{{ Form::label('lblCurrentSimsSpecNeeded', '', ['id'=>'lblCurrentSimsSpecNeeded']) }}</td>
                                    <td>{{ Form::radio('sims_spec_needed', $template->sims_spec_needed, true) }}</td>
                                    <td>
                                        @if ($template->sims_spec_needed == 1)
                                            {{trans('labels.general.yes')}}
                                        @else
                                            {{trans('labels.general.no')}}
                                        @endif
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--SPECIAL REQUIREMENTS --}}
                                <tr>
                                    <td nowrap>{{ Form::label('special_requirements', trans('labels.scheduling.special_requirements'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('special_requirements', null, false, ['id'=>'currentSpecRequirements']) }}</td>
                                    <td>{{ Form::label('lblCurrentSpecRequirements', '', ['id'=>'lblCurrentSpecRequirements']) }}</td>
                                    <td>{{ Form::radio('special_requirements', $template->special_requirements, true) }}</td>
                                    <td>
                                        @if ($template->special_requirements == 1)
                                            {{trans('labels.general.yes')}}
                                        @else
                                            {{trans('labels.general.no')}}
                                        @endif
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                {{--PUBLIC NOTES--}}
                                <tr>
                                    <td nowrap>{{ Form::label('public_comments', trans('labels.event.public_notes'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('public_comments', '', false, ['id'=>'currentPublicComments']) }}</td>
                                    <td class="overflow"><div>{{ Form::label('lblCurrentPublicComments', '', ['id'=>'lblCurrentPublicComments']) }}</div></td>
                                    @isset($template->public_comments)
                                        <td>{{ Form::radio('public_comments', $template->public_comments, true) }}</td>
                                        {{--the overflow class limits to one line and adds ellipsis, requires a div inside the td--}}
                                        <td class="overflow"><div>{!! $template->public_comments !!}</div></td>
                                    @else
                                        {{--setting value to '' here because otherwise it kept returning the name (public_comments)?--}}
                                        <td>{{ Form::radio('public_comments', '', true) }}</td>
                                        <td>{{trans('labels.general.none')}}</td>
                                    @endisset

                                    <td>{{ Form::radio('public_comments', 'mergePublicNotes', false) }}</td>
                                    <td>{{trans('labels.scheduling.pub_notes_merge')}}</td>
                                </tr>
                                {{--INTERNAL NOTES--}}
                                <tr>
                                    <td nowrap>{{ Form::label('internal_comments', trans('labels.event.internal_notes'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('internal_comments', '', false, ['id'=>'currentInternalComments']) }}</td>
                                    <td class="overflow"><div>{{ Form::label('lblCurrentInternalComments', '', ['id'=>'lblCurrentInternalComments']) }}</div></td>

                                    @isset($template->internal_comments)
                                        <td>{{ Form::radio('internal_comments', $template->internal_comments, true) }}</td>
                                        <td class="overflow"><div>{!! $template->internal_comments !!}</div></td>
                                    @else
                                        {{--setting value to '' here because otherwise it kept returning the name (public_comments)?--}}
                                        <td>{{ Form::radio('internal_comments', '', true) }}</td>
                                        <td>{{trans('labels.general.none')}}</td>
                                    @endisset
                                    <td>{{ Form::radio('internal_comments', 'mergeInternalNotes', false) }}</td>
                                    <td>{{trans('labels.scheduling.internal_notes_merge')}}</td>
                                </tr>
                                {{--RESOURCES--}}
                                <tr>
                                    <td nowrap>{{ Form::label('resources', trans('labels.resources.resources'), ['class' => 'col-lg-2 control-label']) }}</td>
                                    <td>{{ Form::radio('resources', 'event_resources', false) }}</td>
                                    <td>{{trans('labels.scheduling.existing_resources')}}
                                        @isset($event)
                                            <p></p>
                                            <p>{{ trans('labels.event.initial_meeting_room') }}: {{$event->DisplayIMR}}</p>
                                            <p>{{ trans('labels.event.event_rooms') }}: {{$event->getResources(1)}}</p>
                                            @if($event->getResources(2) != null)
                                                <p>{{ trans('labels.event.equipment') }}: {{$event->getResources(2)}}</p>
                                            @endif
                                            @if($event->getResources(3) != null)
                                                <p>{{ trans('labels.event.personnel') }}: {{$event->getResources(3)}}</p>
                                            @endif
                                        @endisset
                                    </td>
                                    <td>{{ Form::radio('resources', 'template_resources', true) }}</td>
                                    <td>{{trans('labels.scheduling.template_resources')}}
                                        <p></p>
                                        <p>{{ trans('labels.event.initial_meeting_room') }}: {{$template->DisplayIMR}}</p>
                                        <p>{{ trans('labels.event.event_rooms') }}: {{$template->getResources(1)}}</p>
                                        @if($template->getResources(2) != null)
                                            <p>{{ trans('labels.event.equipment') }}: {{$template->getResources(2)}}</p>
                                        @endif
                                        @if($template->getResources(3) != null)
                                            <p>{{ trans('labels.event.personnel') }}: {{$template->getResources(3)}}</p>
                                        @endif
                                    </td>
                                    <td>{{ Form::radio('resources', 'merge_resources', false) }}</td>
                                    <td>{{trans('labels.scheduling.merge_resources')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{--hidden fields for template_id to pass them back in request--}}
                    {{ Form::hidden('template_id', $template->id, array('id' => 'template_id')) }}
                    {{ Form::hidden('status_type_id', $event->status_type_id, array('id' => 'status_type_id')) }}

                    <div class="float-left">
                        {{ link_to(URL::previous(), trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                    </div><!--pull-left-->
                    <div class="float-right">
                        {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md create-event-submit', 'name'=>'submit', 'value'=>'save', 'id' => 'save_event']) }}
                    </div><!--pull-right-->
                </div><!-- /.card-footer -->
            </div>
        </div>
    </div>
</section>

@endsection

@section('after-scripts')

    {{-- include the Spectrum color picker CSS, JavaScript files and JQuery. --}}
    {{ Html::style("css/jquery-ui/jquery-ui.css") }}
    {{ Html::style("/css/spectrum/spectrum.css") }}
    {{ Html::style("/css/spectrum/larasim-spectrum.css") }}
    {{ Html::script("/js/spectrum/spectrum.js") }}
    {{ Html::script("/js/spectrum/larasim-spectrum.js") }}
    {{-- Moment JS --}}
    {{ Html::script("/js/moment-with-locales.js") }}

    {{-- include the Spectrum colorpicker code and configuration. --}}
    <script>
        $(document).ready(function() {
            // console.log(localStorage['currLoc']);

            $('#lblHeaderText').text(localStorage['headingText']); //sets text for heading (selected course and date)
            $("<input>").attr({
                name: "currLoc",
                id: "currLoc",
                type: "hidden",
                value: localStorage['currLoc']
            }).appendTo("form");
            $("<input>").attr({
                name: "course_id",
                id: "course_id",
                type: "hidden",
                value: localStorage['course_id']
            }).appendTo("form");
            $("<input>").attr({
                name: "initial_meeting_room",
                id: "initial_meeting_room",
                type: "hidden",
                value: localStorage['initial_meeting_room']
            }).appendTo("form");
            $("<input>").attr({
                name: "event_id",
                id: "event_id",
                type: "hidden",
                value: localStorage['event_id']
            }).appendTo("form");
            $("<input>").attr({
                name: "start_date",
                id: "start_date",
                type: "hidden",
                value: localStorage['start_date']
            }).appendTo("form");
            $("<input>").attr({
                name: "current_public_comments",
                id: "current_public_comments",
                type: "hidden",
                value: localStorage['public_comments']
            }).appendTo("form");
            $("<input>").attr({
                name: "current_internal_comments",
                id: "current_internal_comments",
                type: "hidden",
                value: localStorage['internal_comments']
            }).appendTo("form");
            if(localStorage['abbrv'] != '')
            {
                $('#lblCurrentAbbrvText').text(localStorage['abbrv']); //sets current abbrv label
            }
            else
            {
                $('#lblCurrentAbbrvText').text('None'); //sets to none when null
            }
            $('#current_abbrv').val(localStorage['abbrv']); //sets current abbrv radio value
            $('#lblCurrentNumParticipants').text(localStorage['class_size']); //sets current num participants label
            $('#currentNumParticipants').val(localStorage['class_size']); //sets current num participants radio value
            $('#lblCurrentSetupTime').text(localStorage['setup_time']); //sets current setup time label
            $('#currentSetupTime').val(localStorage['setup_time']); //sets current setup time radio value

            $('#lblCurrentStartTime').text(moment(localStorage['start_time'], 'HH:mm').format('h:mm a')); //sets current start time label
            $('#currentStartTime').val(localStorage['start_time']); //sets current setup time radio value

            $('#lblCurrentEndTime').text(moment(localStorage['end_time'], 'HH:mm').format('h:mm a')); //sets current start time label
            $('#currentEndTime').val(localStorage['end_time']); //sets current setup time radio value

            $('#lblCurrentTeardownTime').text(localStorage['teardown_time']); //sets current teardown time label
            $('#currentTeardownTime').val(localStorage['teardown_time']); //sets current teardown time radio value
            if(localStorage['instructor_report_BA'] == 'A')
            {
                $('#lblCurrentInstructorReport').text(localStorage['instructor_report']+ ' {{trans('labels.scheduling.minutes_after')}}'); //sets current instructor report time label
                $('#currentInstructorReport').val(localStorage['instructor_report']); //sets current instructor report time radio value
            }
            else //before, so it's a negative number
            {
                //console.log(0-localStorage['instructor_report']);
                $('#lblCurrentInstructorReport').text(localStorage['instructor_report']+ ' {{trans('labels.scheduling.minutes_before')}}'); //sets current instructor report time label
                $('#currentInstructorReport').val(0-localStorage['instructor_report']); //sets current instructor report time radio value
            }
            if(localStorage['instructor_leave_BA'] == 'A')
            {
                $('#lblCurrentInstructorLeave').text(localStorage['instructor_leave']+ ' {{trans('labels.scheduling.minutes_after')}}'); //sets current instructor leave time label
                $('#currentInstructorLeave').val(localStorage['instructor_leave']); //sets current instructor leave time radio value
            }
            else //before, so it's a negative number
            {
                $('#lblCurrentInstructorLeave').text(localStorage['instructor_leave']+ ' {{trans('labels.scheduling.minutes_before')}}'); //sets current instructor leave time label
                $('#currentInstructorLeave').val(0-localStorage['instructor_leave']); //sets current instructor leave time radio value
            }
            $('#currentHtmlColor').val(localStorage['html_color']); //sets current color
            if(localStorage['html_color'] != '')
            {
                $('#lblHtmlColor').spectrum({
                    color: localStorage['html_color']
                });
            }
            else
            {
                $("#html_color").spectrum("set", null);
            }

            if (localStorage['sims_spec_needed'] == 1)
            {
                $('#lblCurrentSimsSpecNeeded').text('{{trans('labels.general.yes')}}');
            }
            else
            {
                $('#lblCurrentSimsSpecNeeded').text('{{trans('labels.general.no')}}');
            }
            $('#currentSimsSpecNeeded').val(localStorage['sims_spec_needed']); //sets current instructor sims spec needed radio value

            if (localStorage['special_requirements'] == 1)
            {
                $('#lblCurrentSpecRequirements').text('{{trans('labels.general.yes')}}');
            }
            else
            {
                $('#lblCurrentSpecRequirements').text('{{trans('labels.general.no')}}');
            }
            $('#currentSpecRequirements').val(localStorage['special_requirements']); //sets current spec requirements radio value

            if(localStorage['public_comments'] != '')
            {
                $('#lblCurrentPublicComments').text(localStorage['public_comments']);
            }
            else
            {
                $('#lblCurrentPublicComments').text('{{trans('labels.general.none')}}');
            }
            $('#currentPublicComments').val(localStorage['public_comments']); //sets current public comments radio value

            if(localStorage['internal_comments'] != '')
            {
                $('#lblCurrentInternalComments').text(localStorage['internal_comments']);
            }
            else
            {
                $('#lblCurrentInternalComments').text('{{trans('labels.general.none')}}');
            }
            $('#currentInternalComments').val(localStorage['internal_comments']); //sets current public comments radio value


        }); //$(document).ready(function()


        $(".spectrum_html_color").spectrum({

            disabled: true,

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