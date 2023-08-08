<?php

namespace App\Models\CourseInstance;
use App\Models\Access\User\User;
use App\Models\CourseContent\QSE\EventUserQSE;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\Role\Role;
use Carbon\Carbon;
use Session;


class EventUser extends Model
{
    protected $table = 'event_user';

    use SoftDeletes;

    protected $fillable = ['id', 'user_id', 'role_id', 'event_id', 'status_id', 'attend_date', 'who_marked_attend', 'request_notes', 'created_at', 'updated_at', 'created_by', 'last_edited_by'];

    protected static function boot()
    {
        parent::boot();

        //scope to select only event_user records related to course->course_instance->event for this site
        // (to avoid calling Event model attributes that it cannot find and getting not found error)
        // mitcks: I think there has to be a better way to do this, but no luck so far
        static::addGlobalScope('thisSite', function (Builder $builder) {
            $builder->whereHas('event', function($query){
                $query->join('course_instances', 'course_instances.id', '=', 'events.course_instance_id')
                      ->join('courses', 'courses.id', '=', 'course_instances.course_id')
                    ->where('courses.site_id', SESSION::get('site_id'));
            });
        });

    }

    public function scopeEnrolled($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeWaitlist($query)
    {
        return $query->whereIn('status_id', [3,2]);
    }

    public function scopePendingPayment($query)
    {
        return $query->whereIn('status_id', [5]);
    }

    public function scopeMyWaitlistRequests($query)
    {
        //get all the event_user records with a waitlist status (note these should all be active because Global soft delete scope applied)
        $waitlistRequests = $this->waitlist()->get();

        //array to store the event_user.id for records they can approve/deny
        $eventUserIdArray = [];

        //loop through and see if they have *add-people-to-events permission
        foreach ($waitlistRequests as $waitlistRequest)
        {
            if ($waitlistRequest->event->hasSiteCourseEventPermissions('add-people-to-events',
                'course-add-people-to-events',
                'event-add-people-to-events'))
            {
                $eventUserIdArray[] =  $waitlistRequest->id;
            }
        }

        //return the records they can approve/deny
        return $query->whereIn('id', $eventUserIdArray);

    }

    public function eventUserHistory()
    {
        return $this->hasMany(EventUserHistory::class, 'event_user_id', 'id');
    }

    public function eventUserPayment()
    {
        return $this->hasOne(EventUserPayment::class, 'event_user_id', 'id');
    }

    public function eventUserQSE()
    {
        return $this->hasMany(EventUserQSE::class, 'event_user_id', 'id');
    }

    public function eventUserQSEMostRecentComplete()
    {
        return $this->hasMany(EventUserQSE::class, 'event_user_id', 'id')
            ->where('complete', 1)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function event()
    {
        return $this->belongsTo(Event::class,'event_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



    /**
     * Created_at date for display in Friday, January 10, 2020 format
     *
     * @return string
     */
    public function getDisplayCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->timezone(session('timezone'))->format('m/d/y g:i A');
    }

    public function getViewHistoryButtonAttribute()
    {
        if (access()->user() != null) {
            if ($this->event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events'])) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.view_history') . '">
                    <button class="btn btn-sm btn-default" data-toggle="modal"
                    data-id="' . $this->id . '" data-target="#historyModal">
                    <i class="fa fa-history fa-fw"></i></button></span>&nbsp;';
            }
            else
            {
                //disable button unless they have *add-people-to-events permission
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.view_history') . '">
                    <button class="btn btn-sm btn-default" data-toggle="modal"
                    data-id="' . $this->id . '" data-target="#historyModal" disabled>
                    <i class="fa fa-history fa-fw"></i></button></span>&nbsp;';
            }
        }
    }

    public function getApproveButtonAttribute($pageFrom)
    {
        if (!$this->event->isParkingLot()) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.add') . '">
                    <a href="/event/users/approve/' . $this->id . '/' . $pageFrom . '" name="approve" class="btn btn-sm btn-success">
                    <i class="fa fa-user-plus fa-fw"></i></a></span>&nbsp;';
        }
    }

    public function getMoveButtonAttribute($tab=null)
    {
        if (access()->user() != null) {
            if ($this->event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events'])) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.move') . '">
                <a href="/event/users/move/'.$this->id.'/'.$tab.'" name="move" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i></a></span>&nbsp;';
            }
            else
            {
                //disable button unless they have *add-people-to-events permission
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.move') . '">
                    <button class="btn btn-sm btn-primary disabled">
                    <i class="fa fa-fw fa-external-link-alt"></i></button></span>&nbsp;';
            }
        }
    }

