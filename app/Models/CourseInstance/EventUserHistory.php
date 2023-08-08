<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\User;
use Carbon\Carbon;


class EventUserHistory extends Model
{

    protected $table = 'event_user_history';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'event_user_id', 'action_id', 'display_text', 'action_by', 'created_at', 'updated_at'];

    public function eventUser()
    {
        return $this->belongsTo(EventUser::class,'event_user_id');
    }

    public function eventUserHistoryAction()
    {
        return $this->belongsTo(EventUserHistoryAction::class, 'action_id');
    }

    /**
     * ACTION BY
     *
     * Return the fullname of the user who made this action
     *
     * @return string
     */
    public function getDisplayActionByAttribute()
    {
        if($this->action_by)
        {
            return User::find($this->action_by)->NameEmail;
        }

    }

    public function getDisplayActionDateAttribute()
    {
        return Carbon::parse($this->created_at)->timezone(session('timezone'))->format('m/d/y g:i A');
    }
}
