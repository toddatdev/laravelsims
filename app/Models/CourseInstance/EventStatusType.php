<?php

namespace App\Models\CourseInstance;

use App\Models\Resource\Resource;
use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;

class EventStatusType extends Model
{
    protected $table = 'event_status_types';

    protected $fillable = ['id', 'site_id', 'description', 'icon', 'html_color'];

    public function events()
    {
        return $this->hasMany(Event::class, 'status_type_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class,'id','site_id');
    }
}
