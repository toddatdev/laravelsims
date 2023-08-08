<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    protected $table = 'resource_types';

    protected $fillable = ['id', 'abbrv', 'display_order'];

    public function resources()
    {
        return $this->hasMany(Resource::class, 'resource_type_id');
    }
}
