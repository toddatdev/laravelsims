<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Session;

class ScheduleRequestScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('course', function($query){
            $query->where('site_id', SESSION::get('site_id'));
        });
    }
}