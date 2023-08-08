<?php

namespace App\Http\Controllers\CourseInstance;

use Illuminate\Http\Request;
use App\Models\Site\Site;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventResource;
use App\Models\Building\Building;
use App\Models\Resource\ResourceType;
use DateTime;
use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\CourseInstance\CourseInstance;
use App\Models\CourseInstance\ScheduleRequest;
use App\Models\Course\CourseTemplate;
use App\Models\Location\Location;
use App\Models\Resource\Resource;
use App\Models\Resource\ResourceCategory;
use App\Models\Resource\ResourceSubCategory;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use Carbon\Carbon;
use App\Http\Requests\CourseInstance\StoreCourseInstanceRequest;


class OldCourseInstanceController extends Controller
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
        $start_date = new DateTime();
        $end_date = new DateTime();
        $end_date->modify('+1 day');
        $start_date->modify('-1 day');

        //for Course select list
        $site_id = Session::get('site_id');

        $courses = $this->getCoursesAndResources($start_date, $end_date);
        $locationsWithResources = $this->getResourceAndLocationsList();
        $templates = CourseTemplate::with(['courseTemplateResources', 'courseTemplateResources.resourceIdentifierType', 'course'])->get();

        //for start and end time defaults
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);

        $resourceList = $this->getResourcesDPList();
        $resources = Resource::with(['category', 'subcategory', 'location' => function($q) use ($site_id) {
            $q->where('site_id', '=', $site_id);
        }])->has('location')->orderBy('abbrv', 'ASC')->get();

        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = $this->getLocationsAndResources();
        extract($locationsAndResources);
        $courseId = Course::where('site_id',$site_id)->select('id','abbrv')->get();
        $courseIDsWithAbbrv =  $courseId->pluck('abbrv','id');
        $courseInstance = 0;

        return view('courseInstance.create', compact('courses', 'resources', 'defaultBusinessBeginHour', 'defaultBusinessEndHour' ,'businessEndHour', 'courses', 'resources','locationsAndResources', 'businessBeginHour', 'defaultStartHour', 'defaultEndHour', 'locationsWithResources', 'templates', 'resourceList'));
    }
    public function getEventsAndResourcesByDate($selectedDate) {
        $start_date = new DateTime($selectedDate);
        $end_date = new DateTime($selectedDate);
        $start_date->modify('-1 day');
        $end_date->modify('+1 day');

        $site_id = Session::get('site_id');
        $courses = $this->getCoursesAndResources($start_date, $end_date);

        return response()->json($courses);
    }

    public static function getResourceAndLocationsList()
    {
        $data = Location::with(['resources','resources.category','resources.subcategory'])->orderBy('display_order')->get();
        return $data;
    }

    public static function getLocationsAndResources()
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

                $locationsAndResources .= "{\"id\":\"Location-" . $location->id . "\",\"name\":\"" . $building->abbrv . " " . $location->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";

                //only display resource types if there are resources
                $allResourcesTypes = Resource::where('location_id', '=', $location->id)->groupBy('resource_type_id')->get(['resource_type_id']);
                $resourceTypes = ResourceType::wherein('id', $allResourcesTypes)->orderBy('display_order')->get(['abbrv', 'id']);

                foreach ($resourceTypes as $resourceType) {

                    $resources = Resource::where('location_id', '=', $location->id)
                                ->where('resource_type_id', '=', $resourceType->id)
                                ->where('retire_date', '=', null)
                                ->orderBy('abbrv', 'asc')
                                ->get(['abbrv', 'id']);

                    $locationsAndResources .= "{\"id\":\"Type-" . $resourceType->id . "\",\"name\":\"" . $resourceType->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";

                    foreach ($resources as $resource) {
                        $resourceCategory = ResourceCategory::where('resource_type_id', '=', $resourceType->id)->get(['abbrv', 'id']);
                        $resourceSubCategory = ResourceSubCategory::where('resource_category_id', '=', $resourceCategory[0]->id)->get(['abbrv', 'id']);
                        $locationsAndResources .= "{\"id\":\"" . $resource->id . "\",\"name\":\"" . $resource->abbrv . "\", \"type\":\"" . $resourceType->abbrv . "\", \"category\":\"".$resourceCategory[0]->abbrv."\", \"subcategory\":\"".$resourceSubCategory[0]->abbrv."\"},";
                    }

                    $locationsAndResources .= "]},";

                }

                $locationsAndResources .= "]},";

            }

        }

        $locationsAndResources .= "]";
        
       return [ 'locationsAndResources' => $locationsAndResources,
           'businessBeginHour' =>$businessBeginHour,
           'businessEndHour' =>$businessEndHour];
           //'eventText' =>$eventText];

    }

    /**
     * Store a newly created course instance in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseInstanceRequest $request)
    {
        $editCourse = 0;
        if(isset($request->editCourseInstance)) {
            $editCourse = $request->editCourseInstance;
        }

        $user = Auth::user();
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;
        $request['start_time'] = Carbon::parse($request->selectDate . ' ' . $request->start_time);
        $request['end_time'] = Carbon::parse($request->selectDate . ' ' . $request->end_time);

        if(isset($request['special_requirements']) && $request['special_requirements'] == 1) {
            $request['special_requirements'] = 1;
        } else {
            $request['special_requirements'] = 0;
        }

        if(isset($request['sims_spec_needed']) && $request['sims_spec_needed'] == 1) {
            $request['sims_spec_needed'] = 1;
        } else {
            $request['sims_spec_needed'] = 0;
        }

        //if they have indicated that instructor report time is before, make value to insert negative
        if($request->instructorReportBA == 'Before') {
            $request['fac_report'] = 0 - $request->schedule_addClass_InstructorReport;
        } else {
            $request['fac_report'] = $request->schedule_addClass_InstructorReport;
        }

        //if they have indicated that instructor leave time is before, make value to insert negative
        if($request->instructorLeaveBA == 'Before') {
            $request['fac_leave'] = 0 - $request->schedule_addClass_InstructorLeave;
        } else {
            $request['fac_leave'] = $request->schedule_addClass_InstructorLeave;
        }

        if($editCourse > 0) {
            $event = Event::where('id', '=', $editCourse)
                ->with(['eventResources'])->first();

            $request['course_instance_id'] = $event->course_instance_id;
            $request['color'] = $request->html_color;
            $event->fill($request->all())->save();
        } else {
            $cInst = CourseInstance::create($request->all());
            $request['course_instance_id'] = $cInst->id;
            $request['color'] = $request->html_color;
            $event = Event::create($request->all());
        }

	if(isset($request['fromRequest'])) {
		$sr = ScheduleRequest::where('id','=',$request['fromRequest'])->first();
		$sr->event_id = $event->id;
		$sr->save();
	}
        if($editCourse == 0) {
            $addedResources = $request->get('addedResources');
            if(is_array($addedResources)) {
                foreach($addedResources as $idx=>$GUID) {
                    $RID = $request->get('RID-'.$GUID);
                    $RStart = $request->get('RStart-'.$GUID);
                    $REnd = $request->get('REnd-'.$GUID);
                    $RSetup = $request->get('RSetup-'.$GUID);
                    $RTeardown = $request->get('RTeardown-'.$GUID);

                    $CalculatedStartTime = Carbon::parse($RStart)->addMinutes(abs($RSetup));
                    $CalculatedEndTime = Carbon::parse($REnd)->subMinutes(abs($RTeardown));
                    $eventResource = EventResource::create([
                        'event_id' => $event->id,
                        'resource_id' => $RID,
                        'start_time' => $CalculatedStartTime->toDateTimeString(),
                        'end_time' => $CalculatedEndTime->toDateTimeString(),
                        'setup_time' => $RSetup,
                        'teardown_time' => $RTeardown,
                        'created_by' => $event->created_by,
                        'last_edited_by' => $event->last_edited_by
                    ]);
                }
            }
        } else {
            $addedResources = $request->get('addedResources');

            foreach($event->eventResources as $o=>$oldResource) {
                $found = false;
                if(is_array($addedResources)) {
                    foreach($addedResources as $idx=>$GUID) {
                        $rid = $request->get('RID-'.$GUID);
                        if($rid == $oldResource->resource_id)
                            $found = true;
                    }
                }
                if(!$found)
                    $oldResource->delete();
            }
            if(is_array($addedResources)) {
                foreach($addedResources as $idx=>$GUID) {
                    $rid = $request->get('RID-'.$GUID);
                    $RStart = $request->get('RStart-'.$GUID);
                    $REnd = $request->get('REnd-'.$GUID);
                    $RSetup = $request->get('RSetup-'.$GUID);
                    $RTeardown = $request->get('RTeardown-'.$GUID);

                    $CalculatedStartTime = Carbon::parse($RStart)->addMinutes(abs($RSetup));
                    $CalculatedEndTime = Carbon::parse($REnd)->subMinutes(abs($RTeardown));
                    if($this->hasResource($rid, $event->eventResources)) {
                        foreach($event->eventResources as $key=>$eventResc) {
                            if($eventResc->resource_id == $rid) {
                                $event->eventResources[$key]->event_id = $event->id;
                                $event->eventResources[$key]->resource_id = $rid;
                                $event->eventResources[$key]->start_time = $CalculatedStartTime->toDateTimeString();
                                $event->eventResources[$key]->end_time = $CalculatedEndTime->toDateTimeString();
                                $event->eventResources[$key]->setup_time = $RSetup;
                                $event->eventResources[$key]->teardown_time = $RTeardown;
                                $event->eventResources[$key]->last_edited_by = $event->last_edited_by;
                                $event->eventResources[$key]->save();
                            }
                        }
                    } else {
                        $eventResource = EventResource::create([
                            'event_id' => $event->id,
                            'resource_id' => $rid,
                            'start_time' => $CalculatedStartTime->toDateTimeString(),
                            'end_time' => $CalculatedEndTime->toDateTimeString(),
                            'setup_time' => $RSetup,
                            'teardown_time' => $RTeardown,
                            'created_by' => $event->created_by,
                            'last_edited_by' => $event->last_edited_by
                        ]);
                    }
                }
            }
        }
        return redirect()->action('CourseInstance\CourseInstanceController@edit', [$event->id])
            ->with('success', '<ul>
                    <li><a href="/courseInstance/clone/'.$event->id.'/'.$request->selectDate.'">Clone this instance</a></li>
                    <li><a href="/courseInstance/template/create/'.$event->id.'">Create a course template</a> with this event </li>
                    </ul>');
    }


    private function hasResource($resourceId, $resources) {
        foreach($resources as $key=>$resource) {
            if($resource->resource_id == $resourceId)
                return true;
        }
        return false;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $courseInstance = CourseInstance::find($id);

        return view('courseInstance.show', compact('courseInstance'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::where('id', '=', $id)->with(['courseInstance', 'eventResources', 'courseInstance.course'])->first();
        $start_date = new DateTime(Carbon::parse($event->start_time)->toDateString());
        $end_date = new DateTime(Carbon::parse($event->start_time)->toDateString());

        $scheduleRequest = ScheduleRequest::where('event_id', '=', $id)->first();

        // if schedule requestion get location / building information
        if($scheduleRequest) {
            $location = Location::where('id', $scheduleRequest->location_id)->first();
            $scheduleRequest->location_abbrv = $location->abbrv;
            $scheduleRequest->building_abbrv = Building::where('id', $location->building_id)->value('abbrv');
        } else {
            $scheduleRequest = [];
        }

        $courses = $this->getCoursesAndResources($start_date, $end_date);
        $site_id = Session::get('site_id');

        //for start and end time defaults
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);

        $resources = Resource::with(['category', 'subcategory', 'location' => function($q) use ($site_id) {
            $q->where('site_id', '=', $site_id);
        }])->has('location')->orderBy('abbrv', 'ASC')->get();
        $resourceList = $this->getResourcesDPList();

        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = $this->getLocationsAndResources();
        extract($locationsAndResources);

        $courseInstance = $event;
        return view('courseInstance.edit', compact('courseInstance', 'courses', 'resources', 'defaultBusinessBeginHour', 'defaultBusinessEndHour' ,'businessEndHour', 'locationsAndResources', 'businessBeginHour', 'defaultStartHour', 'defaultEndHour', 'resourceList', 'scheduleRequest'));
    }


    public function clone($id, $date)
    {
        $event = Event::where('id', '=', $id)->with(['courseInstance', 'eventResources'])->first();
        $start_date = new DateTime(Carbon::parse($event->start_time)->toDateString());
        $end_date = new DateTime(Carbon::parse($event->start_time)->toDateString());

        $courses = $this->getCoursesAndResources($start_date, $end_date);

        $site_id = Session::get('site_id');

        //for start and end time defaults
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);

        $resources = Resource::with(['category', 'subcategory', 'location' => function($q) use ($site_id) {
            $q->where('site_id', '=', $site_id);
        }])->has('location')->orderBy('abbrv', 'ASC')->get();
        $resourceList = $this->getResourcesDPList();

        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = $this->getLocationsAndResources();
        extract($locationsAndResources);
        $cloneCourseDate = $date;

        $courseInstance = $event;
        return view('courseInstance.clone', compact('courseInstance', 'courses', 'resources', 'defaultBusinessBeginHour', 'defaultBusinessEndHour' ,'businessEndHour', 'locationsAndResources', 'businessBeginHour', 'defaultStartHour', 'defaultEndHour', 'cloneCourseDate', 'resourceList'));
    }


    public function fromRequest($id) {
        $site_id = Session::get('site_id');
        $start_date = new DateTime();
        $end_date = new DateTime();
        $end_date->modify('+1 day');
	    $start_date->modify('-1 day');
	    $scheduleRequest = ScheduleRequest::where('id', '=', $id)->first();
        $courses = $this->getCoursesAndResources($start_date, $end_date);

        // location / building information
        $location = Location::where('id', $scheduleRequest->location_id)->first();
        $scheduleRequest->location_abbrv = $location->abbrv;
        $scheduleRequest->building_abbrv = Building::where('id', $location->building_id)->value('abbrv');

        //for start and end time defaults
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);

        // resources
        $resourceList = $this->getResourcesDPList();
        $resources = Resource::with(['category', 'subcategory', 'location' => function($q) use ($site_id) {
            $q->where('site_id', '=', $site_id);
            }])->has('location')->orderBy('abbrv', 'ASC')->get();

        $locationsAndResources = $this->getLocationsAndResources();
        extract($locationsAndResources);

        // template information
	    $templates = CourseTemplate::with(['courseTemplateResources', 'courseTemplateResources.resourceIdentifierType'])->get();
        if(isset($scheduleRequest->template_id)) {
            $template = CourseTemplate::where('id', '=', $scheduleRequest->template_id)->with(['courseTemplateResources', 'courseTemplateResources.resourceIdentifierType'])->first();
        } else {
            $template = [];
        }

        $course = CourseInstance::where('course_id', '=', $scheduleRequest->course_id)->with(['course'])->first();
        $resources = Resource::with(['category', 'subcategory'])->orderBy('abbrv', 'ASC')->get();

        return view('courseInstance.fromResource', compact('template', 'courses', 'resources', 'defaultBusinessBeginHour', 'defaultBusinessEndHour' ,'businessEndHour', 'locationsAndResources', 'businessBeginHour', 'defaultStartHour', 'defaultEndHour', 'course', 'templates', 'resources', 'resourceList', 'scheduleRequest'));
    }


    public function fromTemplate($id) {
        $site_id = Session::get('site_id');
        $start_date = new DateTime();
        $end_date = new DateTime();
        $end_date->modify('+1 day');
        $start_date->modify('-1 day');
        $courses = $this->getCoursesAndResources($start_date, $end_date);

        //for start and end time defaults
        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);

        $resourceList = $this->getResourcesDPList();
        $resources = Resource::with(['category', 'subcategory', 'location' => function($q) use ($site_id) {
            $q->where('site_id', '=', $site_id);
        }])->has('location')->orderBy('abbrv', 'ASC')->get();

        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
        $locationsAndResources = $this->getLocationsAndResources();
        extract($locationsAndResources);
        $templates = CourseTemplate::with(['courseTemplateResources', 'courseTemplateResources.resourceIdentifierType'])->get();
        $template = CourseTemplate::where('id', '=', $id)->with(['courseTemplateResources', 'courseTemplateResources.resourceIdentifierType'])->first();
        $course = CourseInstance::where('course_id', '=', $template->course_instance_id)->with(['course'])->first();
        $resources = Resource::with(['category', 'subcategory'])->orderBy('abbrv', 'ASC')->get();

        return view('courseInstance.fromTemplate', compact('template', 'courses', 'resources', 'defaultBusinessBeginHour', 'defaultBusinessEndHour' ,'businessEndHour', 'locationsAndResources', 'businessBeginHour', 'defaultStartHour', 'defaultEndHour', 'course', 'templates', 'resources', 'resourceList'));
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

    private function getResourcesDPList() {

        $site_id = Session::get('site_id');
        $resourceList = DB::table('locations')
            ->select('resources.id as resource_id', 'buildings.abbrv as building_abbrv', 'resources.abbrv as resource_abbrv', 'locations.abbrv as location_abbrv')
            ->join('resources','resources.location_id','=','locations.id')
            ->join('buildings','buildings.id','=','locations.building_id')
            ->where(['locations.site_id' => $site_id, 'resources.retire_date' => NULL, 'resources.resource_type_id' => 1])
            ->orderBy('buildings.abbrv','ASC')
            ->orderBy('locations.abbrv','ASC')
            ->orderBy('resources.abbrv','ASC')
            ->get();

        $resources = [];
        foreach($resourceList as $location) {
            $resources[$location->resource_id] = $location->building_abbrv . " " . $location->location_abbrv. " " . $location->resource_abbrv;
        }
        return $resources;
    }

    public static function getCoursesAndResources($start_date, $end_date) {
        // populates the events list etc for daypilot
        $courses = Course::orderBy('abbrv')
            ->with(['courseInstances', 'courseOption', 'courseOption.courseOptions', 'courseInstances.events' => function($q) use ($start_date, $end_date) {
                $q->whereDate('start_time', '>=', $start_date->format('Y-m-d'));
                $q->whereDate('end_time', '<=', $end_date->format('Y-m-d'));
                $q->where('deleted_at', null);
            }, 'courseInstances.events.eventResources'])
            ->where(['retire_date' => NULL])
            ->get();

        foreach($courses as $i=>$course) {
          foreach($course->courseInstances as $j=>$instance) {
            foreach($instance->events as $k=>$event) {
                foreach($event->eventResources as $l=>$eventResource) {
                    $event->eventText .= $eventResource->getResourceEventGrid();
                }
                $event->eventText = substr($event->eventText, 0, strlen($event->eventText)-1);
            }
          }
        }

        return $courses;
    }


    public static function getEventsForDate($date) {

        $data = DB::table('resources')
            // some items are aliased to meet Day Pilot specs   
            ->select('event_resources.id', DB::raw("CONVERT(resources.id, CHAR) as resource"), 'event_resources.event_id','events.public_comments', 'events.internal_comments', 'courses.id as course_id',  'event_resources.start_time AS start', 'event_resources.end_time as end', 'event_resources.setup_time AS setup', 'event_resources.teardown_time AS teardown', 'courses.abbrv AS text', 'courses.name', 'resources.resource_type_id', 'resources.resource_category_id', 'resources.resource_sub_category_id', 'resources.location_id', 'event_resources.isIMR as isIMR')
            ->join('event_resources', 'resources.id','=', 'event_resources.resource_id')
            ->join('events', 'event_resources.event_id', '=', 'events.id')
            ->join('course_instances', 'events.course_instance_id', '=', 'course_instances.id')
            ->join('courses', 'course_instances.course_id', '=', 'courses.id')
            ->where(DB::raw("(STR_TO_DATE(events.start_time, '%Y-%m-%d'))"), '=', $date)
            ->where('courses.site_id', '=', Session::get('site_id'))
            ->whereNull('event_resources.deleted_at')
            ->get();
            
        // Format for Day Pilot Times specs
        foreach ($data as &$d) {
            $d->start = str_replace(' ', 'T', $d->start);
            $d->end = str_replace(' ', 'T', $d->end);
            $d->course_option_color = Course::find($d->course_id)->getCourseOption(7);
            $d->barColor = Event::find($d->event_id)->eventColor();

            // Create on hover text for each event
            $d->bubbleHtml = '<h4>'. $d->name . '</h4><h6><b>'. trans('labels.event.public_notes') .': </b></h6>' . $d->public_comments .'<h6><b>'. trans('labels.event.internal_notes') .': </b></h6>'. $d->internal_comments;
        }
        return response()->json($data);
    }


    public static function getLocationsAndResourcesData()
   { 
       $buildings = Building::orderBy('display_order')->get(['abbrv', 'id']);
       $allResourceLocations = Resource::groupBy('location_id')->get(['location_id']);
       //For grid tree
       $locationsAndResources = "[";
       foreach ($buildings as $building) {
           //only display locations if there are resources
           $locations = Location::wherein('id', $allResourceLocations)->where('building_id', "=", $building->id)->orderBy('display_order')->get(['abbrv', 'id']);
           foreach ($locations as $location) {
               $locationsAndResources .= "{\"id\":\"Location-" . $location->id . "\",\"name\":\"" . $building->abbrv . " " . $location->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";
               //only display resource types if there are resources
               $resourceTypes = ResourceType::whereHas('resources', function($query) use ($location) {
                   $query->where('location_id', $location->id);
               })->get();
               foreach ($resourceTypes as $resourceType) {
                   $resources = Resource::where('location_id', '=', $location->id)
                       ->where('resource_type_id', '=', $resourceType->id)
                       ->where('retire_date', '=', null)
                       ->orderBy('abbrv', 'asc')
                       ->get();
                   $locationsAndResources .= "{\"id\":\"Type-" . $resourceType->id . "\",\"name\":\"" . $resourceType->abbrv . "\", \"expanded\": true, \"cellsDisabled\": true, \"children\":[";
                   foreach ($resources as $resource) {
                       $locationsAndResources .= "{\"id\":\"" . $resource->id . "\",\"name\":\"" . $resource->abbrv . "\", \"type\":\"" . $resourceType->abbrv . "\", \"category\":\"".$resource->category->abbrv."\", \"subcategory\":\"".$resource->subcategory->abbrv."\"},";
                   }
                   $locationsAndResources .= "]},";
               }
               $locationsAndResources .= "]},";
           }
       }
       $locationsAndResources .= "]"; 

       $data  = ['locationsAndResources' => $locationsAndResources];
       extract($data);
       return $data;

   }
}
