<?php

namespace App\Models\CourseContent\QSE;

use App\Models\CourseInstance\EventUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EventUserQSE extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_user_qse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_user_id', 'course_qse_id', 'complete'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function getResultAttribute()
    {
        $eventUserQSEAttemptQuestions = EventUserQSEAnswer::where('event_user_qse_id', $this->id)->whereHas('eventUserQSe', function($q) {
            $q->where('complete', 1);
        })->distinct('course_qse_question_id')->pluck('course_qse_question_id');
        $questions = QSEQuestion::where('course_qse_id', $this->qse->id)->whereIn('id', $eventUserQSEAttemptQuestions)->published()->get();

        $total = 0;
        $attempted = 0;
        $correct = 0;

        foreach ($questions as $question) {
            $total++;
            $correctAnswers = QSEQuestionAnswer::byQuestion($question->id)->correct()->pluck('id');
            $incorrectAnswers = QSEQuestionAnswer::byQuestion($question->id)->inCorrect()->pluck('id');

            $eventUserQSECorrectAnswersCount = EventUserQSEAnswer::where([
                'event_user_qse_id' => $this->id,
                'course_qse_question_id' => $question->id,
            ])->whereIn('qse_question_answer_id', $correctAnswers)->count();

            $eventUserQSEIncorrectAnswersCount = EventUserQSEAnswer::where([
                'event_user_qse_id' => $this->id,
                'course_qse_question_id' => $question->id,
            ])->whereIn('qse_question_answer_id', $incorrectAnswers)->count();

            if($correctAnswers->count() > 1) {
                Log::info('Correct Answer Check', [$eventUserQSECorrectAnswersCount, $correctAnswers->count()]);
                if ($eventUserQSECorrectAnswersCount === $correctAnswers->count() && $eventUserQSEIncorrectAnswersCount === 0) {
                    $correct++;
                }
                $attempted++;
            } elseif ($eventUserQSECorrectAnswersCount > 0) {
                $correct++;
                $attempted++;
            } elseif($eventUserQSEIncorrectAnswersCount > 0) {
                $attempted++;
            }
        }

        return [
            'total' => $total,
            'attempted' => $attempted,
            'correct' => $correct
        ];
    }

    public function scopeComplete($query){
        return $query->where('complete', 1);
    }

    public function scopeIncomplete($query){
        return $query->where('complete', 0);
    }

    public function getCreatedAtTzAttribute() {
        return $this->created_at->setTimezone(session('timezone'));
    }

    public function getFormattedCreatedAtTzAttribute() {
        return \Carbon\Carbon::parse($this->created_at)->timezone(session('timezone'))->format('m/d/y g:i A')."<i class='ml-3 mr-1 fas fa-clock text-muted'></i>" .$this->created_at->diffForHumans();
    }

    /**
     * QSE
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qse(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QSE::class, 'course_qse_id');
    }

    /**
     * Event User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EventUser::class);
    }

    public function eventUserQSEAnswers()
    {
        return $this->hasMany(EventUserQSEAnswer::class, 'event_user_qse_id');
    }
}
