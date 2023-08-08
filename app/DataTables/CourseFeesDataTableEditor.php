<?php

namespace App\DataTables;

use App\Http\Requests\Request;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTablesEditor;
use App\Models\Course\CourseFees;
use Session;

class CourseFeesDataTableEditor extends DataTablesEditor
{
    protected $model = CourseFees::class;

    /**
     * Get create action validation rules.
     *
     * @return array
     */
    public function createRules()
    {
        return [

            //fee type must be unique within the site
            'course_fee_type_id' => ['required', Rule::unique('course_fees')
                ->where(function($query) {
                    $query->where('course_fees.course_id', Session::get('course_id'));
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

            //course_fee_type_id must be unique within the course
            'course_fee_type_id' => Rule::unique($model->getTable())
                ->ignore($model->getKey())
                ->where(function($query) {
                    $query->where('course_fees.course_id', Session::get('course_id'));
                }),

            'amount' => 'sometimes|required',
            'deposit' => 'sometimes|required',
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
