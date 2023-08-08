<?php

namespace App\Http\Controllers\Site;

use App\Events\Backend\Site\SendManualEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\SiteEmailRequest;
use App\Http\Requests\Site\SiteSendManually;
use App\Models\Access\RoleUser\RoleUser;
use App\Models\Course\Course;
use App\Models\Course\CourseEmails;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventEmails;
use App\Models\Site\SiteEmails;
use App\Models\Site\SiteEmailTypes;
use App\Repositories\Backend\Access\Role\RoleRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\Facades\DataTables;

class SiteEmailsController extends Controller
{

    protected $roles;
    protected $request;

    public function __construct(RoleRepository $roles, Request $request)
    {
        $this->roles = $roles;
        $this->request = $request;
    }

    public function index() {
        return view('sites.emails.new.index');
    }

    public function createSite(Request $request) {
        $type = 1;

        $email_types = SiteEmailTypes::where('type', 1)->get()->pluck('name','id');

        return view('sites.emails.new.create.site-create', compact('email_types'))
            ->with('type', $type);
    }

    public function createCourse(Request $request) {
        $type = 2;

        $email_types = SiteEmailTypes::where('type', 2)->get()->pluck('name','id');

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

        // Get Course Count for this Site
        $courseCount = Course::where('site_id', Session::get('site_id'))
            ->whereNull('retire_date')
            ->count();

        return view('sites.emails.new.create.course-create', compact('email_types', 'permissions_types', 'eventRoles', 'courseCount'))
            ->with('type', $type);
    }

    public function createEvent(Request $request) {
        $type = 3;

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

        // Get Course Count for this Site
        $courseCount = Course::where('site_id', Session::get('site_id'))
            ->whereNull('retire_date')
            ->count();

        $eventCount = Event::join('course_instances', 'course_instances.id', '=', 'events.course_instance_id')
            ->join('courses', 'courses.id', '=', 'course_instances.course_id')
            ->whereNotNull('events.initial_meeting_room')
            ->where('courses.site_id', Session::get('site_id'))
            ->count();

        return view('sites.emails.new.create.event-create', compact('email_types', 'permissions_types', 'eventRoles', 'courseCount', 'eventCount'))
            ->with('type', $type);
    }

