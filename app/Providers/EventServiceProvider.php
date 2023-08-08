<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


/**
 * Class EventServiceProvider.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // \App\Events\Frontend\Auth\UserConfirmed::class => [
        //     \App\Listeners\Frontend\Auth\SendUserCreatedEmail::class
        // ],
        \App\Events\Frontend\Course\UserAddedToCourse::class => [
            \App\Listeners\Frontend\Course\SendUserAddedToCourseEmail::class
        ],
        \App\Events\Frontend\Course\DropUserFromCourse::class => [
            \App\Listeners\Frontend\Course\SendDropUserFromCourseEmail::class
        ],
        \App\Events\Frontend\Event\UserAddedToEvent::class => [
            \App\Listeners\Frontend\Event\SendUserAddedToEventEmail::class
        ],
        \App\Events\Frontend\Event\DropUserFromEvent::class => [
            \App\Listeners\Frontend\Event\SendDropUserFromEventEmail::class
        ],
        \App\Events\Backend\Site\SendManualEmail::class => [
            \App\Listeners\Backend\Site\SendEmailManually::class
        ],
        \App\Events\Backend\Course\SendManualEmail::class => [
            \App\Listeners\Backend\Course\SendEmailManually::class
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            '\App\Listeners\Emails\MessageWasSent'
        ],
        // link more Listeners here ... 
    ];

    /**
     * Class event subscribers.
     *
     * @var array
     */
    protected $subscribe = [
        /*
         * Frontend Subscribers
         */

        /*
         * Auth Subscribers
         */
        \App\Listeners\Frontend\Auth\UserEventListener::class,

        /*
         * Backend Subscribers
         */

        /*
         * Access Subscribers
         */
        \App\Listeners\Backend\Access\User\UserEventListener::class,
        \App\Listeners\Backend\Access\Role\RoleEventListener::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
