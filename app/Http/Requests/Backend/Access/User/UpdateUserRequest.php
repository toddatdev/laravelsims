<?php

namespace App\Http\Requests\Backend\Access\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
/**
 * Class UpdateUserRequest.
 */
class UpdateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //I changed this to hasPermisison from hasRole -jl 2018-03-29 11:17 
        return access()->hasPermission('manage-users'); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'email'        => 'required|email|max:191',
            'first_name'   => 'required|max:30',
            'last_name'    => 'required|max:50',
            'middle_name'  => 'string|max:30|nullable',            
            'email'        => ['required', 'email', 'max:191', Rule::unique('users')
                                                           ->ignore($this->user->id)],

        ];
    }
}
