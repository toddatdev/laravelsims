<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Site\Site;
use App\Repositories\Backend\Access\Permission\PermissionRepository;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Carbon\Carbon;
use Collective\Html\FormFacade;

/**
 * Class AccountPermissionsController
 */
class AccountPermissionsController extends Controller {

    /**
     * @var PermissionRepository
     */
    protected $permissions;

    public function __construct(PermissionRepository $permissions)
    {
        $this->permissions = $permissions;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        
        return view('frontend.user.account.permissions');
    }


    // load the account permissions according to role type in $request->get($role_type) (1 == site / 2 == course / 3 == event)
    // redirect to that role types function in PermissionsRepository.php
    public function accountPermissionsTableData(Request $request)
    {

            // get current user
            $user = Auth::user();

            if($request->get('role_type') == 1) {
                return DataTables::of($this->permissions->getUserSitePermissions($user->id))
                ->addColumn('permission_id', function($permissions) {
                    return $permissions->permission_id;
                })
                ->addColumn('permission_name', function($permissions) {
                    return $permissions->permission_name;
                })
                ->make(true);


            } elseif ($request->get('role_type') == 2) {

                return DataTables::of($this->permissions->getUserCoursePermissions($user->id))
                ->addColumn('permission_id', function($permissions) {
                    return $permissions->permission_id;
                })
                ->addColumn('course_name', function($permissions) {
                    return $permissions->course_name;
                })
                ->addColumn('permission_name', function($permissions) {
                    return $permissions->permission_name;
                })
                ->make(true);


            } elseif ($request->get('role_type') == 3) {

                return DataTables::of($this->permissions->getUserEventPermissions($user->id))
                ->addColumn('permission_id', function($permissions) {
                    return $permissions->permission_id;
                })
                ->addColumn('course_name', function($permissions) {
                    return $permissions->course_name;
                })
                ->addColumn('event', function($permissions) {
                    return Carbon::parse($permissions->event_start_time)->format('m/d/y g:ia');
                })
                ->addColumn('permission_name', function($permissions) {
                    return $permissions->permission_name;
                })
                ->make(true);

            }
    }

}
