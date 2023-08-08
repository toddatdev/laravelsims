<?php

namespace App\Http\Controllers\Course;

use App\Events\Backend\Course\SendManualEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseEmailRequest;
use App\Http\Requests\Course\CourseSendManuallyRequest;
use App\Models\Access\RoleUser\RoleUser;
use App\Models\Course\Course;
use App\Models\Course\CourseEmails;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventEmails;
use App\Models\Site\SiteEmailTypes;
use App\Repositories\Backend\Access\Role\RoleRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\Facades\DataTables;

class CourseEmailsController extends Controller
{
    protected $roles;
    protected $course;
    protected $request;

    public function __construct(RoleRepository $roles, Course $course, Request $request)
    {
        $this->roles = $roles;
        $this->course = $course;
        $this->request = $request;
    }


    public function index(Request $request) {
        $id = Session::get('course_id');
        $course = $this->course->find($id);
        $type = $request->type;
        return view('courses.emails.new.index', compact('course', 'type'));
    }


    public function show($id) {
        // mimic a user request, build on the fly to send to datatables
        $request = new Request();
        $request->setMethod('POST');
        $request->request->add(['type' => 2]); // set to view course by default
        $request->request->add(['course_id' => $id]);
        $name = $this->course->find($id)->name;
        $course = $this->course->find($id);
        session(['course_id' => $id]);
        
        // Need to store the prev. path so we can send back correctly
        $prev_path = explode('/',\URL::previous());

        if(sizeof($prev_path) == 5) { // Coming From Catalog
            $via = $prev_path[4];
        }
        else if(sizeof($prev_path) == 4) { // Coming From My Courses
            $via = $prev_path[3];

        }
        $this->emailTableData($request);
        return view('courses.emails.new.index', compact('id', 'name', 'via', 'course'));
    }


    public function createCourse(Request $request) {
        $type = 2;
        $email_types = SiteEmailTypes::where('type', 2)->get()->pluck('name','id');

        $id = Session::get('course_id');
        $name = $this->course->find($id)->name;
        $course = $this->course->find($id);

        // get permission to pop To & CC fields
        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '=', 2)
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


        return view('courses.emails.new.create.course-create', compact('email_types', 'permissions_types', 'eventRoles', 'name', 'id', 'course'))
            ->with('type', $type);        

    }

    public function createEvent(Request $request) {
        $type = 3;

        $email_types = SiteEmailTypes::where('type', 3)->get()->pluck('name','id');        

        $id = Session::get('course_id');
        $name = $this->course->find($id)->name;
        $course = $this->course->find($id);

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

        // count all the events associted w/ this course
        $eventCount = Event::whereNotNull('events.initial_meeting_room')
                ->whereHas('CourseInstance', function($q) use ($id) {
                 $q->where('course_id', $id);
                })
            ->count();

        return view('courses.emails.new.create.event-create', compact('email_types', 'permissions_types', 'eventRoles', 'name', 'id', 'eventCount', 'course'))
            ->with('type', $type);        

    }
