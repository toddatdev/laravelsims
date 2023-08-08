<?php

namespace App\Http\Controllers\Location;

//Get the Location database models and requests
use App\Models\Location\Location;
use App\Models\Building\Building;

use App\Models\Site\Site;

use App\Models\Access\User\User;

use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
//For the database access to pull the timzone list
use App\Models\Location\LocationScope;
use App\Models\Resource\Resource;
use App\Models\CourseInstance\EventResource;
use App\Models\CourseInstance\Event;
use App\Models\Resource\ResourceType;
use Illuminate\Support\Facades\DB;


//To get the site_id from the session -jl 2018-03-26 17:38 
use Session; 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LocationController extends Controller
{
    public function all()
    {
    	$locations = Location::all();
		return view('locations.all', compact('locations'));
	}

	public function show($id)
	{
    	$location = Location::find($id);
    	return view('locations.show', compact('location'));
	}

    public function schedulers($id)
    {
        $location = Location::find($id);
        $schedulers = DB::table('location_schedulers')
                    ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->where('location_id', $id)
                    ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                    ->get();

        $users = User::whereNotExists(function ($query) use ($id) {
            $query->select(DB::raw(1))
                    ->from('location_schedulers')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->whereRaw('users.id = location_schedulers.user_id')
                    ->whereRaw('location_schedulers.location_id = '. $id);
            })
            ->get();

        return view('locations.schedulers', compact('location', 'schedulers', 'users'));
    }

   public function viewScheduler($id)
    {
        $user = User::find($id);

        $buildings = Building::orderby('display_order')->whereNull('retire_date')->get();

        return view('locations.viewscheduler', compact('user', 'buildings'));
    }

    //need to think about how to restrict this to only people from the locations site.
    public function removeScheduler($locationId, $userId)
    {
        DB::table('location_schedulers')
            ->where("location_id", '=', $locationId)
            ->where('user_id',     '=', $userId)
            ->delete();
 
        $location = Location::find($locationId);

        $schedulers = DB::table('location_schedulers')
                    ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->where('location_id', $locationId)
                    ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                    ->get();

        $users = User::whereNotExists(function ($query) use ($locationId) {
            $query->select(DB::raw(1))
                    ->from('location_schedulers')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->whereRaw('users.id = location_schedulers.user_id')
                    ->whereRaw('location_schedulers.location_id = '. $locationId);
            })
            ->get();

        return view('locations.schedulers', compact('location', 'schedulers', 'users'));
   }

    //need to think about how to restrict this to only people from the locations site.
    public function removeAllScheduler($userId)
    {
        DB::table('location_schedulers')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                        ->from('locations')
                        ->whereRaw('locations.id = location_schedulers.location_id');
            })
            ->where('user_id', '=', $userId)
            ->delete();
 
       return redirect()->route('view_scheduler', [$userId])->withFlashSuccess("Removed from all Locations");
