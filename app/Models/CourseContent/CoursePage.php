<?php

namespace App\Models\CourseContent;

use Illuminate\Database\Eloquent\Model;

class CoursePage extends Model
{
    protected $table = 'course_pages';

    protected $fillable = [
        'locked_by',
        'text'
    ];

    protected $touches = ['courseContent'];

    public function courseContent()
    {
        return $this->belongsTo(CourseContent::class,'course_contents_id', 'id');
    }
}
