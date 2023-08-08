<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('active_user_profile_question', trans('menus.backend.user-profile-questions.active'), [], ['class' => 'btn btn-primary btn-xs']) }}
    {{ link_to_route('create_user_profile_question', trans('menus.backend.user-profile-questions.create'), [], ['class' => 'btn btn-success btn-xs']) }}
    {{ link_to_route('retired_user_profile_question', trans('menus.backend.user-profile-questions.retired'), [], ['class' => 'btn btn-warning btn-xs']) }}
    {{ link_to_route('all_user_profile_questions', trans('menus.backend.user-profile-questions.view-all'), [], ['class' => 'btn btn-info btn-xs']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.user-profile-questions.questions') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('active_user_profile_question', trans('menus.backend.user-profile-questions.active')) }}</li>
            <li>{{ link_to_route('create_user_profile_question', trans('menus.backend.user-profile-questions.create')) }}</li>
            <li>{{ link_to_route('retired_user_profile_question', trans('menus.backend.user-profile-questions.retired')) }}</li>
            <li>{{ link_to_route('all_user_profile_questions', trans('menus.backend.user-profile-questions.view-all')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>