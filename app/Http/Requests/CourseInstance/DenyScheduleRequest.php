<?php

namespace App\Http\Requests\CourseInstance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

class DenyScheduleRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'cc_email' => explode(',',str_replace(' ', '', $this->cc_email)), // strip spaces and turn into array for laravel
            'bcc_email' => explode(',',str_replace(' ', '', $this->bcc_email)),
        ]);
    }

    public function rules()
    {
        return [

            'email_content'     => 'required',
            'cc_email.*'        => 'email',
            'bcc_email.*'       => 'email'

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
            'email_content.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule.course_id')]),
            'cc_email.*' => ':attribute '.trans('labels.scheduling.deny_cc_invalid_email'),
            'bcc_email.*' => ':attribute '.trans('labels.scheduling.deny_bcc_invalid_email')
        ];
    }


    public function attributes()
    {
        $attributes = array();
        foreach ($this->cc_email as $index=>$email) {
            $attributes['cc_email.'.$index] = $email;
        }
        foreach ($this->bcc_email as $index=>$email) {
            $attributes['bcc_email.'.$index] = $email;
        }
        return $attributes;
    }
    
}
