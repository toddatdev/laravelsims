<?php

namespace App\Http\Controllers\CourseInstance;

use App\Models\Resource\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\Http\Requests\Course\StoreCourseTemplateRequest;
use App\Http\Requests\Course\UpdateCourseTemplateRequest;
use Carbon\Carbon;
use App\Models\Course\Course;
use App\Models\CourseInstance\Event;
use App\Models\Course\CourseTemplate;
use App\Models\Course\CourseTemplateResource;
use App\Models\CourseInstance\CourseInstanceType;
use Illuminate\Support\Facades\DB;


class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
    }

    /**
     * Show the form for creating a new template from scratch with no example event
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromScratch(Course $course)
    {
        $courseID = $course->id;
        $templateName = null;
        $eventAbbrv = null;
        $numberParticipants = null;
        $publicComments = null;
        $internalComments = null;
        $color = null;
        if ($course->getCourseOption(7) <> "0")
        {
            //the function above return 0 when no color is set, so only set if value returned
            $color = $course->getCourseOption(7);
        }
        $simsSpecialistNeeded = 0;
        $specialRequirements = 0;
        $instructorReport = $course->getCourseOption(10);
        $instructorLeave = $course->getCourseOption(11);
        $initialMeetingRoom = null;
        $initialMeetingRoomType = null;

        $roomList = $this->getIMRList();
        $resourceList = $this->getResourceList();
        $resourceTypes = $this->getResourceTypes(0);

        $defaultSetupTime = $course->getCourseOption(8);
        $defaultTeardownTime = $course->getCourseOption(9);

        $startTime = Carbon::createFromFormat('G', Session::get('business_start_hour'))->format('H:i');
        $endTime = Carbon::createFromFormat('G', Session::get('business_end_hour'))->format('H:i');

        return view('courseInstance.template.create-from-scratch', compact('course', 'courseID',
            'templateName', 'eventAbbrv', 'numberParticipants', 'publicComments', 'internalComments', 'color', 'startTime', 'endTime',
            'simsSpecialistNeeded', 'specialRequirements', 'instructorReport', 'instructorLeave', 'initialMeetingRoom',
            'initialMeetingRoomType', 'roomList', 'resourceList', 'resourceTypes', 'defaultSetupTime', 'defaultTeardownTime'));

    }

    /**
     * Show the form for creating a new template based on event
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        $courseID = $event->courseInstance->course_id;
        $templateName = null;
        $eventAbbrv = $event->abbrv;
        $numberParticipants = $event->class_size;
        $publicComments = $event->public_comments;
        $internalComments = $event->internal_comments;
        $color = $event->color;
        $simsSpecialistNeeded = $event->sims_spec_needed;
        $specialRequirements = $event->special_requirements;
        $instructorReport = $event->fac_report;
        $instructorLeave = $event->fac_leave;
        $initialMeetingRoom = $event->initial_meeting_room;
        $initialMeetingRoomType = null;
        $defaultSetupTime = $event->setup_time;
        $defaultTeardownTime  = $event->teardown_time;
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_time)->format('H:i');
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_time)->format('H:i');

        $roomList = $this->getIMRList();
        $resourceList = $this->getResourceList();
        $resourceTypes = $this->getResourceTypes(0);

        return view('courseInstance.template.create', compact('event', 'courseID', 'templateName', 'eventAbbrv', 'numberParticipants', 'publicComments', 'internalComments',
            'color', 'simsSpecialistNeeded', 'specialRequirements', 'instructorReport', 'instructorLeave', 'initialMeetingRoom', 'initialMeetingRoomType',
            'defaultSetupTime', 'defaultTeardownTime', 'startTime', 'endTime', 'roomList', 'resourceList', 'resourceTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseTemplate $template)
    {
        $templateName = $template->name;
        $eventAbbrv = $template->event_abbrv;
        $courseID = $template->course_id;
        $numberParticipants = $template->class_size;
        $publicComments = $template->public_comments;
        $internalComments = $template->internal_comments;
        $color = $template->color;
        $simsSpecialistNeeded = $template->sims_spec_needed;
        $specialRequirements = $template->special_requirements;
        $instructorReport = $template->fac_report;
        $instructorLeave = $template->fac_leave;
        $initialMeetingRoom = $template->initial_meeting_room;
        $initialMeetingRoomType = $template->initial_meeting_room_type;
        $defaultSetupTime = $template->setup_time;
        $defaultTeardownTime  = $template->teardown_time;
        $startTime = Carbon::createFromFormat('H:i:s', $template->start_time)->format('H:i');
        $endTime = Carbon::createFromFormat('H:i:s', $template->end_time)->format('H:i');

        $roomList = $this->getIMRList();
        $resourceList = $this->getResourceList();
        $resourceTypes = $this->getResourceTypes(0);

        // mitcks 6/5/19: using a DB table here so I can sort by two columns (cannot be done with sortBy on related Model)')
        $courseTemplateResources = DB::table('course_template_resources')
            ->select('course_template_resources.id', 'course_template_resources.*')
            ->join('resources','resources.id','=','course_template_resources.resource_id')
            ->where(['course_template_resources.course_template_id' => $template->id])
            ->orderBy('resources.resource_type_id','ASC')
            ->orderBy('resources.abbrv','ASC')
            ->get();

        return view('courseInstance.template.edit', compact('template', 'eventAbbrv', 'courseID', 'numberParticipants', 'publicComments', 'internalComments',
            'color', 'simsSpecialistNeeded', 'specialRequirements', 'instructorReport', 'instructorLeave', 'initialMeetingRoom', 'initialMeetingRoomType',
            'defaultSetupTime', 'defaultTeardownTime', 'startTime', 'endTime', 'templateName', 'roomList', 'resourceList', 'resourceTypes', 'courseTemplateResources' ));

    }

    /**
     * Show the form for duplication the specified resource.
     *
     * @param  int  $template
     * @return \Illuminate\Http\Response
     */
    public function duplicateTemplate(CourseTemplate $template)
    {
        $templateName = $template->name;
        $eventAbbrv = $template->event_abbrv;
        $courseID = $template->course_id;
        $numberParticipants = $template->class_size;
        $publicComments = $template->public_comments;
        $internalComments = $template->internal_comments;
        $color = $template->color;
        $simsSpecialistNeeded = $template->sims_spec_needed;
        $specialRequirements = $template->special_requirements;
        $instructorReport = $template->fac_report;
        $instructorLeave = $template->fac_leave;
        $initialMeetingRoom = $template->initial_meeting_room;
        $initialMeetingRoomType = $template->initial_meeting_room_type;
        $defaultSetupTime = $template->setup_time;
        $defaultTeardownTime  = $template->teardown_time;
        $startTime = Carbon::createFromFormat('H:i:s', $template->start_time)->format('H:i');
        $endTime = Carbon::createFromFormat('H:i:s', $template->end_time)->format('H:i');

        $roomList = $this->getIMRList();
        $resourceList = $this->getResourceList();
        $resourceTypes = $this->getResourceTypes(0);

        // mitcks 6/5/19: using a DB table here so I can sort by two columns (cannot be done with sortBy on related Model)
        $courseTemplateResources = DB::table('course_template_resources')
            ->select('course_template_resources.id', 'course_template_resources.*')
            ->join('resources','resources.id','=','course_template_resources.resource_id')
            ->where(['course_template_resources.course_template_id' => $template->id])
            ->orderBy('resources.resource_type_id','ASC')
            ->orderBy('resources.abbrv','ASC')
            ->get();

        return view('courseInstance.template.duplicateTemplate', compact('template', 'courseID', 'eventAbbrv', 'numberParticipants', 'publicComments', 'internalComments',
            'color', 'simsSpecialistNeeded', 'specialRequirements', 'instructorReport', 'instructorLeave', 'initialMeetingRoom', 'initialMeetingRoomType',
            'defaultSetupTime', 'defaultTeardownTime', 'startTime', 'endTime', 'templateName', 'roomList', 'resourceList', 'resourceTypes', 'courseTemplateResources' ));

    }

    /**
     * Show the form for exporting the specified resource.
     *
     * @param  int  $template
     * @return \Illuminate\Http\Response
     */
    public function exportTemplate(CourseTemplate $template)
    {
        $templateName = $template->name;
        $eventAbbrv = $template->event_abbrv;
        $courseID = $template->course_id;
        $numberParticipants = $template->class_size;
        $publicComments = $template->public_comments;
        $internalComments = $template->internal_comments;
        $color = $template->color;
        $simsSpecialistNeeded = $template->sims_spec_needed;
        $specialRequirements = $template->special_requirements;
        $instructorReport = $template->fac_report;
        $instructorLeave = $template->fac_leave;
        $initialMeetingRoom = $template->initial_meeting_room;
        $initialMeetingRoomType = $template->initial_meeting_room_type;
        $defaultSetupTime = $template->setup_time;
        $defaultTeardownTime  = $template->teardown_time;
        $startTime = Carbon::createFromFormat('H:i:s', $template->start_time)->format('H:i');
        $endTime = Carbon::createFromFormat('H:i:s', $template->end_time)->format('H:i');

        $roomList = $this->getIMRList();
        $resourceList = $this->getResourceList();
        $resourceTypes = $this->getResourceTypes(0);

        // mitcks 6/5/19: using a DB table here so I can sort by two columns (cannot be done with sortBy on related Model)
        $courseTemplateResources = DB::table('course_template_resources')
            ->select('course_template_resources.id', 'course_template_resources.*')
            ->join('resources','resources.id','=','course_template_resources.resource_id')
            ->where(['course_template_resources.course_template_id' => $template->id])
            ->orderBy('resources.resource_type_id','ASC')
            ->orderBy('resources.abbrv','ASC')
            ->get();

        //get courses for dropdown
        $courses = Course::orderBy('abbrv')
            ->where(['retire_date' => NULL])
            ->get();

        return view('courseInstance.template.exportTemplate', compact('template', 'eventAbbrv', 'courseID', 'numberParticipants', 'publicComments', 'internalComments',
            'color', 'simsSpecialistNeeded', 'specialRequirements', 'instructorReport', 'instructorLeave', 'initialMeetingRoom', 'initialMeetingRoomType',
            'defaultSetupTime', 'defaultTeardownTime', 'startTime', 'endTime', 'templateName', 'roomList', 'resourceList', 'resourceTypes', 'courseTemplateResources', 'courses' ));

    }

    // mitcks 6/5/19: this was part of Matt's code and not currently used, but leaving it here for now in case needed
    public function nameIsTaken(Request $request) {
        if($request['event_id'] > 0) {
            $count = CourseTemplate::where([
                ['id', '!=', $request['event_id']],
                ['course_id', '=', $request['course_id']],
                ['name', '=', $request['template_name']]
            ])->count();
        } else {
            $count = CourseTemplate::where([
                ['course_id', '=', $request['course_id']],
                ['name', '=', $request['template_name']]
            ])->count();
        }
        return response()->json(['count'=>$count]);
    }

    /**
     * Store a newly created template in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseTemplateRequest $request)
    {
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;
        $request['created_by'] = $user->id;

        if($request->instructor_report_BA == 'B')
            {$request['fac_report'] = 0 - $request->instructor_report;}
        else
            {$request['fac_report'] = $request->instructor_report;}

        if($request->instructor_leave_BA == 'B')
            {$request['fac_leave'] = 0 - $request->instructor_leave;}
        else
            {$request['fac_leave'] = $request->instructor_leave;}

        if ($request->has('special_requirements'))
            {$request['special_requirements'] = 1;}
        else
            {$request['special_requirements'] = 0;}

        if ($request->has('sims_spec_needed'))
            {$request['sims_spec_needed'] = 1;}
        else
            {$request['sims_spec_needed'] = 0;}

        $request['color'] = $request['html_color'];

        //Save to course_templates table
        $template = CourseTemplate::create($request->all());

        //loop through resources and save to course_template_resources table
        for ($x = 0; $x <= $request->resource_count; $x++ ) {

            if($request->input($x.'_resource_id') > 0) //don't try to insert if they didn't select a resource
            {
                $templateResource = new CourseTemplateResource;
                if($request->radio_is_imr == $x)
                {
                    $templateResource->isIMR = 1;

                    //go back and set in course_templates too
                    $template->update(
                        ['initial_meeting_room' => $request->input($x.'_resource_id'),
                        'initial_meeting_room_type' => $request->input($x.'_resource_type')]
                    );
                    $template->save;
                }
                else
                {
                    $templateResource->isIMR = null;
                }
                $templateResource->course_template_id = $template->id;
                $templateResource->resource_id = $request->input($x.'_resource_id');
                $templateResource->resource_identifier_type = $request->input($x.'_resource_type');
                $templateResource->start_time = Carbon::createFromFormat('H:i A', $request->input($x.'_start_time'))->format('H:i');
                $templateResource->end_time = Carbon::createFromFormat('H:i A', $request->input($x.'_end_time'))->format('H:i');
                $templateResource->setup_time = $request->input($x.'_setup_time');
                $templateResource->teardown_time = $request->input($x.'_teardown_time');
                $templateResource->created_by = $user->id;
                $templateResource->last_edited_by = $user->id;
                $templateResource->save();
            }
        }

        return redirect()->route('all_course_templates',[$template->course_id])
            ->with('success', $template->name . trans('alerts.backend.templates.created'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseTemplateRequest $request, CourseTemplate $template)
    {
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;

        if($request->instructor_report_BA == 'B')
        {$request['fac_report'] = 0 - $request->instructor_report;}
        else {$request['fac_report'] = $request->instructor_report;}

        if($request->instructor_leave_BA == 'B')
        {$request['fac_leave'] = 0 - $request->instructor_leave;}
        else {$request['fac_leave'] = $request->instructor_leave;}

        if ($request->has('special_requirements'))
        {$request['special_requirements'] = 1;}
        else
        {$request['special_requirements'] = 0;}

        if ($request->has('sims_spec_needed'))
        {$request['sims_spec_needed'] = 1;}
        else
        {$request['sims_spec_needed'] = 0;}

        $request['color'] = $request['html_color'];

        $template->update($request->all());

        //loop through existing CourseTemplateResource and if they do not exist in request,
        // set all IMR to null (do this before add to avoid IMR unique key below if they change rooms)
        $templateResources = CourseTemplateResource::where('course_template_id', $template->id)->get();
        foreach($templateResources as $templateResource)
        {
            $templateResource->update(['isIMR' => null]);

            if($request->has($templateResource->id . '_course_template_resource_id') == false)
            {
                $templateResource::destroy($templateResource->id);
            }
        }

        //loop through resources and save
        for ($x = 0; $x <= $request->resource_count; $x++ ) {

            if($request->radio_is_imr == $x)
            {
                $isIMR = 1;

                //dd($request);

                //go back and set in course_templates too
                $template->update(
                    ['initial_meeting_room' => $request->input($x.'_resource_id'),
                    'initial_meeting_room_type' => $request->input($x.'_resource_type')]
                );
            }
            else
            {
                $isIMR = null;
            }

            if ($request->has($x . '_resource_id')) //check to see if it has been set
            {
                if ($request->input($x . '_resource_id') > 0) //don't try to insert if they didn't select a resource in a new row
                {
                    if($request->has($x . '_course_template_resource_id'))
                    {
                        $templateResourceUpdate = CourseTemplateResource::find($request->input($x . '_course_template_resource_id'));
                        $templateResourceUpdate->resource_id = $request->input($x.'_resource_id');
                        $templateResourceUpdate->resource_identifier_type = $request->input($x.'_resource_type');
                        $templateResourceUpdate->start_time = Carbon::createFromFormat('H:i A', $request->input($x.'_start_time'))->format('H:i');
                        $templateResourceUpdate->end_time = Carbon::createFromFormat('H:i A', $request->input($x.'_end_time'))->format('H:i');
                        $templateResourceUpdate->setup_time = $request->input($x.'_setup_time');
                        $templateResourceUpdate->teardown_time = $request->input($x.'_teardown_time');
                        $templateResourceUpdate->last_edited_by = $user->id;
                        $templateResourceUpdate->isIMR = $isIMR;
                        $templateResourceUpdate->save();

                    }
                    else
                    {

                        $templateResourceCreate = CourseTemplateResource::create([
                                'course_template_id' => $template->id,
                                'resource_id' => $request->input($x.'_resource_id'),
                                'resource_identifier_type' => $request->input($x.'_resource_type'),
                                'start_time' => Carbon::createFromFormat('H:i A', $request->input($x.'_start_time'))->format('H:i'),
                                'end_time' => Carbon::createFromFormat('H:i A', $request->input($x.'_end_time'))->format('H:i'),
                                'setup_time' => $request->input($x.'_setup_time'),
                                'teardown_time' => $request->input($x.'_teardown_time'),
                                'created_by' => $user->id,
                                'last_edited_by' => $user->id,
                                'isIMR' => $isIMR
                            ]);

                        //add new ID to request object so it does not try to delete it
                        $request[$templateResource->id . '_course_template_resource_id'] = $templateResource->id;

                    }

                }
            }
        }


        return redirect()->route('all_course_templates',[$template->course_id])
            ->with('success', $template->name . trans('alerts.backend.templates.edited'));

    }

// mitcks 6/5/19 - this section is "Matt code" keeping in case needed

//    private function hasResource($Id, $resources) {
//        foreach($resources as $key=>$resource) {
//            if($resource->id == $Id)
//                return true;
//        }
//        return false;
//    }

//    private function deleteResources($template, $request) {
//        $addedResources = $request->get('addedResources');
//        foreach($template->courseTemplateResources as $tdx=>$templateResource) {
//            $found = false;
//            if(is_array($addedResources)) {
//                foreach($addedResources as $idx=>$rec) {
//                    $resource = json_decode($rec);
//                    if($templateResource->id == $resource->id)
//                        $found = true;
//                }
//            }
//            if(!$found) {
//                $templateResource->delete();
//            }
//        }
//        return $template;
//    }



//    private function updateTemplate($id, $request, $user) {
//        $template = CourseTemplate::where('id', '=', $id)->with(['course', 'courseTemplateResources'])->first();
//        $template->fill($request->toArray());
//        $template->save();
//        return $template;
//    }
//
//    private function createTemplate($request, $user) {
//          $request['course_id'] = $request->fromEvent;
//          $request['created_by'] = $user->id;
//          return CourseTemplate::create($request->all());
//    }

//    private function updateResource($newResource, $request, $CIType, $user) {
//        $resource = CourseTemplateResource::where('id', '=', $newResource->id)->first();
//        $resource->resource_id = $newResource->resource_id;
//        $resource->resource_identifier_type = $CIType->id;
//        $resource->start_time = $newResource->start_time;
//        $resource->end_time = $newResource->end_time;
//        $resource->setup_time = $newResource->setup_time;
//        $resource->teardown_time = $newResource->teardown_time;
//        $resource->last_edited_by = $user->id;
//        $resource->save();
//        return $resource;
//    }
//    private function saveResource($resource, $template, $CIType, $user) {
//        $resource->resource_identifier_type = $CIType->id;
//        $resource->course_template_id = $template->id;
//        $resource->last_edited_by = $user->id;
//        $resource->created_by = $user->id;
//        return CourseTemplateResource::create((array)$resource);
//    }
//
//    private function saveResources($request, $user, $template) {
//        $addedResources = $request->get('addedResources');
//
//        if(is_array($addedResources)) {
//            foreach($addedResources as $idx=>$rec) {
//                $resource = json_decode($rec);
//                $CIType = ResourceIdentifierType::where('abbrv', '=', $resource->resource_identifier_type->abbrv)->first();
//                $template_resource = $this->saveResource($resource, $template, $CIType, $user);
//
//                if($resource->id == $request['imr']) {
//                    $template->initial_meeting_room = $template_resource->id;
//                    $template->initial_meeting_room_type = $CIType->id;
//                    $template->save();
//                }
//            }
//        }
//    }
//
//    private function randomGen($min, $max, $quantity) {
//        $numbers = range($min, $max);
//        shuffle($numbers);
//        return array_slice($numbers, 0, $quantity);
//    }


//    public function clone($id)
//    {
//        $site_id = Session::get('site_id');
//        $start_date = new DateTime();
//        $end_date = new DateTime();
//        $end_date->modify('+1 day');
//        $start_date->modify('-1 day');
//
//        $event = CourseTemplate::where('id', '=', $id)
//            ->with(['courseTemplateResources', 'courseTemplateResources.resourceIdentifierType', 'course'])->first();
//
//        $resources = Resource::with(['type', 'category', 'subcategory', 'location' => function($q) use ($site_id) {
//            $q->where('site_id', '=', $site_id);
//        }, 'location.building'])->has('location')->orderBy('abbrv', 'ASC')->get();
//        $resourceTypes = ResourceType::all();
//
//        $tmpTime2 = explode(":", $event->start_time);
//        $event->start_time = $tmpTime2[0].":".$tmpTime2[1];
//
//        $tmpTime2 = explode(":", $event->end_time);
//        $event->end_time = $tmpTime2[0].":".$tmpTime2[1];
//
//        $courses = CourseInstanceController::getCoursesAndResources($start_date, $end_date);
//
//        $defaultStartHour = Site::find($site_id)->getSiteOption(6);
//        $defaultEndHour = str_pad($businessEndHour ?? $defaultStartHour+1, 2, '0', STR_PAD_LEFT);
//        $defaultBusinessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
//        $defaultBusinessEndHour = str_pad($businessEndHour ?? $defaultBusinessBeginHour+1, 2, '0', STR_PAD_LEFT);
//
//        $locationsAndResources = CourseInstanceController::getLocationsAndResources();
//        extract($locationsAndResources);
//
//        return view('courseInstance.template.clone', compact('courses', 'locationsAndResources', 'businessBeginHour', 'businessEndHour', 'locationsAndResources', 'event', 'resources', 'resourceTypes'));
//    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = CourseTemplate::where('id', '=', $id)->with(['course', 'courseTemplateResources'])->first();
        $course_id = $template->course->id;
        $template->delete();

        return redirect()->route('all_course_templates', $template->course->id)->with('success', $template->name . trans('alerts.backend.templates.delete_success'));
    }

    //populates select for Initial Meeting Room with just type=1
    private function getIMRList() {

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

    //populates select with Resources
    private function getResourceList() {

        $site_id = Session::get('site_id');
        $resourceList = DB::table('locations')
            ->select('resources.id as resource_id', 'buildings.abbrv as building_abbrv', 'resources.abbrv as resource_abbrv', 'locations.abbrv as location_abbrv', 'resources.resource_type_id as resource_type_id')
            ->join('resources','resources.location_id','=','locations.id')
            ->join('buildings','buildings.id','=','locations.building_id')
            ->where(['locations.site_id' => $site_id, 'resources.retire_date' => NULL])
            ->orderBy('buildings.abbrv','ASC')
            ->orderBy('locations.abbrv','ASC')
            ->orderBy('resources.resource_type_id', 'ASC')
            ->orderBy('resources.abbrv','ASC')
            ->get();

        $resources = [];
        foreach($resourceList as $location) {
            $resources[$location->resource_id] = $location->building_abbrv . " " . $location->location_abbrv. " - ";
                if($location->resource_type_id == 1)
                {$resources[$location->resource_id] .= "Rooms ";}
                elseif($location->resource_type_id == 2)
                {$resources[$location->resource_id] .= "Equipment ";}
                else
                {$resources[$location->resource_id] .= "Personnel ";}

            $resources[$location->resource_id] .= " - " . $location->resource_abbrv;
        }
        return $resources;
    }

    //this is for the Type dropdown, doing here instead of pulling from database so that I can trans the text
    public function getResourceTypes($resourceId) {

        $resource = Resource::find($resourceId);

        $resourceTypes = array();
        $resourceTypes[1] = trans('labels.resources.specific');
        $resourceTypes[2] = trans('labels.resources.category');
        $resourceTypes[3] = trans('labels.resources.subcategory');

        if ($resource != null)
        {
            $resourceTypes[2] = $resource->category->abbrv;

            if ($resource->subcategory->id != null)
            {
                $resourceTypes[3] = $resource->subcategory->abbrv;
            }
            else
            {
                //this resource has no subcategory so remove option 3
                unset($resourceTypes[3]);
            }
        }

        return $resourceTypes;
    }

    public function findDefaultValuesWithResourceID($id)
    {
        $resource = Resource::find($id);

        $resourceTypes = array();
        $resourceTypes[1] = trans('labels.resources.specific');
        $resourceTypes[2] = trans('labels.resources.category');
        $resourceTypes[3] = trans('labels.resources.subcategory');

        if ($resource != null)
        {
            $resourceTypes[2] = $resource->category->abbrv;

            if ($resource->subcategory->id != null)
            {
                $resourceTypes[3] = $resource->subcategory->abbrv;
            }
            else
            {
                //this resource has no subcategory so remove option 3
                unset($resourceTypes[3]);
            }
        }

        return response()->json(['resourceTypes' => $resourceTypes]);
    }




}
