<?php

namespace App\Services;

use App\Repositories\StudyRepository;
use Illuminate\Http\Request;

class StudyService
{
    public function __construct(StudyRepository $studyRepository)
    {
        $this->study = $studyRepository;
    }

    public function index()
    {
        return $this->study->all();
    }

    public function create_new(Request $request)
    {
        $stream['name'] = $request->name;
        $stream['description'] = $request->description;
        $stream['invite_code'] = $request->invite_code;
        $stream['start_date'] = $request->start_date;
        $stream['end_date'] = $request->end_date;

        return $this->study->create($stream);
    }

    public function update(Request $request, $id){

         $attributes = $request->all();
        return $this->study->update($id, $attributes);

    }

    public function findStudyByName(Request $request)
    {
        return $this->study->where_study_name($request->name);
    }

    public function findStudyById(int $id)
    {
        return $this->study->where_study_id($id);
    }

}
