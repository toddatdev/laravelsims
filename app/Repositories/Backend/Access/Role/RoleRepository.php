<?php

namespace App\Repositories\Backend\Access\Role;

use App\Models\Access\Role\Role;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\Role\RoleCreated;
use App\Events\Backend\Access\Role\RoleDeleted;
use App\Events\Backend\Access\Role\RoleUpdated;

// I added this to allow us to add the site_id. -jl 2018-03-27 9:23 
use Session;
use App\Models\Site\Site;
/**
 * Class RoleRepository.
 */
class RoleRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Role::class;

    /**
     * @param string $order_by
     * @param string $sort
     *
     * @return mixed
     */
    public function getAll($order_by = 'sort', $sort = 'asc')
    {
        return $this->query()
            ->with('users', 'permissions')
            //Limit it so that there are NO "All Access Administrator" roles available to the end users.
            //This hides the "Administrator" checkbox on the User Management screen -jl 2018-04-30 17:21
            ->where('roles.all', '!=', '1')
            ->orderBy($order_by, $sort)
            ->get();
    }

    public function getRoles($roleType)
    {
        $getRolesQuery = $this->query()
            ->with('users', 'permissions')
            ->whereExists(function ($query) use ($roleType){
                $query->select(DB::raw(1))
                    ->from('permission_role')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    ->whereRaw('permission_role.role_id = roles.id')
                    ->where('permission_type_id', $roleType);
            })
            ->where('all', '!=', 1)
            ->orderBy('name')
            ->get();

            $getRoles = [];
            foreach($getRolesQuery as $roleItem) {
                $getRoles[$roleItem->id] = $roleItem->name;
            }
            return $getRoles;
    }

    /**
     * @return mixed
     */
    public function getForDataTable($roleType)
    {
 
        return $this->query()
            ->with('users')
            ->with(array('permissions' => function($query) use ($roleType) {
                $query->where('permission_type_id', $roleType);
            }))
            ->whereExists(function ($query) use ($roleType){
                $query->select(DB::raw(1))
                    ->from('permission_role')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    ->whereRaw('permission_role.role_id = roles.id')
                    ->where('permission_type_id', $roleType);
            })
            ->where('all', '!=', 1);
    }

    /**
     * @param array $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function create(array $input)
    {
        if ($this->query()->where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.access.roles.already_exists'));
        }

        //See if the role has all access
        //$all = $input['associated-permissions'] == 'all' ? true : false;
        $all = false;

        if (! isset($input['permissions'])) {
            $input['permissions'] = [];
        }

        //This config is only required if all is false
        if (! $all) {
            //See if the role must contain a permission as per config
            if (config('access.roles.role_must_contain_permission') && count($input['permissions']) == 0) {
                throw new GeneralException(trans('exceptions.backend.access.roles.needs_permission'));
            }
        }

        DB::transaction(function () use ($input, $all) {
            $role = self::MODEL;
            $role = new $role();
            $role->name = $input['name'];
            // I added this -jl 2018-03-26 16:28 
            $role->site_id = Session::get('site_id');

            $role->sort = isset($input['sort']) && strlen($input['sort']) > 0 && is_numeric($input['sort']) ? (int) $input['sort'] : 0;

            //See if this role has all permissions and set the flag on the role
            $role->all = $all;

            $role->learner = $input['learner'];

            if ($role->save()) {
                if (! $all) {
                    $permissions = [];

                    if (is_array($input['permissions']) && count($input['permissions'])) {
                        foreach ($input['permissions'] as $perm) {
                            if (is_numeric($perm)) {
                                array_push($permissions, $perm);
                            }
                        }
                    }

                    $role->attachPermissions($permissions);
                }

                event(new RoleCreated($role));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.roles.create_error'));
        });
    }

    /**
     * @param Model $role
     * @param  $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update(Model $role, array $input)
    {
        //See if the role has all access, administrator always has all access
        if ($role->id == 1) {
            $all = true;
        } else {
            $all = false;
        }

        if (! isset($input['permissions'])) {
            $input['permissions'] = [];
        }

        //This config is only required if all is false
        if (! $all) {
            //See if the role must contain a permission as per config
            if (config('access.roles.role_must_contain_permission') && count($input['permissions']) == 0) {
                throw new GeneralException(trans('exceptions.backend.access.roles.needs_permission'));
            }
        }

        $role->name = $input['name'];
        $role->sort = isset($input['sort']) && strlen($input['sort']) > 0 && is_numeric($input['sort']) ? (int) $input['sort'] : 0;
        $role->learner = $input['learner'];

        //See if this role has all permissions and set the flag on the role
        $role->all = $all;

        DB::transaction(function () use ($role, $input, $all) {
            if ($role->save()) {
                //If role has all access detach all permissions because they're not needed
                if ($all) {
                    $role->permissions()->sync([]);
                } else {
                    //Remove all roles first
                    $role->permissions()->sync([]);

                    //Attach permissions if the role does not have all access
                    $permissions = [];

                    if (is_array($input['permissions']) && count($input['permissions'])) {
                        foreach ($input['permissions'] as $perm) {
                            if (is_numeric($perm)) {
                                array_push($permissions, $perm);
                            }
                        }
                    }

                    $role->attachPermissions($permissions);
                }

                event(new RoleUpdated($role));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.roles.update_error'));
        });
    }

    /**
     * @param Model $role
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $role)
    {
        //Would be stupid to delete the administrator role
        if ($role->id == 1) { //id is 1 because of the seeder
            throw new GeneralException(trans('exceptions.backend.access.roles.cant_delete_admin'));
        }

        //Don't delete the role is there are users associated
        if ($role->users()->count() > 0) {
            throw new GeneralException(trans('exceptions.backend.access.roles.has_users'));
        }

        DB::transaction(function () use ($role) {
            //Detach all associated roles
            $role->permissions()->sync([]);

            if ($role->delete()) {
                event(new RoleDeleted($role));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.roles.delete_error'));
        });
    }

    /**
     * Return the role.id of the default "Standard" user for this site. It is set in site_option. 
     * @version 1.1
     * @author lutzjw
     * @date:   2018-04-24T10:19:57-0500
     * @since   1.0
     * @return  integer   The value in site_option where site_id = the Session variable and site_option_id = 4. 
     */
    public function getDefaultUserRole()
    {
        return intval(Site::find(Session::get('site_id'))->getSiteOption(4));
    }


    /**
     * Get Users w/ passed roles emails
     * @param $roles -> arr of roles.id
     * @param $course_id -> courses.id for 1st pass, can be null
     * @param $event_id -> events.id for 2nd pass, can be null
     * @return array of user emails  
     */
    public function getRoleEmails($roles, $course_id, $event_id) {
        $arr = [];
        $roles = explode(',', $roles);

        // get role_user emails
        if(isset($course_id)) {
            $role_users = DB::table('roles')->join('role_user', 'role_user.role_id', '=', 'roles.id')
                ->join('users', 'users.id', '=', 'role_user.user_id')
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                ->whereIn('roles.id', $roles)
                ->where('role_user.course_id', $course_id)
                ->get();
            foreach ($role_users as $role_user ) {
                if(!in_array($role_user->email, $arr, true)) {
                    array_push($arr, $role_user->email);                    
                }
            }
        }
   
        // get event_user emails
        if(isset($event_id)) {           
            $event_users = DB::table('roles')->join('event_user', 'event_user.role_id', '=', 'roles.id')
                ->join('users', 'users.id', '=', 'event_user.user_id')
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                ->whereIn('roles.id', $roles)
                ->where('event_user.event_id', $event_id)
                ->whereNull('event_user.deleted_at')
                ->get();
                
            foreach ($event_users as $event_user) {                
                if(!in_array($event_user->email, $arr, true)) {
                    array_push($arr, $event_user->email);                    
                }
            }
        }
        // Send Back Emails
        return $arr;
    }
}