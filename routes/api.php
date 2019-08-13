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


 // Reports
    Route::middleware('auth:api')->group(function () {
    Route::get('/my_form_performace', 'ReportingController@index');
});



// study
Route::middleware('auth:api')->group(function () {
    Route::post('/studies', 'StudyController@update');

    Route::post('/createStudy', 'StudyController@create');

    Route::post('/addStudyItem/{id}', 'StudyController@addStudyItem');
    Route::post('/studyItemUpdate/{id}', 'StudyController@studyItemUpdate');
    Route::post('/study_item_access', 'StudyController@study_item_access');

    Route::get('/studies', 'StudyController@index');

    Route::get('/formGetStudies', 'StudyController@formGetStudies');
    Route::get('/getStudyItem/{id}', 'StudyController@studyItem');
    Route::get('/studies/{id}', 'StudyController@edit');
    Route::get('/studyItemListing/{id}', 'StudyController@studyItemListing');
    Route::get('/study_users/{id}/{studyitem}', 'StudyController@study_users');
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
    Route::post('/saveEditStudyField', 'StreamController@update');

    Route::get('/questionstream/{question_uniqid?}', 'StreamController@show');


});
//forms
Route::middleware('auth:api')->group(function () {
    Route::post('/saveForLater/{formid}', 'FormController@store');
    Route::post('/saveForm/{formid}/{study_id?}', 'FormController@saveForm');
    Route::post('/globalsiteconfig/', 'FormController@globalSiteConfig');

    Route::get('/questionstreams', 'FormController@index');
    Route::get('/formStudyItemListings/{id}', 'StudyController@formStudyItemListings');
    Route::get('/formStudyItemListing/{id}', 'StudyController@formStudyItemListing');
    Route::get('/questionstreams/{id}', 'FormController@show');
    Route::get('/getFormValues/{id}', 'FormController@getFormValues');
    Route::get('/getFormUser', 'UserController@getFormUser');
    Route::get('/getGlobalSiteConfig/{id?}', function ($id = null) {
        $Cms_form_config =  DB::table('cms_form_configs')->where([['study_id' , '=', $id]])->get();
       return $Cms_form_config;
    });

//    Route::post('/formsaver/{id}/{formId}', function (Request $request, $studyId = null, $formId) {
//
//       // $studyId = $formId;
//
//
//       //
//    });






});


Route::middleware('auth:api')->group(function () {
    Route::post('/invite', 'AccessController@store');
});

Route::get('linkChecker/{id}', 'AccessController@linkChecker');
