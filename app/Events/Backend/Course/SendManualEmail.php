<?php

namespace App\Events\Backend\Course;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendManualEmail.
 */
class SendManualEmail
{
    use SerializesModels;

    /**
     * @var
     * Assuming this will be a large sum of users that the email should get sent to.
     */
    public $email;

    /**
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }
}