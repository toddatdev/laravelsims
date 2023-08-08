<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class CourseCategoryGroup extends Model
{
    protected $table;

    protected $fillable = ['abbrv', 'description', 'site_id'];

    //each category group has many categories
    public function CourseCategories()
    {
        return $this->hasMany(CourseCategories::class);
    }

}
