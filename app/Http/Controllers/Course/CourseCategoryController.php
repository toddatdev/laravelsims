<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course\CourseCategory;

class CourseCategoryController extends Controller
{
    public function store($courseId, $categoryId)
    {
        $courseCategory = new CourseCategory;
        $courseCategory->course_id = $courseId;
        $courseCategory->course_category_id = $categoryId;
        $courseCategory->save();
        return back();
    }

    public function destroy($id)
    {
        CourseCategory::destroy($id);
        return back();
    }
}
