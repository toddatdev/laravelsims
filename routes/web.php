<?php

/**
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', 'LanguageController@swap');

/* ----------------------------------------------------------------------- */

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__ . '/Frontend/');
});

/*
 * All routes in this group require authentication
 * -jl 2019-05-17 14:00
 */

Route::group(['middleware' => 'auth'], function () {

    /* ----------------------------------------------------------------------- */

    /*
     * Backend Routes
     * Namespaces indicate folder structure
     */
    Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
        /*
         * These routes need view-backend permission
         * (good if you want to allow more than one group in the backend,
         * then limit the backend features by different roles or permissions)
         *
         * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
         */
        includeRouteFiles(__DIR__ . '/Backend/');
    });

    /**
     * Site Management
     */

    Route::group(['middleware' => 'access.routeNeedsPermission:manage-sites'], function () {
        Route::get('/sites/all', 'Site\SitesController@index')->name('all_sites'); //to bring back all sites data
        Route::get('/sites/show/{site}', 'Site\SitesController@show'); //show a site

        Route::get('/sites/create', 'Site\SitesController@create')->name('create_site'); //create a site
        Route::post('/sites', 'Site\SitesController@store');

        Route::get('/sites/edit/{site}', 'Site\SitesController@edit')->name('edit_site'); //edit a site
        Route::patch('/sites/update/{site}', 'Site\SitesController@update')->name('update_site');

        //For DataTables
        Route::get('sitetables', 'Site\SitesTableController@getIndex')->name('sitetables');
        Route::get('sitetables.data', 'Site\SitesTableController@anyData')->name('sitetables.data');

        //Debugging
        Route::get('sitebuildings.data/{id}', 'Site\SitesTableController@buildings')->name('sitebuildings.data');
        Route::get('siteindex.data', 'Site\SitesTableController@indexData')->name('indexData.data');
        Route::get('siteoptions.data', 'Site\SiteOptionController@indexData')->name('siteoptions.data');

        //quick Test
        // Disabled to run $ php artisan route:list
        // Route::get('users.data', 'Backend\Access\UserTableController@listUsers')->name('userlist.data');

    });

    //Manage Roles
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-roles;manage-sites'], function () {

        Route::post('/site/users/add', 'Site\SiteUsersController@store')->name('site.user.store');
        Route::get('/site/users/delete/{id}', 'Site\SiteUsersController@delete')->name('site.user.delete');

        Route::get('/site/users/{role_id}', 'Site\SiteUsersController@users')->name('site_users');
        Route::get('siteuserstable.data', 'Site\SiteUsersController@siteUsersTableData')->name('siteusers.table'); // datatable

    });


    Route::group(['middleware' => 'access.routeNeedsPermission:client-manage-site-options'], function () {

        Route::get('/site/options/{site}', 'Site\SiteOptionController@indexForEdit')->name('list_site_options');
        Route::post('/site/options/update', 'Site\SiteOptionController@updateAll')->name('update_all_options');

    });

    /**
     * User Profile Questions Management
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-userprofilequestions'], function () {
        Route::get('/user-profile-questions/all', 'UserProfile\UserProfileController@index')->name('all_user_profile_questions');

        //Active and Retired Profile Questions
        Route::get('/user-profile-questions/active', 'UserProfile\UserProfileQuestionController@activeQuestions')->name('active_user_profile_question');
        Route::get('/user-profile-questions/retired', 'UserProfile\UserProfileQuestionController@retiredQuestions')->name('retired_user_profile_question');

        Route::get('/user-profile-questions/create', 'UserProfile\UserProfileController@create')->name('create_user_profile_question'); //create a Profile Question
        Route::post('/user-profile-questions', 'UserProfile\UserProfileController@store');


        //For DataTables
        Route::get('questiontables', 'UserProfile\UserProfileController@index')->name('questiontables');
        Route::post('questiontables.data', 'UserProfile\UserProfileController@updateDisplayOrder')->name('questiontables.data');

        //Edit Question
        Route::get('/user-profile-questions/edit/{id}', 'UserProfile\UserProfileController@edit')->name('edit_user_profile_question');

        //Delete Question
        Route::get('/user-profile-questions/deleteconfirm/{question}', 'UserProfile\UserProfileController@deleteconfirm')->name('delete_user_profile_question');
        Route::delete('/user-profile-questions/delete/{id}', array('uses' => 'UserProfile\UserProfileController@destroy', 'as' => 'question.delete'));

        //Retire Question
        Route::get('/user-profile-questions/retire/{id}', 'UserProfile\UserProfileController@retireQuestion')->name('retire_user_profile_question');

        //Activate Question
        Route::get('/user-profile-questions/activate/{id}', 'UserProfile\UserProfileController@activateQuestion')->name('activate_user_profile_question');

    });


    /**
     * Course Management (General Items Available to Several Permissions)
     * TODO: CHECK THE PERMISSIONS ON THIS SECTION - right now it looks like someone who can do templates can activate/decativate course?
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-courses;manage-templates;course_categories;course-options'], function () {
        Route::get('/courses/all', 'Course\CourseController@all')->name('all_courses'); //to bring back all course data
        Route::get('/courses/active', 'Course\CourseController@active')->name('active_courses');
        Route::get('/courses/deactivated', 'Course\CourseController@deactivated')->name('deactivated_courses');

        Route::get('/courses/show/{course}', 'Course\CourseController@show'); //show a course

        //For DataTables
        Route::get('coursetables', 'Course\CoursesTableController@getIndex')->name('coursetables');
        Route::get('coursesall.data', 'Course\CoursesTableController@allData')->name('coursetables.all');
        Route::get('coursesactive.data', 'Course\CoursesTableController@activeData')->name('coursetables.active');
        Route::get('coursesinactive.data', 'Course\CoursesTableController@inactiveData')->name('coursetables.inactive');

    });

    /**
     * Course Managers Only
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-courses'], function () {

        Route::get('/courses/create', 'Course\CourseController@create')->name('create_course'); //create a course
        Route::post('/courses', 'Course\CourseController@store');

        Route::get('/courses/edit/{course}', 'Course\CourseController@edit')->name('edit_course'); //edit a course
        Route::patch('/courses/update/{course}', 'Course\CourseController@update')->name('update_course');

        //For Image Upload
        Route::get('/courses/upload-image/{course}', 'Course\CourseController@imageUpload')->name('upload-image'); //upload catalog image
        Route::patch('/courses/update-image/{course}', 'Course\CourseController@imageUploadCourse')->name('update_course_image');

        //For Image Delete
        Route::get('/courses/delete-image/{course}', 'Course\CourseController@imageDelete')->name('delete-image'); //delete catalog image
        Route::patch('/courses/remove-image/{course}', 'Course\CourseController@imageDeleteCourse')->name('remove_course_image');

        Route::get('/courses/deactivate/{course}', 'Course\CourseController@deactivateCourse')->name('deactivate');
        Route::get('/courses/activate/{course}', 'Course\CourseController@activateCourse')->name('activate');

        //this is to call code to make parking lot events for existing courses (one time thing)
        Route::get('/courses/makeParkingLotEvents', 'Course\CourseController@makeParkingLotEvents')->name('makeParkingLotEvents');

    });

    /**
     * Course Content
     * manage-course-curriculum -- Permission to ALL courses
     * course-manage-course-curriculum -- Permission to SPECIFIC course
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-course-curriculum'], function () {
        //Route::get('/courses/courseContent/edit/{id}', 'Course\CourseContentController@index' );
    });

    // TODO: Permissions for Course Content
    Route::post('/course/content/updateOrder', 'CourseContent\CourseContentController@updateOrder');
    Route::get('/course/content/{id}/duplicate', 'CourseContent\CourseContentController@duplicate');
    Route::get('/course/content/{id}/{type}/publish', 'CourseContent\CourseContentController@publish');
    Route::get('/course/content/{id}/publishmodule', 'CourseContent\CourseContentController@publishmodule');
    Route::get('/course/content/{id}/delete', 'CourseContent\CourseContentController@destroy');
    Route::get('/course/content/{id}/retire', 'CourseContent\CourseContentController@retire');
    Route::get('/course/content/{id}/retiremodule', 'CourseContent\CourseContentController@retireModule');
    Route::get('/course/content/{id}/activate', 'CourseContent\CourseContentController@activate');
    Route::get('/course/content/{id}/activatemodule', 'CourseContent\CourseContentController@activateModule');
    Route::get('/course/content/{id}/indent', 'CourseContent\CourseContentController@indent');
    Route::get('/course/content/{id}/outdent', 'CourseContent\CourseContentController@outdent');
    Route::resource('/course/content', 'CourseContent\CourseContentController');

    Route::post('/course/content/{id}/uploadimage', 'CourseContent\CourseContentController@uploadImage');
    Route::post('/course/content/{id}/uploadFile', 'CourseContent\CourseContentController@uploadFile')->name('course.file.upload');

    Route::resource('/course/content/page', 'CourseContent\PagesController');
    Route::get('/course/content/page/{id}/{type}', 'CourseContent\PagesController@show');

    // QSE Routes
    Route::get('/course/content/qse/{courseContent}/edit', 'CourseContent\QSE\QSEController@edit')->name('qse-edit');
    Route::get('/course/content/qse/{courseContent}/show', 'CourseContent\QSE\QSEController@show')->name('qse-show');
    Route::put('/course/content/qse/{courseContent}/update', 'CourseContent\QSE\QSEController@update')->name('qse-update');
    Route::post('/course/content/qse/question', 'CourseContent\QSE\QSEQuestionController@store')->name('qse-question-store');
    Route::post('/course/content/qse/submit-answer', 'CourseContent\QSE\QSEQuestionController@submitAnswer')->name('qse-submit-answer');
    Route::post('/course/content/qse/take-quiz', 'CourseContent\QSE\QSEQuestionController@takeQuiz')->name('qse-take-quiz');
    Route::post('/course/content/qse/submit-quiz', 'CourseContent\QSE\QSEQuestionController@submitQuiz')->name('qse-submit-quiz');
    Route::post('/course/content/qse/question/{question}/retire', 'CourseContent\QSE\QSEQuestionController@retireOrActivate')->name('qse-question-retire-or-activate');
    Route::put('/course/content/qse/question/{question}', 'CourseContent\QSE\QSEQuestionController@update')->name('qse-question-update');
    Route::post('/course/content/qse/question/{question}/display-order', 'CourseContent\QSE\QSEQuestionController@updateDisplayOrder')->name('qse-question-update-display-order');
    Route::post('/course/content/qse/question/{question}/duplicate', 'CourseContent\QSE\QSEQuestionController@duplicate')->name('qse-question-duplicate');
    Route::delete('/course/content/qse/question/{question}', 'CourseContent\QSE\QSEQuestionController@destroy')->name('qse-question-delete');
    Route::get('/course/content/qse/question/answers', 'CourseContent\QSE\QSEQuestionAnswerController@index')->name('qse-question-answers');
    Route::delete('/course/content/qse/question/answer/{questionAnswer}', 'CourseContent\QSE\QSEQuestionAnswerController@destroy')->name('qse-question-answer-delete');
    Route::get('/course/content/qse/{event_user_qse}/results-report', 'CourseContent\QSE\QSEController@resultsReport')->name('qse-results-report');
    Route::get('/course/content/qse/{qse}/report', 'CourseContent\QSE\QSEController@report')->name('qse-report');

    Route::get('/course/content/{type}/{id}/edit', 'CourseContent\CourseFilesController@edit');

//    Route::get('/courses/content/{course}/edit', 'CourseContent\CourseContentController@show' );


    /**
     * Course Options
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:course-options'], function () {

        Route::get('/courses/courseOptions/index/{course}', 'Course\CourseOptionController@index')->name('all_course_options');
        Route::resource('CourseOptions', 'Course\CourseOptionsController'); //course_options table (note plural)

        Route::resource('CourseOption', 'Course\CourseOptionController'); //this is used for store and destroy to course_option table

    });

    /**
     * Course Fees
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage_course_fees'], function () {

        //Payment Report
        Route::get('site/authNetTransactionsReport', 'Payments\TransactionsController@authNetTransactionsReport')->name('authNetTransactionsReport');
        Route::get('authNetTransactionsReport.data', 'Payments\TransactionsController@authNetTransactionsData')->name('authNetTransactionsReport.data');

        //Course Fee Types
        Route::resource('courseFeeTypes', 'Course\CourseFeeTypesController');
        Route::post('updateCourseFeeTypeActivation', 'Course\CourseFeeTypesController@updateActivation')->name('updateCourseFeeTypeActivation');

       //Course Coupons
        Route::resource('courseCoupons', 'Course\CourseCouponsController');
        //this additional route needed to pass course_id to dataTable class (https://yajrabox.com/docs/laravel-datatables/master/buttons-with)
        Route::get('datatable/{id}', function(CourseCouponsDataTable $dataTable, $course_id){
            return $dataTable->with('course_id', $course_id)
                ->render('path.to.view');
        });
        //this route is needed to pass course_id to the index function (cannot just use standard index in resource route)
        Route::get('courseCoupons/index/{course_id}', 'Course\CourseCouponsController@index')->name('course_coupons'); //to bring back all coupons for a specific course

        //Course Fees
        Route::resource('courseFees', 'Course\CourseFeesController');
        //this additional route needed to pass course_id to dataTable class (https://yajrabox.com/docs/laravel-datatables/master/buttons-with)
        Route::get('datatable/{id}', function(CourseFeesDataTable $dataTable, $course_id){
            return $dataTable->with('course_id', $course_id)
                ->render('path.to.view');
        });
        //this route is needed to pass course_id to the index function (cannot just use standard index in resource route)
        Route::get('courseFees/index/{course_id}', 'Course\CourseFeesController@index')->name('course_fees'); //to bring back all coupons for a specific course
        Route::post('updateCourseFeeActivation', 'Course\CourseFeesController@updateActivation')->name('updateCourseFeeActivation');

    });

    /**
     * Course Categories
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:course_categories'], function () {
        //Course Category Groups
        Route::get('/courses/courseCategory/index/{course}', 'Course\CourseCategoryGroupsController@index')->name('all_course_groups'); //to bring back all groups/categories
        Route::get('/courses/courseCategoryGroups/create/{course}', 'Course\CourseCategoryGroupsController@create')->name('create_course_category_group'); //create a course category group
        Route::post('/courses/courseCategoryGroups', 'Course\CourseCategoryGroupsController@store');
        Route::get('/courses/courseCategoryGroups/edit/{courseCategoryGroup}/{course}', 'Course\CourseCategoryGroupsController@edit')->name('edit_course_category_group'); //edit a course category group
        Route::patch('/courses/courseCategoryGroups/update/{courseCategoryGroup}', 'Course\CourseCategoryGroupsController@update')->name('update_course_category_group');
        //mitcks 4/10/18 just learned how to add a resource route below for CRUD may be able to go back and cleanup other items to use this
        //documentation: https://laravel.com/docs/5.6/controllers
        Route::resource('courseCategoryGroups', 'Course\CourseCategoryGroupsController'); //delete

        //Course Categories
        Route::get('/courses/courseCategory/create/{group}/{course}', 'Course\CourseCategoriesController@create')->name('create_course_category'); //create a course category
        Route::patch('/courses/courseCategory/store/{group}', 'Course\CourseCategoriesController@storeCategories')->name('store_course_categories');
        Route::get('/courses/courseCategory/edit/{courseCategories}/{course}', 'Course\CourseCategoriesController@edit')->name('edit_course_category'); //edit a course category
        Route::patch('/courses/courseCategory/update/{courseCategories}', 'Course\CourseCategoriesController@update')->name('update_course_category');


        Route::patch('/courses/courseCategory/storeCheckbox/{courseID}/{categoryID}', 'Course\CourseCategoryController@store')->name('store_course_category');
        Route::resource('CourseCategory', 'Course\CourseCategoryController');

        Route::resource('CourseCategories', 'Course\CourseCategoriesController');

    });


    /**
     * Building Management
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-buildings'], function () {
        //For the Views
        Route::get('/buildings/all', 'Building\BuildingController@all')->name('all_buildings'); //list all buildings
        Route::get('/buildings/active', 'Building\BuildingController@active')->name('active_buildings'); //list all active buildings
        Route::get('/buildings/retired', 'Building\BuildingController@retired')->name('retired_buildings'); //list all retired buildings

        Route::get('/buildings/show/{building}', 'Building\BuildingController@show'); //show a building
        Route::get('/buildings/retire/{building}', 'Building\BuildingController@retire'); //retire a building
        Route::get('/buildings/activate/{building}', 'Building\BuildingController@activate'); //retire a building
        Route::get('/buildings/create', 'Building\BuildingController@create')->name('create_building');
        Route::post('/buildings', 'Building\BuildingController@store');
        Route::get('/buildings/edit/{building}', 'Building\BuildingController@edit')->name('edit_building'); //edit a course
        Route::patch('/buildings/update/{building}', 'Building\BuildingController@update')->name('update_building');

        //For the DataTables
        Route::get('buildingtables', 'Building\BuildingTableController@getIndex')->name('buildingtables');
        Route::get('buildingsall.data', 'Building\BuildingTableController@anyData')->name('buildings.all');
        Route::get('buildingsactive.data', 'Building\BuildingTableController@active')->name('buildings.active'); //list all active buildings
        Route::get('buildingsretired.data', 'Building\BuildingTableController@retired')->name('buildings.retired'); //list all retired buildings

        //testing/debugging
        Route::get('buildinglocations.data/{id}', 'Building\BuildingTableController@buildingLocations')->name('buildings.locations');
        Route::get('/buildings/test/{id}', 'Building\BuildingTableController@test')->name('buildings.test');


    });
    /**
     * Location Management
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-locations'], function () {
        //For the Views
        Route::get('/locations/all', 'Location\LocationController@all')->name('all_locations'); //list all locations
        Route::get('/locations/active', 'Location\LocationController@active')->name('active_locations'); //list active locations
        Route::get('/locations/retired', 'Location\LocationController@retired')->name('retired_locations'); //list active locations
        Route::get('/locations/activate/{location}', 'Location\LocationController@activateLocation')->name('activate_location'); //edit a location
        Route::get('/locations/retire/{location}', 'Location\LocationController@retireLocation')->name('retired_location'); //edit a
        Route::get('/locations/schedulers/{location}', 'Location\LocationController@schedulers')->name('location_schedulers'); //edit a location
        Route::get('/locations/removescheduler/{location}/{user}', 'Location\LocationController@removeScheduler')->name('remove_scheduler'); //edit a
        Route::get('/locations/removeallscheduler/{user}', 'Location\LocationController@removeAllScheduler')->name('remove_all_scheduler'); //edit a
        Route::get('/locations/addscheduler/{location}/{user}', 'Location\LocationController@addScheduler')->name('add_scheduler'); //edit a
        Route::get('/locations/viewscheduler/{user}', 'Location\LocationController@viewScheduler')->name('view_scheduler'); //edit a

        Route::get('/locations/edit/{location}', 'Location\LocationController@edit')->name('edit_location'); //edit a location
        Route::patch('/locations/update/{location}', 'Location\LocationController@update')->name('update_location');
        Route::get('/locations/create', 'Location\LocationController@create')->name('create_location');
        Route::post('/locations', 'Location\LocationController@store');

        //For the DataTables
        Route::get('locationsall.data', 'Location\LocationTableController@all')->name('locations.all');
        Route::get('locationsactive.data', 'Location\LocationTableController@active')->name('locations.active');
        Route::get('locationsretired.data', 'Location\LocationTableController@retired')->name('locations.retired');
        Route::get('availableschedulers.data/{location}', 'Location\LocationTableController@availableSchedulers')->name('locations.availableSchedulers');

        //for debugging
        Route::get('locationsschedulers.data/{id}', 'Location\LocationTableController@schedulers')->name('locations.schedulers');
        Route::get('viewscheduler.data/{user}', 'Location\LocationTableController@viewScheduler')->name('view.scheduler'); //edit a
        Route::get('test.data', 'Location\LocationTableController@test')->name('test.scheduler'); //edit a
        Route::get('locationbuilding.data/{id}', 'Location\LocationTableController@building')->name('location.buildings.'); //list all retired buildings

        Route::resource('locationSchedulers', 'Location\LocationSchedulersController');

    });

    /**
     * Resource Management
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:manage-resources'], function () {
        //Manage Resource Views
        Route::get('/resources/all', 'Resource\ResourceController@all')->name('all_resources'); //list all resources
        Route::get('/resources/active', 'Resource\ResourceController@active')->name('active_resources');
        Route::get('/resources/deactivated', 'Resource\ResourceController@deactivated')->name('deactivated_resources');

        //Manage Resource CRUD
        Route::resource('resources', 'Resource\ResourceController', ['names' => [
            'create' => 'resource_create',
            'destroy' => 'resource_destroy',
        ]]);

        //Duplicate Resource (to create new similar resource)
        Route::get('/resources/duplicate/{resource}', 'Resource\ResourceController@duplicate')->name('duplicate_resource');

        //confirmation of delete, later when these are attached to events you will only be able to delete if there are no events
        Route::get('/resources/deleteconfirm/{resource}', 'Resource\ResourceController@deleteconfirm')->name('deleteconfirm');
        //I should NOT have had to make this delete route, but resources.destroy would not work?
        Route::delete('delete/{id}', array('uses' => 'Resource\ResourceController@destroy', 'as' => 'resource.delete'));

        //Subcategory select list, results are filtered based on category
        Route::get('resourcesubcategory/get/{id}', 'Resource\ResourceController@getSubcategories');

        //Manage Resource Activate/Deactivate
        Route::get('/resources/deactivate/{resource}', 'Resource\ResourceController@deactivate')->name('deactivate');
        Route::get('/resources/activate/{resource}', 'Resource\ResourceController@activate')->name('activate');

        //Manage Resource DataTables
        Route::get('resourcetables', 'Resource\ResourceTableController@getIndex')->name('resourcetables');
        Route::get('resourcesall.data', 'Resource\ResourceTableController@allData')->name('resourcetables.all');
        Route::get('resourcesactive.data', 'Resource\ResourceTableController@activeData')->name('resourcetables.active');
        Route::get('resourcesinactive.data', 'Resource\ResourceTableController@inactiveData')->name('resourcetables.inactive');

        //Resouce Category
        Route::get('/resources/resourceCategory/index', 'Resource\ResourceCategoryController@index')->name('resource_category_index');
        Route::resource('ResourceCategory', 'Resource\ResourceCategoryController', ['names' => [
            'create' => 'resource_category_create',
        ]]);

        //had to add these for edit resource category, the resource controller above would not work correctly
        Route::get('/resources/resourceCategory/edit/{resourceCategory}', 'Resource\ResourceCategoryController@edit')->name('edit_resource_category'); //edit a resource category
        Route::patch('/resources/resourceCategory/update/{resourceCategory}', 'Resource\ResourceCategoryController@update')->name('update_resource_category');

        //Resource Subcategory
        Route::resource('ResourceSubCategory', 'Resource\ResourceSubCategoryController');
        Route::get('/resources/resourceSubCategory/create/{categoryId}', 'Resource\ResourceSubCategoryController@create')->name('create_resource_subcategory');
        Route::patch('/resources/resourceSubCategory/store/{categoryId}', 'Resource\ResourceSubCategoryController@store')->name('store_resource_subcategory');
        Route::get('/resources/resourceSubCategory/edit/{resourceSubCategory}', 'Resource\ResourceSubCategoryController@edit')->name('edit_resource_subcategory');
        Route::patch('/resources/resourceSubCategory/update/{resourceSubCategory}', 'Resource\ResourceSubCategoryController@update')->name('update_resource_subcategory');


    });

    /**
     * Scheduling
     */
    Route::group(['middleware' => ['access.routeNeedsPermission:scheduling']], function () {
//        Route::resource('courseInstance', 'CourseInstance\CourseInstanceController');

//        Route::get('courseInstance/edit/{id}', 'CourseInstance\CourseInstanceController@edit');
//        Route::get('courseInstance/clone/{id}/{date}', 'CourseInstance\CourseInstanceController@clone');
//        Route::get('courseInstance/fromTemplate/{id}', 'CourseInstance\CourseInstanceController@fromTemplate');
//        Route::get('courseInstance/fromRequest/{id}', 'CourseInstance\CourseInstanceController@fromRequest');

        //  schedule request for scheduler
        Route::get('scheduleRequest/pending', 'CourseInstance\ScheduleRequestController@pending')->name('pending_requests');
        Route::get('scheduleRequest/approved', 'CourseInstance\ScheduleRequestController@approved');
        Route::get('scheduleRequest/denied', 'CourseInstance\ScheduleRequestController@denied');
        Route::get('scheduleRequest/all', 'CourseInstance\ScheduleRequestController@all');
        Route::post('scheduleRequest/deny', 'CourseInstance\ScheduleRequestController@deny'); //deny request
        Route::get('scheduleRequest/comment/delete/{id}', 'CourseInstance\ScheduleCommentController@delete'); //remove request comment
        Route::get('requests.data', 'CourseInstance\ScheduleRequestController@requestsTableData'); // data

        //these routes duplicated here because needed by both requester and scheduler
//        Route::get('/courseInstance/getEventsAndResourcesByDate/{selectedDate}', 'CourseInstance\CourseInstanceController@getEventsAndResourcesByDate');
        Route::get('scheduleRequest/request/{id}', 'CourseInstance\ScheduleRequestController@show'); //request modal
        Route::get('scheduleRequest/denyModal/{id}', 'CourseInstance\ScheduleRequestController@denyModal'); //deny modal

        Route::get('event/delete/{id}/{all?}', 'CourseInstance\EventController@delete'); //soft delete event

    });

    //mitcks 2019-12-11 I removed the permissions from this route because there are event level permissions not handled here
