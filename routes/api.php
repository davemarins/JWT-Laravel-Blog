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
    Route::get('subscriberstats', 'SubscribersController@stats1');
    Route::get('subscriberstatsgroupby', 'SubscribersController@stats2');
    // newsletter APIs
    Route::get('getnewsletters', 'NewslettersController@newsletters');
    Route::get('getnewsletterdraft', 'NewslettersController@getdraft');
    Route::get('deletedraft', 'NewslettersController@deleteDraft');
    Route::post('savenewsletterdraft', 'NewslettersController@saveNewsletterDraft');
    Route::post('sendnewsletter', 'NewslettersController@sendNewsletter');
    // blog article APIs
    Route::get('getarticledraft', 'BlogArticleController@getdraft');
    Route::post('savearticledraft', 'BlogArticleController@saveArticlesDraft');
    Route::get('deletearticledraft', 'BlogArticleController@deleteDraft');
    Route::get('getallarticles', 'BlogArticleController@articles');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show');
        Route::post('laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload');
        // list all lfm routes here...
    });

    /*
    Route::post('uploadimage', function() {
        $CKEditor = Input::get('CKEditor');
        $funcNum = Input::get('CKEditorFuncNum');
        $message = $url = '';
        if (Input::hasFile('upload')) {
            $file = Input::file('upload');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move(storage_path().'/images/', $filename);
                $url = public_path() .'/images/' . $filename;
            } else {
                $message = 'An error occured while uploading the file.';
            }
        } else {
            $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    });
    */

});
