<div class="pull-right d-none d-lg-block">
    {{ link_to_route('resource_category_create', trans('buttons.backend.resources.addCategory'), [], ['class' => 'btn btn-success btn-sm']) }}
</div><!--pull right-->

<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.resourceCategory.tasks') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            {{--<li>{{ link_to_route('active_resources', trans('menus.backend.resource.view-active')) }}</li>--}}
            <li>{{ link_to_route('resource_category_create', trans('buttons.backend.resources.addCategory')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->