//    Route::group(['middleware' => ['access.routeNeedsPermission:schedule-request;scheduling;course-add-event-comment']], function() {
    Route::post('/schedule/comment/add', 'CourseInstance\ScheduleCommentController@store'); //add request comment
//    });

    /**
     * Scheduling **New Section Added by Kim to Cleanup Matt's Code **
     */
    Route::group(['middleware' => ['access.routeNeedsPermission:scheduling']], function () {
        // TODO: MITCKS 7/1/19 Not using a resource controller here for create because it will get confused with Matt's code above
        //  but when I finish cleanup I could fix this
        Route::get('courseInstance/main/dateTest', 'CourseInstance\CourseInstanceController@dateTest')->name('date_test');


        Route::post('courseInstance/main/store', 'CourseInstance\CourseInstanceController@store');
        Route::post('courseInstance/main/update/{event}', 'CourseInstance\EventController@update');

        Route::get('courseInstance/main/create', 'CourseInstance\CourseInstanceController@create')->name('create_course_instance');
        Route::get('courseInstance/main/anotherEvent/{event}', 'CourseInstance\CourseInstanceController@anotherEvent')->name('add_event_to_course_instance');
        Route::get('courseInstance/main/duplicateEvent/{event}', 'CourseInstance\CourseInstanceController@duplicateEvent')->name('duplicate_event');
        Route::get('courseInstance/main/fromRequest/{request}', 'CourseInstance\CourseInstanceController@fromRequest')->name('add_event_from request');
        Route::get('courseInstance/main/editEvent/{event}', 'CourseInstance\CourseInstanceController@editEvent')->name('edit_event');

        //Called on template change to compare values
        Route::get('courseInstance/main/templateCompare/{template}/{event}', 'CourseInstance\CourseInstanceController@templateCompare')->name('template_compare');
        //apply the selected template values
        Route::post('courseInstance/main/templateApply', 'CourseInstance\CourseInstanceController@templateApply');

        //page to list all events for a course instance and display conflict flag
        Route::get('courseInstance/main/confirmation/{courseInstance}', 'CourseInstance\CourseInstanceController@displayRecurrenceConfirmation')->name('recurrence_confirmation');
        Route::get('courseInstanceEventsTable.data', 'CourseInstance\CourseInstanceController@courseInstanceEventsTableData')->name('courseInstance.events.table'); // courseInstance events datatable

        //mitcks 2021-1-20 these are duplicates of other routes above with less restrictive permissions, commenting out as these were causing schedule request to not display resources in grid.  Can be removed after testing.
        //gets default values JSON on course selection
        //Route::get('findDefaultValuesWithCourseID/{id}/{locationCheck}', 'CourseInstance\CourseInstanceController@findDefaultValuesWithCourseID');
        //gets default values JSON on template selection
        //Route::get('findDefaultTemplateValues/{templateId}/{scheduleRequestId?}/{date?}', 'CourseInstance\CourseInstanceController@findDefaultTemplateValues');

        //display deleted events
        Route::get('courseInstance/events/deleted', 'CourseInstance\EventController@deletedEvents')->name('deleted_events');
        Route::get('deletedEventsTable.data', 'CourseInstance\EventController@deletedEventsTableData')->name('deleted.events.table'); // deleted events datatable
        //restore deleted event
        Route::get('event/restore/{id}', 'CourseInstance\EventController@restore');

    });


    /**
     * My Schedule Request
     */
    Route::group(['middleware' => ['access.routeNeedsPermission:schedule-request;scheduling;course-schedule-request']], function () {
        Route::resource('scheduleRequest', 'CourseInstance\ScheduleRequestController');

        //these routes duplicated here because needed by both requester and scheduler
//        Route::get('/courseInstance/getEventsAndResourcesByDate/{selectedDate}', 'CourseInstance\CourseInstanceController@getEventsAndResourcesByDate');
        Route::get('myScheduleRequest/request/{id}', 'CourseInstance\ScheduleRequestController@show'); //request modal
        // Route::post('schedule/comment/add', 'CourseInstance\ScheduleCommentController@store'); //add request comment

        // my schedule requests
        Route::get('myScheduleRequest/pending', 'CourseInstance\ScheduleRequestController@userPending')->name('my_pending_requests');
        Route::get('myScheduleRequest/approved', 'CourseInstance\ScheduleRequestController@userApproved');
        Route::get('myScheduleRequest/denied', 'CourseInstance\ScheduleRequestController@userDenied');
        Route::get('myScheduleRequest/all', 'CourseInstance\ScheduleRequestController@userAll');
        Route::get('myScheduleRequest/delete/{request}', 'CourseInstance\ScheduleRequestController@delete'); //deny request

        Route::get('userRequests.data', 'CourseInstance\ScheduleRequestController@userRequestsTableData'); // data

        //mitcks 2020-04-21 Note the difference between duplicateEvent vs anotherEvent is as follows:
        // duplicateEvent is making a new schedule request with a NEW group_request_id based on the scheduleRequestId in parameter
        // anotherEvent is making a new schedule request with THE SAME group_request_id as the scheduleRequestId in parameter
        // this second option related to grouping events was commented out in the interface for phase one, and it also looks like some code
        // for it inScheduleRequestController@anotherEvent may have been deleted, so if this comes back for phase two this will need
        // to be fixed.  It is confusing because the two routes below both go to the anotherEvent function.
        Route::get('scheduleRequest/anotherEvent/{scheduleRequestId}', 'CourseInstance\ScheduleRequestController@anotherEvent');
        Route::get('scheduleRequest/duplicateEvent/{scheduleRequestId}', 'CourseInstance\ScheduleRequestController@anotherEvent');
        Route::get('schedulerequesttoday.data', 'CourseInstance\ScheduleRequestController@usersRequestsTodayTableData');

    });

    /**
     * Template Management
     */
    Route::group(['middleware' => ['access.routeNeedsPermission:manage-templates']], function () {
        Route::resource('courseInstance/template', 'CourseInstance\TemplateController');
        Route::get('courseInstance/template/create/{event}', 'CourseInstance\TemplateController@create')->name("create_course_template");
        Route::get('courseInstance/template/create-from-scratch/{course}', 'CourseInstance\TemplateController@createFromScratch')->name("create_template_from_scratch");
        Route::get('courseInstance/template/edit/{template}', 'CourseInstance\TemplateController@edit')->name("edit_course_template");;
        Route::get('courseInstance/template/duplicateTemplate/{template}', 'CourseInstance\TemplateController@duplicateTemplate');
        Route::get('courseInstance/template/exportTemplate/{template}', 'CourseInstance\TemplateController@exportTemplate');

        Route::get('courseInstance/template/delete/{id}', 'CourseInstance\TemplateController@destroy');
        Route::get('template.nameIsTaken', 'CourseInstance\TemplateController@nameIsTaken');

        Route::get('/courses/courseTemplate/index/{course}', 'Course\CourseTemplateController@index')->name('all_course_templates');
        Route::get('eventResources.data/{event}', 'CourseInstance\EventResourceController@eventResourcesTableData')->name('event.resources');

        Route::get('courseInstance/templateResource/delete/{id}', 'CourseInstance\TemplateController@destroyTemplateResouce')->name('delete_course_template_resource');

        //gets resource type values give resource id selection for ajax function
        Route::get('findDefaultValuesWithResourceID/{id}', 'CourseInstance\TemplateController@findDefaultValuesWithResourceID');

    });


    /**
     * Event Users/Assign Roles
     */
    Route::group(['middleware' => ['access.routeNeedsPermission:event-add-people-to-events;add-people-to-events;course-add-people-to-events;
                                    event-mark-event-attendance;course-mark-event-attendance;site-mark-event-attendance']], function () {
        Route::get('event/users/{id}', 'CourseInstance\EventUsersController@users')->name('event.user.add');; // assign event roles to users page
        Route::get('event/users/history/{id}', 'CourseInstance\EventUsersController@historyModal'); // event_user_history modal
        Route::get('event/users/move/{id}/{tab?}', 'CourseInstance\EventUsersController@moveModal'); // move user to new event modal
        Route::post('event/user/move', 'CourseInstance\EventUsersController@move')->name('event.user.move'); // move the user to another event
        Route::post('event/users/add', 'CourseInstance\EventUsersController@store')->name('event.user.store'); // add user with event role
        Route::post('updateAttendance', 'CourseInstance\EventUsersController@updateAttendance')->name('updateAttendance'); // update attendance
        Route::get('event/users/approve/{id}/{pageFrom?}', 'CourseInstance\EventUsersController@approve')->name('event.user.approve'); // approve user's request
        Route::get('event/users/park/{id}/{pageFrom?}', 'CourseInstance\EventUsersController@park')->name('event.user.park'); // park user
        Route::get('event/users/delete/{id}/{tab?}', 'CourseInstance\EventUsersController@delete')->name('event.user.delete'); // delete user
        Route::get('mycourses/waitlist', 'CourseInstance\EventUsersController@myCoursesWaitlist')->name('mycourses.waitlist'); // waitlist page for my courses
        Route::get('eventusers.data', 'CourseInstance\EventUsersController@usersData')->name('eventusers.typeahead'); // typeahead
        Route::get('eventuserstable.data', 'CourseInstance\EventUsersController@eventUsersTableData')->name('event.users.table'); // eventusers datatable
        Route::get('eventwaitlisttable.data', 'CourseInstance\EventUsersController@eventWaitlistTableData')->name('event.waitlist.table'); // event waitlist datatable
        Route::get('usersmovetable.data', 'CourseInstance\EventUsersController@userMoveTableData')->name('users.move.table'); // move datatable
        Route::get('usershistorytable.data', 'CourseInstance\EventUsersController@userHistoryTableData')->name('users.history.table'); // history datatable
        Route::get('mycourseswaitlisttable.data', 'CourseInstance\EventUsersController@myCoursesWaitlistTableData')->name('mycourses.waitlist.table'); // event waitlist datatable
    });


    /**
     * Course Users/Assign Roles
     */
    Route::group(['middleware' => 'access.routeNeedsPermission:course-add-people-to-courses;add-people-to-courses;manage-roles'], function () {
        Route::get('/course/catalog/users/{course_id}/{role_id?}', 'Course\CourseUsersController@catalogUsers')->name('course_catalog_users');
        Route::post('/course/catalog/users/add', 'Course\CourseUsersController@storeCatalog')->name('course.catalog.user.store');
        Route::get('/course/catalog/users/delete/{id}', 'Course\CourseUsersController@delete')->name('course.catalog.user.delete');
        Route::get('courseusers.data', 'Course\CourseUsersController@usersData')->name('courseusers.typeahead'); // typeahead
        Route::get('courseuserstable.data', 'Course\CourseUsersController@courseUsersTableData')->name('courseusers.table'); // datatable

        Route::get('/course/users/{course_id}', 'Course\CourseUsersController@users')->name('course_users');
        Route::post('/course/users/add', 'Course\CourseUsersController@store')->name('course.user.store');
        Route::get('/course/users/delete/{id}', 'Course\CourseUsersController@delete')->name('course.user.delete');
    });


    Route::get('/mycourses', 'Course\CourseController@myCourses')->name('mycourses');
    Route::get('mycourses.data', 'Course\CoursesTableController@myCoursesData')->name('coursetables.mycourses');


}); /* This is the end of the Auth section that requires Authentication to the routes -jl 2019-05-17 13:55

/** 
 * All the routes below are publically accessible 
 * -jl 2019-05-17 14:01
 */


