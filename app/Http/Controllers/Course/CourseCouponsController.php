<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\CourseCoupons;
use Illuminate\Http\Request;
use App\DataTables\CourseCouponsDataTable;
use App\DataTables\CourseCouponsDataTableEditor;
use Auth;
use Carbon\Carbon;
use Session;

class CourseCouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CourseCouponsDataTable $dataTable, $course_id)
    {
        //Put course_id in Session Variable to use in the create validation rule
        session(['course_id' => $course_id]);

        //course_id passed as parameter to filter to coupons for this course
        return $dataTable->with('course_id', $course_id)->render('courses.courseCoupons.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CourseCouponsDataTableEditor $editor)
    {
        return $editor->process(request());
    }



}
