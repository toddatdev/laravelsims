<?php

namespace App\Models\Email;

use App\Models\CourseInstance\EventEmails;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class SentEmails extends Model
{
    protected $table = 'sent_email_messages';

    protected $fillable = ['site_email_id', 'course_email_id', 'event_email_id','primary_recipient', 'to', 'cc', 'bcc', 'subject', 'body', 'mailgun_id'];

    public function eventEmails(){
    	return $this->belongsTo(EventEmails::Class, 'event_email_id');
    }

    /**
     * Display the created_at datestamp in "short date time" format (
     * @author mitcks
     * @date   3/19/20
     * @return formatted date
     */
    public function getDisplayCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->timezone(session('timezone'))->format('m/d/y g:i A');
    }

	/**
	* Display the Sent Email Show (info) button
	* @author lutzjw
	* @date   3/18/20 8:49
	* @return HTML string for the View (info) button.
	*/
    public function getShowButtonAttribute()
    {
       return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                <a href="/eventEmail/show/'.$this->id.'" class="btn-sm infoButton">
                <i class="fa fa-fw fa-info"></i></a></span>&nbsp;';
    }

	/**
	* We probably don't need to group action buttons since we most likely will only show sent emails,
	*  but I'm putting it here for consistency and potential future use (a "resend" button maybe?)
	* @author lutzjw
	* @date   3/18/20 8:33
	* @return HTML text of the button(s)
	*/
    public function getActionButtonsAttribute() {
        return $this->getShowButtonAttribute();
    }

}
