{{ Html::script("/js/tinymce/tinymce.min.js") }}
{{ Html::script("js/jquery.js") }}

<script type="text/javascript">
    $(function () {
        // For Email Subject
        tinymce.init({
            selector: "#subject",
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo | code',
            statusbar: false,
            inline: false,
            resize: false,
            formats: {
                // Changes the default format for h1 to have a class of heading
                h1: { block: 'h1', classes: 'heading' }
            },
            setup: function (ed) {
                // Makes Subject Input Single Line
                ed.on('keydown', function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
                ed.addButton('dropBar', {
                    type: 'menubutton',
                    title: 'Insert Placeholder',
                    text: 'Insert Placeholder',
                    icon: false,
                    menu: [
                        {
                            text: 'Insert Course Name',
                            onclick: function() {
                                ed.plugins.variables.addVariable('course_name');
                            }
                        },
                        {
                            text : 'Insert Course Abbreviation',
                            onclick: function() {
                                ed.plugins.variables.addVariable('course_abbrv');
                            }
                        },
                        {
                            text: 'Insert User First Name',
                            onclick: function() {
                                ed.plugins.variables.addVariable('first_name');
                            }
                        },
                        {
                            text: 'Insert User Last Name',
                            onclick: function() {
                                ed.plugins.variables.addVariable('last_name');
                            }
                        },
                    ],
                });
            },
            plugins: [
                'variables',
            ],
            variable_mapper: { // Can add more vars to be mapped here
                course_name: '{Course Name}',
                course_abbrv: '{Course Abbreviation}',
                first_name: '{First Name}',
                last_name: '{Last Name}',
            },
        });            

        // For Body text Area
        tinymce.init({
            selector: "textarea",
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo |  formatselect | bold italic underline backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link code',
            statusbar: false,
            setup: function(ed) {   
                // Dropdown toolbar
                ed.addButton('dropBar', {
                    type: 'menubutton',
                    title: 'Insert Var',
                    text: 'Insert Placeholde',
                    icon: false,
                    menu: [ // Add new options from var_mapper here ...
                        {
                            text: 'Insert Course Name',
                            onclick: function() {
                                ed.plugins.variables.addVariable('course_name');
                            }
                        },
                        {
                            text : 'Insert Course Abbreviation',
                            onclick: function() {
                                ed.plugins.variables.addVariable('course_abbrv');
                            }
                        },
                        {
                            text: 'Insert User First Name',
                            onclick: function() {
                                ed.plugins.variables.addVariable('first_name');
                            }
                        },
                        {
                            text: 'Insert User Last Name',
                            onclick: function() {
                                ed.plugins.variables.addVariable('last_name');
                            }
                        },
                    ],
                });
                

                // Show elt attribs, leaving here to debug for now
                ed.on('variableClick', function(e) {
                    console.log('click', e);
                });
            },
            plugins: [
                'variables',
                'advlist autolink lists link charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table paste code help wordcount',
            ],
            variable_mapper: { // Can add more vars to be mapped here
                course_name: '{Course Name}',
                course_abbrv: '{Course Abbreviation}',
                first_name: '{First Name}',
                last_name: '{Last Name}',
            },
        });
        
    });    
</script>


<div class="box-body">
    <!-- Label -->
    <div class="form-group">
        {!! Form::label('label', trans('labels.courseEmails.label'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-10">
            {!! Form::input('label', 'label', null, ['class' => 'form-control', 'placeholder' => trans('labels.courseEmails.label')]) !!}
        </div>      
    </div>

    <!-- Email Type -->
    <div class="form-group">
        {!! Form::label('email_type_id', trans('labels.courseEmails.email_type'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-4">
            {!! Form::select('email_type_id', $email_types, null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) !!}
        </div>
    </div>
    
    <!-- Email Subject -->
    <div class="form-group">
        {!! Form::label('subject', trans('labels.courseEmails.subject'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-10">
            {!! Form::input('subject', 'subject', null, ['id' => 'subject', 'class' => 'form-control', 'placeholder' => trans('labels.courseEmails.subject')]) !!}
        </div>
    </div>


    <!-- Email Body -->
    <div class="form-group">
        {!! Form::label('body', trans('labels.courseEmails.body'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-10">
            {{ Form::textarea('body', null, ['class' => 'form-control']) }}
        </div>
    </div>
</div>

<!-- TO -->
<div class="form-group">
    {{ Form::label('to_roles', trans('labels.courseEmails.to'), ['class' => 'col-lg-2 control-label']) }}
    <div class="col-lg-3"> 
        @if(isset($courseEmails))
            {{  Form::select('to_roles[]', $permissions_types, $toRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
        @else
            {{ Form::select('to_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
        @endif
    </div>
    <div class="col-lg-1">
        {{ Form::label('to_other', trans('labels.courseEmails.other_to'), ['class' => 'col-lg control-label']) }}
    </div>
    <div class="col-lg-5">    
        {{ Form::input('to_other', 'to_other', null, ['class' => 'form-control']) }}
    </div>
</div>


<!-- CC -->
<div class="form-group">
    {{ Form::label('cc_roles', trans('labels.courseEmails.cc'), ['class' => 'col-lg-2 control-label']) }}
    <div class="col-lg-3"> 
    @if(isset($courseEmails))
            {{  Form::select('cc_roles[]', $permissions_types, $ccRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
        @else
            {{ Form::select('cc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
        @endif
    </div>
    <div class="col-lg-1">
        {{ Form::label('cc_other', trans('labels.courseEmails.other_cc'), ['class' => 'col-lg control-label']) }}
    </div>
    <div class="col-lg-5">    
        {{ Form::input('cc_other', 'cc_other', null, ['class' => 'form-control']) }}
    </div>
</div>

<!-- Email Timing -->
<div class="form-group">
    {{ Form::label('when', 'Automatically Send This Email:', ['class' => 'col-lg-3 control-label']) }}
    <div class="col-lg-1">
        @if(isset($courseEmails))
            {{ Form::number('time_amount', $courseEmails->time_amount, ['class' => 'form-control', 'placeholder' => 'Time', 'min' => '0']) }}
        @else
            {{ Form::number('time_amount', '', ['class' => 'form-control', 'placeholder' => 'Time', 'min' => '0']) }}
        @endif
    </div>
    <div class="col-lg-2">
       {{ Form::select('time_type', [null => trans('labels.general.select'), '1' => 'Minutes', '2' => 'Hours', '3' => 'Days'], null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
    </div>
    <div class="col-lg-2">
        {{ Form::select('time_offset', [null => trans('labels.general.select'), '1' => 'Before Start Time', '2' => 'After Start Time', '3'=> 'Before End Time', '4' => 'After End Time'], null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
    </div>
</div>


<!-- When the event ... -->
<div class="form-group">
    {{ Form::label('limit', 'When the number of people in the:', ['class' => 'col-lg-3 control-label']) }}
    <div class="col-lg-2">
       {{ Form::select('role_id', $eventRoles, null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
    </div>

    <div class="col-lg-1">
        {{ Form::label('', 'role is', ['class' => 'col-lg control-label'])}}
    </div>

    <div class="col-lg-2">
       {{ Form::select('role_offset', [null => trans('labels.general.select'), '1' => 'Less Than', '2' => 'Greater Than'], null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
    </div>

    <div class="col-lg-1">
        @if(isset($courseEmails))
            {{ Form::number('role_amount', $courseEmails->role_amount, ['class' => 'form-control', 'placeholder' => 'Amount', 'min' => '0']) }}
        @else
            {{ Form::number('role_amount', '', ['class' => 'form-control', 'placeholder' => 'Amount', 'min' => '0']) }}
        @endif
    </div>
</div>


<!-- Btn's -->
<div class="box box-success">
    <div class="box-body">
        <div class="pull-left">
            {{ link_to_route('courseInstanceEmails.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
        </div>

        <div class="pull-right">
            {{ Form::submit($submitButton, ['class' => 'btn btn-success btn-md']) }}
        </div>

        <div class="clearfix"></div>
    </div>
</div>
