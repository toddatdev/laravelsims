<?php

namespace App\models\location;

use App\Models\Access\User\User;
use Illuminate\Database\Eloquent\Model;

class LocationSchedulers extends Model
{
    protected $table = 'location_schedulers';

    public $timestamps = false;

    protected $fillable = ['location_id', 'user_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
