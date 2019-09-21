<?php

namespace App\Http\Controllers;

use App\Form;
use App\Services\FormService;
use App\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public $formService;

    public function __construct(FormService $FormService)
    {
        $this->formService = $FormService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flight = Stream::where('studyId', 2)->get();
        $flight = $flight->pluck('questions');
        $flight = json_decode($flight);
        $flight = Arr::flatten($flight);

        print '[' . join($flight, ',') . ']';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $formId)
    {
        $jsonRequest = json_encode($request->post());
        $request['saved_for_later_answers'] = $jsonRequest;
        $request->validate([
            'saved_for_later_answers' => 'required|json',
        ]);

        $jsonRequest = $this->formService->saveForLater($jsonRequest, $formId);
        return response()->json($jsonRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Form $form
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = Stream::where('studyId', $id)->get();
        $form = $form->pluck('questions');
        $form = json_decode($form);

        $form = Arr::flatten($form);

        print '[' . join($form, ',') . ']';
    }

    public function getFormValues($id)
    {
        $this->formService->getFormValues($id);
        $formDetails = $this->formService->returnFormData();
        print_r($formDetails);
    }


    public function saveForm(Request $request, $formId, $studyId = false)
    {
        $request = $request->post();
        $this->formService->formId = $formId;
        $this->formService->studyId = $studyId;

        $this->formService->fieldNameExtractor($request);
        $myArray = $this->formService->copySaveForLaterJsonToArray($request);
        $checkForDuplicated = $this->formService->checkForDuplicates();
        if (!count($checkForDuplicated)) {
            $this->formService->saveFormAnswers($myArray);
            return $this->formService->update_study_item_accesses();
        } else {
            return 'no can do';
        }
    }

    public function globalSiteConfig(Request $request)
    {

        DB::table('cms_form_configs')
            ->updateOrInsert(
                [
                    'study_id' => $request->study_id
                ],
                [
                    'intro_text' => $request->intro_text,
                    'site_name' => $request->site_name,
                    'path_to_logo' => $request->path_to_logo,
                    'background_colour' => $request->background_colour,
                    'text_colour' => $request->text_colour
                ]
            );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Form $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Form $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Form $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
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


}
