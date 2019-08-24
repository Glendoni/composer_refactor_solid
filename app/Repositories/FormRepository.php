<?php

namespace App\Repositories;

use App\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FormRepository
{
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function saveForLater($id, $attributes)
    {
        $db = DB::table('forms')
            ->updateOrInsert(
                ['study_id' => $id, 'user_id' => Auth::id()],
                ['saved_for_later_answers' => $attributes,
                    'study_id' => $id,
                    'user_id' => Auth::id()
                ] //if true then this will be used to retieve save for later form details
            );
        return $db;
    }

    public function getWhereId($id)
    {
        return $this->form->where('study_id', $id)->select('saved_for_later_answers')->get();
    }

    public function copySaveForLaterJson($formId, $studyId, $formFieldName)
    {

        return DB::select('
        (
        select 
        s.id as "question_id",
        s."studyId" as "study_id",
        T1.id as study_items_id,
        T1.user_id,
         T1.siaId,
         s.questions->>\'type\' as type
        from streams s
        LEFT JOIN
            (select sia.study_id, 
            si.id,
            sia.user_id, 
            sia.id as siaId
            from study_item_accesses sia
            LEFT JOIN study_items si
            on sia.study_items_id = si.id
            where  user_id  =' . Auth::id() . ' and si.id=' . $formId . ' and   si.study_id=' . $studyId . ' LIMIT 1
            ) T1
        on s."studyId" = T1.study_id
        where  questions->>\'name\' in (' . $formFieldName . ')
        and  T1.study_id notnull
        )'
        );
    }

    public function checkForDuplicates($formId, $studyId)
    {
        return DB::table('study_item_accesses')
            ->where([['user_id', Auth::id()],
                ['study_items_id', $formId],
                ['study_id', $studyId],
                ['completed', 1]])
            ->get();
    }

    public function saveFormAnswers($myArray)
    {

      return  DB::table('form_answers')->insert($myArray);
    }


    public function update_study_item_accesses($formId, $studyId){

        DB::table('study_item_accesses')
            ->where([['user_id', Auth::id()],

                ['study_items_id', $formId],
                ['study_id', $studyId]])
            ->update(['completed' => 1]);
    }

}
