<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseSendManuallyRequest extends FormRequest {
    public function authorize()
    {
        return true;
    }


    public function prepareForValidation() {
        // strip spaces and turn into array for laravel
        $this->merge([
            'to_other' => explode(',',str_replace(' ', '', $this->to_other)), 
            'cc_other' => explode(',',str_replace(' ', '', $this->cc_other)), 
            'bcc_other' => explode(',',str_replace(' ', '', $this->bcc_other)), 
        ]);
    } 

    public function rules() {
        return [
            'subject' => 'required',
            'body' => 'required',
            'to_other.*' => 'email',
            'cc_other.*' => 'email',
            'bcc_other.*' => 'email',
        ];
    }

    public function messages() {
        return [
            'to_other.*' => trans('labels.eventEmails.comma'),
            'cc_other.*' => trans('labels.eventEmails.comma'),
            'bcc_other.*' => trans('labels.eventEmails.comma'),
        ];
    }
}