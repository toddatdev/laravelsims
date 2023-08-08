<?php

namespace App\Http\Controllers\Schedule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Site\Site;
use App\Models\Building\Building;
use App\Models\Course\Course;
use App\Models\Resource\Resource;
use App\Models\Location\Location;
use App\Http\Requests\Schedule\AddClassRequest;
use Session;
use App\Models\CourseInstance\CourseInstance;
use App\Models\CourseInstance\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Resource\ResourceType;
use DateTime;

class AddClassController extends Controller {

    /**
     * Display a listing of the course selection.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id=null, $event=null) {


        if($event == 'success') {
         $courseInstance = CourseInstance::where(['id' => $id])->first();
         $event = Event::where(['course_instance_id' => $id])->first();
         $course = Course::where(['id' => $courseInstance->course_id])->first();

        $htmlLink = link_to_route('schedule.create', 'Duplicate', ['id' => $id], ['class' => 'btn btn-parimary']);
        $htmlLink .=link_to_route('schedule.create', 'Blank', [], ['class' => 'btn btn-parimary']);
        $htmlLink .= link_to_route('frontend.user.dashboard', 'Dashboard',  [], ['class' => 'btn btn-parimary']);

         $message =  'The class '.$course->abbrv.' has been added on '.$event->start_time.'-'.$event->end_time.' '.$htmlLink;
          return redirect()->route('schedule.create')->withFlashSuccess($message);
        }

        // Put the site info for the banner, etc. -jl 2018-04-16 15:28
        $site_id = Session::get('site_id');

        $courses = Course::where('site_id', $site_id)->where('retire_date', NULL)->get(['abbrv', 'id', 'virtual']);

       $locations = DB::table('locations')
         ->select('resources.id as resource_id', 'buildings.abbrv as building_abbrv', 'resources.abbrv as resource_abbrv', 'locations.abbrv as location_abbrv')
        ->join('resources','resources.location_id','=','locations.id')
        ->join('buildings','buildings.id','=','locations.building_id')
        ->where(['locations.site_id' => $site_id, 'resources.retire_date' => NULL, 'resources.resource_type_id' => 1])
           ->orderBy('buildings.abbrv','ASC')
           ->orderBy('locations.abbrv','ASC')
           ->orderBy('resources.abbrv','ASC')
        ->get();

        $resources = [];
       foreach($locations as $location) {
          $resources[$location->resource_id] = $location->building_abbrv . " " . $location->location_abbrv. " " . $location->resource_abbrv;
       }

        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
       // $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);

       $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);

        $daypilotData = $this->getLocationsAndResources();

        extract($daypilotData);

        $courseId = Course::where('site_id',$site_id)->select('id','abbrv')->get();
        $courseIDsWithAbbrv =  $courseId->pluck('abbrv','id');

       // dd($courseIDsWithAbbrv);
        $course_instance = CourseInstance::whereIn('course_id',array_pluck($courseId,'id'))->select('id')->get();
        $events = Event::whereIn('course_instance_id',array_pluck($course_instance,'id'))->select('id','start_time','end_time','initial_meeting_room','color','course_instance_id')->get();

        $existingEvents = [];
            foreach($events as $event) {
                $existingEvents[] = [
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'id' => (string)$event->id,
                    'resource' => (string)$event->initial_meeting_room,
                    'color' => (string)$event->color,
                    'text' => $event->CourseInstance->Course->abbrv,
                    'barColor'=> $event->color,
                    'moveDisabled' => true,
                    'resizeDisabled' => true,
                    'bubbleHtml' => "<strong>" . $event->CourseInstance->Course->name . "</strong>" .
                    "<br><br><strong>" . trans('labels.event.public_notes'). ":</strong><br>" . $event->CourseInstance->public_comments .
                    "<br><br><strong>" . trans('labels.event.internal_notes') . ":</strong><br>" . $event->CourseInstance->internal_comments,
                ];
            }

          $existingEvents = json_encode($existingEvents);

      $courseInstanceFromDB = $eventFromDB = $endTimeFromDB = $startTimeFromDB = null;

       if($id) {
        $courseInstanceFromDB = CourseInstance::where(['id' => $id])->first();
        $eventFromDB = Event::where(['course_instance_id' => $id])->first();
       // $startTimeFromDB = $eventFromDB ? new DateTime($eventFromDB->start_time) : null;
      //  $endTimeFromDB = $eventFromDB ? new DateTime($eventFromDB->end_time) : null;
       }

         $class =   ['course_id' => old('course_id') ?? optional($courseInstanceFromDB)->course_id,
            'class_size' => old('class_size') ?? optional($courseInstanceFromDB)->class_size ??  null,
            'public_comments' => old('public_comments') ?? $courseInstanceFromDB->public_comments ??  null,
            'internal_comments' => old('internal_comments') ?? optional($courseInstanceFromDB)->internal_comments ?? null,
            'event_date' => old('event_date') ?? Carbon::now(),
            'setup_time' => old('setup_time') ?? optional($eventFromDB)->setup_time ?? 0,
            'instructorReportState' =>old('instructorReportState') ?? ((optional($eventFromDB)->fac_report > 0) ? 'After' : 'Before') ?? 'Before',
            'html_color' => old('html_color')?? optional($eventFromDB)->color ?? null,
            'resource_id' => old('resource_id') ?? optional($eventFromDB)->initial_meeting_room ??null,
            'instructorLeaveState' => old('instructorLeaveState') ?? ((optional($eventFromDB)->fac_leave > 0) ? 'After' : 'Before') ?? 'Before',
            'start_time' => old('start_time')  ?? $defaultBusinessBeginHour. ':00:00',
            'end_time' => old('end_time') ?? $defaultBusinessEndHour. ':00:00',
            'teardown_time' => old('teardown_time') ?? optional($eventFromDB)->teardown_time ?? 0,
            'schedule_addClass_InstructorLeave' => old('schedule_addClass_InstructorLeave') ?? abs(optional($eventFromDB)->fac_leave) ?? 0,
            'schedule_addClass_InstructorReport' => old('schedule_addClass_InstructorReport') ?? abs(optional($eventFromDB)->fac_report) ?? 0];

        return view('schedule.create', compact('site', 'class', 'defaultBusinessBeginHour', 'defaultBusinessEndHour' ,'businessEndHour', 'courses', 'resources','locationsAndResources', 'businessBeginHour', 'eventText','existingEvents'));
    }


    protected function getLocationsAndResources()
    {

        $businessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);

        $buildings = Building::orderBy('display_order')->get(['abbrv', 'id']);
        $allResourceLocations = Resource::groupBy('location_id')->get(['location_id']);

        //For grid tree
        $locationsAndResources = "[";

        foreach ($buildings as $building) {

            //only display locations if there are resources
            $locations = Location::wherein('id', $allResourceLocations)->where('building_id', "=", $building->id)->orderBy('display_order')->get(['abbrv', 'id']);

            foreach ($locations as $location) {

                $locationsAndResources .= "{\"id\":\"Location-" . $location->id . "\",\"name\":\"" . $building->abbrv . " " . $location->abbrv . "\", expanded: true, \"children\":[";

                //only display resource types if there are resources
                $allResourcesTypes = Resource::where('location_id', '=', $location->id)->groupBy('resource_type_id')->get(['resource_type_id']);
                $resourceTypes = ResourceType::wherein('id', $allResourcesTypes)->orderBy('display_order')->get(['abbrv', 'id']);

                foreach ($resourceTypes as $resourceType) {

                    $resources = Resource::where('location_id', '=', $location->id)->where('resource_type_id', '=', $resourceType->id)->get(['abbrv', 'id']);

                    $locationsAndResources .= "{\"id\":\"Type-" . $resourceType->id . "\",\"name\":\"" . $resourceType->abbrv . "\", expanded: true, \"children\":[";

                    foreach ($resources as $resource) {
                        $locationsAndResources .= "{\"id\":\"" . $resource->id . "\",\"name\":\"" . $resource->abbrv . "\"},";
                    }

                    $locationsAndResources .= "]},";

                }

                $locationsAndResources .= "]},";

            }

        }

        $locationsAndResources .= "]";

        //For existing events in grid

        $events = Event::whereDate('start_time', '<=', '2018-10-01')->with('eventResources')->get();

        //$eventResources = EventResource::get();
        $eventText = "[";
        foreach ($events as $event) {

            foreach ($event->eventResources as $eventResource) {
                $eventText .= $eventResource->getResourceEventGrid();
            }

        }
        $eventText .= "]";


       return [ 'locationsAndResources' => $locationsAndResources,
           'businessBeginHour' =>$businessBeginHour,
           'businessEndHour' =>$businessEndHour,
           'eventText' =>$eventText];

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created class in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddClassRequest $request) {

        $input = $request->all();

        $user_id = $request->user()->id;

        $courseInstance = new CourseInstance;
        $courseInstance->course_id = $input['course_id'];
        $courseInstance->public_comments = $input['public_comments'];
        $courseInstance->internal_comments = $input['internal_comments'];
        $courseInstance->class_size = $input['class_size'];
        $courseInstance->created_by = $user_id;
        $courseInstance->last_edited_by = $user_id;

        $event = new Event;

        $start_time = $input['event_date'] . ' ' . $input['start_time'];
        $start_time = Carbon::parse($start_time);

        $end_time = $input['event_date'] . ' ' . $input['end_time'];
        $end_time = Carbon::parse($end_time);

        $event->start_time = $start_time->format('Y-m-d H:i:s');
        $event->end_time = $end_time->format('Y-m-d H:i:s');
        $event->setup_time = $input['setup_time'];
        $event->teardown_time = $input['teardown_time'];
        $event->fac_report = $input['instructorReportState'] == 'After' ? $input['schedule_addClass_InstructorReport'] : '-' . $input['schedule_addClass_InstructorReport'];
        $event->fac_leave = $input['instructorLeaveState'] == 'After' ? $input['schedule_addClass_InstructorLeave'] : '-' . $input['schedule_addClass_InstructorLeave'];
        $event->initial_meeting_room = $input['resource_id'];
        $event->color = $input['html_color'];
        $event->created_by = $user_id;
        $event->last_edited_by = $user_id;

        DB::transaction(function() use ($courseInstance, $event) {
            $courseInstance->save();
            $event->course_instance_id = $courseInstance->id;
            $event->save();
        });

      $message = '<div>Would you like to add another date to this class? '
                . '<a class="btn btn-primary" href="'.route('schedule.addClass',['id' => $courseInstance->id]).'">Yes</a> or '
                . '<a class="btn btn-primary" href="'.route('schedule.addClass',['id' => $courseInstance->id,'event' => 'success']).'">No</a>.</div>';

     return redirect()->route('schedule.addClass')->withFlashSuccess($message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