//       return view('view_scheduler', compact('userId'));
    }
    //need to think about how to restrict this to only people from the locations site.
    public function addScheduler($locationId, $userId)
    {
        DB::table('location_schedulers')
            ->insert(['location_id' => $locationId, 'user_id' => $userId]); 
        $location = Location::find($locationId);

        $schedulers = DB::table('location_schedulers')
                    ->join('users',     'location_schedulers.user_id',     '=', 'users.id')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->where('location_id', $locationId)
                    ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                    ->get();

        $users = User::whereNotExists(function ($query) use ($locationId) {
            $query->select(DB::raw(1))
                    ->from('location_schedulers')
                    ->join('locations', 'location_schedulers.location_id', '=', 'locations.id')
                    ->whereRaw('users.id = location_schedulers.user_id')
                    ->whereRaw('location_schedulers.location_id = '. $locationId);
            })
            ->get();

        return view('locations.schedulers', compact('location', 'schedulers', 'users'));
   }
    public function active()
    {
        $locations =  Location::whereNull('retire_date')
                            ->get();
        return view('locations.active', compact('locations'));
      
	}

    public function retired()
    {
        $locations =  Location::whereNotNull('retire_date')
                            ->get();
        return view('locations.retired', compact('locations'));
      
	}


    public function retireLocation($id)
    {
     	$location = Location::find($id);
     	//set retire_date to now
     	$location->retire_date = \Carbon\Carbon::now();
        $location->update();
        return redirect()->route('retired_locations');
    }

    public function activateLocation($id)
    {
     	$location = Location::find($id);
        //set retire_date to null
     	$location->retire_date = null;
        $location->update();     	
        return redirect()->route('active_locations');
    }

	public function create()
    {
        // create a list of buildings from the Buildings table
        // This needs to be an associative array so we can put 
        // the building ID in the value attribute of the <select> dropdown
        // -jl 2018-03-27 9:51 
        $building_table = DB::table('buildings')
        			->where('retire_date', null)
                    ->where('site_id', Session::get('site_id'))
        			->orderby('abbrv')
        			->get();
        foreach ($building_table as $building) {
            $buildings[$building->id] = $building->abbrv;
        }

        return view('locations.create', compact('buildings'));
    }

	public function edit(Location $location)
    {
        //create a list of buildings from the Buildings table
        //This needs to be an associative array so we can put 
        //the building ID in the value attribute of the <select> dropdown
        // -jl 2018-03-27 9:51 
        $building_table = DB::table('buildings')
        			->where('retire_date', null)
                    ->where('site_id', Session::get('site_id'))
        			->orderby('abbrv')
        			->get();
        foreach ($building_table as $building) {
            $buildings[$building->id] = $building->abbrv;
        }

        return view('locations.edit', compact('location', 'buildings'));
    }

	public function store(StoreLocationRequest $request)
    {
        //Add the session site_id to the reqeust array -jl 2018-03-27 11:50 
        $request['site_id'] = Session::get('site_id');
        Location::create($request->all());
        return redirect()->route('active_locations');
    }

    public function update(Location $location, UpdateLocationRequest $request)
    {
        //Add the session site_id to the reqeust array -jl 2018-03-27 11:50 
        $request['site_id'] = Session::get('site_id');
        $location->update($request->all());
        return redirect()->route('active_locations');
    }



    /**
     * DayPilot Scheduling Grid Rows
     *
     * Contains locations and rooms (children) for a DayPilot tree format
     *
     * @since 2018-08-30 (mitcks)
     * @param
     * @return string formatted for DayPilot grid resource tree
     */
    public function getLocationsAndResources()
    {

        $businessBeginHour = Site::find(Session::get('site_id'))->getSiteOption(6);
        $businessEndHour = Site::find(Session::get('site_id'))->getSiteOption(7);

        $buildings = Building::orderBy('display_order')->get(['abbrv', 'id']);
        $allResourceLocations = Resource::groupBy('location_id')->get(['location_id']);

        //For grid tree
        $locationsAndResources = "[";

        foreach ($buildings as $building) {

            //only display locations if there are resources
            $locations = Location::wherein('id', $allResourceLocations)->where('building_id', "=", $building->id)->orderBy('display_order')->get(['abbrv', 'id']);

            foreach ($locations as $location) {

                $locationsAndResources .= "{\"id\":\"Location-" . $location->id . "\",\"name\":\"" . $building->abbrv . " " . $location->abbrv . "\", expanded: true, \"children\":[";

                //only display resource types if there are resources
                $allResourcesTypes = Resource::where('location_id', '=', $location->id)->groupBy('resource_type_id')->get(['resource_type_id']);
                $resourceTypes = ResourceType::wherein('id', $allResourcesTypes)->orderBy('display_order')->get(['abbrv', 'id']);

                foreach ($resourceTypes as $resourceType) {

                    $resources = Resource::where('location_id', '=', $location->id)->where('resource_type_id', '=', $resourceType->id)->get(['abbrv', 'id']);

                    $locationsAndResources .= "{\"id\":\"Type-" . $resourceType->id . "\",\"name\":\"" . $resourceType->abbrv . "\", expanded: true, \"children\":[";

                    foreach ($resources as $resource) {
                        $locationsAndResources .= "{\"id\":\"" . $resource->id . "\",\"bubbleHtml: \":\"" . $resource->abbrv . "\",\"name\":\"" . $resource->abbrv . "\"},";
                    }

                    $locationsAndResources .= "]},";

                }

                $locationsAndResources .= "]},";

            }

        }

        $locationsAndResources .= "]";

        //For existing events in grid

        $events = Event::whereDate('start_time', '<=', '2018-11-05')->with('eventResources')->get();

        //$eventResources = EventResource::get();
        $eventText = "[";
        foreach ($events as $event) {

            foreach ($event->eventResources as $eventResource) {
                $eventText .= $eventResource->getResourceEventGrid();
            }

        }
        $eventText .= "]";


        return view('classes.calendar', compact('locationsAndResources', 'businessBeginHour', 'businessEndHour', 'eventText'));

    }
}
