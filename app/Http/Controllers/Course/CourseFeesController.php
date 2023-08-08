<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\CourseFees;
use App\Models\Course\CourseFeeTypes;
use Illuminate\Http\Request;
use App\DataTables\CourseFeesDataTable;
use App\DataTables\CourseFeesDataTableEditor;
use Auth;
use Carbon\Carbon;
use Session;

class CourseFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CourseFeesDataTable $dataTable, $course_id)
    {
        //this is for the fee types dropdown
        $courseFeeTypes = CourseFeeTypes::first();
        if(!empty($courseFeeTypes))
        {
            session(['course_types_select' => $courseFeeTypes->getSelectList()]);
        }

        //Put course_id in Session Variable to use in the create validation rule
        session(['course_id' => $course_id]);

        //course_id passed as parameter to filter to coupons for this course
        return $dataTable->with('course_id', $course_id)->render('courses.courseFees.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CourseFeesDataTableEditor $editor)
    {
        return $editor->process(request());
    }

    /**
     * Update Activation (aka retire/un-retire)
     *
     */
    public function updateActivation(Request $request)
    {
        // logged in user - not the user to update
        $user = Auth::user();

        //record to be updated
        $courseFee = CourseFees::find($request->fee_type_id);
        $action = $request->action; //turn_off or turn_on

        if($action == "turn_off")
        {
            //set retire_date
            $courseFee->retire_date = Carbon::now();
        }
        else
        {
            //clear retire_date
            $courseFee->retire_date = null;
        }

        $courseFee->last_edited_by = $user->id;
        $courseFee->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }


}
