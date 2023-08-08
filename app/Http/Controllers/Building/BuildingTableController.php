<?php

namespace App\Http\Controllers\Building;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Building\Building;

class BuildingTableController extends Controller
{

    public function getIndex()
    {
        return view('buildingtables.index');
    }

    public function all()
    {
         $buildings = Building::all()
                    ->orderby('abbrv');

         return DataTables::of($buildings)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($building) {
                return $building->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    public function active()
    {
        $buildings =  Building::where('retire_date', null)
                                ->orderby('abbrv')
                                ->get();
         
         return DataTables::of($buildings)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($building) {
                return $building->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model

            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

   public function retired()
    {
        $buildings =  Building::where('retire_date', '<>', null)
                                ->orderby('abbrv')
                                ->get();
         
         return DataTables::of($buildings)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($building) {
                return $building->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $buildings = Building::all();

        return DataTables::of($buildings)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($building) {
                return $building->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model

            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    public function buildingLocations($id)
    {
        return Building::find($id)->locations;
    } 
 
}