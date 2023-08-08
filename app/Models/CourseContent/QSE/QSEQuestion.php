<?php

namespace App\Models\CourseContent\QSE;

use Illuminate\Database\Eloquent\Model;

class QSEQuestion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qse_questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_qse_id',
        'text',
        'display_order',
        'answer_type_id',
        'required',
        'likert_caption',
        'created_by',
        'last_edited_by',
        'published_by',
        'publish_date',
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

    public function scopeByQSE($query, $id, $random = false){
        $query = $query->where('course_qse_id', $id);

        if ($random) {
            $query = $query->inRandomOrder();
        } else {
            $query = $query->orderBy('display_order');
        }

        return $query;
    }

    public function scopePublished($query){
        return $query->whereNotNull('publish_date');
    }

    public function scopeActive($query){
        return $query->whereNull('retired_date');
    }

    /**
     * QSE Answer Types
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qseAnswerTypes(){
        return $this->hasMany(QSEAnswerType::class, 'course_qse_id');
    }

    /**
     * QSE Question Answers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qseQuestionAnswers()
    {
        return $this->hasMany(QSEQuestionAnswer::class, 'qse_question_id');
    }

    /**
     * QSE
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qse(){
        return $this->belongsTo(QSE::class, 'course_qse_id');
    }
}
