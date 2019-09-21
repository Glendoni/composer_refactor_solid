<?php

namespace App\Services;

use App\Repositories\FormRepository;
use Illuminate\Http\Request;

class FormService
{

    protected $formRepo;
    protected $streamItem;
    protected $getWhereId;


    public $formId;
    public $studyId;
    public $formFieldName;


    public function __construct(FormRepository $formRepository)
    {
        $this->formRepo = $formRepository;
    }

    public function saveForLater($jsonRequest, $formId)
    {
        return $this->formRepo->saveForLater($formId, $jsonRequest);
    }

    public function getFormValues($id)
    {
        return $this->getWhereId = $this->formRepo->getWhereId($id);
    }

    public function returnFormData()
    {
        $form = $this->getWhereId;
        $form = $form->pluck('saved_for_later_answers');
        $form = json_decode($form);
        if (count($form)) {
            return $form[0];
        }
    }

    public function globalSiteConfig(Request $request)
    {

    }

    public function saveFormAnswers($myArray)
    {
        return $this->formRepo->saveFormAnswers($myArray);

    }

    public function copySaveForLaterJsonToArray($request)
    {
        $formId = $this->formId;
        $studyId = $this->studyId;
        $formFieldName = $this->formFieldName;
        $data = $this->formRepo->copySaveForLaterJson($formId, $studyId, $formFieldName);

        $i = 0;
        foreach ($request as $key => $value) {
            if (is_array($value)) {
                $value = null;
            };
            $data[$i]->answer = $value;
            $data[$i]->key = $key;
            $data[$i];
            unset($data[$i]->siaid);
            $i++;
        }
        return json_decode(json_encode($data), true);

    }

    public function checkForDuplicates()
    {
        $formId = $this->formId;
        $studyId = $this->studyId;
        return $this->formRepo->checkForDuplicates($formId, $studyId);

    }

    public function update_study_item_accesses()
    {
        $formId = $this->formId;
        $studyId = $this->studyId;
        return $this->formRepo->update_study_item_accesses($formId, $studyId);
    }

    public function fieldNameExtractor($request)
    {
        $formFieldName = array_keys($request);
        $this->formFieldName = "'" . implode("','", $formFieldName) . "'";

    }
}
