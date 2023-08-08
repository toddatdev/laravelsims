<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class UpdateCourseTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasPermission('manage-templates');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            //name must be unique within the course
            'name' => ['required', 'max:50', Rule::unique('course_templates')
                ->ignore($this->template->id)
                ->where(function($query) {
                $query->where('course_id', $this->course_id);
            })],

            'course_id'  => 'required', 'numeric',
            'class_size'  => 'numeric|min:1',
            'setup_time'  => 'required', 'numeric',
            'teardown_time'  => 'required', 'numeric',
            'radio_is_imr'=> 'required'

        ];

        // loop through resource rows
        $numRows = $this->request->get('resource_count');

        for ($i = 0; $i < $numRows+1; $i++)
        {
            $rules[$i .'_start_time'] = 'required';
            $rules[$i .'_start_time'] = 'date_format:g:i A';
            $rules[$i .'_end_time'] = 'required';
            $rules[$i .'_end_time'] = 'date_format:g:i A|after:' .$i ."_".'start_time';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'name.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_template.name')]),
            'name.unique' => trans('validation.unique', ['attribute' => trans('validation.attributes.course_template.name')]),
            'course_id.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_template.course_id')]),
            'class_size.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_template.class_size')]),
            'class_size.numeric' => trans('validation.numeric', ['attribute' => trans('validation.attributes.course_template.class_size')]),
            'class_size.min' => trans('validation.min.numeric', ['attribute' => trans('validation.attributes.course_template.class_size')]),
            'setup_time.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_template.setup')]),
            'setup_time.numeric' => trans('validation.numeric', ['attribute' => trans('validation.attributes.course_template.setup')]),
            'teardown_time.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_template.teardown')]),
            'teardown_time.numeric' => trans('validation.numeric', ['attribute' => trans('validation.attributes.course_template.teardown')]),
            'radio_is_imr.required' => trans('validation.required', ['attribute' => trans('validation.attributes.course_template.imr')]),
        ];

        // loop through resource rows
        $numRows = $this->request->get('resource_count');

        for ($i = 0; $i < $numRows+1; $i++)
        {
            $messages[$i .'_start_time.required'] = trans('validation.required', ['attribute' => trans('validation.attributes.course_template.resource_start_time')]);
            $messages[$i .'_start_time.date_format'] = trans('validation.regex', ['attribute' => trans('validation.attributes.course_template.resource_start_time')]);
            $messages[$i .'_end_time.required'] = trans('validation.required', ['attribute' => trans('validation.attributes.course_template.resource_end_time')]);
            $messages[$i .'_end_time.date_format'] = trans('validation.regex', ['attribute' => trans('validation.attributes.course_template.resource_end_time')]);
            $messages[$i .'_end_time.after'] = trans('validation.attributes.course_template.resource_start_gt_end');
        }

        return $messages;
    }

}
