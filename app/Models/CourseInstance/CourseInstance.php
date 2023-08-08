<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\SoftDeletes;


class CourseInstance extends Model
{
    protected $table = 'course_instances';

    use SoftDeletes;

    protected $fillable = ['course_id', 'color', 'created_by', 'last_edited_by'];

    //Here is the "scope" section to limit it to just this Site's course instances. Defined in CourseInstanceScope.php
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CourseInstanceScope);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function Course()
    {
        return $this->belongsTo(Course::class);
    }

    public function CourseTrashed()
    {
        return $this->Course()->withoutGlobalScopes();
    }

    // Allow API to use forwards model relationship
    public function CourseToTouch() {
        return $this->Course()->withoutGlobalScopes();
     }

     //return true if this courseInstance has more than one event
     public function hasRecurrence()
     {
        $numEvents = Event::where('course_instance_id', $this->id)->get()->count();

        if($numEvents > 1)
        {
            return true;
        }
        else
        {
            return false;
        }
     }

    /**
     * Get a string of all dates added as part of the same recurrence group
     *
     * @return string
     */
    public function getRecurrenceDatesAttribute()
    {
        $dates = null;

        $courseInstanceEvents = Event::where('course_instance_id', $this->id)
            ->orderby('start_time')
            ->get();

        $i = 0;
        foreach($courseInstanceEvents as $event){
            $i++;
            if ($i == 1)
            {
                $dates = $event->DisplayStartDateShort;
            }
            else
            {
                $dates = $dates . ', ' . $event->DisplayStartDateShort;
            }
        }

        return $dates;
    }

}
