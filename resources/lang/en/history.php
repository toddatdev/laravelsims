<?php

return [

    /*
    |--------------------------------------------------------------------------
    | History Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain strings associated to the
    | system adding lines to the history table.
    |
    */

    'backend' => [
        'none'            => 'There is no recent history.',
        'none_for_type'   => 'There is no history for this type.',
        'none_for_entity' => 'There is no history for this :entity.',
        'recent_history'  => 'Recent History',

        'roles' => [
            'created' => 'created role',
            'deleted' => 'deleted role',
            'updated' => 'updated role',
        ],
        'users' => [
            'changed_password'    => 'changed password for user',
            'created'             => 'created user',
            'deactivated'         => 'deactivated user',
            'deleted'             => 'deleted user',
            'permanently_deleted' => 'permanently deleted user',
            'updated'             => 'updated user',
            'reactivated'         => 'reactivated user',
            'restored'            => 'restored user',
        ],
    ],

    'event_user' => [
        'enrolled'  => 'Enrolled as :role',
        'removed'   => 'Removed from :role role',
        'moved'     => 'Moved from :old_event to :new_event',
        'request_access'  => 'Request Access as :role',

    ],
];
