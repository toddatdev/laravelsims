<?php

namespace App\Models\CourseContent;

use Illuminate\Database\Eloquent\Model;

class ViewerType extends Model
{
    public function courseContent()
    {
        return $this->belongsTo(CourseContent::class, 'viewer_type_id', 'id')->orderBy('display_order');
    }
}
