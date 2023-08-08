<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */
    'general' => [
        'confirm_delete' => 'Confirm Deletion',
        'confirm_delete_content' => 'Are you sure you want to remove ',
        'confirm_restore' => 'Confirm Restoration',
    ],

    'backend' => [
        'roles' => [
            'created'       => 'The role was successfully created.',
            'deleted'       => 'The role was successfully deleted.',
            'updated'       => 'The role was successfully updated.',
            'delete_wall'   => 'Are you sure you want to delete this role?',
        ],

        'users' => [
            'confirmation_email'  => 'A new confirmation e-mail has been sent to the address on file.',
            'created'             => 'The user was successfully created.',
            'deleted'             => 'The user was successfully deleted.',
            'deleted_permanently' => 'The user was deleted permanently.',
            'restored'            => 'The user was successfully restored.',
            'session_cleared'      => "The user's session was successfully cleared.",
            'updated'             => 'The user was successfully updated.',
            'updated_password'    => "The user's password was successfully updated.",
            'need_scheduler_permission' => 'This user does not have scheduling permission. They need to be in a role that has "Manage Scheduling" permission.',
        ],

        'resources' => [
            'created' => '<b>:ResourceName</b> was successfully created. <b><a href="/resources/duplicate/:ResourceID">Duplicate this resource</a></b>.',
            'deleted' => 'The resource was successfully deleted.',
            'updated' => 'The resource was successfully updated.',
            'activated' => 'The resource was successfully activated.',
            'deactivated' => 'The resource was successfully deactivated.',
        ],

        'resourcecategory' => [
            'created' => 'The resource category was successfully created.',
            'updated' => 'The resource category was successfully updated.',
            'delete'  => 'Are you sure you want to delete the :categoryAbbrv category?',
        ],

        'resourcesubcategory' => [
            'created' => 'The resource subcategory was successfully created.',
            'updated' => 'The resource subcategory was successfully updated.',
            'delete'  => 'Are you sure you want to delete the :subcategoryAbbrv subcategory?',

        ],

        'user-profile' => [
            'deleted' => 'The question was successfully deleted.',
            'retired' => 'The question was successfully retired.',
            'activated' => 'The question was successfully activated.',
            'canNotBeDeleted' => 'The question cannot be deleted',
        ],

        'scheduling' => [
            'created' => 'The class was successfully created.',
            'schedule_request_created' => 'schedule request created successfully.',
        ],

        'templates' => [
            'created' => ' template was successfully created.',
            'edited' => ' template was successfully edited.',
            'delete_template_start' => 'Are you sure you want to delete the ',
            'delete_template_end' => ' template?',
            'delete_success' => ' successfully deleted.',
        ],
        'courseusers' => [
            'created' => 'User successfully added to course.',
            'deleted' => 'User successfully removed from course.',
            'deletePart1' => 'Are you sure you want to delete ',
            'deletePart2' => ' from the :course ',
            'deletePart3' => ' role?',
            'required' => 'Please fill out all required fields.',
            'access' => 'You do not have access to that course.',
            'invalid' => 'That is not a valid course.',
        ],

        'siteusers' => [
            'created' => 'User successfully added to site role.',
            'deleted' => 'User successfully removed from site role.',
            'delete' => 'Are you sure you want to delete this user from this site role?',
            'required' => 'Please fill out all required fields.',
        ],

        'options' => [
            'update' => 'Site options updated.',
        ],

        'courseCurriculum' => [
            'delete_module' => 'Are you sure you want to permanently delete ',
            'cannot_delete_module_with_content' => 'You can not delete a module that has content in it.',
            'there_is_no_modules_start' => 'There is no ',
            'there_is_no_modules_end' => ' content.  Click Add Module to get started.'
        ]

    ],

    'frontend' => [
        'scheduling' => [
            'created' => ' was successfully created.',
            'recurrence_created' => 'Multiple events were successfully created.',
            'edited' => 'This event was successfully edited.',
            'deleted' => ':eventName was successfully deleted.',
            'recurrence_deleted' => ':courseName on :eventDates were successfully deleted.',
            'restored' => '<a href="/courseInstance/events/event-dashboard/:eventId">:eventName on :eventDate</a> was successfully restored.',
            'confirm_delete_text' => 'Are you sure you want to delete :Event?',
            'confirm_restore_text' => 'Are you sure you want to restore',
            'schedule_request_created' => 'schedule request created successfully.',
            'no_schedulers' => 'NO SCHEDULERS HAVE BEEN ASSIGNED TO THIS LOCATION',
            'no_location_access' => 'You have not been assigned as a scheduler for any locations.  Please contact ',
            'no_event_location_access' => 'You have not been assigned as a scheduler for the location of this event.  Please contact ',
            'return_to_pending' => 'Return to Pending Requests.',
            'delete_recurrence1' => 'This event was added as part of a recurrence group of these dates: ',
            'delete_recurrence2' => 'Do you want to remove only ',
            'delete_recurrence3' => ' or all events in the group?',
            'has-conflicts'      => 'There are conflicts below. Would you still like to continue?',
            'conflict_confirm' => 'If you are ok with these conflicts click OK and re-submit.',
        ],

        'eventusers' => [
            'created' => ':UserName successfully added as :RoleName to :EventName.',
            'deleted' => ':UserName successfully removed from :eventOrWaitlist.',
            'deleteConfirmPart1' => 'Are you sure you want to remove ', //mitcks: cannot use regular parameters here for user full name because it exists in JS when row clicked and can't be used in trans function
            'deleteConfirmPart2' => 'from ',
            'waitlistDeleteConfirmPart2' => 'from the WAITLIST for :Event ?',
            'myWaitlistDeleteConfirmPart2' => 'from the WAITLIST for ',
            'parkConfirmPart1' => 'Are you sure you want to park ', //mitcks: cannot use regular parameters here for user full name because it exists in JS when row clicked and can't be used in trans function
            'parkConfirmPart2' => 'from ',
            'parkConfirmWaitlistText' => ' the WAITLIST for ',
            'required' => 'Please fill out all required fields.',
            'access' => 'You do not have access to edit the roster for the event on :date.',
            'alreadyEnrolled' => ':user is already enrolled or on the waitlist for <a href="/courseInstance/events/event-dashboard/:eventId/roster">:date</a>',
            'alreadyParked' => ':user is already in the PARKING LOT for :courseAbbrv.',
            'moveSuccess' => ':user was successfully moved to :date',
            'moveToParkingLotSuccess' => ':user was successfully moved to the PARKING LOT for :courseAbbrv.',
            'moveEventToEvent' => 'Are you sure you want to move :name from :fromDate to ' , //toDate has to be inserted in blade, it can't be passed in here because it's JS variable
            'moveParkingLotToEvent' => 'Are you sure you want to move :name from the PARKING LOT to ' , //toDate has to be inserted in blade, it can't be passed in here because it's JS variable
            'moveWaitListToEvent' => 'Are you sure you want to move :name from the WAITLIST on :fromDate to being <span style=color:green>ENROLLED</span> ' , //toDate has to be inserted in blade, it can't be passed in here because it's JS variable
            'select_class_wall' => 'Select Class',
            'select_class_text_wall' => 'You must select a class to move this user to.',
            'unexpected' => 'There has been a unexpected error moving this user.  Please try again.',
            'unexpectedSelfPark' => 'There has been an unexpected error adding you to the course waitlist.  Please contact :siteEmail for assistance.',
            'selfParkSuccess' => 'You were successfully added to the WAITLIST for :courseAbbrv.',
            'selfParkAlreadyParked' => 'You are already on the WAITLIST for :courseAbbrv.',
        ],

        'eventuserrequest' => [
            'enrolled' => 'You are already enrolled as a :role for the event on :date.  
                            Go to the <a href="/courseInstance/events/event-dashboard/:eventId">
                            Event Dashboard</a>.',
            'waitlist' => 'You are already on the wait list (:role) for the event on :date.',
            'unexpected' => 'There has been a unexpected error.  Please try again.',
            'success' => 'Your enrollment request for :date has been submitted.',
            'no_approvers' => 'NO ONE HAS BEEN ASSIGNED TO APPROVE THIS ENROLLMENT REQUEST',
            'auto_full' => 'The event on :date is full. Please select a different date.',
        ],

        'profile' => [
            'image' => 'The type of the uploaded file should be a supported image type.',
            'image_size' => 'Failed to upload your profile picture. The image maximum size is 2MB.'
        ],

        'emails' => [
            'edit' => 'The :label email was successfully edited.',
            'create' => 'The :label email was successfully created.',
            'delete' => 'The :label email was deleted.',
            'image_size' => 'Failed to upload your profile picture. The image maximum size is 2MB.'
        ],

        'qse' => [
            'quiz_submitted_successfully' => 'Your submission was successful!',
            'unsaved_changes_alert' => 'Please save your settings before creating/editing a new question.',
            'results_report_incorrect_no_answer_selected' => 'Incorrect - No Answer Selected',
            'results_report_incorrect' => 'Incorrect',
            'results_report_correct' => 'Correct',
            'can_not_complete' => 'The instructor has designated the :title unavailable at this time.',
            'unavailable_at_this_time' => 'The :title will be available at :date_time',
            'are_you_sure_you_want_to_delete' => 'Are you sure you want to delete this?',
            'are_you_sure_you_want_to_duplicate' => 'Are you sure you want to duplicate this?',
        ]
    ]
];
