<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Models\Access\User\User;
use App\Models\Site\Site;
use Session;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        return view('backend.access.index');
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request)
    {
        return view('backend.access.create')
            //->withRoles($this->roles->getAll());
            // 
            // I updated this to limit to just Roles with Site Permissions -jl 2019-06-18 19:52
            ->withRoles($this->roles->query()
                    ->select('roles.id', 'roles.name') //need to select the id and name for form.
                    // do the joins
                    ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    // Set the permission type to 1 (Site). You can change this for course(2) or Event(3) permissions
                    ->where('permission_type_id', 1)
                    //Group, so you only get one role. The joins will give you multiple permissions per join
                    ->groupBy('roles.id') 
                    ->get());

    }

    /**
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        // This validates that they have selected at least one role. The other required validations are done client side in the blade.
        // -jl 2019-04-04 11:57
        $rules = [
                    'assignees_roles' => 'required',
            ];

            $customMessages = [
                //You can build customized messages specifying value.rule. -jl 2019-04-04 12:07
                'assignees_roles.required' => 'You must select at least one role for the user.'
            ];

        $this->validate($request, $rules, $customMessages);

        $this->users->create(
            [
                'data' => $request->only(
                    'first_name',
                    'last_name',
                    'middle_name',
                    'phone',
                    'email',
                    'password',
                    'status',
                    'confirmed',
                    'confirmation_email'
                ),
                'roles' => $request->only('assignees_roles'),
            ]);

        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.created'));
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function show(User $user, ManageUserRequest $request)
    {
        return view('backend.access.show')
            ->withUser($user);
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function edit(User $user, ManageUserRequest $request)
    {

        $siteUserRoleId= Site::find(Session::get('site_id'))->getSiteOption(4);

        return view('backend.access.edit', compact('siteUserRoleId'))
            ->withUser($user)
            ->withUserRoles($user->roles->pluck('id')->all())
            // ->withRoles($this->roles->getAll());m  //old code was getAll
            // 
            // I updated this to limit to just Roles with Site Permissions -jl 2019-06-18 19:52
            ->withRoles($this->roles->query()
                    ->select('roles.id', 'roles.name') //need to select the id and name for form.
                    // do the joins
                    ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    // Set the permission type to 1 (Site). You can change this for course(2) or Event(3) permissions
                    ->where('permission_type_id', 1)
                    //Group, so you only get one role. The joins will give you multiple permissions per join
                    ->groupBy('roles.id')
                    ->orderBy('roles.name')
                    ->get());

    }

    /**
     * @param User              $user
     * @param UpdateUserRequest $request
     *
     * @return mixed
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $this->users->update($user,
            [
                'data' => $request->only(
                    'first_name',
                    'last_name',
                    'middle_name',
                    'phone',
                    'email',
                    'status',
                    'confirmed'
                ),
                'roles' => $request->only('assignees_roles'),
            ]);

        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function destroy(User $user, ManageUserRequest $request)
    {
        $this->users->delete($user);

        return redirect()->route('admin.access.user.deleted')->withFlashSuccess(trans('alerts.backend.users.deleted'));
    }
}
