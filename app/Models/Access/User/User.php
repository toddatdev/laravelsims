<?php

namespace App\Models\Access\User;

use App\Models\CourseInstance\EventUser;
use App\models\location\LocationSchedulers;
use Illuminate\Notifications\Notifiable;
use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use App\Models\Access\User\Traits\UserSendPasswordReset; // Default
use App\Notifications\Frontend\Auth\PasswordReset; // Custom
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;

use App\Models\Site\Site;
use App\Models\Access\Role\Role;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use UserScope,
        UserAccess,
        Notifiable,
        SoftDeletes,
        UserAttribute,
        UserRelationship;
        // UserSendPasswordReset;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'middle_name', 'phone', 'email', 'password', 'status', 'confirmation_code', 'confirmed', 'image'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name', 'name'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.users_table');
    }

    //This defines the relationship from user to sites via the user_sites table -jl 2018-03-28 11:11
    public function sites()
    {
        return $this->belongsToMany(Site::class, 'user_sites', 'user_id', 'site_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function siteRoles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->where('course_id', null);
    }

    public function eventUsers()
    {
        return $this->hasMany(EventUser::class);
    }

    public function schedulingLocations()
    {
        return $this->hasMany(LocationSchedulers::class);
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new PasswordReset($token));
    }
}
