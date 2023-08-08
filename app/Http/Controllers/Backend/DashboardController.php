<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Site\Site;
use Session;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
 */
    public function index()
    {
        // Put the site info for the banner, etc. -jl 2018-04-16 15:28 
        $site = Site::find(SESSION::get('site_id'));
        return view('backend.dashboard', compact('site'));
    }
}
