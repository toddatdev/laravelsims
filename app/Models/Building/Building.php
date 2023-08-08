<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site\Site;
use App\Models\Location\Location;
use App\Models\Building\BuildingScope;

class Building extends Model
{
    protected $table;
    protected $fillable = ['site_id', 'abbrv', 'name', 'more_info', 'map_url', 'address', 'city', 'state', 'postal_code', 'display_order', 'retire_date', 'timezone'];

    //Here is the "scope" section to limit it to just this Site's buildings. Defined in BuildingScope.php
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BuildingScope);
    }

    //Here is the "relationship" section    
    //return this Building's Site
    public function site()
    {
        return $this->belongsTo(Site::class);      
    }

    public function SiteToTouch() {
        return $this->site()->withoutGlobalScopes();
    }

    //return a collection of the Buildings locations.
    public function locations()
    {
        return $this->hasMany(Location::class); 
    }

    //Here is the "Traits Functions" section 
    //If the URL link is not null, return a link on the abbrv, else just return the abbrv.
    public function urlLink()
    {
        return (!is_null($this->map_url)) ? '<a href="' .$this->map_url. '">'.$this->abbrv.'</a>' : $this->abbrv;
    }

    public function isRetired()
    {
        return $this->retire_date !== null;
    }

    // Get buttons for the building listing page
	public function getShowButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                <a href="/buildings/show/'.$this->id.'" class="btn-sm btn-info">
                <i class="fa fa-search fa-fw"></i></a></span>&nbsp;';
    }

	public function getEditButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                <a href="/buildings/edit/'.$this->id.'" class="btn-sm btn-primary">
                <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
    }

    public function getRetiredButtonAttribute()
    {
        if ($this->isRetired())
        {  //Green Play button to activate building
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.buildings.activate') . '">
                    <a href="/buildings/activate/'.$this->id.'" class="btn-sm btn-success">
                    <i class="fa fa-play fa-fw"></i></a></span>&nbsp;';
        }
        else
        {  //Yellow Pause button to retire the building
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.buildings.retire') . '">
                    <a href="/buildings/retire/'.$this->id.'" data-trans-button-confirm="'.trans('buttons.backend.buildings.retire').'" data-trans-title="'.trans('labels.buildings.retire_wall').'" name="warning_item" class="btn-sm btn-warning">
                    <i class="fa fa-pause fa-fw text-light"></i></a></span>&nbsp;';
        }
    }

    // Build all the buttons for the column. In Laravel this ia access via the $building->action_button attribute.
    public function getActionButtonsAttribute()
    {
    	return $this->getShowButtonAttribute().
    		$this->getEditButtonAttribute().
            $this->getRetiredButtonAttribute();
	}
}

