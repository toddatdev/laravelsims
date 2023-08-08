<?php

namespace App\Events\Frontend\Course;
use Illuminate\Queue\SerializesModels;

/**
 * Class DropUserFromCourse.
 */
class DropUserFromCourse
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;
    public $course;
    public $role;

    /**
     * @param $user
     * @param $course
     */
    public function __construct($user, $course, $role)
    {
        $this->user = $user;
        $this->course = $course;
        $this->role = $role;
    }
}