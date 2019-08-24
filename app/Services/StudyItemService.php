<?php

namespace App\Services;

use App\Repositories\StudyItemRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class StudyItemService
{
    public function __construct(StudyItemRepository $studyItemRepository)
    {
        $this->studyItem = $studyItemRepository;
    }

    public function create_new(Request $request)
    {
        $studyItem['name'] = $request->name;
        $studyItem['note'] = $request->note;
        $studyItem['study_id'] = $request->study_id;
        $studyItem['created_by'] = Auth::id();

        return $this->studyItem->create($studyItem);
    }

    public function update(Request $request, $id){

        $attributes = $request->all();
        return $this->studyItem->update($id,$attributes);

    }

    public function preCheckTableToPreventDuplicates(string $name, int $id)
    {
        return $this->studyItem->where_study_name($id, $name);
    }

    public function studyItemListing($id){
        return $this->studyItem->studyItemListing($id);
    }

    public function formStudyItemListings($id){
        return $this->studyItem->formStudyItemListings($id);
    }

    public function formStudyItemListing($id){
        return $this->studyItem->formStudyItemListings($id);
    }
    public function findStudyById(int $id)
    {
        return $this->studyItem->where_study_id($id);
    }

    function study_item_access(Request $request)
    {
        return $this->studyItem->study_item_access($request);
    }

    function study_item_accesses_delete_null(){
        return $this->studyItem->study_item_accesses_delete_null();
    }

    function study_item_accesses_delete_false(){
        return $this->studyItem->study_item_accesses_delete_false();
    }



}