/**
 * Calendar - does not require login, publicly available
 */

Route::get('/calendar', 'Calendar\CalendarController@getCalendar')->name('default.calendar');
Route::get('calendar/day/{day?}/{location?}/{rooms?}', 'Calendar\CalendarController@getDay');
Route::get('calendar/week/{day?}/{location?}/{rooms?}', 'Calendar\CalendarController@getWeek');
Route::get('calendar/month/{day?}/{location?}/{rooms?}', 'Calendar\CalendarController@getMonth');
Route::get('calendar/agenda', 'Calendar\CalendarController@getAgenda');
Route::get('agenda.data', 'Calendar\CalendarController@agendaData');
Route::get('day.data', 'Calendar\CalendarController@dayData');
Route::get('calendar/agenda/locations/get/{id}', 'Calendar\CalendarController@getLocations');

// Welcome Board
Route::get('/welcome/{location?}', 'Calendar\CalendarController@getWelcomeBoard');
Route::get('/welcomeDark/{location?}', 'Calendar\CalendarController@getWelcomeBoard');
Route::get('/welcomeData/{location?}', 'Calendar\CalendarController@getWelcomeBoardData');


//This route needs to be publicly available so the event modal does not require login
Route::resource('event', 'CourseInstance\EventController');

//Event Dashboard
Route::get('/courseInstance/events/event-dashboard/{id}/{tab?}', 'CourseInstance\EventController@eventDashboard')->name('event_dashboard');
Route::get('/courseInstance/events/event-dashboard/{event}/qse/{qse}/chart-report', 'CourseContent\QSE\QSEController@chartReport')->name('qse-chart-report');

