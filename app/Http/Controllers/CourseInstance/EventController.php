<?php

namespace App\Http\Controllers\CourseInstance;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseInstance\StoreCourseInstanceRequest2;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\ViewerType;
use App\Models\CourseInstance\CourseInstance;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventEmails;
use App\Models\CourseInstance\EventResource;
use App\Models\CourseInstance\EventUser;
use App\Models\CourseInstance\EventUserPayment;
use App\Repositories\Backend\Access\Role\RoleRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Session;
use App\Models\Access\User\User;
use League\HTMLToMarkdown\HtmlConverter;

class EventController extends Controller
{
    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //events are stored along with the course_instance in CourseInstanceController
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $course_list = null;

        //use findOrFail here so that if an invalid ID is passed, 404 error displayed to user
        $event = Event::findOrFail($id);

        //mitcks todo: this is a weird way to do this? this array is then used in the event show.blade file?  FIX this!!!
        if($user) {
            if ($user->hasPermission('course-add-event-comment')) {
                $course_list = Event::getCourseIds($user->id);
            }
        }

        return view('courseInstance.events.show', compact('event', 'course_list'));
    }

    /**
     * Display the Event Dashboard
     *
     * @param  int  $id
     * @param string $tab (optional, defaults to info, these are anchors to go to the page with a specific tab selected)
     * @return \Illuminate\Http\Response
     */
    public function eventDashboard($id, $tab = 'info')
    {

        //if no tab is passed in it defaults to info above

        //use findOrFail here so that if an invalid ID is passed, 404 error displayed to user
        $event = Event::findOrFail($id);
        $user = Auth::user();
        $eventUserPayment = null; //so variable exists even if they aren't logged in

        if($event != null && $user !=null) {
            $eventUser = EventUser::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();
            if ($eventUser != null) {
                $eventUserPayment = EventUserPayment::where('event_user_id', $eventUser->id)->first();
            }
        }

        // to keep the course content decoupled, keep it separate from the Event model
        $course_id = $event->courseInstance->course_id;

        $viewerTypes = (new ViewerType())->orderBy('display_order')->get();

        // published course content for this course
        $courseCurriculum = (new CourseContent)->viewer($course_id);

        // set the roles box to event roles.
        $eventRoles = $this->roles->getRoles(3);

        //construct body for mailto: link here to add returns and strip html formatting from internal_notes
        $mailToBody = trans('labels.event.course') . ": " . $event->CourseNameAndAbbrv . "%0D%0A%0D%0A" .
            trans('labels.event.class_date') . ": " . $event->DisplayDateStartEndTimes . "%0D%0A%0D%0A" .
            trans('labels.event.initial_meeting_room') . ": " . $event->DisplayIMR . "%0D%0A%0D%0A" .
            trans('labels.event.event_rooms') . ": " . $event->getResources(1) . "%0D%0A%0D%0A";
        if ($event->getResources(2) != null) {
            $mailToBody .= trans('labels.event.equipment') . ": " . $event->getResources(2) . "%0D%0A%0D%0A";
        }
        if ($event->getResources(3) != null) {
            $mailToBody .= trans('labels.event.personnel') . ": " . $event->getResources(3) . "%0D%0A%0D%0A";
        }
        if ($event->public_comments != null) {
            $mailToBody .= trans('labels.event.public_notes') . ": " . $event->public_comments . "%0D%0A%0D%0A";
        }
        if ($event->internal_comments != null) {
            $converter = new HtmlConverter();
            $converter->getConfig()->setOption('strip_tags', true);

            $markdown = $converter->convert($event->internal_comments); // $markdown now contains "Turnips!"
            $mailToBody .= trans('labels.event.internal_notes') . ": " . $markdown . "%0D%0A%0D%0A";
        }
        $mailToBody .= trans('labels.event.class_size') . ": " . $event->class_size . " (" . $event->numLearnersEnrolled() . " ";
        if ($event->numLearnersEnrolled() == 1) {
            $mailToBody .= trans('labels.event.one_learner') . ") ";
        } else {
            $mailToBody .= trans('labels.event.more_learners') . ") ";
        }
        if ($event->isFull()) {
            $mailToBody .= trans('labels.event.full');
        }
        $mailToBody .= "%0D%0A%0D%0A";

        //set session variable here with path to redirect to in LoginController (used when they follow login link from event-dashboard)
        session(['event-dashboard' => '/courseInstance/events/event-dashboard/' . $event->id]);

        return view('courseInstance.events.event-dashboard', compact('event', 'tab', 'eventRoles', 'viewerTypes', 'courseCurriculum', 'mailToBody', 'eventUserPayment'));

    }

    /**
     * Display Deleted Events View
     *
     * @return \Illuminate\Http\Response
     */
    public function deletedEvents()
    {
        return view('courseInstance.events.deleted-events');
    }

    /**
     * Deleted Events Data Table
     * builds data for Datatable to display deleted events
     */
    public function deletedEventsTableData(Request $request)
    {
        //get deleted events, need to remove all global scopes, not just trashed because there is a global scope
        // that connects events to course_instances and it prevents withTrashed and onlyTrashed from working as expected

        $events = Event::withoutGlobalScopes()->where('deleted_at', "!=", null)
            ->whereHas('CourseInstance', function($query){
                $query->withoutGlobalScopes()->join('courses', 'courses.id', '=', 'course_instances.course_id')
                    ->where('courses.site_id', SESSION::get('site_id'));
            })->get();

        //Take out any events they are not a scheduler for
        foreach ($events as $key => $value)
        {
            if(!$value->IsSchedulerForLocation())
            {
                $events->forget($key);
            }
        }

        //NOTE: YOU CANNOT FOLLOW THE REGULAR RELATIONSHIPS HERE TO GET RELATED DATA BECAUSE YOU ARE
        // PULLING SOFT DELETED DATA, YOU NEED TO USE THE "TRASHED" RELATIONSHIPS I CREATED FOR
        // THIS PURPOSE
        return DataTables::of($events)
            ->addColumn('courseNameAndAbbrv', function ($events) {
                return $events->CourseInstanceTrashed->CourseTrashed->abbrv;
            })
            ->addColumn('location', function ($events) {
                return $events->initialMeetingRoom->location->building->abbrv . " " . $events->initialMeetingRoom->location->abbrv;
            })
            ->addColumn('eventDateTime', function ($events) {
                return $events->DisplayShortDateStartEndTimes;
            })
            ->addColumn('deletedBy', function ($events) {
                return User::find($events->last_edited_by)->NameEmail;
            })
            ->addColumn('deletedOn', function ($events) {
                return Carbon::parse($events->deleted_at)->timezone(session('timezone'))->format('m/d/y g:i A');
            })
            ->addColumn('restoreButton', function ($events) {
                return $events->restoreButton;
            })
            ->rawColumns(['deletedBy', 'restoreButton'])
            ->make(true);
    }

    public function restore($id)
    {
        $user = Auth::user();

        //Note: using withTrashed will not work here because of the global scope on event checking the site id via relationships
        $event = Event::withoutGlobalScopes()->findOrFail($id);

        //save to use later in restore success message
        $eventName = $event->CourseInstanceTrashed->CourseTrashed->abbrv;

        // set last edited_by to person restoring
        $event->update(['last_edited_by' => $user->id]);

        // restore event
        $event->restore();

        //restore course instance
        CourseInstance::withoutGlobalScopes()->find($event->course_instance_id)->restore();

//see note below in delete function, these are not being deleted, so no need to restore
//        // restore resources that belong to the event
//        EventResource::withoutGlobalScopes()->where('event_id', $event->id)->restore();
//
//        // restore users that belong to the event
//        EventUser::withoutGlobalScopes()->where('event_id', $event->id)->restore();
//
//        // restore Emails that belong to the event
//        EventEmails::withoutGlobalScopes()->where('event_id', $event->id)->restore();

        //success alert
        return redirect()->back()
            ->with('success', trans('alerts.frontend.scheduling.restored', ['eventId'=>$event->id, 'eventName'=> $eventName, 'eventDate'=>$event->DisplayShortDateStartEndTimes]));
    }

    public function delete($id, $all = false)
    {
        //all the destroys/deletes below are soft deletes because "use SoftDeletes" is defined in each of these models

        $user = Auth::user();
        $event = Event::findOrFail($id);

        //save to use later in delete success message
        $eventName = $event->DisplayEventName;
        $recurringDates = $event->CourseInstance->RecurrenceDates;
        $courseName = $event->CourseNameAndAbbrv;

        // set last edited_by to person deleting
        $event->update(['last_edited_by' => $user->id]);

        // soft delete event (using destroy rather than delete here so $event is still accessible)
        if($all == true)
        {
            //soft delete all events in this course
            Event::where('course_instance_id', $event->course_instance_id)->delete();

            // set edited_by and soft delete course_instance that belong to the event
            CourseInstance::find($event->course_instance_id)->update(['last_edited_by' => $user->id]);
            CourseInstance::find($event->course_instance_id)->delete();
        }
        else
        {
            $event->destroy($id);

            // check the course_instance_id to see if there are still any "active" events in this group,
            // if not, then soft delete the course instance as well
            $countActiveEvents = Event::where('course_instance_id', $event->course_instance_id)
                ->where('deleted_at', null)
                ->count();
            if($countActiveEvents == 0)
            {
                // set edited_by and soft delete course_instance that belong to the event
                CourseInstance::find($event->course_instance_id)->update(['last_edited_by' => $user->id]);
                CourseInstance::find($event->course_instance_id)->delete();
            }
        }

//The problem with soft deleting these related records is that some may have already been soft deleted
// for example a user removed from an event, and then when you restore the event, they all come back
// instead of just the ones who were last enrolled, commenting out to test
//        // set edited_by and soft delete resources that belong to the event
//        EventResource::where('event_id', $event->id)->update(['last_edited_by' => $user->id]);
//        EventResource::where('event_id', $event->id)->delete();
//
//        // set edited_by and soft delete users that belong to the event
//        EventUser::where('event_id', $event->id)->update(['last_edited_by' => $user->id]);
//        EventUser::where('event_id', $event->id)->delete();
//
//        // set edited_by and soft delete Emails that belong to the event
//        EventEmails::where('event_id', $event->id)->update(['last_edited_by' => $user->id]);
//        EventEmails::where('event_id', $event->id)->delete();

        //per CHIPS request, set this to go back to calendar day view for date of deleted event instead of dashboard
        return redirect()->route('default.calendar', ['date'=> \Carbon\Carbon::parse($event->start_time)->format('Y-m-d')])
            ->with('success', trans('alerts.frontend.scheduling.deleted', ['eventName'=> $eventName]));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCourseInstanceRequest2 $request, $id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);

        //(mitcks) this contains an array of resources selected on the grid for this event,
        // both existing and newly added, each element has the attributes needed to create/update event_resources
        $request['resource_events'] = json_decode( $request->resource_events );

        //(mitcks) this contains the event_resources.id's they have deleted from the grid (just the id, no other attributes)
        $request['delete_event_arr'] = json_decode( $request->delete_event_arr );

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

        $request['start_time'] = Carbon::parse($request->selectDate . ' ' . $request->start_time);
        $request['end_time'] = Carbon::parse($request->selectDate . ' ' . $request->end_time);

        $event->update($request->all());

        // re-calc event_emails send time
        $evt_emails = EventEmails::select('event_emails.*')
            ->join('email_types', 'email_types.id', '=', 'event_emails.email_type_id')
            ->where('event_emails.event_id', $id)
            ->where(function ($q) {
                $q->where('email_types.name', '=', 'Automatically Send')->orWhere('email_types.name', '=', 'Event Send Manually');
            })
            ->get();

        foreach ($evt_emails as $em) {
            $date = null;
            $amount = 0;
            switch ($em->time_offset) {
                case 1:
                    $date = $request['start_time'];
                    $amount = -abs($em->time_amount);
                    break;
                case 2:
                    $date = $request['start_time'];
                    $amount = abs($em->time_amount);
                    break;
                case 3:
                    $date = $request['end_time'];
                    $amount = -abs($em->time_amount);
                    break;
                case 4:
                    $date = $request['end_time'];
                    $amount = abs($em->time_amount);
                    break;
                default:
                    // default start time
                    $date = $request['start_time'];
                    $amount = 0;
            }
            $time_type = '';
            switch ($em->time_type) {
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

            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date, $event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');

            // Replacing User session timezone check, with the timezone of the building the event will take place.  
            $dateAdj = date_add(\Carbon\Carbon::parse($date, $event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));
         
            $send_at = \Carbon\Carbon::parse($dateAdj)->format('Y-m-d H:i:s');
            // Get Curr time
            $curr = \Carbon\Carbon::now('UTC')->format('Y-m-d H:i:s');

            if ($send_at < $curr) {
                EventEmails::where('id', $em->id)->update(['email_type_id' => 9, 'send_at' => null]);
            }else {
                EventEmails::where('id', $em->id)->update(['email_type_id' => 8, 'send_at' => $send_at]);
            }
        }

        // Delete Event Resources
        // 2021-03-05 mitcks: the grid passes back an array of deleted resources, if they are numeric
        // (as opposed to 777bd58f-220c-872f-0973-873952be861c) that means they are existing records
        // and they need to be deleted from the database, if it's not numeric then it's a resource
        // they just selected and unselected and we do not need to do anything
        foreach ($request->delete_event_arr as $e_delete) {
            if (is_numeric($e_delete)) { // If string then its a Day Pilot ID and we don't want that
                if (EventResource::find($e_delete)) {
                    EventResource::findOrFail($e_delete)->forcedelete(); //use forceDelete here - no reason to soft delete
                }
            }
        }

        // Create/Update Event Resources
        foreach ($request->resource_events as $selectedGridResource) {

            //(mitcks) if this is marked IMR, check to see if it still matches events.initial_meeting_room (there can only be one IMR)
            $isIMR = null; //to store possible change, defaults to null
            if($selectedGridResource->isIMR == 1)
            {
                if($selectedGridResource->resource == $event->initial_meeting_room) //(mitcks)
                {
                    $isIMR = 1;
                }
            }

            //2021-03-05 mitcks - updated this section to check if the ID from the array
            // of selected resources is numeric before using it to check if there is an existing resource.
            // In the array, the ID is event_resources.id for existing resources, but for newly selected
            // resources it is an id like this 777bd58f-220c-872f-0973-873952be861c and somehow "laravel magic"
            // was actually converting it and sometimes finding related records in the database that belonged to
            // other events and updating those instead of creating a new event_resources record for this event
            if (is_numeric($selectedGridResource->id))
            {
                $event_resource = EventResource::find($selectedGridResource->id);

                // Update Resource
                $event_resource->update(['resource_id' => $selectedGridResource->resource,
                                        'start_time' => $selectedGridResource->start,
                                        'end_time' => $selectedGridResource->end,
                                        'setup_time' => $selectedGridResource->setup,
                                        'teardown_time' => $selectedGridResource->teardown,
                                        'isIMR' => $isIMR,
                                        'last_edited_by' => $user->id]);
            }
            else
            {
                // Create Resource
                $newEventResource = EventResource::create(['event_id' => $event->id,
                                'resource_id' => $selectedGridResource->resource,
                                'start_time' => $selectedGridResource->start,
                                'end_time' => $selectedGridResource->end,
                                'setup_time' => $selectedGridResource->setup,
                                'teardown_time' => $selectedGridResource->teardown,
                                'isIMR' => $isIMR,
                                'conflict_ignored' => 0,
                                'created_by' => $user->id,
                                'last_edited_by' => $user->id]);
            }
        }

        return redirect()->route('event_dashboard',[$event->id])
            ->with('success', trans('alerts.frontend.scheduling.edited'));

    }

}
