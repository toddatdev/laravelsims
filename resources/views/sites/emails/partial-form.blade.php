{{-- tinyMCE V4 --}}
{{-- {{ Html::script("/js/tinymce/tinymce.min.js") }} --}}


{{-- TINY MCE V5 --}} {{-- Comment Out if using V4 --}}
{{ Html::script("/js/tinymce/tinymce.min.js") }}

{{-- Need to bring in variables plugin --}} {{-- Comment Out if using V4 --}}
{{ Html::script("/js/tinymce/plugins/variables/plugin.min.js") }}

{{ Html::script("js/jquery.js") }}

<script type="text/javascript">
    $(function () {
        // Subject tinyMCE
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

            setup: function (editor) {
                // Keeps Email Subject to 1 line
                editor.on('keydown', function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });

                editor.ui.registry.addMenuButton('dropBar', {
                    text: 'Insert Placeholder',
                    fetch: function (callback) {
                        var items = [
                            {   // Inserts for User
                                type: 'nestedmenuitem',
                                text: 'User',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'First Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('first_name');
                                            },
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Last Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('last_name');
                                            }
                                        }
                                    ];
                                }
                            },
                            {  // Inserts for Course
                                type: 'nestedmenuitem',
                                text: 'Course',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_name');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Abbreviation',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_abbrv');
                                            }
                                        },

                                    ];
                                }
                            },
                            {  // Inserts for Event
                                type: 'nestedmenuitem',
                                text: 'Event',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Short Date (e.g. 1/7/20)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_date_short');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Long Date',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_date_long');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Day (e.g. Monday)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_day');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Start Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_start_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'End Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_end_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Initial Meeting Room (Full)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('init_mtg_room_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Initial Meeting Room (Abbrv.)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('init_mtg_room_abbrv');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Notes',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_notes');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Internal Notes',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_internal_notes');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Faculty Start Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('faculty_start_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Faculty Leave Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('faculty_leave_time');
                                            }
                                        },
                                    ];
                                }
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Location',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Full Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('location_name_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Abbreviation',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('location_name_abbrv');
                                            }                                            
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'More Info',
                                            onAction: function() {
                                                editor.plugins.variables.addVariable('location_more_info');
                                            }
                                        }     
                                    ];
                                }                           
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Building',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Full Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_name_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Abbreviation',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_name_abbrv');
                                            }                                            
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'More Info',
                                            onAction: function() {
                                                editor.plugins.variables.addVariable('building_more_info');
                                            }
                                        }
                                    ];
                                } 
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Role',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Role',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('role');
                                            }                                            
                                        }
                                    ];
                                }
                            },
                        ];
                        callback(items);
                    }
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
                event_date_short: '{Event Date Short}', 
                event_date_long: '{Event Date Long}', 
                event_day : '{Event Day}',
                event_start_time: '{Event Start Time}',
                event_end_time: '{Event End Time}',
                init_mtg_room_full: '{Initial Mtg Room (full)}',
                init_mtg_room_abbrv: '{Initial Mtg Room (abbreviated)}',
                event_notes: '{Event Notes}',
                event_internal_notes: '{Event Internal Notes}',
                faculty_start_time: '{Faculty Start Time}',  // This will need to be calculated from event.start_time + event.fac_report
                faculty_leave_time: '{Faculty Leave Time}',  // This will need to be calculated from event.end_time + event.fac_leave
                location_name_full: '{Location Name (full)}',
                location_name_abbrv: '{Location Name (abbreviated)}',
                building_name_full: '{Building Name (full)}',
                building_name_abbrv: '{Building Name (abbreviated)}',
                building_map_url: '{Building Map URL}',
                role: '{Role}',
                building_more_info: '{Building More Info}',
                location_more_info: '{Location More Info}'
            },
        });

        // Body tinyMCE
        tinymce.init({
            selector: "textarea",
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo |  formatselect | bold italic underline backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link code',
            statusbar: false,
            setup: function (editor) {
                editor.ui.registry.addMenuButton('dropBar', {
                    text: 'Insert Placeholder',
                    fetch: function (callback) {
                        var items = [
                            {   // Inserts for User
                                type: 'nestedmenuitem',
                                text: 'User',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'First Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('first_name');
                                            },
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Last Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('last_name');
                                            }
                                        }
                                    ];
                                }
                            },
                            {  // Inserts for Course
                                type: 'nestedmenuitem',
                                text: 'Course',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_name');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Abbreviation',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_abbrv');
                                            }
                                        },

                                    ];
                                }
                            },
                            {  // Inserts for Event
                                type: 'nestedmenuitem',
                                text: 'Event',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Event Dashboard (link)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_dashboard_link');
                                            }
                                        }, 
                                        {
                                            type: 'menuitem',
                                            text: 'Short Date (e.g. 1/7/20)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_date_short');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Long Date',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_date_long');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Day (e.g. Monday)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_day');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Start Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_start_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'End Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_end_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Initial Meeting Room (Full)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('init_mtg_room_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Initial Meeting Room (Abbrv.)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('init_mtg_room_abbrv');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Notes',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_notes');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Internal Notes',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('event_internal_notes');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Faculty Start Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('faculty_start_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Faculty Leave Time',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('faculty_leave_time');
                                            }
                                        },
                                    ];
                                }
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Location',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Location Name (Full)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('location_name_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Location Name (Abbrv.)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('location_name_abbrv');
                                            }                                            
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Location More Info',
                                            onAction: function() {
                                                editor.plugins.variables.addVariable('location_more_info');
                                            }
                                        }     
                                    ];
                                }  
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Building',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Building Name (Full)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_name_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Building Name (Abbrv.)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_name_abbrv');
                                            }                                            
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Building Map Url',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_map_url');
                                            }                                            
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Building More Info',
                                            onAction: function() {
                                                editor.plugins.variables.addVariable('building_more_info');
                                            }
                                        }
                                    ];
                                } 
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Role',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Role',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('role');
                                            }                                            
                                        }
                                    ];
                                }
                            },
                        ];
                        callback(items);
                    }
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
                event_dashboard_link: '{Event Dashboard Link}',   
                event_date_short: '{Event Date Short}',   
                event_date_long: '{Event Date Long}',   
                event_day : '{Event Day}',
                event_start_time: '{Event Start Time}',
                event_end_time: '{Event End Time}',
                init_mtg_room_full: '{Initial Mtg Room (full)}',
                init_mtg_room_abbrv: '{Initial Mtg Room (abbreviated)}',
                event_notes: '{Event Notes}',
                event_internal_notes: '{Event Internal Notes}',
                faculty_start_time: '{Faculty Start Time}',  // This will need to be calculated from event.start_time + event.fac_report
                faculty_leave_time: '{Faculty Leave Time}',  // This will need to be calculated from event.end_time + event.fac_leave
                location_name_full: '{Location Name (full)}',
                location_name_abbrv: '{Location Name (abbreviated)}',
                building_name_full: '{Building Name (full)}',
                building_name_abbrv: '{Building Name (abbreviated)}',
                building_map_url: '{Building Map URL}',   
                role: '{Role}',
                building_more_info: '{Building More Info}',
                location_more_info: '{Location More Info}'            
            },
        });


       /* Show advanced options is course checkbox is clicked.
         * Code looks extremly ugly and takes a sec to init in browse, but only way I could get to work ..
        */
        // When edit email loads
        @if(isset($siteEmails))
            var email = {!! $siteEmails !!};
            if (email.is_course == 1) {
                $('.xtra-options').show();
            }
        @endif


        // UI function
        var checkBox = $(".form-check-input");
        checkBox.on('click', function(e) {
            this.checked ? $('.xtra-options').show() : $('.xtra-options').hide();
        });
        
    });

    
