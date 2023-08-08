<?php

namespace App\Models\CourseContent\QSE;

use Illuminate\Database\Eloquent\Model;

class EventUserQSEAnswer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_user_qse_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_user_qse_id', 'course_qse_question_id', 'qse_question_answer_id', 'evaluatee_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * QSE Question
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qseQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QSEQuestion::class, 'course_qse_question_id');
    }

    /**
     * Event User QSE
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventUserQSE(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EventUserQSE::class, 'event_user_qse_id');
    }

    /**
     * QSE Question Answer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qseQuestionAnswer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QSEQuestionAnswer::class);
    }

    public function eventUserQSEComment() {
        return $this->hasOne(EventUserQSEComment::class, 'event_user_qse_answer_id');
    }
}