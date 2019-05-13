<?php
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Access;
use App\Study;
use App\Study_item;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Study as StudyResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\StudyCollection;
use App\Http\Resources\AccessCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cms_form_config;

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



Route::post('login', 'PassportController@login');
 Route::post('register', 'PassportController@register');

// study
Route::middleware('auth:api')->group(function () {
    Route::get('/studies', 'StudyController@index');
    Route::get('/studies/{id}', 'StudyController@edit');
    Route::post('/studies', 'StudyController@update');
    Route::post('/createStudy', 'StudyController@create');
    Route::post('/addStudyItem/{id}', 'StudyController@addStudyItem');
    Route::get('/getStudyItem/{id}', 'StudyController@studyItem');
    Route::post('/studyItemUpdate/{id}', 'StudyController@studyItemUpdate');

    Route::get('/studyItemListing/{id}', 'StudyController@studyItemListing');



    Route::get('/study_users/{id}/{studyitem}', 'StudyController@study_users');
    Route::post('/study_item_access', 'StudyController@study_item_access');
    Route::get('/study_users_form_populators/{id}', 'StudyController@study_users_form_populators');

    Route::get('study_users', function(){

        $user =  Auth::id();
        return $user;
    });
});
//  streams
Route::middleware('auth:api')->group(function () {
    Route::post('/createStream', 'StreamController@store');
    Route::post('/getStudyQuestions', 'StreamController@studyQuestions');
    Route::get('/questionstream/{question_uniqid?}', 'StreamController@show');
    Route::post('/saveEditStudyField', 'StreamController@update');

});
//forms
Route::middleware('auth:api')->group(function () {
    Route::get('/questionstreams', 'FormController@index');
    Route::get('/formStudyItemListings/{id}', 'StudyController@formStudyItemListings');
    Route::get('/formStudyItemListing/{id}', 'StudyController@formStudyItemListing');
    Route::get('/questionstreams/{id}', 'FormController@show');
    Route::post('/saveForLater/{id}', 'FormController@store');

    Route::get('/getFormValues/{id}', 'FormController@getFormValues');
    Route::post('/globalsiteconfig/', 'FormController@globalSiteConfig');

    Route::get('/getFormUser', 'UserController@getFormUser');



    Route::get('/getGlobalSiteConfig/{id?}', function ($id = null) {
        $Cms_form_config =  DB::table('cms_form_configs')->where([['study_id' , '=', $id]])->get();
      ;

       return $Cms_form_config;
    });


});


Route::middleware('auth:api')->group(function () {

    Route::post('/invite', 'AccessController@store');

});

Route::get('linkChecker/{id}', 'AccessController@linkChecker');
