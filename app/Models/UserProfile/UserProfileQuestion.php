<?php

namespace App\Models\userProfile;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site\Site;
use App\Models\userProfile\UserProfileAnswer;

class UserProfileQuestion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_profile_questions';
    protected $primaryKey = 'question_id';
    protected $fillable = ['question_text', 'site_id', 'response_type','display_order','retire_date'];

    /**
     * @var array
     */
    protected $guarded = ['*'];


    /**
    * Get the site id associated with the question
    */
    public function site()
    {
    	return $this->belongsTo(Site::class,'id','site_id');
    }

    /**
    * Get the answer associated with the question
    */
    public function user_profile_answer()
    {
    	return $this->hasMany(UserProfileAnswer::class,'question_id');
    }
}
