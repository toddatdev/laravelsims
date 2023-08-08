<?php

namespace App\Http\Controllers\Course;

use App\Models\Course\CourseCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course\CourseCategoryGroup;
use App\Http\Requests\Course\StoreCourseCategoriesRequest;
use App\Http\Requests\Course\UpdateCourseCategoriesRequest;
use Session;

class CourseCategoriesController extends Controller
{
    public function create($group)
    {
        $courseCategoryGroup = CourseCategoryGroup::get()->where('id', $group)->first();

        return view('courses.courseCategory.create', compact('courseCategoryGroup'));
    }

    public function show(CourseCategories $courseCategories)
    {
        return view('courses.courseCategory.show', compact('courseCategories'));
    }

    public function storeCategories(StoreCourseCategoriesRequest $request, $group)
    {
        $url = $request->input('url');

        $request['site_id'] = Session::get('site_id');
        $request['course_category_group_id'] = $group;

        CourseCategories::create($request->all());

        return redirect($url);
    }


    public function edit(CourseCategories $courseCategories)
    {
        return view('courses.courseCategory.edit', compact('courseCategories'));
    }


    public function update(CourseCategories $courseCategories, UpdateCourseCategoriesRequest $request)
    {
        $url = $request->input('url');
        $courseCategories->update($request->all());
        return redirect($url);
    }

    public function destroy($id)
    {
        CourseCategories::destroy($id);
        return back();
    }

}
