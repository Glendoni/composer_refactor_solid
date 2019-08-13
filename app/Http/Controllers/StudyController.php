<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudyService;
use App\Services\AccessService;
use App\Services\StudyItemService;
use App\Services\StudyUserService;


class StudyController extends Controller
{
    private $studyService;
    private $studyItemService;
    private $studyUserService;

    public function __construct(StudyService $studyService, StudyItemService $studyItemService,StudyUserService $studyUserService)
    {
        $this->studyService = $studyService;
        $this->studyItemService = $studyItemService;
        $this->studyUserService = $studyUserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StudyService $studyService)
    {
        $study = $studyService->index();
        return response()->json($study);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formGetStudies(StudyService $studyService)
    {
        $study = $studyService->index();
        return response()->json($study);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, AccessService $accessService)
    {
        $scc =   $this->studyService->findStudyByName($request);
        if (count($scc)) {
            return  array('error' => 'name already in use');
        }
        $new_study =  $studyService->create_new($request);
        if($new_study->id) {
            $accessService->create_new($new_study->id);
            return  array('success' => 'name already in use');
       }
        return  array('error' => 'something went wrong');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StudyService $studyService, $id)
    {
        $editable_study =  $studyService->findStudyById($id);
        return response()->json($editable_study);
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
        $id = $request->studyId; //to be updated
        $study = $this->studyService->findStudyById($request->studyId);
        if (!count((array) $study)) {
            return  array('error' => 'You do not have access to perform this action');
        }
        $study = $this->studyService->update($request, $id);
        return response()->json($study);
    }

    public function addStudyItem(Request $request, $id)
    {
        $preCheckTableToPreventDuplicates = $this->studyItemService->preCheckTableToPreventDuplicates($request->name, $id);
        if(!count($preCheckTableToPreventDuplicates)){
            $this->studyItemService->create_new($request);
            return  response()->json(['error' => 'Study item created']);
        }else{
            return  response()->json(['error' => 'Study item can not be created']);
        }
    }

    public function studyItemListing($id)
    {
        $studyItem = $this->studyItemService->studyItemListing($id);
        return response()->json($studyItem);
    }

    public function formStudyItemListing($id)
    {
        $study = $this->studyItemService->formStudyItemListings($id);
        return response()->json($study);
    }

    public function study_users($study_id, $study_item_id)
    {
        $studyUser = $this->studyUserService->studyUsers($study_id, $study_item_id);
        return response()->json($studyUser);
    }

    public function study_users_form_populators($id)
    {
        $studyUser = $this->studyUserService->study_users_form_populators($id);
        return response()->json($studyUser);
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

    function study_item_access(Request $request)
    {
//To be improved
        $study_item_access = $this->studyItemService->study_item_access($request);
        $this->studyItemService->study_item_accesses_delete_null();
        $this->studyItemService->study_item_accesses_delete_false();

        return response()->json($study_item_access);

    }

    function studyItem(int $id)
    {
        $studyItem = $this->studyItemService->findStudyById($id);
        return response()->json($studyItem);
    }

    function studyItemUpdate(Request $request, $id)
    {
        $studyItemUpdate =  $this->studyItemService->update($request, $id);
        return response()->json($studyItemUpdate);
    }
}
