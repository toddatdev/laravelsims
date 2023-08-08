<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\CourseOptions;
use App\Models\Course\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course\CourseOptions  $courseOptions
     * @return \Illuminate\Http\Response
     */
    public function show(CourseOptions $courseOptions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course\CourseOptions  $courseOptions
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseOptions $courseOptions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course\CourseOptions  $courseOptions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseOptions $courseOptions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\CourseOptions  $courseOptions
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseOptions $courseOptions)
    {
        //
    }
}
