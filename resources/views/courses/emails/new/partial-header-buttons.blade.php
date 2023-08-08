<!-- large resolutions menu -->
<div class="pull-right d-none d-lg-block">
    @if (Session::get('breadcrumbLevel1') !== 'mycourses')
        <a id="return-course" class="btn btn-primary btn-sm">{{ trans('menus.backend.course.focus') }}</a>
    @endif
    <a href="/courses/courseInstanceEmails?type=2" id="course" class="btn btn-warning btn-sm">{{ trans('menus.backend.siteEmails.course') }}</a>
    <a href="/courses/courseInstanceEmails?type=3" id="event" class="btn btn-danger btn-sm">{{ trans('menus.backend.siteEmails.event') }}</a>
    @if(!empty($create))
        {{ link_to_route('course.create.course', trans('menus.backend.siteEmails.btn'), [], ['class' => 'btn btn-success btn-sm', 'id'=> 'create-email']) }}
    @endif
</div>

<!--  mobile menu -->
<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Emails <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            @if (Session::get('breadcrumbLevel1') !== 'mycourses')
                <li id="return-course-mobile"><a id="mobile-back">{{ trans('menus.backend.course.focus') }}</a></li>
            @endif
            <li id="course-mobile"><a href="/courses/courseInstanceEmails?type=2">{{ trans('menus.backend.siteEmails.course') }}</a></li>
            <li id="event-mobile"><a href="/courses/courseInstanceEmails?type=3">{{ trans('menus.backend.siteEmails.event') }}</a></li>
            @if(!empty($create))
                <li>{{ link_to_route('course.create.course', trans('menus.backend.siteEmails.btn'), [], ['id'=> 'create-email-mobile']) }}</li>
            @endif
        </ul>
    </div>
</div>

<div class="clearfix"></div>

<script>
    // set 
    @if(isset($via))
        via = "{{ $via }}";
        localStorage.setItem("via", via);
    @endif


    // Set href for back button
    var backBtn = document.getElementById('return-course');
    if (localStorage.getItem("via") === 'catalog') {
        // send back to frontend
        backBtn.href = "/courses/catalog";
    }
    else if(localStorage.getItem("via") === 'courses') {
        backBtn.href = "/courses";
    }
    else {
        // back to backend
        backBtn.href = "/courses/active?id={{ Session::get('course_id') }}";
    }
    // clean up
    document.getElementById('return-course').addEventListener("click", cleanup);



    // Set href for back button mobile
    var backBtnMobile = document.getElementById('mobile-back');
    if (localStorage.getItem("via") === 'catalog') {
        // send back to frontend
        backBtnMobile.href = "/courses/catalog";
    }
    else if(localStorage.getItem("via") === 'courses') {
        backBtnMobile.href = "/courses";
    }
    else {
        // back to backend
        backBtnMobile.href = "/courses/active?id={{ Session::get('course_id') }}";
    }

    // clean up
    document.getElementById('mobile-back').addEventListener("click", cleanup);


    function cleanup() {
        localStorage.removeItem('via');
    }

</script>