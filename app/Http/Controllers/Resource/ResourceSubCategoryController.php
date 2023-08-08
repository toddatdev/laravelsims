<?php

namespace App\Http\Controllers\Resource;

use App\Models\Resource\ResourceSubCategory;
use App\Models\Resource\ResourceCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resource\StoreResourceSubCategoryRequest;
use App\Http\Requests\Resource\UpdateResourceSubCategoryRequest;

class ResourceSubCategoryController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($categoryId)
    {

        $resourceCategory = ResourceCategory::get()->where('id', $categoryId)->first();

        return view('resources.resourceSubCategory.create', compact('resourceCategory'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResourceSubCategoryRequest $request, $categoryId)
    {
        $resourceCategory = ResourceCategory::get()->where('id', $categoryId)->first();

        $request['resource_category_id'] = $resourceCategory->id;

        $resourceSubCategory = ResourceSubCategory::create($request->all());

        return redirect()->route('resource_category_index',[$resourceSubCategory])
            ->withFlashSuccess(trans('alerts.backend.resourcesubcategory.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource\ResourceSubCategory  $resourceSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ResourceSubCategory $resourceSubCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource\ResourceSubCategory  $resourceSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ResourceSubCategory $resourceSubCategory)
    {
        return view('resources.resourceSubCategory.edit', compact('resourceSubCategory'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource\ResourceSubCategory  $resourceSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResourceSubCategoryRequest $request, ResourceSubCategory $resourceSubCategory)
    {
        $resourceSubCategory->update($request->all());
        return redirect()->route('resource_category_index',[$resourceSubCategory])
            ->withFlashSuccess(trans('alerts.backend.resourcesubcategory.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource\ResourceSubCategory  $resourceSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($resourceSubCategoryId)
    {
        ResourceSubCategory::destroy($resourceSubCategoryId);
        return back();
    }
}
