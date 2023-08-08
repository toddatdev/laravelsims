<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\CourseInstance\CourseInstanceController;
use App\Models\CourseInstance\EventStatusType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CourseInstance\Event;
use App\Models\Location\Location;
use App\Models\Building\Building;
use App\Models\Course\Course;
use Yajra\DataTables\Facades\DataTables;
use Session;
use App\Models\Site\Site;
use App\Http\Controllers\CourseInstance\ScheduleRequestController;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getCalendar()
    {
        $scheduleRequestController = new ScheduleRequestController;
        $pendingRequestCount = $scheduleRequestController->pendingRequestCount();

        $locations = Location::get()->sortBy('building_location_label')->pluck('building_location_label', 'id')->toArray();

        return view('calendar.calendar', compact('locations', 'pendingRequestCount'));
    }


    public function getDay($inputDay = null, $location = null, $rooms = null)
    {
        //for start and end time defaults
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $businessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);

        //$locationsAndResources = CourseInstanceController::getLocationsAndResources();

        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);

        return view('calendar.day', compact('inputDay', 'location', 'rooms', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }


    public function getWeek($inputWeek = null, $location = null, $rooms = null)
    {


        if(!$inputWeek) {
            $inputWeek = date("Y-m-d");
        }
        $weekEvents = null;

        $weekStartDate = $this->getStartOfWeekDate($inputWeek);
        $weekEndDate = date_add($this->getStartOfWeekDate($inputWeek), date_interval_create_from_date_string('6 days'));


        $events = Event::join('resources', 'resources.id', '=', 'initial_meeting_room')
            ->join('locations', 'locations.id', '=', 'resources.location_id')
            ->join('course_instances', 'course_instances.id', '=', 'course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->where('courses.site_id', SESSION::get('site_id'))
            ->where('events.deleted_at', '=', null)
            ->orderby('start_time')
            ->select('events.*')
            ->whereDate('start_time', '>=', $weekStartDate)
            ->whereDate('start_time', '<=', $weekEndDate);

        if ($location && $location != 'all'){
            $events->where('resources.location_id', '=', $location);
        }

        $events = $events->get();



        foreach ($events as $event)
        {

            $formatStartTime = date_create($event->start_time)->format('g:i a');
            $formatEndTime = date_create($event->end_time)->format('g:i a');

            $roomsList = null;
            if($rooms == 'true') {
                $roomsList = "<br>(". mb_strimwidth($event->eventRooms(), 0, 40, "..."). ")";
            }
            $statusFlag = " ". $event->statusImage();
            $simSpecialistNeeded = " ". $event->SimSpecialistNeededYN();
            $specialRequirements = " ". $event->specialRequirementsNeeded();
            $unresolvedComment = " ". $event->notResolved();
            $formatEvent['id'] = $event->id;
            $formatEvent['resource'] = "TS";
            $formatEvent['start'] = $event->start_time;
            $formatEvent['end'] = $event->end_time;
            $formatEvent['html'] = "<div style='background-color:". $event->eventColor() . "; position: absolute; height:4px; left: 0px; width: 100%; top: 0px;'></div><p style='line-height:110%; padding-top:10px;'>". $formatStartTime.$statusFlag.$simSpecialistNeeded.$specialRequirements.$unresolvedComment."<br><b data-toggle='tooltip' title='".$event->CourseInstance->Course->name."'>" .mb_strimwidth($event->CourseAbbrvEventAbbrv, 0, 20, "..."). "</b><br><span data-toggle='tooltip' title='" . $event->eventRooms()."'>" .$event->InitialMeetingRoom->abbrv. $roomsList ."</span></p>";

            // check if course is to be hidden on calendar
            $hideCourse = Course::find($event->courseInstance->course->id)->getCourseOption(2);
            if(!$hideCourse) {
                $weekEvents[] = $formatEvent;
            }
        }

        $weekStartDate = $weekStartDate->format('Y-m-d');


        return view('calendar.week', compact('weekStartDate','weekEvents', 'inputWeek', 'weekStartDate'));
    }



    public function getMonth($inputMonth = null, $location = null, $rooms = null)
    {

        if(!$inputMonth) {
            $inputMonth = date("Y-m-d");
        }
        $monthEvents = null;

        $firstDate = date_create($inputMonth);
        $firstDate->modify('first day of this month');
        $firstDate->modify('-6 days');

        $lastDate = date_create($inputMonth);
        $lastDate->modify('last day of this month');
        $lastDate->modify('+6 days');

        $events = Event::join('resources', 'resources.id', '=', 'initial_meeting_room')
            ->join('locations', 'locations.id', '=', 'resources.location_id')
            ->join('course_instances', 'course_instances.id', '=', 'course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->where('courses.site_id', SESSION::get('site_id'))
            ->where('events.deleted_at', '=', null)
            ->orderby('start_time')
            ->select('events.*')
            // ->whereMonth('start_time', '=', date_create($inputMonth)->format('m'));
            ->whereBetween('start_time', array($firstDate->format('Y-m-d'), $lastDate->format('Y-m-d')));
        if ($location && $location != 'all'){
            $events->where('resources.location_id', '=', $location);
        }

        $events = $events->get();


        foreach ($events as $event)
        {

            $formatStartTime = date_create($event->start_time)->format('g:i a');
            $formatEndTime = date_create($event->end_time)->format('g:i a');

            $roomsList = null;
            if($rooms == 'true') {
                $roomsList = "<br>(". mb_strimwidth($event->eventRooms(), 0, 40, "..."). ")";
            }
            $statusFlag = " ". $event->statusImage();
            $simSpecialistNeeded = " ". $event->SimSpecialistNeededYN();
            $specialRequirements = " ". $event->specialRequirementsNeeded();
            $unresolvedComment = " ". $event->notResolved();
            $formatEvent['id'] = $event->id;
            $formatEvent['start'] = $event->start_time;
            $formatEvent['end'] = $event->end_time;
            $formatEvent['html'] = "<div style='background-color:". $event->eventColor() . "; position: absolute; height:4px; left: 0px; width: 100%; top: 0px;'></div><p style='line-height:110%; padding-top:3px;'>". $formatStartTime. " - " .$formatEndTime.$statusFlag.$simSpecialistNeeded.$specialRequirements.$unresolvedComment."<br><b data-toggle='tooltip' title='".$event->CourseInstance->Course->name."'>" .mb_strimwidth($event->CourseAbbrvEventAbbrv, 0, 20, "..."). "</b><br><span data-toggle='tooltip' title='" . $event->eventRooms()."'>" .$event->InitialMeetingRoom->abbrv. $roomsList . "</span></p>";

            // check if course is to be hidden on calendar
            $hideCourse = Course::find($event->courseInstance->course->id)->getCourseOption(2);
            if(!$hideCourse) {
                $monthEvents[] = $formatEvent;
            }
        }

        return view('calendar.month', compact('monthEvents', 'inputMonth'));
    }


    public function getAgenda()
    {
        $buildings = Building::pluck('abbrv', 'id')->toArray();
        $locations = Location::pluck('abbrv', 'id')->toArray();

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();

        return view('calendar.agenda', compact('buildings','locations', 'statusTypes'));
    }

    public function agendaData(Request $request)
    {

        $events = Event::join('resources', 'resources.id', '=', 'initial_meeting_room')
            ->join('locations', 'locations.id', '=', 'resources.location_id')
            ->join('course_instances', 'course_instances.id', '=', 'course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->where('courses.site_id', SESSION::get('site_id'))
            ->where('events.deleted_at', '=', null)
            ->orderby('start_time')
            ->orderby('courses.abbrv')
            ->select('events.*');

        if ($request->has('status'))
        {
            if ($request->get('status') != null)
            {
                $events->where('events.status_type_id', '=', $request->get('status'));
            }
        }

        if ($start_date = $request->get('start_date')) {
            $events->whereDate('start_time', '>=', $start_date);
        }

        if ($end_date = $request->get('end_date')) {
            $events->whereDate('start_time', '<=', $end_date);
        }

        if ($resolved = $request->get('resolved')) {
            if($resolved == 'true') {
                $events->where('resolved', '=', 0);
            }
        }

        if ($request->has('building') && $request->get('building') != 'all'){
            $events->where('locations.building_id', '=', $request->get('building'));
        }

        if ($request->has('location' ) && $request->get('location') != 'all'){
            $events->where('resources.location_id', '=', $request->get('location'));
        }

        //search internal notes if they have permission
        if ($request->has('search'))
        {
            if (access()->user() != null && access()->user()->hasPermission('scheduling')) {
                $search = $request->get('search');
                $events->where(function ($query) use ($search) {
                    $query->where('courses.abbrv', 'like', '%' . $search . '%')
                        ->orWhere('courses.name', 'like', '%' . $search . '%')
                        ->orWhere('public_comments', 'like', '%' . $search . '%')
                        ->orWhere('internal_comments', 'like', '%' . $search . '%')
                        ->orWhere('resources.abbrv', 'like', '%' . $search . '%')
                        ->orWhere('events.abbrv', 'like', '%' . $search . '%');
                });
            }
            else {
                $search = $request->get('search');
                $events->where(function ($query) use ($search) {
                    $query->where('courses.abbrv', 'like', '%' . $search . '%')
                        ->orWhere('courses.name', 'like', '%' . $search . '%')
                        ->orWhere('public_comments', 'like', '%' . $search . '%')
                        ->orWhere('resources.abbrv', 'like', '%' . $search . '%')
                        ->orWhere('events.abbrv', 'like', '%' . $search . '%');
                });
            }
        }

        $events->get();

        return DataTables::of($events)
            ->addColumn('building_abbrv', function($events) {
                return $events->initialMeetingRoom->location->building->abbrv;
            })
            ->addColumn('location_abbrv', function($events) {
                return $events->initialMeetingRoom->location->abbrv;
            })
            ->addColumn('courses.abbrv', function($events) {
                return $events->CourseAbbrvEventAbbrv;
            })
            ->addColumn('color', function($events) {
                return '<span style="color:'. $events->eventColor() .';"><i class="fas fa-circle"></i></span>';
            })
            ->addColumn('status', function($events) {
                return $events->statusImage();
            })
            ->addColumn('specialist', function($events) {
                return $events->simSpecialistNeededYN();
            })
            ->addColumn('special_requirements', function($events) {
                return $events->specialRequirementsNeeded();
            })
            ->addColumn('not_resolved', function($events) {
                return $events->notResolved();
            })
            ->addColumn('mtg_rm_abbrv', function($events) {
                return $events->InitialMeetingRoom->abbrv;
            })
            ->addColumn('notes', function($events) {
                $notes = null;
                //display public notes if they exist
                if($events->public_comments != null)
                {
                    $notes .= "<p><strong>" . trans('labels.scheduling.pub_comments') .":</strong></p><p>" . $events->public_comments . "</p>";
                }
                //display internal notes if they exist, permissions on who can view internal notes are checked in event model
                if($events->viewInternalComments() != null)
                {
                    $notes .= "<p><strong>" . trans('labels.scheduling.internal_comments') .":</strong></p><p>" . $events->viewInternalComments() . "</p>";
                }
                return $notes;
            })
            // We are hiding this for now -jl
            // ->addColumn('event_group', function($events) {
            //     if($events->eventXofY()<>'1 of 1')
            //     {return $events->eventXofY() . ' (ID:' . $events->course_instance_id .')';}
            //     else
            //     {return '';}
            // })
            ->addColumn('date', function($events) {
                return $events->DisplayDateStartEndTimes;
            })
            ->addColumn('date_sort', function($events) {
                return $events->start_time;
            })
            ->addColumn('start_time', function($events) {
                return date_create($events->start_time)->format('g:ia');
            })
            ->addColumn('end_time', function($events) {
                return date_create($events->end_time)->format('g:ia');
            })
            ->addColumn('actions', function($events) {
                return $events->getAgendaButtonsAttribute(); //app/model/event
            })
            ->rawColumns(['notes','actions', 'color', 'status', 'specialist', 'special_requirements', 'not_resolved'])
            ->make(true);
    }


    //This function populates the data table for the "today's events" section of the Dashboard

    public function dayData(Request $request)
    {

        $events = Event::join('resources', 'resources.id', '=', 'initial_meeting_room')
            ->join('locations', 'locations.id', '=', 'resources.location_id')
            ->join('course_instances', 'course_instances.id', '=', 'course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->where('courses.site_id', SESSION::get('site_id'))
            ->where('events.deleted_at', '=', null)
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                    ->from('course_option')
                    ->whereRaw('course_option.course_id = courses.id')
                    ->where('course_option.option_id', 2);
            })
            ->orderby('start_time')
            ->orderby('courses.abbrv')
            ->select('events.*');

        if ($start_date = $request->get('start_date')) {
            $events->whereDate('start_time', '=', $start_date);
        }

        if ($request->has('location') && $request->get('location') != 'all'){
            $events->where('resources.location_id', '=', $request->get('location'));
        }

        $events->get();

        return DataTables::of($events)
            ->addColumn('time', function($events) {
                return date_create($events->start_time)->format('g:ia') . ' - ' . date_create($events->end_time)->format('g:ia');
            })
            ->addColumn('color', function($events) {
                return '<span style="color:'. $events->eventColor() .';"><i class="fas fa-circle"></i></span>';
            })
            ->addColumn('status', function($events) {
                return $events->statusImage();
            })
            ->addColumn('specialist', function($events) {
                return $events->simSpecialistNeededYN();
            })
            ->addColumn('notes', function($events) {
                $notes = null;
                //display public notes if they exist
                if($events->public_comments != null)
                {
                    $notes .= "<p><strong>" . trans('labels.scheduling.pub_comments') .":</strong></p><p>" . $events->public_comments . "</p>";
                }
                //display internal notes if they exist, permissions on who can view internal notes are checked in event model
                if($events->viewInternalComments() != null)
                {
                    $notes .= "<p><strong>" . trans('labels.scheduling.internal_comments') .":</strong></p><p>" . $events->viewInternalComments() . "</p>";
                }
                return $notes;
            })
            ->addColumn('special_requirements', function($events) {
                return $events->specialRequirementsNeeded();
            })
            ->addColumn('not_resolved', function($events) {
                return $events->notResolved();
            })
            ->addColumn('images', function($events) {
                return '<span style="color:'. $events->eventColor() .';"><i class="fa-lg fas fa-circle"></i></span> '.
                    $events->statusImage() . ' ' .
                    $events->simSpecialistNeededYN() . ' ' .
                    $events->specialRequirementsNeeded() . ' ' .
                    $events->notResolved();
            })
            ->addColumn('course_name', function($events) {
                return $events->DisplayEventNameShort;
            })
            ->addColumn('courses.abbrv', function($events) {
                return $events->CourseAbbrvEventAbbrv;
            })
            ->addColumn('building_location', function($events) {
                return $events->initialMeetingRoom->location->building->abbrv. ' - ' .$events->initialMeetingRoom->location->abbrv;
            })
            ->addColumn('initial_meeting_room', function($events) {
                return $events->InitialMeetingRoom->abbrv;
            })
            ->addColumn('building_location_room', function($events) {
                return $events->initialMeetingRoom->location->building->abbrv. ' - ' .$events->initialMeetingRoom->location->abbrv . ' ' . $events->InitialMeetingRoom->abbrv;
            })
            ->addColumn('event_rooms', function($events) {
                return $events->eventRooms();
            })
            ->addColumn('actions', function($events) {
                return $events->action_buttons; //app/model/event
            })
            ->rawColumns(['actions', 'images', 'color', 'status', 'specialist', 'special_requirements', 'not_resolved', 'notes'])
            ->make(true);
    }

    public function getLocations($id)
    {

        $locations = Location::orderBy('abbrv')->where('building_id', $id)->pluck('abbrv', 'id');

        return json_encode($locations);

    }


    /**
     * From https://gist.github.com/stecman/0203410aa4da0ef01ea9
     * Find the starting Monday for the given week (or for the current week if no date is passed)
     *
     * This is required as by default in PHP, strtotime considers Sunday the first day of a week,
     * making strtotime('Monday this week') on a Sunday return the adjacent Monday instead of the
     * previous one.
     *
     * @param string|\DateTime|null $date
     * @return \DateTime
     */
    public function getStartOfWeekDate($date = null)
    {
        if ($date instanceof \DateTime) {
            $date = clone $date;
        } else if (!$date) {
            $date = new \DateTime();
        } else {
            $date = new \DateTime($date);
        }

        $date->setTime(0, 0, 0);

        if ($date->format('N') == 1) {
            // If the date is already a Monday, return it as-is
            return $date;
        } else {
            // Otherwise, return the date of the nearest Monday in the past
            // This includes Sunday in the previous week instead of it being the start of a new week
            return $date->modify('last monday');
        }
    }


    /**
     * Display the Welcome Board aka Lobby Display
     *
     * @param  int  $locations
     * @return \Illuminate\Http\Response
     */
    public function getWelcomeBoard($locations = null)
    {

        $siteAbbrv = Site::find(SESSION::get('site_id'))->abbrv;
        $currentDate = Carbon::now()->format('l, F d, Y');

        $currentUrl = url()->current();
        $currentUrl = explode('/', $currentUrl);
        $currentUrl = $currentUrl[3];

        $darkBackground = false;
        if($currentUrl == "welcomeDark")
        {
            $darkBackground = true;
        }

        return view('calendar.welcomeBoard', compact('locations', 'siteAbbrv', 'currentDate', 'darkBackground'));

    }

    /**
     * Get the Welcome Board Event Data
     *
     * @param  int  $locations
     * @return \Illuminate\Http\Response
     */
    public function getWelcomeBoardData($locations = null)
    {

        $countLocations = 0;
        $countBuildings = 0;

        if($locations != null)
        {
            $arrayLocations = explode(',', $locations);
        }

        //get today's events for this location(s),
        // hide if course option hide_course_welcome_board checked
        // exclude if end_time is less than now

        $events = Event::join('resources', 'resources.id', '=', 'initial_meeting_room')
            ->join('locations', 'locations.id', '=', 'resources.location_id')
            ->join('course_instances', 'course_instances.id', '=', 'course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->where('courses.site_id', SESSION::get('site_id'))
            ->where('events.deleted_at', '=', null)
            ->whereDate('start_time', '=', Carbon::today())
            ->whereTime('end_time', '>=', Carbon::now()->toTimeString())
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                    ->from('course_option')
                    ->whereRaw('course_option.course_id = courses.id')
                    ->where('course_option.option_id', 12);
            })
            ->orderby('start_time')
            ->orderby('courses.abbrv')
            ->select('events.*');

        if ($locations != null){
            $events->whereIn('resources.location_id', $arrayLocations);
        }

        $events = $events->get();

        //mitcks 2021-04-21 Change requested to only display bldg/location when more than 1 location
        // in returned dataset (as opposed to the string of locations passed in) so loop through here to count
        $lastRoom = "";
        foreach ($events as $index => $event)
        {
            if( $index > 0)
            {
                if ($lastRoom != $event->initialMeetingRoom->location->id)
                {
                    $countLocations++;
                }
            }
            $lastRoom = $event->initialMeetingRoom->location->id;
        }

        //mitcks 2021-06-05 Adding to check if there is more than one building
        $lastRoom = "";
        foreach ($events as $index => $event)
        {
            if( $index > 0)
            {
               if ($lastRoom != $event->initialMeetingRoom->location->building->id)
               {
                   $countBuildings++;
               }
            }
            $lastRoom = $event->initialMeetingRoom->location->building->id;
        }

        $eventData = "";

        foreach ($events as $event) {

            $eventData .= "<blockquote class='bg-light' style='border-left: 10px solid ". $event->eventColor() ."'>
                        <div class='row'>
                            <div class='col-12'>
                                <div class='float-left ml-2'>
                                    <p class='h4'>" . $event->DisplayStartEndTimes . "
                                    <span class='h6 text-muted ml-5'>". $event->SimSpecialistNeededYN() .
                                    " " . $event->specialRequirementsNeeded().
//                                        " " . $event->statusImage(). //not sure if this should be displayed?
                                    "</span></p>
                                    <p class='h5 text-bold'>" . $event->CourseAbbrvEventAbbrv . "</p>
                                    <p class='h6 text-muted'>" . $event->public_comments . "</p>
                                </div>
                                <div class='float-right'>
                                    <p class='h4 text-right'>" . $event->getDisplayIMR(true) . "</p>";
                                    if(!$countBuildings == 0 AND !$countLocations == 0)
                                    {
                                        $eventData .= "<p class='h6 text-muted text-right'>" . $event->getDisplayIMRBldgLocation() . "</p>";

                                    }
                                    if($countBuildings == 0 AND !$countLocations == 0)
                                    {
                                        $eventData .= "<p class='h6 text-muted text-right'>" . $event->initialMeetingRoom->location->abbrv . "</p>";
                                    }
                                $eventData .= "</div>
                            </div>
                        </div>
                    </blockquote>";
        }

        if($events->count() == 0)
        {
            $eventData = "<blockquote class='bg-light'>" . trans('strings.frontend.welcome_board_no_data') . "</blockquote>";
        }

        return $eventData;

    }

}
