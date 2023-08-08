<?php

namespace App\Models\CourseContent\QSE;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QSEAnswerType extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qse_answer_types';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByName', function (Builder $builder) {
            $builder->orderBy('abbrv');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'abbrv',
        'description',
        'has_response',
        'input_type_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * QSE Questions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qseQuestions() {
        return $this->hasMany(QSEQuestion::class);
    }

    /**
     * Input Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inputType() {
        return $this->belongsTo(InputType::class);
    }
}
