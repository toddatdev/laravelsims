<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Models\Access\User\User;
use Session;
use Validator;
use Storage;
use Debugbar;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
        $output = $this->user->updateProfile(access()->id(), $request->only('first_name', 'last_name', 'middle_name', 'phone', 'email'));

        Debugbar::info('were right here');
        if( $request->hasFile('image'))
        {
            
            $this->storeImage($request);
        }       

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            access()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(trans('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));    
    }

    //Store new image in S3
    public function storeImage($request)
    {
        if( $request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = 'site-'.Session::get('site_id').'/UserProfileAvatars/'.access()->user()->id.'.'.$image->getClientOriginalExtension();

            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);

            $user_update = User::find(access()->user()->id);
            $user_update->update(['image' => $imageName]);

        }
    }

}
