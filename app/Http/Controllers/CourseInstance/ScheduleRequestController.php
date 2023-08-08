<?php

namespace App\Http\Controllers\CourseInstance;

use App\Models\CourseInstance\ScheduleRequest;
use App\Models\CourseInstance\ScheduleRequestGroupId;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\Site\Site;
use App\Models\Course\Course;
use App\Models\Location\Location;
use App\Models\Resource\Resource;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Course\CourseTemplate;
use App\Http\Requests\CourseInstance\StoreScheduleRequest;
use App\Models\CourseInstance\ScheduleComment;
use App\Http\Requests\CourseInstance\DenyScheduleRequest;
use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Mail\scheduleRequestDeny;
use App\Mail\scheduleRequestSubmit;
use App\Mail\scheduleRequestConfirmation;
use Jenssegers\Agent\Agent;


class ScheduleRequestController extends Controller
{


    // returns the number of pending requests for this site
    // pending = where event_id = null and denied_date=null and course->site_id = this site)
    public function pendingRequestCount()
    {
        $user = Auth::user();

        // if there is no logged in user return 0 to prevent crash on calendar
        if($user == null) return 0;
        
        $pendingRequestCount = ScheduleRequest::join('location_schedulers', 'location_schedulers.location_id', '=', 'schedule_requests.location_id');
        $pendingRequestCount->whereHas('course', function($query){
            $query->where('site_id', SESSION::get('site_id'));
        })
            ->where('event_id', null)
            ->where('denied_date', null)
            ->where('deleted_at', null)
            ->where('location_schedulers.user_id', $user->id)
            ->orderBy('start_time')->get();

        return $pendingRequestCount->count();
    }

    // returns the number of pending requests for the current user
    // pending = where event_id = null and denied_date=null and course->site_id = this site)
    public function userPendingRequestCount()
    {
        $user = Auth::user();
        $userPendingRequestCount = ScheduleRequest::whereHas('course', function($query){
            $query->where('site_id', SESSION::get('site_id'));
        })
            
            ->where('event_id', null)
            ->where('denied_date', null)
            ->where('deleted_at', null)
            ->where('requested_by', '=', $user->id)
            ->orderBy('start_time')->count();

        return $userPendingRequestCount;
    }

    // scheduleRequests return requests.blade.php for views, sets datatable to display the requestType
    public function pending()
    {
        $requestType = 'pending';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $businessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }

    public function approved()
    {
        $requestType = 'approved';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }

    public function denied()
    {
        $requestType = 'denied';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }

    public function all()
    {
        $requestType = 'all';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }


