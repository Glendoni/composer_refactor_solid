<?php

namespace App\Http\Controllers;
use App\Form;
use Illuminate\Support\Facades\Auth;
use App\Mail\Email_invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
//use App\Invite;
use App\Access;
use App\User;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkAccess =   Access::where([
        ['study_id', '=', $request->study_id],
        ['invitee_email', '=', $request->invitee_email]
    ])->get();


        $order = User::findOrFail(1);


$unique_id =  uniqid();

     if(!count($checkAccess )) {
        $stream = new Access;
        $stream->invitee_email = $request->invitee_email;
        $stream->study_id = $request->study_id;
        $stream->created_by =  Auth::id();
        $stream->email_confirmation_id =  $unique_id;
        $stream->active =  1;
        $stream->save();

         $unique_id  = '<a href="http://localhost:4200/invite/'.$unique_id.'"> Click to Join Study</a>';

         Mail::to($request->user())->send(new Email_invite($unique_id));
     }
     if( count($checkAccess ) ) return 'already exist';
        if( !count($checkAccess ) ) return 200;
//        $stream = new Access;
//        $stream->invitee_email = $request->invitee_email;
//        $stream->study_id = $request->study_id;
//        $stream->save();
//
//        return Study::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function linkChecker($id)
    {

return  DB::select('(
select a.email_confirmation_id, u.id, invitee_id,
       CASE
           WHEN u.id is null THEN text \'register\'
           ELSE text \'login\'
           END AS redirect
from accesses a
         left join users u
                   ON a.user_id = u.id

WHERE a.email_confirmation_id =\''.$id.'\'
)');










        return  DB::table('accesses')
            ->Join('users', 'accesses.user_id', '=', 'users.id')




        ->where('email_confirmation_id', $id)->get();




//        $study = Access::where('email_confirmation_id', $id)->select('invitee_id')->get();
//        if (count($study)) {
//            if (!$study[0]['invitee_id']) {
//
//                return response()->json($study[0]['invitee_id']);
//            } else {
//
//
//            }
//        }
    }


}


