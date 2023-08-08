<?php

namespace App\Models\CourseContent\QSE;

use Illuminate\Database\Eloquent\Model;

class EventUserQSEComment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_user_qse_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_user_qse_answer_id', 'comment'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Event User QSE Answer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventUserQSEAnswer() {
        return $this->belongsTo(EventUserQSEAnswer::class);
    }
}
