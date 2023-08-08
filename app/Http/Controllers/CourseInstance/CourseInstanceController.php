<?php

namespace App\Http\Controllers\CourseInstance;

use App\Http\Controllers\Controller;
// Using the other CourseInstance Controller to get Grid (locations and resources) info
//use App\Http\Controllers\CourseInstance\OldCourseInstanceController;
use App\Models\Building\Building;
use App\Models\CourseContent\QSE\EventQSEActivation;
use App\Models\CourseContent\QSE\QSE;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourceType;
use App\Models\Site\Site;
use App\Models\Resource\ResourceCategory;
use App\Models\Resource\ResourceSubCategory;
use App\Http\Requests\CourseInstance\StoreCourseInstanceRequest2;
use App\Models\Course\Course;
use App\Models\Course\CourseEmails;
use App\Models\Course\CourseOption;
use App\Models\Course\CourseTemplate;
use App\Models\Course\CourseTemplateResource;
use App\Models\CourseInstance\CourseInstance;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventStatusType;
use App\Models\CourseInstance\EventEmails;
use App\Models\CourseInstance\EventResource;
use App\Models\CourseInstance\ScheduleRequest;
use App\Models\Location\Location;
use App\Models\Location\LocationSchedulers;
use App\Models\Access\User\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Session;
use Mail;
use App\Mail\scheduleApprovalNotification;
use Yajra\DataTables\Facades\DataTables;



