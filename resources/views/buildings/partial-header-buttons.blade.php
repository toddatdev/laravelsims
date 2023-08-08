<div class="pull-right d-none d-lg-block">
    {{ link_to_route('active_buildings', trans('menus.backend.building.view-active'), [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('create_building', trans('menus.backend.building.create'), [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('retired_buildings', trans('menus.backend.building.view-retired'), [], ['class' => 'btn btn-warning text-white btn-sm']) }}
    {{ link_to_route('all_buildings', trans('menus.backend.building.view-all'), [], ['class' => 'btn btn-sm btn-info']) }}
</div><!--pull right-->

<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.building.tasks') }} <span class="caret"></span>
        </button>

        {{-- width is set here so the text for View Retired Building does not wrap--}}
        <ul class="dropdown-menu" role="menu" style="width: 175px !important;">
            <li>{{ link_to_route('active_buildings', trans('menus.backend.building.view-active')) }}</li>
            <li>{{ link_to_route('create_building', trans('menus.backend.building.create')) }}</li>
            <li>{{ link_to_route('retired_buildings', trans('menus.backend.building.view-retired')) }}</li>
            <li>{{ link_to_route('all_buildings', trans('menus.backend.building.view-all')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->