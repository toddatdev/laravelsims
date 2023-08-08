<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Session;

class CourseFeeTypes extends Model
{
    protected $table = 'course_fee_types';

    protected $fillable = ['site_id', 'description', 'created_by', 'last_edited_by', 'retire_date'];

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {

        parent::boot();

        //return only this site's fee types
        static::addGlobalScope('site', function (Builder $builder) {
            $builder->where('course_fee_types.site_id', '=', Session::get('site_id'));
        });
    }


    //Retired or active button, site level manage courses permission only
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

    public function getSelectList()
    {
        $courseFeeTypes = CourseFeeTypes::get()->where('retire_date', null)->sortBy('description');
        $stringtoReturn = null;

        foreach ($courseFeeTypes as $courseFeeType) {
            $stringtoReturn .= "{ label: '" . $courseFeeType->description . "', value: '" . $courseFeeType->id . "' }, ";
        }

        return $stringtoReturn;
    }
}
