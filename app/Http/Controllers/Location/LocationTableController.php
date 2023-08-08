<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Location\Location;
use Session;

use App\Models\Access\User\User;

//For the database access to pull the timzone list
use Illuminate\Support\Facades\DB;
class LocationTableController extends Controller
{
    public function all()
    {
        $locations =  Location::join('buildings', 'buildings.id', '=', 'locations.building_id')
                        ->leftJoin('location_schedulers', 'locations.id', '=', 'location_schedulers.location_id')
                        ->select('locations.*', 'buildings.abbrv as building_abbrv', DB::raw('count(location_schedulers.user_id) as scheduler_cnt'))
                        ->groupBy('locations.id', 'building_abbrv')
                        ->get();

         return DataTables::of($locations)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($location) {
                return $location->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model

            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    public function active()
    {
        $locations =  Location::whereNull('locations.retire_date')
						->join('buildings', 'buildings.id', '=', 'locations.building_id')
                        ->leftJoin('location_schedulers', 'locations.id', '=', 'location_schedulers.location_id')
						->select('locations.*', 'buildings.abbrv as building_abbrv', DB::raw('count(location_schedulers.user_id) as scheduler_cnt'))
                        ->groupBy('locations.id', 'building_abbrv')
			            ->get();

    
         return DataTables::of($locations)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($location) {
                return $location->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model

            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
 
    }
    public function retired()
    {
        $locations =  Location::whereNotNull('locations.retire_date')
                        ->join('buildings', 'buildings.id', '=', 'locations.building_id')
                        ->leftJoin('location_schedulers', 'locations.id', '=', 'location_schedulers.location_id')
                        ->select('locations.*', 'buildings.abbrv as building_abbrv', DB::raw('count(location_schedulers.user_id) as scheduler_cnt'))
                        ->groupBy('locations.id', 'building_abbrv')
                        ->get();
    
         return DataTables::of($locations)
            //Put in the edit column with the pencil icon
            ->addColumn('actions', function($location) {
                return $location->action_buttons; //defined in getActionButtonsAttribute in the Buildings Model

            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
 
    }

    //Get the list of schdedulers for the specified Location ID
    public function schedulers($id)
    {
        $location = Location::find($id);
        $schedulers = DB::table('location_schedulers')
                    ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->where('location_id', $id)
                    ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                    ->get();
        return DataTables::of($schedulers)
                        ->make(true);
    }

    public function viewScheduler($id)
    {
       $locations = DB::table('location_schedulers')
                    ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->join('buildings', 'locations.building_id',           '=', 'buildings.id')
                    ->where('location_id', $id)
                    ->select('buildings.abbrv as building', 'locations.abbrv as location')
                    ->orderBy('buildings.abbrv', 'asc')
                    ->orderBy('locations.abbrv', 'asc')
                    ->get();
        return DataTables::of($locations)
                        ->make(true);
 
    }
    //Lists people with 'scheduling' permission that are not schedulers for the location with $locationID -jl 2019-07-11 15:50
    public function availableSchedulers($locationID)
    {
        $users = DB::table('users')
                    ->select('users.id', 'users.last_name', 'users.first_name', 'users.email')
                    ->join('role_user',       'role_user.user_id',       '=', 'users.id')
                    ->join('permission_role', 'permission_role.role_id', '=', 'role_user.role_id')
                    ->join('permissions',     'permissions.id',          '=', 'permission_role.permission_id')
                    ->join('roles',           'roles.id',                '=', 'role_user.role_id')
                    ->where('permissions.name', 'scheduling')
                    ->where('roles.site_id',     SESSION::get('site_id'))
                    ->whereRaw('user_id not in (select user_id from location_schedulers where `location_schedulers`.location_id = '.$locationID.')')
                    ->orderBy('users.last_name')
                    ->orderBy('users.first_name')
                    ->get();
        return DataTables::of($users)->make(true);
    }

    public function test()
    {
        $users = User::active(1)
                 ->where('users.id', '>', '3'); //Put your SQL after the User::active line.
        
        return DataTables::of($users)->make(true);
    }

    public function building($id)
    {
        return Location::find($id)->building->site;
    }
 
}