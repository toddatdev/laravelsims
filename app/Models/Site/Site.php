<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use App\Models\Building\Building;
use App\Models\Location\Location;
use App\Models\Course\Course;
use App\Models\Access\User\User;
use App\Models\Resource\Resource;
use App\Models\userProfile\UserProfileQuestion;

class Site extends Model
{
    protected $table;

    protected $fillable = ['abbrv', 'name', 'organization_name', 'email', 'url_root'];

    public static function getSiteIDByURL($url)
    {
        try
        {
            return Site::where('url_root', $url)->firstOrFail()->id;
        }
        catch (Exception $e)
        {
            //if it cannot find this site URL in the database then display page not found
            //return view('404');
        }
    }

    public function getEditButtonAttribute()
    {
        return '<a href="/sites/edit/'.$this->id.'" 
        class="btn-sm btn-primary"><i class="fa fa-fw fa-pencil-alt" 
        data-toggle="tooltip" data-placement="top" 
        title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
    }
    
    public function getOptionsButtonAttribute()
    {
        return '<a href="/site/options/'.$this->id.'" 
        class="btn-sm btn-success"><i class="fa fa-fw fa-cog" 
        data-toggle="tooltip" data-placement="top" 
        title="' . trans('buttons.general.options') . '"></i></a> ';
    }

    /**
     * One to Many Relationship to Location (a Site has many Locations)
     * @version 1.0
     * @author mitcks
     * @date 2018-04-19
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Has Many Through Relationship to Resources via the Location table
     * @version 1.0
     * @author mitcks
     * @date 2018-04-19
     * @since 1.0.0
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function resources()
    {
        return $this->hasManyThrough(
            'App\Models\Resource\Resource',
            'App\Models\Location\Location'
        );
    }


    /**
     * Returns the action buttons for this site as an HTML string. Right now it it Edit and Options
     * @version 1.0
     * @author lutzjw
     * @date:   2018-04-24T11:44:52-0500
     * @since   1.0
     * @return  string   HTML for the action buttons.
     */
    public function getActionButtonsAttribute()
    {
        return  $this->getEditButtonAttribute().
                $this->getOptionsButtonAttribute();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_sites', 'site_id', 'user_id');
    }

    public function user_profile_question()
    {
        return $this->hasMany(UserProfileQuestion::class);
    }

    public function buildings()
	{
		return $this->hasMany(Building::class);
	}
 
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * siteOptions defines the relationship between sites and the site_option models/tables.
     * @version 1.0
     * @author lutzjw
     * @date:   2018-04-24T11:46:52-0500
     * @since   1.0
     * @return  SiteOption                  Collection of SiteOption
     */
    public function siteOptions()
    {
        return $this->hasMany('App\Models\Site\SiteOption') ;     
    } 

    /**
     * Get the value of a site's option from the site_option table given the site_option_id in site_option_id.id
     * @version 1.0
     * @author  lutzjw
     * @date:   2018-04-19T15:58:22-0500
     * @since   1.0.0
     * @param   unsigned int    site_option_id : from table site_options.
     * @return  string          value of that site_option for that site and site_option_id
     */
    public function getSiteOption($site_option_id)
    {
        $options = $this->siteOptions;
        foreach ($options as $option)
        {
            if ($option->site_option_id == $site_option_id) return $option->value;
        }
        // if we get here, we haven't found the ID, return null
        return null;
    }

}
