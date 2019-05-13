<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Form;
use Illuminate\Http\Request;
use App\Stream;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,  $formId)
    {
$request['saved_for_later_answers'] = json_encode($request->post());
        //present
        $request->validate([

            'saved_for_later_answers' => 'required|json',

        ]);


        DB::table('forms')
            ->updateOrInsert(
                ['study_id' => $formId, 'user_id' => Auth::id()],
                ['saved_for_later_answers' => json_encode($request->post())],
                ['study_id' => $formId],
                ['user_id' => Auth::id()],
                ['save_for_later' => true] //if true then this will be used to retieve save for later form details
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Form  $form
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        //
    }

    public function getFormValues($id){

        $form = Form::where('study_id', $id)->select('saved_for_later_answers')->get();
        $form = $form->pluck('saved_for_later_answers');
        $form = json_decode($form);
        if (count($form)) {

            print_r($form[0]);
        }
    }

    public function globalSiteConfig(Request $request){

//return $request->path_to_logo;
        DB::table('cms_form_configs')
            ->updateOrInsert(
                ['study_id' => $request->study_id],
                ['intro_text' => $request->intro_text, 'site_name' => $request->site_name,
                'path_to_logo' => $request->path_to_logo,
                'background_colour' => $request->background_colour,
                'text_colour' => $request->text_colour]
            );
    }
}
