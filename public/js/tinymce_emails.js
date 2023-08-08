// Load the TinyMCE Plugin for Email creator, attach to the bodys Textarea
// Level Conditions
// SITE == 1
// COURSE == 2
// EVENT == 3

// Needs the following scripts initialized before we do anything:
// jQuery
// TinyMCE
// TinyMCE variable plugin


// When DOM is LOADED init TinyMCE find which level request is for and attach to our TextArea's.
$(function () {
    'use strict'
    // ? GLOBALS ?
    var hasActionEmailType = false;
    var hasToRecipent = false;

    // Email type MSG boxes
    var emailTypeLookupArr = [
        4,   // Add To Event
        5    // Remove from event
    ];

    /**
     * COURSE
     */
    if (level == 1) {
        // Subject tinyMCE
        tinymce.init({
            selector: "#subject",
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo',
            statusbar: false,
            inline: false,
            resize: false,
            formats: {
                // Changes the default format for h1 to have a class of heading
                h1: { block: 'h1', classes: 'heading' }
            },

            setup: function (editor) {
                // Keeps Email Subject to 1 line
                editor.on('keydown', function (e) {
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
                                title: 'User',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert First Name',
                                            title: 'First Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('first_name');
                                            },
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Last Name',
                                            title: 'Last Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('last_name');
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
                first_name: '{First Name}',
                last_name: '{Last Name}',
            },
        });

        // Body tinyMCE
        tinymce.init({
            selector: "textarea",
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'],
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo |  formatselect | bold italic underline backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link',
            statusbar: false,
            link_assume_external_targets: true,
            setup: function (editor) {
                editor.ui.registry.addMenuButton('dropBar', {
                    text: 'Insert Placeholder',
                    fetch: function (callback) {
                        var items = [
                            {   // Inserts for User
                                type: 'nestedmenuitem',
                                text: 'User',
                                title: 'User',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert First Name',
                                            title: 'First Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('first_name');
                                            },
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Last Name',
                                            title: 'Last Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('last_name');
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
                'variables', 'link'
            ],
            variable_mapper: { // Can add more vars to be mapped here
                first_name: '{First Name}',
                last_name: '{Last Name}',
            },
        });
        return;
    }

    /**
     * COURSE - LEVEL 2
     */
    else if (level == 2) {
        tinymce.init({
            selector: "#subject",
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo',
            statusbar: false,
            inline: false,
            resize: false,
            formats: {
                // Changes the default format for h1 to have a class of heading
                h1: { block: 'h1', classes: 'heading' }
            },

            setup: function (editor) {
                // Keeps Email Subject to 1 line
                editor.on('keydown', function (e) {
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
                                title: 'User',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert First Name',
                                            title: 'First Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('first_name');
                                            },
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Last Name',
                                            title: 'Last Name',
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
                                title: 'Course',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Course Name',
                                            title: 'Course Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_name');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Course Abbreviation',
                                            title: 'Course Abbreviation',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_abbrv');
                                            }
                                        },
                                    ];
                                }
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Role',
                                title: 'Role',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Role',
                                            title: 'Insert Role',
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
                role: '{Role}'
            },
        });

        // Body tinyMCE
        tinymce.init({
            selector: "textarea",
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'],
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo |  formatselect | bold italic underline backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link',
            statusbar: false,
            link_assume_external_targets: true,
            setup: function (editor) {
                editor.ui.registry.addMenuButton('dropBar', {
                    text: 'Insert Placeholder',
                    fetch: function (callback) {
                        var items = [
                            {   // Inserts for User
                                type: 'nestedmenuitem',
                                text: 'User',
                                title: 'User',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert First Name',
                                            title: 'First Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('first_name');
                                            },
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Last Name',
                                            title: 'Last Name',
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
                                title: 'Course',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Course Name',
                                            title: 'Course Name',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_name');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Course Abbreviation',
                                            title: 'Course Abbreviation',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('course_abbrv');
                                            }
                                        },

                                    ];
                                }
                            },
                            {
                                type: 'nestedmenuitem',
                                text: 'Role',
                                title: 'Role',
                                getSubmenuItems: function () {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Insert Role',
                                            title: 'Insert Role',
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
                'variables', 'link'
            ],
            variable_mapper: { // Can add more vars to be mapped here
                course_name: '{Course Name}',
                course_abbrv: '{Course Abbreviation}',
                first_name: '{First Name}',
                last_name: '{Last Name}',
                role: '{Role}'
            },
        });
        return;
    }


    /**
     * EVENT - Level 3
     */
    else if (level == 3) {
        var submitButton = $('#event-email-btn');      

        // Adding in our Event Email Action Messages (SIMS30-458)
        $('#email_type_id').on('change', function (e) {

            // On new Email Type Select set back our defaults
            $('#email-type-msg-box').hide();
            $('#email-type-flag-warn').hide();
            $('.xtra').show();
            submitButton.prop('disabled', false);

            hasActionEmailType = false;

            // Get value of current select email type from dropdown, need to cast as int, and grab the dropdown text
            let curr = parseInt(this.value);
            let text = $(this).find("option:selected").text();

            // If select type is in our lookup array
            if (emailTypeLookupArr.includes(curr)) {
                hasActionEmailType = true;
                
                // Update the DOM
                $('.xtra').hide();
                $('#email-type-flag-warn').show().text("Make sure you select at least one To: role below").css('color', 'rgb(255,0,0)');
                $('#email-type-msg-box').show().text('Select the roles(s) the "To" recipient must be in to receive this ' + text + ' email').css('color', 'rgb(255,0,0)');

                // check to see if TO as values,
                let items = $('.to-roles').val();
  
                // If "To" Roles is empty then diasable the button
                if (Array.isArray(items) && !items.length) {
                    hasToRecipent = false;
                    submitButton.prop('disabled', true);
                }
            }
        });

        // watch the "To" roles when changing
        $('.to-roles').on('click', function() {
            if (hasActionEmailType) {
                let items = $(this).val();
                // if array is not empty
                if (Array.isArray(items) && items.length) {
                    hasToRecipent = true;
                    submitButton.prop('disabled', false);
                }else {
                    hasToRecipent = false;
                    submitButton.prop('disabled', true);       
                }            
            }
        });

        tinymce.init({
            selector: "#subject",
            content_css: [
                // '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                // '//www.tinymce.com/css/codepen.min.css'
            ],
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo',
            statusbar: false,
            inline: false,
            resize: false,
            formats: {
                // Changes the default format for h1 to have a class of heading
                h1: { block: 'h1', classes: 'heading' }
            },

            setup: function (editor) {
                // Keeps Email Subject to 1 line
                editor.on('keydown', function (e) {
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
                                            text: 'Short Date (e.g 1/7/20)',
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
                                            text: 'Instructor Report',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('faculty_start_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Instructor Leave',
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
                                            onAction: function () {
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
                                            onAction: function () {
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
                event_day: '{Event Day}',
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
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'],
            menubar: false,
            browser_spellcheck: true,
            toolbar: 'dropBar | undo redo | formatselect | bold italic underline backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link',
            statusbar: false,
            link_assume_external_targets: true,
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
                                            text: 'Instructor Report',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('faculty_start_time');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Instructor Leave',
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
                                            text: 'Name (Full)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('location_name_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Name (Abbrv.)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('location_name_abbrv');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'More Info',
                                            onAction: function () {
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
                                            text: 'Name (Full)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_name_full');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Name (Abbrv.)',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_name_abbrv');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Map Url',
                                            onAction: function () {
                                                editor.plugins.variables.addVariable('building_map_url');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'More Info',
                                            onAction: function () {
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
                'variables', 'link'
            ],
            variable_mapper: { // Can add more vars to be mapped here
                course_name: '{Course Name}',
                course_abbrv: '{Course Abbreviation}',
                first_name: '{First Name}',
                last_name: '{Last Name}',
                event_dashboard_link: '{Event Dashboard Link}',
                event_date_short: '{Event Date Short}',
                event_date_long: '{Event Date Long}',
                event_day: '{Event Day}',
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
        return;
    } else {
        /**
         * NO LEVEL DEFINED
         */
        console.error('Not Level Defined');
        return;
    }
});
