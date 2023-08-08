<?php

namespace App\Http\Requests\CourseInstance;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreEventUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request (must be logged in)
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

        $fromUrl = url()->previous();

        //if from event-dashboard then user_id required
        if(Str::contains($fromUrl, ['event-dashboard']))
        {
            //if tab wasn't already in URL, add it so they go back to roster tab
            if(!Str::contains($fromUrl, ['roster']))
            {
                $this->redirect = url()->previous().'/roster';
            }

            return [
                'event_id' => 'required|numeric',
                'role_id'  => 'required|numeric',
                'user_id'  => 'required|numeric',
            ];
        }
        //if from self user enrollment/waitlist via course catalog then user_id not required
        else
        {
            return [
                'event_id' => 'required|numeric',
                'role_id'  => 'required|numeric',
            ];
        }


    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'event_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.event_users.event')]),
            'role_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.event_users.role')]),
            'user_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.event_users.user')]),
        ];
    }
}
