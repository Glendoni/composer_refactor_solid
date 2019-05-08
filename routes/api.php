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
    Route::get('/getFormUser', 'UserController@getFormUser');



    Route::post('/user', function (Request $request) {

       // $request = json_decode($request);
        //print_r($request);




        //unset($request['options']['name']);
//        print_r($request['options']);
//
//        exit();
        foreach ($request['options'] as $item) {
unset($item['name']);
            DB::table('study_item_accesses')->insert([$item
            ]);
        }
        DB::table('study_item_accesses')->where('value', '=', null)->delete();

       // return response()->json($request);
        exit;

     //$study = Access::find(1)->access;

       // return UserResource::collection(User::all());


        //return response()->json($study);

//            DB::table('accesses')
//            ->join('users', 'accesses.user_id', '=', 'users.id')
//            ->where('accesses.study_id', 10)
//            ->select('users.*')
//            ->get();


    });


});


Route::middleware('auth:api')->group(function () {

    Route::post('/invite', 'AccessController@store');

});

Route::get('linkChecker/{id}', 'AccessController@linkChecker');