// Manage QSE Data Table
Route::get('eventQSETable.data', 'CourseInstance\EventQSEController@eventQSETableData')->name('event.qse.table');;
Route::get('event/qse/{course_content}/print-preview', 'CourseInstance\EventQSEController@eventQSEPrintPreview')->name('event.qse.print-preview');

// EventQSEUsers Data Table (for subtable in child row)
Route::get('eventUserQSETable.data', 'CourseInstance\EventUserQSEController@eventUserQSETableData')->name('event.user.qse.table');;


//Activate QSE
Route::post('updateQSEActivation', 'CourseInstance\EventQSEController@updateActivation')->name('updateQSEActivation'); // update attendance
//Add QSE to Event
Route::post('addQSEActivation', 'CourseInstance\EventQSEController@addToEvent')->name('addQSEActivation'); // update attendance


//This route lets people see information about a location (and its building)
Route::get('/locations/show/{location}', 'Location\LocationController@show'); //show a location

/**
 * Course Catalog - does not require login, publicly available
 */

Route::get('/courses/catalog', 'Course\CourseController@catalog')->name('catalog');
Route::get('coursecatalog.data', 'Course\CoursesTableController@catalogData')->name('coursetables.catalog');
Route::get('/courses/catalogShow/{course}', 'Course\CourseController@catalogShow');

