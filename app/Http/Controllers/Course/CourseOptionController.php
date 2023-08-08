<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\CourseOption;
use App\Models\Course\CourseOptions;
use App\Models\Course\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CourseOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        $courseOptions = CourseOptions::orderBy('description', 'asc')->get();
        $courseOption = CourseOption::where('course_id', $course->id)->get();
        return view('courses.courseOptions.index', compact('courseOptions', 'courseOption', 'course'));
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

        //loop through the possibler options to see what might have been selected/deselected
        $courseOptions = CourseOptions::get();

        foreach ($courseOptions as $courseOption) {

            if($request->filled($courseOption->id))
            {
                //dd($request->input($courseOption->id));

                if ($courseOption->input_type_id == 1) //checkbox
                {
                    $courseOption = CourseOption::updateOrCreate(
                        ['course_id' => $request['course_id'], 'option_id' => $courseOption->id],
                        ['value' => '1']
                    );
                }
                elseif ($courseOption->input_type_id == 8 || $courseOption->input_type_id == 10)
                {
                    if($request->input($courseOption->id.'BA')=='B')
                    {
                        $value = 0 - $request->input($courseOption->id);
                    }
                    elseif($request->input($courseOption->id.'BA')=='A')
                    {
                        $value = $request->input($courseOption->id);
                    }
                    $courseOption = CourseOption::updateOrCreate(
                        ['course_id' => $request['course_id'], 'option_id' => $courseOption->id],
                        ['value' => $value]
                    );
                }
                else //color, number, minutes
                {
                    $courseOption = CourseOption::updateOrCreate(
                        ['course_id' => $request['course_id'], 'option_id' => $courseOption->id],
                        ['value' => $request->input($courseOption->id)]
                    );
                }
            }
            else
            {
                //this option has no value, so delete
                $courseOption = CourseOption::where('course_id', $request['course_id'])
                                ->where('option_id', $courseOption->id)
                                ->delete();
            }

        }


        return redirect()->route('all_course_options',$request['course_id'])
            ->with('success',trans('labels.courseOptions.updated_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course\CourseOption  $courseOption
     * @return \Illuminate\Http\Response
     */
    public function show(CourseOption $courseOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course\CourseOption  $courseOption
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseOption $courseOption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course\CourseOption  $courseOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseOption $courseOption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\CourseOption  $courseOption
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CourseOption::destroy($id);
        return back();
    }


}
