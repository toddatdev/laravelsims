<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\CourseFeeTypes;
use Illuminate\Http\Request;
use App\DataTables\CourseFeeTypesDataTable;
use App\DataTables\CourseFeeTypesDataTableEditor;
use Auth;
use Carbon\Carbon;


class CourseFeeTypesController extends Controller
{
    //Built from example in this tutorial: https://yajrabox.com/docs/laravel-datatables/8.0/editor-tutorial

    /**
     * Display a listing of the resource.
     *
     */
    public function index(CourseFeeTypesDataTable $dataTable)
    {
        return $dataTable->render('courses.courseFees.feeTypes.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CourseFeeTypesDataTableEditor $editor)
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
        $courseFeeType = CourseFeeTypes::find($request->fee_type_id);
        $action = $request->action; //turn_off or turn_on

        if($action == "turn_off")
        {
            //set retire_date
            $courseFeeType->retire_date = Carbon::now();
        }
        else
        {
            //clear retire_date
            $courseFeeType->retire_date = null;
        }

        $courseFeeType->last_edited_by = $user->id;
        $courseFeeType->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }
}
