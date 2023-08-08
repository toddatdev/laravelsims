<?php

namespace App\Http\Middleware;

use Closure;

class AuthKey
{
    /**
     * Handle an incoming request. 
     * All Api request unless specified by its own middleware Need to match our pusher app key
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = '';

        // Keeping this here for testing locally since the APP_KEY doesn't really work on local Laravel web servers
        // -jl 2020-05-18 16:19
        // return $next($request);

        // By Our Apache Config, http custom headers don't get set unless you ask nicely 
        $headers = apache_request_headers();
        if (isset($headers['APP_KEY'])) {
            $token = $headers['APP_KEY'];
        }        

        if ($token != env('PUSHER_APP_KEY')) {
            return response()->json(['message' => 'Cannot Perform Request, App Key Not Found.'], 401);
        }
        return $next($request);
    }
}
