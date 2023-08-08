<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseTemplateResource;

class ResourceIdentifierType extends Model
{
    protected $table = 'resource_identifier_types';

    protected $fillable = ['abbv'];

    public function courseTemplateResource()
    {
        return $this->hasMany(CourseTemplateResource::class);
    }

    public function courseTemplate()
    {
        return $this->belongsToMany(CourseTemplate::class);
    }
}
