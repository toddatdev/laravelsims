<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Site\Site;
use Auth;
use Session;
/**
 * Class FrontendController.
 */
class FrontendController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Put the site info for the banner, etc. -jl 2018-04-16 15:28 
        $site = Site::find(SESSION::get('site_id'));
        $user = Auth::user(); // See if the user is logged in
        if ($user){
            return redirect()->route('frontend.user.dashboard'); //just point them to the dashboard
        }
        else {
            return view('frontend.auth.login', compact('site')); //make them log in.
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function macros()
    {
        return view('frontend.macros');
    }
}
