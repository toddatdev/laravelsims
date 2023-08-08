<?php

namespace App\Models\CourseContent\QSE;

use App\Models\CourseInstance\Event;
use Illuminate\Database\Eloquent\Model;

class EventQSEActivation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_qse_activation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'qse_id', 'activation_state', 'last_edited_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function qse() {
        return $this->belongsTo(QSE::class);
    }

}
