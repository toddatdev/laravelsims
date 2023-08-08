<?php


namespace App\Http\Controllers\Site;


use App\Models\Access\Role\Role;
use App\Models\Building\Building;
use App\Models\Course\Course;
use App\Models\Location\Location;
use Illuminate\Http\Request;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventUser;
use App\Models\Resource\Resource;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function Clue\StreamFilter\fun;

class SiteReportsController extends \App\Http\Controllers\Controller
{
    public function eventActivity(Request $request)
    {

        if ($request->ajax()) {

            $data = Event::select('*');

            if ($request->get('from', '') != '' && $request->get('to', '') != '') {
                $data = $data->whereDate('start_time', '>=', Carbon::parse($request->from)->format('Y-m-d'))
                    ->whereDate('start_time', '<=', Carbon::parse($request->to)->format('Y-m-d'));
            }

            if ($request->get('course_id', 'all') != 'all') {
                $data = $data->whereHas('CourseInstance', function ($q) use ($request) {
                    $q->where('course_id', $request->course_id);
                });
            }

            if ($request->get('building_id', 'all') != 'all') {
                $data = $data->whereHas('InitialMeetingRoom', function ($q) use ($request) {
                    $q->whereHas('location', function ($q) use ($request) {
                        $q->where('building_id', $request->building_id);
                    });
                });
            }

            if ($request->get('location_id', 'all') != 'all') {
                $data = $data->whereHas('InitialMeetingRoom', function ($q) use ($request) {
                    $q->where('location_id', $request->location_id);
                });
            }

            $data = $data->orderBy('start_time')->get();

            return Datatables::of($data)
                ->addColumn('event_id', function ($data) {
                    return $data->id;
                })
                ->addColumn('notes', function ($data) {
                    return $data->public_comments;
                })
                ->addColumn('internal_notes', function ($data) {
                    return $data->internal_comments;
                })
                ->addColumn('class_limit', function ($data) {
                    return $data->class_size;
                })
                ->addColumn('encounter_hours', function ($data) {
                    $start = Carbon::parse($data->start_time);
                    $end = Carbon::parse($data->end_time);
                    $classHours = $start->diffInHours($end);

                    return $data->numLearnersEnrolled() * $classHours;
                })
                ->addColumn('event_hours', function ($data) {
                    $start = Carbon::parse($data->start_time);
                    $end = Carbon::parse($data->end_time);
                    return $start->diffInHours($end);
                })
                ->addColumn('enrolled_user_count', function ($data) {

                    $roles = Role::with(['eventUsers' => function ($q) use ($data) {
                        $q->where('event_id', $data->id);
                    }])->where('site_id', \Session::get('site_id'))
                        ->with('permissions')
                        ->whereExists(function ($query){
                            $query->select(DB::raw(1))
                                ->from('permission_role')
                                ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                                ->whereRaw('permission_role.role_id = roles.id')
                                ->where('permission_type_id', 3);
                        })
                        ->where('all', '!=', 1)
                        ->orderBy('name')->get();

                    $eUC = [];

                    foreach ($roles as $role) {
                        $eUC[] = "{$role->eventUsers->count()}";
                    }

                    return implode(", ", $eUC);
                })
                ->addColumn('course_abbreviation', function ($data) {
                    $abbrv = '';
                    $courseInstance = $data->CourseInstance;
                    if ($courseInstance) {
                        $abbrv = $courseInstance->Course->abbrv;
                    }
                    return $abbrv;
                })
                ->addColumn('location', function ($data) {
                    $abbrv = '';
                    $initialMeetingRoom = $data->initialMeetingRoom;
                    if ($initialMeetingRoom) {
                        $abbrv = $initialMeetingRoom->location->abbrv;
                    }
                    return $abbrv;
                })
                ->addColumn('building', function ($data) {
                    $abbrv = '';
                    $initialMeetingRoom = $data->initialMeetingRoom;
                    if ($initialMeetingRoom) {
                        $location = $initialMeetingRoom->location;
                        if ($location) {
                            $building = $location->building;
                            if ($building) {
                                $abbrv = $building->abbrv;
                            }
                        }
                    }
                    return $abbrv;
                })
                ->addColumn('room_hours', function ($data) {
                    $resources = Resource::with(['eventResources' => function ($query) use ($data) {
                        $query->where('event_id', $data->id);
                    }])
                        ->where('resource_type_id', 1)->get();

                    $roomsHours = [];

                    foreach ($resources as $resource) {
                        foreach ($resource->eventResources as $eventResource) {
                            $startTime = Carbon::parse($eventResource->start_time);
                            $endTime = Carbon::parse($eventResource->end_time);
                            $totalStart = $startTime->subMinutes($eventResource->setup_time);
                            $totalEnd = $endTime->addMinutes($eventResource->teardown_time);
                            $roomsHours[] = $totalEnd->diff($totalStart)->format('%H:%I');
                        }
                    }

                    $totalHours = 0;
                    $totalMinutes = 0;
                    foreach ($roomsHours as $roomsHour) {
                        $hm = explode(':', $roomsHour);
                        $totalHours += intval($hm[0]);
                        $totalMinutes += intval($hm[1]);
                    }

                    $totalHours += (int)($totalMinutes / 60);
                    $totalMinutes = $totalMinutes % 60;

                    $totalHours = $totalHours < 10 ? '0' . $totalHours : $totalHours;
                    $totalMinutes = $totalMinutes < 10 ? '0' . $totalMinutes : $totalMinutes;

                    return "$totalHours:$totalMinutes";
                })
                ->addColumn('class_date', function ($data) {
                    return Carbon::parse($data->start_time)->format('m-d-Y');
                })
                ->rawColumns(['internal_notes', 'enrolled_user_count'])
                ->make(true);;
        }

        $buildings = Building::pluck('abbrv', 'id')->toArray();
        $locations = Location::pluck('abbrv', 'id')->toArray();
        $courses = Course::pluck('abbrv', 'id')->where(['retire_date' => NULL])->toArray();

        return view('sites.reports.event-activity', compact('buildings', 'locations', 'courses'));
    }

