<?php

namespace App\Http\Requests\CourseContent;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseContentRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id'         => 'required',
            'viewer_type_id'    => 'required',
            'content_type_id'   => 'required',
            'parent_id'         => 'required',
            'display_order'     => 'numeric',
            'menu_id'           => 'numeric',
            'menu_title'        => 'string',
            'created_by'        => 'numeric'
        ];
    }
}
