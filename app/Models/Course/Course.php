<?php

namespace App\Models\Course;

use App\Models\Access\Role\Role;
use App\Models\Access\RoleUser\RoleUser;
use App\Models\CourseContent\CourseContent;
use App\Models\Course\CourseCoupons;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventUser;
use App\Models\CourseInstance\CourseInstance;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Site\Site;
use Illuminate\Support\Arr;

class Course extends Model
{
    protected $table;

    protected $fillable = ['site_id', 'abbrv', 'name', 'retire_date', 'catalog_description', 'catalog_image', 'author_name', 'virtual', 'created_by', 'last_edited_by' ];

    //Here is the "scope" section to limit it to just this Site's courses. Defined in CourseScope.php
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CourseScope);
    }

    public function Site() {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the coupons for the course
     */
    public function courseCoupons()
    {
        return $this->hasMany(CourseCoupons::class);
    }

    // Allow API to use forwards model relationship
    public function SiteToTouch() {
        return $this->Site()->withoutGlobalScopes();
    }

    /**
     * Scope a query to only include active courses
     * this is called in a query with ->active()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('retire_date', null);
    }

    /**
     * Scope a query to only include retired courses
     * this is called in a query with ->retired()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired($query)
    {
        return $query->where('retire_date', '<>', null);
    }


    /**
     * Scope a query to only include the logged in user's courses based on site, course and/or event permissions
     * this is called in a query including the 3 parameters, for example:
     *  Course::myCourses('schedule-request','course-schedule-request', '')
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    //mitcks 2020-04-21: still working on this, it works but doesn't go to event level yet
    // it is currently only being called from Http/Controllers/CourseInstance/ScheduleRequestController.php
    // where the $eventPermissionNames is not needed

    public function scopeMyCourses($query, $sitePermissionNames, $coursePermissionNames, $eventPermissionNames)
    {
        //check to be sure the user is logged in
        if (access()->user() != null) {

            //FIRST CHECK TO SEE IF THEY HAVE SITE LEVEL ACCESS, IF YES RETURN ALL COURSES
            if (access()->hasPermissions($sitePermissionNames))
            {
                return $query; //just return all courses
            }
            else
            {
                //SECOND CREATE AN ARRAY OF THE COURSES THEY HAVE COURSE OR EVENT LEVEL PERMISSION FOR

                $courses = $this->get();
                $courseIdArray = [];

                //If not an array, make a one item array
                if (!is_array($coursePermissionNames)) {
                    $coursePermissionNames = [$coursePermissionNames];
                }

                foreach ($courses as $course)
                {
                    if ($course->hasPermissions($coursePermissionNames)) {
                        $courseIdArray[] =  $course->id;
                    }
                }

                //THIRD ADD ANY COURSES THEY HAVE EVENT LEVEL ACCESS FOR **NOT COMPLETE YET**
                // This gets a little complicated because you only need to check for evet event permission if they don't
                // already have course level permission (I think)


                return $query->whereIn('id', $courseIdArray);
            }

        }
    }

    //To concatenate abbrv and virtual for course select lists
    public function getAbbrvVirtualAttribute()
    {
        $virtual = null;

        if ($this->attributes['virtual'] == "1" )
        {
            $virtual = '(Virtual)';
        }

        return $this->attributes['abbrv'] . ' ' . $virtual;
    }

    public function courseInstances()
    {
        return $this->hasMany(CourseInstance::class);
    }

    public function courseTemplates()
    {
        return $this->hasMany(CourseTemplate::class);
    }

    public function courseOption()
    {
        return $this->hasMany(CourseOption::class);
    }

    public function courseRoleUsers()
    {
        return $this->hasMany(RoleUser::class);
    }

    public function courseContents()
    {
        return $this->hasMany(CourseContent::class);
    }

    public function getCourseOption($course_option_id)
    {
        $options = $this->courseOption;
        foreach ($options as $option)
        {

            if ($option->option_id == $course_option_id) {
                return $option->value;
            }
        }
        // if we get here, we haven't found the ID, return null
        return 0;
    }

    public function isRetired()
    {
        return $this->retire_date == NULL;
    }

    public function isOptionChecked($optionId)
    {
        $isOptionChecked = $this->courseOption->where('option_id', $optionId);
        if ($isOptionChecked->isEmpty())
        {return 0;}
        else
        {return 1;}
    }

    /**
     * Get a string of the next x class dates (displayed on Catalog)
     * display "event full" message to the right of date if class is full
     * display the building abbrv (of the IMR) IF there is more than one building for the set of dates returned
     *
     * @param $numDates optional parameter for max number of dates to return
     * @return string
     */
    public function upcomingClassDates($numEvents = 10)
    {

        $upcomingClassDates = null;

        //get next x events (defaults to 10 if no parameter passed in)
        $events = Event::with('CourseInstance')
            ->where('start_time', '>=', date("Y-m-d"))
            ->whereHas('CourseInstance', function($q){
                $q->where('course_id', $this->id);
                })
            ->orderBy('start_time')
            ->paginate($numEvents);

        //figure out of there is more than one building for this set of events
        $buildingCount = 0;
        $lastbuildingId = 0;
        if($events) {
            foreach ($events as $index => $event) {
                if ($index == 0){
                    //set for first value
                    $lastbuildingId = $event->initialMeetingRoom->location->building->id;
                }
                else
                {
                    if($lastbuildingId != $event->initialMeetingRoom->location->building->id)
                    {
                        $buildingCount++;
                    }
                    //set for next loop
                    $lastbuildingId = $event->initialMeetingRoom->location->building->id;
                }
            }
        }

        //loop through events to create the string of dates to display
        if($events) {
            foreach ($events as $index => $event) {
                if ($index == 0) {
                    //first item, no comma, link to event dashboard
                    $upcomingClassDates = "<a href='/courseInstance/events/event-dashboard/" . $event->id . "'>" . Carbon::parse($event->start_time)->format('n/j/Y') . "</a>";
                } else {
                    //subsequent items
                    $upcomingClassDates .= ', ' . "<a href='/courseInstance/events/event-dashboard/" . $event->id . "'>" . Carbon::parse($event->start_time)->format('n/j/Y') . "</a>";
                }

                //if there is more than one location in this event set, display IMR location abbrv
                if ($buildingCount > 0) {
                    $upcomingClassDates .= " (" . $event->initialMeetingRoom->location->building->abbrv . ")";
                }

                //if full display "event full" text
                if ($event->isFull()) {
                    $upcomingClassDates .= " <span class='event-full'>" . trans('labels.event.full') . "</span>";
                }
            }
        }

        return $upcomingClassDates;
    }
    
    /**
     * Determine if the logged in user has access to the given permission(s) at the course level
     *
     * @param $coursePermissionsNames = an array of course level permission names
     * @return true or false
     */
    public function hasPermissions($coursePermissionNames)
    {
        if (access()->user() != null) {

            //If not an array, make a one item array
            if (! is_array($coursePermissionNames)) {
                $coursePermissionNames = [$coursePermissionNames];
            }

            //mitcks: note, this is using a DB here because I had difficulty getting this to work with relationships
            //this takes the permission names and returns corresponding role id's to use in select below
            $roles = DB::table('roles')
                ->select('roles.id')
                ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
                ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->whereIn('permissions.name', $coursePermissionNames)
                ->distinct()
                ->pluck('roles.id');

            if ($roles)
            {
                //check to see if current user has a course role with one of the permissions,
                // we only need to return first because that's enough to determine access
                //see if the user is associated in one of these roles for this event
                $courseRoleUser = DB::table('role_user')
                    ->select('user_id', 'role_id', 'course_id')
                    ->where('user_id', Auth::user()->id)
                    ->where('course_id', $this->id)
                    ->whereIn('role_id', $roles)
                    ->first();

                if ($courseRoleUser) {
                    return true;
                } else {
                    return false;
                }
            }
            else
            {
                //no valid roles found, return false
                return false;
            }

        } else {
            //not logged in, return false
            return false;
        }

    }

    /**
     * Determine if the logged in user has access to the given permission(s)at the site or course level
     *
     * @param $sitePermissionsNames = an array of site level permission names
     * @param $coursePermissionsNames = an array of course level permission names
     * @return true or false
     */
    public function hasSiteCoursePermissions($sitePermissionsNames, $coursePermissionsNames)
    {

        if (access()->user() != null) {
            //check site level
            if (access()->hasPermissions($sitePermissionsNames)) {
                return true;
            } else {
                //check course level
                if ($this->hasPermissions($coursePermissionsNames)) {
                    return true;
                } else {

                    return false;
                }
            }
        } else {
            //not logged in, return false
            return false;
        }
    }

    /**
    * Returns a list of roles both course and event level, for the logged in user for a course
    * @author lutzjw
    * @date   3/13/20 16:41
    * @return
    */
    public function getRoles()
    {

        $courseRoles =  RoleUser::with('role')
                        ->where('user_id', Auth::user()->id)
                        ->where('course_id', $this->id)
                        ->get()
                        ->pluck('role.name', 'id');


        $eventRoles = EventUser::join('events', 'events.id', 'event_user.event_id')
            ->join('course_instances', 'course_instances.id', 'events.course_instance_id')
            ->join('roles', 'roles.id', 'event_user.role_id')
            ->where('event_user.user_id', Auth::user()->id)
            ->where('course_instances.course_id', $this->id)
            ->whereNull('events.deleted_at')
            ->pluck('roles.name');

        $returnArray = $courseRoles->merge($eventRoles)->unique()->sort();
        
        return $returnArray->implode(', ');
    }

    /**
     * PARKING LOT EVENT ID
     *
     * Given the current course, return the id of the parking lot event
     *
     * @return integer
     */

    public function getParkingLotEventIdAttribute()
    {
        $courseId = $this->id;
        $parkingLotEvent = Event::with('CourseInstance')
            ->where('parking_lot', '=', 1)
            ->whereHas('CourseInstance', function ($q) use ($courseId) {
                $q->where('course_id', '=', $courseId);
            })->first();
        if ($parkingLotEvent)
        {
            return $parkingLotEvent->id;
        }
        else
        {
            return 0;
        }
    }

    /**
     * NUMBER OF LEARNERS PARKED
     *
     * Number of learners parked for this course
     *
     * @return integer
     */
    public function numLearnersParked()
    {
        $parkingLotId = $this->ParkingLotEventId;
        $event = Event::where('id', $parkingLotId)->first();
        $roles = Role::where(['learner' => 1])->pluck('id');

        if($event)
        {
            $numLearnersParked = $event->eventUsers()
            ->whereIn('role_id', $roles)
            ->count();
            return $numLearnersParked;
        }
        else
        {
            return 0;
        }
    }


// Get buttons for the listing pages (active, deactivated, all)

    //info button, todo: where is this displayed? Manage courses?
    public function getShowButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                <a href="/courses/show/'.$this->id.'"
                class="btn-sm infoButton"><i class="fa fa-fw fa-info"></i></a></span>&nbsp;';
    }

    //no permissions needed, this is displayed in course catalog and my courses
    public function getViewCatalogButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                <a href="/courses/catalogShow/'.$this->id.'"
                class="btn-sm infoButton"><i class="fa fa-fw fa-info"></i></a></span>&nbsp;';
    }

    //displays edit course button, must have site level manage-courses permission
    public function getEditButtonAttribute()
    {
        if (access()->hasPermission('manage-courses'))
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                    <a href="/courses/edit/'.$this->id.'" class="btn-sm editButton">
                    <i class="fa fa-fw fa-pencil-alt"></i></a></span>&nbsp;';
        }
    }

    //displays edit content button, must have site level manage-course-curriculum permission
    public function getManageCourseButtonAttribute()
    {
        if (access()->hasPermission('manage-course-curriculum'))
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('menus.backend.courseCurriculum.edit-course-curriculum') . '">
                    <a href="/course/content/'.$this->id.'/edit"
                    class="btn-sm editButton"><i class="fa fa-fw fa-book"></i></a></span> ';
        }
    }


    //displays templates button, must have site level manage-templates permission
    public function getTemplatesButtonAttribute()
    {
        if (access()->hasPermission('manage-templates')) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.courses.templates') . '">
                    <a href="/courses/courseTemplate/index/' . $this->id . '"
                    class="btn-sm templateButton"><i class="fa fa-fw fa-cog"></i></a></span>&nbsp;';
        }
    }

    //displays course categories button, must have site level course_categories permission
    public function getCategoriesButtonAttribute()
    {
        if (access()->hasPermission('course_categories'))
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.courses.categories') . '">
                    <a href="/courses/courseCategory/index/'.$this->id.'"
                    class="btn-sm categoriesButton"><i class="fa fa-fw fa-tasks"></i></a></span>&nbsp;';
                }
    }

    public function getOptionsButtonAttribute()
    {
        if (access()->hasPermission('course-options')) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.courses.options') . '">
                    <a href="/courses/courseOptions/index/' . $this->id . '"
                    class="btn-sm optionsButton"><i class="fa fa-fw fa-sliders-h"></i></a></span>&nbsp;';
        }
    }

    //displays add people button on manage courses
    public function getCourseUsersButtonAttribute()
    {
        //user must be (1)logged in, (2)have add-people-to-courses or course-add-people-to-courses permission
        if (access()->user() != null) {
            if ($this->hasSiteCoursePermissions(['add-people-to-courses'], ['course-add-people-to-courses'])) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('menus.backend.course.assign') . '">
                        <a href="/course/catalog/users/' . $this->id . '"
                        class="btn-sm addButton"><i class="fa fa-fw fa-users"></i></a></span>&nbsp;';
            } else {
                return '';
            }
        }
    }

    //displays add people button on my courses, passes page parameter for cancel button and breadcrumbs
    public function getMyCoursesUsersButtonAttribute()
    {
        //user must be (1)logged in, (2)have add-people-to-courses or course-add-people-to-courses permission
        if (access()->user() != null) {
            if ($this->hasSiteCoursePermissions(['add-people-to-courses'], ['course-add-people-to-courses'])) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('menus.backend.course.assign') . '">
                        <a href="/course/catalog/users/' . $this->id . '?page=mycourses"
                        class="btn-sm addButton"><i class="fa fa-fw fa-users"></i></a></span>&nbsp;';
            } else {
                return '';
            }
        }
    }

    // Email Btn for Course Level Emails
    public function getEmailButtonAttribute() {

        //user must be (1)logged in, (2)have site level manage-course-emails or course level course-manage-course-emails permission
        if (access()->user() != null) {
            if ($this->hasSiteCoursePermissions(['manage-course-emails'], ['course-manage-course-emails'])){
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.email') . '">
                        <a href="/courses/courseInstanceEmails/' . $this->id . '"
                        class="btn-sm emailButton"><i class="fa fa-fw fa-envelope"></i></a></span>&nbsp;';
            }
        }
    }

    //Retired or active button, site level manage courses permission only
    public function getRetiredButtonAttribute()
    {
        if (access()->hasPermission('manage-courses')) {
            if ($this->isRetired()) {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.retire') . '">
                        <a href="/courses/deactivate/' . $this->id . '"
                        class="btn-sm btn-warning"><i class="text-light fa fa-fw fa-pause"></i></a></span>&nbsp;';
            }
            else {
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.activate') . '">
                        <a href="/courses/activate/' . $this->id . '"
                        class="btn-sm addButton"><i class="fa fa-fw fa-play"></i></a></span>&nbsp;';
            }
        }
    }


    // Edit Btn for Course Content
    public function getCourseContentButtonAttribute() {
        if (access()->user() != null) {
            if ($this->hasSiteCoursePermissions(['manage-course-curriculum'], ['course-manage-course-curriculum'])){
                return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('menus.backend.courseCurriculum.edit-course-curriculum') . '">
                        <a href="/course/content/'.$this->id.'/edit"
                        class="btn-sm editButton"><i class="fas fa-fw fa-book"></i></a></span>&nbsp;';
            }
        }
    }

    // Get Enroll Button
    public function getEnrollButtonAttribute() {
        if (access()->user() != null 
            // check if we should hide the enrollment button.
            && !$this->isOptionChecked(6)
            //We'll also check to see if they have add-people-to-events or course-add-people-to-events
            //and *NOT* show this button, since they don't need to enroll for this course, they just add themselves
            && !$this->hasSiteCoursePermissions(['add-people-to-events'], ['course-add-people-to-events'])){
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.enroll') . '">
                    <a href="courses/catalogShow/'.$this->id.'"
                    class="btn-sm optionsButton"><i class="fas fa-fw fa-user-plus"></i></a></span>&nbsp;';
        }
    }

    // Get Fees Button
    public function getFeesButtonAttribute() {
        if (access()->hasPermission('manage_course_fees')) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.courses.fees') . '">
                    <a href="/courseFees/index/'.$this->id.'"
                    class="btn-sm btn-success"><i class="fas fa-fw fa-dollar-sign"></i></a></span>&nbsp;';
        }
    }

    // Build all the buttons for the action column. In Laravel this is accessed via
    // the $course->action_button attribute.
    public function getActionButtonsAttribute()
    {
        return  $this->getShowButtonAttribute().
                $this->getEditButtonAttribute().
                $this->getManageCourseButtonAttribute().
                $this->getTemplatesButtonAttribute().
                $this->getCategoriesButtonAttribute().
                $this->getOptionsButtonAttribute().
                $this->getCourseUsersButtonAttribute().
                $this->getEmailButtonAttribute().
                $this->getFeesButtonAttribute().
                $this->getRetiredButtonAttribute();

    }

    //used for buttons in course catalog and my courses
    public function getCatalogActionButtonsAttribute()
    {
        return  $this->getViewCatalogButtonAttribute().
                $this->getCourseContentButtonAttribute().
                $this->getEnrollButtonAttribute().
                $this->getMyCoursesUsersButtonAttribute().
                $this->getEmailButtonAttribute();
    }

}


