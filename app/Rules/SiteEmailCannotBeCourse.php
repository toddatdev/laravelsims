<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SiteEmailCannotBeCourse implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $email_type;

    public function __construct($email_type)
    {
        $this->email_type = $email_type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    // public function passes($attribute, $value)
    // {
    //     return  $this->email_type != $this->is_course; 
    // }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.attributes.siteEmails.type_course_rule');
    }
}
