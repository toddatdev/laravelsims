<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\Course;
use App\Models\Course\CourseCategoryGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseCategoryGroupRequest;
use App\Http\Requests\Course\UpdateCourseCategoryGroupRequest;
use Session;

class CourseCategoryGroupsController extends Controller
{
    public function index(Course $course)
    {
        $courseCategoryGroups = CourseCategoryGroup::orderBy('abbrv')->get()->where('site_id', Session::get('site_id'));

        return view('courses.courseCategory.index', compact('courseCategoryGroups','course'));
    }

    public function show(CourseCategoryGroup $courseCategoryGroups)
    {
        return view('courses.courseCategoryGroups.show', compact('courseCategoryGroups'));
    }

    public function create($course)
    {
        return view('courses.courseCategoryGroups.create');
    }

    public function edit(CourseCategoryGroup $courseCategoryGroup)
    {
        return view('courses.courseCategoryGroups.edit', compact('courseCategoryGroup'));
    }

    public function update(CourseCategoryGroup $courseCategoryGroup, UpdateCourseCategoryGroupRequest $request)
    {
        $url = $request->input('url');
        $courseCategoryGroup->update($request->all());
        return redirect($url);
    }


    public function store(StoreCourseCategoryGroupRequest $request)
    {
        $url = $request->input('url');
        $request['site_id'] = Session::get('site_id');
        CourseCategoryGroup::create($request->all());
        return redirect($url);
    }

    public function destroy($id)
    {
        CourseCategoryGroup::destroy($id);
        return back();
    }

}