    // myScheduleRequests return requests-user.blade.php for view, sets datatable to display the requestType
    public function userPending()
    {
        $requestType = 'pending';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $businessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests-user', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }

    public function userApproved()
    {
        $requestType = 'approved';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests-user', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }

    public function userDenied()
    {
        $requestType = 'denied';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests-user', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }

    public function userAll()
    {
        $requestType = 'all';
        $site_id = Session::get('site_id');
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();
        extract($locationsAndResources);
        return view('courseInstance.scheduleRequest.requests-user', compact('requestType', 'defaultStartHour', 'defaultEndHour', 'defaultBusinessBeginHour', 'defaultBusinessEndHour', 'locationsAndResources', 'businessBeginHour', 'businessEndHour'));
    }


    // scheduleRequests table data
    public function requestsTableData(Request $request)
    {

        $user = Auth::user();

        $scheduleRequests = ScheduleRequest::join('location_schedulers', 'location_schedulers.location_id', '=', 'schedule_requests.location_id');
        // $scheduleRequests = ScheduleRequest::find();
        $scheduleRequests->orderBy('start_time');

        // laravel was having conflicts with ids, added this long select
        $scheduleRequests->select('schedule_requests.id', 'schedule_requests.course_id','schedule_requests.group_request_id',
            'schedule_requests.template_id', 'schedule_requests.location_id', 'schedule_requests.start_time', 'schedule_requests.end_time',
            'schedule_requests.num_rooms', 'schedule_requests.class_size', 'schedule_requests.sims_spec_needed', 'schedule_requests.notes',
            'schedule_requests.event_id', 'schedule_requests.denied_by', 'schedule_requests.denied_date', 'schedule_requests.created_at',
            'schedule_requests.updated_at', 'schedule_requests.requested_by', 'schedule_requests.deleted_at', 'location_schedulers.user_id');

            if ($start_date = $request->get('start_date')) {
                $scheduleRequests->whereDate('created_at', '>=', $start_date);
            }

            if ($end_date = $request->get('end_date')) {
                $scheduleRequests->whereDate('created_at', '<=', $end_date);
            }

            //check which view is being requested by datatable ajax data call and enter into query
            if ($status = $request->get('status')){
                if($status == 'pending') {
                    $scheduleRequests->where('event_id', null);
                    $scheduleRequests->where('denied_date',  null);
                }

                if($status == 'denied') {
                    $scheduleRequests->where('denied_date', '!=', null);
                }

                if($status == 'approved') {
                    $scheduleRequests->where('event_id', '!=', null);
                }
            }

            $scheduleRequests->where('deleted_at', '=', null);
            $scheduleRequests->where('location_schedulers.user_id', '=', $user->id);

            $scheduleRequests->get();


        return DataTables::of($scheduleRequests)

            ->addColumn('course.abbrv', function($scheduleRequests) {
                return $scheduleRequests->course->abbrv;
            })
            ->addColumn('sim.spec.needed', function($scheduleRequests) {
                return $scheduleRequests->SimSpecialistNeededYN();
            })
            ->addColumn('location.abbrv', function($scheduleRequests) {
                return $scheduleRequests->location->building->abbrv . ' ' . $scheduleRequests->location->abbrv;
            })
            ->addColumn('requested_by', function($scheduleRequests) {
                return User::find($scheduleRequests->requested_by)->Name;
            })
            ->addColumn('date', function($scheduleRequests) {
                return date_create($scheduleRequests->start_time)->format('m/d/Y');
            })
            ->addColumn('time', function($scheduleRequests) {
                return date_create($scheduleRequests->start_time)->format('g:ia') . ' - ' . date_create($scheduleRequests->end_time)->format('g:ia');
            })
            ->addColumn('date_time', function($scheduleRequests) {
                return $scheduleRequests->requestDateTime();
            })
            ->addColumn('requested_timestamp', function($scheduleRequests) {
                return strtotime($scheduleRequests->created_at);
            })
            ->addColumn('group', function($scheduleRequests) {
                if($scheduleRequests->eventXofY()<>'1 of 1')
                {return $scheduleRequests->eventXofY() . ' (id: ' . $scheduleRequests->group_request_id . ')';}
                else
                {return '';}
            })
            ->addColumn('status', function($scheduleRequests) {
                return $scheduleRequests->status();
            })
            ->addColumn('submitted_date', function($scheduleRequests) {
                return date_create($scheduleRequests->created_at->timezone(session('timezone')))->format('m/d/y g:ia');
            })
            ->addColumn('submitted_timestamp', function($scheduleRequests) {
                return strtotime($scheduleRequests->created_at);
            })
            ->addColumn('setup_minutes', function($scheduleRequests) {
                return $scheduleRequests->SetupMinutes;
            })
            ->addColumn('teardown_minutes', function($scheduleRequests) {
                return $scheduleRequests->TeardownMinutes;
            })
            ->addColumn('start_time', function($scheduleRequests) {
                return $scheduleRequests->start_time;
            })
            ->addColumn('end_time', function($scheduleRequests) {
                return $scheduleRequests->end_time;
            })
            ->addColumn('actions', function($scheduleRequests) {
                return $scheduleRequests->pending_request_action_button; //app/model/scheduleRequest
            })
            ->rawColumns(['actions', 'sim.spec.needed', 'status'])

            ->make(true);
    }


    // myScheduleRequests table data
    public function userRequestsTableData(Request $request)
    {
        $user = Auth::user();

        $scheduleRequests = ScheduleRequest::orderBy('start_time');
        $scheduleRequests->where('requested_by', $user->id);
        $scheduleRequests->where('deleted_at', '=', null);

        // look for event date ranges for the request.  -jl 2019-07-22 13:46
        if ($start_date = $request->get('start_date')) {
            $scheduleRequests->whereDate('start_time', '>=', $start_date);
        }

        if ($end_date = $request->get('end_date')) {
            $scheduleRequests->whereDate('end_time', '<=', $end_date);
        }

        //check which view is being requested by datatable ajax data call and enter into query
        if ($status = $request->get('status')){
            if($status == 'pending') {
                $scheduleRequests->where('event_id', null);
                $scheduleRequests->where('denied_date',  null);
            }

            if($status == 'denied') {
                $scheduleRequests->where('denied_date', '!=', null);
            }

            if($status == 'approved') {
                $scheduleRequests->where('event_id', '!=', null);
            }
        }

        $scheduleRequests->get();

        return DataTables::of($scheduleRequests)

            ->addColumn('course.abbrv', function($scheduleRequests) {
                return $scheduleRequests->course->abbrv;
            })
            ->addColumn('sim.spec.needed', function($scheduleRequests) {
                return $scheduleRequests->SimSpecialistNeededYN();
            })
            ->addColumn('location.abbrv', function($scheduleRequests) {
                return $scheduleRequests->location->building->abbrv . ' ' . $scheduleRequests->location->abbrv;
            })
            ->addColumn('requested_date', function($scheduleRequests) {
                return date_create($scheduleRequests->created_at->timezone(session('timezone')))->format('m/d/Y g:ia');
            })
            ->addColumn('requested_timestamp', function($scheduleRequests) {
                return strtotime($scheduleRequests->created_at);
            })
            ->addColumn('date', function($scheduleRequests) {
                return date_create($scheduleRequests->start_time)->format('m/d/Y');
            })
            ->addColumn('time', function($scheduleRequests) {
                return date_create($scheduleRequests->start_time)->format('g:ia') . ' - ' . date_create($scheduleRequests->end_time)->format('g:ia');
            })
            ->addColumn('event_time', function($scheduleRequests) {
                return date_create($scheduleRequests->start_time)->format('m/d/y'). ' ' .date_create($scheduleRequests->start_time)->format('g:ia') . ' - ' . date_create($scheduleRequests->end_time)->format('g:ia');
            })
            ->addColumn('group', function($scheduleRequests) {
                if($scheduleRequests->eventXofY()<>'1 of 1')
                {return $scheduleRequests->eventXofY() . ' (id: ' . $scheduleRequests->group_request_id . ')';}
                else
                {return '';}
            })
            ->addColumn('status', function($scheduleRequests) {
                return $scheduleRequests->status();
            })
            ->addColumn('setup_minutes', function($scheduleRequests) {
                return $scheduleRequests->SetupMinutes;
            })
            ->addColumn('teardown_minutes', function($scheduleRequests) {
                return $scheduleRequests->TeardownMinutes;
            })
            ->addColumn('start_time', function($scheduleRequests) {
                return $scheduleRequests->start_time;
            })
            ->addColumn('end_time', function($scheduleRequests) {
                return $scheduleRequests->end_time;
            })
            ->addColumn('actions', function($scheduleRequests) {
                return $scheduleRequests->action_buttons; //app/model/scheduleRequest
            })
            ->rawColumns(['actions', 'sim.spec.needed', 'status'])

            ->make(true);
    }

    /**
     * Show the form for creating a new Schedule Request (/scheduleRequest/create)
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //this is used in the blade to customize the date based on broswer
        $agent = new Agent();

        //Locations Dropdown
        $locations = Location::get()->sortBy('building_location_label')->pluck('building_location_label', 'id')->toArray();

        //Template DropDown
        //mitcks: 2020-04-21 I am not sure why resources are also being pulled here with templates, I think this may be
        // residual from Matt's old code
        $templates = CourseTemplate::with(['courseTemplateResources', 'courseTemplateResources.Resources', 'course'])->get();

        //Courses Dropdown
        //  The myCourses scope is applied to select all courses the logged in user
        //      has permission to subit a schedule request for, the first parameter is the site level permission,
        //      the second is the course level permission and the third is the event level permission (not applicable in this case)
        //  The active scope is applied to limit to courses where retire_date is null
        $courses = Course::myCourses('schedule-request','course-schedule-request', '')
            ->active()
            ->orderby('abbrv')
            ->get();

        //For row headings in the grid
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();

        //extract puts this is the proper JSON format for the grid
        extract($locationsAndResources);

        return view('courseInstance.scheduleRequest.create', compact('agent', 'courses', 'locations', 'templates', 'locationsAndResources'));
    }

    public function anotherEvent($scheduleRequestId)
    {
        //this is used in the blade to customize the date based on browser
        $agent = new Agent();

        //mitcks 2020-04-21 this $currentUrl is used in resources/views/courseInstance/scheduleRequest/partial-create.blade.php
        // in the JavaScript for the time slider when a schedule request is being duplicated, it seems like an odd
        // way to do this, but it works, so leaving as is for now
        $currentUrl = url()->current();
        $currentUrl = explode('/', $currentUrl);
        $currentUrl = $currentUrl[4];

        $scheduleRequest = ScheduleRequest::find($scheduleRequestId);

        //Courses Dropdown
        //because we are duplicating, we are just returning the one course and the dropdown should be disabled
        $courses = Course::orderBy('abbrv')
                    ->where('id', $scheduleRequest->course_id)
                    ->get();

        //Locations Dropdown
        $locations = Location::get()->sortBy('building_location_label')->pluck('building_location_label', 'id')->toArray();

        //Template Dropdown
        $templates = CourseTemplate::where('course_id', $scheduleRequest->course_id)
            ->with(['courseTemplateResources', 'courseTemplateResources.resourceType', 'course'])
            ->get();

        //For row headings in the grid
        $locationsAndResources = CourseInstanceController::getLocationsAndResourcesData();

        //extract puts this is the proper JSON format for the grid
        extract($locationsAndResources);

        return view('courseInstance.scheduleRequest.create', compact('agent', 'courses', 'locations', 'templates', 'locationsAndResources', 'scheduleRequest', 'currentUrl'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        $previousUrl = url()->previous();
        $previousUrl = explode('/', $previousUrl);
        $previousUrl = $previousUrl[4];
        
        // if(!$request->has('group_request_id') and ($previousUrl == 'duplicateEvent' || strpos($previousUrl, 'create') !== false))
        if(!$request->has('group_request_id'))
        {
            $scheduleRequestGroupID = ScheduleRequestGroupId::create();
            $request['group_request_id'] = $scheduleRequestGroupID->id;
        }

        $user = Auth::user();
        $request['requested_by'] = $user->id;

        $request['start_time'] = Carbon::parse($request->eventDate . ' ' . $request->start_time);
        $request['end_time'] = Carbon::parse($request->eventDate . ' ' . $request->end_time);

        $scheduleRequest = ScheduleRequest::create($request->all());

        //send email to location schedulers to alert them of new request
        $to_email = $scheduleRequest->getSchedulersEmails();

        $scheduleEmail = ScheduleRequest::find($scheduleRequest->id);

        //if there are no schedulers, send to site help email with notice the location has no schedulers
        if (empty($to_email))
        {
            $scheduleEmail->no_schedulers = trans('alerts.frontend.scheduling.no_schedulers');
            Mail::to(Session::get('site_email'))->send(new scheduleRequestSubmit($scheduleEmail));
        }
        //else send notice to location schedulers
        else
        {
            Mail::to($to_email)->send(new scheduleRequestSubmit($scheduleEmail));
        }

        //send confirmation email to requester
        Mail::to($user)->send(new scheduleRequestConfirmation($scheduleEmail));

        return redirect()->route('my_pending_requests')
        ->withFlashSuccess($scheduleRequest->course->abbrv . ' ' . trans('alerts.frontend.scheduling.schedule_request_created'));
    }


    public function deny(DenyScheduleRequest $request)
    {

        $scheduleRequestId = $request->post('schedule_request_id');
        $site_id = Session::get('site_id');
        $scheduleRequest = ScheduleRequest::find($scheduleRequestId);
        // $url_root = "http://" . Site::find($site_id)->url_root;

        $user = User::find($scheduleRequest->requested_by);
        
        $scheduleEmail = ScheduleRequest::find($scheduleRequestId);
        $scheduleEmail->email_content = $request->post('email_content');

        // get emails from cc input on form
        if($request->post('cc_email')) {
            $cc_emails = array_filter($request->post('cc_email'));
        }
        // get emails from bcc input
        if($request->post('bcc_email')) {
            $bcc_email = array_filter($request->post('bcc_email'));
        }

        // add scheduling users email addresses to cc line
        $cc_email = array_merge($scheduleRequest->getSchedulersEmails(), $cc_emails);

        // email url link for requester
        // $scheduleEmail->email_url = $url_root . '/myScheduleRequest/denied?id='. $scheduleEmail->id;

        // send denied email 
        // TO: REQUESTER 
        // CC: (ALL USERS WITH SCHEDULING PERMISSION + CC INPUT)
        // BCC: (BCC INPUT ONLY)
        if ((empty($cc_email)) && (empty($bcc_email))) {
            Mail::to($user)->send(new scheduleRequestDeny($scheduleEmail));
        } elseif (($cc_email) && ($bcc_email)) {
            Mail::to($user)->cc($cc_email)->bcc($bcc_email)->send(new scheduleRequestDeny($scheduleEmail));
        } elseif ($cc_email) {
            Mail::to($user)->cc($cc_email)->send(new scheduleRequestDeny($scheduleEmail));
        } elseif ($bcc_email) {
            Mail::to($user)->bcc($bcc_email)->send(new scheduleRequestDeny($scheduleEmail));
        }
        
        // deny in DB
        $scheduleRequest->denied_date = \Carbon\Carbon::now();
        $scheduleRequest->denied_by = Auth::user()->id;
        $scheduleRequest->update();

        // deny comment
        $scheduleComment = ScheduleComment::create([
            'schedule_request_id' => $scheduleRequestId,
            'comment' => $scheduleEmail->email_content,
            'created_by' => Auth::user()->id,
            'last_edited_by' => Auth::user()->id,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        // success response
        $response = array('status' => 'success');

        return $response;

    }

    public function delete($id)
    {

        $user = Auth::user();

        $request = ScheduleRequest::find($id);
        $request->deleted_at = \Carbon\Carbon::now();
        $request->update();

        return redirect()->route('my_pending_requests');
    }

    // show modal
    public function show($id)
    {
        $request = ScheduleRequest::find($id);
        $template = CourseTemplate::find($request->template_id);

        return view('courseInstance.scheduleRequest.show', compact('request', 'template'));
    }

    // deny modal
    public function denyModal($id)
    {
        $request = ScheduleRequest::find($id);
        $default_text = trans('labels.scheduling.deny_email_content');
        $requestUser = User::find($request->requested_by);

        // build a last, first (email); string for requested user in the recipients display
        $requestedByUserString = $requestUser->last_name. ', ' .$requestUser->first_name. ' (' .$requestUser->email. ');';

        // replace words in the default text with request information
        if (substr_count($default_text, ':request_date:') > 0) {
            $default_text = str_replace(":request_date:", date_create($request->start_time)->format("m/d/Y"), $default_text);
        }
        if (substr_count($default_text, ':request_time:') > 0) {
            $default_text = str_replace(":request_time:", date_create($request->start_time)->format("g:ia"), $default_text);
        }

        return view('courseInstance.scheduleRequest.deny', compact('request', 'requestedByUserString', 'default_text'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
