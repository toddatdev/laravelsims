<?php

namespace App\Http\Requests\Resource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class UpdateResourceSubCategoryRequest extends FormRequest
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

            //abbrv and description (aka full name) must be unique within the category
            'description' => ['required', 'max:50', Rule::unique('resource_sub_category')
                ->ignore($this->resourceSubCategory->id)
                ->where(function($query) {
                $query->where('resource_category_id', $this->resource_category_id);
            })],

            'abbrv' => ['required', 'max:25', Rule::unique('resource_sub_category')
                ->ignore($this->resourceSubCategory->id)
                ->where(function($query) {
                    $query->where('resource_category_id', $this->resource_category_id);
                })],
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
            'description.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resourceSubCategory.full_name')]),
            'description.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.resourceSubCategory.full_name')]),
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.resourceSubCategory.abbrv')]),
            'abbrv.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.resourceSubCategory.abbrv')]),
        ];
    }

}
