<div class="pull-right d-none d-lg-block">
{{ link_to_route('active_resources', trans('menus.backend.resource.view-active'), [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('resource_create', trans('menus.backend.resource.create'), [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('deactivated_resources', trans('menus.backend.resource.view-inactive'), [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('all_resources', trans('menus.backend.resource.view-all'), [], ['class' => 'btn btn-info btn-sm']) }}
</div><!--pull right-->

<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.resource.tasks') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('active_resources', trans('menus.backend.resource.view-active')) }}</li>
            <li>{{ link_to_route('resource_create', trans('menus.backend.resource.create')) }}</li>
            <li>{{ link_to_route('deactivated_resources', trans('menus.backend.resource.view-inactive')) }}</li>
            <li>{{ link_to_route('all_resources', trans('menus.backend.resource.view-all')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->