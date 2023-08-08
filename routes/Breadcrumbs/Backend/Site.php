<?php

Breadcrumbs::register('create_site', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('menus.backend.site.create'),  route('create_site'));
});

Breadcrumbs::register('edit_site', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('menus.backend.site.edit'), route('edit_site', $id));
});

Breadcrumbs::register('all_sites', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('menus.backend.site.view-all'),  route('all_sites'));
});

