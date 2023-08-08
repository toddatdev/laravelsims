<?php

namespace App\Http\Controllers\CourseInstance;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\QSE\QSE;
use App\Models\CourseInstance\EventUser;
use App\Models\CourseContent\QSE\EventUserQSE;
use Auth;
use App\Models\CourseContent\QSE\EventQSEActivation;
use App\Models\CourseInstance\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Illuminate\Support\Facades\DB;


class EventUserQSEController extends Controller
{
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Event User QSE Data Table
     * builds data for Datatable to display non retired & published qse for an event
     */
    public function eventUserQSETableData(Request $request)
    {
        $event_id = $request->get('event_id');
        $qse_id = $request->get('qse_id');

        //figure out if this is a learner qse
        $qse = QSE::findOrFail($qse_id);
        $isLearnerQSE = 0;
        if ($qse->courseContents->viewer_type_id == 2) //it is a learner QSE
        {
            $isLearnerQSE = 1;
        }


        //get either Learners or Non-learners Depending on where QSE is placed in content
        $eventUsers = DB::table('event_user')
            ->join('roles', 'roles.id', '=', 'event_user.role_id')
            ->where('event_user.event_id', $event_id)
           ->where('event_user.status_id', '=', 1)
            ->select('event_user.id as id', 'event_user.user_id as user_id',
                DB::raw("(select count(*) from event_user_qse 
                        where event_user_qse.course_qse_id = $qse_id
                        and event_user_qse.event_user_id = event_user.id 
                        and complete = 1
                        group by event_user_qse.course_qse_id) as count"),
                DB::raw("(select allow_multiple_submits from qse 
                        where qse.id = $qse_id) as allow_multiple_submits")
            )
            ->get();

        $dataTables = DataTables::of($eventUsers)
            ->addColumn('name', function ($eventUser) {
                return User::find($eventUser->user_id)->FullName;
            })
            ->addColumn('status', function ($eventUser) {
                // did they complete the quiz? this isn't right - not looking for specific quiz
                if ($eventUser->count >= 1) {
                    if ($eventUser->allow_multiple_submits == 1) {
                        return "<i class='text-success fad fa-check fa-lg'></i> (" . $eventUser->count . " attempts)";
                    } else {
                        return "<i class='text-success fad fa-check fa-lg'></i>";
                    }
                } else {
                    return "<i class='text-danger fad fa-times fa-lg'></i>";
                }
            })
            ->addColumn('firstName', function ($eventUser) {
                return User::find($eventUser->user_id)->first_name;
            })
            ->addColumn('lastName', function ($eventUser) {
                return User::find($eventUser->user_id)->last_name;
            })
            ->rawColumns(['status', 'report']);

        if ($qse->qse_type_id === 1) {
            $dataTables = $dataTables
                ->addColumn('report', function ($eventUser) use ($qse_id) {
                    $eventUserQSE = \App\Models\CourseContent\QSE\EventUserQSE::where('event_user_id', $eventUser->id)
                        ->where('course_qse_id', $qse_id)
                        ->complete()
                        ->orderBy('created_at', 'DESC')
                        ->first();

                    return $eventUserQSE ? "
                        <a href=" . route('qse-results-report', $eventUserQSE->id) . "
                           class='btn btn-link btn-sm font-weight-bold py-0'
                           data-toggle='modal'
                           data-target='#resultReport{$eventUserQSE->id}Modal'>
                           " . trans('labels.qse.results_report') . "
                        </a>
                    " : '';

                })->addColumn('grade', function ($eventUser) use ($qse_id) {
                    $eventUserQSE = \App\Models\CourseContent\QSE\EventUserQSE::where('event_user_id', $eventUser->id)
                        ->whereHas('qse', function ($query) use ($qse_id) {
                            $query->where('id', $qse_id)->where('qse_type_id', 1);
                        })
                        ->complete()
                        ->orderBy('created_at', 'DESC')
                        ->first();

                    if ($eventUserQSE) {
                        $percent = round(($eventUserQSE->result['correct'] / $eventUserQSE->result['total']) * 100);

                        return "{$eventUserQSE->result['correct']}/{$eventUserQSE->result['total']} ({$percent}%)";
                    }

                    return "-";
                });
        }

        return $dataTables->make(true);
    }

    public function isQSEComplete($eventId, $qseId)
    {
        $eventUserQSE = EventUserQSE::where('event_id', $eventId)
            ->where('qse_id', $qseId)
            ->where('complete', 1)
            ->first();
        if ($eventUserQSE) {
            return true;
        } else {
            return false;
        }
    }
}
