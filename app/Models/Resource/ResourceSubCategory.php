<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class ResourceSubCategory extends Model
{

    protected $table = 'resource_sub_category';

    protected $fillable = ['resource_category_id', 'abbrv', 'description', 'site_id', 'retire_date'];

    /**
     * One to Many Relationship to Resources via the resourse_sub_category_id
     * @version 1.0
     * @author mitcks
     * @date 2018-04-27
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany(Resource::class, 'resource_sub_category_id');
    }

    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id');
    }

    /**
     * CAN DELETE
     *
     * If the subcategory has not been used in the resources table then it can be deleted
     *
     * @return boolean
     */
    public function canDelete()
    {
        if ($this->resources()->exists())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
