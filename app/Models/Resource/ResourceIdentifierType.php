<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseTemplateResource;

class ResourceIdentifierType extends Model
{
    protected $table = 'resource_identifier_types';

    protected $fillable = ['abbrv', 'display_order'];

    public function courseTemplateResources()
    {
        return $this->hasMany(CourseTemplateResource::class, 'resource_type_id');
    }
}
