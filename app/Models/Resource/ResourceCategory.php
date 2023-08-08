<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;


class ResourceCategory extends Model
{

    protected $table = 'resource_category';

    protected $fillable = ['resource_type_id', 'abbrv', 'description', 'site_id', 'retire_date'];

    /**
     * One to Many Relationship to Resources via the resourse_category_id
     * @version 1.0
     * @author mitcks
     * @date 2018-04-27
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany(Resource::class, 'resource_category_id');
    }

    public function subcategory()
    {
        return $this->hasMany(ResourceSubCategory::class, 'resource_category_id');
    }

    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }

    /**
     * CAN DELETE
     *
     * If the category has no subcategories and has not been used in the resources table
     * then it can be deleted
     *
     * @return boolean
     */
    public function canDelete()
    {
        if ($this->resources()->exists() or $this->subcategory()->exists())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
