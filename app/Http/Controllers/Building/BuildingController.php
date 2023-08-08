<?php

namespace App\Http\Controllers\Building;

//Get the Building database models and requests
use App\Models\Building\Building;
use App\Models\Location\Location;
use App\Models\Resource\Resource;
use App\Http\Requests\Building\StoreBuildingRequest;
use App\Http\Requests\Building\UpdateBuildingRequest;
//For the database access to pull the timzone list
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// I added this so we can get the site_id -jl 2018-03-27 11:49 
use Session;

class BuildingController extends Controller
{
    //
    public function all()
    {
    	$buildings = Building::all();
		return view('buildings.all', compact('buildings'));
	}

    public function active()
    {
        $buildings = Building::all();
        return view('buildings.active', compact('buildings'));
    }

    public function retired()
    {
        $buildings = Building::all();
        return view('buildings.retired', compact('buildings'));
    }
    public function retire($id)
    {
        $building = Building::find($id);
        $building->retire_date = \Carbon\Carbon::now(); 
        $building->update();
        // Retire any locations the building has as well. -jl 2018-12-13 15:52
        Location::where('building_id', '=', $id)->update(['retire_date' => \Carbon\Carbon::now()]);
        //Retire any resources associated with this building. 
        // Since we are a couple of joins away, and we are using a variable for the building ID,
        // we need to build the join by hand.
        Resource::join('locations', function ($join) use($id) {
                    $join->on('resources.location_id', '=', 'locations.id')
                         ->where('locations.building_id', '=', $id);
                    })
                //You need to do the getQuery here so it doesn't trigger the ambiguous updated_at update.
                ->getQuery()
                //You need to explicitly update the updated_at in resources, since it is ambiguous in the query.
                ->update(['resources.retire_date' => \Carbon\Carbon::now(),
                          'resources.updated_at'  => \Carbon\Carbon::now()]);
        // TO-DO : 
        //      - Have a pop up modal telling the user that these locations and resources will be retired as well.
        return redirect()->route('retired_buildings');
    }

    public function activate($id)
    {
        $building = Building::find($id);
        $building->retire_date = null; 
        $building->update();
        //Activate any locations the building has as well. -jl 2018-12-13 15:56
        Location::where('building_id', '=', $id)->update(['retire_date' => null]);
        //Activate any resources associated with this building. 
        // Since we are a couple of joins away, and we are using a variable for the building ID,
        // we need to build the join by hand.
        Resource::join('locations', function ($join) use($id) {
                    $join->on('resources.location_id', '=', 'locations.id')
                         ->where('locations.building_id', '=', $id);
                    })
                //You need to do the getQuery here so it doesn't trigger the ambiguous updated_at update.
                ->getQuery()
                //You need to explicitly update the updated_at in resources, since it is ambiguous in the query.
                ->update(['resources.retire_date' => null,
                          'resources.updated_at'  => \Carbon\Carbon::now()]);
        // TO-DO : 
        //      - Have a pop up modal telling the user that these locations and resources will be activated as well.

        return redirect()->route('active_buildings');
    }
	public function show($id)
	{
    	$building = Building::find($id);
    	return view('buildings.show', compact('building'));
	}
	public function create()
	{
        //create a list of timezones from the timezones table
        //This needs to be an associative array so we can put 
        //the text in the value attribute of the <select> dropdown
        $tz_table = DB::table('timezones')
                    ->where('visible', true)
                    ->orderby('tz')
                    ->pluck('tz');
        foreach ($tz_table as $time_zone) {
            $time_zones[$time_zone] = $time_zone;
        }

        return view('buildings.create', compact('time_zones'));
	}

	public function store(StoreBuildingRequest $request)
    {
        //Add the session site_id to the reqeust array -jl 2018-03-27 11:50 
        $request['site_id'] = Session::get('site_id');
        Building::create($request->all());
        return redirect()->route('active_buildings');
    }

    public function edit(Building $building)
    {
        //create a list of timezones from the timezones table
        //This needs to be an associative array so we can put 
        //the text in the value attribute of the <select> dropdown
        $tz_table = DB::table('timezones')->where('visible', true)->orderby('tz')->pluck('tz');
        foreach ($tz_table as $time_zone) {
            $time_zones[$time_zone] = $time_zone;
        }

        return view('buildings.edit', compact('building', 'time_zones'));
    }

    public function update(Building $building, UpdateBuildingRequest $request)
    {
        //Add the session site_id to the reqeust array -jl 2018-03-27 11:50 
        $request['site_id'] = Session::get('site_id');
        $building->update($request->all());
//        return redirect()->route('active_buildings');
        return redirect()->route('edit_building',[$building])
            ->with('success',trans('labels.buildings.updated_successfully'));

    }

}
