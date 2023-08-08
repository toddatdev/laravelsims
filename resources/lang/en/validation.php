<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute must be unique.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [

        'backend' => [
            'access' => [
                'permissions' => [
                    'associated_roles' => 'Associated Roles',
                    'dependencies'     => 'Dependencies',
                    'display_name'     => 'Display Name',
                    'group'            => 'Group',
                    'group_sort'       => 'Group Sort',

                    'groups' => [
                        'name' => 'Group Name',
                    ],

                    'name'       => 'Name',
                    'first_name' => 'First Name',
                    'last_name'  => 'Last Name',
                    'middle_name' => 'Middle Name',
                    'system'     => 'System',
                ],

                'roles' => [
                    'associated_permissions' => 'Associated Permissions',
                    'name'                   => 'Name',
                    'sort'                   => 'Sort',
                ],

                'users' => [
                    'active'                  => 'Active',
                    'associated_roles'        => 'Associated Roles',
                    'confirmed'               => 'Confirmed',
                    'email'                   => 'E-mail Address',
                    'name'                    => 'Name',
                    'last_name'               => 'Last Name',
                    'first_name'              => 'First Name',
                    'middle_name'             => 'Middle Name',
                    'phone'                   => 'Phone',
                    'other_permissions'       => 'Other Permissions',
                    'password'                => 'Password',
                    'password_confirmation'   => 'Password Confirmation',
                    'send_confirmation_email' => 'Send Confirmation E-mail',
                ],
            ],
        ],

        'frontend' => [
            'email'                     => 'E-mail Address',
            'first_name'                => 'First Name',
            'middle_name'               => 'Middle Name',
            'last_name'                 => 'Last Name',
            'phone'                     => 'Phone',
            'profile_picture'           => 'Profile Picture',
            'password'                  => 'Password',
            'password_confirmation'     => 'Password Confirmation',
            'old_password'              => 'Old Password',
            'new_password'              => 'New Password',
            'new_password_confirmation' => 'New Password Confirmation',

            // I added this for the modal about the phone number on the user page. -jl 2018-04-02 14:40
            'why'                       => 'Why?',
            'why_phone_ques'            => 'Why do you need my phone number?',
            'why_phone_answer'          => ':site_abbrv may need to get in touch with you to notify you about canceled or changed events. This phone number will only be used by :site_name for information regarding the program.<br/><br/>Entering your phone number is <b>optional</b>.<br/><br/>If you have any questions, you can contact :site_abbrv at <a href="mailto::site_email">:site_email</a>.',
            'close'                     => 'Close',

        ],

        'site' => [
            'abbrv'                     => 'Abbreviation',
            'name'                      => 'Full Site Name',
            'organization_name'         => 'Organization Name',
            'email'                     => 'Email',
        ],

        'user-profile-questions' => [
            'question'                  => 'Question',
            'answer'                    => 'Answer',
        ],

        'event_users' => [
            'user'                     => 'User',
            'event'                    => 'Event',
            'role'                     => 'Role',
            'move_event_id'            => 'You must select an event',
            'move_event_user'          => 'You must be moving a user',
            'duplicate'                => 'You have already made a request for this event for this role.',
        ],

        'schedule_comment' => [
            'comment'                  => 'Comment',
            'max_size'                 => 'Number of Characters',
        ],

        'course' => [
            'abbrv'                     => 'Abbreviation',
            'name'                      => 'Full Course Name',
            'catalog_description'       => 'Catalog Description',
            'author_name'               => 'Author Name',
            'catalog_image'             => 'Catalog Image',
        ],

        'course_template' => [
            'name'                      => 'template name',
            'class_size'                => 'number of participants',
            'setup'                     => 'setup time',
            'teardown'                  => 'teardown time',
            'imr'                       => 'initital meeting room',
            'course_id'                 => 'course',
            'resource_name'             => 'resource name',
            'resource_start_time'       => 'resource start time',
            'resource_end_time'         => 'resource end time',
            'resource_start_gt_end'              => 'The resource end time must be greater than the start time'
        ],

        'course_users' => [
            'user'                     => 'User Search',
            'course'                   => 'Course',
            'role'                     => 'Course Role',
        ],

        'site_users' => [
            'user'                     => 'User Search',
            'role'                     => 'Site Role',
        ],


        'courseCategoryGroup' => [
            'abbrv'                     => 'Abbreviation',
            'description'               => 'Description',
        ],


        'resource' => [
            'abbrv'                     => 'Abbreviation',
            'full_name'                 => 'Full Name',
            'location'                  => 'Location',
            'category'                  => 'Category',
            'subcategory'               => 'Subcategory',
            'type'                      =>  'Type',
        ],

        'resourceCategory' => [
            'abbrv'                     => 'Abbreviation',
            'full_name'                 => 'Full Name',
        ],

        'resourceSubCategory' => [
            'abbrv'                     => 'Abbreviation',
            'full_name'                 => 'Full Name',
        ],

        'courseCategories' => [
            'abbrv'                     => 'Abbreviation',
            'description'               => 'Description',
        ],

        'buildings' => [
            'create_building_box_title' => 'Create New Building',
            'id'                        => 'Building ID',
            'abbrv'                     => 'Abbreviation',
            'name'                      => 'Full Building Name',
            'more_info'                 => 'More information',
            'map_url'                   => 'Map URL',
            'address'                   => 'Street Address',
            'city'                      => 'City',
            'state'                     => 'State/Province',
            'postal_code'               => 'Zip/Postal Code',
            'timezone'                  => 'Timezone',
            'timezone_help'             => 'Timezone in Region/City format',
            'display_order'             => 'Display Order',
            'create_button'             => 'Create',
            'update_button'             => 'Update',
        ],
        'schedule' => [
            'course_id'                 => 'Course',
            'location_id'               => 'Location',
            'class_size'                => 'Number of Participants',
            'num_rooms'                 => 'Number of Rooms',
            'fac_report'                => 'Instructor Report',
            'fac_leave'                 => 'Instructor Leave',
            'start_time'                => 'Start Time',
            'end_time'                  => 'End Time',
            'event_date'                => 'Event Date',
            'initial_meeting_room'      => 'Initial Meeting Room',
        ],
        'locations' => [
            'create_location_box_title' => 'Create New Location',
            'id'                        => 'Location ID',
            'abbrv'                     => 'Abbreviation',
            'name'                      => 'Full Location Name',
            'more_info'                 => 'More information',
            'directions_url'            => 'Directions URL',
            'display_order'             => 'Display Order',
            'create_button'             => 'Create',
            'update_button'             => 'Update',
            'unique'                    => 'The :attribute must be unique within the building.',
        ],
        'siteEmails' => [
            'type_course_rule'          => 'Site Type Email Cannot be Course Email.'
        ],
        'qse' => [
            'answerCorrects' => 'At least one answer should be correct.'
        ]
    ],

];