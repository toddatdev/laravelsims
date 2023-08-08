<?php

namespace App\Http\Requests\Site;
use App\Models\Site\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteRequest extends FormRequest
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
            'abbrv' => 'required|max:50|unique:sites,abbrv,'. $this->site->id,
            'name' => 'required|max:255|unique:sites,name,'. $this->site->id,
            'organization_name' => 'required|max:255|unique:sites,organization_name,'. $this->site->id,
            'email' => 'required|email|unique:sites,email,'. $this->site->id,
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
