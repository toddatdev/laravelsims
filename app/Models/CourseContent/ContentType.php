<?php

namespace App\Models\CourseContent;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    public function CourseContent()
    {
        return $this->belongsTo(CourseContent::class, 'id', 'content_type_id');
    }
}
