<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Study;
use App\Study_item;
use App\User;
use App\Stream;
use App\Study_item_access;
use Illuminate\Support\Facades\DB;

class StudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $study = User::find(Auth::id())->access()
            ->where('accesses.active', true)
            ->where('accesses.user_id', Auth::id())
            ->get();

        return response()->json($study);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $stream = new Study;
        $stream->name = $request->name;
        $stream->save();

        return Study::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stream = new Study;
        $stream->name = $request->name;
        $stream->save();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $study = Study::find($id);
        return response()->json($study);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $study = Study::find($request->studyId);
        $study->name = $request->name;
        $study->description = $request->description;
        $study->invite_code = $request->invite_code;

        $study->save();

        return response()->json($study);


    }

    public function addStudyItem(Request $request, $id)
    {

        $study = new Study_item;
        $study->name = $request->name;
        $study->note = $request->note;
        $study->study_id = $request->study_id;
        $study->created_by = Auth::id();
        $study->save();


    }

    public function studyItemListing($id)
    {
        //return   DB::raw('* from study_item_accesses')

            return DB::table('study_items')
              // ->leftJoin('study_item_accesses', 'study_items.id', '=', 'study_item_accesses.study_items_id')

                   ->whereRaw('study_id ='.$id.'  and id not in (select study_items_id from study_item_accesses  where  value = false and study_id= '.$id.')')
                ->select('name', 'id', 'study_id')
                ->get();


                return   DB::table('accesses')
            ->Join('users', 'accesses.user_id', '=', 'users.id')
            ->leftJoin('study_item_accesses', 'users.id', '=', 'study_item_accesses.user_id')
            ->where([['accesses.study_id', $id], ['study_item_accesses.value', false]])
            ->selectRaw(' users.name,  users.id, accesses.study_id, study_item_accesses.value')
            ->get();




        $study = Study_item::first()->items()->select('user_id')->get();
        return response()->json($study);
    }

    public function formStudyItemListing($id)
    {




        $study = User::find(Auth::id())->sublist()
            ->where('accesses.user_id', Auth::id())
            ->get();
        return response()->json($study);
    }


    public function study_users($study_id, $study_item_id)
    {



//        return DB::table('study_items')
//            // ->leftJoin('study_item_accesses', 'study_items.id', '=', 'study_item_accesses.study_items_id')
//
//            ->whereRaw('study_id ='.$id.'  and id not in
//            (select study_items_id from study_item_accesses
//             where user_id =1 and value = true and study_id= '.$id.')
//             ')
//            ->select('name', 'id', 'study_id')
//            ->get();

        return

DB::select('(select distinct u.id  as username,
                T2.value,
                u.name,
                u.id,
                T1.study_id,
                T2.study_items_id,
                CASE
                    WHEN T2.name is null THEN text \'Not Registered\'
                    ELSE text \'Registered\'
                    END AS user_status
from users u


         join(select distinct a.user_id, a.study_id from accesses a where study_id = '.$study_id.') T1
             on u.id = T1.user_id 
         left join(select distinct sia.user_id,
                                   sia.value,
                                   sia.study_items_id,
                                   si.name
                   from study_item_accesses sia
                            join study_items si
                                 on sia.study_items_id = si.id
                   where sia.study_id = '.$study_id.' and study_items_id= '.$study_item_id.') T2
                  on u.id = T2.user_id)')


            ;

//        return   DB::table('accesses')
//            ->Join('users', 'accesses.user_id', '=', 'users.id')
//            ->leftJoin('study_item_accesses', 'users.id', '=', 'study_item_accesses.user_id')
//            ->whereRaw('accesses.study_id= '. $id )
//            ->selectRaw(' users.name,  users.id, accesses.study_id, study_item_accesses.value')
//            ->get();

//        return   DB::table('accesses')
//            ->Join('users', 'accesses.user_id', '=', 'users.id')
//            ->leftJoin('study_item_accesses', 'users.id', '=', 'study_item_accesses.user_id')
//            ->where([['accesses.study_id', $id], ['study_item_accesses.value', false]])
//            ->selectRaw(' users.name,  users.id, accesses.study_id, study_item_accesses.value')
//            ->get();


//        return   DB::table('accesses')
//            ->Join('users', 'accesses.user_id', '=', 'users.id')
//            ->leftJoin('study_item_accesses', 'users.id', '=', 'study_item_accesses.user_id')
//            ->where('accesses.study_id', $id)
//            ->selectRaw(' users.name,  users.id, accesses.study_id, study_item_accesses.value')
//            ->get();

//        return   DB::table('accesses')
//            ->Join('users', 'accesses.user_id', '=', 'users.id')
//            ->Join('study_item_accesses', 'accesses.study_id', '=', 'study_item_accesses.study_id')
//            ->whereRaw('accesses.study_id = '.$id.' and accesses.user_id   IN (select user_id from study_item_accesses where study_id = '.$id.')')
//            ->selectRaw('distinct users.name,  users.id, accesses.study_id')
//            ->get();
//
//
//     return   DB::table('accesses')
//            ->Join('users', 'accesses.user_id', '=', 'users.id')
//            ->Join('study_item_accesses', 'accesses.study_id', '=', 'study_item_accesses.study_id')
//            ->whereRaw('accesses.study_id = '.$id.' and accesses.user_id  not IN (select user_id from study_item_accesses where study_id = '.$id.')')
//            ->selectRaw('distinct users.name,  users.id, accesses.study_id')
//            ->get();
    }

    public function study_users_form_populators($id)
    {

        return   DB::table('accesses')
            ->Join('users', 'accesses.user_id', '=', 'users.id')
            ->Join('study_item_accesses', 'accesses.study_id', '=', 'study_item_accesses.study_id')
            ->whereRaw('accesses.study_id = '.$id.' and accesses.user_id   IN (select user_id from study_item_accesses where study_id = '.$id.')')
            ->selectRaw('distinct users.id')
            ->get();


    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function study_item_access(Request $request){

        foreach ($request['options'] as $item) {
            unset($item['name']);
            DB::table('study_item_accesses')->insert([$item
            ]);
        }
        DB::table('study_item_accesses')->where('value', '=', null)->delete();

    }
}
