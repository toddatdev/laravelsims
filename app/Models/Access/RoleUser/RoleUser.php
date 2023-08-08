<?php

namespace App\Models\Access\RoleUser;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    public $timestamps = false;

    protected $fillable = ['user_id', 'role_id', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Access\Role\Role');
    }
}
