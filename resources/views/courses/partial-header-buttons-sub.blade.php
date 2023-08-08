<div class="pull-right d-none d-lg-block">
@if(!empty($course->id))
    {{ link_to_route('active_courses', trans('menus.backend.course.focus'), ['id'=>$course->id], ['class' => 'btn btn-primary btn-sm']) }}
    @endif
    {{ link_to_route('active_courses', trans('menus.backend.course.view-active'), [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('deactivated_courses', trans('menus.backend.course.view-inactive'), [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('all_courses', trans('menus.backend.course.view-all'), [], ['class' => 'btn btn-info btn-sm']) }}
</div><!--pull right-->

<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.course.tasks') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('active_courses', trans('menus.backend.course.view-active')) }}</li>
            <li>{{ link_to_route('deactivated_courses', trans('menus.backend.course.view-inactive')) }}</li>
            <li>{{ link_to_route('all_courses', trans('menus.backend.course.view-all')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->