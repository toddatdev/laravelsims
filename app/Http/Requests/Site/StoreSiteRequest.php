<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //I changed this to hasPermisison from hasRole -jl 2018-03-29 11:17 
        return access()->hasPermission('manage-sites'); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'abbrv' => ['required', 'max:50', Rule::unique('sites')],
            'name'  => ['required', 'max:255', Rule::unique('sites')],
            'organization_name' => ['required', 'max:255', Rule::unique('sites')],
            'email' => ['required','email', 'max:255', Rule::unique('sites')],
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
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.site.abbrv')]),
            'name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.site.name')]),
            'organization_name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.site.organization_name')]),
            'email.required' => trans('validation.required', ['attribute' => trans('validation.attributes.site.email')]),
        ];
    }

}
