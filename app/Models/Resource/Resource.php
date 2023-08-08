<?php

namespace App\Models\Resource;

use App\Models\Course\CourseTemplateResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location\Location;
use App\Models\CourseInstance\Event;
use App\Models\CourseInstance\EventResource;
use Illuminate\Support\Facades\DB;

class Resource extends Model
{
    protected $table = 'resources';

    protected $fillable = ['abbrv', 'description', 'location_id', 'resource_type_id', 'resource_category_id', 'resource_sub_category_id', 'retire_date', 'created_by', 'last_edited_by'];


    public function isRetired()
    {
        return $this->retire_date == NULL;
    }

    /**
     * Many to One Relationship to Location via location_id, use this to get the location abbrv, etc.
     * @version 1.0
     * @author mitcks
     * @date 2018-04-27
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    // Allow API to use forwards model relationship
    public function LocationToTouch() {
        return $this->location()->withoutGlobalScopes();
    }

    /**
     * Many to one Relationship to ResourceCategory via the resourse_category_id
     * @version 1.0
     * @author mitcks
     * @date 2018-04-27
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id');
    }

    /**
     * Many to one Relationship to ResourceSubCategory via the resourse_sub_category_id
     * Note the withDefault at the end is needed because this is not a required field and can be null
     * Further explained here https://laravel.com/docs/5.6/eloquent-relationships#one-to-many under Default Models heading
     * @version 1.0
     * @author mitcks
     * @date 2018-04-27
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(ResourceSubCategory::class, 'resource_sub_category_id')->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id')->withDefault();
    }

    public function eventResources()
    {
        return $this->hasMany(EventResource::class, 'resource_id');
    }

    public function templateResources()
    {
        return $this->hasMany(CourseTemplateResource::class, 'resource_id');
    }

    /**
     * CAN DELETE
     *
     * If the resource has not been used in the event_resources table then it can be deleted
     * @author John Lutz <lutzjw@upmc.edu>
     * @return boolean
     */
    public function canDelete()
    {
        return !($this->eventResources()->exists());
    }

    /**
     * Are there conflicts for a possible resource, date, time
     *
     * @param date $date
     * @param time $startTime
     * @param time $endTime
     * @return boolean
     */
    public function hasConflict($date, $startTime, $endTime)
    {
//        DB::enableQueryLog();
        $eventResource = EventResource::where('resource_id', $this->id)
            ->whereDate('start_time', '=', Carbon::parse($date)->toDateString())
            ->whereRaw( "not(date_add(`end_time`, INTERVAL teardown_time MINUTE) <= '$startTime' 
                    or date_add(`start_time`, INTERVAL - setup_time MINUTE) >= '$endTime')")
            ->whereHas('Event')
            ->get();
//        dd(DB::getQueryLog());
//        dd($eventResource);

        if($eventResource->count()>0)
        {
            return true; //return true and return, no need to look for other conflicts
        }
        else
        {
            return false;
        }

    }

    /**
     * Buttons for dataTable pages
     * @version 1.0
     * @author mitcks
     * @date 2018-05-04
     * @since 1.0.0
     * @return string
     */

    public function getEditButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                <a href="/resources/'.$this->id.'/edit" class="btn-sm editButton">
                <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
    }

    public function getDuplicateButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.duplicate') . '">
                <a href="/resources/duplicate/'.$this->id.'" class="btn-sm duplicateButton">
                <i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
    }

    public function getRetiredButtonAttribute()
    {
        if ($this->isRetired())
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.deactivate') . '">
                    <a href="/resources/deactivate/'.$this->id.'"
                    class="btn-sm btn-warning text-white"><i class="fa fa-lg fa-pause"></i></a></span>&nbsp;';
        }
        else
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.activate') . '">
                    <a href="/resources/activate/'.$this->id.'"
                    class="btn-sm btn-success"><i class="fa fa-lg fa-play"></i></a></span>&nbsp;';
        }
    }

    public function getDeleteButtonAttribute()
    {
        if ($this->canDelete())
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                <a href="/resources/deleteconfirm/'.$this->id.'"
                class="btn-sm btn-danger"><i class="fa fa-lg fa-trash"></i></a></span>&nbsp;';
        }
        else
        {
            return;
        }
    }


    public function getActionButtonsAttribute()
    {
        return  $this->getEditButtonAttribute().
                $this->getDuplicateButtonAttribute().
                $this->getRetiredButtonAttribute().
                $this->getDeleteButtonAttribute();
    }

    /**
     * One to Many Relationship to Events via initial_meeting_room
     */
    public function initial_meeting_rooms()
    {
        return $this->hasMany(Event::class, 'initial_meeting_room');
    }


}
