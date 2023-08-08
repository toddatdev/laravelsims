<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');
Route::get('macros', 'FrontendController@macros')->name('macros');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */


Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::get('myclassestable.data', 'DashboardController@myClassesTableData');
        
        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        Route::get('account/permissions', 'AccountPermissionsController@index')->name('account_permissions');
        Route::get('accountpermissions.data', 'AccountPermissionsController@accountPermissionsTableData');
        
        Route::get('question/answer/{id}', 'AccountController@getChildAnswers')->name('getChildAnswers');

        Route::patch('user/update/answers', 'AccountController@updateAnswerOfUser')->name('profile.update.answers');
        
        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');

        /*
         * Profile Questions Specific
         */
        //Route::patch('userprofile/update', 'UpdateProfileController@update')->name('userprofile.update');
    });
});
