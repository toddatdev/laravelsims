<?php

namespace App\Models\Course;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\CourseTemplate;
use App\Models\Resource\ResourceType;
use App\Models\Resource\Resource;


class CourseTemplateResource extends Model
{
    protected $table = 'course_template_resources';

    protected $fillable = ['course_template_id', 'start_time', 'end_time', 'setup_time',
        'teardown_time', 'last_edited_by', 'resource_id', 'resource_identifier_type',
        'created_by', 'isIMR'];

    public function courseTemplate()
    {
        return $this->belongsTo(CourseTemplate::class);
    }

    public function Resources()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    //mitcks 6/4/19 - TODO: I'm not sure what this is used for? Maybe it can be removed
    public function resourceIdentifierType()
    {
        return $this->hasOne(ResourceIdentifierType::class, 'id', 'resource_identifier_type');
    }

    //mitcks 6/4/19 - TODO: I'm not sure what this is used for? Maybe it can be removed
    public function resourceType()
    {
        return $this->hasOne(ResourceType::class, 'id', 'resource_type_id');
    }

    /**
     * start time minus setup time
     *
     * @return string
     */
    public function getStartTimeLessSetupAttribute()
    {
        return Carbon::parse($this->start_time)->addMinutes(0-$this->setup_time)->toTimeString();
    }

    /**
     * end time plus teardown time
     *
     * @return string
     */
    public function getEndTimePlusTeardownAttribute()
    {
        return Carbon::parse($this->end_time)->addMinutes($this->teardown_time)->toTimeString();
    }
}
