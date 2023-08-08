<div class="pull-right d-none d-lg-block">
    {{ link_to_route('active_courses', trans('menus.backend.course.focus'), ['id'=>Session::get('course_id')], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('course_coupons', trans('menus.backend.courseCoupons.title'), [Session::get('course_id')], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('courseFeeTypes.index', trans('menus.backend.courseFeeTypes.title'), ['id'=>Session::get('course_id')], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('active_courses', trans('menus.backend.course.view-active'), [], ['class' => 'btn btn-primary btn-sm']) }}
</div><!--pull right-->

<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.courseFees.tasks') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('active_courses', trans('menus.backend.course.focus'), ['id'=>Session::get('course_id')]) }}</li>
            <li>{{ link_to_route('course_coupons', trans('menus.backend.courseCoupons.title'), [Session::get('course_id')]) }}</li>
            <li>{{ link_to_route('courseFeeTypes.index', trans('menus.backend.courseFeeTypes.title'), [Session::get('course_id')]) }}</li>
            <li>{{ link_to_route('active_courses', trans('menus.backend.course.view-active')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->