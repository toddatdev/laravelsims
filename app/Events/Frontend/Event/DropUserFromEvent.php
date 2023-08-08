<?php

namespace App\Events\Frontend\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class DropUserFromEvent.
 */
class DropUserFromEvent
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;
    public $event;
    public $role;

    /**
     * @param $user
     * @param $event
     */
    public function __construct($user, $event, $role)
    {
        $this->user = $user;
        $this->event = $event;
        $this->role = $role;
    }
}