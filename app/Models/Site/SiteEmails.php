<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Site\Site;
use App\Models\Site\SiteEmailTypes;
use Session;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteEmails extends Model
{
    use SoftDeletes;
    protected $table = 'site_emails';

    protected $fillable = ['site_id', 'email_type_id', 'label', 'subject', 'body', 'created_by', 'last_edited_by', 'to_roles', 'to_other', 'cc_roles', 'cc_other', 'bcc_roles', 'bcc_other', 'time_amount', 'time_type', 'time_offset', 'role_id', 'role_amount', 'role_offset'];

    protected static function boot() {        
        parent::boot();

        static::addGlobalScope('site_id', function (Builder $builder) {
            $builder->where('site_emails.site_id', '=', Session::get('site_id'));
        });
    }

    public function site() {
        return $this->belongsTo(Site::class);
    }

    public function emailType() {
        return $this->hasOne(SiteEmailTypes::class, 'id', 'email_type_id');
    }

    // Finds the correct email_types.type to build the edit button link
    public function checkEmailType() {
        return $this->type;
    }

    public function emailTypeName() {
        return $this->name;
    }
   
    // Buttons

	public function getEditButtonAttribute() {
        if ($this->checkEmailType() == 1) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                    <a href="/admin/site/emails/'.$this->id.'/edit/1" class="btn-sm editButton">
                    <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
        }
        elseif ($this->checkEmailType() == 2) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                    <a href="/admin/site/emails/'.$this->id.'/edit/2" class="btn-sm editButton">
                    <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
        }
        elseif ($this->checkEmailType() == 3) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                    <a href="/admin/site/emails/'.$this->id.'/edit/3" class="btn-sm editButton">
                    <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
        }
    }

    public function getDeleteButtonAttribute() {
        if ($this->checkEmailType() == 1) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                    <a href="/admin/site/emails/delete/'.$this->id.'?type='.$this->type.'" 
                    data-trans-button-confirm="'.trans('buttons.backend.siteEmails.delete').'" 
                    data-trans-title="'.trans('labels.siteEmails.delete_wall').'" name="warning_item" 
                    class="btn-sm deleteButton"><i class="fa fa-trash fa-fw"></i></a></span>&nbsp;';
        }else {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                    <a href="/admin/site/emails/delete/'.$this->id.'?type='.$this->type.'" 
                    class="btn-sm deleteButton"><i class="fa fa-trash fa-fw"></i></a></span>&nbsp;';
        }
    }

    public function getCloneButtonAttribute() {    
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.duplicate') . '">
                <a href="/admin/site/emails/clone/'.$this->id.'?type='.$this->type.'"
                        class="btn-sm duplicateButton"><i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
    }
    

    public function getSendManuallyButtonAttribute() {
        if($this->checkEmailType() == 1) {
            if($this->emailTypeName() == 'Send Manually') {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.send_now') . '">
                        <a href="/admin/site/emails/send/'.$this->id.'" class="btn-sm siteSendManButton">
                        <i class="fa fa-paper-plane fa-fw"></i></a></span>&nbsp;';
            }
        }
    }

    public function getActionButtonsAttribute() {
        return $this->getEditButtonAttribute().
               $this->getCloneButtonAttribute().
               $this->getSendManuallyButtonAttribute().
               $this->getDeleteButtonAttribute();
	}
}