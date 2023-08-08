<div class="pull-right d-none d-lg-block">
    {{ link_to_route('active_buildings', trans('menus.backend.building.view-active'), [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('retired_buildings', trans('menus.backend.building.view-retired'), [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('all_buildings', trans('menus.backend.building.view-all'), [], ['class' => 'btn btn-sm btn-info']) }}
</div><!--pull right-->

<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.building.tasks') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('active_buildings', trans('menus.backend.building.view-active')) }}</li>
            <li>{{ link_to_route('retired_buildings', trans('menus.backend.building.view-retired')) }}</li>
            <li>{{ link_to_route('all_buildings', trans('menus.backend.building.view-all')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>