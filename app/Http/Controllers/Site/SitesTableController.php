<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Site\Site;
use App\Models\Access\User\User;

// Use this to get the site_id -jl 2018-03-28 21:19 
use Session;

class SitesTableController extends Controller
{

    public function getIndex()
    {
        return view('sitetables.index');
    }

    public function indexData()
    {
        return Site::find(Session::get('site_id'));
    } 
    public function buildings($id)
    {
        return Site::find($id)->buildings;
    }
 
    //Get a collection of all of this sites users -jl 2018-03-28 14:25 
    public function getUsers()
    {
        return Site::find(Session::get('site_id'))->users;          
    } 
/**
 * Returns the list of all sites with the edit and option buttons.
 * @version 1.0
 * @author lutzjw
 * @date:   2018-04-24T11:37:33-0500
 * @since   1.0]
 * @return  DataTable                   includes all fhte fields, sorted by abbrv, and the action buttons (edit, options, etc)..
 */
    public function anyData()
    {
        $sites = Site::orderBy('abbrv')->get();

        return DataTables::of($sites)

            ->addColumn('actions', function($site) {
                return $site->action_buttons;

            })
            ->rawColumns(['actions', 'confirmed'])
            ->make(true);
    }
}
