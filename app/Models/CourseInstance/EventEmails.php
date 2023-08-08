<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CourseInstance\Event;
use App\Models\Email\SentEmails;
use App\Models\Site\SiteEmailTypes;


class EventEmails extends Model
{
    use SoftDeletes;
    protected $table = 'event_emails';

    protected $fillable = ['event_id', 'email_type_id', 'course_email_id','label', 'subject', 'body', 'created_by', 'last_edited_by', 'to_roles', 'to_other', 'cc_roles', 'cc_other', 'bcc_roles', 'bcc_other','time_amount', 'time_type', 'time_offset', 'role_id', 'role_amount', 'role_offset', 'send_at', 'edited'];



    public function event() {
        return $this->belongsTo(Event::class);
    }

    // Allow API to use forwards model relationship
    public function eventToTouch() {
        return $this->event()->whereNull('deleted_at')->withoutGlobalScopes();
     }

    public function emailType() {
        return $this->hasOne(SiteEmailTypes::class, 'id', 'email_type_id');
    }

    public function checkEmailType() {
        return $this->type;
    }

    public function sentEmails() {
        return $this->hasMany(SentEmails::class, 'event_email_id');
    }

    /**
     * @desc - return the to_roles as associative array
     * @return (array) roles 
     */
    public function toRoles() {
        return explode(',', $this->to_roles);
    }

    // Data Table Buttons
    public function getEditButtonAttribute() {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                <a href="/courseInstance/events/event-dashboard/'.$this->event_id.'/email/edit/'.$this->id.'" class="btn-sm editButton" 
                id="edit"><i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
    }

    public function getDeleteButtonAttribute() {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                <a href="/courseInstance/events/event-dashboard/'.$this->event_id.'/email/delete/'.$this->id.'" 
                data-trans-button-confirm="'.trans('buttons.backend.siteEmails.delete').'" 
                data-trans-title="'.trans('labels.siteEmails.delete_wall').'" name="email_delete" 
                class="btn-sm deleteButton"><i class="fa fa-trash fa-fw"></i></a></span>&nbsp;';
    }

    public function getCloneButtonAttribute() {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.duplicate') . '">
                <a href="/courseInstance/events/event-dashboard/'.$this->event_id.'/email/clone/'.$this->id.'"
                class="btn-sm duplicateButton"><i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
    }

    public function getActionButtonsAttribute() {
        return $this->getEditButtonAttribute().
            $this->getCloneButtonAttribute().
            $this->getDeleteButtonAttribute();
    }

}
