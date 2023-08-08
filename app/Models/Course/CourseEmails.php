<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Course\Course;
use App\Models\Site\SiteEmailTypes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CourseEmails extends Model
{
    use SoftDeletes;
    protected $table = 'course_emails';

    protected $fillable = ['course_id', 'email_type_id', 'site_email_id','label', 'subject', 'body', 'created_by', 'last_edited_by', 'to_roles', 'to_other', 'cc_roles', 'cc_other', 'bcc_roles', 'bcc_other','time_amount', 'time_type', 'time_offset', 'role_id', 'role_amount', 'role_offset', 'edited'];

    // protected static function boot() {
    //     parent::boot();

    //     static::addGlobalScope('course_id', function (Builder $builder) {
    //         $builder->where('course_emails.course_id', '=', Session::get('site_id'));
    //     });
    // }

    public function course() {
        return $this->belongsTo(Course::class); 
    }

    public function emailType() {
        return $this->hasOne(SiteEmailTypes::class, 'id', 'email_type_id');
    }

    public function checkEmailType() {
        return $this->type;
    }

    public function emailTypeName() {
        return $this->name;
    }
    
    // Buttons
	public function getEditButtonAttribute() {
        if ($this->checkEmailType() == 2) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                    <a href="/courses/courseInstanceEmails/'.$this->id.'/edit/2" class="btn-sm editButton" 
                    id="edit"><i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
        }
        elseif ($this->checkEmailType() == 3) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                    <a href="/courses/courseInstanceEmails/'.$this->id.'/edit/3" class="btn-sm editButton" 
                    id="edit"><i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
        }

    }

    public function getDeleteButtonAttribute() {    
        if ($this->checkEmailType() == 2) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                    <a href="/courses/courseInstanceEmails/delete/'.$this->id.'?type='.$this->type.'" 
                    data-trans-button-confirm="'.trans('buttons.backend.siteEmails.delete').'" 
                    data-trans-title="'.trans('labels.siteEmails.delete_wall').'" name="warning_item" 
                    class="btn-sm deleteButton"><i class="fa fa-trash fa-fw"></i></a></span>&nbsp;';
        }else {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                    <a href="/courses/courseInstanceEmails/delete/'.$this->id.'?type='.$this->type.'" 
                    class="btn-sm deleteButton"><i class="fa fa-trash fa-fw"></i></a></span>&nbsp;';
        }
    }

    public function getCloneButtonAttribute() {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.duplicate') . '">
                <a href="/courses/courseInstanceEmails/clone/'.$this->id.'?type='.$this->type.'"
                class="btn-sm duplicateButton"><i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
    }

    public function getManualSendButtonAttribute() {

        if($this->emailTypeName() == 'Send Manually')
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.send_now') . '">
                    <a href="/courses/courseInstanceEmails/manual-send/'.$this->id.'" class="btn-sm siteSendManButton">
                    <i class="fa fa-paper-plane fa-fw"></i></a></span>&nbsp;';
        }
    }

    public function getActionButtonsAttribute() {
        return $this->getEditButtonAttribute().
            $this->getCloneButtonAttribute().
            $this->getManualSendButtonAttribute().
            $this->getDeleteButtonAttribute();
	}
}
