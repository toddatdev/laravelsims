<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Site\Site;

class CheckSite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //get the site_id from the database based on the root_url
        $rootURL = $request->root();
        $rootURLWithoutHttp = strtolower(substr($rootURL, strpos($rootURL, "/")+2));

        //ID
        $site_id = Site::getSiteIDByURL($rootURLWithoutHttp);
        $request->session()->put('site_id', $site_id);

        //Site Help Email (access in blade with {{ Session::get('site_email') }})
        $request->session()->put('site_email', Site::find($site_id)->email);

        //Site URL Root (access in blade with {{ Session::get('url_root') }})
        $request->session()->put('url_root', Site::find($site_id)->url_root);

        //Site Abbrv (access in blade with {{ Session::get('site_abbrv') }})
        $request->session()->put('site_abbrv', Site::find($site_id)->abbrv);

        //Business Start Hour (access in blade with {{ Session::get('business_start_hour') }})
        $request->session()->put('business_start_hour', Site::find($site_id)->getSiteOption(6));

        //Business End Hour (access in blade with {{ Session::get('business_end_hour') }})
        $request->session()->put('business_end_hour', Site::find($site_id)->getSiteOption(7));

        $minutes = array();
        for ($i = 0; $i <= 120; $i+=15 ) {
            $minutes[$i] = $i.' '.trans('labels.general.minutes');
        }
        $request->session()->put('minutes', $minutes);

        return $next($request);
    }
}
