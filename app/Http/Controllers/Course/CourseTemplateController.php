<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\CourseOption;
use App\Models\Course\CourseTemplate;
use App\Models\Course\CourseOptions;
use App\Models\Course\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon as Carbon;


class CourseTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        $templates = CourseTemplate::where('course_id', '=', $course->id)->with(['creator', 'editor'])->orderBy('name')->get();

        return view('courses.courseTemplates.index', compact('templates', 'course'));
    }

}