    public function eventRoster(Request $request)
    {

        if ($request->ajax()) {

            $data = Event::select('*');

            if ($request->get('from', '') != '' && $request->get('to', '') != '') {
                $data = $data->whereDate('start_time', '>=', Carbon::parse($request->from)->format('Y-m-d'))
                    ->whereDate('start_time', '<=', Carbon::parse($request->to)->format('Y-m-d'));
            }

            if ($request->get('course_id', 'all') != 'all') {
                $data = $data->whereHas('CourseInstance', function ($q) use ($request) {
                    $q->where('course_id', $request->course_id);
                });
            }

            if ($request->get('building_id', 'all') != 'all') {
                $data = $data->whereHas('InitialMeetingRoom', function ($q) use ($request) {
                    $q->whereHas('location', function ($q) use ($request) {
                        $q->where('building_id', $request->building_id);
                    });
                });
            }

            if ($request->get('location_id', 'all') != 'all') {
                $data = $data->whereHas('InitialMeetingRoom', function ($q) use ($request) {
                    $q->where('location_id', $request->location_id);
                });
            }

            $data = $data->whereHas('eventUsers', function($q) {
                    $q->whereHas('role', function ($q) {
                        $q->where('site_id', \Session::get('site_id'));
                    });
                })->with('eventUsers', 'eventUsers.role', 'eventUsers.user')
                ->orderBy('start_time')->get();

            return Datatables::of($data)
                ->addColumn('event_id', function ($data) {
                    return $data->id;
                })
                ->addColumn('name', function ($data) {
                    $user = $data->eventUsers->first()->user;

                    return implode(', ', array_filter([$user->last_name, $user->first_name, $user->middle_name]));
                })
                ->addColumn('class_role', function ($data) {

                    return $data->eventUsers->first()->role->name;
                })
                ->addColumn('email', function ($data) {
                    return $data->eventUsers->first()->user->email;
                })
                ->addColumn('encounter_hours', function ($data) {
                    $start = Carbon::parse($data->start_time);
                    $end = Carbon::parse($data->end_time);

                    return $end->diffInMinutes($start) / 60;
                })
                ->addColumn('course', function ($data) {
                    $abbrv = '';
                    $courseInstance = $data->CourseInstance;
                    if ($courseInstance) {
                        $abbrv = $courseInstance->Course->abbrv;
                    }
                    return $abbrv;
                })

                ->addColumn('class_date', function ($data) {
                    return Carbon::parse($data->start_time)->format('Y-m-d H:i');
                })
                ->rawColumns(['internal_notes', 'enrolled_user_count'])
                ->make(true);;
        }

        $buildings = Building::pluck('abbrv', 'id')->toArray();
        $locations = Location::pluck('abbrv', 'id')->toArray();
        $courses = Course::pluck('abbrv', 'id')->where(['retire_date' => NULL])->toArray();

        return view('sites.reports.event-roster', compact('buildings', 'locations', 'courses'));
    }

    /**
     * @param $type
     * @param $query
     * @return mixed
     */
    public function search($type, $query)
    {
        $data = [];
        if ($type == 'building') {
            $data = app(Building::class);
        } else if ($type == 'location') {
            $data = app(Location::class);
        } else if ($type == 'course') {
            $data = app(Course::class);
        }

        return response()->json($data->where('name', 'LIKE', "%$query%")->select('id', 'name')->get(), 200);
    }

    public function getCourses($building, $location = null)
    {
        $courses = Course::orderBy('abbrv')
            ->whereHas('courseInstances', function ($q) use ($building, $location) {
                $q->whereHas('events', function ($q) use ($building, $location) {
                    $q->whereHas('eventResources', function ($q) use ($building, $location) {
                        $q->whereHas('Resources', function ($q) use ($building, $location) {
                            $q->whereHas('location', function ($q) use ($building, $location) {
                                $q->where('building_id', $building);
                                if ($location != null) {
                                    $q->where('id', $location);
                                }
                            });
                        });
                    });
                });
            })
            ->pluck('abbrv', 'id');
        return response()->json($courses, 200);
    }
}