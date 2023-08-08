<?php

namespace App\Models\Access\Role\Traits\Relationship;

/**
 * Class RoleRelationship.
 */
trait RoleRelationship
{
    /**
     * @return mixed
     */
    public function users()
    {
        //return $this->belongsToMany(config('auth.providers.users.model'), config('access.role_user_table'), 'role_id', 'user_id');
        //ksm 2018_08-13 the line above stopped working correctly after Laravel upgrade to version 5.6, changed to below

        return $this->belongsToMany(Users::class, 'role_user', 'role_id', 'user_id');

    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('access.permission'), config('access.permission_role_table'), 'role_id', 'permission_id')
            ->orderBy('display_name', 'asc');
    }
}
