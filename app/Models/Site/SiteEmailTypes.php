<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;

class SiteEmailTypes extends Model {

    protected $table = 'email_types';
    
    protected $fillable = ['name'];

}