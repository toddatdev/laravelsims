<?php

namespace App\Http\Controllers\Resource;

use App\Models\Resource\ResourceCategory;
use App\Models\Resource\ResourceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resource\StoreResourceCategoryRequest;
use App\Http\Requests\Resource\UpdateResourceCategoryRequest;


use Session;

class ResourceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ResourceCategory::where('site_id', Session::get('site_id'))->orderBy('abbrv')->get();
        return view('resources.resourceCategory.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $resourceTypes = ResourceType::orderBy('abbrv')->get();

        return view('resources.resourceCategory.create', compact('resourceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResourceCategoryRequest $request)
    {
        $request['site_id'] = Session::get('site_id');
        $resourceCategory = ResourceCategory::create($request->all());

        return redirect()->route('resource_category_index',[$resourceCategory])
            ->withFlashSuccess(trans('alerts.backend.resourcecategory.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ResourceCategory $resourceCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ResourceCategory $resourceCategory)
    {
        $resourceTypes = ResourceType::orderBy('abbrv')->get();

        return view('resources.resourceCategory.edit', compact('resourceCategory', 'resourceTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResourceCategoryRequest $request, ResourceCategory $resourceCategory)
    {
        $resourceCategory->update($request->all());
        return redirect()->route('resource_category_index',[$resourceCategory])
            ->withFlashSuccess(trans('alerts.backend.resourcecategory.updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($resourceCategoryId)
    {
        ResourceCategory::destroy($resourceCategoryId);
        return back();
    }
}
