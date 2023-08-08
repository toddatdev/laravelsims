<div class="pull-right mb-10 hidden-md hidden-sm hidden-xs">

    @if(!empty($courseEmails->id))
        {{ link_to_route('courses/courseInstanceEmails.id', trans('menus.backend.courseEmails.focus'), ['id'=>$courseEmails->id], ['class' => 'btn btn-primary btn-xs']) }}
    @endif

    {{ link_to_route('courseInstanceEmails.index', trans('menus.backend.courseEmails.view-active'), [], ['class' => 'btn btn-primary btn-xs']) }}
    {{ link_to_route('courseInstanceEmails.create', trans('menus.backend.courseEmails.create'), [], ['class' => 'btn btn-success btn-xs']) }}
    {{ link_to_route('courses/courseInstanceEmails.option', trans('menus.backend.courseEmails.view-retired'), ['method' => 'retired'], ['class' => 'btn btn-warning btn-xs']) }}
    {{ link_to_route('courses/courseInstanceEmails.option', trans('menus.backend.courseEmails.view-all'), ['method' => 'all'], ['class' => 'btn btn-info btn-xs']) }}
</div>

<div class="pull-right mb-10 hidden-lg ">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.courseEmails.tasks') }} <span class="caret"></span>
        </button>
        <!-- Need to be Links to Route ... -->
        <ul class="dropdown-menu" role="menu">
            @if(!empty($courseEmails->id))
                <li>{{ link_to_route('courses/courseInstanceEmails.id', trans('menus.backend.courseEmails.focus'), ['id'=>$courseEmails->id], ['class' => 'btn btn-primary btn-xs']) }}</li>
            @endif
               <li>{{ link_to_route('courseInstanceEmails.index', trans('menus.backend.courseEmails.view-active'), [], ['class' => 'btn btn-primary btn-xs']) }}</li>
               <li>{{ link_to_route('courseInstanceEmails.create', trans('menus.backend.courseEmails.create'), [], ['class' => 'btn btn-success btn-xs']) }}</li>
               <li>{{ link_to_route('courses/courseInstanceEmails.option', trans('menus.backend.courseEmails.view-retired'), ['method' => 'retired'], ['class' => 'btn btn-warning btn-xs']) }}</li>
               <li>{{ link_to_route('courses/courseInstanceEmails.option', trans('menus.backend.courseEmails.view-all'), ['method' => 'all'], ['class' => 'btn btn-info btn-xs']) }}</li>
        </ul>
    </div>
</div>

<div class="clearfix"></div>