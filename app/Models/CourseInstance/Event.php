<?php

namespace App\Models\CourseInstance;

use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\QSE\QSE;
use App\models\location\LocationSchedulers;
use App\Models\Resource\ResourceType;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Resource\Resource;
use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Models\Access\Permission\Permission;
use App\Models\Course\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\Role\Role;
use Session;


class Event extends Model
{
    protected $table = 'events';

    use SoftDeletes;

    protected $fillable = ['id', 'course_instance_id', 'abbrv', 'public_comments', 'internal_comments',
        'start_time', 'end_time', 'setup_time', 'teardown_time', 'class_size',
        'fac_report', 'fac_leave', 'initial_meeting_room', 'color', 'sims_spec_needed', 'special_requirements',
        'status_type_id', 'resolved', 'parking_lot', 'created_by', 'last_edited_by', 'updated_at'];


    protected static function boot()
    {
        parent::boot();

        //scope to only select events for the current site
        static::addGlobalScope(new EventScope);

    }

    public function eventResources()
    {
        return $this->hasMany(EventResource::class);
    }

    public function eventStatusType()
    {
        return $this->belongsTo(EventStatusType::class,'status_type_id','id');
    }

    public function scheduleRequest()
    {
        return $this->hasOne(ScheduleRequest::class, 'event_id');
    }

    public function CourseInstance()
    {
        return $this->belongsTo(CourseInstance::class);
    }

    //This should be used when you're trying to get related information for record filtered out by global scopes,
    // such as soft delete, used for the deleted events page for exanple
    public function CourseInstanceTrashed()
    {
        return $this->CourseInstance()->withoutGlobalScopes();
    }

    // Allow API to use forwards model relationship
    public function CourseInstanceToTouch() {
        return $this->CourseInstance()->whereNull('deleted_at')->withoutGlobalScopes();
     }

    public function eventUsers()
    {
        return $this->hasMany(EventUser::class);
    }

    public function eventRequest()
    {
        return $this->hasMany(EventRequest::class, 'event_id');
    }

    /**
     * One to Many Relationship to Resources to Get the Initial Meeting Room Abbrv
     */
    public function InitialMeetingRoom()
    {
        return $this->belongsTo(Resource::class, 'initial_meeting_room');
    }

    public function eventEmails() {
        return $this->hasMany(EventEmails::class, 'event_id');
    }

