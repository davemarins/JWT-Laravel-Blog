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

Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    // Route::post('refresh', 'AuthController@refresh');
    // Route::post('me', 'AuthController@me');
    // Route::post('signup', 'AuthController@signup');
    Route::post('sendpasswordresetlink', 'ResetPasswordController@sendEmail');
    Route::post('resetpassword', 'ChangePasswordController@process');
    // subscribers APIs
    Route::post('subscribe', 'SubscribersController@subscribe');
    Route::get('subscribers', 'SubscribersController@subscribers');
    Route::post('unsubscribe', 'SubscribersController@unsubscribe');
    // newsletter APIs
    Route::get('getnewsletters', 'NewslettersController@newsletters');
    Route::get('getnewsletterdraft', 'NewslettersController@getdraft');
    Route::get('deletedraft', 'NewslettersController@deleteDraft');
    Route::post('savenewsletterdraft', 'NewslettersController@saveNewsletterDraft');
    Route::post('sendnewsletter', 'NewslettersController@sendNewsletter');
    // blog article APIs


});
