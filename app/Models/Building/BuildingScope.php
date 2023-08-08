<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Session;

class BuildingScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('buildings.site_id', '=', Session::get('site_id'));
    }
}

