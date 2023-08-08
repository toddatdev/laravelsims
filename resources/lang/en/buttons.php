<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Buttons Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in buttons throughout the system.
    | Regardless where it is placed, a button can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'users' => [
                'activate'           => 'Activate',
                'change_password'    => 'Change Password',
                'clear_session'      => 'Clear Session',
                'deactivate'         => 'Deactivate',
                'delete_permanently' => 'Delete Permanently',
                'login_as'           => 'Login As :user',
                'resend_email'       => 'Resend Confirmation E-mail',
                'restore_user'       => 'Restore User',
                'edit_profile'       => 'Edit User Profile',
            ],
        ],
        'buildings' => [
                'activate'           => 'Activate',
                'retire'             => 'Retire',
        ],
        'locations' => [
                'activate'           => 'Activate',
                'retire'             => 'Retire',
                'remove_scheduler_all' => 'Remove :name from scheduling ALL Locations',
        ],
        'courses' => [
            'categories'             => 'Categories',
            'options'                => 'Options',
            'editGroup'              => 'Edit Group',
            'addGroup'               => 'Add New Group',
            'deleteGroup'            => 'Delete Group',
            'addCategory'            => 'Add Category',
            'deleteCategory'         => 'Delete Category',
            'editCategory'           => 'Edit Category',
            'templates'              => 'Templates',
            'fees'                   => 'Fees'
        ],
        'resources' => [
            'allCategory'            => 'All Categories',
            'addCategory'            => 'Add Category',
            'editCategory'           => 'Edit Category',
            'deleteCategory'         => 'Delete Category',
            'disabledDeleteCategory' => 'Category is in use and cannot be deleted',
            'addSubCategory'         => 'Add Subcategory',
            'editSubCategory'        => 'Edit Subcategory',
            'deleteSubCategory'      => 'Delete Subcategory',
            'disabledDeleteSubCategory'=> 'Subcategory is in use and cannot be deleted',
            'equipment'              => 'Equipment',
            'rooms'                  => 'Rooms',
            'personnel'              => 'Personnel',

        ],
        'user-profile-questions' => [
            'firstLevelPlus'           => 'firstLevelPlus',

        ],
        'siteEmails' => [
            'activate'           => 'Activate',
            'delete'             => 'Delete',
            'send_now'           => 'Send Now',
        ],
    ],

    'emails' => [
        'auth' => [
            'confirm_account' => 'Confirm Account',
            'reset_password'  => 'Reset Password',
        ],
    ],

    'general' => [
        'cancel'    => 'Cancel',
        'continue'  => 'Continue',
        'deactivate' => 'Deactivate',
        'activate'  => 'Activate',
        'image'     => 'Upload Image',
        'image_new' => 'Upload New Image',
        'options'   => 'Options',
        'add_row'   => 'Add Row',
        'export'    => 'Export',
        'import'    => 'Import',
        'add_user'  => 'Add User',
        'retire'    => 'Retire',
        'filter'    => 'Filter',
        'email'     => 'Email',
        'send_now'  => 'Send Manually',
        'enroll'    => 'Enroll',
        'close'     => 'Close',
        'apply'     => 'Apply',
        'print'     => 'Print',

        'crud' => [
            'create' => 'Submit',
            'delete' => 'Delete',
            'edit'   => 'Edit',
            'save'   => 'Save',
            'update' => 'Update',
            'view'   => 'View',
            'add'    => 'Add',
            'duplicate'  => 'Duplicate',
            'copy'   => 'Copy',
            'move' => 'Move',
            'waitlist' => 'Waitlist',
            'comments' => 'Comments',
        ],

        //for location schedulers
        'schedulers' => 'Schedulers',

        'add_request_to_group'   => 'Add another request to this group',
        'copy_request'           => 'Copy this request to another date',

        'save' => 'Save',
        'view' => 'View',
        'register'  => 'Register',
        'my_courses' => 'My Courses',
    ],

    'course_templates' => [
        'create_template' => 'Create Template',
    ],
    'calendar' => [
        'go_to_day'     => 'Go to this Date on Calendar',
        'display_notes' => 'Display Notes',
        'hide_notes'    => 'Hide Notes'
    ],

    'event' => [
        'add_people'            => 'Add People to Event',
        'edit'                  => 'Edit Event',
        'recurrence_group'      => 'Recurrence Group',
        'duplicate'             => 'Duplicate Event',
        'anotherEvent'          => 'Add Another Event to Group',
        'newEventSameCourse'    => 'Add a New Event to Same Course',
        'delete'                => 'Delete Event',
        'dashboard'             => 'Event Dashboard',
        'view_history'          => 'View History',
        'park'                  => 'Park',
        'restore'               => 'Restore Event',
        'add_comment'           => 'Add Comment',
        'pay_online'            => 'Pay Online'
    ],

    'curriculum' => [
        'publish'            => 'Publish',
        'add_module'         => 'Add Module',
        'next'               => 'Next',
        'previous'           => 'Previous'
    ],

    'qse' => [
        'add_question'  => 'Add Question',
        'edit'          => 'Edit',
        'delete'        => 'Delete',
        'add_another_answer' => 'Add Answer',
        'read_more'     => 'Read More',
        'read_less'     => 'Read Less',
        'close'         => 'Close',
        'retire'        => 'Retire',
        'activate'      => 'Activate',
        'submit_quiz'   => 'Submit',
        'submit_answer' => 'Submit Answer',
        'submitting'    => 'Submitting...',
        'take_the_quiz' => 'Take the Quiz',
        'resume_quiz'   => 'Resume Quiz',
        'begin'         => 'Begin',
        'beginning'     => 'Beginning...',
        'please_wait'   => 'Please wait...',
        'complete_the_qse' => 'Complete the :qse',
        'print'     => 'Print',
        'duplicate'     => 'Duplicate',
    ]
];
