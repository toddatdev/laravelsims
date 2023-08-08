<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;

// I added this -jl 2018-03-28 13:40 
use App\Models\Access\User\User;

/**
 * Class UserTableController.
 */
class UserTableController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function getSites($id)
    {
        return  User::find($id)->sites;
    } 
    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageUserRequest $request)
    {
        return DataTables::of($this->users->getForDataTable($request->get('status'), $request->get('trashed')))
        ->escapeColumns(['first_name', 'last_name', 'middle_name', 'phone', 'email'])
        ->editColumn('confirmed', function ($user) {
            return $user->confirmed_label;
        })
            ->addColumn('roles', function ($user) {
                return $user->roles->count() ?
                    implode('<br/>', $user->siteRoles->pluck('name')->toArray()) :
                    trans('labels.general.none');
            })
            ->addColumn('actions', function ($user) {
                return $user->action_buttons;
            })
//          ->withTrashed() //ksm 2018-08-03 this was removed from DataTables (Per vendor (https://yajrabox.com/docs/laravel-datatables/master/upgrade#trashed))
            ->make(true);
    }
}
