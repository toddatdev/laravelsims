<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseOptions;

class CourseOption extends Model
{
    protected $table = 'course_option';

    protected $fillable = ['course_id', 'option_id', 'value'];

    public function courseOptions()
    {
        return $this->hasOne(CourseOptions::class, 'id', 'option_id');
    }

}