Route::get('admin/access/role/create/1', 'Backend\Access\Role\RoleController@createSite')->name('role.create.site');
Route::get('admin/access/role/create/2', 'Backend\Access\Role\RoleController@createCourse')->name('role.create.course');
Route::get('admin/access/role/create/3', 'Backend\Access\Role\RoleController@createEvent')->name('role.create.event');

//For DataTables
Route::get('roleTable.data', 'Backend\Access\Role\RoleTableController@roleTableData');


/**
 * Site Emails
 */
Route::group(['middleware' => 'access.routeNeedsPermission:manage-sites;client-manage-site-options'], function () {
    // Handles Rendering Create Pages
    Route::get('admin/site/emails/create/1', 'Site\SiteEmailsController@createSite')->name('email.create.site');
    Route::get('admin/site/emails/create/2', 'Site\SiteEmailsController@createCourse')->name('email.create.course');
    Route::get('admin/site/emails/create/3', 'Site\SiteEmailsController@createEvent')->name('email.create.event');

    // Handles Rendering Edit Pages
    Route::get('admin/site/emails/{id}/edit/1', 'Site\SiteEmailsController@editSite')->name('email.edit.site');
    Route::get('admin/site/emails/{id}/edit/2', 'Site\SiteEmailsController@editCourse')->name('email.edit.course');
    Route::get('admin/site/emails/{id}/edit/3', 'Site\SiteEmailsController@editEvent')->name('email.edit.event');

    // Routes for action button
    Route::get('admin/site/emails/clone/{id}', 'Site\SiteEmailsController@clone');
    Route::get('admin/site/emails/send/{id}', 'Site\SiteEmailsController@sendManually');
    Route::post('admin/site/emails/sendNow', 'Site\SiteEmailsController@sendNow');
    Route::get('admin/site/emails/delete/{id}', 'Site\SiteEmailsController@destroy');
    Route::post('admin/site/emails/remove', 'Site\SiteEmailsController@remove');


    // For DataTables
    Route::get('emailTable.data', 'Site\SiteEmailsController@emailTableData');

    // Main Resource
    Route::resource('admin/site/emails', 'Site\SiteEmailsController');
});