    public function getParkButtonAttribute($pageFrom)
    {
        //data-waitlisttext is null below because this is called from roster
        if(!$this->event->isParkingLot())
        {
            if (access()->user() != null) {
                if ($this->event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events'])) {
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.park') . '">
                    <a href="/event/users/park/'.$this->id.'/'.$pageFrom.'" 
                    name="park_button" id="park_button" 
                    data-fullname="'. $this->user->name. '"
                    data-eventdate="'. $this->event->DisplayEventNameShort. '"
                    data-waitlisttext="" 
                    class="btn-sm btn orangeButton">
                    <i class="fas fa-lg fa-parking-circle"></i></a></span>&nbsp;';
                }
                else
                {
                    //disable button unless they have *add-people-to-events permission
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.park') . '">
                    <button class="btn btn-sm orangeButton disabled">
                    <i class="fas fa-lg fa-parking-circle"></i></button></span>&nbsp;';
                }
            }
        }
    }

    public function getParkWaitlistButtonAttribute($pageFrom)
    {
        if(!$this->event->isParkingLot())
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.park') . '">
                <a href="/event/users/park/'.$this->id.'/'.$pageFrom.'" 
                name="park_button" id="park_button" 
                data-fullname="'. $this->user->name. '"
                data-eventdate="'. $this->event->DisplayEventNameShort. '"
                data-waitlisttext="'. trans('alerts.frontend.eventusers.parkConfirmWaitlistText') .'"
                class="btn-sm btn orangeButton">
                <i class="fas fa-lg fa-parking-circle"></i></a></span>&nbsp;';
        }
    }

    public function getDeleteRosterButtonAttribute($tab=null)
    {
        if (access()->user() != null) {
            if ($this->event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events'])) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                        <a href="/event/users/delete/'.$this->id.'/'.$tab.'" 
                        name="delete_roster" id="delete_roster" 
                        data-fullname="'. $this->user->name. '"
                        data-eventdate="'. $this->event->DisplayEventNameShort. '"
                        class="btn-sm btn btn-danger">
                        <i class="fa fa-user-minus fa-fw"></i></a></span>&nbsp;';
            }
            else
            {
                //disable button unless they have *add-people-to-events permission
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                    <button class="btn btn-sm btn-danger disabled">
                    <i class="fa fa-user-minus fa-fw"></i></button></span>&nbsp;';
            }
        }
    }

    public function getDeleteWaitlistButtonAttribute($tab=null)
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                <a href="/event/users/delete/'.$this->id.'/'.$tab.'" 
                name="delete_wailist" id="delete_wailist" 
                data-fullname="'. $this->user->name. '"
                data-eventdate="'. $this->event->DisplayEventNameShort. '"
                class="btn-sm btn deleteButton">
                <i class="fa fa-user-minus fa-fw"></i></a></span>&nbsp;';
    }

    // Build all the buttons for the Roster tab action column
    public function getRosterActionButtonsAttribute()
    {
        return  $this->getViewHistoryButtonAttribute().
                $this->getMoveButtonAttribute('roster').
                $this->getParkButtonAttribute('roster').
                $this->getDeleteRosterButtonAttribute('roster');
    }

    // Build all the buttons for the Waitlist tab action column
    public function getWaitlistActionButtonsAttribute()
    {
        return  $this->getViewHistoryButtonAttribute().
                $this->getApproveButtonAttribute('eventDashboard').
                $this->getMoveButtonAttribute('waitlist').
                $this->getParkWaitlistButtonAttribute('waitlist').
                $this->getDeleteWaitlistButtonAttribute('waitlist');
    }

    // Build all the buttons for the My Courses Waitlist action column
    public function getMyCoursesWaitlistActionButtonsAttribute()
    {
        return  $this->getViewHistoryButtonAttribute().
                $this->getApproveButtonAttribute('myCoursesWaitList').
                $this->getMoveButtonAttribute('mycourses').
                $this->getParkButtonAttribute('myCoursesWaitList').
                $this->getDeleteWaitlistButtonAttribute('mycourses');
    }
}