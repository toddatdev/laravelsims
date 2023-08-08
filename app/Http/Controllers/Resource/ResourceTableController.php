<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\Site\Site;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Resource\Resource;
use Session;

class ResourceTableController extends Controller
{

    public function getIndex()
    {
        return view('resourcetables.index');
    }

    public function allData()
    {

        $resources = Site::find(Session::get('site_id'))->resources()->get();

        return DataTables::of($resources)

            ->addColumn('building_location', function($resources){
                return $resources->location->building_location_label;
            })
            ->addColumn('type', function($resources){
                return $resources->type->abbrv;
            })
            ->addColumn('category_abbrv', function($resources){
                return $resources->category->abbrv;
            })
            ->addColumn('sub_category_abbrv', function($resources){
                return $resources->subcategory->abbrv;
            })
            ->addColumn('actions', function($resources) {
                return $resources->action_buttons; //defined in getActionButtonsAttribute in the Resource Model
            })

            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    public function activeData()
    {
        $resources = Site::find(Session::get('site_id'))->resources()
            ->whereNull('resources.retire_date')
            ->get();

        return DataTables::of($resources)

            ->addColumn('building_location', function($resources){
                return $resources->location->building_location_label;
            })
            ->addColumn('type', function($resources){
                return $resources->type->abbrv;
            })
            ->addColumn('category_abbrv', function($resources){
                return $resources->category->abbrv;
            })
            ->addColumn('sub_category_abbrv', function($resources){
                return $resources->subcategory->abbrv;
            })
            ->addColumn('actions', function($resources) {
                return $resources->action_buttons; //defined in getActionButtonsAttribute in the Resource Model
            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }

    public function inactiveData()
    {
        $resources = Site::find(Session::get('site_id'))->resources()
            ->whereNotNull('resources.retire_date')
            ->get();

        return DataTables::of($resources)

            ->addColumn('building_location', function($resources){
                return $resources->location->building_location_label;
            })
            ->addColumn('type', function($resources){
                return $resources->type->abbrv;
            })
            ->addColumn('category_abbrv', function($resources){
                return $resources->category->abbrv;
            })
            ->addColumn('sub_category_abbrv', function($resources){
                return $resources->subcategory->abbrv;
            })
            ->addColumn('actions', function($resources) {
                return $resources->action_buttons; //defined in getActionButtonsAttribute in the Resource Model
            })

            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }
}
