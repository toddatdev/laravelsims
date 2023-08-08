<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class StoreCourseCategoryGroupRequest extends FormRequest
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

            //abbrv must be unique within the site
            'abbrv' => ['required', 'max:25', Rule::unique('course_category_groups')->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],

            'description' => 'required', 'max:50',
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
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.courseCategoryGroup.abbrv')]),
            'abbrv.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.courseCategoryGroup.abbrv')]),
            'description.required' => trans('validation.required', ['attribute' => trans('validation.attributes.courseCategoryGroup.description')]),
        ];
    }

}
