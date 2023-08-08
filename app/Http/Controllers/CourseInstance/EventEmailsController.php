<?php

namespace App\Http\Controllers\CourseInstance;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseInstance\EventEmailRequest;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventEmails;
use App\Models\Email\SentEmails;
use App\Models\Site\SiteEmailTypes;
use App\Repositories\Backend\Access\Role\RoleRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\Facades\DataTables;

// Bring in EventEmailModel

class EventEmailsController extends Controller
{
    protected $roles;
    protected $request;

    public function __construct(RoleRepository $roles, Request $request)
    {
        $this->roles = $roles;
        $this->request = $request;
    }

    /**
     * Default DataTable view for event emails given an Event
     * @return index-interface
     */
    public function index() {
        $event_info = Event::select('courses.name', 'resources.abbrv', 'events.start_time')
            ->join('course_instances', 'course_instances.id', '=', 'events.course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->join('resources', 'resources.id', '=', 'events.initial_meeting_room')
            ->where('events.id', \Request::segment(4))
            ->get();

//        $details = $event_info[0]->name . ' ' . $event_info[0]->abbrv . ' ' . \Carbon\Carbon::parse($event_info[0]->start_time)->format('Y-m-d g:i a');

        $event = Event::findOrFail(\Request::segment(4));

        return view('courseInstance.events.emails.index', compact('event'));
    }


    /**
     * Create New Event Email for an Event
     * @param event_id
     * @param request
     * @return create-interface
     */
    public function create($event_id, Request $request) {

        $event = Event::find($event_id);

        $email_types = SiteEmailTypes::where('type', 3)->get()->pluck('name','id');

        // get permission to pop To & CC fields
        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '!=', 1)
            ->where('site_id', '=', Session::get('site_id'))
            ->get();            

        if (!$permissions_table->isEmpty()) {
            // Roles ID & Name
            foreach ($permissions_table as $p) {
                $permissions_types[$p->role_id] = $p->name;
            }
        }else {
            $permissions_types = [null];
        }

        // To fill role_id select box
        $eventRoles = $this->roles->getRoles(3);
        // dd($event_id);
        return view('courseInstance.events.emails.create.create', compact('email_types', 'permissions_types', 'eventRoles', 'event_id', 'event'));
    }

    /**
     * Store newly Create Event Email
     * @param request
     * @return index
     */
    public function store(EventEmailRequest $request) {

        $user = Auth::user();
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;

        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;
        $request['edited'] = 2;


        // EventEmailRequest converts these into array to check for validation so we need to convert baack to comma seperated
        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;

        // Find Event that matches with email request
        $event = Event::find($request->event_id);

        if ($request->email_type_id == 8) { // auto send, so we can calc. send at time
            $date;
            $amount = 0;
            switch($request->time_offset) {
                case 1:
                    $date = $event->start_time;
                    $amount = -abs($request->time_amount);
                    break;
                case 2:
                    $date = $event->start_time;
                    $amount = abs($request->time_amount);
                    break;
                case 3:
                    $date = $event->end_time;
                    $amount = -abs($request->time_amount);
                    break;
                case 4:
                    $date = $event->end_time;
                    $amount = abs($request->time_amount);
                    break;
                default:
                    $date = $event->start_time;
                    $amount = 0;
            }

            $time_type = '';
            switch ($request->time_type) {
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
            // get cur time in UTC
            $curr = \Carbon\Carbon::now('UTC')->format('Y-m-d H:i:s');
            if($send_at < $curr) {
                $request['email_type_id'] = 9; // Event Send Manually
                $request['send_at'] = null;
            }else {
                $request['send_at'] = $send_at;
            }
        }        

        // Save new record
        $eventEmail = EventEmails::create($request->all());

        return redirect()->route('event_dashboard',[$eventEmail->event_id, 'email'])
            ->with('success', trans('alerts.frontend.emails.create', ['label'=>$eventEmail->label]));

    }


    /**
     * Edit Existing Event Email
     * @param event_id
     * @param id
     * @return edit-interface
     */
    public function edit($event_id, $id) {

        $event = Event::find($event_id);

        $eventEmail = EventEmails::findOrFail($id);

        $email_types = SiteEmailTypes::where('type', 3)->get()->pluck('name','id');

        // get premission to pop To & CC fields
        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '!=', 1)
            ->where('site_id', '=', Session::get('site_id'))
            ->get();

        if (!$permissions_table->isEmpty()) {
            // Roles ID & Name
            foreach ($permissions_table as $p) {
                $permissions_types[$p->role_id] = $p->name;
            }
        }else {
            $permissions_types = [null];
        }

        // To fill role_id select box
        $eventRoles = $this->roles->getRoles(3);

        // Gets the values from multi select
        $toRolesArrSelected = explode(',', $eventEmail->to_roles);
        $ccRolesArrSelected = explode(',', $eventEmail->cc_roles);
        $bccRolesArrSelected = explode(',', $eventEmail->bcc_roles);

        return view('courseInstance.events.emails.edit.edit', compact('eventEmail', 'email_types', 'permissions_types', 'eventRoles','toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected', 'event_id', 'event'));
    }

