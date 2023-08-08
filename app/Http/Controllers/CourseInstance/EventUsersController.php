<?php

namespace App\Http\Controllers\CourseInstance;

use App\Models\CourseInstance\CourseInstance;
use App\Models\CourseInstance\EventUserHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\CourseInstance\StoreEventUserRequest;
use App\Http\Requests\CourseInstance\MoveEventUserRequest;
use Auth;
use Carbon\Carbon;
use App\Models\Course\Course;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventUser;
use App\Events\Frontend\Event\UserAddedToEvent;
use App\Events\Frontend\Event\DropUserFromEvent;
use App\Models\Access\Role\Role;
use App\Models\Site\Site;
use Session;
use Debugbar;
use DataTables\Editor;

class EventUsersController extends Controller
{

    /**
     * @var RoleRepository
     */
    protected $roles;
    protected $request;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles, Request $request)
    {
        $this->roles = $roles;
        $this->request = $request;
    }

    /**
     * MY COURSES WAITLIST
     * displays view for those with *add-people-to-events permission to see
     * all applicable waitlisted enrollment requests
     * Route: event/users/waitlist
     * View: resources/views/courseInstance/events/mycourses-waitlist.blade.php
     */
    public function myCoursesWaitlist()
    {
        return view('courseInstance.events.mycourses-waitlist');
    }


    //TODO mitcks: I think this can be deleted
    /**
     * event roles 
     * displays the page to assign users to events and their specified roles
     * events/users.blade.php
     * 
     * Can get to this page from Pending to Add User to Event
     */
