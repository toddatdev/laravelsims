<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;

class SiteOptions extends Model
{
    protected $table = 'site_options';

    protected $fillable = ['name', 'description', 'client_managed'];
}
