<?php

namespace App\DataTables;

use App\Models\Course\CourseCoupons;
use App\Models\Course\CourseFees;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CourseFeesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable()
    {
        //return records for just this course
        $courseFees = CourseFees::where('course_id', $this->course_id);
        return DataTables::of($courseFees)
            ->setRowId('id')
            ->addColumn('deposit_description', function($courseFees) {
                return $courseFees->deposit_description; //defined in getDepositDescriptionAttribute in the CourseFees Model
            })
            ->addColumn('fee_type_description', function($courseFees) {
                return $courseFees->courseFeeTypes->description;
            })->addColumn('actions', function($courseFees) {
                return $courseFees->activation_button; //defined in getActivationButtonAttribute in the CourseFeeTypes Model
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
                'data' => 'fee_type_description',
                'className' => 'editable',
                'title' => trans('labels.courseFees.fee_type'),
                'editField' => 'course_fee_type_id' //displays description but edits the hidden type field using value
            ],
            [
                'data' => 'course_fee_type_id',
                'className' => 'editable',
                'title' => trans('labels.courseFees.fee_type'),
                'visible' => False,
            ],
            [
                'data' => 'amount',
                'className' => 'editable',
                'title' => trans('labels.courseFees.amount'),
                'render' => "$.fn.dataTable.render.number( ',', '.', 2, '$' )" //format as currency
            ],
            [
                'data' => 'deposit_description',
                'className' => 'editable',
                'title' => trans('labels.courseFees.deposit'),
                'editField' => 'deposit' //displays text (Yes/No) but edits the hidden type field using values (1/0)
            ],
            [
                'data' => 'deposit',
                'className' => 'editable',
                'title' => trans('labels.courseFees.deposit'),
                'visible' => False,
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
        return 'CourseFees_' . date('YmdHis');
    }
}
