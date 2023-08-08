<?php

namespace App\Http\Requests\CourseInstance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class StoreCourseInstanceRequest extends FormRequest
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

            'course_id'             => 'required',
            'class_size'            => 'required',
            'class_size'            => 'numeric|min:1',
            'initial_meeting_room'  => 'required',
            'schedule_addClass_InstructorReport' => 'required|numeric',
            'schedule_addClass_InstructorLeave'  => 'required|numeric',
            'start_time'            => 'required',
            'end_time'              => 'required',
            'selectDate'            => 'required|date',
            "public_comments"       => "max:50",
            "internal_comments"     => "max:4000",
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
            'class_size.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'class_size.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'class_size.numeric' => trans('validation.numeric', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'class_size.min' => trans('validation.min.numeric', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'schedule_addClass_InstructorReport.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.fac_report')]),
            'schedule_addClass_InstructorLeave.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.fac_leave')]),
            'start_time.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.start_time')]),
            'end_time.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.end_time')]),
            'selectDate.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.selectDate')]),
            'initial_meeting_room.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.initial_meeting_room')]),
        ];
    }
}
