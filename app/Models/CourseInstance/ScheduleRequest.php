<?php

namespace App\Models\CourseInstance;

use App\Models\Course\CourseTemplate;
use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\Course;
use App\Models\Access\User\User;
use App\Models\Location\Location;
use App\Models\CourseInstance\ScheduleComment;
use App\Models\Access\Permission\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ScheduleRequest extends Model
{

    protected $table = 'schedule_requests';

    protected $fillable = ['id', 'requested_by', 'group_request_id', 'course_id', 'location_id', 'start_time', 'end_time', 'template_id', 'num_rooms', 'class_size', 'sims_spec_needed', 'notes', 'event_id', 'denied_by', 'denied_date'];

    //Here is the "scope" section to limit it to just this Site's schedule requests. Defined in ScheduleRequestScope.php
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ScheduleRequestScope);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    // build the comments related to this schedule request
    // $hideFirstHR is an optional parameter that hides the first HR in display
    public function comments($hideFirstHR = false)
    {
        $scheduleRequest = ScheduleRequest::where('id', '=', $this->id)->first();

        // if event_id == null - don't look for comments in the events
        if ($scheduleRequest->event_id == null) {
            $event_id = null;
            $comments = ScheduleComment::orderBy('id', 'desc')
                ->where('schedule_request_id', '=', $this->id)
                ->where('deleted_at', '=', null)
                ->get();
        } else {
            $event_id = $scheduleRequest->event_id;
            $comments = ScheduleComment::orderBy('id', 'desc')
                ->where('schedule_request_id', '=', $this->id)
                ->where('deleted_at', '=', null)
                ->orwhere('event_id', '=', $event_id)
                ->get();
        }

        $formatComments = "<div class='row'>";
        $formatComments = $formatComments.'<div class="col-md-12">';
        $formatComments = $formatComments. '<div class="timeline">';

        foreach($comments as $comment){

            $formatComments = $formatComments.'<div>';
            $formatComments = $formatComments.'<i class="fas fa-comment bg-blue"></i>';
            $formatComments = $formatComments.'<div class="timeline-item">';
            $formatComments = $formatComments.'<span class="time">'.Carbon::parse($comment->created_at)->timezone(session('timezone'))->format('m/d/y g:i A').'<i class="ml-3 mr-1 fas fa-clock"></i>' .$comment->created_at->diffForHumans() .'</span>';
            $formatComments = $formatComments.'<h3 class="timeline-header"><a href="#">'.$comment->CreatedBy() .'</a></h3>';
            $formatComments = $formatComments. '<div class="timeline-body">';
            $formatComments = $formatComments. $comment->comment;
            $formatComments = $formatComments.'</div>';
            $formatComments = $formatComments.'</div>';
            $formatComments = $formatComments.'</div>';
        }
        $formatComments = $formatComments.'</div>';
        $formatComments = $formatComments.'</div>';
        $formatComments = $formatComments.'</div>';
        return $formatComments;

    }

    public function eventResolved() {
        $event = Event::where('id', '=', $this->event_id)->first();

        if ($event == null) {
            return null;
        } else {
            return $event->resolved;
        }
    }


    /**
     * @return string
     *
     * Given the current event, this function returns a string indicating how many events there are
     * in the same course instance and in what "spot" this event is based on an order by start_time
     * for example, it will return "2 of 3" for the second event in a set of 3 events for the same
     * course instance
     */
    public function eventXofY()
    {
        $scheduleRequests = ScheduleRequest::where('group_request_id', $this->group_request_id)
            ->orderby('start_time')
            ->get();
        $i = 0;
        foreach($scheduleRequests as $request){
            $i++;
            if ($this->id == $request->id) $thisEventNum = $i;
        }

        $eventXofY = $thisEventNum . " of " . $i;

        return $eventXofY;
    }


    // status icon / hour glass if comments
    public function status()
    {
        if (!empty($this->event_id)) {
            $status = '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.approved') . '">
                        <i class="fas fa-lg fa-check"></i></span>';

        } elseif(!empty ($this->denied_date)) {
            $status = '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.denied') . '">
                        <i class="fas fa-lg fa-times"></i></span>';

        } else {
            $comments = ScheduleComment::orderBy('id', 'desc')
                ->where('schedule_request_id', '=', $this->id)
                ->where('deleted_at', '=', null)
                ->get();

            if(sizeof($comments) >= 1) {
                $status = '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.pending') . '">
                            <i class="fas fa-lg fa-hourglass"></i></span>';
            } else {
                $status = '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.pending') . '">
                            <i class="fas fa-lg fa-clock"></i></span>';
            }
        }

        return $status;
    }

    // status icon / hour glass if comments
    public function statusText()
    {
        if (!empty($this->event_id)) {
            $status = 'Approved';

        } elseif(!empty ($this->denied_date)) {
            $status = 'Denied';

        } else {
            $comments = ScheduleComment::orderBy('id', 'desc')
                ->where('schedule_request_id', '=', $this->id)
                ->where('deleted_at', '=', null)
                ->get();

            if(sizeof($comments) >= 1) {
                $status = 'Pending (comments below)';
            } else {
                $status = 'Pending';
            }
        }

        return $status;
    }


    // Sims specialist needed icon
    public function SimSpecialistNeededYN()
    {
        if ($this->sims_spec_needed == 1)
        {
            $SimSpecialistNeededYN = '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.sim_spec') . '">
                                        <i class="fas fa-lg fa-running"></i></span>';}
        else
        {
            $SimSpecialistNeededYN = "<i></i>";
        }
        return $SimSpecialistNeededYN;
    }

    // Sims specialist needed yes/no text
    public function SimSpecialistNeededText()
    {
        if ($this->sims_spec_needed == 1)
        {
            $SimSpecialistNeededYN = 'Yes';}
        else
        {
            $SimSpecialistNeededYN = "No";
        }
        return $SimSpecialistNeededYN;
    }

    /**
     * Show the Short Date (e.g. 1/9/20) for this request.
     * @author lutzjw
     * @date:   2020-02-14T14:16:02-0500
     * @return  string : The Date in m/d/yy format
     */
  public function shortDate()
    {
        return date_create($this->start_time)->format('m/d/y');
    }

    /**
    * Return the start time (e.g. 8:00am)
    * @author lutzjw
    * @date   2/14/20 14:30
    * @return string : The start time in g:ia format
    */
    public function startTime()
    {
        return date_create($this->start_time)->format('g:ia');
    }

    /**
    * Return the end time (e.g. 10:00am)
    * @author lutzjw
    * @date   2/14/20 14:33
    * @return string : The end time in g:ia format
    */
    public function endTime()
    {
        return date_create($this->end_time)->format('g:ia');
    }

    /**
    * Return the requested time (e.g. 8:00am - 10:00am)
    * @author lutzjw
    * @date   2/14/20 14:33
    * @return string : The request time in g:ia - g:ia format
    */
    public function requestTime()
    {
        return date_create($this->start_time)->format('g:ia'). ' - ' .date_create($this->end_time)->format('g:ia');
    }
  
    /**
    * Return the requested date and time (e.g. 1/9/20 8:00am - 10:00am)
    * @author lutzjw
    * @date   2/14/20 14:41
    * @return string : The request date and time in m/d/Y gia - g:ia format
    */
    public function requestDateTime()
    {
        return $this->shortDate(). " " .$this->requestTime();
    }


    /**
     * GET SCHEDULER'S EMAILS
     *
     * returns an array location scheduler emails for this request's location
     *
     * @return array
     */
    public function getSchedulersEmails()
    {
        $scheduling_emails = [];

        // get an array of schedulers emails for the location where the event is being requested
        $usersScheduling = DB::table('location_schedulers')
            ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
            ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
            ->where('location_id', $this->location_id)
            ->select('users.email')
            ->get();

        // build email array with schedulers emails
        foreach($usersScheduling as $userScheduling) {
            $scheduling_emails[] = $userScheduling->email;
        }

        return $scheduling_emails;
    }

    /**
     * DISPLAY SCHEDULER'S EMAILS
     *
     * returns a string (format = last, first (email);) of location scheduler emails for this
     * request's location (e.g., for display on deny view)
     *
     * @return string
     */
    public function getSchedulersEmailsToDisplay()
    {
        $stringEmails = "";

        // get an array of schedulers names and emails for the location where the event is being requested
        $usersScheduling = DB::table('location_schedulers')
            ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
            ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
            ->where('location_id', $this->location_id)
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->get();

        // build string with schedulers emails
        foreach($usersScheduling as $userScheduling) {
            $stringEmails .= $userScheduling->last_name . ', ' . $userScheduling->first_name . " (" . $userScheduling->email . "); ";
        }

        return $stringEmails;
    }

    /**
     * REQUESTED BY
     *
     * Given the current schedule request, return the fullname of the user who requested the event (should never by null)
     *
     * @return string
     */
    public function requestedBy()
    {
        if($this->requested_by)
        {
            return User::find($this->requested_by)->NameEmail;
        }

    }

    /**
     * DENIED BY
     *
     * Given the current schedule request, return the fullname of the user who denied the event (can be null)
     *
     * @return string
     */
    public function deniedBy()
    {
        if($this->denied_by)
        {
            return User::find($this->denied_by)->NameEmail;
        }

    }

    //SETUP MINUTES ATTRIBUTE
    //gets setup minutes from template (if one is associated with request)
    // if no template, check the course options for setup minutes
    public function getSetupMinutesAttribute()
    {
        if($this->template_id != null)
        {
            $template = CourseTemplate::find($this->template_id);
            if($template)
            {
                $setupMinutes = $template->setup_time;
            }
            else
            {
                $setupMinutes = 0;
            }
        }
        else
        {
            //this will return 0 if not set
            $setupMinutes = Course::find($this->course_id)->getCourseOption(8);
        }
        return $setupMinutes;
    }

    //TEARDOWN MINUTES ATTRIBUTE
    //gets teardown minutes from template (if one is associated with request)
    // if no template, check the course options for teardown minutes
    public function getTeardownMinutesAttribute()
    {
        if($this->template_id != null)
        {
            $template = CourseTemplate::find($this->template_id);
            if($template)
            {
                $teardownMinutes = $template->teardown_time;
            }
            else
            {
                $teardownMinutes=0;
            }
        }
        else
        {
            //this will return 0 if not set
            $teardownMinutes = Course::find($this->course_id)->getCourseOption(9);
        }
        return $teardownMinutes;
    }

    // myScheduleRequests buttons
    public function getShowCommentsButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                    <a href="#" class="btn-sm btn-primary" data-toggle="modal"
                    data-id="'.$this->id.'" data-target="#commentsModal">
                    <i class="fas fa-comment-edit fa-fw"></i></a></span>&nbsp;';
    }

    public function getEventButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '"> 
                <a data-toggle="modal" data-target="#modal" href="/event/'.$this->event_id.'" 
                class="btn-sm infoButton"><i class="fa fa-info fa-fw"></i></a></span>&nbsp;';
    }

    public function getAddEventButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.add_request_to_group') . '"> 
                <a href="/scheduleRequest/anotherEvent/'.$this->id.'"
                class="btn-sm addButton"><i class="fa fa-fw fa-plus-square"></i></a></span>&nbsp;';
    }

    public function getDuplicateEventButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.copy_request') . '"> 
                <a href="/scheduleRequest/duplicateEvent/'.$this->id.'"
                class="btn-sm duplicateButton"><i class="fa fa-fw fa-clone"></i></a></span>&nbsp;';
    }

    public function getDeleteRequestButtonAttribute()
    {
        $comments = ScheduleComment::orderBy('id', 'desc')
            ->where('schedule_request_id', '=', $this->id)
            ->where('deleted_at', '=', null)
            ->get();

        // pass the abbrv and date in data fields for the confirm message
        if(sizeof($comments) == 0 && empty($this->event_id) && empty($this->denied_date)) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '"> 
                <a href="/myScheduleRequest/delete/'.$this->id.'" name="delete" data-abbrv="'. $this->course->abbrv. '" data-date="'. \Carbon\Carbon::parse($this->start_time)->format('m/d/Y') . '"
                class="btn-sm deleteButton">
                <i class="fas fa-fw fa-trash"></i></a></span>';
        } else {
            return '';
        }
    }

    // myScheduleRequest - build action buttons group, if (view != null) hide modal view button
    public function getActionButtonsAttribute($view = null)
    {
        $buttons = '';
        if ($view == null) 
        {
            $buttons = $this->getShowCommentsButtonAttribute();
        }
// mitcks 2020-03-30 commenting this out for now, we are not doing grouping in phase 1
//      $buttons .= $this->getAddEventButtonAttribute().
        $buttons .= $this->getDuplicateEventButtonAttribute().
        $this->getDeleteRequestButtonAttribute();

        return $buttons;
    }

    
    // scheduleRequests buttons
    public function getShowButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                    <a href="#" class="btn-sm btn-primary" data-toggle="modal"
                    data-id="'.$this->id.'" data-target="#commentsModal">
                    <i class="fas fa-comment-edit fa-fw"></i></a></span>&nbsp;';
    }

    public function getAddRequestButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('navs.frontend.scheduling.add_class') . '"> 
            <a href="/courseInstance/main/fromRequest/'.$this->id.'"
            class="btn-sm addButton"><i class="fas fa-fw fa-calendar-plus addButton"></i></a></span>&nbsp;';
    }

    public function getDenyRequestButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.deny_class') . '">
                    <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                    data-id="'.$this->id.'" data-target="#denyModal">
                    <i class="fas fa-calendar-times fa-fw"></i></a></span>&nbsp;';
    }
    
    // scheduleRequests - build action buttons group, if (view != null) hide modal view button
    public function getPendingRequestActionButtonAttribute($view = null)
    {
        if ($view == null) {
            if (!empty($this->denied_date)) 
            {
                $buttons = $this->getShowButtonAttribute();
            }
                elseif (!empty($this->event_id)) 
            {
                $buttons = $this->getShowButtonAttribute();
            } else
            {
                $buttons = $this->getShowButtonAttribute().  
                    $this->getAddRequestButtonAttribute().
                    $this->getDenyRequestButtonAttribute();
                
            }
        } else {
            $buttons = $this->getAddRequestButtonAttribute().
                $this->getDenyRequestButtonAttribute();
        }

        return $buttons;
    }

    //MITCKS 2020-01-30 REPLACED THIS WITH SPECIFIC FUNCTIONS FOR REQUESTED_BY AND DENIED_BY
    // BUT STILL NEED THIS FOR THE CREATED_BY ABOVE, TRIED TO CHANGE THAT ONE AND IT CAUSED THE INFO MODAL
    // TO NOT LOAD, NO TIME TO RESEARCH RIGHT NOW
    // todo: this function should be removed, it's redundant of built in user ones
    public function getUserFirstLast($id)
    {
        $user = User::where('id',$id)->value('first_name') . ' ' . User::where('id',$id)->value('last_name');
        return $user;
    }

}
