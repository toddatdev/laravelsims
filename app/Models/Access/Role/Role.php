<?php

namespace App\Models\Access\Role;

use App\Models\CourseInstance\EventUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\Access\Role\Traits\RoleAccess;
use App\Models\Access\Role\Traits\Scope\RoleScope;
use App\Models\Access\Role\Traits\Attribute\RoleAttribute;
use App\Models\Access\Role\Traits\Relationship\RoleRelationship;
use App\Models\Access\User\User;
use App\Models\Access\Permission\Permission;

use Illuminate\Database\Eloquent\Builder;
use Session;
/**
 * Class Role.
 */
class Role extends Model
{
    use RoleScope,
        RoleAccess,
        RoleAttribute,
        RoleRelationship;

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
    // Added site_id -jl 2018-03-23 12:32
    protected $fillable = ['name', 'all', 'sort', 'site_id', 'learner'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.roles_table');
    }

    // ////////////////////////////////////////
    // //Here is the "scope" section to limit it to just this Site's roles. -jl 2018-03-23 12:31
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('site_id', function (Builder $builder){
            $builder->where('roles.site_id', Session::get('site_id'));
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * This was added after upgrading to Laravel 5.6 to fix issue with not being able to access user's roles
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->orderBy('display_name', 'asc');
    }

    public function eventUsers() {
        return $this->hasMany(EventUser::class);
    }
}
