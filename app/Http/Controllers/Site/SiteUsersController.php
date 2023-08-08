<?php

namespace App\Http\Controllers\Site;

use App\Models\Course\Course;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Site\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Site\StoreSiteUserRequest;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use Storage;
use Session;


class SiteUsersController extends Controller
{

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }


    // returns the site users page
    public function users($role_id)
    {

        $site_id = Session::get('site_id');
        $site = Site::find($site_id);

        // set the roles box to course roles.
        $siteRoles = $this->roles->getRoles(1);

        return view('sites.users', compact('site', 'role_id', 'siteRoles'));
    }


    // pulls data for Datatable
    public function siteUsersTableData(Request $request)
    {

        $site_id = Session::get('site_id');
        $site = Site::find($site_id);

        $users = User::active(true)
            ->selectRaw('roles.name as "role_name", role_user.id as "role_user_id"')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereExists(function ($query) use ($site) {
                $query->select(DB::raw(1))
                    ->from('permission_role')
                    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                    ->whereRaw('permission_role.role_id = roles.id')
                    ->where('roles.site_id', $site->id)
                    ->where('permission_type_id', 1);
            })
            ->where('all', '!=', 1);

        return DataTables::of($users)
            ->addColumn('id', function ($users) {
                return $users->role_user_id;
            })
            ->addColumn('role', function ($users) {
                return $users->role_name;
            })
            ->addColumn('name', function ($users) {
                return $users->first_name .' '. $users->last_name;
            })
            ->addColumn('email', function ($users) {
                return $users->email;
            })
            ->addColumn('actions', function ($users) {
                return '<a href="/site/users/delete/'.$users->role_user_id.'" name="delete" class="btn btn-sm btn-danger">
                <i class="fas fa-lg fa-trash"
                data-toggle="tooltip"
                data-placement="top"
                title="'.trans('buttons.general.crud.delete').'"></i></a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    // add user to db
    public function store(StoreSiteUserRequest $request)
    {

        // set variables
        $user_id = $request->post('user_id');
        $role_id = $request->post('role_id');

        DB::table('role_user')->insert(
            ['user_id' => $user_id, 'role_id' => $role_id]
        );

        return redirect()
                ->back()
                ->with('role_id', $role_id)
                ->withFlashSuccess(trans('alerts.backend.siteusers.created'));
    }

    // delete user from db
    public function delete($id)
    {
        DB::table('role_user')->where('id', $id)->delete();

        return redirect()
                ->back()
                ->withFlashSuccess(trans('alerts.backend.siteusers.deleted'));
    }

}
