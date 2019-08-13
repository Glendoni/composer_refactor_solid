<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class StudyUserRepository
{
    protected $studyUser;

    public function study_users($study_id, $study_item_id)
    {

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
         join(select distinct a.user_id, a.study_id from accesses a where study_id = ' . $study_id . ') T1
             on u.id = T1.user_id 
         left join(select distinct sia.user_id,
                                   sia.value,
                                   sia.study_items_id,
                                   si.name
                   from study_item_accesses sia
                            join study_items si
                                 on sia.study_items_id = si.id
                   where sia.study_id = ' . $study_id . ' and study_items_id= ' . $study_item_id . ') T2
                  on u.id = T2.user_id)
                  order by u.name');

    }

    public function study_users_form_populators($id)
    {
        return DB::table('accesses')
            ->Join('users', 'accesses.user_id', '=', 'users.id')
            ->Join('study_item_accesses', 'accesses.study_id', '=', 'study_item_accesses.study_id')
            ->whereRaw('accesses.study_id = ' . $id . ' and accesses.user_id   IN (select user_id from study_item_accesses where study_id = ' . $id . ')')
            ->selectRaw('distinct users.id')
            ->get();
    }



}
