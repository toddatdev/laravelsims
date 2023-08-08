<?php

namespace App\DataTables;

use App\Models\Course\CourseFeeTypes;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CourseFeeTypesDataTable extends DataTable
{
    //Built from example in this tutorial: https://yajrabox.com/docs/laravel-datatables/8.0/editor-tutorial

    protected $actions = ['print', 'excel', 'myCustomAction'];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable()
    {
        $courseFeeTypes = CourseFeeTypes::get();
        return DataTables::of($courseFeeTypes)
            ->setRowId('id')
            ->addColumn('actions', function($courseFeeTypes) {
                return $courseFeeTypes->activation_button; //defined in getActivationButtonAttribute in the CourseFeeTypes Model
            })
            ->rawColumns(['actions']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom' => 'Bfrtip',
                'order' => [1, 'asc'],
                'select' => [
                    'style' => 'os',
                    'selector' => 'td:first-child',
                ],
                'buttons' => [
                    ['extend' => 'create', 'editor' => 'editor'],
                    ['extend' => 'edit', 'editor' => 'editor'],
                ]
            ]);
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            [
                'data' => null,
                'defaultContent' => '',
                'className' => 'select-checkbox',
                'title' => '',
                'orderable' => false,
                'searchable' => false
            ],
            [
                'data' => 'description',
                'className' => 'editable',
                'title' => trans('labels.general.description'),
            ],
            'actions'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'CourseFeeTypes_' . date('YmdHis');
    }
}