</script>
<div class="box-body">
    <!-- Label -->
    <div class="form-group">
        {!! Form::label('label', trans('labels.siteEmails.label'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-10">
            {!! Form::input('label', 'label', null, ['class' => 'form-control', 'placeholder' => trans('labels.siteEmails.label')]) !!}
        </div>      
    </div>

    <!-- Email Type -->
    <div class="form-group">
        {!! Form::label('email_type_id', trans('labels.siteEmails.email_type'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-4">
            {!! Form::select('email_type_id', $email_types, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
        </div>
    </div>
    
    <!-- Email Subject -->
    <div class="form-group">
        {!! Form::label('subject', trans('labels.siteEmails.subject'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-10">
            {!! Form::input('subject', 'subject', null, ['id' => 'subject', 'class' => 'form-control', 'placeholder' => trans('labels.siteEmails.subject')]) !!}
        </div>
    </div>


    <!-- Email Body -->
    <div class="form-group">
        {!! Form::label('body', trans('labels.siteEmails.body'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-10">
            {{ Form::textarea('body', null, ['class' => 'form-control']) }}
        </div>
    </div>
</div>

<!-- Is Course Email ChkBox -->
<div class="form-group">
    {!! Form::label('is_course', trans('labels.siteEmails.course_box'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        @if(isset($siteEmails))
            @if($siteEmails->is_course == "1")
                {{ Form::checkbox('is_course', 1, true, ['class' => 'form-check-input']) }}
            @else
                {{ Form::checkbox('is_course', 1, false, ['class' => 'form-check-input']) }}
            @endif
            @else
            {{ Form::checkbox('is_course', 1, false, ['class' => 'form-check-input']) }}
        @endif
        {{ Form::label('is_course', trans('labels.siteEmails.is_course_description'), ['class' => 'checkbox-inline']) }}
    </div>
</div>


<!-- TO -->
<div class="form-group xtra-options" style="display:none;">
    {{ Form::label('to_roles', trans('labels.siteEmails.to'), ['class' => 'col-lg-2 control-label']) }}
    <div class="col-lg-3"> 
        @if(isset($siteEmails))
            {{  Form::select('to_roles[]', $permissions_types, $toRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
        @else
            {{ Form::select('to_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
        @endif
    </div>
    <div class="col-lg-1">
        {{ Form::label('to_other', trans('labels.siteEmails.other_to'), ['class' => 'col-lg control-label']) }}
    </div>
    <div class="col-lg-5">    
        {{ Form::input('to_other', 'to_other', null, ['class' => 'form-control']) }}
    </div>
</div>


<!-- CC -->
<div class="form-group xtra-options" style="display:none;">
    {{ Form::label('cc_roles', trans('labels.siteEmails.cc'), ['class' => 'col-lg-2 control-label']) }}
    <div class="col-lg-3"> 
    @if(isset($siteEmails))
            {{  Form::select('cc_roles[]', $permissions_types, $ccRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
        @else
            {{ Form::select('cc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
        @endif
    </div>
    <div class="col-lg-1">
        {{ Form::label('cc_other', trans('labels.siteEmails.other_cc'), ['class' => 'col-lg control-label']) }}
    </div>
    <div class="col-lg-5">    
        {{ Form::input('cc_other', 'cc_other', null, ['class' => 'form-control']) }}
    </div>
</div>

<!-- Email Timing -->
<div class="form-group xtra-options" style="display:none;">
    {{ Form::label('when', 'Automatically Send This Email:', ['class' => 'col-lg-3 control-label']) }}
    <div class="col-lg-1">
        @if(isset($siteEmails))
            {{ Form::number('time_amount', $siteEmails->time_amount, ['class' => 'form-control', 'placeholder' => 'Time', 'min' => '0']) }}
        @else
            {{ Form::number('time_amount', '', ['class' => 'form-control', 'placeholder' => 'Time', 'min' => '0']) }}
        @endif
    </div>
    <div class="col-lg-2">
       {{ Form::select('time_type', [null => trans('labels.general.select'), '1' => 'Minutes', '2' => 'Hours', '3' => 'Days'], null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
    </div>
    <div class="col-lg-2">
        {{ Form::select('time_offset', [null => trans('labels.general.select'), '1' => 'Before Start Time', '2' => 'After Start Time', '3'=> 'Before End Time', '4' => 'After End Time'], null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
    </div>
</div>


<!-- When the event ... -->
<div class="form-group xtra-options" style="display:none;">
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
        @if(isset($siteEmails))
            {{ Form::number('role_amount', $siteEmails->role_amount, ['class' => 'form-control', 'placeholder' => 'Amount', 'min' => '0']) }}
        @else
            {{ Form::number('role_amount', '', ['class' => 'form-control', 'placeholder' => 'Amount', 'min' => '0']) }}
        @endif
    </div>
</div>


<!-- Btn's -->
<div class="box box-success">
    <div class="box-body">
        <div class="pull-left">
            {{ link_to_route('emails.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
        </div>

        <div class="pull-right">
            {{ Form::submit($submitButton, ['class' => 'btn btn-success btn-md']) }}
        </div>

        <div class="clearfix"></div>
    </div>
</div>
