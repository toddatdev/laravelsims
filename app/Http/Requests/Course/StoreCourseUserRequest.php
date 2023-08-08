<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasPermission('add-people-to-courses');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id' => 'required|numeric',
            'user_id'   => 'required|numeric',
            'role_id'   => 'required|numeric',
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
            'course_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_users.course')]),
            'user_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_users.user')]),
            'role_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_users.role')]),
        ];
    }
}
