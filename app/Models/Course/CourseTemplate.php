<?php

namespace App\Models\Course;

use App\Models\Resource\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Access\User\User;
use Carbon\Carbon as Carbon;

class CourseTemplate extends Model
{
    protected $table = 'course_templates';
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['name', 'event_abbrv', 'class_size', 'public_comments', 'internal_comments', 'expectations',
        'start_time', 'end_time', 'setup_time', 'teardown_time', 'fac_report', 'fac_leave',
        'color', 'initial_meeting_room', 'initial_meeting_room_type', 'created_by',
        'last_edited_by', 'course_id', 'sims_spec_needed', 'special_requirements'];

    protected $appends = ['action_buttons'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone(session('timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone(session('timezone'))->toDateTimeString();
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class,'last_edited_by');
    }

    public function courseTemplateResources() {
        return $this->hasMany(CourseTemplateResource::class);
    }

    public function courseTemplateLocation() {
        $this->initial_meeting_room;
        return $this->hasMany(CourseTemplateResource::class);
    }

    public function initialMeetingRoomType()
    {
        return $this->hasOne(ResourceIdentifierType::class, 'initial_meeting_room_type');
    }

    /**
     * One to Many Relationship to Resources to Get the Initial Meeting Room Abbrv
     */
    public function InitialMeetingRoom()
    {
        return $this->belongsTo(Resource::class, 'initial_meeting_room');
    }

    /**
     * Initial Meeting Room With Building and Location for Display
     *
     * @return string
     */
    public function getDisplayIMRAttribute()
    {
        return $this->initialMeetingRoom->location->building->abbrv . ' ' . $this->initialMeetingRoom->location->abbrv . ' ' . $this->initialMeetingRoom->abbrv;
    }

    /**
     * Template Resources for Display
     *
     * @param int $typeId - defaults to 1=room, other possible values 2=equipment, 3=Personnel
     * @return string
     */
    public function getResources($typeId = 1)
    {
        $resources = Resource::whereHas('templateResources', function($query) {
            $query->where('course_template_id', $this->id);
        })
            ->where('resource_type_id', $typeId)
            ->orderBy('abbrv')->get();

        $i = 0;
        $templateResources = "";
        foreach($resources as $templateResource){
            $i++;
            if ($i == 1)
            {
                $templateResources = $templateResource->abbrv;
            }
            else
            {
                $templateResources = $templateResources . ", " . $templateResource->abbrv;
            }
        }
        return $templateResources;
    }

    public function getEditButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                <a href="/courseInstance/template/edit/' . $this->id . '" class="btn-sm editButton">
                <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
    }

    public function getCloneButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.duplicate') . '">
                <a href="/courseInstance/template/duplicateTemplate/' . $this->id . '" class="btn-sm duplicateButton">
                <i class="fa fa-clone fa-fw"></i></a></span>&nbsp;';
    }

    public function getExportButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.export') . '">
                <a href="/courseInstance/template/exportTemplate/' . $this->id . '" class="btn-sm orangeButton">
                <i class="fas fa-file-export fa-fw"></i></a></span>&nbsp;';
    }

    public function getDeleteButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete') . '">
                <a href="/courseInstance/template/delete/' . $this->id . '" 
                name="delete_template" id="delete_template" 
                class="btn-sm deleteButton" data-templatename="'. $this->name. '">
                <i class="fa fa-trash fa-fw"></i></a></span>&nbsp;';
    }

    public function getActionButtonsAttribute()
    {
        return $this->getEditButtonAttribute().
            $this->getCloneButtonAttribute().
            $this->getExportButtonAttribute().
            $this->getDeleteButtonAttribute();
    }
}
