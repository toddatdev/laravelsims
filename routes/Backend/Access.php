<?php

/**
 * All route names are prefixed with 'admin.access'.
 */
Route::group([
    'prefix'     => 'access',
    'as'         => 'access.',
    'namespace'  => 'Access',
], function () {

    /*
     * User Management
     */
    Route::group([
        // 'middleware' => 'access.routeNeedsRole:1',
        // I changed the above to the below to allow the manage-users (3) and manage-roles (6) to access. -jl 2018-03-30 11:57 
        'middleware' => 'access.routeNeedsPermission:3;6',
    ], function () {
        Route::group(['namespace' => 'User', 'middleware' => 'access.routeNeedsPermission:3'], function () {
            /*
             * For DataTables
             */
            Route::post('user/get', 'UserTableController')->name('user.get');

            /*
             * User Status'
             */
            Route::get('user/deactivated', 'UserStatusController@getDeactivated')->name('user.deactivated');
            Route::get('user/deleted', 'UserStatusController@getDeleted')->name('user.deleted');

            /*
             * Encrypt Passwords of Imported Users
             */
            Route::get('user/encrypt', 'UserPasswordController@getEncryptPasswords')->name('user.encrypt-password');
            Route::patch('user/encrypt/post', 'UserPasswordController@updateEncryptPasswords')->name('user.encrypt-password.post');

            /*
             * User CRUD
             */
            Route::resource('user', 'UserController');


            /*
             * Specific User
             */
            Route::group(['prefix' => 'user/{user}'], function () {
                // Account
                Route::get('account/confirm/resend', 'UserConfirmationController@sendConfirmationEmail')->name('user.account.confirm.resend');

                // Status
                Route::get('mark/{status}', 'UserStatusController@mark')->name('user.mark')->where(['status' => '[0,1]']);

                // Password
                Route::get('password/change', 'UserPasswordController@edit')->name('user.change-password');
                Route::patch('password/change', 'UserPasswordController@update')->name('user.change-password.post');

                // Access
                Route::get('login-as', 'UserAccessController@loginAs')->name('user.login-as');

                // Session
                Route::get('clear-session', 'UserSessionController@clearSession')->name('user.clear-session');
            });

            Route::group(['prefix' => 'user/{user}'], function () {
                Route::get('questions', 'ProfileQuestionController@index')->name('user.question-answers');
                Route::patch('questions', 'ProfileQuestionController@store')->name('user.question-answers-save');
            });
            
                    
            /*
             * Deleted User
             */
            Route::group(['prefix' => 'user/{deletedUser}'], function () {
                Route::get('delete', 'UserStatusController@delete')->name('user.delete-permanently');
                Route::get('restore', 'UserStatusController@restore')->name('user.restore');
            });
        });

        /*
        * Role Management
        */
        Route::group(['namespace' => 'Role', 'middleware' => 'access.routeNeedsPermission:6'], function () {
            Route::resource('role', 'RoleController', ['except' => ['show', 'create']]);

        });
    });
});
