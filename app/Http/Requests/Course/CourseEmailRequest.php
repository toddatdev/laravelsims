<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Session;

class CourseEmailRequest extends FormRequest
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

    // To Validate comma seperated emails for Laravel
    public function prepareForValidation() {
        // strip spaces and turn into array for laravel
        $this->merge([
            'to_other' => explode(',',str_replace(' ', '', $this->to_other)), 
            'cc_other' => explode(',',str_replace(' ', '', $this->cc_other)), 
            'bcc_other' => explode(',',str_replace(' ', '', $this->bcc_other)), 
        ]);
    }    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */    
    public function rules()
    {
        if ($this->get('_method') === 'PATCH') {
            return [
                'label' => ['required', Rule::unique('course_emails')
                    ->ignore($this->id)
                    ->where(function($query) {
                        $query->where('course_id', Session::get('course_id'));
                        $query->whereNull('deleted_at');
                    })
                ],     
                'email_type_id' => 'required',
                'subject' => 'required',
                'body' => 'required',
                'time_amount' => 'required_with:time_type, time_offset',
                'time_type' => 'required_with:time_amount, time_offset',
                'time_offset' => 'required_with:time_amount, time_type',
                'role_id' => 'required_with:role_amount, role_offset',
                'role_amount' => 'required_with:role_id, role_offset',
                'role_offset' => 'required_with:role_amount, role_id',
                'option' => 'required_unless:type,==,2',
                'to_other.*' => 'email',
                'cc_other.*' => 'email',
                'bcc_other.*' => 'email',
            ];
        }else {
            return [
                'label' => ['required', Rule::unique('course_emails')
                    ->where(function($query) {
                        $query->where('course_id', Session::get('course_id'));
                        $query->whereNull('deleted_at');
                    })
                ],  
                'email_type_id' => 'required',
                'subject' => 'required',
                'body' => 'required',
                'time_amount' => 'required_with:time_type, time_offset',
                'time_type' => 'required_with:time_amount, time_offset',
                'time_offset' => 'required_with:time_amount, time_type',
                'role_id' => 'required_with:role_amount, role_offset',
                'role_amount' => 'required_with:role_id, role_offset',
                'role_offset' => 'required_with:role_amount, role_id',
                'to_other.*' => 'email',
                'cc_other.*' => 'email',
                'bcc_other.*' => 'email',
            ];
            
        }
        
    }

    public function messages() {
        return [
            'option.required_unless' => 'Need select an option at bottom to handle update',
            'to_other.*' => trans('labels.eventEmails.comma'),
            'cc_other.*' => trans('labels.eventEmails.comma'),
            'bcc_other.*' => trans('labels.eventEmails.comma'),
        ];
    }
}
