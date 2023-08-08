<?php

namespace App\Http\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest {

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
        
        $answersRequired = $this->get('answers_field_count',1);
        
        return [
            "question" => "required",
            "answers" => "required|array|min:$answersRequired"
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
            'question.required' => trans('validation.required', ['attribute' => trans('validation.attributes.user-profile-questions.question')]),
            'answers.min' => 'There is an empty answer field.  Please either enter text or remove the empty answer row.',
        ];
    }

}