/**
 * Course Emails
 */
Route::group(['middleware' => 'access.routeNeedsPermission:manage-courses;manage-course-emails;manage-event-emails;course-manage-course-emails'], function () {
    // Main Resource
    Route::resource('/courses/courseInstanceEmails', 'Course\CourseEmailsController');
    Route::get('courses/courseInstanceEmails/{id}', 'Course\CourseEmailsController@show');

    // For DataTables
    Route::get('courseEmailTable.data', 'Course\CourseEmailsController@emailTableData');

    // Routes for action button
    Route::get('/courses/courseInstanceEmails/clone/{id}', 'Course\CourseEmailsController@clone');
    Route::get('/courses/courseInstanceEmails/manual-send/{id}', 'Course\CourseEmailsController@sendManually');
    Route::post('/courses/course/emails/sendNow', 'Course\CourseEmailsController@sendNow');
    Route::get('/courses/courseInstanceEmails/delete/{id}', 'Course\CourseEmailsController@destroy');
    Route::post('/courses/courseInstanceEmails/remove', 'Course\CourseEmailsController@remove');

    // Handles Rendering Create Pages
    Route::get('courses/courseInstanceEmails/create/2', 'Course\CourseEmailsController@createCourse')->name('course.create.course');
    Route::get('courses/courseInstanceEmails/create/3', 'Course\CourseEmailsController@createEvent')->name('course.create.event');

    // Handles Rendering Edit Pages
    Route::get('courses/courseInstanceEmails/{id}/edit/2', 'Course\CourseEmailsController@editCourse')->name('course.edit.course');
    Route::get('courses/courseInstanceEmails/{id}/edit/3', 'Course\CourseEmailsController@editEvent')->name('course.edit.event');
});

