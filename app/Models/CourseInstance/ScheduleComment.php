<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseInstance\ScheduleRequest;
use App\Models\Access\User\User;


class ScheduleComment extends Model
{
    protected $table = 'schedule_comments';
    protected $fillable = ['id', 'schedule_request_id', 'event_id', 'comment', 'created_by', 'last_edited_by', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * CREATED BY
     *
     * Given the current event, return the fullname of the user who created the event (should never be null)
     *
     * @return string
     */
    public function createdBy()
    {
        if($this->created_by)
        {
            return User::find($this->created_by)->NameEmail;
        }

    }

}