<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Session;

class EventScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('CourseInstance', function($query){
            $query->join('courses', 'courses.id', '=', 'course_instances.course_id')
            	  ->where('courses.site_id', SESSION::get('site_id'));
        });
    }
}