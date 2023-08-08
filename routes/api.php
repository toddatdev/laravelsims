<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// The was a Laravel default
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * API For Node.js Email Service
 * Uses AuthKey Middleware that requires each request to have a header with our PUSHER_APP_KEY
*/

// Get All The Event Emails
Route::get('/emails', 'Api\CourseInstance\EventEmailApiController@show');


// Get specific email
Route::get('/email/{id}', 'Api\CourseInstance\EventEmailApiController@email');


// Get Event Emails Ready to be sent via Mailgun
Route::get('/send/emails', 'Api\CourseInstance\EventEmailApiController@send');


// Patch set event email was sent to 1
Route::patch('/sent/email/{id}', 'Api\CourseInstance\EventEmailApiController@update');


// Post store the Sent Email
Route::post('/sent', 'Api\CourseInstance\EventEmailApiController@sent');


// Get all the Sent Emails
Route::get('/sent/all', 'Api\CourseInstance\EventEmailApiController@sentAll');