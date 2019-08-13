<?php

namespace App\Services;

use App\Repositories\StudyUserRepository;
use Illuminate\Http\Request;

class StudyUserService
{
    public function __construct(StudyUserRepository $studyUserRepository)
    {
        $this->studyUser = $studyUserRepository;
    }

    public function studyUsers($study_id, $study_item_id){
        return $this->studyUser->study_users($study_id, $study_item_id);
    }
    public function study_users_form_populators($id)
    {
        return $this->studyUser->study_users_form_populators($id);

    }


}


