<?php

namespace App\Models\CourseContent\QSE;

use Illuminate\Database\Eloquent\Model;

class QSEQuestionAnswer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qse_question_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qse_question_id',
        'text',
        'display_order',
        'correct',
        'feedback',
        'created_by',
        'last_edited_by',
        'retired_by',
        'retired_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function scopeByQuestion($query, $id) {
        return $query->where('qse_question_id', $id)->orderBy('display_order');
    }

    public function scopeCorrect($query) {
        return $query->where('correct', 1);
    }

    public function scopeIncorrect($query) {
        return $query->where('correct', 0);
    }

    /**
     * QSE Question
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qseQuestion()
    {
        return $this->belongsTo(QSEQuestion::class);
    }

    public function eventUserQSEAnswers() {
        return $this->hasMany(EventUserQSEAnswer::class, 'qse_question_answer_id');
    }
}