class CourseInstanceController extends Controller
{
    public function create(Request $request)
    {
        $courseId = 0;
        $requestId = 0;
        $templateList = null;
        $setupTime = 0;
        $teardownTime = 0;
        $instructorReport = 0;
        $instructorLeave = 0;
        $color = null;
        $date = Carbon::now();
        $classSize = 0;
        $simsSpecNeeded = 0;
        $specialRequirements = 0;
        $statusTypeId = 0;
        $templateId = 0;
        $initialMeetingRoom = 0;
        $publicComments = null;
        $internalComments = null;
        $courseInstance = null;
        $abbrv = null;

        $startTime = Carbon::createFromFormat('G', Session::get('business_start_hour'))->format('H:i');
        $endTime = Carbon::createFromFormat('G', Session::get('business_end_hour'))->format('H:i');
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        $agent = new Agent();

        //if date is passed (format must be YYYY-MM-DD) in use it as default event date, else use today's date
        //example URL with date /courseInstance/main/create/?date=2019-07-05
        if ($request->has('date')) {
            $date = $request->date;//Carbon::parse($request->date)->format('Y-m-d');//
        }

        //if course is passed in set course dropdown to this course and set other defaults from course options
        if ($request->has('course_id')) {
            $courseId = $request->course_id;

            //$templateList = CourseTemplate::where('course_id', $courseId)->orderBy('name')->get();
            $templateList = $this->getTemplateListForSchedulersLocations($courseId);
            
            $setupTime = Course::find($courseId)->getCourseOption(8);
            $teardownTime = Course::find($courseId)->getCourseOption(9);
            $instructorReport = Course::find($courseId)->getCourseOption(10);
            $instructorLeave = Course::find($courseId)->getCourseOption(11);
            $color = Course::find($courseId)->getCourseOption(7);
        }
        
        $IMR_List = $this->getIMRListForSchedulersLocations();
        $editable_resources = $this->getResourceIDForGrid();
        
        //get courses for dropdown
        $courses = Course::orderBy('abbrv')
        ->where(['retire_date' => NULL])
        ->get();

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();

        //if IMR_List is empty then that means they cannot schedule at any locations, display alert
        if($IMR_List)
        {
            return view('courseInstance.main.create', compact('courses', 'date', 'abbrv', 'startTime',
                'endTime', 'setupTime', 'teardownTime', 'IMR_List', 'instructorReport', 'instructorLeave', 'courseId',
                'templateList', 'color', 'classSize', 'simsSpecNeeded', 'templateId', 'publicComments',
                'internalComments', 'specialRequirements', 'initialMeetingRoom', 'courseInstance', 'requestId', 'locationsAndResources',
                'agent', 'editable_resources', 'statusTypes', 'statusTypeId'));
        }
        else
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
        }
    }

    public function dateTest(Request $request)
    {
        return view('courseInstance.main.dateTest');
    }

    public function fromRequest($requestId)
    {

        $courseInstance = null;
        $agent = new Agent();
        $scheduleRequest = ScheduleRequest::findOrFail($requestId);
        $courseId = $scheduleRequest->course_id;
        $templateList = $this->getTemplateListForSchedulersLocations($courseId);
        $date = Carbon::parse($scheduleRequest->start_time)->format('Y-m-d');
        $templateId = $scheduleRequest->template_id;

        //note per JL: the following items set in request take precedence over those in requested template
        $classSize = $scheduleRequest->class_size;
        $startTime = Carbon::parse($scheduleRequest->start_time)->format('H:i');
        $endTime = Carbon::parse($scheduleRequest->end_time)->format('H:i');
        $simsSpecNeeded = $scheduleRequest->sims_spec_needed;
        $statusTypeId = 0;

        $scheduleRequest_id = $scheduleRequest->id;

        $editable_resources = $this->getResourceIDForGrid();

        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);

        $abbrv = null;

        //set default values from course options if they exist
        $setupTime = Course::find($courseId)->getCourseOption(8);
        $teardownTime = Course::find($courseId)->getCourseOption(9);
        $instructorReport = Course::find($courseId)->getCourseOption(10);
        $instructorLeave = Course::find($courseId)->getCourseOption(11);
        $color = Course::find($courseId)->getCourseOption(7);
        
        $specialRequirements = 0;
        $initialMeetingRoom = 0;
        $publicComments = null;
        $internalComments = null;
        $courseInstance = null;

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();

        // If there are notes, prepend them to the Internal Comments. -jl 2019-10-16 18:13
        if ($scheduleRequest->notes)
        {
            $internalComments .= '<p>***Special Request:<br>' .$scheduleRequest->notes. '<br>***</p>';
        }
        
        //if template was set in request go find the remaining defaults from template (note that a template overrides course options set above)
        if ($templateId > 0) {
            $template = CourseTemplate::find($templateId);

            if ($template != null)
            {
                $publicComments = $template->public_comments;
                //Keep any current internal comments from a request and add the template comments -jl 2019-10-16 18:02
                $internalComments .= $template->internal_comments;
                $color = $template->color;
                $setupTime = $template->setup_time;
                $teardownTime = $template->teardown_time;
                $instructorReport = $template->fac_report;
                $instructorLeave = $template->fac_leave;
                $specialRequirements = $template->special_requirements;
                $initialMeetingRoom = $template->initial_meeting_room;
            }
        }

        $IMR_List = $this->getIMRListForSchedulersLocations();

        //get courses for dropdown
        $courses = Course::orderBy('abbrv')
            ->where(['retire_date' => NULL])
            ->get();

        //if IMR_List is empty then that means they cannot schedule at any locations, display alert
        if($IMR_List)
        {
            return view('courseInstance.main.fromRequest', compact('courses', 'date', 'abbrv', 'startTime',
                'endTime', 'setupTime', 'teardownTime', 'IMR_List', 'instructorReport', 'instructorLeave', 'courseId',
                'templateList', 'color', 'classSize', 'simsSpecNeeded', 'templateId', 'publicComments',
                'internalComments', 'specialRequirements', 'initialMeetingRoom', 'courseInstance', 'agent', 'locationsAndResources',
                'editable_resources', 'scheduleRequest', 'statusTypes', 'statusTypeId'));
        }
        else
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
        }
    }

    public function anotherEvent($eventId)
    {
        $templateId = 0;
        $agent = new Agent();

        $event = Event::findOrFail($eventId);
        $date = Carbon::parse($event->start_time)->format('Y-m-d');
        $courseId = $event->courseInstance->course_id;
        $courseInstance = CourseInstance::find($event->course_instance_id);
        //$templateList = CourseTemplate::where('course_id',$courseId)->orderBy('name')->get();
        $templateList = $this->getTemplateListForSchedulersLocations($courseId);
        $abbrv = $event->abbrv;
        $classSize = $event->class_size;
        $startTime = Carbon::parse($event->start_time)->format('H:i');
        $endTime = Carbon::parse($event->end_time)->format('H:i');
        $simsSpecNeeded = $event->sims_spec_needed;
        $specialRequirements = $event->special_requirements;
        $statusTypeId = $event->status_type_id;
        $color = $event->color;
        $setupTime = $event->setup_time;
        $teardownTime = $event->teardown_time;
        $instructorReport = $event->fac_report;
        $instructorLeave = $event->fac_leave;
        $initialMeetingRoom = $event->initial_meeting_room;
        $publicComments = $event->public_comments;
        $internalComments = $event->internal_comments;
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        $editable_resources = $this->getResourceIDForGrid();
        $IMR_List = $this->getIMRListForSchedulersLocations();
        $event_resources = EventResource::where('event_id', '=', $eventId)->get();

        //get courses for dropdown
        $courses = Course::orderBy('abbrv')
            ->where(['retire_date' => NULL])
            ->get();

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();

        //if IMR_List is empty then that means they cannot schedule at any locations, display alert
        if($IMR_List)
        {
            return view('courseInstance.main.anotherEvent', compact('event', 'courses', 'abbrv', 'date', 'startTime',
                'endTime', 'setupTime', 'teardownTime', 'IMR_List', 'instructorReport', 'instructorLeave', 'courseId',
                'templateList', 'color', 'classSize', 'simsSpecNeeded', 'templateId', 'publicComments',
                'internalComments', 'specialRequirements', 'initialMeetingRoom', 'courseInstance', 'locationsAndResources',
                'agent', 'editable_resources','event_resources', 'statusTypes', 'statusTypeId'));
        }
        else
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
        }
    }

    public function duplicateEvent($eventId)
    {
        $templateId = 0;
        $courseInstance = 0;
        $agent = new Agent();

        $event = Event::findOrFail($eventId);
        $date = Carbon::parse($event->start_time)->format('Y-m-d');
        $courseId = $event->courseInstance->course_id;
        //$templateList = CourseTemplate::where('course_id',$courseId)->orderBy('name')->get();
        $templateList = $this->getTemplateListForSchedulersLocations($courseId);
        $classSize = $event->class_size;
        $abbrv = $event->abbrv;
        $startTime = Carbon::parse($event->start_time)->format('H:i');
        $endTime = Carbon::parse($event->end_time)->format('H:i');
        $simsSpecNeeded = $event->sims_spec_needed;
        $specialRequirements = $event->special_requirements;
        $statusTypeId = $event->status_type_id;
        $color = $event->color;
        $setupTime = $event->setup_time;
        $teardownTime = $event->teardown_time;
        $instructorReport = $event->fac_report;
        $instructorLeave = $event->fac_leave;
        $initialMeetingRoom = $event->initial_meeting_room;
        $publicComments = $event->public_comments;
        $internalComments = $event->internal_comments;
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        $editable_resources = $this->getResourceIDForGrid();

        $event_resources = EventResource::where('event_id', '=', $eventId)->get();

        $IMR_List = $this->getIMRListForSchedulersLocations();

        //get courses for dropdown
        $courses = Course::orderBy('abbrv')
            ->where(['retire_date' => NULL])
            ->get();

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();

        //if IMR_List is empty then that means they cannot schedule at any locations, display alert
        if($IMR_List)
        {
            return view('courseInstance.main.duplicateEvent', compact('event', 'courses', 'date', 'abbrv', 'startTime',
                'endTime', 'setupTime', 'teardownTime', 'IMR_List', 'instructorReport', 'instructorLeave', 'courseId',
                'templateList', 'color', 'classSize', 'simsSpecNeeded', 'templateId', 'publicComments',
                'internalComments', 'specialRequirements', 'initialMeetingRoom', 'courseInstance', 'locationsAndResources',
                'event_resources', 'agent', 'editable_resources', 'statusTypes', 'statusTypeId'));
        }
        else
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
        }
    }

    public function editEvent($eventId)
    {

        $user = Auth::user();

        $templateId = 0;
        $courseInstance = 0;
        $agent = new Agent();

        $event = Event::findOrFail($eventId);
        $date = Carbon::parse($event->start_time)->format('Y-m-d');

        $courseId = $event->courseInstance->course_id;
        //$templateList = CourseTemplate::where('course_id',$courseId)->orderBy('name')->get();
        $templateList = $this->getTemplateListForSchedulersLocations($courseId);
        $abbrv = $event->abbrv;
        $classSize = $event->class_size;
        $startTime = Carbon::parse($event->start_time)->format('H:i');
        $endTime = Carbon::parse($event->end_time)->format('H:i');
        $simsSpecNeeded = $event->sims_spec_needed;
        $specialRequirements = $event->special_requirements;
        $color = $event->color;
        $statusTypeId = $event->status_type_id;
        $setupTime = $event->setup_time;
        $teardownTime = $event->teardown_time;
        $instructorReport = $event->fac_report;
        $instructorLeave = $event->fac_leave;
        $initialMeetingRoom = $event->initial_meeting_room;
        $publicComments = $event->public_comments;
        $internalComments = $event->internal_comments;
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        $editable_resources = $this->getResourceIDForGrid();

        // Field For DayPilot to Determine if Event Resoruces we pull can be edited
        //(mitcks) this field actually indicates that you are in either the editEvent or templateApply views
        $editable = true;

        $IMR_List = $this->getIMRListForSchedulersLocations();

        //Check to see if this user is in location_schedulers for the IMR location
        $userCanEdit = LocationSchedulers::where('user_id', $user->id)
                                            ->where('location_id', $event->InitialMeetingRoom->location_id)
                                            ->first();

        //get courses for dropdown
        $courses = Course::orderBy('abbrv')
            ->where(['retire_date' => NULL])
            ->get();

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();

        //if IMR_List is empty then that means they cannot schedule at any locations, display alert
        if($IMR_List) {
            if ($userCanEdit)
            {
            return view('courseInstance.main.editEvent', compact('courses', 'date', 'abbrv', 'startTime',
                'endTime', 'setupTime', 'teardownTime', 'IMR_List', 'instructorReport', 'instructorLeave', 'courseId',
                'templateList', 'color', 'classSize', 'simsSpecNeeded', 'templateId', 'publicComments',
                'internalComments', 'specialRequirements', 'initialMeetingRoom', 'courseInstance', 'event', 'locationsAndResources',
                'editable', 'agent', 'editable_resources', 'statusTypes', 'statusTypeId'));
            }
            else
            {
                return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_event_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
            }
        }
        else
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
        }
    }

    /**
     * TEMPLATE COMPARE
     * this displays the view to compare event's current values to the selected template values
     */
    public function templateCompare($templateId, $eventId)
    {
        //dd($eventId.' '.$templateId);
        $event = Event::findOrFail($eventId);
        $template = CourseTemplate::findOrFail($templateId);

        return view('courseInstance.main.templateCompare', compact('template', 'event'));
    }

    /**
     * TEMPLATE APPLY
     * returns the user to edit event with the values they selected from template applied
     */
    public function templateApply(Request $request)
    {
        //logged in user
        $user = Auth::user();

        if(strpos($request->currLoc, 'editEvent') != false)
        {
            $returnView = 'courseInstance.main.editEvent';
        }
        elseif(strpos($request->currLoc, 'duplicateEvent') != false)
        {
            $returnView = 'courseInstance.main.duplicateEvent';
        }
        else
        {
            //this can happen if they redirected to /courseInstance/main/templateApply then change template again
            $returnView = 'courseInstance.main.editEvent';
        }

        //event selected for editing
        if(empty($request->event_id))
        {
            $event = null;
        }
        else
        {
            $event = Event::findOrFail($request->event_id);
        }

        //this is the flag for event only, template only or merge resources
        //possible values are event_resources, template_resources or merge_resources
        $resources = $request->resources;

        $courseInstance = 0; //todo: not sure what this is for?
        $agent = new Agent(); //todo: ditto

        $date = Carbon::parse($request->start_date)->format('Y-m-d'); //used to set date picker in partial-form

        $courseId = $request->course_id; //used to set course dropdown in partial-form

        //this is for the template dropdown
        $templateList = $this->getTemplateListForSchedulersLocations($courseId);
        $templateId = $request->template_id;
        $template = CourseTemplate::findOrFail($templateId);

        //the following values are coming from templateCompare, depending on which options they selected they are either
        //the value from the event model or from the template.  In the case of comments and resources they also have the option to "merge" values
        $abbrv = $request->abbrv;
        $classSize = $request->numParticipants;
        $startTime = Carbon::parse($request->start_time)->format('H:i');
        $endTime = Carbon::parse($request->end_time)->format('H:i');
        $simsSpecNeeded = $request->sims_spec_needed;
        $specialRequirements = $request->special_requirements;

        if($request->html_color == 'html_color') //checking for this because it returns the text 'html_color' when null?
        {
            $color = null;
        }
        else
        {
            $color = $request->html_color;
        }

        $setupTime = $request->setup_time;
        $teardownTime = $request->teardown_time;
        $instructorReport = $request->instructor_report;
        $instructorLeave = $request->instructor_leave;

        //(mitcks) If they select to keep existing resources then IMR stays the same,
        // else if they select replace with template or merge existing/template then use template IMR
        if($resources == 'event_resources')
        {
            //todo: (mitcks) I want to use the one from request here, but if I do, it does not display in grid - will try to figure out later
            //$initialMeetingRoom = $request->initial_meeting_room;
            $initialMeetingRoom = $event->initial_meeting_room;
        }
        else
        {
            $initialMeetingRoom = $template->initial_meeting_room;
        }

        //if they chose to merge, combine event and template comments
        if($request->public_comments == 'mergePublicNotes')
        {
            $publicComments = $request->current_public_comments . " " . $template->public_comments;
        }
        else
        {
            $publicComments = $request->public_comments;
        }

        if($request->internal_comments == 'mergeInternalNotes')
        {
            $internalComments = $request->current_internal_comments . "<br>" . $template->internal_comments;
        }
        else
        {
            $internalComments = $request->internal_comments;
        }

        //these are for row headings in grid
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);

        //This is an array of all the resources that the currently logged in user can schedule (based on `location_schedulers` table
        $editable_resources = $this->getResourceIDForGrid();

        // Field For DayPilot to Determine if Event Resources we pull can be edited
        //(mitcks) this field actually indicates that you are in either the editEvent or templateApply views
        $editable = true;

        //for IMR dropdown
        $IMR_List = $this->getIMRListForSchedulersLocations();


        //get courses for dropdown (mitcks: not really needed on edit since it's disabled on edit? - todo: try removing when time allows)
        $courses = Course::orderBy('abbrv')
            ->where(['retire_date' => NULL])
            ->get();

        //get status type options for dropdown
        $statusTypes = EventStatusType::where('site_id', Session::get('site_id'))->orderby('id')->get();
        $statusTypeId = $request->status_type_id;

        //if IMR_List is empty then that means they cannot schedule at any locations, display alert
        if($IMR_List)
        {
            return view($returnView, compact('courses', 'date', 'abbrv', 'startTime',
                'endTime', 'setupTime', 'teardownTime', 'IMR_List', 'instructorReport', 'instructorLeave', 'courseId',
                'templateList', 'color', 'classSize', 'simsSpecNeeded', 'templateId', 'publicComments',
                'internalComments', 'specialRequirements', 'initialMeetingRoom', 'courseInstance', 'event',
                'locationsAndResources', 'editable', 'agent', 'editable_resources', 'resources', 'statusTypes', 'statusTypeId'));
        }
        else
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.scheduling.no_location_access') . '<a href:"mailto:"' . Session::get('site_email') .'">' . Session::get('site_email') . '</a>.');
        }
    }


    /**
     * Store a newly created course instance in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseInstanceRequest2 $request)
    {
        //get and check recurrence dates
        $recurrenceDates = $request['recurrence_dates'];
        $arrayRecurrenceDates = [];
        //dd($recurrenceDates);
        if($recurrenceDates != null)
        {
            $arrayRecurrenceDates = explode(',', $recurrenceDates);
        }
        //loop through recurrence array to check if valid date and convert to yyyy-mm-dd format
        foreach ($arrayRecurrenceDates as $key =>$date) {
            try {
                $date = Carbon::createFromFormat('m-d-Y',$date)->format('Y-m-d');
                $arrayRecurrenceDates[$key] = $date;
            }
            catch (\Exception $err) {
                //remove from array
                unset($arrayRecurrenceDates[$key]);
            }
        }

        //if $request->selectDate (from the single date input) does not already exist in array add it
        if (!in_array($request->selectDate, $arrayRecurrenceDates)) {
            //
            array_push($arrayRecurrenceDates, $request->selectDate);
        }

        //dd(print_r($arrayRecurrenceDates));

        $user = Auth::user();
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;
        $request['resource_events'] = json_decode( $request->resource_events );        

        //save to course_instances table unless adding to existing course_instance
        if($request->has('course_instance_id'))
        {
            $courseInstance = CourseInstance::find($request->course_instance_id);
        }
        else
        {
            $courseInstance = CourseInstance::create($request->all());
            $request['course_instance_id'] = $courseInstance->id;
        }

        //these are the same regardless of recurrence date

        if($request->instructor_report_BA == 'B')
        {$request['fac_report'] = 0 - $request->instructor_report;}
        else
        {$request['fac_report'] = $request->instructor_report;}

        if($request->instructor_leave_BA == 'B')
        {$request['fac_leave'] = 0 - $request->instructor_leave;}
        else
        {$request['fac_leave'] = $request->instructor_leave;}

        if ($request->has('special_requirements'))
        {$request['special_requirements'] = 1;}
        else
        {$request['special_requirements'] = 0;}

        if ($request->has('sims_spec_needed'))
        {$request['sims_spec_needed'] = 1;}
        else
        {$request['sims_spec_needed'] = 0;}

        $request['color'] = $request['html_color'];

        $startTime = $request->start_time; //time for all occurrences (date will change though)
        $endTime = $request->end_time;

        //loop though $arrayRecurrenceDates to add events
        foreach ($arrayRecurrenceDates as $key =>$recurrenceDate) {

            $request['start_time'] = Carbon::parse($recurrenceDate . ' ' . $startTime);
            $request['end_time'] = Carbon::parse($recurrenceDate . ' ' . $endTime);

            $event = Event::create($request->all());

            //get possible QSE for this course to the event
            $active_qses = QSE::whereHas('courseContents', function ($q) use ($event) {
                $q->where('course_id', $event->courseInstance->course_id)
                    ->whereNull('retired_date')
                    ->whereNotNull('published_date');
            })->get();

            //add each possible qse to event_qse_activation table
            foreach ($active_qses as $active_qse) {
                $eventQSEActivation = EventQSEActivation::create(
                    ['event_id' => $event->id,
                    'qse_id' => $active_qse->id,
                    'activation_state' => $active_qse->activation_state,
                    'last_edited_by' => $user->id]);
            }

            //if a template was selected AND this is NOT the original single date selected
            // then instead of using the selected grid resources for each
            // event, loop through the template resources and try to find best available
            if ($request->selectDate != $recurrenceDate && $request->template_id != null) {
                //go get template resources, it is important to order by resource_identifier_type
                // so it books the specific ones first and it doesn't try to use these as alternates for others
                $courseTemplateResources = CourseTemplateResource::where('course_template_id', $request->template_id)
                    ->orderBy('resource_identifier_type')->get();
                //dd($courseTemplateResources);

                foreach ($courseTemplateResources as $templateResource) {

                    //check to see if there is a conflict
                    // (per JL 2020-09-04) using the template resource start and end times here, not the ones selected in the upper portion of create event

                    $resourceToStore = 0; //this ID id set in the loops below based on the following logic:
                    // 1) If the type is specific OR there is no conflict with template resource, use the template resource (schedule any specific resources first so they are not available for subsequent searches)
                    // 2) Else if it is not specific AND there is a conflict, search for an available resource based on category or subcategory
                    //      a) If type is category, loop through all other resources in this location with the same category
                    //          if a non-conflicting resource is found schedule resource, if none are found then schedule original resource
                    //      b) If type is subcategory, loop through all other resources in this location with the same subcategory
                    //          if a non-conflicting resource is found schedule resource, if none are found then schedule original resource

                    //need this to access the hasConflict function
                    $resource = Resource::where('id', $templateResource->resource_id)->first();

                    $templateResourceStartDateTime = Carbon::parse($recurrenceDate . ' ' . $templateResource->StartTimeLessSetup);
                    $templateResourceEndDateTime = Carbon::parse($recurrenceDate . ' ' . $templateResource->EndTimePlusTeardown);

                    //if type is specific OR there is no conflict then book this resource
                    if ($templateResource->resource_identifier_type == 1 || !$resource->hasConflict($recurrenceDate, $templateResourceStartDateTime, $templateResourceEndDateTime)) {
                        $resourceToStore = $templateResource->resource_id;
                    }
                    //there is a conflict, look for possible available resources by CATEGORY
                    elseif ($templateResource->resource_identifier_type == 2) {
                        //get all possible resources for this category and location
                        $resourcesByCategory = Resource::where('resource_category_id', $templateResource->Resources->resource_category_id)
                            ->where('location_id', $templateResource->Resources->location_id)
                            ->orderBy('abbrv')
                            ->get();

                        //loop through to look for resource with no conflict
                        $counter = 0;
                        foreach ($resourcesByCategory as $resource) {
                            $counter++;

                            if (!$resource->hasConflict($recurrenceDate, $templateResourceStartDateTime, $templateResourceEndDateTime)) {
                                //book this resource

                                $resourceToStore = $resource->id;
                                break;
                            }
                            else {
                                //the resource was not available
                                if($counter == $resourcesByCategory->count())
                                {
                                    //there are no alternatives, book original resource even though conflict
                                    $resourceToStore = $templateResource->resource_id;
                                }
                            }
                        }
                    }
                    elseif ($templateResource->resource_identifier_type == 3) {
                        //get all possible resources for this subcategory and location
                        $resourcesBySubCategory = Resource::where('resource_sub_category_id', $templateResource->Resources->resource_sub_category_id)
                            ->where('location_id', $templateResource->Resources->location_id)
                            ->orderBy('abbrv')
                            ->get();
                        //loop through to look for resource with no conflict
                        $counter = 0;
                        foreach ($resourcesBySubCategory as $resource)
                        {
                            $counter++;
                            if (!$resource->hasConflict($recurrenceDate, $templateResourceStartDateTime, $templateResourceEndDateTime)) {
                                //book this resource
                                $resourceToStore = $resource->id;
                                break;
                            }
                            else
                            {
                                if($counter == $resourcesBySubCategory->count())
                                {
                                    //there are no alternatives, book original resource even though conflict
                                    $resourceToStore = $templateResource->resource_id;
                                }
                            }
                        }
                    }

                    //store the event resource
                    $eventResource = EventResource::create(['event_id' => $event->id,
                        'resource_id' => $resourceToStore,
                        'start_time' => Carbon::parse($recurrenceDate . ' ' . $templateResource->start_time),
                        'end_time' => Carbon::parse($recurrenceDate . ' ' . $templateResource->end_time),
                        'setup_time' => $templateResource->setup_time,
                        'teardown_time' => $templateResource->teardown_time,
                        'isIMR' => $templateResource->isIMR,
                        'conflict_ignored' => 0,
                        'created_by' => $user->id,
                        'last_edited_by' => $user->id]);

                    //if this resource was the IMR, check to see if the event record IMR needs updated to match
                    if($templateResource->isIMR == 1 && $resourceToStore !== $event->initial_meeting_room)
                    {
                        $event->update(['initial_meeting_room' => $eventResource->resource_id]);
                    }
                }
            }
            else
            {
                // Add Event Resources based on grid
                foreach ($request->resource_events as $e) {

                    //need to get just the time from the grid array because date changes for each recurrence
                    $resourceStartTime = Carbon::parse($e->start)->toTimeString();
                    $resourceStartDateTime = Carbon::parse($recurrenceDate . ' ' . $resourceStartTime);
                    $resourceEndTime = Carbon::parse($e->end)->toTimeString();
                    $resourceEndDateTime = Carbon::parse($recurrenceDate . ' ' . $resourceEndTime);
                    //dd($e->start, $e->setup, $resourceStartTime , $resourceEndTime);

                    $eventResource = EventResource::create(['event_id' => $event->id, 'resource_id' => $e->resource, 'start_time' => $resourceStartDateTime, 'end_time' => $resourceEndDateTime, 'setup_time' => $e->setup, 'teardown_time' => $e->teardown, 'isIMR' => $e->isIMR, 'conflict_ignored' => 0, 'created_by' => $user->id, 'last_edited_by' => $user->id]);
                }
            }

            //if this derived from a request then update the schedule_requests table
            $linkToPendingRequests = "";

            if($request->has('request_id')) {
                if ($request->request_id > 0) {
                    $scheduleRequest = ScheduleRequest::find($request->request_id);
                    $scheduleRequest->event_id = $event->id;
                    $scheduleRequest->save();

                    $linkToPendingRequests = "<a href = " . url('/scheduleRequest/pending') .">" .  trans('alerts.frontend.scheduling.return_to_pending') . "</a>";

                    //send approval email to requestor
                    Mail::to(User::find($scheduleRequest->requested_by))->send(new scheduleApprovalNotification($event));
                }
            }


            $displayDate = Carbon::parse($event->start_time)->format('m/d/Y h:i A');


            $course_email_table_data = CourseEmails::where('course_emails.course_id', $request->course_id)  //for just this course
                ->whereHas('emailType', function($q) {
                    $q->where('type', 3);  //Just event emails.
                    })
                ->get();


            foreach ($course_email_table_data as $course_email) {
                $request['email_type_id'] = $course_email->emailType->id;
                // Determine send_at date given course_email options
                $send_at = null;
                if ($course_email->emailType->id == 8) {  //Automatically send emails.
                    $date;
                    $amount = 0;
                    switch ($course_email->time_offset) {
                        case 1: // b4 start
                            $date = $event->start_time;
                            $amount = -abs($course_email->time_amount);
                            break;
                        case 2: // after start
                            $date = $event->start_time;
                            $amount = abs($course_email->time_amount);
                            break;
                        case 3: //  b4 end
                            $date = $event->end_time;
                            $amount = -abs($course_email->time_amount);
                            break;
                        case 4: // after end
                            $date = $event->end_time;
                            $amount = abs($course_email->time_amount);
                            break;
                        default:
                            // default start time
                            $date = $event->start_time;
                            $amount = 0;
                    }

                    $time_type = '';
                    switch ($course_email->time_type) {
                        case 1:
                            $time_type = 'minutes';
                            break;
                        case 2:
                            $time_type = 'hours';
                            break;
                        case 3:
                            $time_type = 'days';
                            break;
                        default:
                            $time_type = 'minutes';
                    }

                    $utcDate = Carbon::createFromFormat('Y-m-d H:i:s', $date, $event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');

                    // Replacing User session timezone check, with the timezone of the building the event will take place.
                    $dateAdj = date_add(Carbon::parse($utcDate, $event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));
                    // Get send_at in UTC so no matter where the location is, we have a specific time to send it.
                    $send_at = Carbon::parse($dateAdj)->format('Y-m-d H:i:s');

                    // get cur time in local via the session
                    $curr = Carbon::now()->timezone('UTC')->format('Y-m-d H:i:s');
                    if ($send_at < $curr) {
                        $course_email["email_type_id"] = 9; //it's too late to send, set it to Send Manually
                        $send_at = null;
                    }
                }

                $eventEmails = EventEmails::firstOrCreate(
                    ['label' => $course_email->label, 'event_id' => $event->id], // values to check
                    [ // Data
                        'event_id' => $event->id, // Active event ID
                        'email_type_id' => $course_email->email_type_id,
                        'course_email_id' => $course_email->id,
                        'label' => $course_email->label,
                        'subject' => $course_email->subject,
                        'body' => $course_email->body,
                        'to_roles' => $course_email->to_roles,
                        'to_other' => $course_email->to_other,
                        'cc_roles' => $course_email->cc_roles,
                        'cc_other' => $course_email->cc_other,
                        'bcc_roles' => $course_email->bcc_roles,
                        'bcc_other' => $course_email->bcc_other,
                        'time_amount' => $course_email->time_amount,
                        'time_type' => $course_email->time_type,
                        'time_offset' => $course_email->time_offset,
                        'role_id' => $course_email->role_id,
                        'role_amount' => $course_email->role_amount,
                        'role_offset' => $course_email->role_offset,
                        'send_at' => $send_at,
                        'created_by' => $course_email->created_by,
                        'last_edited_by' => $user->id, // User that made the update
                        'created_at' => $course_email->created_at,
                        'updated_at' => $course_email->updated_at,
                        'deleted_at' => $course_email->deleted_at
                    ]
                );

            }

        }

//        dd('try stopping here');

        //check to see if more than one date was added
        if(count($arrayRecurrenceDates) > 1)
        {
            //go to the summary page to display all dates and possible conflicts
            return redirect()->route('recurrence_confirmation',[$courseInstance->id])
                ->with('success', trans('alerts.frontend.scheduling.recurrence_created')
                );
        }
        else
        {
            //go to event dashboard for single event
            return redirect()->route('event_dashboard',[$event->id])
                ->with('success', $event->DisplayEventName . ' '
                    . trans('alerts.frontend.scheduling.created')
                    . " " . $linkToPendingRequests
                );
        }

    }


    //populates select for Initial Meeting Room with just type=1 and with locations for logged in user
    private function getIMRListForSchedulersLocations () {

        $site_id = Session::get('site_id');
        $user = Auth::user();

        $resourceList = DB::table('locations')
            ->select('resources.id as resource_id', 'buildings.abbrv as building_abbrv', 'resources.abbrv as resource_abbrv', 'locations.abbrv as location_abbrv')
            ->join('resources','resources.location_id','=','locations.id')
            ->join('buildings','buildings.id','=','locations.building_id')
            ->join('location_schedulers', 'location_schedulers.location_id', '=', 'locations.id')
            ->where(['locations.site_id' => $site_id,
                     'resources.retire_date' => NULL,
                     'resources.resource_type_id' => 1,
                     'location_schedulers.user_id' => $user->id
                    ])
            ->orderBy('buildings.abbrv','ASC')
            ->orderBy('locations.abbrv','ASC')
            ->orderBy('resources.abbrv','ASC')
            ->get();

        $resources = [];
        foreach($resourceList as $location) {
            $resources[$location->resource_id] = $location->building_abbrv . " " . $location->location_abbrv. " " . $location->resource_abbrv;
        }
        return $resources;
    }


    //GET SCHEDULED EVENT RESOURCES FOR DAYPILOT GRID
    //mitcks 2020-03-30 copied this function in from Matt's original CourseInstanceController because it appears to still be used
    //mitcks 2020-05-14 this function IS still used by the daypilot grid to display event_resources already
    // scheduled for a given date.  We had a bug where it wasn't displaying anything on certain dates because Matt
    // was pulling data with a DB statement that included deleted events. Subsequent code could
    // not find related attributes for deleted events and then nothing loaded in the grid.
    // Updated to use the EventResource model (this filters out deleted events via the whereHas).
    // NOTE: still using a select to get/format fields for the JSON file
    // passed to DayPilot.  **Resource_id is renamed to resource and converted from int to char** I do not know
    // yet why this is necessary, but the grid broke when it was an int.
    public static function getEventsForDate($date, $templateApply = null, $IMR = null, $eventId=null, $templateId=null)
    {
        //(2020-07-09 mitcks) if template compare was used and templateOnly selected, then we need to exclude
        // the previously scheduled resources for this event
        if ($templateApply == "templateOnly")
        {
            $data = EventResource::whereHas('Event', function ($q) use ($date, $eventId) {
                $q->whereDate('start_time', date($date))
                ->where('event_id', '<>', $eventId);
            })
                ->select('id', 'resource_id', DB::raw("CONVERT(resource_id, CHAR) as resource"), 'event_id',
                    'event_resources.start_time AS start', 'event_resources.end_time as end',
                    'event_resources.setup_time AS setup', 'event_resources.teardown_time AS teardown', 'isIMR')
                ->with('event')
                ->get();
        }
        else
        {
            $data = EventResource::whereHas('Event', function ($q) use ($date) {
                $q->whereDate('start_time', date($date));
            })
                ->select('id', 'resource_id', DB::raw("CONVERT(resource_id, CHAR) as resource"), 'event_id',
                    'event_resources.start_time AS start', 'event_resources.end_time as end',
                    'event_resources.setup_time AS setup', 'event_resources.teardown_time AS teardown', 'isIMR')
                ->with('event')
                ->get();
        }


        // Format for Day Pilot Times specs
        foreach ($data as $d => $value) {

            //these two lines format the times for DayPilot
            $value['start'] = str_replace(' ', 'T', $value['start']);
            $value['end'] = str_replace(' ', 'T', $value['end']);
            $value['testIMR'] = $IMR;

            //check to see if this record matches the $IMR set at event level (this can change coming from templateApply)
            if ($IMR != null)
            {
                if ($IMR == $value['resource_id'])
                {
                    $value['isIMR'] = 1;
                }
                else
                {
                    $value['isIMR'] = null;
                }
            }

            if($value['isIMR'] ==1)
            {
                //(mitcks) added inline if for spacing when there is an event abbrv
                $value['text'] = $value->event->courseInstance->course->abbrv . ($value->event->abbrv != null ? ' ' . $value->event->abbrv : ''). ' (IMR)';
            }
            else
            {
                $value['text'] = $value->event->courseInstance->course->abbrv . ' ' . $value->event->abbrv;
            }

            $value['barColor'] = $value->event->eventColor();

            // Create on hover text for each event
            $value['bubbleHtml'] = '<h4>'. $value->event->courseInstance->course->name. ' ('. $value->event->courseInstance->course->abbrv . ' ' . $value->event->abbrv .')</h4>';

            //add public comments if not null
            if($value->event->public_comments !=null)
            {
                $value['bubbleHtml'] .= '<h6><b>' . trans('labels.event.public_notes') .': </b></h6>' . $value->event->public_comments;
            }

            //add internal notes if not null and they have permission to view
            if($value->event->hasSiteCourseEventPermissions(['scheduling','event-details'], '', '') && $value->event->internal_comments != null)
            {
                $value['bubbleHtml'] .= '<h6><b>' . trans('labels.event.internal_notes') .': </b></h6>'. $value->event->internal_comments;
            }

            //put new/updated values back in $data array
            $data[$d] = $value;
        }

        return response()->json($data);
    }



    // Gets Resource_ID's user can create an Event with

    private function getResourceIDForGrid() {
        $site_id = Session::get('site_id');
        $user = Auth::user();

        $resourceList = DB::table('locations')
        ->select('resources.id')
        ->join('resources','resources.location_id','=','locations.id')
        ->join('buildings','buildings.id','=','locations.building_id')
        ->join('location_schedulers', 'location_schedulers.location_id', '=', 'locations.id')
        ->where(['locations.site_id' => $site_id,
                 'resources.retire_date' => NULL,
                 'location_schedulers.user_id' => $user->id
                ])
        ->orderBy('resources.id','ASC')
        ->get();

        $resources = [];
        foreach($resourceList as $r) {
            $resources[] = $r->id;
        }
        
        return $resources;
    }

    //populates select for template list for this schedulers location
    private function getTemplateListForSchedulersLocations ($courseId) {

        $user = Auth::user();

        $templateList = DB::table('course_templates')
            ->join('resources','course_templates.initial_meeting_room','=','resources.id')
            ->join('location_schedulers', 'location_schedulers.location_id', '=', 'resources.location_id')
            ->where([
                'location_schedulers.user_id' => $user->id,
                'course_templates.course_id' => $courseId
            ])
            ->orderBy('course_templates.name','ASC')
            ->select('course_templates.name', 'course_templates.id');

        return $templateList;
    }

    public function displayRecurrenceConfirmation($courseInstanceId)
    {
        $courseInstance = CourseInstance::find($courseInstanceId);
        $events = Event::where('course_instance_id', $courseInstanceId)->orderBy('start_time')->get();
        return view('courseInstance.main.confirmation', compact('events', 'courseInstance'));
    }

    public function courseInstanceEventsTableData(Request $request)
    {
        $courseInstanceId = $request->get('course_instance_id');
        $events = Event::where('course_instance_id', $courseInstanceId)->orderBy('start_time')->get();

        return DataTables::of($events)
            ->addColumn('date', function($events) {
                return $events->DisplayStartDate;
            })
            ->addColumn('time', function($events) {
                return date_create($events->start_time)->format('g:ia') . ' - ' . date_create($events->end_time)->format('g:ia');
            })
            ->addColumn('conflicts', function($events) {
                if($events->hasResourceConflict())
                {
                    return '<span style="color:red;"><i class="fad fa-times fa-lg"></i></span> ';
                }
                else
                {
                    return '<span style="color:green;"><i class="fad fa-check fa-lg"></i></span> ';
                }
            })

            ->addColumn('images', function($events) {
                return '<span style="color:'. $events->eventColor() .';"><i class="fas fa-circle"></i></span> '.
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
            ->rawColumns(['actions', 'images', 'color', 'conflicts'])
            ->make(true);
    }

    //mitcks 2020-03-30 copied this in from Matt's old code, trying to consolidate CourseInstanceController's
    public static function getLocationsAndResourcesData()
   { 
       $buildings = Building::orderBy('display_order')->get(['abbrv', 'id']);
       $allResourceLocations = Resource::groupBy('location_id')->get(['location_id']);
       //For grid tree
       $locationsAndResources = "[";
       foreach ($buildings as $building) {
           //only display locations if there are resources
           $locations = Location::wherein('id', $allResourceLocations)->where('building_id', "=", $building->id)->orderBy('display_order')->get(['abbrv', 'id']);
           foreach ($locations as $location) {
               $locationsAndResources .= "{\"id\":\"Location-" . $location->id . "\",\"name\":\"" . $building->abbrv . " " . $location->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";
               //only display resource types if there are resources
               $resourceTypes = ResourceType::whereHas('resources', function($query) use ($location) {
                   $query->where('location_id', $location->id);
               })->get();
               foreach ($resourceTypes as $resourceType) {
                   $resources = Resource::where('location_id', '=', $location->id)
                       ->where('resource_type_id', '=', $resourceType->id)
                       ->where('retire_date', '=', null)
                       ->orderBy('abbrv', 'asc')
                       ->get();
                   $locationsAndResources .= "{\"id\":\"Type-" . $resourceType->id . "\",\"name\":\"" . $resourceType->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";
                   foreach ($resources as $resource) {
                       $locationsAndResources .= "{\"id\":\"" . $resource->id . "\",\"name\":\"" . $resource->abbrv . "\", \"type\":\"" . $resourceType->abbrv . "\", \"category\":\"".$resource->category->abbrv."\", \"subcategory\":\"".$resource->subcategory->abbrv."\"},";
                   }
                   $locationsAndResources .= "]},";
               }
               $locationsAndResources .= "]},";
           }
       }
       $locationsAndResources .= "]"; 

       $data  = ['locationsAndResources' => $locationsAndResources];
       extract($data);
       return $data;
   }

    //mitcks 2020-03-30 copied this in from Matt's old code, trying to consolidate CourseInstanceController's
    public static function getLocationsAndResources()
    {

        $businessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);

        $buildings = Building::orderBy('display_order')->get(['abbrv', 'id']);
        $allResourceLocations = Resource::groupBy('location_id')->get(['location_id']);

        //For grid tree
        $locationsAndResources = "[";

        foreach ($buildings as $building) {

            //only display locations if there are resources
            $locations = Location::wherein('id', $allResourceLocations)->where('building_id', "=", $building->id)->orderBy('display_order')->get(['abbrv', 'id']);

            foreach ($locations as $location) {

                $locationsAndResources .= "{\"id\":\"Location-" . $location->id . "\",\"name\":\"" . $building->abbrv . " " . $location->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";

                //only display resource types if there are resources
                $allResourcesTypes = Resource::where('location_id', '=', $location->id)->groupBy('resource_type_id')->get(['resource_type_id']);
                $resourceTypes = ResourceType::wherein('id', $allResourcesTypes)->orderBy('display_order')->get(['abbrv', 'id']);

                foreach ($resourceTypes as $resourceType) {

                    $resources = Resource::where('location_id', '=', $location->id)
                        ->where('resource_type_id', '=', $resourceType->id)
                        ->where('retire_date', '=', null)
                        ->orderBy('abbrv', 'asc')
                        ->get(['abbrv', 'id']);

                    $locationsAndResources .= "{\"id\":\"Type-" . $resourceType->id . "\",\"name\":\"" . $resourceType->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";

                    foreach ($resources as $resource) {
                        $resourceCategory = ResourceCategory::where('resource_type_id', '=', $resourceType->id)->get(['abbrv', 'id']);
                        $resourceSubCategory = ResourceSubCategory::where('resource_category_id', '=', $resourceCategory[0]->id)->get(['abbrv', 'id']);
                        $locationsAndResources .= "{\"id\":\"" . $resource->id . "\",\"name\":\"" . $resource->abbrv . "\", \"type\":\"" . $resourceType->abbrv . "\", \"category\":\"".$resourceCategory[0]->abbrv."\", \"subcategory\":\"".$resourceSubCategory[0]->abbrv."\"},";
                    }

                    $locationsAndResources .= "]},";

                }

                $locationsAndResources .= "]},";

            }

        }

        $locationsAndResources .= "]";

        return [ 'locationsAndResources' => $locationsAndResources,
            'businessBeginHour' =>$businessBeginHour,
            'businessEndHour' =>$businessEndHour];
        //'eventText' =>$eventText];

    }

    //mitcks 2020-03-30 copied this in from Matt's old code, trying to consolidate CourseInstanceController's
    public static function getCoursesAndResources($start_date, $end_date) {
        // populates the events list etc for daypilot
        $courses = Course::orderBy('abbrv')
            ->with(['courseInstances', 'courseOption', 'courseOption.courseOptions', 'courseInstances.events' => function($q) use ($start_date, $end_date) {
                $q->whereDate('start_time', '>=', $start_date->format('Y-m-d'));
                $q->whereDate('end_time', '<=', $end_date->format('Y-m-d'));
                $q->where('deleted_at', null);
            }, 'courseInstances.events.eventResources'])
            ->where(['retire_date' => NULL])
            ->get();

        foreach($courses as $i=>$course) {
            foreach($course->courseInstances as $j=>$instance) {
                foreach($instance->events as $k=>$event) {
                    foreach($event->eventResources as $l=>$eventResource) {
                        $event->eventText .= $eventResource->getResourceEventGrid();
                    }
                    $event->eventText = substr($event->eventText, 0, strlen($event->eventText)-1);
                }
            }
        }

        return $courses;
    }

    public function findDefaultValuesWithCourseID($id, $locationCheck)
    {
        $user = Auth::user();
        //Need to limit templates to just ones this user has scheduling permission for
        // -jl 2019-10-18 14:20
        if($locationCheck) 
        {
            $templateList = DB::table('course_templates')
                ->join('resources','course_templates.initial_meeting_room','=','resources.id')
                ->join('location_schedulers', 'location_schedulers.location_id', '=', 'resources.location_id')
                ->where([
                    'location_schedulers.user_id' => $user->id,
                    'course_templates.course_id' => $id
                ])
                ->orderBy('course_templates.name','ASC')
                ->select('course_templates.name', 'course_templates.id')
                ->get();
        }
        else //let them get any templates -jl 2019-10-18 14:20
        {
            $templateList = DB::table('course_templates')
                ->where([
                    'course_templates.course_id' => $id
                ])
                ->orderBy('course_templates.name','ASC')
                ->select('course_templates.name', 'course_templates.id')
                ->get();
        }

        $courseOptions = [
            'setupTime'             => Course::find($id)->getCourseOption(8),
            'teardownTime'          => Course::find($id)->getCourseOption(9),
            'instructorReport'      => Course::find($id)->getCourseOption(10),
            'instructorLeave'       => Course::find($id)->getCourseOption(11),
            'color'                 => Course::find($id)->getCourseOption(7),
        ];

        return response()->json(['templates' => $templateList, 'courseOptions' => $courseOptions]);
    }

    public function findDefaultTemplateValues($templateId, $scheduleRequestId = 0, $date)
    {
        $template_resources = array();

        $template = CourseTemplate::where('course_templates.id',$templateId)
            ->join('resources', 'course_templates.initial_meeting_room', '=', 'resources.id')
            ->first();

        $scheduleRequest = ScheduleRequest::find($scheduleRequestId);

        // Get the Count of Resource Type Room
        $roomCount = CourseTemplate::join('course_template_resources', 'course_templates.id', '=', 'course_template_resources.course_template_id')
            ->join('resources', 'course_template_resources.resource_id', '=', 'resources.id')
            ->join('resource_types', 'resources.resource_type_id', '=', 'resource_types.id')
            ->where('resource_types.id', 1) // Type = Room
            ->where('course_templates.id',$templateId)
            ->get()
            ->count();

        // Get template resources
        //2020-09-14 mitcks - updating this to look for alternate resources when there is a conflict
        //Step 1: get the templated resources, order by type so specific ones are first
        $templateResources = CourseTemplateResource::where('course_template_id', $templateId)
            ->orderBy('resource_identifier_type')
            ->get();

        //Step 2: loop through and add all specific ones to $template_resources array that we will pass to grid
        foreach ($templateResources as $templateResource) {

            //need this later to access the hasConflict function
            $resource = Resource::where('id', $templateResource->resource_id)->first();

            $templateResourceStartDateTime = Carbon::parse($date . ' ' . $templateResource->StartTimeLessSetup);
            $templateResourceEndDateTime = Carbon::parse($date . ' ' . $templateResource->EndTimePlusTeardown);

            //if type is specific OR there is no conflict then select this resource
            if ($templateResource->resource_identifier_type == 1 ||
                (!$resource->hasConflict($date, $templateResourceStartDateTime, $templateResourceEndDateTime)
                && array_search($resource->id, array_column($template_resources, 'resource_id')) === false)) {

                array_push($template_resources, $templateResource);
                if($templateResource->isIMR == 1)
                {
                    $templateIMR = $resource->id;
                }
            }
            //else look for an available alternative based on category
            elseif($templateResource->resource_identifier_type == 2 )
            {
                //get all possible resources for this category and location
                $resourcesByCategory = Resource::where('resource_category_id', $templateResource->Resources->resource_category_id)
                    ->where('location_id', $templateResource->Resources->location_id)
                    ->orderBy('abbrv')
                    ->get();

                //loop through to look for resource with no conflict
                $counter = 0;
                foreach ($resourcesByCategory as $resource) {
                    $counter++;

                    //check to make sure no conflict AND resource not already selected in prior loop as alternate
                    if (!$resource->hasConflict($date, $templateResourceStartDateTime, $templateResourceEndDateTime)
                        && array_search($resource->id, array_column($template_resources, 'resource_id')) === false) {

                        //add original to array to fill in all the values like start time, etc.
                        array_push($template_resources, $templateResource);

                        //update original resource_id to available resource_id
                        foreach($template_resources as $key => $value)
                        {
                            if ($value['id'] == $templateResource->id)
                            {
                                $value['resource_id'] =  $resource->id;
                                if($templateResource->isIMR == 1)
                                {
                                    $templateIMR = $resource->id;
                                }
                            }
                        }
                        break;
                    }

                    else {
                        //the resource was not available, if this is the end, select original even though there is a conflict
                        if($counter == $resourcesByCategory->count())
                        {
                            array_push($template_resources, $templateResource);
                            if($templateResource->isIMR == 1)
                            {
                                $templateIMR = $templateResource->resource_id;
                            }
                        }
                    }
                }
            }
            //else look for an available alternative based on subcategory
            elseif($templateResource->resource_identifier_type == 3 )
            {
                //get all possible resources for this subcategory and location
                $resourcesBySubCategory = Resource::where('resource_sub_category_id', $templateResource->Resources->resource_sub_category_id)
                    ->where('location_id', $templateResource->Resources->location_id)
                    ->orderBy('abbrv')
                    ->get();
                //loop through to look for resource with no conflict
                $counter = 0;
                foreach ($resourcesBySubCategory as $resource)
                {
                    $counter++;

                    //check to make sure no conflict AND resource not already selected in prior loop as alternate
                    if (!$resource->hasConflict($date, $templateResourceStartDateTime, $templateResourceEndDateTime)
                        && array_search($resource->id, array_column($template_resources, 'resource_id')) === false) {

                        //add original to array to fill in all the values
                        array_push($template_resources, $templateResource);
                        //update original resource_id to available resource_id
                        foreach($template_resources as $key => $value)
                        {
                            if ($value['id'] == $templateResource->id)
                            {
                                $value['resource_id'] =  $resource->id;
                                if($templateResource->isIMR == 1)
                                {
                                    $templateIMR = $resource->id;
                                }
                            }
                        }
                        break;
                    }
                    else
                    {
                        //the resource was not available, if this is the end, select original even though there is a conflict
                        if($counter == $resourcesBySubCategory->count())
                        {
                            array_push($template_resources, $templateResource);
                            if($templateResource->isIMR == 1)
                            {
                                $templateIMR = $templateResource->resource_id;
                            }
                        }
                    }
                }
            }
        }

        //note per JL: the following items set in request take precedence over template
        if($scheduleRequest)
        {
            $classSize = $scheduleRequest->class_size;
            $startTime = Carbon::parse($scheduleRequest->start_time)->format('H:i');
            $endTime = Carbon::parse($scheduleRequest->end_time)->format('H:i');
            $simsSpecNeeded = $scheduleRequest->sims_spec_needed;

            //get the delta in minutes between schedule request start/end time and the template start time so we can shift template resource times accordingly
            //note - the diffInMinutes would only work if I made them new Carbon times here, it also forces them both to have
            // today's date so that the date is ignore
            $scheduleRequestStart  = new Carbon($startTime);
            $templateStart    = new Carbon($template->start_time);
            $startTimeDelta = $templateStart->diffInMinutes($scheduleRequestStart, false);

            $scheduleRequestEnd  = new Carbon($endTime);
            $templateEnd    = new Carbon($template->end_time);
            $endTimeDelta = $templateEnd->diffInMinutes($scheduleRequestEnd, false);
        }
        else
        {
            //get the values from template
            $classSize = $template->class_size;
            $startTime = Carbon::parse($template->start_time)->format('H:i');
            $endTime = Carbon::parse($template->end_time)->format('H:i');
            $simsSpecNeeded = $template->sims_spec_needed;

            //if there is no schedule request just set delta to 0
            $startTimeDelta = 0;
            $endTimeDelta = 0;
        }

        $publicComments = $template->public_comments;
        $internalComments = $template->internal_comments;

        $templateOptions = [
            'numParticipants'       => $classSize,
            'abbrv'                 => $template->event_abbrv,
            'startTime'             => $startTime,
            'endTime'               => $endTime,
            'publicComments'        => $publicComments,
            'internalComments'      => $internalComments,
            'setupTime'             => $template->setup_time,
            'teardownTime'          => $template->teardown_time,
            'instructorReport'      => $template->fac_report,
            'instructorLeave'       => $template->fac_leave,
            'color'                 => $template->color,
            'simsSpecNeeded'        => $simsSpecNeeded,
            'specialRequirements'   => $template->special_requirements,
            //$templateIMR is set above so that if there is a conflict and an alternate resource
            // is found, the IMR in the upper part of the form is set to match the grid
            'initialMeetingRoom'    => $templateIMR,
            'internalComments'      => $template->internal_comments,
            'location_id'           => $template->location_id,
            'num_rooms'             => $roomCount,
            'resources'             => $template_resources,
            'startTimeDelta'        => $startTimeDelta,
            'endTimeDelta'          => $endTimeDelta
        ];

        return response()->json(['templateOptions' => $templateOptions]);
    }
    
    // type = 2
    public function getResourceByCategoryId ($resource_id, $resource_category_id, $location_id) {

        $resourceList = DB::table('resources')
            ->where('resources.id', '!=', $resource_id)
            ->where('resources.location_id', $location_id)
            ->where('resources.resource_category_id', $resource_category_id)
            ->get();

        return response()->json(['resourceList' => $resourceList]);
    }

    // type = 3
    public function getResourceBySubCategoryId ($resource_id, $resource_sub_category_id, $location_id) {

        $resourceList = DB::table('resources')
        ->where('resources.id', '!=', $resource_id)
        ->where('resources.location_id', $location_id)
        ->where('resources.resource_sub_category_id', $resource_sub_category_id)
        ->get();

        return response()->json(['resourceList' => $resourceList]);

    }

}