// Gets Events For the Given day
Route::get('/courseInstance/getEventsAndForDate/{date}/{templateApply?}/{IMR?}/{eventId?}/{templateId?}', 'CourseInstance\CourseInstanceController@getEventsForDate');


// Create Schedule Request
Route::group(['middleware' => ['access.routeNeedsPermission:schedule-request;scheduling;course-schedule-request']], function () {

    //gets default values JSON on course selection
    Route::get('findDefaultValuesWithCourseID/{id}/{locationCheck}', 'CourseInstance\CourseInstanceController@findDefaultValuesWithCourseID');
    //gets default values JSON on template selection
    Route::get('findDefaultTemplateValues/{templateId}/{scheduleRequestId?}/{date?}', 'CourseInstance\CourseInstanceController@findDefaultTemplateValues');

    // Part of Templates    
    // Type = 2
    Route::get('/courseInstance/getResourceByCategoryId/{resource_id}/{resource_category_id}/{location_id}', 'CourseInstance\CourseInstanceController@getResourceByCategoryId');

    // Type = 3
    Route::get('/courseInstance/getResourceBySubCategoryId/{resource_id}/{resource_sub_category_id}/{location_id}', 'CourseInstance\CourseInstanceController@getResourceBySubCategoryId');

});


/**
 * Event Email Controller Routes
 * TC - I know I'm breaking my own rule for using resources routes instead of specificly name routes, but resources breaks the backend due to missing {event_id}
 */
