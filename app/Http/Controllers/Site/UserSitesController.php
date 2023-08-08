<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Site\UserSite;

class UserSitesController extends Controller
{

    public function addUserSite($userid, $siteid)
    {
        $usersite = UserSite::firstOrCreate(
            ['user_id' => $userid, 'site_id' => $siteid],
            ['user_id' => $userid, 'site_id' => $siteid]
        );
    }

}
