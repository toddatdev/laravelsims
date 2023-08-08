<?php

namespace App\Http\Controllers\CourseInstance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CourseInstance\EventResource;


class EventResourceController extends Controller
{

    protected $table = 'event_resources';

    protected $fillable = ['event_id', 'resource_id', 'start_time', 'end_time', 'setup_time',
        'teardown_time', 'conflict_ignored', 'created_by', 'last_edited_by'];


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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    // eventResources table data
    public function eventResourcesTableData($EventID)
    {
        $eventResources = EventResource::where('event_id', '=', $EventID) ->with(['Resources' => function ($q) {
            $q->orderBy('resource_type_id', 'asc');
        }]);

        return DataTables::of($eventResources)
            ->make(true);
    }
}
