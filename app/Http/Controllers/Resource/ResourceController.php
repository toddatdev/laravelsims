<?php

namespace App\Http\Controllers\Resource;

use App\Models\Resource\Resource;
use App\Models\Location\Location;
use App\Models\Resource\ResourceType;
use App\Models\Resource\ResourceCategory;
use App\Models\Resource\ResourceSubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use DB;
use App\Http\Requests\Resource\StoreResourceRequest;
use App\Http\Requests\Resource\UpdateResourceRequest;



/**
 * Class ResourceController
 * @package App\Http\Controllers\Resource
 */
class ResourceController extends Controller
{
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
     * Display all resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $resources = Resource::orderBy('abbrv')->get();
        return view('resources.all', compact('resources'));
    }

    /**
     * Display active resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function active()
    {
        $resources = Resource::orderBy('abbrv')->get();
        return view('resources.active', compact('resources'));
    }

    /**
     * Display deactivated resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function deactivated()
    {
        $resources = Resource::orderBy('abbrv')->get();
        return view('resources.deactivated', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* These populate the select dropdowns in the form */
        $locations = Location::get()->sortBy('building_location_label')->pluck('building_location_label', 'id');
        $categories = ResourceCategory::where('site_id', Session::get('site_id'))->orderBy('abbrv')->pluck('abbrv', 'id');
        $resourceTypes = ResourceType::orderBy('abbrv')->get();

        //empty until category selected
        $subcategories = ResourceSubCategory::where('resource_category_id',0)
            ->pluck('abbrv', 'id');

        return view('resources.create', compact('locations', 'categories', 'subcategories', 'resourceTypes'));
    }

    /**
     * Show the form for duplicating the specified resource.
     *
     * @param  \App\Models\Resource\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Resource $resource)
    {
        /* These populate the select dropdowns in the form, the prepend is so that a value is not set by default */
        $locations = Location::get()->sortBy('building_location_label')->pluck('building_location_label', 'id');
        $categories = ResourceCategory::where('site_id', Session::get('site_id'))->orderBy('abbrv')->pluck('abbrv', 'id');
        $subcategories = ResourceSubCategory::orderBy('abbrv')
            ->where('resource_category_id',$resource->resource_category_id)
            ->pluck('abbrv', 'id');
        $subcategories->prepend('Select...', '0');

        $resourceTypes = ResourceType::orderBy('abbrv')->get();

        return view('resources.duplicate', compact('resource', 'locations', 'categories', 'subcategories', 'resourceTypes'));

    }

    /**
     * Creates the subcategory select list onchange of the category list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcategories($id)
    {

        $subcategories = ResourceSubCategory::orderBy('abbrv')->where('resource_category_id', $id)->pluck('abbrv','id');

        return json_encode($subcategories);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResourceRequest $request)
    {
        //dd($request->all()); //debug

        $user = Auth::user();
        $request['created_by'] = $user->id;
        $request['last_edited_by'] = $user->id;

        //if nothing selected in drop down change to null
        if ( $request['resource_sub_category_id'] == "Select..." || $request['resource_sub_category_id'] == "0" ){
            $request['resource_sub_category_id'] = null;
        }

        $resource = Resource::create($request->all());

        return redirect()->route('all_resources',[$resource])
            ->withFlashSuccess(trans('alerts.backend.resources.created', ['ResourceName'=>$resource->abbrv . ' (' . $resource->location->building->abbrv . ' ' . $resource->location->abbrv . ')', 'ResourceID'=>$resource->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        /* These populate the select dropdowns in the form, the prepend is so that a value is not set by default */
        $locations = Location::get()->sortBy('building_location_label')->pluck('building_location_label', 'id');
        $categories = ResourceCategory::where('site_id', Session::get('site_id'))->orderBy('abbrv')->pluck('abbrv', 'id');
        $subcategories = ResourceSubCategory::orderBy('abbrv')
            ->where('resource_category_id',$resource->resource_category_id)
            ->pluck('abbrv', 'id');
        $subcategories->prepend('Select...', '0');

        $resourceTypes = ResourceType::orderBy('abbrv')->get();

        return view('resources.edit', compact('resource', 'locations', 'categories', 'subcategories', 'resourceTypes'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $user = Auth::user();
        $request['last_edited_by'] = $user->id;

//       dd($request->all()); //debug

        //If nothing selected in drop down change to null
        if ( $request['resource_sub_category_id'] == "Select..." || $request['resource_sub_category_id'] == "0" ){
            $request['resource_sub_category_id'] = null;
        }

        $resource->update($request->all());

        return redirect()->route('all_resources',[$resource])
            ->withFlashSuccess(trans('alerts.backend.resources.updated'));

    }

    /**
     * Delete confirmation page
     * @return \Illuminate\Http\Response
     */
    public function deleteconfirm(Resource $resource)
    {
        //dd($resource->all()); //debug

        return view('resources.deleteconfirm', compact('resource'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Resource::destroy($id);
        return redirect()->route('active_resources',[$id])
            ->withFlashSuccess(trans('alerts.backend.resources.deleted'));
    }

    /**
     * Deactivate the specified resource (aka retire)
     *
     * @param Resource $resource
     * @return mixed
     */
    public function deactivate(Resource $resource)
    {
        //set retire_date to now
        $resource->update(['retire_date' => \Carbon\Carbon::now()]);

        return redirect()->route('deactivated_resources',[$resource])
            ->withFlashSuccess(trans('alerts.backend.resources.deactivated'));
    }

    /**
     * Activate the specified resource
     *
     * @param Resource $resource
     * @return mixed
     */
    public function activate(Resource $resource)
    {
        //set retire_date to null
        $resource->update(['retire_date' => NULL]);
        return redirect()->route('active_resources',[$resource])
            ->withFlashSuccess(trans('alerts.backend.resources.activated'));
    }


}
