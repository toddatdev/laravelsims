<?php

namespace App\Models\CourseInstance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resource\Resource;
use Illuminate\Database\Eloquent\SoftDeletes;


class EventResource extends Model
{
    protected $table = 'event_resources';

    use SoftDeletes;

    protected $fillable = ['event_id', 'resource_id', 'start_time', 'end_time', 'setup_time', 'teardown_time', 'isIMR', 'conflict_ignored', 'created_by', 'last_edited_by'];

    /**
     * Many to One Relationship to Event via event_id, use this to get color, etc.
     * @version 1.0
     * @author mitcks
     * @date 2018-09-28
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Event()
    {
        return $this->belongsTo(Event::class);
    }

    public function Resources()
    {
        return $this->belongsTo(Resource::class, 'resource_id')->orderBy('abbrv');
    }

    /**
     * start time minus setup time
     *
     * @return string
     */
    public function getStartTimeLessSetupAttribute()
    {
        return Carbon::parse($this->start_time)->addMinutes(0-$this->setup_time);
    }

    /**
     * end time plus teardown time
     *
     * @return string
     */
    public function getEndTimePlusTeardownAttribute()
    {
        return Carbon::parse($this->end_time)->addMinutes($this->teardown_time);
    }

    /**
     * DayPilot Scheduling Grid Rows
     *
     * Contains scheduled resources by event for the grid
     *
     * @since 2018-09-29 (mitcks)
     * @param
     * @return string formatted for DayPilot grid resource tree
     */
    public function getResourceEventGrid()
    {

        $eventResourceForGrid = "{\"id\": \"" . $this->id . "\", ";
        $eventResourceForGrid .= "\"start\": \"" . $this->start_time . "\", ";
        $eventResourceForGrid .= "\"end\": \"" . $this->end_time . "\", ";
        $eventResourceForGrid .= "\"resource\": \"" . $this->resource_id . "\", ";
        $eventResourceForGrid .= "\"barColor\": \"" . $this->Event->color . "\", ";
        $eventResourceForGrid .= "\"text\": \"" . addcslashes($this->Event->CourseAbbrvEventAbbrv, '\"') . "\", ";
        $eventResourceForGrid .= "\"bubbleHtml\": \"" . "<strong>" . addcslashes($this->Event->CourseInstance->Course->name, '\"') . "</strong>" .
            "<br><br><strong>". trans('labels.event.public_notes') .":</strong><br>" . addcslashes($this->Event->public_comments, '\"').
            "<br><br><strong>". trans('labels.event.internal_notes') .":</strong><br>" . addcslashes($this->Event->internal_comments, '\"'). "\"},";

        return $eventResourceForGrid;

    }

}
