<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Course\Course;
use Session;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursesTableController extends Controller
{

    public function getIndex()
    {
        return view('coursetables.index');
    }


    // all data
    public function allData()
    {
        $courses = Course::get();

        return DataTables::of($courses)
            ->addColumn('actions', function($course) {
                return $course->action_buttons; //defined in getActionButtonsAttribute in the Course Model
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    // active data
    public function activeData(Request $request)
    {
        //uses active scope
        $courses = Course::active()->get();

        // Gets the select ID from "Back To Course" Btn to jsut build DataTable w/ the row
        if ($request->get('id')) {
            $id = $request->get('id');
            $courses = Course::where('id','=', $id)->get();
        }
       
        return DataTables::of($courses)
            ->addColumn('actions', function($course) {
                return $course->action_buttons; //defined in getActionButtonsAttribute in the Course Model
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }


    // inactive data
    public function inactiveData()
    {
        //uses retired scope
        $courses = Course::retired()->get();

        return DataTables::of($courses)
            ->addColumn('actions', function($course) {
                return $course->action_buttons; //defined in getActionButtonsAttribute in the Course Model
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }


    // courses/catalog - get all the courses
    public function catalogData(Request $request)
    {

        // this query checks for hide this course on catalog option id = 5
        $courses = Course::whereNotExists(function($query)
                    {
                    $query->select(DB::raw(1))
                        ->from('course_option')
                        ->whereRaw('course_option.course_id = courses.id')
                        ->where('course_option.option_id', 5);
                    })
                    ->whereNull('retire_date')
                    ->orderBy('abbrv');

                    // check for our 'Catalog Filter' dropdown click sort
                    if ($sort = $request->get('sort')) {
                        $courses->whereExists(function($query) use ($sort)
                        {
                        $query->select(DB::raw(1))
                            ->from('course_category')
                            ->whereRaw('course_category.course_id = courses.id')
                            ->where('course_category.course_category_id', $sort);
                        });
                    }

        $courses->get();

        return DataTables::of($courses)
            ->addColumn('name', function ($course) {
                return $course->name;
            })
            ->addColumn('actions', function($course) {
                return $course->getCatalogActionButtonsAttribute();
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    public function myCoursesData(Request $request)
    {

        $myCourses = Course::selectRaw('courses.id, courses.name as "name", courses.abbrv as "abbrv"')
            ->join('role_user', 'role_user.course_id', '=', 'courses.id')
            ->join('roles',     'roles.id',            '=', 'role_user.role_id')
            ->whereNull('courses.retire_date')
            ->where('role_user.user_id', Auth::user()->id)
            ->distinct();

        $eventCourses = Course::selectRaw('courses.id, courses.name as "name", courses.abbrv as "abbrv"')
            ->join('course_instances', 'course_instances.course_id', '=', 'courses.id')
            ->join('events',           'events.course_instance_id',  '=', 'course_instances.id')
            ->join('event_user',       'event_user.event_id',        '=', 'events.id')
            ->whereNull('event_user.deleted_at')
            ->whereNull('events.deleted_at')
            ->where('event_user.user_id', Auth::user()->id)
            ->distinct();

        $myCourses->union($eventCourses)->distinct()->orderBy("name")->get();

      return DataTables::of($myCourses)
            //Add all of the roles (course and event) that this person has with this course.
            ->addColumn('role_name', function($coursesEvent) {
                return($coursesEvent->getRoles());
            })
            ->addColumn('actions', function($coursesEvent) {
                return $coursesEvent->getCatalogActionButtonsAttribute();
            })
            ->rawColumns(['actions'])
            ->make(true);

    }

}