Route::group(['middleware' => ['access.routeNeedsPermission:manage-event-emails;course-manage-event-emails;event-manage-event-emails']], function () {
    // Index
    Route::get('courseInstance/events/event-dashboard/{event_id}/emails/index', 'CourseInstance\EventEmailsController@index')->name('event.emails.index');

    // Create
    Route::get('courseInstance/events/event-dashboard/{event_id}/email/create', 'CourseInstance\EventEmailsController@create')->name('event.email.create');

    // Store
    Route::post('courseInstance/events/event-dashboard/{event_id}/emails', 'CourseInstance\EventEmailsController@store');

    // Edit
    Route::get('courseInstance/events/event-dashboard/{event_id}/email/edit/{id}', 'CourseInstance\EventEmailsController@edit')->name('event.email.edit');

    // Update
    Route::patch('courseInstance/events/event-dashboard/{event_id}/emails', 'CourseInstance\EventEmailsController@update');

    // Delete
    Route::get('courseInstance/events/event-dashboard/{event_id}/email/delete/{id}', 'CourseInstance\EventEmailsController@destroy');

    // Clone
    Route::get('courseInstance/events/event-dashboard/{event_id}/email/clone/{id}', 'CourseInstance\EventEmailsController@clone');

    //Show a single Event Email
    Route::get('eventEmail/show/{id}', 'CourseInstance\EventEmailsController@showSentEmail');

    // Data Tables
    Route::get('eventEmailTable.data', 'CourseInstance\EventEmailsController@emailTableData')->name('event.emails.table');;
    Route::get('eventSentEmailTable.data', 'CourseInstance\EventEmailsController@sentEmailTableData')->name('event.sent.emails.table');;

});


/**
 * Event Request
 */
// Enroll in Event
Route::group(['middleware' => 'auth'], function () {
    Route::resource('enroll', 'CourseInstance\EventRequestController');

    //request enrollment form
    Route::get('courseInstance/events/request/{course_id}/{event_id?}', 'CourseInstance\EventRequestController@requestEnrollment')->name('enrollRequest');

    //self park
    Route::get('course/selfpark/{id}', 'CourseInstance\EventUsersController@selfpark')->name('course.self.park'); // park user

    //store enrollment request
    Route::post('event/user/request', 'CourseInstance\EventRequestController@store')->name('event.user.request');

    //Fees - check if selected role is a learner role
    Route::post('isLearnerRole', 'CourseInstance\EventRequestController@isLearnerRole')->name('isLearnerRole');
    //lookup fee amount based on type selection
    Route::post('lookupFee', 'CourseInstance\EventRequestController@lookupFee')->name('lookupFee');
    //verify coupon code
    Route::post('checkCoupon', 'CourseInstance\EventRequestController@checkCoupon')->name('checkCoupon');
    //payment form to send data to processing company
    Route::get('payment/{event_user_payment_id}', 'CourseInstance\EventRequestController@requestPayment')->name('paymentRequest');
    //get authorize.net token
    Route::post('getAuthNetToken', 'CourseInstance\EventRequestController@getAuthNetToken')->name('getAuthNetToken');
    //return here upon payment completion
    Route::get('paymentComplete/{event_user_payment_id}', 'CourseInstance\EventRequestController@completePayment')->name('paymentComplete');
    //allow user to remove pending payment request from enrollment requests in dashboard
    Route::get('deletePaymentRequest/{id}', 'CourseInstance\EventUsersController@deletePendingPayment')->name('deletePaymentRequest'); // delete pending payment request
    //receipt
    Route::get('paymentReceipt/{event_user_payment_id}', 'CourseInstance\EventRequestController@paymentReceipt')->name('paymentReceipt');

});

// Pending Request and Actions
// Route::group(['middleware' => ['access.routeNeedsPermission:add-people-to-events']], function() {
Route::group(['middleware' => 'auth'], function () {
    Route::get('pending', 'CourseInstance\EventRequestController@pending')->name('pending');
    Route::get('requestPending.data', 'CourseInstance\EventRequestController@requestTableData');
    Route::get('pending/add/{id}', 'CourseInstance\EventRequestController@add');
    Route::get('pending/waitlist/{id}', 'CourseInstance\EventRequestController@waitlist');
    Route::get('pending/deny/{id}', 'CourseInstance\EventRequestController@deny');
});

/**
 * Site Reporting
 */
Route::group(['middleware' => ['access.routeNeedsPermission:site-report-creation']], function () {

    Route::get('reports-event-activities', 'Site\SiteReportsController@eventActivity')->name('reports-event-activities');
    Route::get('reports-event-rosters', 'Site\SiteReportsController@eventRoster')->name('reports-event-rosters');
    Route::get('reports/search/{type}/{query}', 'Site\SiteReportsController@search')->name('reports-search');
    Route::get('reports/courses/{building}/{location?}', 'Site\SiteReportsController@getCourses')->name('reports-courses');

});

//Authorize.NET Webhooks Route (this needs to be public)
Route::post('authnet-webhook-listener', 'Payments\AuthnetWebhookController@webhookListener');
//Route::get('testReturn', 'CourseInstance\EventRequestController@testReturn')->name('testReturn');

Route::get('webhookCompletionCheck', 'Payments\AuthnetWebhookController@webhookCompletionCheck');

Route::get('/logs', 'LogsController@show')->name('logs');
Route::get('/chartjs', function (){
  return view('chartjs');
});

