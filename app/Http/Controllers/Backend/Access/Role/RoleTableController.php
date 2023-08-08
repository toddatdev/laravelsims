<?php

namespace App\Http\Controllers\Backend\Access\Role;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;

/**
 * Class RoleTableController.
 */
class RoleTableController extends Controller
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

    public function roleTableData(Request $request)
    {

        if ($roleType = $request->get('type')){
            if($roleType == 'site') {
                $roleType = '1';
            }

            if($roleType == 'course') {
                $roleType = '2';
            }

            if($roleType == 'event') {
                $roleType = '3';
            }
        }

        return DataTables::of($this->roles->getForDataTable($roleType))
        // mitcks 8/13/18 the where clause no longer supported in new version of DataTables
        //->where('all', '!=', '1')  //Make is so that no end user sees 'Administrator' (Superuser) roles. -jl 2018-05-01 10:09
            ->escapeColumns(['name'])
            ->addColumn('permissions', function ($role) {
                if ($role->all) {
                    return '<span class="label label-success">'.trans('labels.general.all').'</span>';
                }

                return $role->permissions->count() ?
                    implode('<br/>', $role->permissions->pluck('display_name')->toArray()) :
                    '<span class="label label-danger">'.trans('labels.general.none').'</span>';
            })
            ->addColumn('users', function ($role) {
                return $role->users->count();
            })
            ->addColumn('actions', function ($role) use ($roleType) {
                if ($roleType == '1') {
                    return $role->getSiteButtonsAttribute();
                } else {
                    return $role->action_buttons;
                }
            })
            ->make(true);
    }
}