    /**
     * IS SCHEDULER FOR EVENT LOCATION
     *
     * returns true if the logged in user is a scheduler for the IMR location associated with this event
     *
     * @return true or false
     */
    //returns true if the logged in user is a scheduler for the IMR location associated with this event
    public function IsSchedulerForLocation()
    {
        $user = Auth::user();

        if($user) {
            $allowedLocationsForUser = LocationSchedulers::where('location_id', $this->initialMeetingRoom->location->id)
                ->where('user_id', $user->id)->first();
        }

        if($allowedLocationsForUser){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * VIEW INTERNAL COMMENTS
     *
     * returns the internal comments if user has permission
     *
     * @return string
     */
    public function viewInternalComments()
    {
        if ($this->hasSiteCourseEventPermissions(['scheduling','event-details'], [], [])) {
            return $this->internal_comments;
        }
        else {
            return null;
        }
    }

    /**
     * IS PARKING LOT
     *
     * returns true if this is a parking lot event
     *
     * @return true or false
     */
    public function isParkingLot()
    {
        if($this->parking_lot == 1){
            return true;
        }
        else {
            return false;
        }
    }


    // build the comments related to this event_id
    public function comments()
    {
        $scheduleRequest = ScheduleRequest::where('event_id', '=', $this->id)->first();

        if ($scheduleRequest == null) {
            $schedule_request_id = null;
            $comments = ScheduleComment::orderBy('id', 'desc')
                ->where('event_id', '=', $this->id)
                ->where('deleted_at', '=', null)
                ->get();
        } else {
            $schedule_request_id = $scheduleRequest->id;
            $comments = ScheduleComment::orderBy('id', 'desc')
                ->where('schedule_request_id', '=', $schedule_request_id)
                ->where('deleted_at', '=', null)
                ->orwhere('event_id', '=', $this->id)
                ->get();
        }

        // $current_user = Auth::user(); // (for delete below)

        $formatComments = "<div class='row'>";
        $formatComments = $formatComments.'<div class="col-md-12">';
        $formatComments = $formatComments. '<div class="timeline">';

        foreach($comments as $comment){
//
//            /* delete feature commented out, add $delete to string below
//            $delete = '';
//            if($comment->created_by == $current_user->id) {
//                $delete = '<a href="/scheduleRequest/comment/delete/'.$comment->id.'"><i class="fa fa-trash comment-trash" aria-hidden="true"></i></a>';
//            }
//            */
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

    /**
     * REQUESTED BY
     *
     * Given the current event, return the fullname of the user who requested the event (it is possible for this to be null if event not created from request)
     *
     * @param string $idOnly, optional parameter, pass true if you want to return the user's ID instead of the name
     *
     * @return string
     */
    public function requestedBy($idOnly = false)
    {
        $scheduleRequest = ScheduleRequest::where('event_id', '=', $this->id)->first();
        if ($scheduleRequest == null) {
            return null;
        } else {
            if ($idOnly == true) {
                return $scheduleRequest->requested_by;
            } else {
                return User::find($scheduleRequest->requested_by)->name;
            }
        }
    }

    //mitcks 2019-10-21 researched this function Joel originally created to be sure it is needed, I updated it to use the scheduleRequest relationship,
    // but found we still need this function to return null when no request exists for the event (this occurs when events are no created via a schedule request)
    public function scheduleRequestId()
    {
        if($this->scheduleRequest()->exists()){
            return $this->scheduleRequest->id;
        }
        else
        {
            return null;
        }
    }

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

    /**
     * LAST EDITED BY
     *
     * Given the current event, return the fullname of the user who last edited the event (should never by null)
     *
     * @return string
     */
    public function editedBy()
    {
        if($this->last_edited_by)
        {
            return User::find($this->last_edited_by)->NameEmail;
        }

    }


    //mitcks todo: this is a strange function - figure out where it is used and why?
    // Get List of course ids user with course-add-event-comment has
    public static function getCourseIds($id) {
        // 
        $arr = [];
        $courseIdList = Permission::select('role_user.course_id')
            ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id' )
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('permissions.name', 'course-add-event-comment')
            ->where('role_user.user_id', $id)
            ->get();
        foreach ($courseIdList as $c) {
            array_push($arr, $c->course_id);
        }

        return $arr;
    }

    /**
     * EVENT X OF Y (displayed when more than one event per course instance)
     *
     * Given the current event, this function returns a string indicating how many events there are
     * in the same course instance and in what "spot" this event is based on an order by start_time
     * for example, it will return "2 of 3" for the second event in a set of 3 events for the same
     * course instance
     *
     * @return string
     */
    public function eventXofY()
    {
        $courseInstanceEvents = Event::where('course_instance_id', $this->course_instance_id)
            ->orderby('start_time')
            ->get();
        $i = 0;
        foreach($courseInstanceEvents as $event){
            $i++;
            if ($this->id == $event->id) $thisEventNum = $i;
        }

        $eventXofY = $thisEventNum . " of " . $i;

        return $eventXofY;
    }

    /**
     * EMAILS BY ROLE
     *
     * Given the current event, return a comma separated string of emails for eventUsers in a learner role
     * if no role_id parameter is passed in, return all eventUsers for this event
     *
     * @return string
     */
    public function emailsByRoleAndStatus($roleId=0, $statusId)
    {
        $strEmails = "";

        if ($roleId == 0)
        {
            $eventUsers = $this->eventUsers()->get()->where('status_id', $statusId);
        }
        else
        {
            $eventUsers = $this->eventUsers()
                ->where('role_id', $roleId)
                ->where('status_id', $statusId)
                ->get();
        }

        if($eventUsers)
        {
            $i = 0;
            foreach($eventUsers as $eventUser){
                $i++;
                if ($i == 1)
                {
                    $strEmails = $eventUser->user->email;
                }
                else
                {
                    $strEmails = $strEmails . "; " . $eventUser->user->email;
                }
            }
        }

        return $strEmails;
    }

    /**
     * NUMBER OF LEARNERS ENROLLED
     *
     * Given the current event, return a number of event_users in a learner role
     *
     * @return integer
     */
    public function numLearnersEnrolled()
    {
        $roles = Role::where(['learner' => 1])->pluck('id');

        $numLearners = $this->eventUsers()
            ->whereIn('role_id', $roles)
            ->where('status_id', 1)
            ->count();

        return $numLearners;
    }

    /**
     * NUMBER OF LEARNERS WAITLISTED
     *
     * Given the current event, return a number of event_users waitlisted for a learner role
     *
     * @return integer
     */
    public function numLearnersWaitlisted()
    {
        $roles = Role::where(['learner' => 1])->pluck('id');

        $numLearnersWaitlisted = $this->eventUsers()
            ->whereIn('role_id', $roles)
            ->where('status_id', 3)
            ->count();

        return $numLearnersWaitlisted;
    }


    /**
     * NUMBER BY ROLE and STATUS
     *
     * Given the current event and role_id, return a number of event_users in a role
     *
     * @return integer
     */
    public function numByRoleAndStatus($roleId, $statusId)
    {

        $numByRoleAndStatus = $this->eventUsers()
            ->where('role_id', $roleId)
            ->where('status_id', $statusId)
            ->count();

        return $numByRoleAndStatus;
    }

    /**
     * IS FULL
     *
     * Given the current event, return true if the number of learners enrolled is >= class_size
     *
     * @return boolean
     */
    public function isFull()
    {
        if ($this->numLearnersEnrolled() >= $this->class_size)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * IS ENROLLED
     *
     * Given a user_id, return true if they are actively enrolled in the event
     * mitcks: note that this is here rather than in eventUsers model because it's a
     *  1->many relationship and that returns "many" records and not really what we want here
     *
     * @param string $userId, optional parameter, defaults to logged in user if nothing passed
     *
     * @return boolean
     */
    public function isEnrolled($userId=0)
    {
        //if no user id has been passed in and they are logged in then set to logged in user
        if ($userId == 0 and access()->user()) {
            $userId = access()->user()->id;
        }


        //actively enrolled (not soft deleted)
        $enrolledUser = EventUser::where('event_id', $this->id)
            ->where('user_id', $userId)
            ->where('status_id', 1)
            ->first();

        if ($enrolledUser) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * IS WAITLIST
     *
     * Given a user_id, return true if they are actively waitlisted for the event
     *
     * @param string $userId, optional parameter, defaults to logged in user if nothing passed
     *
     * @return boolean
     */
    public function isWaitlisted($userId=0)
    {
        //if no user id has been passed in and they are logged in then set to logged in user
        if ($userId == 0 and access()->user()) {
            $userId = access()->user()->id;
        }

        //actively waitlisted (not soft deleted)
        $waitlistedUser = EventUser::where('event_id', $this->id)
            ->where('user_id', $userId)
            ->where('status_id', 3)
            ->first();

        if ($waitlistedUser) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * HAS RESOURCE CONFLICT
     *
     * check to see if any resource conflicts exist for this event, return true if at least 1 conflict exists
     * the logic for finding overlaps came from https://www.soliantconsulting.com/blog/determining-two-date-ranges-overlap/
     * If ( NOT (EndA <= StartB or StartA >= EndB) ; “Overlap”)
     *
     * @return boolean
     * @author mitcks
     * @created 2020-07-24
     */
    public function hasResourceConflict()
    {
        //get resources for this event
        $eventResources = EventResource::where('event_id', $this->id)->get();
        //dd($eventResources);

        foreach($eventResources as $eventResource){

            //start time less any setup time
            $startTime = $eventResource->StartTimeLessSetup;
            //end time plus any teardown time
            $endTime = $eventResource->EndTimePlusTeardown;

            //dd('$startTime:'. $startTime . ' $endTime:'.$endTime );

            //2020-07-27 mitcks - the whereRaw is necessary below because Laravel eloquent cannot do a whereNot
            // followed by a nested or, also needs the whereHas to connect to Events in order to not include
            // event_resources for a deleted event (currently event_resources are NOT being soft deleted when the event
            // is soft deleted)
            $eventResource = EventResource::where('event_id', '!=', $this->id)
                ->where('resource_id', $eventResource->resource_id)
                ->whereDate('start_time', '=', Carbon::parse($this->start_time)->toDateString())
                ->whereRaw( "not(date_add(`end_time`, INTERVAL teardown_time MINUTE) <= '$startTime' 
                    or date_add(`start_time`, INTERVAL - setup_time MINUTE) >= '$endTime')")
                ->whereHas('Event')
                ->get();

            //dd($this->id, $startTime, $endTime, $eventResource->toSql(), $eventResource->getBindings());
            //dd($eventResource);

            if($eventResource->count()>0)
            {
                return true; //return true and return, no need to look for other conflicts
            }
        }

        return false;
    }


    /**
     * EVENT ROOMS
     *
     * Given the current event, return a string of the event rooms in alpha order by abbrv
     * separated by commas
     *
     * @return string
     * TODO: this function can be removed once all references changed to use getResources below
     */
    public function eventRooms()
    {

        $resources = Resource::whereHas('eventResources', function($query) {
                        $query->where('event_id', $this->id);
                    })
            ->where('resource_type_id', 1)
            ->orderBy('abbrv')->get();

        $i = 0;
        $eventRooms = "";
        foreach($resources as $eventRoom){
            $i++;
            if ($i == 1)
            {
                $eventRooms = $eventRoom->abbrv;
            }
            else
            {
                $eventRooms = $eventRooms . ", " . $eventRoom->abbrv;
            }
        }
        return $eventRooms;
    }

    /**
     * Event Resources for Display
     *
     * @param int $typeId - defaults to 1=room, other possible values 2=equipment, 3=Personnel
     * @return string
     */
    public function getResources($typeId = 1)
    {
        $resources = Resource::whereHas('eventResources', function($query) {
            $query->where('event_id', $this->id);
        })
            ->where('resource_type_id', $typeId)
            ->orderBy('abbrv')->get();

        $i = 0;
        $eventResources = "";
        foreach($resources as $eventResource){
            $i++;
            if ($i == 1)
            {
                $eventResources = $eventResource->abbrv;
            }
            else
            {
                $eventResources = $eventResources . ", " . $eventResource->abbrv;
            }
        }
        return $eventResources;

    }


    /**
     * SIMS SPECIALIST NEEDED IMAGE
     *
     * This function returns a "running man" image when true
     *
     * @return string (image)
     */
    public function SimSpecialistNeededYN()
    {
        if ($this->sims_spec_needed == 1)
        {
            $SimSpecialistNeededYN = '<span class="mr-1 simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.sim_spec') . '">
                                        <i class="fas fa-lg fa-running"></i></span>';}
        else
        {
            $SimSpecialistNeededYN = "<i></i>";
        }
        return $SimSpecialistNeededYN;
    }


    /**
     * SPECIAL REQUIREMENTS IMAGE
     *
     * This function returns a star image when true
     *
     * @return string (image)
     */
    public function specialRequirementsNeeded()
    {
        if ($this->special_requirements == 1)
        {
            $specialRequirementsNeeded = '<span class="mr-1 simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.special_requirements') . '">
                                            <i class="fas fa-lg fa-star"></i></span>';
        }
        else
        {
            $specialRequirementsNeeded = "<i></i>";
        }
        return $specialRequirementsNeeded;
    }

    /**
     * STATUS IMAGE
     *
     * This function returns a star image when true
     *
     * @return string (image)
     */
    public function statusImage()
    {
        if ($this->status_type_id != null)
        {
            $statusImage = '<span class="mr-1 simptip-position-top simptip-smooth" data-tooltip="' . $this->eventStatusType->description . '">
                                            <i style="color:'.$this->eventStatusType->html_color.'" class="fa-lg '.$this->eventStatusType->icon.'"></i></span>';
        }
        else
        {
            $statusImage = "<i></i>";
        }
        return $statusImage;
    }

    /**
    * Unresolved Comments
    * Returns a blue comments icon if the event has unresolved comments,
    * only visible to schedulers and the event requester
    * @author lutzjw
    * @date   2/27/20 14:04
    * @return string (image)
    */
   
    public function notResolved()
    {
        $commentsIcon = '<span class="mr-1 simptip-position-top simptip-smooth" data-tooltip="' . trans('labels.scheduling.unresolved_comments') . '">
                                        <i class="fa fa-lg fa-comments" style="color:#0000ff"></i></span>';

        //if they are a scheduler for location return flag icon
        if (access()->user() != null) {
            if (access()->user()->hasPermission('scheduling')) {
                if ($this->IsSchedulerForLocation()) {
                    if (!$this->resolved) {
                        return $commentsIcon;
                    }
                }
            }
        }

        //if they were the requester of the event return flag icon
        if (access()->user() != null) {
            if ($this->requestedBy(true) == Auth::user()->id) {
                if (!$this->resolved) {
                    return $commentsIcon;
                }            }
        }

        //if neither case above was met, return nothing
        return "<i></i>";
    }

    /**
     * EVENT COLOR
     *
     * This function returns the event color based on the following logic:
     * If a color has been set in events table use that
     *    Else if a color has been set for the course in course options use that
     *    Else if a color has been set for the IMR location use that
     *    Else just use white (which will appear as no color on calendar, etc)
     *
     * @return string (RGB color)
     */
    public function eventColor()
    {
        //default to white for when nothing else set
        $eventColor = 'rgb(255, 255, 255)';

        //check to see if there is an event color
        if ($this->color <> "")
        {
            $eventColor = $this->color;
        }
        else
        {
            //check to see if there is a course option color
            $courseOptionColor = Course::find($this->courseInstance->course->id)->getCourseOption(7);
            if($courseOptionColor <> "0")
            {
                $eventColor = $courseOptionColor;
            }
            else
            {
                //check to see if there is a location color for the IMR
                if($this->initialMeetingRoom->location->html_color <> "")
                {
                    $eventColor = $this->initialMeetingRoom->location->html_color;
                }
            }
        }

        return $eventColor;
    }

    /**
     * Determine if the logged in access has the given permission(s) at the event level
     *
     * @param $eventPermissionNames = an array of event level permission names
     * @return true or false
     */
    public function hasPermissions($eventPermissionNames)
    {
        //user must be logged in to check permissions
        if (access()->user() != null) {

            //If not an array, make a one item array
            if (! is_array($eventPermissionNames)) {
                $eventPermissionNames = [$eventPermissionNames];
            }

            //this takes the permission names and returns possible role id's to use in select below
            $roles = DB::table('roles')
                ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
                ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->whereIn('permissions.name', $eventPermissionNames)
                ->pluck('roles.id');

            //see if the user is associated in one of these roles for this event
            $userEventRoles = DB::table('event_user')
                ->select('user_id', 'role_id')
                ->where('user_id', Auth::user()->id)
                ->where('event_id', $this->id)
                ->whereIn('role_id', $roles)
                ->where('deleted_at', Null)
                ->where('status_id', 1)
                ->first();

            if($userEventRoles)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            //not logged in, return false
            return false;
        }
    }

    /**
     * Determine if the logged in access has the given permission(s) at the site, course or event level
     *
     * @param $sitePermissionNames = an array of site level permission names
     * @param $coursePermissionNames = an array of course level permission names
     * @param $eventPermissionNames = an array of course level permission names
     * @return true or false
     */
    public function hasSiteCourseEventPermissions($sitePermissionNames, $coursePermissionNames, $eventPermissionNames)
    {
        //user must be logged in to check permissions
        if (access()->user() != null) {
            //check site level
            if (access()->hasPermissions($sitePermissionNames)) {
                return true;
            }
            else{
                if ($this->CourseInstance->Course->hasPermissions($coursePermissionNames)) {
                    return true;
                }
                else{
                    if ($this->hasPermissions($eventPermissionNames)) {
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            }
        } else {
            //not logged in, return false
            return false;
        }
    }


    //mitcks todo: UGH! why is this function here?  the user model already has a getNameAttribute that can get called with getName() ?
    public function getUserFirstLast($id)
    {
        $user = User::where('id',$id)->value('first_name') . ' ' . User::where('id',$id)->value('last_name');
        return $user;
    }

    /**
     * Get All the QSE that should be displayed on QSE Tab (Event Dashboard)
     *
     * @return \Illuminate\Support\Collection model
     */
    public function getQSE($retired = false)
    {
        //TODO (mitcks): this is to return QSE sorted first by parent display order, then qse display order
        // there may be a better way to do this
        //Get Parent Modules By Display Order
        $courseModules = CourseContent::where('course_id', $this->CourseInstance->course_id)
            ->published()
            ->where('content_type_id', 1)
            ->where('parent_id', 0)
            ->orderby('display_order');

        $courseModules =  $courseModules->get();

        $qseToDisplay = collect(new QSE);

        //loop through Parent Modules and get QSE
        foreach ($courseModules as $courseModule)
        {
            $courseContentQSEs = CourseContent::where('course_id', $this->CourseInstance->course_id)
                ->where('parent_id', $courseModule->id)
                ->published()
                ->QSE()
                ->orderby('display_order');

            if (!$retired) {
                $courseContentQSEs = $courseContentQSEs->whereNull('retired_date');
            }

            $courseContentQSEs = $courseContentQSEs->get();

            if ($courseContentQSEs->isNotEmpty()) {
                foreach($courseContentQSEs as $courseContentQSE)
                {
                    // Get qse record
                    $qse = QSE::where('course_content_id', $courseContentQSE->menu_id)
                        ->leftJoin('event_qse_activation', function($join){
                            $join->on('event_qse_activation.qse_id', '=', 'qse.id')
                            ->where('event_qse_activation.event_id','=', $this->id);
                        })
                        ->first();

                    $qseToDisplay->push($qse);
                    //$qseToDisplay->push(['event_id' => $this->id]);
                }
            }
        }

        return $qseToDisplay;
    }

    /**
     * Get All the Users Who Can "Do Something" For this Event Based On a Permission
     *
     * @param $sitePermissionNames = an array of site level permission names
     * @param $coursePermissionNames = an array of course level permission names
     * @param $eventPermissionNames = an array of event level permission names
     * @return User model
     */
    public function getUsers($sitePermissionNames, $coursePermissionNames, $eventPermissionNames)
    {

        //If not passed in as an array, make each a one item array
        if (! is_array($sitePermissionNames)) {
            $sitePermissionNames = [$sitePermissionNames];
        }
        if (! is_array($coursePermissionNames)) {
            $coursePermissionNames = [$coursePermissionNames];
        }
        if (! is_array($eventPermissionNames)) {
            $eventPermissionNames = [$eventPermissionNames];
        }

        //First get the users with site level permission
        //get the site role id's based on permission names
        $siteRoles = DB::table('roles')
            ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->whereIn('permissions.name', $sitePermissionNames)
            ->where('roles.site_id', Session::get('site_id'))
            ->pluck('roles.id');

        $siteUsers = User::whereHas('siteRoles', function($q) use ($siteRoles){
            $q->whereIn('role_id', $siteRoles);
        })->get();

        //Second get the users with course level permission
        //get the site role id's based on permission names
        $courseRoles = DB::table('roles')
            ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->whereIn('permissions.name', $coursePermissionNames)
            ->where('roles.site_id', Session::get('site_id'))
            ->pluck('roles.id');

        $courseUsers = User::whereHas('roles', function($q) use ($courseRoles){
            $q->whereIn('role_id', $courseRoles)->where('course_id', $this->courseInstance->course_id);
        })->get();

        //Third get the users with event level permission
        //get the site role id's based on permission names
        $eventRoles = DB::table('roles')

            ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->whereIn('permissions.name', $eventPermissionNames)
            ->where('roles.site_id', Session::get('site_id'))
            ->pluck('roles.id');

        $eventUsers = User::whereHas('eventUsers', function($q) use ($eventRoles){
            $q->whereIn('role_id', $eventRoles)->where('event_id', $this->id);
        })->get();

        //merge the three collections
        $users = $siteUsers->merge($courseUsers);
        $users = $users->merge($eventUsers);
        //remove any duplicates
        $users = $users->unique();

        return $users;

    }

    /**
     * Event date for display in Friday, January 10, 2020 format
     *
     * @return string
     */
    public function getDisplayStartDateAttribute()
    {
        if($this->isParkingLot())
        {
            return trans('labels.event.parkingLot');
        }
        else {
            return Carbon::parse($this->start_time)->format('l, F d, Y');
        }
    }

    /**
     * Event date for display in 2/26/20 format
     *
     * @return string
     */
    public function getDisplayStartDateShortAttribute()
    {
        if($this->isParkingLot())
        {
            return trans('labels.event.parkingLot');
        }
        else {
            return Carbon::parse($this->start_time)->format('m/d/y');
        }
    }

    /**
     * Event start - end times for display in 8:00am - 3:00pm format
     *
     * @return string
     */
    public function getDisplayStartEndTimesAttribute()
    {
        if($this->isParkingLot())
        {
            return trans('labels.event.parkingLot');
        }
        else
        {
           return date_create($this->start_time)->format('g:ia') . ' - ' . date_create($this->end_time)->format('g:ia');
        }
    }

    public function getDisplayStartEndTimesTZAttribute()
    {
        if($this->isParkingLot())
        {
            return trans('labels.event.parkingLot');
        }
        else
        {
            //to get abbreviation for building timezone
            $tz = Carbon::now()->timezone($this->initialMeetingRoom->location->building->timezone)->timezoneAbbreviatedName;
            return date_create($this->start_time)->format('g:ia') . ' - ' . date_create($this->end_time)->format('g:ia') . ' ' . $tz;
        }
    }

    /**
     * Event day and start - end times for display in Wednesday, January 15, 2020 (9:15am - 3:00pm) format
     *
     * @return string
     */
    public function getDisplayDateStartEndTimesAttribute()
    {
        if($this->isParkingLot())
        {
            return $this->DisplayStartDate;
        }
        else
        {
            return $this->DisplayStartDate .
                ' (' . $this->DisplayStartEndTimes . ')';
        }

    }

    /**
     * Event day and start - end times for display in o2/26/20 (9:15am - 3:00pm) format
     *
     * @return string
     */
    public function getDisplayShortDateStartEndTimesAttribute()
    {
        if($this->isParkingLot())
        {
            return $this->DisplayStartDateShort;
        }
        else {
            return $this->DisplayStartDateShort .
                ' (' . $this->DisplayStartEndTimes . ')';
        }
    }

    /**
     * Event start_time in "local" timezone based on the timezone of building (via IMR)
     *
     * @return date
     */
    public function getStartTimeInIMRTimezoneAttribute()
    {
        $startTimeInIMRTimezone = Carbon::parse($this->start_time)
                                    ->timezone($this->initialMeetingRoom->location->building->timezone)
                                    ->format('m/d/y g:i A');

        return $startTimeInIMRTimezone;
    }

    /**
     * Event end_time in "local" timezone based on the timezone of building (via IMR)
     *
     * @return date
     */
    public function getEndTimeInIMRTimezoneAttribute()
    {
        $endTimeInIMRTimezone = Carbon::parse($this->end_time)
            ->timezone($this->initialMeetingRoom->location->building->timezone)
            ->format('m/d/y g:i A');

        return $endTimeInIMRTimezone;
    }

    /**
     * Course name and course abbrv event abbrv for display
     * example: Joint Aspiration and Injection Course: Shoulder and Knee (JAI Skills A)
     *
     * @return string
     */
    public function getCourseNameAndAbbrvAttribute()
    {
        return $this->courseInstance->course->name . ' ('. $this->CourseAbbrvEventAbbrv. ')';
    }

    /**
     * Course name and course abbrv event abbrv for display
     * example: Joint Aspiration and Injection Course: Shoulder and Knee (JAI Skills A)
     *
     * @return string
     */
    public function getCourseAbbrvEventAbbrvAttribute()
    {
        $courseAbbrvEventAbbrv = $this->courseInstance->course->abbrv;

        if($this->abbrv != null)
        {
            $courseAbbrvEventAbbrv = $courseAbbrvEventAbbrv . ' ' . $this->abbrv;
        }

        return $courseAbbrvEventAbbrv;
    }

    /**
     * Initial Meeting Room With Building and Location for Display
     *
     * @return string
     */
    public function getDisplayIMRAttribute()
    {
        return $this->initialMeetingRoom->location->building->abbrv . ' ' . $this->initialMeetingRoom->location->abbrv . ' ' . $this->initialMeetingRoom->abbrv;
    }

    /**
     * Initial Meeting Room With Option of Hiding Building and Location in Display
     * Note: this is done in a separate function from getDisplayIMRAttribute above because
     * you cannot pass parameters into Laravel's "magic" accessors
     *
     * @param  bool  $roomOnly
     * @return string
     */
    public function getDisplayIMR($roomOnly=false)
    {
        if($roomOnly)
        {
            return $this->initialMeetingRoom->abbrv;
        }
        else
        {
            return $this->initialMeetingRoom->location->building->abbrv . ' ' . $this->initialMeetingRoom->location->abbrv . ' ' . $this->initialMeetingRoom->abbrv;
        }
    }

    /**
     * Initial Meeting Room Building and Location without Room (for Welcome Board)
     *
     * @return string
     */
    public function getDisplayIMRBldgLocation($roomOnly=false)
    {
        return $this->initialMeetingRoom->location->building->abbrv . ' ' . $this->initialMeetingRoom->location->abbrv;
    }

    /**
     * Event name for display in Joint Aspiration and Injection Course: Shoulder and Knee (JAI) - Friday, January 10, 2020 (8:00am - 3:00pm) format
     *
     * @return string
     */
    public function getDisplayEventNameAttribute()
    {

        return $this->CourseNameAndAbbrv . ' - '.
            $this->DisplayDateStartEndTimes;

    }

    /**
     * Event name for display in shorter format like NARAT 01/17/20 (8:00am - 3:00pm)
     *
     * @return string
     */
    public function getDisplayEventNameShortAttribute()
    {
        return $this->courseInstance->course->abbrv. ' - '.
            $this->DisplayShortDateStartEndTimes;
    }

    /**
     * Display Enrollment Counts (size, enrolled, waitlisted, parked) for this Event
     *
     * @return string
     */
    public function getDisplayEventUserCountsAttribute()
    {
        if (access()->user() != null) {
            if ($this->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events']))
            {
                return trans('labels.event.class_size') . ": " . $this->class_size .
                    "&emsp;<a href='/courseInstance/events/event-dashboard/" . $this->id . "/roster'>" . trans('labels.event.enrolled') . ": " . $this->numLearnersEnrolled() . "</a>" .
                    "&emsp;<a href='/courseInstance/events/event-dashboard/" . $this->id . "/waitlist'>" . trans('labels.event.waitlisted') . ": " . $this->numLearnersWaitlisted() . "</a>" .
                    "&emsp;<a href='/mycourses/waitlist'>" . trans('labels.event.parkingLot') . ": " . $this->courseInstance->course->numLearnersParked() . "</a>";
            }
            else
            {
                return trans('labels.event.class_size') . ": " . $this->class_size .
                    "&emsp; " . trans('labels.event.enrolled') . ": " . $this->numLearnersEnrolled() .
                    "&emsp; " . trans('labels.event.waitlisted') . ": " . $this->numLearnersWaitlisted() .
                    "&emsp; " . trans('labels.event.parkingLot') . ": " . $this->courseInstance->course->numLearnersParked();
            }
        }
    }


    /**
     * EVENT RELATED ACTION BUTTONS
     */

    /**
     * Event Dashboard button, goes to the event dashboard, all can view (no login required)
     *
     * @return string
     */
    public function getEventDashboardButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.dashboard') . '">
                <a href="/courseInstance/events/event-dashboard/'.$this->id.'" class="btn-sm infoButton">
                <i class="fa fa-tachometer-alt fa-fw"></i></a></span>&nbsp;';
    }

    //icon instead of button, used on dashboard
    public function getEventDashboardIconAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.dashboard') . '">
                <a href="/courseInstance/events/event-dashboard/'.$this->id.'">
                <i class="fad fa-tachometer-alt fa-lg"></i></a></span>';
    }

    /**
     * Date on Calendar button, goes to the calendar day-view for this event date, all can view (no login required)
     *
     * @return string
     */
    public function getCalendarDateAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.calendar.go_to_day') . '">
                <a href="/calendar?date=' . Carbon::parse($this->start_time)->format('Y-m-d') . '" class="btn-sm addButton">
                <i class="fa fa-calendar fa-fw"></i></a></span>&nbsp;';
    }

    /**
     * Event Recurrence button, user must be (1)logged in, (2)have scheduling permission, and (3) be a scheduler for the IMR location of the event to see this button
     * only display if there is more than one event for course_instance_id
     * @return string
     */
    public function getRecurrenceButtonAttribute()
    {
        if($this->courseInstance->hasRecurrence()) {
            if (access()->user() != null) {
                if (access()->user()->hasPermission('scheduling')) {
                    if ($this->IsSchedulerForLocation()) {
                        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.recurrence_group') . '">
                            <a href="/courseInstance/main/confirmation/' . $this->course_instance_id . '" class="btn-sm infoButton">
                            <i class="fa fa-layer-group fa-fw"></i></a></span>&nbsp;';
                    } else {
                        //disabled for a scheduler not associated with location
                        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.recurrence_group') . '">
                            <a href="/courseInstance/main/confirmation/' . $this->course_instance_id . '" class="btn-sm disabledButton">
                            <i class="fa fa-layer-group fa-fw"></i></a></span>&nbsp;';
                    }
                }
            }
        }
    }

    /**
     * Create Template from Event button, user must be (1)logged in and (2)have manage-templates permission
     *
     * @return string
     */
    public function getTemplatesButtonAttribute()
    {
        if (access()->user() != null){
            if(access()->hasPermission('manage-templates')){
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.course_templates.create_template') . '">
                        <a href="/courseInstance/template/create/' . $this->id . '" class="btn-sm templateButton">
                        <i class="fa fa-cog fa-fw"></i></a></span>&nbsp;';
            }
        }
    }

    /**
     * Edit Event button, user must be (1)logged in, (2)have scheduling permission, and (3) be a scheduler for the IMR location of the event to see this button
     *
     * @return string
     */
    public function getEditButtonAttribute()
    {
        if (access()->user() != null){
            if (access()->user()->hasPermission('scheduling')) {
                if ($this->IsSchedulerForLocation()) {
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.edit') . '">
                            <a href="/courseInstance/main/editEvent/' . $this->id . '" class="btn-sm editButton">
                            <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
                } else {
                    //disabled for a scheduler not associated with location
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.edit') . '">
                            <a href="/courseInstance/main/editEvent/' . $this->id . '" class="btn-sm disabledButton">
                            <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
                }
            }
        }
    }

    /**
     * Duplicate Event button, user must be (1)logged in, (2)have scheduling permission, and (3) be a scheduler for the IMR location of the event to see this button
     *
     * @return string
     */
    public function getDuplicateButtonAttribute()
    {
        if (access()->user() != null){
            if (access()->user()->hasPermission('scheduling')) {
                if ($this->IsSchedulerForLocation()) {
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.duplicate') . '">
                            <a href="/courseInstance/main/duplicateEvent/' . $this->id . '" class="btn-sm duplicateButton">
                            <i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
                } else {
                    //disabled for a scheduler not associated with location disabledButton
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.duplicate') . '">
                            <a href="/courseInstance/main/duplicateEvent/' . $this->id . '" class="btn-sm disabledButton">
                            <i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
                }
            }
        }
    }

    /** NOT CURRENTLY IN USE, fix tooltips if used again
     * Another Event to Group button, user must be (1)logged in, (2)have scheduling permission, and (3) be a scheduler for the IMR location of the event to see this button
     *
     * @return string
     */
    public function getAnotherEventToGroupButtonAttribute()
    {
        if (access()->user() != null){
            if (access()->user()->hasPermission('scheduling')) {
                if ($this->IsSchedulerForLocation()) {
                    return '<a href="/courseInstance/main/anotherEvent/' . $this->id . '" class="btn-sm infoButton">
                            <i class="fa fa-link fa-fw" data-toggle="tooltip" data-placement="top" 
                            title="' . trans('buttons.event.anotherEvent') . '"></i></a> ';
                } else {
                    //disabled for a scheduler not associated with location
                    return '<a href="/courseInstance/main/anotherEvent/' . $this->id . '" class="btn-sm disabledButton">
                            <i class="fa fa-link fa-fw" data-toggle="tooltip" data-placement="top" 
                            title="' . trans('buttons.event.anotherEvent') . '"></i></a> ';
                }
            }
        }
    }

    /** NOT CURRENTLY IN USE, fix tooltips if used again
     * A new event for the same course, user must be (1)logged in, (2)have scheduling permission, and (3) be a scheduler for the IMR location of the event to see this button
     *
     * @return string
     */
    public function getNewEventThisCourseButtonAttribute()
    {
        if (access()->user() != null){
            if (access()->user()->hasPermission('scheduling')) {
                if ($this->IsSchedulerForLocation()) {
                    return '<a href="/courseInstance/main/create?course_id=' . $this->courseInstance->course_id . '" class="btn-sm emailButton">
                            <i class="fa fa-plus fa-fw" data-toggle="tooltip" data-placement="top" 
                            title="' . trans('buttons.event.newEventSameCourse') . '"></i></a> ';
                } else {
                    //disabled for a scheduler not associated with location
                    return '<a href="/courseInstance/main/create?course_id=' . $this->courseInstance->course_id . '" class="btn-sm disabledButton">
                            <i class="fa fa-plus fa-fw" data-toggle="tooltip" data-placement="top" 
                            title="' . trans('buttons.event.newEventSameCourse') . '"></i></a> ';
                }
            }
        }
    }

    /**
     * Delete Event button, user must be (1)logged in, (2)have scheduling permission, and (3) be a scheduler for the IMR location of the event to see this button
     *
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        if (access()->user() != null) {
            if (access()->user()->hasPermission('scheduling')) {
                if ($this->IsSchedulerForLocation()) {
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.delete') . '">
                            <a href="/event/delete/' . $this->id . '" name="delete_event" class="btn-sm deleteButton">
                            <i class="fa fa-trash fa-fw"></i></a></span>';
                } else {
                    //disabled for a scheduler not associated with location
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.delete') . '">
                            <a href="/event/delete/' . $this->id . '" name="delete_event" class="btn-sm disabledButton">
                            <i class="fa fa-trash fa-fw"></i></a></span>';
                }
            }
        }
    }

    /**
     * Restore (aka un delete) Event button, only displayed on Deleted Events Page for Schedulers
     *
     * @return string
     */
    public function getRestoreButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.event.restore') . '">
                <a href="/event/restore/' . $this->id . '" name="restore_event" id="restore_event" 
                data-eventdate="'. $this->CourseInstanceTrashed->CourseTrashed->abbrv.' - ' . $this->DisplayShortDateStartEndTimes. '"
                class="btn-sm addButton">
                <i class="fas fa-trash-restore fa-lg"></i></a></span>&nbsp;';
    }

    // Event Email Button - NOT CURRENTLY IN USE, accessed via event-dashboard email tab, fix tooltips if used again,
    public function getEventEmailButtonAttribute() {
        // User must have event email permissions
        if (access()->user() != null) {
            if ($this->hasSiteCourseEventPermissions(['manage-event-email'], ['course-manage-event-emails'], ['event-manage-event-emails'])) {
                // grant access
                return '<a href="/courseInstance/events/event-dashboard/' . $this->id . '/emails"
                        class="btn-sm emailButton"><i class="fa fa-fw fa-envelope"
                        data-toggle="tooltip" data-placement="top"
                        title="' . trans('buttons.general.email') . '"></i></a> ';
            }else {
                // disable button
                return '<a href="/dashboard" class="btn-sm disabledButton"><i class="fa fa-fw fa-envelope"
                        data-toggle="tooltip" data-placement="top"
                        title="' . trans('buttons.general.email') . '"></i></a> ';
            }
        }
    }


    /**
     * Build all the buttons displayed on:
     *  Main Dashboard (Daily Schedule Section)
     *  Main Dashboard (My Events Section)
     *
     * In Laravel this is accessed via $events->action_buttons
     *
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return  $this->getEventDashboardIconAttribute();
        //2020-02-12 mitcks: commented out buttons because these functions can be access via new dashboard,
        // but left here in case users decide they want the shortcuts
//                $this->getTemplatesButtonAttribute().
//                $this->getEditButtonAttribute().
//                $this->getDuplicateButtonAttribute().
//                $this->getAnotherEventToGroupButtonAttribute().
//                $this->getNewEventThisCourseButtonAttribute().
//                $this->getDeleteButtonAttribute()
//;
    }

    /**
     * Build all the buttons displayed on calendar agenda view page. In Laravel this is accessed via $events->agenda_buttons
     * TODO: (mitcks) this is the same as above - do we need both?
     *
     * @return string
     */
    public function getAgendaButtonsAttribute()
    {
        return  $this->getEventDashboardButtonAttribute().
                $this->getTemplatesButtonAttribute().
                $this->getEditButtonAttribute().
                $this->getDuplicateButtonAttribute();
                // $this->getAnotherEventToGroupButtonAttribute().
                // $this->getNewEventThisCourseButtonAttribute().
                //mitcks 2020-08-28 removed delete button because it's too hard to add recurrence to sweet alert due to data table
                //$this->getDeleteButtonAttribute();
    }

    /**
     * Build all the buttons displayed on the event dashboard page. In Laravel this is accessed via $events->event_dashboard_buttons
     *
     * @return string
     */
    public function getEventDashboardButtonsAttribute()
    {
        return  $this->getCalendarDateAttribute().
                $this->getRecurrenceButtonAttribute().
                $this->getTemplatesButtonAttribute().
                $this->getEditButtonAttribute().
                $this->getDuplicateButtonAttribute().
//                $this->getAnotherEventToGroupButtonAttribute().
//                $this->getNewEventThisCourseButtonAttribute().
                $this->getDeleteButtonAttribute();
    }

}