    /**
     * Update Existing Event Email
     * @param request
     * @return index
     */
    public function update(EventEmailRequest $request) {
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;
        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;
        $request['edited'] = 2;

        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;

        // find the email
        $eventEmail = EventEmails::findOrFail($request->id);

        if ($request->email_type_id == 8) { // auto send, so we can calc. send at time
            // Find the event
            $event = Event::find($eventEmail->event_id);
            $date = null;
            $amount = 0;
            switch($request->time_offset) {
                case 1:
                    $date = $event->start_time;
                    $amount = -abs($request->time_amount);
                    break;
                case 2:
                    $date = $event->start_time;
                    $amount = abs($request->time_amount);
                    break;
                case 3:
                    $date = $event->end_time;
                    $amount = -abs($request->time_amount);
                    break;
                case 4:
                    $date = $event->end_time;
                    $amount = abs($request->time_amount);
                    break;
                default:
                    $date = $event->start_time;
                    $amount = 0;
            }

            $time_type = '';
            switch ($request->time_type) {
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

            // Adjust $date to UTC to account for UTC 
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date, $event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');

            // Replacing User session timezone check, with the timezone of the building the event will take place.  
            $dateAdj = date_add(\Carbon\Carbon::parse($date,  $event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));        
            
            $send_at = \Carbon\Carbon::parse($dateAdj)->format('Y-m-d H:i:s');
            // get cur time in UTC
            $curr = \Carbon\Carbon::now('UTC')->format('Y-m-d H:i:s');
            if($send_at < $curr) {
                $request['email_type_id'] = 9; // Event Send Manually
                $request['send_at'] = null;
            }else {
                $request['email_type_id'] = 8;
                $request['send_at'] = $send_at;
            }            

        }

        $eventEmail->update($request->all());

        return redirect()->route('event_dashboard',[$eventEmail->event_id, 'email'])
            ->with('success', trans('alerts.frontend.emails.edit', ['label'=>$eventEmail->label]));

    }


    /**
     * Clone an exsiting Event Email
     * @param event_id
     * @param id
     * @return clone-interface
     */
    public function clone($event_id, $id) {
        $clonedEmail = EventEmails::find($id);
        
        
        $email_types = SiteEmailTypes::where('type', 3)->get()->pluck('name','id');

        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '!=', 1)
            ->where('site_id', '=', Session::get('site_id'))
            ->get();

        if (!$permissions_table->isEmpty()) {
            // Roles ID & Name
            foreach ($permissions_table as $p) {
                $permissions_types[$p->role_id] = $p->name;
            }
        }else {
            $permissions_types = [null];
        }

        // To fill role_id select box
        $eventRoles = $this->roles->getRoles(3);

        // Gets the values from multi select
        $toRolesArrSelected = explode(',', $clonedEmail->to_roles);
        $ccRolesArrSelected = explode(',', $clonedEmail->cc_roles);
        $bccRolesArrSelected = explode(',', $clonedEmail->bcc_roles);        

        return redirect()->route('event.email.create', ['event_id' => $event_id])->with(compact('clonedEmail', 'event_id','permissions_types', 'email_types', 'eventRoles', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected'));
    }

    /**
     * Soft Delete Event Email
     * @param event_id
     * @param id
     * @return EventEmail-index 
     */
    public function destroy($event_id, $id) {
        // Find $id 
        $email = EventEmails::findOrFail($id); 

        // match record event_id w/ passed $event_id
        if ($email->event_id == $event_id) {
            $email->delete();
        }

        return redirect()->route('event_dashboard',[$email->event_id, 'email'])
            ->with('success', trans('alerts.frontend.emails.delete', ['label'=>$email->label]));

    }


    /**
     * Builds Event Email Table Data
     */
    public function emailTableData(Request $request) {

        $event_id = $request->get('event_id');

        $eventEmails = EventEmails::where('event_id', $event_id)->get();

        return DataTables::of($eventEmails)
            ->addColumn('actions', function($eventEmails) {
                return $eventEmails->action_buttons;
            })
            ->addColumn('label', function ($eventEmails) {
                return $eventEmails->label;
            })
            ->addColumn('type_name', function ($eventEmails) {
                return $eventEmails->emailType->name;
            })
            ->rawColumns(['actions', 'subject'])
            ->make(true);
    }

    /**
    * Builds Sent Email Table Data that goes under the Event Emails on the Event Dashboard
    * @author lutzjw
    * @date   3/16/20 16:44
    * @param  Request 
    * @return DataTable
    */
    public function sentEmailTableData(Request $request) {

        $event_id = $request->get('event_id');

        $sentEmailMessages = SentEmails::whereHas('eventEmails', function($q) use ($event_id){
            $q->where('event_id', '=', $event_id);
        })->get();

        return DataTables::of($sentEmailMessages)
            ->addColumn('label', function ($sentEmailMessages) {
                return $sentEmailMessages->eventEmails->label;
            })
            ->addColumn('type_name', function ($sentEmailMessages) {
                return $sentEmailMessages->eventEmails->emailType->name;
            })
            ->addColumn('created_at', function ($sentEmailMessages) {
                return $sentEmailMessages->DisplayCreatedAt;
            })
            ->addColumn('actions', function($sentEmailMessages) {
                return $sentEmailMessages->ActionButtons;
            })
            ->rawColumns(['actions', 'subject'])
            ->make(true);
    }

    /**
    * Show the specified Event email. 
    * @author lutzjw
    * @date   3/18/20 08:54
    * @return A view to the event email. 
    */
    public function showSentEmail(Request $request) {
        $sentEmail = SentEmails::findOrFail($request->id);
        return view('courseInstance.events.showEventEmail', compact('sentEmail'));
    }
}
