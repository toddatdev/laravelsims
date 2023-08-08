<?php

namespace App\Http\Controllers\CourseInstance;

use App\Http\Controllers\Controller;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\QSE\QSE;
use App\Models\CourseInstance\EventUser;
use Auth;
use App\Models\CourseContent\QSE\EventQSEActivation;
use App\Models\CourseInstance\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use DateTime;

class EventQSEController extends Controller
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
     * Add Record to EventQSEActivation from Event-Dashboard Button on QSE Tab
     *
     */
    public function addToEvent(Request $request)
    {
        $user = Auth::user();

        $event_id = $request->event_id;
        $content_id = $request->content_id;

        $QSE = QSE::where('course_content_id', $content_id)->first();

        $eventQSEActivation = EventQSEActivation::create(
            ['event_id' => $event_id,
                'qse_id' => $QSE->id,
                'activation_state' => $QSE->activation_state,
                'last_edited_by' => $user->id]);

        return response()->json(
            [
                'success' => true,
                'redirect_url' => '/courseInstance/events/event-dashboard/' . $event_id . '/qse'
            ]
        );

    }


    /**
     * Activate/Deactivate QSE for Event From Event-Dashboard Button on QSE Tab via Ajax
     *
     */
    public function updateActivation(Request $request)
    {
        // logged in user
        $user = Auth::user();
        $event_id = $request->event_id;

        //record to be updated
        $eventQSEActivation = EventQSEActivation::where('qse_id', $request->qse_id)
            ->where('event_id', $request->event_id)->first();

        $action = $request->action; //turn_off or turn_on

        if ($action == "turn_on") {
            $eventQSEActivation->activation_state = 1;
        } else {
            $eventQSEActivation->activation_state = 0;
        }

        $eventQSEActivation->last_edited_by = $user->id;
        $eventQSEActivation->save();

        return response()->json(
            [
                'success' => true,
                'redirect_url' => '/courseInstance/events/event-dashboard/' . $event_id . '/qse'
            ]
        );
    }

    /**
     * Event QSE Data Table
     * builds data for Datatable to display non retired & published qse for an event
     */
    public function eventQSETableData(Request $request)
    {
        $event_id = $request->get('event_id');

        $event = Event::findOrFail($event_id);

        $qseToDisplay = $event->getQSE($request->get('retired', false));

        return DataTables::of($qseToDisplay)
            ->addColumn('expand_button', function ($qseToDisplay) {
                return '<button class="p-0 m-0 btn btn-sm  "
                        name="expand_button" id="expand_button" 
                        data-qse_id="' . $qseToDisplay->qse_id . '"
                        data-qse_type_id="' . $qseToDisplay->qse_type_id . '"
                        data-action="expand">
                        <i class="text-success fas fa-plus-circle fa-lg"></i></button>';
            })
            ->addColumn('menu_title', function ($qseToDisplay) {
                return $qseToDisplay->courseContents->menu_title .
                    '<a href="#" onclick="event.preventDefault(); window.open(\'' . route('event.qse.print-preview', $qseToDisplay->course_content_id) . '\');"><i class="text-dark ml-3 fas fa-print"></i></a>' . (
                        $qseToDisplay->qse_id ? '<a href="#" onclick="event.preventDefault(); window.open(\'' . route('qse-report', $qseToDisplay->qse_id) . '\');"><i class="text-dark ml-3 fas fa-list-alt ml-1"></i></a>' : ''
                    );
            })
            ->addColumn('report', function ($qseToDisplay) use ($event_id) {
                if ($qseToDisplay->qse_id) {
                    return '<a href="#" onclick="event.preventDefault(); window.open(\'' . route('qse-chart-report', [$event_id, $qseToDisplay->qse_id]) . '\');"><i class="text-dark ml-3 fas fa-chart-bar"></i></a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('menuTitle', function ($qseToDisplay) {
                if ($qseToDisplay->courseContents->retired_date == "") {
                    return $qseToDisplay->courseContents->menu_title;
                } else {
                    return $qseToDisplay->courseContents->menu_title . " (" . trans('labels.qse.retired') . ")";
                }
            })
            ->addColumn('current_status', function ($qseToDisplay) {
                if ($qseToDisplay->courseContents->retired_date !== null) {
                    //is retired
                    return "<i class='fas fa-eye-slash'></i> " . trans('labels.qse.retired');
                } else {
                    if ($qseToDisplay->activation_state == 0) {
                        if ($qseToDisplay->qse_id == null) {
                            //not available - needs added to event
                            return "<i class='fas fa-eye-slash'></i> " . trans('labels.qse.needs_added');
                        } else {
                            //not available - turned off
                            return "<i class='fas fa-eye-slash'></i> " . trans('labels.qse.turned_off');
                        }
                    } else //activation state is on
                    {
                        //check to see if manual or automatic
                        if ($qseToDisplay->activation_type_id == 2) //automatic
                        {
                            $event = Event::findOrFail($qseToDisplay->event_id);
                            // this is used to display the timezone abbreviation
                            $eventTZ = Carbon::now()->timezone($event->initialMeetingRoom->location->building->timezone)->timezoneAbbreviatedName;
                            // this is used to get now in the building timezone for comparison below
                            $nowBuildingTZ = Carbon::now()->timezone($event->initialMeetingRoom->location->building->timezone);

                            // calculate if available based on time
                            if ($qseToDisplay->automatic_activation_time == "S") // calculate off start time
                            {
                                $whenAvailable = Carbon::parse($event->start_time);
                            } else // calculate off end time
                            {
                                $whenAvailable = Carbon::parse($event->end_time);
                            }

                            // offset by minutes
                            // Note: I could not get Carbon greaterThan function to work correctly here with times, so casting to PHP DateTime instead
                            $whenAvailable = new DateTime($whenAvailable->addMinutes($qseToDisplay->minutes));
                            $nowBuildingTZ = new DateTime($nowBuildingTZ);

                            if ($nowBuildingTZ >= $whenAvailable) {
                                // is available now
                                return "<i class='fas fa-eye'></i> " . trans('labels.qse.available_as_of') . "  " . $whenAvailable->format('m/d/y g:i A') . ' ' . $eventTZ;
                            } else {
                                // will be available in future
                                return "<i class='fas fa-eye-slash'></i> " . trans('labels.qse.will_be_available') . "  " . $whenAvailable->format('m/d/y g:i A') . ' ' . $eventTZ;
                            }
                        } else // manual and turned on
                        {
                            return "<i class='fas fa-eye'></i> " . trans('labels.qse.available');
                        }
                    }
                }
            })
            ->addColumn('activation_type', function ($qseToDisplay) {
                if ($qseToDisplay->activation_type_id == 2) {
                    if (abs($qseToDisplay->minutes) < 60) //minutes
                    {
                        if (abs($qseToDisplay->minutes) == 1) {
                            $description = abs($qseToDisplay->minutes) . " " . trans('labels.qse.minute');
                        } else {
                            $description = abs($qseToDisplay->minutes) . " " . trans('labels.qse.minutes');
                        }
                    } elseif (abs($qseToDisplay->minutes) < 1440) //hours
                    {
                        if (abs($qseToDisplay->minutes) / 60 == 1) {
                            $description = abs($qseToDisplay->minutes) / 60 . " " . trans('labels.qse.hour');
                        } else {
                            $description = abs($qseToDisplay->minutes) / 60 . " " . trans('labels.qse.hours');
                        }
                    } else //days
                    {
                        if (abs($qseToDisplay->minutes) / 1440 == 1) {
                            $description = abs($qseToDisplay->minutes) / 1440 . " " . trans('labels.qse.day');
                        } else {
                            $description = abs($qseToDisplay->minutes) / 1440 . " " . trans('labels.qse.days');
                        }
                    }

                    if ($qseToDisplay->minutes < 0) //before or after
                    {
                        $description .= " " . trans('labels.qse.before');
                    } else {
                        $description .= " " . trans('labels.qse.after');
                    }

                    if ($qseToDisplay->automatic_activation_time == "S") {
                        $description .= " " . trans('labels.qse.start_time');
                    } else {
                        $description .= " " . trans('labels.qse.end_time');
                    }

                    return $description;
                } else {
                    return trans($qseToDisplay->activationType->description);
                }
            })
            ->addColumn('change_availability', function ($qseToDisplay) {
                if ($qseToDisplay->courseContents->retired_date == "") {
                    if ($qseToDisplay->activation_state == 1) {
                        return '<button class="p-0 m-0 btn btn-sm btn-link shadow-none simptip-position-top simptip-smooth"
                        name="activate_qse" id="activate_qse" 
                        data-tooltip="' . trans('labels.qse.deactivate') . '"
                        data-qse_id="' . $qseToDisplay->qse_id . '"
                        data-action="turn_off">
                        <i class="text-success fad fa-toggle-on fa-2x"></i></button>';

                    } else {
                        if ($qseToDisplay->qse_id == null) {
                            //in this case there is no qse_id due to left join in $qseToDisplay
                            //so use the course_content_id then lookup qse_id based on that later
                            return '<button class="btn btn-sm btn-success" name="add_qse" id="add_qse" 
                            data-content_id="' . $qseToDisplay->course_content_id . '">Add to Event</button>';
                        } else {
                            return '<button class="p-0 m-0 btn btn-sm btn-link shadow-none simptip-position-top simptip-smooth"
                            name="activate_qse" id="activate_qse" 
                            data-tooltip="' . trans('labels.qse.activate') . '"
                            data-qse_id="' . $qseToDisplay->qse_id . '"
                            data-action="turn_on">
                            <i class="text-secondary fad fa-toggle-off fa-2x"></i></button>';
                        }
                    }
                } else {
                    return null;
                }

            })
            ->addColumn('completion', function ($qseToDisplay) use ($event_id){
                $eventUserIds = EventUser::where('event_id', $event_id)->pluck('id');
                $eventUserQSEs = \App\Models\CourseContent\QSE\EventUserQSE::whereHas('qse', function ($query) use ($qseToDisplay) {
                        $query->where('course_content_id', $qseToDisplay->course_content_id);
                    })
                    ->orderBy('created_at', 'DESC')
                    ->whereIn('event_user_id', $eventUserIds)
                    ->groupBy('event_user_id')
                    ->get();

                $totalComplete = 0;

                foreach ($eventUserQSEs as $euq) {
                    if ($euq->complete) {
                        $totalComplete++;
                    }
                }

                $total = count($eventUserIds);

                if ($total == 0) {
                    return "0/0 (0%)";
                }
                $percent = round(($totalComplete / $total) * 100);

                return "{$totalComplete}/{$total} ({$percent}%)";
            })
            ->addColumn('avg_score', function ($qseToDisplay) use ($event_id){
                $eventUserIds = EventUser::where('event_id', $event_id)->pluck('id');
                $eventUserQSEs = \App\Models\CourseContent\QSE\EventUserQSE::whereHas('qse', function ($query) use ($qseToDisplay) {
                    $query->where('course_content_id', $qseToDisplay->course_content_id)->where('qse_type_id', 1);
                })
                    ->complete()
                    ->whereIn('event_user_id', $eventUserIds)
                    ->groupBy('event_user_id')
                    ->orderBy('created_at', 'DESC')
                    ->get();

                $percent = 0;
;
                foreach ($eventUserQSEs as $euq) {
                    if ($euq->result['total'] > 0) {
                        $percent += ($euq->result['correct'] / $euq->result['total']) * 100;
                    }
                }

                if ($qseToDisplay->qse_type_id == 1) {
                    $percent = (int) (count($eventUserIds) > 0 ? $percent / count($eventUserIds) : 0);
                    return "{$percent}%";
                } else {
                    return "NA";
                }

            })
            ->rawColumns(['expand_button', 'current_status', 'change_availability', 'menu_title', 'report'])
            ->make(true);
    }

    public function eventQSEPrintPreview(CourseContent $courseContent)
    {
        $qse = QSE::where('course_content_id', $courseContent->id)
            ->with([
                'qseQuestions' => function ($q) {
                    $q->orderBy('display_order');
                }
            ])
            ->first();
        //for role select list

        return view('courseContent.qse.print-preview', compact('courseContent', 'qse'));
    }
}
