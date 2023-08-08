<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;

class SiteOption extends Model
{
    protected $table = 'site_option';

    protected $fillable = ['site_id', 'site_option_id', 'value'];
}
