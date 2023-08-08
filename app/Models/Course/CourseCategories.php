<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class CourseCategories extends Model
{
    protected $table = 'course_categories';

    protected $fillable = ['abbrv', 'description', 'course_category_group_id', 'site_id'];

    //each category belongs to a category group
    public function CourseCategoryGroup()
    {
        return $this->belongsTo(CourseCategoryGroup::class);
    }
}
