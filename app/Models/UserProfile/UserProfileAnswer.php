<?php
namespace App\Models\userProfile;
use Illuminate\Database\Eloquent\Model;
use App\Models\userProfile\UserProfile;
use App\Models\userProfile\UserProfileQuestion;
class UserProfileAnswer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    protected $primaryKey = 'user_profile_answer_id';
    
    protected $table = 'user_profile_answers';
    protected $fillable = ['question_id', 'parent_id', 'answer_text', 'comment_needed', 'display_order','retire_date'];
    /**
     * @var array
     */
    protected $guarded = ['*'];
    /**
    * Get the profile associated with user profile answer 
    */
    public function user_profile()
    {
    	return $this->belongsTo(UserProfile::class,'user_profile_answer_id');
    }
    /**
    * Get the question associated with user profile answer 
    */
    public function user_profile_question()
    {
    	return $this->belongsTo(UserProfileQuestion::class,'question_id','question_id');
    }
}