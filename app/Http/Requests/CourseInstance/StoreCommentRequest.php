<?php

namespace App\Http\Requests\CourseInstance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Session;

class StoreCommentRequest extends FormRequest
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
        $fromUrl = url()->previous();

        //if tab wasn't already in URL, add it so they go back to roster tab
        if(!Str::contains($fromUrl, ['comments']))
        {
            $this->redirect = url()->previous().'/comments';
        }

        return [
            'comment' => 'required|max:4000'
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
            'comment.required' => trans('validation.required', ['attribute' => trans('validation.attributes.schedule_comment.comment')]),
            'comment.max' => trans('validation.max.numeric', ['attribute' => trans('validation.attributes.schedule_comment.max_size')]),
        ];
    }
}
