<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    protected $table;

    protected $fillable = ['user_id', 'site_id'];

}
