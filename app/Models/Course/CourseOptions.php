<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class CourseOptions extends Model
{
    protected $table = 'course_options';

    protected $fillable = ['description'];
}