/**
*  store 
*  @desc - 
*  $request CourseEmailRequest
* @return 
*/
    public function store(CourseEmailRequest $request) {    
        $user = Auth::user();
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;

        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;

        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;
        
        $request['edited'] = 1;

        $courseEmail = CourseEmails::create($request->all());

        if ($request->option == 1) {
            // TODO : At some point we should think about limiting this to just add these
            // emails for events after the time this email was created. Adding emails
            // to long past events is going to be a waste.
            $events = Event::whereNotNull('events.initial_meeting_room')
                ->whereHas('CourseInstance', function($q) use ($courseEmail) {
                 $q->where('course_id', $courseEmail->course_id);
             })
                ->get();
            //Keep the original email type, as we might change the $request->email_type_id
            $og_req_id = $request->email_type_id;
            foreach ($events as $event) {
                $request['email_type_id'] = $og_req_id;
                $send_at = null;
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
                  
                    $utcDate = Carbon::createFromFormat('Y-m-d H:i:s', $date, $event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');

                     // Replacing User session timezone check, with the timezone of the building the event will take place.
                    $dateAdj = date_add(Carbon::parse($utcDate, $event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));

                    $send_at = Carbon::parse($dateAdj)->format('Y-m-d H:i:s');
                    
                    // get cur time in local via the session
                    $curr = Carbon::now()->timezone('UTC')->format('Y-m-d H:i:s');
                    if($send_at < $curr) {
                        $request['email_type_id'] = 9; // too late to send, set it to Send Manually
                        $send_at = null;
                    }
                }
                
                EventEmails::create([
                    'event_id' => $event->id,      
                    'email_type_id' => $request['email_type_id'],
                    'course_email_id' => $courseEmail->id,
                    'label' => $request->label,          
                    'subject' => $request->subject,       
                    'body' => $request->body,           
                    'to_roles' => $request['to_roles'],       
                    'to_other' => $request->to_other,       
                    'cc_roles' => $request['cc_roles'],       
                    'cc_other' => $request->cc_other,       
                    'bcc_roles' => $request['bcc_roles'],      
                    'bcc_other' => $request->bcc_other,      
                    'time_amount' => $request->time_amount,    
                    'time_type' => $request->time_type,      
                    'time_offset' => $request->time_offset,
                    'send_at' => $send_at, // Need to calculate           
                    'role_id' => $request->role_id,       
                    'role_amount' => $request->role_amount,   
                    'role_offset' => $request->role_offset,   
                    'created_by' => $request['created_by'],    
                    'last_edited_by' => $request['created_by'],                    
                ]);
            }
        }
        // Redirect back to index
        return redirect()->route('courseInstanceEmails.index', ['type' => $this->request->type])->with('success','Email created successfully.');
    }

    public function editCourse($id) {
        $courseEmails = CourseEmails::findOrFail($id);
        $id = Session::get('course_id');
        $name = $this->course->find($id)->name;
        $course = $this->course->find($id);

        $email_types = SiteEmailTypes::where('type', 2)->get()->pluck('name','id');

        // get premission to pop To & CC fields
        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '=', 2)
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
        $toRolesArrSelected = explode(',', $courseEmails->to_roles);
        $ccRolesArrSelected = explode(',', $courseEmails->cc_roles);
        $bccRolesArrSelected = explode(',', $courseEmails->bcc_roles);

        return view('courses.emails.new.edit.course-edit', compact('courseEmails', 'permissions_types', 'email_types', 'eventRoles', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected', 'id', 'name', 'course'))
            ->with('type', 2);        

    }

    public function editEvent($id) {

        $courseEmails = CourseEmails::findOrFail($id);
        $id = Session::get('course_id');
        $name = $this->course->find($id)->name;
        $course = $this->course->find($id);

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
        $toRolesArrSelected = explode(',', $courseEmails->to_roles);
        $ccRolesArrSelected = explode(',', $courseEmails->cc_roles);
        $bccRolesArrSelected = explode(',', $courseEmails->bcc_roles);

        $uneditCount = EventEmails::where('course_email_id', $courseEmails->id)->where('edited', '0')->count();
        $allCount = EventEmails::where('course_email_id', $courseEmails->id)->count();

        return view('courses.emails.new.edit.event-edit', compact('courseEmails', 'permissions_types', 'email_types', 'eventRoles', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected', 'id', 'name', 'course', 'uneditCount', 'allCount'))
            ->with('type', 3);

    }

    public function update($id, CourseEmailRequest $request) {        
        // Build
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;
        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;
        
        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;
        
        $request['edited'] = 1;



        // Find 
        $courseEmails = CourseEmails::find($id);

        // Update
        $courseEmails->update($request->all());

        // update unedited
        if ($request->option == 1) {
            $emails = EventEmails::select('event_emails.*')
                    ->join('events', 'events.id', 'event_emails.event_id')
                    ->whereNull('events.deleted_at')  //not deleted events 
                    ->whereNotNull('events.initial_meeting_room')  //not parking lot events that don't have IMR
                    ->where('event_emails.edited', '=', 0)
                    ->where('event_emails.course_email_id', $id)
                    ->get();
            $og_req_id = $request->email_type_id;
            foreach ($emails as $email) {
                $request['email_type_id'] = $og_req_id;
                $request['send_at'] = null;

                if ($request->email_type_id == 8) {
                    $date;
                    $amount = 0;
                    switch($request->time_offset) {
                        case 1:
                            $date = $email->event->start_time;
                            $amount = -abs($request->time_amount);
                            break;
                        case 2:
                            $date = $email->event->start_time;
                            $amount = abs($request->time_amount);
                            break;
                        case 3:
                            $date = $email->event->end_time;
                            $amount = -abs($request->time_amount);
                            break;
                        case 4:
                            $date = $email->event->end_time;
                            $amount = abs($request->time_amount);
                            break;
                        default:
                            $date = $email->event->start_time;
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
                    $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date, $email->event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');

                    // Replacing User session timezone check, with the timezone of the building the event will take place.                    
                    $dateAdj = date_add(\Carbon\Carbon::parse($date, $email->event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));
                                       
                    $send_at = \Carbon\Carbon::parse($dateAdj)->format('Y-m-d H:i:s'); 
                    $request['send_at'] = $send_at;

                    // get cur time in local via the session
                    $curr = \Carbon\Carbon::now('UTC')->format('Y-m-d H:i:s');
                    if($send_at < $curr) {
                        $request['email_type_id'] = 9; // Event Send Manually
                        $request['send_at'] = null;
                    }                    
                }
                // update record
                $email->update($request->all());
            }      
        }

        // update all, regardless if they have been edited or not
        if ($request->option == 2) {
            $emails = EventEmails::select('event_emails.*')
                    ->join('course_emails', 'event_emails.course_email_id', 'course_emails.id')
                    ->join('events', 'events.id', 'event_emails.event_id')
                    ->whereNull('events.deleted_at')  //We'll get errors getting deleted events
                    ->whereNotNull('events.initial_meeting_room')  //not parking lot events that don't have IMR
                    ->where('course_email_id', $id)
                    ->get();
            $og_req_id = $request->email_type_id;            
            foreach ($emails as $email) {
                $request['email_type_id'] = $og_req_id;
                $request['send_at'] = null;
                $request['edited'] = 0; //reset the event_email.edited flag to zero
                if ($request->email_type_id == 8) {
                    $date;
                    $amount = 0;
                    switch($request->time_offset) {
                        case 1:
                            $date = $email->event->start_time;
                            $amount = -abs($request->time_amount);
                            break;
                        case 2:
                            $date = $email->event->start_time;
                            $amount = abs($request->time_amount);
                            break;
                        case 3:
                            $date = $email->event->end_time;
                            $amount = -abs($request->time_amount);
                            break;
                        case 4:
                            $date = $email->event->end_time;
                            $amount = abs($request->time_amount);
                            break;
                        default:
                            $date = $email->event->start_time;
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
                    
                    $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date, $email->event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');

                    // Replacing User session timezone check, with the timezone of the building the event will take place.                      
                    $dateAdj = date_add(\Carbon\Carbon::parse($date, $email->event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));                    
                    $send_at = \Carbon\Carbon::parse($dateAdj)->format('Y-m-d H:i:s'); 
                    $request['send_at'] = $send_at;

                    // get cur time in local via the session
                    $curr = \Carbon\Carbon::now('UTC')->format('Y-m-d H:i:s');
                    if($send_at < $curr) {
                        $request['email_type_id'] = 9; // Event Send Manually
                        $request['send_at'] = null;
                    }                    
                }
                // update record
                $email->update($request->all());
            }
        }

        // only apply update to new events
        if ($request->option == 3) {
            // TC - 2020_02_04 not sure why I left this here ...
        }


        return redirect()->route('courseInstanceEmails.index', ['type' => $this->request->type])->with('success','Email updated successfully.');
    }

    public function emailTableData(Request $request) { // $request is course_id passed from @show()
        $emailType = $request->get('type');
        $course_id = $request->get('course_id');

        // Course Email
        if($emailType == '2') {
            $data = CourseEmails::select('course_emails.id', 'course_emails.course_id','course_emails.label', 'email_types.name', 'email_types.type')
                ->join('email_types', 'email_types.id', '=', 'course_emails.email_type_id')
                ->where('email_types.type', 2)
                ->where('course_emails.course_id', $course_id)
                ->get();
        }

        if($emailType == '3') {
            $data = CourseEmails::select('course_emails.id', 'course_emails.course_id','course_emails.label', 'email_types.name', 'email_types.type')
                ->leftJoin('event_emails', 'course_emails.id', '=', 'event_emails.course_email_id')
                ->join('email_types', 'email_types.id', '=', 'course_emails.email_type_id')
                ->where('email_types.type', 3)
                ->where('course_emails.course_id', $course_id)
                ->distinct()
                ->get();
        }
    
        return DataTables::of($data)
            ->addColumn('actions', function($d) {
                return $d->action_buttons;
            })->rawColumns(['actions'])->make(true);

    }

    public function clone($id) {
        $type = $this->request->input('type');
        $clonedEmail = CourseEmails::find($id);

        // Course
        if($type == 2) {
            $email_types = SiteEmailTypes::where('type', 2)->get()->pluck('name','id');

            $id = Session::get('course_id');
            $name = $this->course->find($id)->name;
    
            // get permission to pop To & CC fields
            $permissions_table = DB::table('permissions')
                ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
                ->join('roles', 'permission_role.role_id', '=', 'roles.id')
                ->where('permission_type_id', '=', 2)
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

            $toRolesArrSelected = explode(',', $clonedEmail->to_roles);
            $ccRolesArrSelected = explode(',', $clonedEmail->cc_roles);
            $bccRolesArrSelected = explode(',', $clonedEmail->bcc_roles);

            return redirect()->route('course.create.course')->with(compact('clonedEmail', 'email_types', 'permissions_types', 'eventRoles', 'name', 'id', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected'));
        }

        // Event
        else if ($type == 3) {
            $email_types = SiteEmailTypes::where('type', 3)->get()->pluck('name','id');

            $id = Session::get('course_id');
            $name = $this->course->find($id)->name;
    
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
            $toRolesArrSelected = explode(',', $clonedEmail->to_roles);
            $ccRolesArrSelected = explode(',', $clonedEmail->cc_roles);
            $bccRolesArrSelected = explode(',', $clonedEmail->bcc_roles);

            // count all the events associted w/ this course
            $eventCount = Event::whereNotNull('events.initial_meeting_room')
                ->whereHas('CourseInstance', function($q) use ($clonedEmail) {
                 $q->where('course_id', $clonedEmail->course_id);
                })
                ->count();

            return redirect()->route('course.create.event')->with(compact('clonedEmail', 'permissions_types', 'email_types', 'eventRoles', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected', 'id', 'name', 'eventCount'));
        }
    }

    public function destroy($id) {
        $email = CourseEmails::find($id);
//        dd($email);
        if ($email->email_type_id == 2 || $email->email_type_id == 3) {
            $email->delete();
            return redirect()->route('courseInstanceEmails.index', ['type' => $this->request->input('type')])->with('success','Email Deleted successfully.');
        }else {
            $uneditCount = EventEmails::where('course_email_id', $email->id)->where('edited', '0')->count();
            $allCount = EventEmails::where('course_email_id', $email->id)->count();
            return view('courses.emails.new.delete', compact('email', 'uneditCount', 'allCount'));
        }        
    }

    // Handles the actual deleting
    public function remove(Request $request) {
        $this->validate(
            $request, 
            ['option' => 'required'],
            ['option.required' => 'Need to make a selection below, or cancel.']
        );

        if ($request->option == 1) { // delete just this
            CourseEmails::find($request->course_email_id)->delete();
        }
        
        if ($request->option == 2) { // delete all unedited 
            EventEmails::where('edited', '=', 0)
                ->where('course_email_id', $request->course_email_id)
                ->delete();
            
            // Then Delete this
            CourseEmails::find($request->course_email_id)->delete(); 
        }

        if ($request->option == 3) { // delete all
            EventEmails::where('course_email_id', $request->course_email_id)->delete();

            // Then Delete this
            CourseEmails::find($request->course_email_id)->delete(); 
        }

        return redirect()->route('courseInstanceEmails.index', ['type' => $this->request->input('type')])->with('success','Email deleted successfully.');

    }

    public function sendManually($id) {

        $email = CourseEmails::findOrFail($id);

        // get premission to pop To,CC & BCC fields
        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '=', 2)
            ->where('site_id', '=', Session::get('site_id'))
            ->orderby ('roles.name')
            ->get();

        if (!$permissions_table->isEmpty()) {
            // Roles ID & Name
            foreach ($permissions_table as $p) {
                $permissions_types[$p->role_id] = $p->name;
            }
        }else {
            $permissions_types = [null];
        }
        return view('courses.emails.new.send-manually', compact('email', 'permissions_types'));
    }

    public function sendNow(CourseSendManuallyRequest $request) {

        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;

        // Will still need to Validate
        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;

        $email = (object) $request->all();
        //dd($request);
        if ($email->to_roles == null AND $email->cc_roles == null AND $email->bcc_roles == null AND $email->to_other == "" AND $email->cc_other == "" AND $email->bcc_other == "")
        {
            return redirect('courses/courseInstanceEmails/manual-send/'.$request->course_email_id)->with('error', trans('strings.emails.no_addresses'));
        }
        else
        {
            //check to make sure people in roles
            $roleUsersTo = RoleUser::whereIn('role_id', explode(',', $email->to_roles))->get();
            $roleUsersCC = RoleUser::whereIn('role_id', explode(',', $email->cc_roles))->get();
            $roleUsersBCC = RoleUser::whereIn('role_id', explode(',', $email->bcc_roles))->get();

            if(!$roleUsersTo->isEmpty() OR !$roleUsersCC->isEmpty() OR !$roleUsersBCC ->isEmpty()
                OR $email->to_other != "" OR $email->cc_other != "" OR $email->bcc_other != "")
            {
                event(new SendManualEmail($email));
            }
            else
            {
                return redirect('courses/courseInstanceEmails/manual-send/'.$request->course_email_id)->with('error', trans('strings.emails.no_addresses'));
            }
        }
        return redirect('courses/courseInstanceEmails?type=2')->with('success',trans('strings.emails.sent_successfully'));
    }

}