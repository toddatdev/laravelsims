<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\User;
use App\Models\Access\Role\Role;

class EventRequest extends Model {

    use SoftDeletes;

    protected $table = 'event_user_requests';

    protected $fillable = ['event_id', 'role_id', 'user_id','comments'];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }
    

    // Will added the requestee to the event
    public function getAddButtonAttribute() {
        return '<a href="/pending/add/'.$this->id.'" 
                data-trans-button-confirm="Add" 
                data-trans-title="Add '. $this->user->first_name. ' '. $this->user->last_name. '" name="confirm_item" 
                class="btn-sm addButton"><i class="fa fa-plus-circle fa-fw" data-toggle="tooltip" 
                data-placement="top" title="'.trans('buttons.general.crud.add').'"></i></a> ';        
    }

    // Will put requestee on waitlist
    public function getWaitlistButtonAttribute() {
        return '<a href="/pending/waitlist/'.$this->id.'"
                data-trans-button-confirm="Waitlist" 
                data-trans-title="Waitlist '. $this->user->first_name. ' '. $this->user->last_name. '" name="confirm_item" 
                class="btn-sm waitlistButton"><i class="fa fa-clock fa-fw" data-toggle="tooltip" 
                data-placement="top" title="'.trans('buttons.general.crud.waitlist').'"></i></a> ';        
    }
    
    // will remove requestee from pending
    public function getDeleteButtonAttribute() {
        return '<a href="/pending/deny/'.$this->id.'"
                data-trans-button-confirm="Delete" 
                data-trans-title="Deny '. $this->user->first_name. ' '. $this->user->last_name. '" name="warning_item" 
                class="btn-sm deleteButton"><i class="fa fa-trash fa-fw" data-toggle="tooltip" 
                data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
    }

    /**
     * Create Sweetalert showing requestee comments if they made one
     */
    public function comments() {
        if ($this->comments) {
            return '<a data-trans-text="'.$this->comments.'" name="info_item" 
                    class="btn-sm commentsButton"><i class="fa fa-comment-dots fa-fw" data-toggle="tooltip" 
                    data-placement="top" title="'.trans('buttons.general.crud.comments').'"></i></a> ';
        }
    }
    
    // Bundle Action Button attribs
    public function getActionButtonsAttribute() {
        return $this->getAddButtonAttribute().
            $this->getWaitlistButtonAttribute().
            $this->getDeleteButtonAttribute();
    }
}