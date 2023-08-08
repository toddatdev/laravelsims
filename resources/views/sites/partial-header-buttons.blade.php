@permission(!'client-manage-site-options')
<div class="mb-10 ">
    {{ link_to_route('all_sites', trans('menus.backend.site.view-all'), [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('create_site', trans('menus.backend.site.create'), [], ['class' => 'btn btn-success btn-sm']) }}
</div><!--pull right-->
@endauth

<div class="clearfix"></div>