    public function store (SiteEmailRequest $request) {
        // check for type 2 || 3, if option is 1 then update to create course_email for every course
        $user = Auth::user();
        $request['site_id'] = Session::get('site_id');
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;
        // Check if columns are selected
        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;

        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;

        // Save new Site Email 
        $siteEmail = SiteEmails::create($request->all());

        //Hold the original email_type_id, as we might need to change it 
        // to manually send if the email sent_at date is in the past.
        $og_req_id = $request->email_type_id;    
                
        // If option to save to all courses was checked
        if ($request->option == 1) {
            $courses = Course::whereNull('retire_date')->get();

            foreach ($courses as $course) {
                $request["email_type_id"] = $og_req_id;
                $courseEmail = CourseEmails::create([
                    'course_id' => $course->id, 
                    'email_type_id' => $request->email_type_id,
                    'site_email_id' => $siteEmail->id,
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
                    'role_id' => $request->role_id,       
                    'role_amount' => $request->role_amount,   
                    'role_offset' => $request->role_offset,   
                    'created_by' => $request['created_by'],    
                    'last_edited_by' => $request['created_by'],
                ]);
                // Get all events that have courseinstances that match this course
                if ($request->type == 3) {
                    // TODO : At some point we should think about limiting this to just add these
                    // emails for events after the time this email was created. Adding emails
                    // to long past events is going to be a waste.
                    $events = Event::join('course_instances', 'course_instances.id', '=', 'events.course_instance_id')
                        ->select('events.*')
                        ->whereNotNull('events.initial_meeting_room')
                        ->where('course_instances.course_id', $course->id)
                        ->get();

                    foreach ($events as $event) {
                        $request["email_type_id"] = $og_req_id; //the email_type_id might have been reset in a previous loop
                        $send_at = null;
                        // if Automatically Send need to build send_at time
                        if ($request->email_type_id == 8) {
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
                                $request["email_type_id"] = 9; //it's too late to send, set it to Send Manually
                                $send_at = null;
                            }
                        } // if ($request->email_type_id == 8)
                        EventEmails::create([
                            'event_id' => $event->id,      
                            'email_type_id' => $request->email_type_id,
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
                    } // foreach ($events as $event)
                } //if ($request->type == 3)
            } // foreach ($courses as $course)
        } //($request->option == 1)

        // Redirect back to index
        return redirect()->route('emails.index', ['type' => $this->request->type])->with('success','Email created successfully.');
    }

    public function editSite($id) {
        $siteEmails = SiteEmails::findOrFail($id); // throw 404 page if not found, this would happen if user entered some random ID in URL

        $email_types = SiteEmailTypes::where('type', 1)->get()->pluck('name','id');

        return view('sites.emails.new.edit.site-edit', compact('siteEmails', 'email_types'))
            ->with('type', 1);
    }

    public function editCourse($id) {
        $siteEmails = SiteEmails::findOrFail($id); // throw 404 page if not found, this would happen if user entered some random ID in URL

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

        $uneditCount = CourseEmails::where('site_email_id', $id)->where('edited', '0')->count();

        $allCount = CourseEmails::where('site_email_id', $id)->count();

        // Gets the values from multi select
        $toRolesArrSelected = explode(',', $siteEmails->to_roles);
        $ccRolesArrSelected = explode(',', $siteEmails->cc_roles);
        $bccRolesArrSelected = explode(',', $siteEmails->bcc_roles);

        return view('sites.emails.new.edit.course-edit', compact('siteEmails', 'permissions_types', 'email_types', 'eventRoles', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected', 'uneditCount', 'allCount'))
            ->with('type', 2);
    }

    public function editEvent($id) {
        $siteEmails = SiteEmails::findOrFail($id); // throw 404 page if not found, this would happen if user entered some random ID in URL

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

        $uneditCount = CourseEmails::where('site_email_id', $id)->where('edited', '0')->count();
        $allCount = CourseEmails::where('site_email_id', $id)->count();

        $uneditEventCount = EventEmails::join('course_emails', 'course_emails.id', '=', 'event_emails.course_email_id')
                            ->join('events', 'events.id', 'event_emails.event_id')
                            ->whereNull('events.deleted_at')  //make sure we don't inlcude deleted events
                            ->where('course_emails.site_email_id', $id)
                            ->where('event_emails.edited', '0')
                            ->count();
        $allEventCount = EventEmails::join('course_emails', 'course_emails.id', '=', 'event_emails.course_email_id')
                            ->join('events', 'events.id', 'event_emails.event_id')
                            ->whereNull('events.deleted_at')  //make sure we don't inlcude deleted events
                            ->where('course_emails.site_email_id', $id)
                            ->count();

        // Gets the values from multi select
        $toRolesArrSelected = explode(',', $siteEmails->to_roles);
        $ccRolesArrSelected = explode(',', $siteEmails->cc_roles);
        $bccRolesArrSelected = explode(',', $siteEmails->bcc_roles);

        return view('sites.emails.new.edit.event-edit', compact('siteEmails', 'permissions_types', 'email_types', 'eventRoles', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected', 'uneditCount', 'allCount', 'uneditEventCount', 'allEventCount'))
            ->with('type', 3);
    }

    public function update($id, SiteEmailRequest $request)
    {
        // Build
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;
        $request['site_id'] = Session::get('site_id');      
        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;

        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;

        // Find 
        $siteEmails = SiteEmails::find($id);
 
        // Update
        $siteEmails->update($request->all());
        //Hold the original email_type_id, as we might need to change it 
        // to manually send if the email sent_at date is in the past.
        $og_req_id = $request->email_type_id;

        if ($request->option == 1) { //update course_emails, and event_emails only if NOT edited
            $courseEmails = CourseEmails::where('edited', '=', 0)
                ->where('site_email_id', $id)
                ->get();
            foreach ($courseEmails as $courseEmail) {
                // update with that new new
                $courseEmail->update($request->all());
            } 
            // update unedited event emails
            if($request->type == 3) {
                $evt_emails = EventEmails::select('event_emails.*')
                    ->join('course_emails', 'event_emails.course_email_id', 'course_emails.id')
                    ->join('events', 'events.id', 'event_emails.event_id')
                    ->whereNull('events.deleted_at')  //If we don't check for deleted emails, we'll get errors looking for 
                    ->where('course_emails.site_email_id', $id)
                    ->where('event_emails.edited', 0)
                    ->get();
                    
                foreach ($evt_emails as $evt_email) {
                    $request['email_type_id'] = $og_req_id;
                    $request['send_at'] = null;

                    // IF Auto Send rebuild send_at time
                    if ($request->email_type_id == 8) { // Type Automatically Send
                        $date;
                        $amount = 0;
                        switch($request->time_offset) {
                            case 1:
                                $date = $evt_email->event->start_time;
                                $amount = -abs($request->time_amount);
                                break;
                            case 2:
                                $date = $evt_email->event->start_time;
                                $amount = abs($request->time_amount);
                                break;
                            case 3:
                                $date = $evt_email->event->end_time;
                                $amount = -abs($request->time_amount);
                                break;
                            case 4:
                                $date = $evt_email->event->end_time;
                                $amount = abs($request->time_amount);
                                break;
                            default:
                                $date = $evt_email->event->start_time;
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

                        $utcDate = Carbon::createFromFormat('Y-m-d H:i:s', $date, $evt_email->event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');
                        
                        // Replacing User session timezone check, with the timezone of the building the event will take place.
                        $dateAdj = date_add(Carbon::parse($utcDate, $evt_email->event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));

                        $send_at = Carbon::parse($dateAdj)->format('Y-m-d H:i:s'); 
                        $request['email_type_id'] = 8;
                        $request['send_at'] = $send_at;
                                                
                        // get cur time in local via the session
                        $curr = Carbon::now()->timezone('UTC')->format('Y-m-d H:i:s');
                        if($send_at < $curr) {
                            $request['email_type_id'] = 9;// Event Send Manually
                            $request['send_at'] = null;
                        }
                    }
                    $evt_email->update($request->all());
                }
            }
        }

        //update every instance of the course_emails and event_emails table, even if edited
        if ($request->option == 2) { 
            $courseEmails = CourseEmails::where('site_email_id', $id)->get();
            foreach ($courseEmails as $courseEmail) {
                // update with that new email.
                $request['edited'] = 0; //Set each to not edited.
                $courseEmail->update($request->all());
            } 
            // If type 3 try to update the child event_email record for this site_email as well
            if($request->type == 3) {
                $evt_emails = EventEmails::select('event_emails.*')
                    ->join('course_emails', 'course_emails.id', 'event_emails.course_email_id')
                    ->join('events', 'events.id', 'event_emails.event_id')
                    ->whereNull('events.deleted_at')  //If we don't check for deleted emails, we'll get errors looking for event_model->start_time below for email_type_id=8 (automatic emails)
                    ->where('course_emails.site_email_id', '=', $id)
                    ->get();

                $og_req_id = $request->email_type_id;    
                foreach ($evt_emails as $evt_email) {
                    //reset the original email type id, since it my have been reset during
                    //a previous loop
                    $request['email_type_id'] = $evt_email->email_type_id;
                    $request['send_at'] = null;

                    if ($request->email_type_id == 8) { // Type Automatically Send
                        $date;
                        $amount = 0;
                        switch($request->time_offset) {
                            case 1:
                                $date = $evt_email->event->start_time;
                                $amount = -abs($evt_email->event->time_amount);
                                break;
                            case 2:
                                $date = $evt_email->event->start_time;
                                $amount = abs($evt_email->event->time_amount);
                                break;
                            case 3:
                                $date = $evt_email->event->end_time;
                                $amount = -abs($evt_email->event->time_amount);
                                break;
                            case 4:
                                $date = $evt_email->event->end_time;
                                $amount = abs($evt_email->event->time_amount);
                                break;
                            default:
                                $date = $evt_email->event->start_time;
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

                        $utcDate = Carbon::createFromFormat('Y-m-d H:i:s', $date, $evt_email->event->InitialMeetingRoom->location->building->timezone)->setTimezone('UTC');
                        
                        // Replacing User session timezone check, with the timezone of the building the event will take place.
                        $dateAdj = date_add(Carbon::parse($utcDate, $evt_email->event->InitialMeetingRoom->location->building->timezone)->timezone('UTC'), date_interval_create_from_date_string($amount . $time_type));

                        $send_at = Carbon::parse($dateAdj)->format('Y-m-d H:i:s'); 

                        $request['send_at'] = $send_at;

                        // get cur time in local via the session
                        $curr = Carbon::now()->timezone('UTC')->format('Y-m-d H:i:s');
                        if($send_at < $curr) {
                            $request['email_type_id'] = 9; // Event Send Manually
                            $request['send_at'] = null;
                        }
                    }
                    $request['edited'] = 0; //Set each to not edited.
                    $evt_email->update($request->all());
                }
            }
        }        

        // if option == 3, then do same as usual

        return redirect()->route('emails.index', ['type' => $this->request->type])->with('success','Email updated successfully.');
    }


    public function emailTableData(Request $request) {

        $emailType = $request->get('type');
        
        // Site Email
        if($emailType == '1') {
            $data = SiteEmails::where('email_types.type', 1)
                ->select('site_emails.id','site_emails.label', 'email_types.name', 'email_types.type')
                ->join('email_types', 'email_types.id', '=', 'site_emails.email_type_id')
                ->get();
        }

        // Course Email
        if($emailType == '2') {
            $data = SiteEmails::where('email_types.type', 2)
                ->select('site_emails.id','site_emails.label', 'email_types.name', 'email_types.type')
                ->join('email_types', 'email_types.id', '=', 'site_emails.email_type_id')
                ->get();
        }

        // Event Email
        if($emailType == '3') {
            $data = SiteEmails::where('email_types.type', 3)
                ->select('site_emails.id','site_emails.label', 'email_types.name', 'email_types.type')
                ->join('email_types', 'email_types.id', '=', 'site_emails.email_type_id')
                ->get();
        }
        
        return DataTables::of($data)
            ->addColumn('actions', function($d) {
                return $d->action_buttons;
            })->rawColumns(['actions'])->make(true);
    }

    public function destroy($id) {
        $email = SiteEmails::find($id);
        $type = $this->request->input('type');
        
        // using this static approach for now...
        if ($email->email_type_id == 1 || $email->email_type_id == 6 || $email->email_type_id == 7) {
            $email->delete();
            return redirect()->route('emails.index', ['type' => $type])->with('success','Email deleted successfully.');
        }else {
            if ($type == 2) {
                $uneditCount = CourseEmails::where('site_email_id', $id)->where('edited', '0')->count();
                $allCount = CourseEmails::where('site_email_id', $id)->count();
                return view('sites.emails.new.delete', compact('email', 'uneditCount', 'allCount'));
            }
            else if ($type == 3) {
                $eventEmail = true;
                $uneditCount = CourseEmails::where('site_email_id', $id)->where('edited', '0')->count();
                $allCount = CourseEmails::where('site_email_id', $id)->count();
                $uneditEventCount = EventEmails::join('course_emails', 'course_emails.id', '=', 'event_emails.course_email_id')->where('course_emails.site_email_id', $id)->where('event_emails.edited', '0')->count();
                $allEventCount = EventEmails::join('course_emails', 'course_emails.id', '=', 'event_emails.course_email_id')->where('course_emails.site_email_id', $id)->count();        
                return view('sites.emails.new.delete', compact('eventEmail','email', 'uneditCount', 'allCount', 'uneditEventCount', 'allEventCount'));
            }
        }
    }

    public function remove(Request $request) {

        $this->validate(
            $request, 
            ['option' => 'required'],
            ['option.required' => 'Need to make a selection below, or cancel.']
        );

        if ($request->option == 1) { // delete just this
            SiteEmails::find($request->site_email_id)->delete();
        }

        if ($request->option == 2) { // delete all unedited 
            if ($request->type == 3) {
                $id = $request->site_email_id;
                EventEmails::join('course_emails', function ($join) use($id) {
                    $join->on('event_emails.course_email_id', '=', 'course_emails.id')
                         ->where('course_emails.site_email_id', '=', $id);
                    })
                    ->where('event_emails.edited', '=', 0)
                    ->getQuery()
                    ->update(['event_emails.updated_at' => \Carbon\Carbon::now(),
                              'event_emails.deleted_at' => \Carbon\Carbon::now()]);
            }

            CourseEmails::where('edited', '=', 0)
                ->where('site_email_id', $request->site_email_id)
                ->delete();
       
            // Then Delete this
            SiteEmails::find($request->site_email_id)->delete(); 
        }

        if ($request->option == 3) { // delete all
            if ($request->type == 3) {
                $id = $request->site_email_id;
                EventEmails::join('course_emails', function ($join) use($id) {
                    $join->on('event_emails.course_email_id', '=', 'course_emails.id')
                         ->where('course_emails.site_email_id', '=', $id);
                    })
                    ->getQuery()
                    ->update(['event_emails.updated_at' => \Carbon\Carbon::now(),
                              'event_emails.deleted_at' => \Carbon\Carbon::now()]);
            }
            
            CourseEmails::where('site_email_id', $request->site_email_id)->delete();

            // Then Delete this
            SiteEmails::find($request->site_email_id)->delete(); 
        }

        return redirect()->route('emails.index', ['type' => $request->type])->with('success','Email deleted successfully.');
    }

    public function clone($id) {
        $clonedEmail = SiteEmails::find($id);

        $type = $this->request->input('type');
        if ($type == 1) { // Site Type
            $email_types = SiteEmailTypes::where('type', 1)->get()->pluck('name','id');

            return redirect()->route('email.create.site')->with(compact('clonedEmail', 'email_types'));            
        }

        // Course Type
        else if($type == 2) { 
            $email_types = SiteEmailTypes::where('type', 2)->get()->pluck('name','id');

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

            // Get Course Count for this Site
            $courseCount = Course::where('site_id', Session::get('site_id'))
                ->whereNull('retire_date')    
                ->count();    

            $toRolesArrSelected = explode(',', $clonedEmail->to_roles);
            $ccRolesArrSelected = explode(',', $clonedEmail->cc_roles);
            $bccRolesArrSelected = explode(',', $clonedEmail->bcc_roles);

            return redirect()->route('email.create.course')->with(compact('clonedEmail', 'email_types', 'permissions_types', 'eventRoles', 'courseCount', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected'));
        }

        // Event Type
        else if ($type == 3) { 
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
    
            // Get Course Count for this Site
            $courseCount = Course::where('site_id', Session::get('site_id'))
                ->whereNull('retire_date')
                ->count();
    
            $eventCount = Event::join('course_instances', 'course_instances.id', '=', 'events.course_instance_id')
                ->join('courses', 'courses.id', '=', 'course_instances.course_id')
                ->whereNotNull('events.initial_meeting_room')
                ->where('courses.site_id', Session::get('site_id'))
                ->count();     
            
            $toRolesArrSelected = explode(',', $clonedEmail->to_roles);
            $ccRolesArrSelected = explode(',', $clonedEmail->cc_roles);
            $bccRolesArrSelected = explode(',', $clonedEmail->bcc_roles);
                
            return redirect()->route('email.create.event')->with(compact('clonedEmail', 'email_types', 'permissions_types', 'eventRoles', 'courseCount', 'eventCount', 'toRolesArrSelected', 'ccRolesArrSelected', 'bccRolesArrSelected'));
        }        
    }

    public function sendManually($id) {
        $email = SiteEmails::findOrFail($id);
        // get premission to pop To,CC & BCC fields
        $permissions_table = DB::table('permissions')
            ->join('permission_role', 'permissions.id','=', 'permission_role.permission_id')
            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
            ->where('permission_type_id', '=', 1)
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
        return view('sites.emails.new.send-manually', compact('email', 'permissions_types'));
    }

    public function sendNow(SiteSendManually $request) {
        $request->input('to_roles') ? $request['to_roles'] = implode(',', $request->input('to_roles')) : $request['to_roles'] = null;
        $request->input('cc_roles') ? $request['cc_roles'] = implode(',', $request->input('cc_roles')) : $request['cc_roles'] = null;
        $request->input('bcc_roles') ? $request['bcc_roles'] = implode(',', $request->input('bcc_roles')) : $request['bcc_roles'] = null;


        // Will still need to Validate
        $request->input('to_other') ? $request['to_other'] = implode(',', $request->input('to_other')) : $request['to_other'] = null;
        $request->input('cc_other') ? $request['cc_other'] = implode(',', $request->input('cc_other')) : $request['cc_other'] = null;
        $request->input('bcc_other') ? $request['bcc_other'] = implode(',', $request->input('bcc_other')) : $request['bcc_other'] = null;

        $email = (object) $request->all();
        //dd($email);
        if ($email->to_roles == null AND $email->cc_roles == null AND $email->bcc_roles == null AND $email->to_other == "" AND $email->cc_other == "" AND $email->bcc_other == "")
        {
            return redirect('admin/site/emails/send/'.$request->site_email_id)->with('error', trans('strings.emails.no_addresses'));
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
                return redirect('admin/site/emails/send/'.$request->site_email_id)->with('error', trans('strings.emails.no_addresses'));
            }
        }
        return redirect('admin/site/emails')->with('success',trans('strings.emails.sent_successfully'));
    }

}