<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Session;

class LocationScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('locations.site_id', '=', Session::get('site_id'));
    }
}

