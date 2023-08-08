<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class AddClassRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "course_id" => "required|integer",
            "class_size" => "required",
            "public_comments" => "max:50",
            "internal_comments" => "max:4000",
            "event_date" => "required",
            "setup_time" => "required|integer",
            "start_time" => "required",
            "end_time" => "required|after:start_time",
            //"end_time" => "required|gt:start_time",
            "teardown_time" => "required|integer",
         //  "schedule_addClass_InstructorReport" => "integer",
            "instructorReportState" => "in:Before,After",
           // "schedule_addClass_InstructorLeave" => "integer",
            "instructorLeaveState" => "in:Before,After",
           // "html_color" => "trim",
            "resource_id" => "required|integer",
        ];
    }

        public function messages()
    {
        return [
            'course_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.course_id')]),
            'class_size.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.class_size')]),
            'resource_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.resource_id')]),
            'end_time.after' => 'End time must be greater than start time.',
            
        ];
    }

}
