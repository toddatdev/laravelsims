<?php

namespace App\Models\Location;
// 
use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;
use App\Models\Building\Building;
use App\Models\Resource\Resource;
use App\Models\Location\LocationScope;

class Location extends Model
{
    protected $table;
    protected $fillable = ['site_id', 'building_id', 'abbrv', 'name', 'more_info', 'directions_url', 'display_order', 'html_color', 'retired_date'];

    //Here is the "scope" section
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LocationScope);
    }

    //Here is the "relationship" section 
    //return the Locations Building
    public function building()
    {
        return $this->belongsTo(Building::class);      
    }

    // Allow API to use forwards model relationship
    public function BuildingToTouch() {
        return $this->building()->withoutGlobalScopes();
    }

    /**
     * One to Many Relationship to Resource (a Location has many Resources)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * Many to One Relationship to Site via site_id (a site has many locations)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    //End\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    ////////////////////////////////////////
    //Here is the "Traits Functions" section 
    //If the URL link is not null, return a link on the abbrv, else just return the abbrv.
    public function urlLink()
    {
        return (!is_null($this->directions_url)) ? '<a href="' .$this->directions_url. '">'.$this->abbrv.'</a>' : $this->abbrv;
    }


    public function fullUrlLinks()
    {
        return $this->building->urlLink()."&nbsp;:&nbsp;".$this->urlLink();
    }
 
    public function isRetired()
    {
        return $this->retire_date != null;
    }

    // Here is the "Build Action Buttons" section for the DataTables
	public function getShowButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view') . '">
                <a href="/locations/show/'.$this->id.'" class="btn-sm btn-info">
                <i class="fa fa-search fa-fw"></i></a></span>&nbsp;';
    }

	public function getEditButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit') . '">
                <a href="/locations/edit/'.$this->id.'" class="btn-sm btn-primary">
                <i class="fa fa-pencil-alt fa-fw"></i></a></span>&nbsp;';
    }

    public function getRetiredButtonAttribute()
    {
        if ($this->isRetired())
        {  //Green Play button to activate location
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.locations.activate') . '">
                    <a href="/locations/activate/'.$this->id.'" class="btn-sm btn-success">
                    <i class="fa fa-play fa-fw"></i></a></span>&nbsp;';
        }
        else
        {  //Yellow Pause button to retire the location
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.locations.retire') . '">
                    <a href="/locations/retire/'.$this->id.'" data-trans-button-confirm="'.trans('buttons.backend.locations.retire').'" data-trans-title="'.trans('labels.locations.retire_wall').'" name="warning_item" class="btn-sm btn-warning">
                    <i class="fa fa-pause fa-fw text-light" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.locations.retire').'""></i></a></span>&nbsp;';
        }
    }

    public function getSchelulerButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.schedulers') . '">
                <a href="/locations/schedulers/'.$this->id.'" class="btn-sm btn-success">
                <i class="fa fa-user fa-fw"></i></a></span>&nbsp;';
    }

    // Build all the buttons for the column. In Laravel this ia access via the $location->action_button attribute.
    public function getActionButtonsAttribute()
    {
    	return  $this->getShowButtonAttribute().
                $this->getEditButtonAttribute().
                $this->getRetiredButtonAttribute().
                $this->getSchelulerButtonAttribute();
	}

    public function getBuildingLocationLabelAttribute()
    {
        return $this->building->abbrv. ' - ' .$this->abbrv;
    }


}
