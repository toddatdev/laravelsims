<?php

namespace App\Models\userProfile;

use Illuminate\Database\Eloquent\Model;
use App\Models\userProfile\UserProfileAnswer;
use App\Models\Access\User\User;

class UserProfile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_profile';

    protected $primaryKey = 'user_profile_id';
    

    protected $fillable = ['user_id','user_profile_answer_id','comment'];

    /**
     * @var array
     */
    protected $guarded = ['*'];

    /**
    * Get the answer associated with user profile
    */
    public function user_profile_answer()
    {
    	return $this->belongsTo(UserProfileAnswer::class,'user_profile_answer_id','user_profile_answer_id');
    }

    /**
    * Get the user id associated with user profile
    */
    public function user()
    {
    	return $this->belongsTo(User::class,'id','user_id');
    }
}