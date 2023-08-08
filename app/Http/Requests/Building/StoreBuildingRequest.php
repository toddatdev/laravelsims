<?php

namespace App\Http\Requests\Building;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class StoreBuildingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //I changed this to hasPermisison from hasRole -jl 2018-03-29 11:17 
        return access()->hasPermission('manage-buildings'); 
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
            'abbrv' => ['required', 'max:50', Rule::unique('buildings')->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],

            //name must be unique within the site
            'name'  => ['required', 'max:255', Rule::unique('buildings')->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],

            'more_info'     => 'max:2000',
            'map_url'       => 'max:2048',
            'address'       => 'max:100',
            'city'          => 'max:100',
            'state'         => 'max:100',
            'postal_code'   => 'max:100',
            'display_order'  => ['required', 'numeric', Rule::unique('buildings')->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],
            'timezone'      => 'required|max:50',
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
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.buildings.abbrv')]),
            'abbrv.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.buildings.abbrv')]),
            'name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.buildings.name')]),
            'name.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.buildings.name')]),
        ];
    }

}
