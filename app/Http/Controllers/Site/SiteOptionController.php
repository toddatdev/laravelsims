<?php

namespace App\Http\Controllers\Site;

use App\Models\Site\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Site\SiteOption;
use Illuminate\Support\Facades\DB; //To pull the option names from the site_option table
use Session;
use Auth;
use Debugbar;

class SiteOptionController extends Controller
{
    
	/**
	 * Create a list of options for the site with the ID of $id. We need to make the SQL a join with the site_options 
	 * table so we get the text name of the option. 
	 * -jl 2018-04-19 11:31 
	 * @param type $id -> sites.id
	 * @return view to the site/options.blade.php file.
	 */
    public function indexForEdit($id)
	{
		$site = Site::find($id);
		$user = Auth::user();
		if ($user->hasPermission('client-manage-site-options', false)) {
			if($id == SESSION::get('site_id')) {
				$siteOptions = DB::table('site_options')
								->leftJoin('site_option', function ($join) use($id) {
									$join->on('site_option.site_option_id', '=', 'site_options.id')
										->where('site_option.site_id', '=', $id);
									})
								->select('site_options.name', 'site_options.id', 'site_option.value', 'site_options.description')
								->orderBy('site_options.name')
								->where('site_options.client_managed', '=', 1)
								->get();
			}else {
				return redirect()->back()->withFlashDanger(trans('auth.general_error'));
			}

		} else {
			$siteOptions = DB::table('site_options')
			->leftJoin('site_option', function ($join) use($id) {
				$join->on('site_option.site_option_id', '=', 'site_options.id')
					->where('site_option.site_id', '=', $id);
				})
			->select('site_options.name', 'site_options.id', 'site_option.value', 'site_options.description')
			->orderBy('site_options.name')
			->get();
		}
						 
  		$optionCount = count($siteOptions);
		
		  
        return view('sites.options', compact('siteOptions', 'optionCount', 'site'));
    }

	/**
	 * Updates site_otion with each option set in options.blade.php. Steps through each of the sets of options.
	 * Creates new options if they do not exist it the site_option table.
	 * -jl 2018-04-19 11:23 
	 * @param Request $request 
	 * @return view back to the list of sites.
	 */
	public function updateAll(Request $request)
	{
		// echo '<div>There are '.$request['optionCount'].' options for site ID '.$request['siteId'].' here.</div>';
		for ($i=1; $i <=$request['optionCount']; $i++) 
		{ 
			$siteOption = new SiteOption;
			// echo ('<div>Option ID # '.$request['option_id_'.$i].' has a value of '. $request['value_'.$i].'.</div>');
			siteOption::updateOrCreate(['site_id'        => $request['siteId'], 
									    'site_option_id' => $request['option_id_'.$i]],
									   ['value' => $request['value_'.$i]]);
		}

		return redirect()->back()->withFlashSuccess(trans('alerts.backend.options.update'));
	} 

	/**
	 * Just a dummy function to do testing with.
	 * -jl 2018-04-19 11:24 
	 * @return type
	 */
	public function indexData()
	{
		$siteOptions = SiteOption::where('site_id', SESSION::get('site_id'))->get();
		foreach ($siteOptions as $siteOption)
		{
			echo $siteOption->value.'<br>';
		}
		return $siteOptions;
	} 
}
