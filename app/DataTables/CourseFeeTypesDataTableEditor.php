<?php

namespace App\DataTables;

use App\Models\Course\CourseFeeTypes;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTablesEditor;
use Session;

class CourseFeeTypesDataTableEditor extends DataTablesEditor
{
    //Built from example in this tutorial: https://yajrabox.com/docs/laravel-datatables/8.0/editor-tutorial

    protected $model = CourseFeeTypes::class;

    /**
     * Get create action validation rules.
     *
     * @return array
     */
    public function createRules()
    {
        return [
            //description must be unique within the site
            'description' => ['required', 'max:255', Rule::unique('course_fee_types')
                ->where(function($query) {
                $query->where('site_id', Session::get('site_id'));
            })],
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

            //description must be unique within the site
            'description' => ['required', 'max:255', Rule::unique($model->getTable())
                ->ignore($model->getKey())
                ->where(function($query) {
                    $query->where('site_id', Session::get('site_id'));
                })],
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
        $data['site_id'] = Session::get('site_id');
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