//    public function users($id)
//    {
//        $event = Event::find($id);
//
//        // This is only used when directed from pending enrollment
//        $enroll_id = $this->request->input('enroll_id');
//        $enroll_request = $event->eventRequest->find($enroll_id);
//
//        // auth check to make sure you can't access other events
//        if(!$event->hasSiteCourseEventPermissions('add-people-to-events',
//            'course-add-people-to-events',
//            'event-add-people-to-events'))
//            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
//                    ['date'=>$event->DisplayStartDate]));
//
//        // set the roles box to event roles.
//        $eventRoles = $this->roles->getRoles(3);
//
//        return view('courseInstance.events.users', compact('event', 'eventRoles', 'enroll_request'));
//    }


    /**
     * MOVE MODAL
     * displays a modal that allows a user to be moved to a new event
     * Route: event/users/move/{id}/{tab?}
     * View: events/users-move.blade.php
     */
    public function moveModal($id, $tab = null)
    {
        $event_user = EventUser::findOrFail($id);

        if($event_user) {
            $move_user = User::find($event_user->user_id);
            $event = Event::find($event_user->event_id);

            // auth check to make sure you can't access other events
            if (!$event->hasSiteCourseEventPermissions('add-people-to-events',
                'course-add-people-to-events',
                'event-add-people-to-events'))
                return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
                    ['date' => $event->DisplayStartDate]));

            return view('courseInstance.events.users-move', compact('event_user', 'event', 'move_user', 'tab'));
        }

    }

    /**
    * typeahead search box data
    * this pulls username/email from the active users list.
    */
    public function usersData(Request $request)
    {

        $searchString = (!empty($request->get('q'))) ? strtolower($request->get('q')) : null;
        
        if (!isset($searchString)) {
            die('Invalid query.');
        }

        // this replaces spacing with a %wildcard to search first_name last_name
        $searchString = str_replace(' ', '%', $searchString);

        $usersQuery = User::active(true)
            ->where(function($query) use ($searchString){
                return $query
                    ->where('first_name', 'like', '%' . $searchString .'%')
                    ->orWhere('last_name', 'like', '%' . $searchString .'%')
                    ->orWhere('email', 'like', '%' . $searchString .'%');
            })
            ->get();

        foreach ($usersQuery as $usersList) {
            $user['id'] = $usersList->id;
            $user['name'] = $usersList->first_name .' '. $usersList->last_name . ' (' . $usersList->email . ')';
            $user['email'] = $usersList->email;
            $databaseUsers[] = $user; // TC 2020-03-02 this $var was getting set OTF so when it was empty causing the return to throw 500
        }
        
        $success = true;
        $status = 200;
        
        // no result were found
        if (!isset($databaseUsers)) {
            $success = false;
            $status = 404;
        }

        // build json data for Typeahead
        $users = json_encode(array(
            "success" => $success,
            "status" => $status,
            "error"  => null,
            "data"   => [
                "user"   => isset($databaseUsers) ? $databaseUsers : []
            ]
        ));

        return $users;
    }


    /**
     * EVENT DASHBOARD ROSTER TAB DATA
     * event assign user/roles Datatable
     * builds data for Datatable to display the users with event roles set
     * event/users.blade.php
     */
    public function eventUsersTableData(Request $request)
    {

        $event_id = $request->get('event_id');
        $event = Event::find($event_id);
        
        //only get enrolled users
        $eventUsers = EventUser::where('event_id', $event->id)->enrolled();

        return DataTables::of($eventUsers)

            ->addColumn('role', function ($eventUsers) {
                return Role::find($eventUsers->role_id)->name . " (". $eventUsers->event->numByRoleAndStatus($eventUsers->role_id, 1) .") <span style=\"display:none;\" id=\"". $eventUsers->role_id."\">" .
                        $eventUsers->event->emailsByRoleAndStatus($eventUsers->role_id, 1) ."</span> 
                        <button class=\"btn btn-link\" title = \"" . trans('labels.event.emails_for_role') . "\" data-toggle = \"tooltip\"
                        onclick=\"copyToClipboard('#". $eventUsers->role_id."')\"><i class=\"fas fa-mail-bulk\"></i></button>";
            })
            ->addColumn('name', function ($eventUsers) {
                return $eventUsers->user->NameEmail;
            })
            ->addColumn('firstName', function ($eventUsers) {
                return $eventUsers->user->first_name;
            })
            ->addColumn('lastName', function ($eventUsers) {
                return $eventUsers->user->last_name;
            })
            ->addColumn('email', function ($eventUsers) {
                return $eventUsers->user->email;
            })
            ->addColumn('roleNameOnly', function ($eventUsers) {
                return Role::find($eventUsers->role_id)->name;
            })
            ->addColumn('actions', function($eventUsers) {
                return $eventUsers->RosterActionButtons;
            })
            ->addColumn('chbx_attend', function($eventUsers) {

                if($eventUsers->attend_date !== null)
                {
                    return '<button class="p-0 m-0 btn btn-sm btn-link shadow-none simptip-position-top simptip-smooth"
                        name="mark_attendance" id="mark_attendance" 
                        data-tooltip="' . trans('labels.events.unmark_attend') . '"
                        data-event_user_id="'. $eventUsers->id. '"
                        data-action="turn_off">
                        <i class="text-success fad fa-toggle-on fa-2x"></i></button>';

                }
                else
                {
                    return '<button class="p-0 m-0 btn btn-sm btn-link shadow-none simptip-position-top simptip-smooth"
                        name="mark_attendance" id="mark_attendance" 
                        data-tooltip="' . trans('labels.events.mark_attend') . '"
                        data-event_user_id="'. $eventUsers->id. '"
                        data-action="turn_on">
                        <i class="text-secondary fad fa-toggle-off fa-2x"></i></button>';
                }

            })
            ->rawColumns(['actions', 'role', 'name', 'chbx_attend'])
            ->make(true);
    }

    /**
     * EVENT DASHBOARD WAITLIST TAB DATA
     * builds data for Datatable to display the users on waitlist
     */
    public function eventWaitlistTableData(Request $request)
    {
        $event_id = $request->get('event_id');
        $event = Event::find($event_id);

        // auth check to make sure you can't access other events
        if(!$event->hasSiteCourseEventPermissions('add-people-to-events',
            'course-add-people-to-events',
            'event-add-people-to-events'))
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
                ['date'=>$event->DisplayStartDate]));

        $eventUsers = EventUser::where('event_id', $event->id)->where('status_id', 3);

        return DataTables::of($eventUsers)
            ->addColumn('role', function ($eventUsers) {
                return Role::find($eventUsers->role_id)->name . " (". $eventUsers->event->numByRoleAndStatus($eventUsers->role_id, 3) .") <span style=\"display:none;\" id=\"". $eventUsers->role_id."_waitlist\">" .
                    $eventUsers->event->emailsByRoleAndStatus($eventUsers->role_id, 3) ."</span> 
                        <button class=\"btn btn-link\" title = \"" . trans('labels.event.emails_for_role') . "\" data-toggle = \"tooltip\"
                        onclick=\"copyToClipboard('#". $eventUsers->role_id."_waitlist')\"><i class=\"fas fa-mail-bulk\"></i></button>";
            })
            ->addColumn('name', function ($eventUsers) {
                return $eventUsers->user->NameEmail;
            })
            ->addColumn('request_notes', function ($eventUsers) {
                return $eventUsers->request_notes;
            })
            ->addColumn('request_date', function ($eventUsers) {
                return $eventUsers->DisplayCreatedAt;
            })
            ->addColumn('created_at', function ($eventUsers) {
                return $eventUsers->created_at;
            })
            ->addColumn('firstName', function ($eventUsers) {
                return $eventUsers->user->first_name;
            })
            ->addColumn('lastName', function ($eventUsers) {
                return $eventUsers->user->last_name;
            })
            ->addColumn('email', function ($eventUsers) {
                return $eventUsers->user->email;
            })
            ->addColumn('roleNameOnly', function ($eventUsers) {
                return Role::find($eventUsers->role_id)->name;
            })
            ->addColumn('actions', function($eventUsers) {
                return $eventUsers->WaitlistActionButtons;
            })
            ->rawColumns(['actions', 'role', 'name'])
            ->make(true);
    }

    /**
     * MY COURSES WAITLIST TAB DATA
     * builds data for Datatable to display the users on waitlist
     */
    public function myCoursesWaitlistTableData(Request $request)
    {
        $eventUsers = EventUser::MyWaitlistRequests()->get();

        return DataTables::of($eventUsers)
            ->addColumn('courseNameAbbrv', function ($eventUsers) {
                return $eventUsers->event->CourseNameAndAbbrv;
            })
            ->addColumn('name', function ($eventUsers) {
                return $eventUsers->user->NameEmail;
            })
            ->addColumn('role', function ($eventUsers) {
                  return Role::find($eventUsers->role_id)->name;
            })
            ->addColumn('event_date', function ($eventUsers) {
                if($eventUsers->event->isParkingLot())
                {
                    return trans('labels.event.parkingLot');
                }
                else
                {
                    if($eventUsers->event->isFull())
                    {
                        $fullText = "&emsp;<span class='event-full'>" . trans('labels.event.full') . "</span>";
                    }
                    else
                    {
                        $fullText = null;
                    }
                    //Event date links to event-dashboard, followed by enrollment counts
                    return "<a href='/courseInstance/events/event-dashboard/". $eventUsers->event_id .
                            "/waitlist'>".$eventUsers->event->DisplayShortDateStartEndTimes."</a>". $fullText . "&emsp;" . $eventUsers->event->DisplayEventUserCounts;
                }
            })
            ->addColumn('request_notes', function ($eventUsers) {
                if($eventUsers->event->isParkingLot() and $eventUsers->status_id == 2)
                {
                    return trans('strings.frontend.event_user.parked') . ' ' . $eventUsers->request_notes;
                }
                else
                {
                    return $eventUsers->request_notes;
                }
            })
            ->addColumn('request_date', function ($eventUsers) {
                return $eventUsers->DisplayCreatedAt;
            })
            ->addColumn('created_at', function ($eventUsers) {
                return $eventUsers->created_at;
            })
            ->addColumn('firstName', function ($eventUsers) {
                return $eventUsers->user->first_name;
            })
            ->addColumn('lastName', function ($eventUsers) {
                return $eventUsers->user->last_name;
            })
            ->addColumn('email', function ($eventUsers) {
                return $eventUsers->user->email;
            })
            ->addColumn('roleNameOnly', function ($eventUsers) {
                return Role::find($eventUsers->role_id)->name;
            })
            ->addColumn('actions', function($eventUsers) {
                return $eventUsers->MyCoursesWaitlistActionButtons;
            })
            ->rawColumns(['actions', 'role', 'name', 'event_date'])
            ->make(true);
    }

    /**
     * event users move modal Datatable
     * builds datatable data for course selection
     * event/users-move.blade.php
     */
    public function userMoveTableData(Request $request)
    {

        // get the event_id of the event they are viewing - so you can get all other events in the same course
        $eventMove = Event::find($request->get('event_id'));

        // auth check to make sure you can't access other events
        if(!$eventMove->hasSiteCourseEventPermissions('add-people-to-events',
            'course-add-people-to-events',
            'event-add-people-to-events'))
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
                ['date'=>$eventMove->DisplayStartDate]));

        // date range
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');


         $events = Event::with('CourseInstance')
             ->whereDate('start_time', '>=', $start_date)
             ->whereDate('start_time', '<=', $end_date)
             ->whereHas('CourseInstance', function($q) use ($eventMove) {
                 $q->where('course_id', $eventMove->courseInstance->course->id);
             })
             ->where('initial_meeting_room', '!=', null)
             ->orderBy('start_time')
             ->get();

         return DataTables::of($events)
         ->addColumn('event_id', function($events) {
            return $events->id;
         })
         ->addColumn('class_size', function($events) {
             return $events->class_size;
         })
         ->addColumn('num_enrolled', function($events) {
             return $events->numLearnersEnrolled();
         })
         ->addColumn('num_waitlist', function($events) {
             return $events->numLearnersWaitlisted();
         })
         ->addColumn('date', function($events) {
             if($events->isFull())
             {
                 return $events->DisplayDateStartEndTimes . " <span class='event-full'>" . trans('labels.event.full') . "</span> ";
             }
             else
             {
                 return $events->DisplayDateStartEndTimes;
             }
         })
         ->addColumn('date_sort', function($events) {
             return $events->start_time;
         })
         ->addColumn('time', function($events) {
            return $events->DisplayStartEndTimes;
         })
         ->addColumn('location', function($events) {
            return $events->initialMeetingRoom->location->building->abbrv . ' '. $events->initialMeetingRoom->location->abbrv . ' - '. $events->InitialMeetingRoom->abbrv;
         })
         ->rawColumns(['date'])
         ->make(true);
    }

    /**
     * user history modal
     * displays a modal for event_user_history
     */
    public function historyModal($id)
    {
        $eventUser = EventUser::find($id);

        //get the history
        $eventUserHistory = $eventUser->eventUserHistory;

        //format history into timeline string
        $timeline = '<div class="row"><div class="col-md-12"><div class="timeline">';
        foreach($eventUserHistory as $history){

            //Display Text
            if($history->action_id == 3) //moved
            {
                //put string of two event_ids in array, 1st position is the event moved from, 2nd position is event moved to
                $myArray = explode(', ', $history->display_text);
                if(count($myArray)==2) //just in case it's not formatted correctly
                {
                    $event1 = Event::find($myArray[0]);
                    $event2 = Event::find($myArray[1]);
                    $displayText = null;

                    //check each event, if nothing is found for that ID it was deleted, display text instead of date
                    if($event1)
                    {
                        $displayText = $event1->DisplayDateStartEndTimes;
                    }
                    else
                    {
                        $displayText = trans('labels.event.event_deleted');
                    }
                    if($event2)
                    {
                        $displayText .=  ' <i class="fas fa-arrow-right"></i> ' .$event2->DisplayDateStartEndTimes;
                    }
                    else
                    {
                        $displayText .=  ' <i class="fas fa-arrow-right"></i> ' . trans('labels.event.event_deleted');
                    }
                }
                else
                {
                    $displayText = trans('labels.events.event_role').': '. $history->display_text;
                }
            }
            elseif(in_array($history->action_id, [8,9,10,11,12,13]))
            {
                $displayText = ""; //don't need to repeat it after heading for these types
            }
            else
            {
                $displayText = trans('labels.events.event_role').': '. $history->display_text;
            }

            $timeline = $timeline. '<div>';
            $timeline = $timeline. '<i class="fas fa-user bg-green"></i>';
            $timeline = $timeline. '<div class="timeline-item">';
            $timeline = $timeline. '<span class="time">' . $history->DisplayActionBy . ' ' . $history->DisplayActionDate . ' <i class="fas fa-clock"></i> ' . $history->created_at->diffForHumans() .'</span>';
            $timeline = $timeline. '<h3 class="timeline-header no-border">'.$history->eventUserHistoryAction->action .'</h3>';
            $timeline = $timeline. '<div class="timeline-body text-sm">'. $displayText .'</div></div></div>';
        }
        $timeline = $timeline. '</div></div></div>';

        return view('courseInstance.events.roster-history', compact('eventUser', 'eventUserHistory', 'timeline'));
    }

    /**
     * event users history modal Datatable
     * builds datatable for event user history modal
     */
    public function userHistoryTableData(Request $request)
    {

        // get event_userfor the event/user combination they are viewing
        $eventUser = EventUser::find($request->get('event_user_id'));

        //get the history
        $eventUserHistory = $eventUser->eventUserHistory;

        return DataTables::of($eventUserHistory)
            ->addColumn('action_type', function($eventUserHistory) {
                return $eventUserHistory->eventUserHistoryAction->action;
            })
            ->addColumn('display_text', function($eventUserHistory) {
                if($eventUserHistory->action_id == 3) //moved
                {
                    //put string of two event_ids in array, 1st position is the event moved from, 2nd position is event moved to
                    $myArray = explode(', ', $eventUserHistory->display_text);
                    if(count($myArray)==2) //just in case it's not formatted correctly
                    {
                        $event1 = Event::find($myArray[0]);
                        $event2 = Event::find($myArray[1]);
                        $strToReturn = null;

                        //check each event, if nothing is found for that ID it was deleted, display text instead of date
                        if($event1)
                        {
                            $strToReturn = $event1->DisplayDateStartEndTimes;
                        }
                        else
                        {
                            $strToReturn = trans('labels.event.event_deleted');
                        }
                        if($event2)
                        {
                            $strToReturn .=  " -> " .$event2->DisplayDateStartEndTimes;
                        }
                        else
                        {
                            $strToReturn .=  " -> " . trans('labels.event.event_deleted');
                        }
                        return $strToReturn;
                    }
                    else
                    {
                        return $eventUserHistory->display_text;
                    }
                }
                else
                {
                    return $eventUserHistory->display_text;
                }
            })
            ->addColumn('action_by', function($eventUserHistory) {
                return $eventUserHistory->DisplayActionBy;
            })
            ->addColumn('action_date', function($eventUserHistory) {
                return $eventUserHistory->DisplayActionDate;
            })
            ->rawColumns(['action_by', 'display_text'])
            ->make(true);
    }


    //Called from Toggle Button on Event Dashboard Users Tab to Record Attendance
    public function updateAttendance(Request $request)
    {
        // logged in user - not the user to update
        $user = Auth::user();

        //record to be updated
        $eventUser = EventUser::find($request->event_user_id);
        $action = $request->action; //turn_off or turn_on

       if($action == "turn_on")
       {
           //set attend_date
           $eventUser->attend_date = \Carbon\Carbon::now();
           $action_id = 8; //marked attended
       }
       else
       {
           //clear attend_date
           $eventUser->attend_date = null;
           $action_id = 9; //unmarked attended
       }

        $eventUser->who_marked_attend = $user->id;
        $eventUser->last_edited_by = $user->id;
        $eventUser->save();

        //update event_user_history
        $eventUserHistory = EventUserHistory::create(
            [
                'event_user_id' => $eventUser->id,
                'action_id' => $action_id,
                'display_text' => ' ',
                'action_by' => $user->id
            ]
        );

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }

    /**
     * add event user/role to DB, this is called from the add user section of Roster tab when user is "manually" added
     * 
     * events/users.blade.php
     */
    public function store(StoreEventUserRequest $request)
    {

        // actual logged in user - not the user to update
        $user = Auth::user();

        // set variables
        $event_id = $request['event_id'];
        $user_id = $request['user_id'];
        $role_id = $request['role_id'];

        //Validation request should catch this, but just in case
        if(!$event_id or !$user_id or !$role_id)
        {
            return redirect()->back()->withErrors(trans('exceptions.general.unexpected_error', ['details'=>'Required data missing for insert']));
        }

        $event = Event::find($event_id);

        //put role_id in session variable so value in dropdown on form stays selected
        $request->session()->put('role_id', $role_id);

        //enrolled
        $status_id = 1; //Enrolled Status
        $action_id = 2; //Enrolled Action
        $display_text = Role::find($role_id)->name;

        // verify logged in user has permission to add people to this event
        if(!$event->hasSiteCourseEventPermissions('add-people-to-events',
            'course-add-people-to-events',
            'event-add-people-to-events'))
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
            ['date'=>$event->DisplayStartDate]));

        //check to see if a record already exists for this user and event, if yes update, if no create
        //including soft deleted records (trashed) so if they were previously deleted it updates that record

        //first check to see if there was a soft deleted record for this user/event and restore it for updating
        EventUser::withTrashed()
            ->where('event_id', $event_id)
            ->where('user_id', $user_id)
            ->restore();

        //now do updateOrCreate to either create new record or update existing
        $eventUser = EventUser::updateOrCreate(
            [
                //unique field combination
                'user_id' => $user_id,
                'event_id' => $event_id,
            ],
            [
                'role_id' => $role_id,
                'last_edited_by' => $user->id,
                'status_id' => $status_id
            ]
        );

        //only set the created_by field if this is a create (otherwise it is erroneously changed on update)
        if($eventUser->wasRecentlyCreated)
        {
            $eventUser->created_by = $user->id;
            $eventUser->save();
        }

        //update event_user_history
        $eventUserHistory = EventUserHistory::create(
            [
                'event_user_id' => $eventUser->id,
                'action_id' => $action_id,
                'display_text' => $display_text,
                'action_by' =>$user->id
            ]
        );

        //send email if update successful
        if($eventUser)
        {
            event(new UserAddedToEvent(User::find($user_id), $event, Role::find($role_id)));
        }

        return redirect()->route('event_dashboard',[$event_id, $request->tab])
                ->with('success', trans('alerts.frontend.eventusers.created', ['UserName'=>User::find($user_id)->name, 'RoleName'=>Role::find($role_id)->name, 'EventName'=>'<a href="/courseInstance/events/event-dashboard/'.$eventUser->event_id.'/roster">'.$eventUser->event->DisplayEventName.'</a>']));

    }

    /**
     * approve event user request and change status to enrolled
     *
     * this is called from the add user action button on waitlist tab and mycourses waitlist
     *
     */
    public function approve($id, $pageFrom)
    {

        // actual logged in user - not the user to update
        $user = Auth::user();

        //record to be soft updated
        $eventUser = EventUser::find($id);

        // verify logged in user has permission to add people to this event
        if(!$eventUser->event->hasSiteCourseEventPermissions('add-people-to-events',
            'course-add-people-to-events',
            'event-add-people-to-events'))
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
                ['date'=>$eventUser->event->DisplayStartDate]));


        //update status to enrolled
        $eventUser->status_id = 1;
        $eventUser->last_edited_by = $user->id;
        $eventUser->save();

        //update event_user_history
        $eventUserHistory = EventUserHistory::create(
            [
                'event_user_id' => $eventUser->id,
                'action_id' => 2,
                'display_text' => Role::find($eventUser->role_id)->name,
                'action_by' =>$user->id
            ]
        );


        //send email if update successful
        if($eventUser)
        {
            event(new UserAddedToEvent(User::find($eventUser->user_id), Event::find($eventUser->event_id), Role::find($eventUser->role_id)));
        }

        if($pageFrom == 'eventDashboard')
        {
            return redirect()->route('event_dashboard',[$eventUser->event_id, 'roster'])
                ->with('success', trans('alerts.frontend.eventusers.created', ['UserName'=>User::find($eventUser->user_id)->name, 'RoleName'=>Role::find($eventUser->role_id)->name, 'EventName'=>'<a href="/courseInstance/events/event-dashboard/'.$eventUser->event_id.'/roster">'.$eventUser->event->DisplayEventName.'</a>']));
        }
        else //mycourses
        {
            return redirect()->route('mycourses.waitlist')
                ->with('success', trans('alerts.frontend.eventusers.created', ['UserName'=>User::find($eventUser->user_id)->name, 'RoleName'=>Role::find($eventUser->role_id)->name, 'EventName'=>'<a href="/courseInstance/events/event-dashboard/'.$eventUser->event_id.'/roster">'.$eventUser->event->DisplayEventName.'</a>']));
        }

    }

    /**
     * self park
     *
     * this is called from show catalog, user can add themselves to the waitlist for the course
     *
     */
    public function selfPark($courseId)
    {
        // logged in user
        $user = Auth::user();

        //Step 1:
        // find the event_id of the parking lot for this course
        // find a learner role to enroll them in
        // if either of these do not exist, redirect with error
        $parkingLotId = Course::where('id', $courseId)->first()->ParkingLotEventId;

        //gets a learner role, if more than one it picks the first
        $learnerRole = Role::with('users', 'permissions')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('permission_role')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    ->whereRaw('permission_role.role_id = roles.id')
                    ->where('permission_type_id', 3);
            })
            ->where('learner', '=', 1)
            ->orderBy('name')
            ->first();;

        //stop and show error if either of these are empty
        if($parkingLotId==0 or !$learnerRole)
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.unexpectedSelfPark', ['siteEmail'=>Site::find(Session::get('site_id'))->email]));
        }

        //Step 2: if user is already in parking lot (not soft deleted) throw an error
        $eventUserCheck = EventUser::where('event_id', $parkingLotId)
            ->with('event') //eager load related Event model
            ->where('user_id', $user->id)
            ->first();

        //redirect back with an error message that they are already enrolled
        if($eventUserCheck)
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.selfParkAlreadyParked', ['user'=>User::find($eventUserCheck->user_id)->name, 'courseAbbrv'=>$eventUserCheck->event->CourseNameAndAbbrv]));
        }

        //Step 3: insert new parking lot record

        //first check to see if there was a soft deleted record for this user/event and restore it for updating
        EventUser::withTrashed()
            ->where('event_id', $parkingLotId)
            ->where('user_id', $user->id)
            ->restore();

        //now do updateOrCreate to either create new record or update existing
        $eventUser = EventUser::updateOrCreate(
            [
                //unique field combination
                'user_id' => $user->id,
                'event_id' => $parkingLotId,
            ],
            [
                'role_id' => $learnerRole->id, //need to find a learner role
                'last_edited_by' => $user->id,
                'status_id' => 3 //waitlist
            ]
        );

        //only set the created_by field if this is a create (otherwise it is erroneously changed on update)
        if($eventUser->wasRecentlyCreated)
        {
            $eventUser->created_by = $user->id;
            $eventUser->save();
        }

        //update event_user_history
        $eventUserHistory = EventUserHistory::create(
            [
                'event_user_id' => $eventUser->id,
                'action_id' => 7,
                'display_text' => trans('labels.event.parkingLot'),
                'action_by' =>$user->id
            ]
        );

        return redirect()->back()->withFlashSuccess(trans('alerts.frontend.eventusers.selfParkSuccess', ['courseAbbrv' => $eventUser->event->CourseNameAndAbbrv]));

    }

    /**
     * park event user
     *
     * this is called from the park user action button on roster, waitlist tab and mycourses waitlist
     *
     */
    public function park($id=0, $pageFrom)
    {

        // actual logged in user - not the user to update
        $user = Auth::user();

        //record to be updated
        $eventUser = EventUser::find($id);

        if($eventUser) //they are changing an existing record to parking lot event
        {
            //Step 1: find the event_id of the parking lot for this course
            $parkingLotId = Course::where('id', $eventUser->event->courseInstance->course->id)->first()->ParkingLotEventId;

            //Step 2: if user is already in parking lot (not soft deleted) throw an error
            $eventUserCheck = EventUser::where('event_id', $parkingLotId)
                ->with('event') //eager load related Event model
                ->where('user_id', $eventUser->user_id)
                ->first();

            //redirect back with an error message that they are already enrolled
            if($eventUserCheck)
            {
                return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.alreadyParked', ['user'=>User::find($eventUserCheck->user_id)->name, 'courseAbbrv'=>$eventUserCheck->event->CourseNameAndAbbrv]));
            }

            //Step 3: if a trashed record already exists for the parking lot we
            // need to permanently delete it first because we can't have two records with the same
            // event_id & user_id combination

            EventUser::onlyTrashed()
                ->where('event_id', $parkingLotId) //event they are being moved into
                ->where('user_id', $eventUser->user_id)
                ->forceDelete();

            //Step 4: update existing record to parking lot
            // we already know the record we are updating via $eventUser->id - no need to set the user_id

            //if they were previously enrolled, change status to parked, this is used to differentiate between
            // a user placed directly in the parking lot (status = 3) and someone parked from an event (status = 2)
            if($eventUser->status_id == 1)
            {
                $newEventUser = EventUser::where('id', '=', $eventUser->id)->update(
                    ['updated_at' => \Carbon\Carbon::now(),
                        'status_id' => 2,
                        'last_edited_by' => $user->id,
                        'event_id' => $parkingLotId]);
            }
            else
            {
                $newEventUser = EventUser::where('id', '=', $eventUser->id)->update(
                    ['updated_at' => \Carbon\Carbon::now(),
                        'last_edited_by' => $user->id,
                        'event_id' => $parkingLotId]);
            }

            //update event user history
            if($newEventUser)
            {
                $newEventUser = EventUser::find($eventUser->id);

                //update event_user_history
                $eventUserHistory = EventUserHistory::create(
                    [
                        'event_user_id' => $eventUser->id,
                        'action_id' => 3, //Moved
                        'display_text' => $eventUser->event_id . ", " . $parkingLotId,
                        'action_by' => Auth::user()->id,
                    ]
                );

            }

            //Step 5: send a parked email (todo: email needs to be written)


            //Step 6: redirect back and display success message
            if($newEventUser) {
                if ($pageFrom == 'roster') {
                    return redirect()->route('event_dashboard', [$eventUser->event_id, 'roster'])
                        ->with('success', trans('alerts.frontend.eventusers.moveToParkingLotSuccess', ['user' => User::find($eventUser->user_id)->name, 'courseAbbrv' => $eventUser->event->CourseNameAndAbbrv]));
                }
                if ($pageFrom == 'waitlist') {
                    return redirect()->route('event_dashboard', [$eventUser->event_id, 'waitlist'])
                        ->with('success', trans('alerts.frontend.eventusers.moveToParkingLotSuccess', ['user' => User::find($eventUser->user_id)->name, 'courseAbbrv' => $eventUser->event->CourseNameAndAbbrv]));
                } else //mycourses waitlist
                {
                    return redirect()->route('mycourses.waitlist')
                        ->with('success', trans('alerts.frontend.eventusers.moveToParkingLotSuccess', ['user' => User::find($eventUser->user_id)->name, 'courseAbbrv' => $eventUser->event->CourseNameAndAbbrv]));
                }
            }
            else
            {
                //unexpected error updating
                return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.unexpected'));
            }
        }
        else //they are being added directly to parking lot (aka "course waitlist")
        {
            //I don't have a way for admins to do this yet
        }

    }


    /**
     * delete event user/role from DB
     * 
     * events/users.blade.php
     */
    public function delete($id, $tab = null)
    {
        //record to be soft deleted
        $destroyEventUser = EventUser::find($id);

        //store these for use in email function later
        $event_id = $destroyEventUser->event_id;
        $user_id = $destroyEventUser->user_id;
        $role_id = $destroyEventUser->role_id;

        //before soft deleting the record, set last_edited_by to user who is removing them
        $destroyEventUser->last_edited_by = Auth::user()->id;
        $destroyEventUser->save();

        //soft delete
        $destroyEventUser->destroy($id);

        //update event_user_history
        $eventUserHistory = EventUserHistory::create(
            [
                'event_user_id' => $destroyEventUser->id,
                'action_id' => 4, //Removed
                'display_text' => Role::find($role_id)->name,
                'action_by' => Auth::user()->id,
            ]
        );


        //if delete is successful send email
        if ($destroyEventUser) {
            event(new DropUserFromEvent(User::find($user_id), Event::find($event_id), Role::find($role_id)));
        }

        //figure out what page they came from, take them back there and customize text in alert as needed
        if ($tab == 'roster') //from the roster tab in event dashboard
        {
            return redirect()->route('event_dashboard', [$event_id, $tab])
                ->with('success', trans('alerts.frontend.eventusers.deleted', ['UserName' => User::find($user_id)->name, 'eventOrWaitlist' => 'event']));
        }
        elseif ($tab == 'waitlist') //from the waitlist tab in event dashboard
        {
            return redirect()->route('event_dashboard', [$event_id, $tab])
                ->with('success', trans('alerts.frontend.eventusers.deleted', ['UserName' => User::find($user_id)->name, 'eventOrWaitlist' => 'waitlist']));
        }
        else //from mycourses waitlist
        {
            return redirect()->route('mycourses.waitlist')
                ->with('success', trans('alerts.frontend.eventusers.deleted', ['UserName' => User::find($user_id)->name, 'eventOrWaitlist' => 'waitlist']));
        }
    }

    /**
     * User started payment process and now changed mind and wants to remove request
     */
    public function deletePendingPayment($id)
    {
        //record to be soft deleted
        $destroyEventUser = EventUser::find($id);

        //before soft deleting the record, set last_edited_by to user who is removing them
        $destroyEventUser->last_edited_by = Auth::user()->id;
        $destroyEventUser->save();

        //soft delete
        $destroyEventUser->destroy($id);

        //update event_user_history
        $eventUserHistory = EventUserHistory::create(
            [
                'event_user_id' => $destroyEventUser->id,
                'action_id' => 4, //Removed
                'display_text' => 'User self removed pending payment request',
                'action_by' => Auth::user()->id,
            ]
        );

        return redirect()->back();
    }


    /**
     * move event user/role from one event to the next - update DB
     * 
     * events/users-move.blade.php
     */
    public function move(MoveEventUserRequest $request)
    {
        // actual logged in user - not the user to update
        $user = Auth::user();

        // Event User Record to Modify
        $eventUser = EventUser::findOrFail($request->post('event_user_id'));

        $event_id = $request->post('event_move_id'); // event to move user into

        $tab = $request->post('tab'); // tab to go back too after success, roster or waitlist

        // auth check for event they are being moved into (to make sure the person adding has permission)
        $event = Event::findOrFail($event_id);

        // auth check to make sure user can't modify events they are not associated with
        if(!$event->hasSiteCourseEventPermissions('add-people-to-events',
            'course-add-people-to-events',
            'event-add-people-to-events'))
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.access',
                    ['date'=>$event->DisplayStartDate]));

        //Step 1: if user is already actively enrolled (not soft deleted)
        // in the event they are being moved into throw an error
        $eventUserCheck = EventUser:: where('event_id', $event_id)
                                        ->where('user_id', $eventUser->user_id)
                                        ->first();

        //redirect back to the move view with an error message that they are already enrolled
        if($eventUserCheck)
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.alreadyEnrolled', ['user'=>User::find($eventUserCheck->user_id)->name, 'date'=>$eventUserCheck->event->DisplayEventNameShort, 'eventId'=>$eventUserCheck->event->id]));
        }

        //Step 2: if a trashed record already exists for the event they are being moved into we
        // need to permanently delete it first because we can't have two records with the same
        // event_id & user_id combination (making the assumption that the event_user record they will be editing
        // in step 3 is more current and has related data they want to keep)
        // note: this will also delete related records in event_user_history via cascade delete

        EventUser::onlyTrashed()
                    ->where('event_id', $request->post('event_move_id')) //event they are being moved into
                    ->where('user_id', $eventUser->user_id)
                    ->forceDelete();


        //Step 3: enroll them in the new event
        //  if they were previously soft deleted it will restore that record
        //  if they were waitlisted, it will change status to enrolled

        //remove from old event email - because of the way Tanner set this up passing 3 ID's instead of the event_user_id, you have to send email BEFORE you change record
        event(new DropUserFromEvent(User::find($eventUser->user_id), Event::find($eventUser->event_id), Role::find($eventUser->role_id)));

        // we already know the record we are updating via $eventUser->id - no need to set the user_id
        $newEventUser = EventUser::where('id', '=', $eventUser->id)->update(
            ['updated_at' => \Carbon\Carbon::now(),
                'status_id' => 1,
                'last_edited_by' => $user->id,
                'event_id' => $event_id]);

        //on successful change of event_id, send a remove email followed by an add email
        if($newEventUser)
        {
            $newEventUser = EventUser::find($eventUser->id);

            //add to new event email
            event(new UserAddedToEvent(User::find($newEventUser->user_id), Event::find($newEventUser->event_id), Role::find($newEventUser->role_id)));

            //update event_user_history
            $eventUserHistory = EventUserHistory::create(
                [
                    'event_user_id' => $eventUser->id,
                    'action_id' => 3, //Moved
                    'display_text' => $eventUser->event_id . ", " . $event_id,
                    'action_by' => Auth::user()->id,
                ]
            );

            //on successful move redirect back to where they came from, link in alert to go to event they were moved into
            if($tab == 'mycourses') //go back to mycourses waitlist
            {
                return redirect()->route('mycourses.waitlist', [])
                    ->with('success', trans('alerts.frontend.eventusers.moveSuccess',
                        ['user' => User::find($newEventUser->user_id)->name,
                            'date' => "<a href=/courseInstance/events/event-dashboard/" .
                                $newEventUser->event_id . "/roster>" . $newEventUser->event->DisplayEventNameShort . "</a>"]));
            }
            else //go back to event dashboard tab
            {
                return redirect()->route('event_dashboard', [$eventUser->event_id, $tab])
                    ->with('success', trans('alerts.frontend.eventusers.moveSuccess',
                        ['user' => User::find($newEventUser->user_id)->name,
                            'date' => "<a href=/courseInstance/events/event-dashboard/" .
                                $newEventUser->event_id . "/roster>" . $newEventUser->event->DisplayEventNameShort . "</a>"]));
            }
        }
        {
            return redirect()->back()->withErrors(trans('alerts.frontend.eventusers.unexpected'));
        }
    }

}
