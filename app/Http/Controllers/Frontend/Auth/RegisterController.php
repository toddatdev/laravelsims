<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Events\Frontend\Auth\UserRegistered;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Models\Access\User\User;
use Session;
use Storage;
/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        // Where to redirect users after registering
        $this->redirectTo = route(homeRoute());

        $this->user = $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        if (config('access.users.confirm_email')) {
            $user = $this->user->create($request->only('first_name', 'last_name', 'middle_name', 'phone', 'email', 'password'));

            event(new UserRegistered($user));

            //adds the user_id, site_id combination to the user_sites table (if not already there)
            app( 'App\Http\Controllers\Site\UserSitesController')->addUserSite($user->id,Session::get('site_id'));

            if( $request->hasFile('image'))
            {
                $this->storeImage($user, $request);
            }

            return redirect($this->redirectPath())->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.created_confirm'));

        } else {
            access()->login($this->user->create($request->only('first_name', 'last_name', 'middle_name', 'phone', 'email', 'password')));

            event(new UserRegistered(access()->user()));

            //adds the user_id, site_id combination to the user_sites table (if not already there)
            app( 'App\Http\Controllers\Site\UserSitesController')->addUserSite(access()->user()->id,Session::get('site_id'));

            if( $request->hasFile('image'))
            {
                $user = User::find(access()->user()->id);
                $this->storeImage($user, $request);
            }

            return redirect($this->redirectPath());
        }
    }

    //Store new image in S3
    public function storeImage($user, $request)
    {
        if( $request->hasFile('image'))
        {

            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $image = $request->file('image');
            $imageName = 'site-'.Session::get('site_id').'/UserProfileAvatars/'.$user->id.'.'.$image->getClientOriginalExtension();

            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);

            $user->update(['image' => $imageName]);

        }
    }

}
