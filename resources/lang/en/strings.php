<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Strings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in strings throughout the system.
    | Regardless where it is placed, a string can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'users' => [
                'delete_user_confirm'  => 'Are you sure you want to delete this user permanently? Anywhere in the application that references this user\'s id will most likely error. Proceed at your own risk. This can not be un-done.',
                'if_confirmed_off'     => '(If confirmed is off)',
                'restore_user_confirm' => 'Restore this user to its original state?',
            ],
        ],

        'dashboard' => [
            'title'   => 'Site Administration',
            'welcome' => 'Welcome',
        ],

        'general' => [
            'all_rights_reserved' => 'All Rights Reserved.',
            'are_you_sure'        => 'Are you sure you want to do this?',
            'boilerplate_link'    => 'SIMS 3.0',
            'continue'            => 'Continue',
            'member_since'        => 'Member since',
            'minutes'             => ' minutes',
            'search_placeholder'  => 'Search...',
            'timeout'             => 'You were automatically logged out for security reasons since you had no activity in ',

            'see_all' => [
                'messages'      => 'See all messages',
                'notifications' => 'View all',
                'tasks'         => 'View all tasks',
            ],

            'status' => [
                'online'  => 'Online',
                'offline' => 'Offline',
            ],

            'you_have' => [
                'messages'      => '{0} You don\'t have messages|{1} You have 1 message|[2,Inf] You have :number messages',
                'notifications' => '{0} You don\'t have notifications|{1} You have 1 notification|[2,Inf] You have :number notifications',
                'tasks'         => '{0} You don\'t have tasks|{1} You have 1 task|[2,Inf] You have :number tasks',
            ],
        ],

        'search' => [
            'empty'      => 'Please enter a search term.',
            'incomplete' => 'You must write your own search logic for this system.',
            'title'      => 'Search Results',
            'results'    => 'Search Results for :query',
        ],

        'welcome' => '<p>This is the adminstrative section for the Simulation Information Management System (SIMS). Only simulation program administrators should have access to this. Users can see these administrative pages by having <span style=font-weight:bold;>View Site Administration</span> permission in at least one of their SIMS roles, assigned under the  <span style=font-weight:bold;>Accounts → User Management</span> on the left hand menu here. </p>',

        'courseCoupons' => [
            'amount_help' => 'The coupon amount can either be a percentage of the fee, or a specific amount based on the type selected. When selecting percent, please enter the percentage as a whole number, for example 25% should be entered as 25.',
        ],
    ],

    'emails' => [
        'auth' => [
            'error'                   => 'Whoops!',
            'greeting'                => 'Hello!',
            'regards'                 => 'Regards,',
            'trouble_clicking_button' => 'If you’re having trouble clicking the ":action_text" button, copy and paste the URL below into your web browser:',
            'thank_you_for_using_app' => 'Thank you for using our application!',

            'password_reset_subject'    => 'Reset Password',
            'password_cause_of_email'   => 'You are receiving this email because we received a password reset request for your account.',
            'password_if_not_requested' => 'If you did not request a password reset, no further action is required.',
            'reset_password'            => 'Click here to reset your password',

            'click_to_confirm' => 'Click here to confirm your account:',
        ],
        'no_addresses' => 'The email cannot be sent. There are no users in the roles selected.',
        'sent_successfully' => 'Email sent successfully.'

    ],

    'frontend' => [

        'welcome_board_no_data' => 'No more events today.',
        'welcome_to_heading' => 'Welcome to',
        'todays_events' => 'TODAY&apos;S EVENTS',

        'no_building_info' => 'No information is available for this building. Contact <a href=mailto:":siteEmail">:siteEmail</a> for assistance.',
        'no_location_info' => 'No information is available for this location. Contact <a href=mailto:":siteEmail">:siteEmail</a> for assistance.',

        'event-dashboard-login' => 'If you are affiliated with this event, please <a href="/login">login</a> to view additional information.',
        'event-dashboard-waitlist' => 'You have requested enrollment to this event and are currently on the waitlist. Please contact <a href=mailto:":siteEmail">:siteEmail</a> for assistance.',

        'waitlist_request' => '<p><a href="/courseInstance/events/event-dashboard/:eventId">:eventName</a></p>
                                <p>You requested enrollment on :createdAt and should hear back shortly.
                                If you do not, please contact <a href=mailto:":siteEmail">:siteEmail</a>.</p>',

        'pending_payment_request' => '<p><a href="payment/:eventUserPaymentId">:eventName</a></p>
                                <p>You started the enrollment process on :createdAt but have not completed payment. Follow the link
                                above to resume the online payment process. If you need assistance, please contact <a href=mailto:":siteEmail">:siteEmail</a>.
                                If you are no longer interested in this event <a href="deletePaymentRequest/:eventUserId">click here</a> to remove your request.</p>',

        'payment_waiting_for_webhook' => 'Processing payment. Please do not close this window or click the back button on your browser. You will be automatically directed to the event dashboard upon completion.',

        'self_parked' => '<p>:eventName</p>
                                <p>You have been placed on the waitlist.  You will be notified
                                if you are assigned to an event.
                                Please contact <a href=mailto:":siteEmail">:siteEmail</a> with any questions.</p>',

        'parked_from_event' => '<p>:eventName</p>
                                <p>You have been moved to the waitlist (parking lot).  At this time, you have not 
                                been reassigned to a new class. You will be notified when you are reassigned.
                                Please contact <a href=mailto:":siteEmail">:siteEmail</a> with any questions.</p>',

        'no_enroll_event_full' => 'Enrollment requests are not allowed because this event is full. <a href="/courses/catalogShow/:course_id">Please select another date</a>.',

        'test' => 'Test',

        'tests' => [
            'based_on' => [
                'permission' => 'Permission Based - ',
                'role'       => 'Role Based - ',
            ],

            'js_injected_from_controller' => 'Javascript Injected from a Controller',

            'using_blade_extensions' => 'Using Blade Extensions',

            'using_access_helper' => [
                'array_permissions'     => 'Using Access Helper with Array of Permission Names or ID\'s where the user does have to possess all.',
                'array_permissions_not' => 'Using Access Helper with Array of Permission Names or ID\'s where the user does not have to possess all.',
                'array_roles'           => 'Using Access Helper with Array of Role Names or ID\'s where the user does have to possess all.',
                'array_roles_not'       => 'Using Access Helper with Array of Role Names or ID\'s where the user does not have to possess all.',
                'permission_id'         => 'Using Access Helper with Permission ID',
                'permission_name'       => 'Using Access Helper with Permission Name',
                'role_id'               => 'Using Access Helper with Role ID',
                'role_name'             => 'Using Access Helper with Role Name',
            ],

            'view_console_it_works'          => 'View console, you should see \'it works!\' which is coming from FrontendController@index',
            'you_can_see_because'            => 'You can see this because you have the role of \':role\'!',
            'you_can_see_because_permission' => 'You can see this because you have the permission of \':permission\'!',
        ],

        'user' => [
            'change_email_notice' => 'If you change your e-mail you will be logged out until you confirm your new e-mail address.',
            'email_changed_notice' => 'You must confirm your new e-mail address before you can log in again.',
            'profile_updated'  => 'Profile successfully updated.',
            'password_updated' => 'Password successfully updated.',
        ],
        'event_request' => [
            'request_submitted' => 'Your enrollment request has been submitted.',
            'auto_enroll_info' => 'This course allows learners to self register. Select a date and role above then click submit to register.'
        ],
        'welcome_to' => 'Welcome to :place',
        'email' => [
            'based_upon' => 'This is based upon the',
            'site_email_template' => 'site email template',
            'course_email_template' => 'course email template',
            'but_edited' => 'but edited',
            'not_based_site_template' => 'This is not based upon any site email template.',
            'not_based_course_template' => 'This is not based upon any course email template.',
        ],
        'event_user' => [
            'parked' => 'Enrolled previously, see history.'
        ],
    ],
];
