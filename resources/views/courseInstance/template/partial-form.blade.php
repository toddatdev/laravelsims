
        {{--show course dropdown here only on export view   --}}
        @if (Request::is('*exportTemplate*'))
            <div class="form-group row">
                {{ Form::label('name', trans('labels.template.export_to_course'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
                <div class="col-lg-2">
                    {{ Form::select('course_id', $courses->pluck('abbrv_virtual', 'id'), null, ['class' => 'form-control course-select']) }}
                </div>
            </div>
        @endif

        <div class="form-group row">
            {{ Form::label('name', trans('labels.template.template_name'), ['class' => 'col-lg-2 required control-label text-lg-right']) }}
            <div class="col-lg-3">
                {{ Form::text('name', $templateName, ['class' => 'form-control', 'placeholder' => trans('labels.template.template_name') , 'maxLength' => '50']) }}
            </div>
            {{ Form::label('name', trans('labels.template.event_abbrv'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
            <div class="col-lg-3">
                {{ Form::text('event_abbrv', $eventAbbrv, ['class' => 'form-control', 'placeholder' => trans('labels.template.event_abbrv') , 'maxLength' => '30']) }}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('numParticipants', trans('labels.scheduling.num_participants'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
            <div class="col-lg-1">
                {{Form::number('class_size', old('class_size', $numberParticipants), ['class' => 'form-control', 'placeholder' => '0', "min" => "1"])}}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('public_comments', trans('labels.scheduling.pub_comments'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
            <div class="col-lg-8">
                {{ Form::text('public_comments', $publicComments, ['class' => 'form-control', 'placeholder' => trans('labels.scheduling.pub_comments') , 'maxLength' => '50']) }}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('internal_comments', trans('labels.scheduling.internal_comments'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
            <div class="col-lg-8">
                {{ Form::textarea('internal_comments', $internalComments, ['class' => 'form-control editorhtml', 'placeholder' => trans('labels.scheduling.internal_comments')]) }}
            </div>
        </div>

        {{--mitcks 6/3/19 - hiding this for now, these are set in the background from the radio button in the template resource grid--}}
        {{--<div class="form-inline">--}}
            {{--<div class="form-group col-lg-12 mb-20">--}}
                {{--{{ Form::label('html_color', trans('labels.general.initial_meeting_room'), ['class' => 'col-lg-2 control-label']) }}--}}
                {{--{{ Form::select('initial_meeting_room', $roomList, $initialMeetingRoom, ['class' => 'form-control', 'id' => 'initial_meeting_room', 'placeholder' => trans('schedule.addClass.selectRoom')]) }}--}}
                {{--{{ Form::select('initial_meeting_room_type', $resourceTypes, $initialMeetingRoomType, ['class' => 'form-control']) }}--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--@include('courseInstance.partial-setup-teardown-time')--}}
        <div class="form-group row" style="padding-bottom:-10px;">
            {{ Form::label('times', trans('schedule.addClass.times'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
            <div class="col-lg-10">
                <div class="form-group row">
                    <div class="col-sm-2">
                        {{ Form::label('setup_time', trans('schedule.addClass.setup')) }}
                        {{ Form::select('setup_time', Session::get('minutes'), $defaultSetupTime, ['class' => 'form-control', 'id' => 'setup_time']) }}
                    </div>
                    <div class="col-sm-5">
                        {{ Form::label('range', trans('labels.scheduling.event_time')) }}
                        @include('courseInstance.template.partial-slider_time')
                    </div>
                    <div class="col-sm-2">
                        {{ Form::label('teardown_time', trans('schedule.addClass.teardown')) }}
                        {{ Form::select('teardown_time', Session::get('minutes'), $defaultTeardownTime, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-inline">
            <div class="form-group col-lg-12 mb-20">
                {{ Form::label('schedule.addClass.InstructorReport', trans('schedule.addClass.InstructorReport'), ['class' => 'col-lg-2 control-label']) }}
                @if($instructorReport > 0)
                    {{ Form::select('instructor_report', Session::get('minutes'), $instructorReport, ['class' => 'form-control']) }}
                    {{ Form::select('instructor_report_BA', ['B' => 'Before Start Time', 'A' => 'After Start Time'], 'A', ['class' => 'form-control']) }}
                @else
                    <?php
                    $instructorReport = abs($instructorReport);
                    ?>
                    {{ Form::select('instructor_report', Session::get('minutes'), $instructorReport, ['class' => 'form-control']) }}
                    {{ Form::select('instructor_report_BA', ['B' => 'Before Start Time', 'A' => 'After Start Time'], 'B', ['class' => 'form-control']) }}
                @endif

                <span>&nbsp;&nbsp;&nbsp;</span>

                {{ Form::label('schedule.addClass.InstructorLeave', trans('schedule.addClass.InstructorLeave'), ['class' => 'control-label']) }}
                <span>&nbsp;</span>
                @if($instructorLeave >= 0)
                    {{ Form::select('instructor_leave', Session::get('minutes'), $instructorLeave, ['class' => 'form-control']) }}
                    {{ Form::select('instructor_leave_BA', ['B' => 'Before End Time', 'A' => 'After End Time'], 'A', ['class' => 'form-control']) }}
                @else
                    <?php
                    $instructorLeave = abs($instructorLeave);
                    ?>
                    {{ Form::select('instructor_leave', Session::get('minutes'), $instructorLeave, ['class' => 'form-control']) }}
                    {{ Form::select('instructor_leave_BA', ['B' => 'Before End Time', 'A' => 'After End Time'], 'B', ['class' => 'form-control']) }}
                @endif
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('html_color', trans('labels.general.color'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
            <div class="col-lg-1">
                {{ Form::input('html_color', 'html_color', $color, ['class' => 'spectrum_html_color', ]) }}
                <span id='spectrum-text' class='palette-label'></span>
            </div>

            <div class="col-lg-2">
                @if ($simsSpecialistNeeded == 1)
                    {{ Form::checkbox('sims_spec_needed', 'checked', true, ['class' => 'mt-10']) }} <strong>{{ trans('labels.scheduling.sim_spec') }}</strong>
                @else
                    {{ Form::checkbox('sims_spec_needed', 'notchecked', false, ['class' => 'mt-10']) }} <strong>{{ trans('labels.scheduling.sim_spec') }}</strong>
                @endif
            </div>

            <div class="col-lg-3">
                @if ($specialRequirements == 1)
                    {{ Form::checkbox('special_requirements', 'checked', true, ['class' => 'mt-10']) }} <strong>{{ trans('labels.scheduling.special_requirements') }}</strong>
                @else
                    {{ Form::checkbox('special_requirements', 'notchecked', false, ['class' => 'mt-10']) }} <strong>{{ trans('labels.scheduling.special_requirements') }}</strong>
                @endif
            </div>
        </div>

        {{--For all views except Export Template you need this hidden field to pass course_id back into request object--}}
        @if (!Request::is('*exportTemplate*'))
            {{ Form::hidden('course_id', $courseID) }}
        @endif


        {{--This rownum field is incremented when template resource rows are added to grid at bottom and used after submission to know how many times to loop--}}
        @php
            $rownum = 0;
        @endphp

        <h4>{{ trans('labels.resources.resources') }} </h4>

            <table id="tableResources" class="table table order-list table-striped">
                <thead style="font-weight: bold; background-color: lightgray">
                <tr class="font-weight-bold header">
                    <td class="font-weight-bold">{{ trans('labels.resources.resource') }}</td>
                    <td>{{ trans('labels.resources.type') }}</td>
                    <td align="center" nowrap>IMR
                        <span class="simptip-position-top simptip-smooth" data-tooltip="{{ trans('labels.general.initial_meeting_room') }}">
                            <i class="text-info fas fa-question-circle" data-placement="top"></i>
                        </span>
                    </td>
                    <td>{{ trans('labels.general.setup') }}</td>
                    <td>{{ trans('labels.general.start_time') }}</td>
                    <td>{{ trans('labels.general.end_time') }}</td>
                    <td>{{ trans('labels.general.teardown') }}</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>

                {{--Creating New Template from Event or from "Scratch"--}}
                @isset($event)
                    {{--if not old then get from database--}}
                    @if(old('resource_count', null) == null)
                        @foreach($event->eventResources as $rownum => $eventResource)
                            <tr class="{{ $eventResource->resources->resource_type_id }}">
                                <td>
                                    {{ Form::select($rownum .'_resource_id', $resourceList, $eventResource->resource_id, ['class' => 'form-control selectResource', 'id' => $rownum .'_resource_id']) }}
                                </td>
                                <td>
                                    @inject('resourceTypes', 'App\Http\Controllers\CourseInstance\TemplateController')
                                    {{ Form::select($rownum .'_resource_type', $resourceTypes->getResourceTypes($eventResource->resource_id), null, ['class' => 'form-control', 'id' => $rownum .'_resource_type']) }}
                                </td>
                                <td align="center">
                                    @if($eventResource->resource_id === $initialMeetingRoom)
                                        {{--mitcks 6/26/19 - not using laravel form collective for radio button because it would not hold value after validation--}}
                                        {{--{{ Form::radio('radio_is_imr', $rownum, true) }}--}}
                                        <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $rownum }}"  {{ old('radio_is_imr')=="$rownum" ? 'checked='.'"'.'checked'.'"' : 'checked' }} />
                                    @else
                                        {{--{{ Form::radio('radio_is_imr', $rownum, false) }}--}}
                                        <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $rownum }}"  {{ old('radio_is_imr')=="$rownum" ? 'checked='.'"'.'checked'.'"' : '' }} />
                                    @endif
                                </td>
                                <td>
                                    {{ Form::select($rownum .'_setup_time', Session::get('minutes'), $eventResource->setup_time, ['class' => 'form-control', 'id' => $rownum ."_setup_time"]) }}
                                </td>
                                <td>
                                    {{ Form::input($rownum .'_start_time', $rownum .'_start_time', \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $eventResource->start_time)->format('g:i A'), ['class' => 'form-control timepicker' ]) }}
                                </td>
                                <td>
                                    {{ Form::input($rownum .'_end_time', $rownum .'_end_time', \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $eventResource->end_time)->format('g:i A'), ['class' => 'form-control timepicker' ]) }}
                                </td>

                                <td>
                                    {{ Form::select($rownum .'_teardown_time', Session::get('minutes'), $eventResource->teardown_time, ['class' => 'form-control', 'id' => $rownum ."_teardown_time"]) }}
                                </td>
                                <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="{{ trans('buttons.general.crud.delete') }}"></td>
                            </tr>
                        @endforeach
                    @else
                        {{--After validation error, loop through old to recreate selected rows here--}}
                        @include('courseInstance.template.partial-resources-after-validation')
                    @endif
                @else
                    {{--add a blank row for create from scratch --}}
                    @unless(isset($template))
                        {{--first form submission so need blank row--}}
                        @if(old('resource_count', null) == null)
                            <tr>
                                <td>
                                    {{ Form::select($rownum .'_resource_id', $resourceList, null, ['class' => 'form-control selectResource', 'id' => $rownum .'_resource_id']) }}
                                </td>
                                <td>
                                    {{--{{ Form::select($rownum .'_resource_type', $resourceTypes, null, ['class' => 'form-control', 'id' => $rownum .'_resource_type']) }}--}}
                                    @inject('resourceTypes', 'App\Http\Controllers\CourseInstance\TemplateController')

                                    <?php $firstResourceId = array_key_first($resourceList) ?>
                                    {{ Form::select($rownum .'_resource_type', $resourceTypes->getResourceTypes($firstResourceId), null, ['class' => 'form-control', 'id' => $rownum .'_resource_type']) }}
                                </td>
                                <td align="center">
                                    {{--mitcks 6/26/19 - not using laravel form collective for radio button because it would not hold value after validation--}}
                                    @if(old('radio_is_imr', null) != null)
                                        <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $rownum }}"  {{ old('radio_is_imr')=="$rownum" ? 'checked='.'"'.'checked'.'"' : '' }} />
                                    @else
                                        <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $rownum }}" checked />
                                    @endif
                                </td>
                                <td>
                                    {{ Form::select($rownum .'_setup_time', Session::get('minutes'), null, ['class' => 'form-control', 'id' => $rownum ."_setup_time"]) }}
                                </td>
                                <td>
                                    {{ Form::input($rownum .'_start_time', $rownum .'_start_time', \Carbon\Carbon::createFromFormat('H:i', $startTime)->format('g:i A'), ['class' => 'form-control timepicker' ]) }}
                                </td>
                                <td>
                                    {{ Form::input($rownum .'_end_time', $rownum .'_end_time', \Carbon\Carbon::createFromFormat('H:i', $endTime)->format('g:i A'), ['class' => 'form-control timepicker' ]) }}
                                </td>
                                <td>
                                    {{ Form::select($rownum .'_teardown_time', Session::get('minutes'), null, ['class' => 'form-control', 'id' => $rownum ."_teardown_time"])}}
                                </td>
                                <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="{{ trans('buttons.general.crud.delete') }}"></td>
                            </tr>
                        @else
                            {{--After validation error, loop through old to recreate selected rows here--}}
                            @include('courseInstance.template.partial-resources-after-validation')
                        @endif

                    @endunless
                @endisset

                {{--Editing Existing Template--}}
                @isset($template)
                    {{--if not old then get from database--}}
                    @if(old('resource_count', null) == null)
                        @foreach($courseTemplateResources as $rownum => $templateResource)
                            <tr>
                                <td>
                                    {{ Form::hidden($rownum .'_course_template_resource_id', $templateResource->id) }}
                                    {{ Form::hidden($templateResource->id .'_course_template_resource_id', $templateResource->id) }}
                                    {{ Form::select($rownum .'_resource_id', $resourceList, $templateResource->resource_id, ['class' => 'form-control selectResource', 'id' => $rownum .'_resource_id']) }}
                                </td>
                                <td>
                                    {{--{{ Form::select($rownum .'_resource_type', $resourceTypes, $templateResource->resource_identifier_type, ['class' => 'form-control', 'id' => $rownum .'_resource_type']) }}--}}
                                    @inject('resourceTypes', 'App\Http\Controllers\CourseInstance\TemplateController')
                                    {{ Form::select($rownum .'_resource_type', $resourceTypes->getResourceTypes($templateResource->resource_id), $templateResource->resource_identifier_type, ['class' => 'form-control', 'id' => $rownum .'_resource_type']) }}
                                </td>
                                <td align="center">
                                    @if($templateResource->isIMR == 1)
                                        {{--mitcks 6/26/19 - not using laravel form collective for radio button because it would not hold value after validation--}}
                                        {{--{{ Form::radio('radio_is_imr', $rownum, true) }}--}}
                                        <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $rownum }}"  {{ old('radio_is_imr')=="$rownum" ? 'checked='.'"'.'checked'.'"' : 'checked' }} />
                                    @else
                                        {{--{{ Form::radio('radio_is_imr', $rownum, false) }}--}}
                                        <input type="radio" id="radio_is_imr" name="radio_is_imr" value="{{ $rownum }}"  {{ old('radio_is_imr')=="$rownum" ? 'checked='.'"'.'checked'.'"' : '' }} />
                                    @endif
                                </td>
                                <td>
                                    {{ Form::select($rownum .'_setup_time', Session::get('minutes'), $templateResource->setup_time, ['class' => 'form-control', 'id' => $rownum ."_setup_time"]) }}
                                </td>
                                <td>
                                    {{ Form::input($rownum .'_start_time', $rownum .'_start_time', \Carbon\Carbon::createFromFormat('H:i:s', $templateResource->start_time)->format('g:i A'), ['class' => 'form-control timepicker' ]) }}
                                </td>
                                <td>
                                    {{ Form::input($rownum .'_end_time', $rownum .'_end_time', \Carbon\Carbon::createFromFormat('H:i:s', $templateResource->end_time)->format('g:i A'), ['class' => 'form-control timepicker' ]) }}
                                </td>

                                <td>
                                    {{ Form::select($rownum .'_teardown_time', Session::get('minutes'), $templateResource->teardown_time, ['class' => 'form-control', 'id' => $rownum ."_teardown_time"]) }}
                                </td>
                                <td>
                                    <input type="button" class="ibtnDel btn btn-md btn-danger "  value="{{ trans('buttons.general.crud.delete') }}">
                                </td>
                            </tr>
                        @endforeach
                    @else
                        {{--After validation error, loop through old to recreate selected rows here--}}
                        @include('courseInstance.template.partial-resources-after-validation')
                    @endif

                @endisset


                {{ Form::hidden('resource_count', $rownum, ['id' => 'resource_count']) }}

                </tbody>
                <tfoot>
                <tr class="footer">
                    <td colspan="8" class="text-right">
                        <input type="button" class="btn btn-info add-row" value="{{ trans('buttons.general.add_row') }}">
                    </td>
                </tr>
                <tr>
                </tr>
                </tfoot>
            </table>


@section('after-scripts')

    {{--Tiny MCE Editor for Course Description--}}
    <script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea',
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

    {{--Time Picker--}}
    {{ Html::style("css/jquery.timepicker.css") }}
    {{ Html::script("js/jquery.timepicker.js") }}

    <script>
        $(document).ready(function(){

            //these only should execute when creating a new template from scratch
            if (window.location.href.indexOf("create-from-scratch") > -1) {
                $("#setup_time").change(function(){
                    var selected_setup_time = $("#setup_time").val();
                    $("#0_setup_time").val(selected_setup_time);
                });

                $("#teardown_time").change(function(){
                    var selected_teardown_time = $("#teardown_time").val();
                    $("#0_teardown_time").val(selected_teardown_time);
                });
            }

            $("body").on("change", ".selectResource", function() {
                
                var resourceID = $(this).val();

                var row = $(this).attr('id');
                var n = row.indexOf("_");
                var i = row.substring(0, n);

                //alert(row + ' ' + i );

                if(resourceID) {

                    $.ajax({
                        url: '/findDefaultValuesWithResourceID/'+resourceID,
                        type: "GET",
                        data : {"_token":"{{ csrf_token() }}"},
                        dataType: "json",
                        success:function(data) {
                            console.log(data.resourceTypes);
                            console.log(data.courseOptions);

                            //populate template dropdown
                            if(data.resourceTypes){

                                $('#' + i + '_resource_type').empty();
                                $('#' + i +'_resource_type').focus;
                                $.each(data.resourceTypes, function(key, value){
                                    $('select[name="' + i + '_resource_type"]').append('<option value="'+ key +'">' + value+ '</option>');
                                });
                            }else{
                                $(this).empty();
                            }

                        }
                    });

                }
                else{
                    //no resource ID was passed
                    $('.selectResource').empty();
                }

            });

            var counter = $('#tableResources tr').length - 3;

                $(".add-row").click(function(){

                //alert($('input:hidden[name=end_time]').val());

                //alert(moment($('input:hidden[name=end_time]').val(), 'HH:mm').format('h:mm A'));

                $('#resource_count').val(counter);

                var markup = "";

                markup += '<tr><td>';
                markup += '<select class="form-control selectResource" id="'+counter+'_resource_id" name="'+counter+'_resource_id">';

                @foreach($resourceList as $key => $resource)
                    markup += '<option value="{{ $key }}">{{ $resource }}</option>';
                @endforeach

                markup += '</select></td>';

                markup += '<td><select class="form-control" id="'+counter+'_resource_type" name="'+counter+'_resource_type">';

                @inject('resourceTypes', 'App\Http\Controllers\CourseInstance\TemplateController')
                <?php $firstResourceId = array_key_first($resourceList) ?>
                @foreach($resourceTypes->getResourceTypes($firstResourceId) as $id => $type)
                    markup += '<option value="{{ $id }}">{{ $type }}</option>';
                @endforeach

                markup += '</select></td>';

                markup += '<td align="center">';
                markup += '<input name="radio_is_imr" id="radio_is_imr" type="radio" ';
                markup += 'value="'+ counter + '"';
                markup += '{{ old('radio_is_imr')=="' + counter + '" ? 'checked='.'"'.'checked'.'"' : '' }}';
                markup += '/></td>';

                markup += '<td><select class="form-control" id="'+counter+'_setup_time" name="'+counter+'_setup_time">';
                @foreach(Session::get('minutes') as $key => $minutes)
                    markup += '<option '
                    if ($("#setup_time").val() === "{{ $key }}"){
                        markup += ' selected '
                    }
                    markup += ' value="{{ $key }}">{{ $minutes }}</option>';
                @endforeach
                markup += '</select></td>';

                markup += '<td><input value="' + moment($('input:hidden[name=start_time]').val(), 'HH:mm').format('h:mm A') + '" id="' +counter+ '_start_time" type="text" class="form-control timepicker" name="' + counter + '_start_time"/></td>';
                markup += '<td><input value="' + moment($('input:hidden[name=end_time]').val(), 'HH:mm').format('h:mm A') + '" id="' +counter+ '_end_time" type="text" class="form-control timepicker" name="' + counter + '_end_time"/></td>';

                markup += '<td><select class="form-control" id="'+counter+'_teardown_time" name="'+counter+'_teardown_time">';
                @foreach(Session::get('minutes') as $key => $minutes)
                    markup += '<option '
                    if ($("#teardown_time").val() === "{{ $key }}"){
                        markup += ' selected '
                    }
                markup += 'value="{{ $key }}">{{ $minutes }}</option>';
                @endforeach
                markup += '</select></td>';

                markup += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="{{ trans('buttons.general.crud.delete') }}"></td>';
                markup += '</tr>';

                $("table tbody").append(markup);

                counter++;

                // adds timepicker to newly added rows
                initTimePicker();

            });


            $("table.order-list").on("click", ".ibtnDel", function (event) {
                $(this).closest("tr").remove();
                counter -= 1
            });


            // adds timepicker to rows on load
            initTimePicker();

        });

        function initTimePicker() {
            //Time Picker
            $('input.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 15,
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        }
        
    </script>

    {{--Time Slider--}}
    {{ Html::style("css/nouislider/nouislider.css") }}
    {{ Html::script("/js/nouislider/nouislider.js") }}
    {{ Html::script("/js/moment-with-locales.js") }}

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







