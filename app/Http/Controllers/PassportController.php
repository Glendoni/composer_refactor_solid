<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Access;
//use App\Account;
//use App\Company;
use Illuminate\Support\Facades\Auth;
//use App\Http\Resources\User as UserResource;
//use App\Http\Resources\UserCollection;
use Validator;

class PassportController extends Controller
{
    public $sucessStatus = 200;

    /*
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login() {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json($success, $this->sucessStatus);
        }
        else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /*
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()],401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $insertedId = $user->id;
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;


//      Access::where('invitee_email', $request->email)
//         ->update(['user_id', 3]);

        $flight = Access::where('invitee_email', $request->email)->first();

        $flight->user_id = $insertedId;

        $flight->save();

        //return response()->json(['success' => $success], $this->sucessStatus);
    }

    /*
     * details api
     *
     * @return \Illumiante\Http\Response
     */
    public function getDetails() {

        //return new UserCollection(User::paginate());
        // return new UserResource(User::find(1));
        $user = Auth::user();

        return     $user::find($user->id)->roles()->orderBy('description')->withCount('role')->get();
        // $user = Auth::user();
        return response()->json(['success' => $user], $this->sucessStatus);
    }

    public function getRoles($role) {

        $account = Company::find($role)->account()->where('RelatedAccountId', $role)->get();
        return response()->json($account);

        $user = Auth::user();
        //return     Company::find($role)->relatedAccount()->get();
        return     $user::find($user->id)->roles()->orderBy('description')->withCount('role')->where('roles.id', $role)->get();
    }

}
