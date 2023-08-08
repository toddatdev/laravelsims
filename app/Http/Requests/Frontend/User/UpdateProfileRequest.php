<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;

/**
 * Class UpdateProfileRequest.
 */
class UpdateProfileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'  => 'string|required|max:30',
            'last_name'  => 'string|required|max:50',
            'middle_name'  => 'nullable|string|max:30',
            'email' => 'sometimes|required|email|max:191',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => trans('alerts.frontend.profile.image'),
            'image.uploaded' => trans('alerts.frontend.profile.image_size'),
        ];
    }
}
