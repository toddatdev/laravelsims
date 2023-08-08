<?php

namespace App\Models\CourseContent\QSE;

use Illuminate\Database\Eloquent\Model;

class PresentationType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
