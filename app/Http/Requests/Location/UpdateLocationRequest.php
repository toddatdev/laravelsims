<?php

namespace App\Http\Requests\Location;
use App\Models\Location\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //I changed this to hasPermisison from hasRole -jl 2018-03-29 11:17 
        return access()->hasPermission('manage-locations'); 
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
            'abbrv' => ['required', 'max:50', 
                        Rule::unique('locations')
                                ->ignore($this->location->id)
                                ->where(function ($query)
                                {
                                    $query->where('building_id', $this->location->building_id);
                                })
                        ],
            'name' => ['required', 'max:255', 
                        Rule::unique('locations')
                                ->ignore($this->location->id)
                                ->where(function ($query)
                                {
                                    $query->where('building_id', $this->location->building_id);
                                })
                        ],

           'display_order' => ['required', 'numeric', 'max:100', 'between:0,100',
                        Rule::unique('locations')
                                ->ignore($this->location->id)
                                ->where(function ($query)
                                {
                                    $query->where('building_id', $this->location->building_id);
                                })
                        ],

            'building_id'    => 'max:3',
            'more_info'      => 'max:2000',
            'directions_url' => 'max:2048', 
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
            'abbrv.required' => trans('validation.required', ['attribute' => trans('validation.attributes.locations.abbrv')]),
            'abbrv.unique' => trans('validation.attributes.locations.unique', ['attribute' => trans('validation.attributes.locations.abbrv')]),
            'name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.locations.name')]),
            'name.unique' => trans('validation.attributes.locations.unique', ['attribute' => trans('validation.attributes.locations.name')]),
            'display_order.required' => trans('validation.required', ['attribute' => trans('validation.attributes.locations.display_order')]),
            'display_order.unique' => trans('validation.attributes.locations.unique', ['attribute' => trans('validation.attributes.locations.display_order')]),
            'building_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.locations.display_order')]),
        ];
    }

}
