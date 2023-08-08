<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CourseInstance\ScheduleRequestController;
use App\Models\Access\Role\Role;
use App\Models\Resource\Resource;
use Illuminate\Http\Request;
use App\Models\Site\Site;
use App\Models\Course\Course;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventUser;
use App\Models\Location\Location;
use App\Models\Building\Building;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon as Carbon;
use Session;
use Auth;
use function foo\func;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
    	// Put the site info for the banner, etc. -jl 2018-04-16 15:28 
        $site = Site::find(SESSION::get('site_id'));

        $user = Auth::user();

        //This is used for the My Events Section
        $events = Event::whereDate('start_time', Carbon::today())
            ->orderBy('start_time')
            ->with('eventResources')
            ->with('courseInstance')
            ->get();

        // Object that will be used to pass info for unresolved button in scheduling menu
        $unresolvedObj = (object) [
            'count'     => 0,
            'startDate' => null,
            'endDate'   => null,
        ];

        //get unresolved events this person can comment on
        $unresolvedEvents = Event::where('resolved', 0);
        $unresolvedObj->count     = $unresolvedEvents->count();
        $unresolvedObj->startDate = date_create($unresolvedEvents->min('start_time'))->format('Y-m-d');
        $unresolvedObj->endDate   = date_create($unresolvedEvents->max('start_time'))->format('Y-m-d');


        //mitcks: use this is you decide to implement by scheduler's location (probably not ideally how this should be done)
//        $unresolvedEvents = Event::where('resolved', 0)->get();
//
//        if($unresolvedEvents)
//        {
//            foreach($unresolvedEvents as $unresolvedEvent){
//
//                if($unresolvedEvent->IsSchedulerForLocation())
//                {
//                    $unresolvedObj->count++;
//                    if($unresolvedObj->startDate > $unresolvedEvent->startDate)
//                    {
//                        $unresolvedObj->startDate = date_create($unresolvedEvents->min('start_time'))->format('Y-m-d');
//                    }
//                    if($unresolvedObj->endDate > $unresolvedEvent->endDate)
//                    {
//                        $unresolvedObj->endDate   = date_create($unresolvedEvents->max('start_time'))->format('Y-m-d');
//                    }
//
//                }
//            }
//        }

        $scheduleRequestController = new ScheduleRequestController;
        $pendingRequestCount = $scheduleRequestController->pendingRequestCount();
        $userPendingRequestCount = $scheduleRequestController->userPendingRequestCount();

        //get event_user records for the logged in user where status is waitlist or pending payment
        // (used for waitlist alert inside My Events section)
        $enrollmentRequests = EventUser::where('user_id', $user->id)
                                ->whereIn('status_id', [2,3,5])
                                ->get();

        //get count of pending requests for badge icon (for approver)
        $countEnrollmentRequests = EventUser::MyWaitlistRequests()->count();

        return view('frontend.user.dashboard', compact('site', 'events', 'pendingRequestCount',
                                                        'userPendingRequestCount', 'unresolvedObj','enrollmentRequests', 'countEnrollmentRequests'));
    }

    public function myClassesTableData(Request $request)
    {

        $user = Auth::user();

        // date always starts with today unless we hit the date_type = 0 - range (see below)
        $start_date = Carbon::today()->timezone(session('timezone'))->toDateString();

        // set the date type =
        $date_type = $request->get('date_type');

        if($date_type == 14) {
            // 14 days
            $end_date = Carbon::today()->timezone(session('timezone'))->addDays(14)->toDateString();

        } elseif ($date_type == 30) {
            // 30 days
            $end_date = Carbon::today()->timezone(session('timezone'))->addDays(30)->toDateString();

        } elseif ($date_type == 1) {
            // 3 months
            $end_date = Carbon::today()->timezone(session('timezone'))->addMonths(3)->toDateString();

        } elseif ($date_type == 0) {
            // date range
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

        } else {
            // default 7
            $end_date = Carbon::today()->timezone(session('timezone'))->addDays(7)->toDateString();
        }

        $events = Event::with('eventUsers')
            ->whereHas('eventUsers', function($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status_id', 1);
            })
            ->whereDate('start_time', '>=', $start_date)
            ->whereDate('start_time', '<=', $end_date)
            ->orderBy('start_time', 'ASC')
            ->distinct()->get();

         return DataTables::of($events)
         ->addColumn('date', function($events) {
            return $events->DisplayStartDateShort;
         })
         ->addColumn('time', function($events) {
             return $events->DisplayStartEndTimes;
         })
         ->addColumn('course_name', function($events) {
            return $events->courseInstance->course->name;
         })
         ->addColumn('courses.abbrv', function($events) {
            return $events->CourseAbbrvEventAbbrv;
         })
         ->addColumn('building_location_room', function($events) {
             return $events->initialMeetingRoom->location->building->abbrv. ' - ' .$events->initialMeetingRoom->location->abbrv . ' ' . $events->InitialMeetingRoom->abbrv;
         })
         ->addColumn('event_rooms', function($events) {
                 return $events->eventRooms();
         })
         ->addColumn('start_time_only', function($events) {
             return date_create($events->start_time)->format('H:i:s');
         })
        ->addColumn('end_time_only', function($events) {
            return date_create($events->end_time)->format('H:i:s');
        })
         ->addColumn('actions', function($events) {
            return $events->getActionButtonsAttribute(); //app/model/event
         })
         ->rawColumns(['actions'])
         ->make(true);
    }
}
