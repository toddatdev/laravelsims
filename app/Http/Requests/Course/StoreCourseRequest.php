<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //I changed this to hasPermisison from hasRole -jl 2018-03-29 11:17 
        return access()->hasPermission('manage-courses'); 
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
            'abbrv' => ['required', 'max:30', Rule::unique('courses')->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],

            //name must be unique within the site
            'name'  => ['required', 'max:255', Rule::unique('courses')->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],

            'catalog_description' => 'required|max:10000',
            'author_name' => 'max:2000',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

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
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course.abbrv')]),
            'abbrv.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.course.abbrv')]),
            'name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course.name')]),
            'name.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.course.name')]),
            'catalog_description.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course.catalog_description')]),
            //'author_name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course.author_name')]),
            //'catalog_image.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course.catalog_image')]),

        ];
    }

}
