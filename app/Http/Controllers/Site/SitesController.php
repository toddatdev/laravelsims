<?php

namespace App\Http\Controllers\Site;

use App\Models\Site\Site;
use App\Http\Requests\Site\StoreSiteRequest;
use App\Http\Requests\Site\UpdateSiteRequest;
use App\Http\Controllers\Controller;


class SitesController extends Controller
{
    public function index()
    {

        //$sites = Site::all();
        $sites = Site::orderBy('abbrv')->get();

        return view('sites.index', compact('sites'));
    }

    public function show(Site $site)
    {
        return view('sites.show', compact('site'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    public function update(Site $site, UpdateSiteRequest $request)
    {
        $site->update($request->all());
        return redirect()->route('all_sites');
    }


    public function store(StoreSiteRequest $request)
    {

        Site::create($request->all());

        return redirect()->route('all_sites');

    }
}
