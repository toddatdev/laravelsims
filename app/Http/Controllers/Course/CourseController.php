<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\Course;
use App\Models\CourseInstance\CourseInstance;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventUser;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Access\Permission\Permission;
use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use Illuminate\Http\Request;
use App\Models\Course\CourseCategories;
use Illuminate\Support\Facades\DB;
// Bringing in Course Emails Model
use App\Models\Course\CourseEmails;
use App\Models\Site\SiteEmails;
use Auth;
use Storage;
use Session;


class CourseController extends Controller
{

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }


    public function all()
    {
        return view('courses.all');
    }

    public function active()
    {
        return view('courses.active');
    }

    public function deactivated()
    {
        return view('courses.deactivated');
    }

    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    public function deactivateCourse(Course $course)
    {
        //set retire_date to now
        $course->update(['retire_date' => \Carbon\Carbon::now()]);
        return redirect()->route('deactivated_courses');
    }

    public function activateCourse(Course $course)
    {
        //set retire_date to null
        $course->update(['retire_date' => NULL]);
        return redirect()->route('active_courses');
    }

    //View to create a course
    public function create()
    {
        return view('courses.create');
    }

    //Create new course
    public function store(StoreCourseRequest $request)
    {
        $user = Auth::user();
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;
        $request['site_id'] = Session::get('site_id');

        $course = Course::create($request->all());

        //create parking lot course_instance
        $courseInstance = CourseInstance::create(
            [   'course_id'      => $course->id,
                'created_by'     => $user->id,
                'last_edited_by' => $user->id
            ]
        );

        //create parking lot event
        $event = Event::create(
            [   'course_instance_id'    => $courseInstance->id,
                'class_size'            => 100000,
                'internal_comments'     => 'Parking Lot Event',
                'start_time'            => '1900-01-01 01:00:00',
                'end_time'              => '1900-01-01 02:00:00',
                'parking_lot'           => 1,
                'created_by'            => $user->id,
                'last_edited_by'        => $user->id
            ]
        );

        //if image was uploaded store path
        if( $request->hasFile('image'))
        {
            $this->storeImage($course, $request);
        }

        // Load all emails into course
        // Get Table Data
        $site_email_table_data = SiteEmails::select('site_emails.*')
            ->join('email_types', 'email_types.id', '=', 'site_emails.email_type_id')
            ->whereIn('email_types.type', [2,3]) //course and event emails.
            ->get();

        // Populate Course Emails table
        foreach ($site_email_table_data as $site_email) {
            CourseEmails::firstOrCreate(
                ['label' => $site_email->label, 'course_id' => $course->id], // values to check
                [ // Data
                    'course_id'      => $course->id, // Active Course ID
                    'email_type_id'  => $site_email->email_type_id,
                    'label'          => $site_email->label,
                    'subject'        => $site_email->subject,
                    'body'           => $site_email->body,
                    'to_roles'       => $site_email->to_roles,
                    'to_other'       => $site_email->to_other,
                    'cc_roles'       => $site_email->cc_roles,
                    'cc_other'       => $site_email->cc_other,
                    'bcc_roles'      => $site_email->bcc_roles,
                    'bcc_other'      => $site_email->bcc_other,
                    'time_amount'    => $site_email->time_amount,
                    'time_type'      => $site_email->time_type,
                    'time_offset'    => $site_email->time_offset,
                    'role_id'        => $site_email->role_id,
                    'role_amount'    => $site_email->role_amount,
                    'role_offset'    => $site_email->role_offset,
                    'created_by'     => $site_email->created_by,
                    'last_edited_by' => $user->id, // User that made the update
                ]
            );
        }   


        return redirect()->route('edit_course',[$course])
            ->with('success','Course created successfully.');
    }

    //View to edit a course
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    //Update a course
    public function update(Course $course, UpdateCourseRequest $request)
    {
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;

        $course->update($request->all());

        // Get Table Data
        $site_email_table_data = SiteEmails::select('site_emails.*')
            ->join('email_types', 'email_types.id', '=', 'site_emails.email_type_id')
            ->whereIn('email_types.type', [2,3]) //course and event emails.
            ->get();

        // Populate Course Emails table
        foreach ($site_email_table_data as $site_email) {
            CourseEmails::firstOrCreate(
                ['label' => $site_email->label, 'course_id' => $course->id], // values to check
                [ // Data
                    'course_id'      => $course->id, // Active Course ID
                    'email_type_id'  => $site_email->email_type_id,
                    'label'          => $site_email->label,
                    'subject'        => $site_email->subject,
                    'body'           => $site_email->body,
                    'to_roles'       => $site_email->to_roles,
                    'to_other'       => $site_email->to_other,
                    'cc_roles'       => $site_email->cc_roles,
                    'cc_other'       => $site_email->cc_other,
                    'bcc_roles'      => $site_email->bcc_roles,
                    'bcc_other'      => $site_email->bcc_other,
                    'time_amount'    => $site_email->time_amount,
                    'time_type'      => $site_email->time_type,
                    'time_offset'    => $site_email->time_offset,
                    'role_id'        => $site_email->role_id,
                    'role_amount'    => $site_email->role_amount,
                    'role_offset'    => $site_email->role_offset,
                    'created_by'     => $site_email->created_by,
                    'last_edited_by' => $user->id, // User that made the update
                ]
            );
        }     

        return redirect()->route('edit_course',[$course])
            ->with('success','Course edited successfully.');
    }

    //Store new image in S3
    public function storeImage($course, $request)
    {
        if( $request->hasFile('image'))
        {

            $image = $request->file('image');
            $imageName = 'site-'.Session::get('site_id').'/CourseCatalogImages/'.$course->id.'.'.$image->getClientOriginalExtension();

            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);

            $course->update(['catalog_image' => $imageName]);

        }
    }

    //View to upload a course catalog image
    public function imageUpload(Course $course)
    {
        return view('courses.upload-image', compact('course'));
    }

    //Upload course catalog image from the view
    public function imageUploadCourse(Request $request, Course $course)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //remove image file from s3
        $imageNameToRemove = strstr($course->catalog_image, '/site-');
        Storage::disk('s3')->delete($imageNameToRemove);

        $imageName = 'site-'.Session::get('site_id').'/CourseCatalogImages/'.$course->id.'.'.$request->image->getClientOriginalExtension();
        $image = $request->file('image');
        $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
        $imageName = Storage::disk('s3')->url($imageName);

        $course->update(['catalog_image' => $imageName]);

        return redirect()->route('edit_course',[$course])
            ->with('success','Image uploaded successfully.')
            ->with('path',$imageName);
    }


    //View to delete a course catalog image
    public function imageDelete(Course $course)
    {
        return view('courses.delete-image', compact('course'));
    }

    //Delete the course catalog image from the course
    public function imageDeleteCourse(Request $request, Course $course)
    {
        //remove image file from s3
        $imageNameToRemove = strstr($course->catalog_image, '/site-');
        Storage::disk('s3')->delete($imageNameToRemove);

        //set catalog_image field to null
        $course->update(['catalog_image' => NULL]);

        return redirect()->route('edit_course',[$course])
            ->with('success','Image removed successfully.');

    }


    // users courses - /courses
    public function myCourses()
    {
        // build the dropdown filter via course categories abbrv 'Catalog Filter'
        $courseFilter = CourseCategories::whereHas('CourseCategoryGroup', function ($query) {
            $query->where('abbrv','Catalog Filter')->where('site_id', SESSION::get('site_id'));
        })->orderBy('abbrv')->pluck('abbrv', 'id')->toArray();

        $courses = Course::orderBy('abbrv')->get();

        //get count of pending requests for badge icon (for approver)
        $countEnrollmentRequests = EventUser::MyWaitlistRequests()->count();

        return view('courses.courses', compact('courses', 'courseFilter', 'countEnrollmentRequests'));
    }


    // view all courses - /courses/catalog
    public function catalog()
    {
        // build the dropdown filter via course categories abbrv 'Catalog Filter'
        $courseFilter = CourseCategories::whereHas('CourseCategoryGroup', function ($query) {
            $query->where('abbrv','Catalog Filter')->where('site_id', SESSION::get('site_id'));
        })->orderBy('abbrv')->pluck('abbrv', 'id')->toArray();

        $courses = Course::orderBy('abbrv')->get();

        return view('courses.catalog', compact('courses', 'courseFilter'));
    }

    
    // view single course
    public function catalogShow(Course $course)
    {
        $user = Auth::user();
    
        return view('courses.catalogShow', compact('course'));
    }

    // make parking lot events for courses without them (for current site)
    // route to execute /courses/makeParkingLotEvents (requires manage-courses permission)
    // should only need to run this once per site, after that parking lot events will be created upon course creation
    public function makeParkingLotEvents()
    {
        $user = Auth::user();

        //select Courses (will be limited by site via global scope)
        $courses = Course::all();

        $countUpdatedEvents = 0;

        //loop through and add parking lot event if one does not exist
        foreach ($courses as $course) {

            $events = Event::with('CourseInstance')
                ->where('parking_lot', '=', 1)
                ->whereHas('CourseInstance', function($q) use($course){
                    $q->where('course_id', '=', $course->id);
                })->get();

            if($events->isEmpty()){
                //no parking lot exists yet so create one

                //create parking lot course_instance
                $courseInstance = CourseInstance::create(
                    [   'course_id'      => $course->id,
                        'created_by'     => $user->id,
                        'last_edited_by' => $user->id
                    ]
                );

                //create parking lot event
                $event = Event::create(
                    [   'course_instance_id'    => $courseInstance->id,
                        'class_size'            => 100000,
                        'internal_comments'     => 'Parking Lot Event',
                        'start_time'            => '1900-01-01 01:00:00',
                        'end_time'              => '1900-01-01 02:00:00',
                        'parking_lot'           => 1,
                        'created_by'            => $user->id,
                        'last_edited_by'        => $user->id
                    ]
                );

                $countUpdatedEvents++;

            }

        }

        return redirect()->route('frontend.user.dashboard')
            ->with('success', $countUpdatedEvents . ' Parking Lot Events Created');

    }


}
