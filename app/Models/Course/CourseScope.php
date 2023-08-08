<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Session;

class CourseScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('courses.site_id', '=', Session::get('site_id'));
    }
}