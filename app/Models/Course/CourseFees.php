<?php

namespace App\Models\Course;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;

class CourseFees extends Model
{
    protected $table = 'course_fees';

    protected $fillable = ['course_id', 'course_fee_type_id', 'amount', 'deposit', 'created_by', 'last_edited_by', 'retire_date'];

    //relationship to CourseFeeTypes
    public function courseFeeType()
    {
        return $this->belongsTo(CourseFeeTypes::class);
    }

    public function courseFeeTypes()
    {
        return $this->belongsTo(CourseFeeTypes::class, 'course_fee_type_id', 'id');
    }

    //used in DataTable to display yes/no text instead of just 1/0
    public function getDepositDescriptionAttribute()
    {
        if($this->deposit == 1)
        {
            return 'Yes';
        }
        else
        {
            return 'No';
        }
    }

    public function getActivationButtonAttribute()
    {
        if($this->retire_date !== null)
        {
            //not using simptip here for hover text because it was making the hover all white
            return '<button class="btn btn-sm btn-success "
                        name="change_activation" id="change_activation"
                        title="' . trans('buttons.general.activate') . '"
                        data-fee_type_id="'. $this->id. '"
                        data-action="turn_on">
                        <i class="fa fa-fw fa-play"></i></button>';

        }
        else
        {
            //not using simptip here for hover text because it was making the hover all white
            return '<button class="btn btn-sm btn-warning"
                        name="change_activation" id="change_activation"
                        title="' . trans('buttons.general.retire') . '"
                        data-fee_type_id="'. $this->id. '"
                        data-action="turn_off">
                        <i class="fa fa-fw fa-pause text-light"></i></button>';
        }
    }
}
