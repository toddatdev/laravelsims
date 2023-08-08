<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $table = 'course_category';

    protected $fillable = ['course_id', 'course_category_id'];

}
