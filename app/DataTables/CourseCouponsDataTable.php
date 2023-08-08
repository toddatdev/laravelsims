<?php

namespace App\DataTables;

use App\Models\Course\CourseCoupons;
use App\Models\Course\CourseFeeTypes;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CourseCouponsDataTable extends DataTable
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
        $courseCoupons = CourseCoupons::where('course_id', $this->course_id);
        return DataTables::of($courseCoupons)
            ->setRowId('id')
            ->addColumn('type_description', function($courseCoupons) {
                return $courseCoupons->type_description; //defined in getTypeDescriptionAttribute in the CourseCoupons Model
            });
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
                'data' => 'coupon_code',
                'className' => 'editable',
                'title' => trans('labels.courseFees.coupon_code'),
            ],
            [
                'data' => 'amount',
                'className' => 'editable',
                'title' => trans('labels.courseFees.amount'),
                'render' => "$.fn.dataTable.render.number( ',', '.', 2, )" //format with no $
            ],
            [
                'data' => 'type_description',
                'className' => 'editable',
                'title' => trans('labels.courseFees.type'),
                'editField' => 'type' //displays text (Percent/Value) but edits the hidden type field using values (P/V)
            ],
            [
                'data' => 'type',
                'className' => 'editable',
                'title' => trans('labels.courseFees.type'),
                'visible' => False,
            ],
            [
                'data' => 'expiration_date',
                'className' => 'editable',
                'title' => trans('labels.courseFees.expiration_date'),
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'CourseCoupons_' . date('YmdHis');
    }
}
