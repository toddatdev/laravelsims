<?php

namespace App\Http\Requests\Resource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class UpdateResourceRequest extends FormRequest
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

            //abbrv and description (aka full name) must be unique within the location (not site)
            'description' => ['required', 'max:50', Rule::unique('resources')
                ->ignore($this->resource->id)
                ->where(function($query) {
                    $query->where('location_id', $this->resource->location_id);
                })],

            'abbrv' => ['required', 'max:25', Rule::unique('resources')
                ->ignore($this->resource->id)
                ->where(function($query) {
                    $query->where('location_id', $this->resource->location_id);
                })],

            'location_id'               => 'required',
            'resource_category_id'      => 'required',
            'resource_type_id'          => 'required'
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
            'description.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resource.full_name')]),
            'description.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.resource.full_name')]),
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resource.abbrv')]),
            'abbrv.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.resource.abbrv')]),
            'location_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resource.location')]),
            'resource_category_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resource.category')]),
            'resource_type_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resource.type')]),
        ];
    }

}
