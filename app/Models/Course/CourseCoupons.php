<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class CourseCoupons extends Model
{
    protected $table = 'course_coupons';

    protected $fillable = ['course_id', 'coupon_code', 'amount', 'type', 'created_by', 'last_edited_by', 'expiration_date'];

    //used in DataTable to display full text instead of just P/V
    // note: using V to represent Value in database because that is what it was in old sims
    // but JL wants to change to use the term Amount, so V = Amount (aka Value)
    public function getTypeDescriptionAttribute()
    {
        if($this->type == "P")
        {
            return trans('labels.courseFees.percent');
        }
        else
        {
            return trans('labels.courseFees.amount');
        }
    }
}

