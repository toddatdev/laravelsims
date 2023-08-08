<?php

namespace App\Http\Requests\CourseInstance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class StoreScheduleRequest extends FormRequest
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

    public function rules()
    {
        return [

            'course_id'         => 'required',
            'location_id'       => 'required',
            'eventDate'         => 'required|date',
            'num_rooms'         => 'required',
            'num_rooms'         => 'numeric|min:1',
            'class_size'        => 'required',
            'class_size'        => 'numeric|min:1',
            'sims_spec_needed'  => 'required|numeric',
            "notes"             => 'max:4000',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'course_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.course_id')]),
            'location_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.location_id')]),
            'eventDate.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.event_date')]),
            'num_rooms.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.num_rooms')]),
            'num_rooms.numeric' => trans('validation.numeric', ['attribute' => trans('validation.attributes.schedule.num_rooms')]),
            'num_rooms.min' => trans('validation.min.numeric', ['attribute' => trans('validation.attributes.schedule.num_rooms')]),
            'class_size.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'class_size.numeric' => trans('validation.numeric', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'class_size.min' => trans('validation.min.numeric', ['attribute' => trans('validation.attributes.schedule.class_size')]),
        ];
    }
}
