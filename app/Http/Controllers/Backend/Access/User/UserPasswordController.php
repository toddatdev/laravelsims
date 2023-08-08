<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserPasswordRequest;
use Illuminate\Http\Request;

/**
 * Class UserPasswordController.
 */
class UserPasswordController extends Controller
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

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function edit(User $user, ManageUserRequest $request)
    {
        return view('backend.access.change-password')
            ->withUser($user);
    }

    //opens the view to select imported users via ID range (only Admins have access)
    public function getEncryptPasswords()
    {
        if (access()->hasRole('Administrator'))
        {
            return view('backend.access.encryptPasswords');
        }
        {
            return redirect()->route('admin.access.user.index')->withFlashDanger('NO ACCESS! You need to be an Administrator to do this!');
        }
    }

    //updates the selected range of users passwords to be encrypted
    public function updateEncryptPasswords(Request $request)
    {
        $users = User::whereBetween('id', [$request->start_id, $request->end_id])
                    ->get();
        foreach($users as $user) {
            $user->password = bcrypt($user->password);
            $user->update();
        }
        return redirect()->back()->withFlashSuccess('The passwords for these users were successfully encrypted!');
    }

    /**
     * @param User                      $user
     * @param UpdateUserPasswordRequest $request
     *
     * @return mixed
     */
    public function update(User $user, UpdateUserPasswordRequest $request)
    {
        $this->users->updatePassword($user, $request->only('password'));

        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.updated_password'));
    }
}
