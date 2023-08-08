<?php

namespace App\Http\Requests\CourseInstance;

use Illuminate\Foundation\Http\FormRequest;

class MoveEventUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasPermissions(['add-people-to-events','course-add-people-to-events','event-add-people-to-events']); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_move_id' => 'required|numeric',
            'event_user_id' => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'event_move_id.required' => trans('validation.attributes.event_users.move_event_id'),
            'event_user_id.required' => trans('validation.attributes.event_users.move_event_user'),
        ];
    }
}
