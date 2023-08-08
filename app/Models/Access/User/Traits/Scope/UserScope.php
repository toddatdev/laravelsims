<?php

namespace App\Models\Access\User\Traits\Scope;

/**
 * Class UserScope.
 */
// Need this to pull just this sites Users. -jl 2018-03-28 17:16 
use Session;

trait UserScope
{
    /**
     * @param $query
     * @param bool $confirmed
     *
     * @return mixed
     */
    public function scopeConfirmed($query, $confirmed = true)
    {
        return $query->where('users.confirmed', $confirmed)
                    // I added the joins to limit the users to just this site -jl 2018-03-28 15:14 
                      ->join('user_sites', function ($join)
                        {
                            $join->on('user_sites.user_id', '=', 'users.id')
                              ->where('user_sites.site_id', '=', Session::get('site_id'));
                        })
                      ->select('users.*');  //Get just the fields from the user table
    }

    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = true)
    {
        return $query->where('users.status', $status)
                    // I added the joins and the select to limit the users to just this site -jl 2018-03-28 15:14 
                      ->join('user_sites', function ($join)
                        {
                            $join->on('user_sites.user_id', '=', 'users.id')
                              ->where('user_sites.site_id', '=', Session::get('site_id'));
                        })
                      ->select('users.*');  //Get just the fields from the user table
    }
}
