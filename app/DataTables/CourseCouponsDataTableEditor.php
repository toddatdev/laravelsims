<?php

namespace App\DataTables;

use App\Http\Requests\Request;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTablesEditor;
use App\Models\Course\CourseCoupons;
use Session;

class CourseCouponsDataTableEditor extends DataTablesEditor
{
    protected $model = CourseCoupons::class;

    /**
     * Get create action validation rules.
     *
     * @return array
     */
    public function createRules()
    {
        return [
            //coupon_code must be unique within the course
            'coupon_code' => ['required', 'max:25', Rule::unique('course_coupons')
                ->where(function($query) {
                    $query->where('course_id', Session::get('course_id'));
                })],
            'amount' => 'required',
        ];
    }

    /**
     * Get edit action validation rules.
     *
     * @param Model $model
     * @return array
     */
    public function editRules(Model $model)
    {
        return [

            //note: the sometimes here is important for edit when more than one field required,
            // otherwise when inline editing it will incorrectly error that the non edited field is required

            //coupon_code must be unique within the course
            'coupon_code' => Rule::unique($model->getTable())
                ->ignore($model->getKey())
                ->where(function($query) use ($model) {
                    $query->where('course_coupons.course_id', Session::get('course_id'));
                }),
            'amount' => 'sometimes|required',
        ];
    }

    /**
     * Get remove action validation rules.
     *
     * @param Model $model
     * @return array
     */
    public function removeRules(Model $model)
    {
        return [];
    }

    /**
     * Pre-create action event hook.
     *
     * @param Model $model
     * @return array
     */
    public function creating(Model $model, array $data)
    {
        $data['created_by'] = auth()->user()->id;
        $data['last_edited_by'] = auth()->user()->id;
        return $data;
    }

    /**
     * Pre-update action event hook.
     *
     * @param Model $model
     * @return array
     */
    public function updating(Model $model, array $data)
    {
        $data['last_edited_by'] = auth()->user()->id;
        return $data;
    }

}
