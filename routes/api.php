<?php
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Access;
use App\Study;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Study as StudyResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\StudyCollection;
use App\Http\Resources\AccessCollection;
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



Route::post('login', 'PassportController@login');
 Route::post('register', 'PassportController@register');

// study
Route::middleware('auth:api')->group(function () {
    Route::get('/studies', 'StudyController@index');
    Route::get('/studies/{id}', 'StudyController@edit');
    Route::post('/studies', 'StudyController@update');
    Route::post('/createStudy', 'StudyController@create');

    Route::get('foo', function(){

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
    Route::get('/questionstreams/{id}', 'FormController@show');


    Route::post('/user', function (Request $request) {

return  $request;
       // return User::first()->access;

       // return UserResource::collection(User::all());


        //return response()->json($study);


    });


});


Route::middleware('auth:api')->group(function () {

    Route::post('/invite', 'AccessController@store');

});

Route::get('linkChecker/{id}', 'AccessController@linkChecker');
