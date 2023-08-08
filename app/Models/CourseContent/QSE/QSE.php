<?php

namespace App\Models\CourseContent\QSE;

use App\Models\CourseContent\CourseContent;
use Illuminate\Database\Eloquent\Model;

class QSE extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_content_id',
        'qse_type_id',
        'activation_type_id',
        'activation_state',
        'minutes',
        'automatic_activation_time',
        'elevator_type_id',
        'evaluatee_type_id',
        'presentation_type_id',
        'allow_multiple_submits',
        'random',
        'threshold',
        'feedback_type_id',
        'created_by',
        'last_edited_by',
        'instructions',
        'evaluatee_roles'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function scopeByCourseContent($query, $id) {
        return $query->where('course_content_id', $id);
    }

    public function scopePublished($query) {
        return $query->whereHas('courseContent', function ($q) {
            return $q->whereNotNull('published_date');
        });
    }

    public function scopeActive($query) {
        return $query->whereHas('courseContent', function ($q) {
            return $q->whereNull('retired_date');
        });
    }

    /**
     * QSE Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qseType()
    {
        return $this->belongsTo(QSEType::class);
    }

    /**
     * Activation Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activationType()
    {
        return $this->belongsTo(ActivationType::class);
    }

    /**
     * Evaluator Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluatorType()
    {
        return $this->belongsTo(EvaluatorType::class);
    }

    /**
     * Evaluatee Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluateeType()
    {
        return $this->belongsTo(EvaluateeType::class);
    }

    /**
     * Presentation Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presentationType()
    {
        return $this->belongsTo(PresentationType::class);
    }

    /**
     * Feedback Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feedbackType()
    {
        return $this->belongsTo(FeedbackType::class);
    }

    /**
     * Course Contents
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //TODO (mitcks): after Ansar corrects the publish process so that there is only one record per QSE
    // in course_contents, then this relationship should be corrected to join on course_contents_id
    // instead of menu_id
    public function courseContents()
    {
        return $this->belongsTo(CourseContent::class,'course_content_id','menu_id')
            ->where('published_date','<>', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qseQuestions()
    {
        return $this->hasMany(QSEQuestion::class, 'course_qse_id');
    }

    public function eventUserQSE() {
        return $this->hasMany(EventUserQSE::class, 'course_qse_id');
    }

    public function eventQSEActivations() {
        return $this->hasMany(EventQSEActivation::class, 'qse_id');
    }

    public function isExistsForEventIn($eventId) {
        return $this->eventQSEActivations()->where('event_id', $eventId)->exists();
    }

    public function eventQSEActivationState($eventId) {
        $eventQSEActivation = $this->eventQSEActivations()->where('event_id', $eventId)->first();
        return $eventQSEActivation->activation_state;
    }
}
