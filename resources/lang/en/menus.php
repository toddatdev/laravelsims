<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menus Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in menu items throughout the system.
    | Regardless where it is placed, a menu item can be listed here so it is easily
    | found in a intuitive way.
    |
    */
    'frontend' => [
        'scheduling' => [
            'add-request'   => 'Create New Request',
            'view-all'     => 'View All Requests',
            'view-pending' => 'View Pending Requests',
            'view-approved' => 'View Approved Requests',
            'view-denied'   => 'View Denied Requests',
            'tasks'        => 'Pending Request Tasks',
            'recurrence_group' => 'Recurrence Group',
        ],

        'event' => [
            'assign'   => 'Assign Event Users/Roles',
        ],
    ],

    'backend' => [
        'access' => [
            'title' => 'Accounts',

            'roles' => [
                'all'           => 'All Roles',
                'create'        => 'Create Role',
                'edit'          => 'Edit Role',
                'management'    => 'Role Management',
                'main'          => 'Roles',
                'site_roles'    => 'Site Roles',
                'course_roles'  => 'Course Roles',
                'event_roles'   => 'Event Roles',
            ],

            'users' => [
                'all'             => 'Active Users',
                'change-password' => 'Change Password',
                'create'          => 'Create User',
                'deactivated'     => 'Deactivated Users',
                'deleted'         => 'Deleted Users',
                'edit'            => 'Edit User',
                'main'            => 'Users',
                'view'            => 'View User',
            ],

        ],

        //      This section added to the bootstrap template for SIMS Laravel

        'user-profile-questions' => [
            'title' => 'User Profile Questions',
            'view-all' => 'View All Profile Questions',
            'create' => 'Create New Profile Question',
            'edit' => 'Edit Profile Question',
            'questions' => 'Questions Tasks',
            'update'  => 'Update Profile Question',
            'active'  => 'View All Active Questions',
            'retired' => 'View all Retired Questions',
            'delete'  => 'Confirm Delete Question',
            'all-active' => 'All Active Profile Questions',
            'all-retired' => 'All Retired Profile Questions',
        ],


        'site' => [
            'title' => 'Sites',
            'view-all' => 'View All Sites',
            'create' => 'Create New Site',
            'edit' => 'Edit Site',
            'roles' => 'Site Roles',
            'assign' => 'Assign Site Users/Roles',
            'site' => 'Site',
            'email' => 'Email',
            'options' => 'Options',
            'option_description' => 'Option Description',
            'payments' => 'Payments',
        ],

        'course' => [
            'title'                 => 'Courses',
            'view-all'              => 'View All Courses',
            'view-active'           => 'View Active Courses',
            'view-inactive'         => 'View Retired Courses',
            'view-course'           => 'View Course',
            'create'                => 'Create New Course',
            'focus'                 => 'Back to Course',
            'edit'                  => 'Edit Course',
            'upload'                => 'Upload Catalog Image',
            'image_current'         => 'Current Catalog Image',
            'image_new'             => 'New Catalog Image',
            'image_remove'          => 'Remove Catalog Image',
            'tasks'                 => 'Course Tasks',
            'roles'                 => 'Course Roles',
            'assign'                => 'Assign User to Course Role',
            'curriculum'            => 'Curriculum',
            'edit_page'             => 'Edit Page',
            'edit_video'            => 'Edit Video',
            'courses'               => 'Courses',
            'my_courses'            => 'My Courses',
            'preview_page'          => 'Preview page',
            'show'                  => 'Show',
            'content'               => [
                'module-menu-item'  => [
                    'add_page'              => 'Add Page',
                    'add_articulate'        => 'Add Articulate',
                    'add_downloadable_file' => 'Add Downloadable File',
                    'add_office_file'       => 'Add Office File/PDF',
                    'add_qse'               => 'Add QSE',
                    'add_video'             => 'Add Video',
                    'rename_module'         => 'Rename Module',
                    'retire'                => 'Retire',
                    'activate'              => 'Activate',
                    'delete_module'         => 'Delete Module',
                ]
            ]
        ],

        'courseCategoryGroup'   => [
            'title'             => 'Course Category Groups',
            'create'            => 'Create New Course Category Group',
            'edit'              => 'Edit Course Category Group',
        ],

        'courseCategory'    => [
            'title'         => 'Course Categories',
            'create'        => 'Create New Course Category',
            'edit'          => 'Edit Course Category',
        ],

        'courseOptions' => [
            'title'     => 'Course Options',
            'create'    => 'Create New Course Option',
            'edit'      => 'Edit Course Option',
        ],

        'courseFees' => [
            'title'     => 'Course Fees',
            'tasks'     => 'Tasks',
        ],

        'courseCoupons' => [
            'title'     => 'Course Coupons',
        ],

        'courseFeeTypes' => [
            'title'     => 'Manage Course Fee Types',
        ],

        'courseTemplates' => [
            'title'     => 'Templates',
            'create'    => 'Create New Template',
            'edit'      => 'Edit Template',
            'tasks'     => 'Template Tasks',
        ],

        'courseCurriculum' => [
            'title'                  => 'Course Curriculum',
            'edit-course-curriculum' => 'Edit Course Curriculum',
        ],



        'resource' => [
            'title'         => 'Resource',
            'manage'        => 'Resource Management',
            'create'        => 'Create New Resource',
            'edit'          => 'Edit Resource',
            'delete'        => 'Delete Resource',
            'view-all'      => 'View All Resource',
            'view-active'   => 'View Active Resource',
            'view-inactive' => 'View Retired Resource',
            'tasks'         => 'Resource Tasks',
        ],

        'resourceCategory'  => [
            'title'         => 'Resource Categories',
            'manage'        => 'Category Management',
            'create'        => 'Create New Resource Category',
            'edit'          => 'Edit Category',
            'delete'        => 'Delete Category',
            'index'         => 'View Categories and Subcategories',
            'tasks'         => 'Resource Category Tasks',
        ],

        'resourceSubCategory'  => [
            'title'         => 'Resource Subcategories',
            'create'        => 'Create New Resource Subcategory',
            'edit'          => 'Edit Subcategory',
            'delete'        => 'Delete Subcategory',
        ],


        'building' => [
            'title'        => 'Buildings',
            'view-all'     => 'View All Buildings',
            'view-active'  => 'View Active Buildings',
            'view-retired' => 'View Retired Buildings',
            'create'       => 'Create New Building',
            'edit'         => 'Edit Building',
            'tasks'        => 'Building Tasks',
        ],

        'location' => [
            'title'        => 'Locations',
            'view-all'     => 'View All Locations',
            'view-active'  => 'View Active Locations',
            'view-retired' => 'View Retired Locations',
            'create'       => 'Create New Location',
            'edit'         => 'Edit Location',
            'tasks'        => 'Location Tasks',
            'schedulers'   => 'Schedulers',
        ],
        
        'siteEmails' => [
            'title'        => 'Site Emails',
            'create'       => 'Creating Email',
            'create-site'  => 'Create New Site Email',
            'create-course'=> 'Create New Course Email',
            'create-event' => 'Create New Event Email',
            'edit-site'    => 'Edit Site Email',
            'edit-course'  => 'Edit Course Email',
            'edit-event'   => 'Edit Event Email',
            'tasks'        => 'Email Tasks',
            'view-item'    => 'Email',
            'site'         => 'Site Email' ,
            'course'       => 'Course Email',
            'event'        => 'Event Email',
            'btn'          => 'Create Email'

        ],

        'courseEmails' => [
            'title'        => 'Course Emails for ',
            'create'       => 'Create New Email for ',
            'create-c'     => 'Create New Course Email',
            'create-e'     => 'Create New Event Email',
            'edit'         => 'Edit Emails for ',
            'tasks'        => 'Email Tasks',
            'view-item'    => 'Email',

        ],

        'eventEmails' => [
            'title'        => 'Event Emails',
            'create'       => 'Create New Email',
            'edit'         => 'Edit Email',
            'tasks'        => 'Email Tasks',
            'view-item'    => 'Email',

        ],


//      End of SIMS Laravel Section

        'log-viewer' => [
            'main'      => 'Log Viewer',
            'dashboard' => 'Dashboard',
            'logs'      => 'Logs',
        ],

        'sidebar' => [
            'dashboard' => 'Administration', // I changed this. SIMS30-160 -jl 2019-07-17 14:27
            'general'   => 'General',
            'system'    => 'System',
        ],
    ],

    'language-picker' => [
        'language' => 'Language',
        /*
         * Add the new language to this array.
         * The key should have the same language code as the folder name.
         * The string should be: 'Language-name-in-your-own-language (Language-name-in-English)'.
         * Be sure to add the new language in alphabetical order.
         */
        'langs' => [
            'ar'    => 'Arabic',
            'zh'    => 'Chinese Simplified',
            'zh-TW' => 'Chinese Traditional',
            'da'    => 'Danish',
            'de'    => 'German',
            'el'    => 'Greek',
            'en'    => 'English',
            'es'    => 'Spanish',
            'fr'    => 'French',
            'id'    => 'Indonesian',
            'it'    => 'Italian',
            'ja'    => 'Japanese',
            'nl'    => 'Dutch',
            'pt_BR' => 'Brazilian Portuguese',
            'ru'    => 'Russian',
            'sv'    => 'Swedish',
            'th'    => 'Thai',
            'vi'    => 'Vietnamese',
        ],
    ],
];
