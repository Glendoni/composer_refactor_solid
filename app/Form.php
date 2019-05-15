<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Form extends Model
{
    //
function scopeSaveForm($formId, $request ){

    DB::table('forms')
        ->updateOrInsert(
            ['study_id' => $formId, 'user_id' => Auth::id()],
            ['saved_for_later_answers' => json_encode($request->post()),
                'study_id' => $formId,
                'user_id' => Auth::id(),
                'save_for_later' => true] //if true then this will be used to retieve save for later form details
        );

}
}
