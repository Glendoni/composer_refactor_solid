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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $formId)
    {


        $request['saved_for_later_answers'] = json_encode($request->post());
        //present
        $request->validate([
            'saved_for_later_answers' => 'required|json',
        ]);

        DB::table('forms')
            ->updateOrInsert(
                ['study_id' => $formId, 'user_id' => Auth::id()],
                ['saved_for_later_answers' => json_encode($request->post()),
                    'study_id' => $formId,
                    'user_id' => Auth::id()] //if true then this will be used to retieve save for later form details
            );


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

    public function getFormValues($id)
    {

        $form = Form::where('study_id', $id)->select('saved_for_later_answers')->get();
        $form = $form->pluck('saved_for_later_answers');
        $form = json_decode($form);
        if (count($form)) {

            print_r($form[0]);
        }
    }

    public function globalSiteConfig(Request $request)
    {

//return $request->path_to_logo;
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

    public function saveForm(Request $request, $formId, $studyId = false)
    {

        $formFieldName = array_keys($request->post());
        $formFieldName = "'" . implode("','", $formFieldName) . "'";
        $data = DB::select('
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

        $i = 0;
        foreach ($request->post() as $key => $value) {


            if (is_array($value)) {
                $value = null;
            };
            $data[$i]->answer = $value;
            $data[$i]->key = $key;
            $data[$i];
            unset($data[$i]->siaid);
            $i++;
        }
        $myArray = json_decode(json_encode($data), true);


        $checkForDuplicated = DB::table('study_item_accesses')
            ->where([['user_id', Auth::id()],
                ['study_items_id', $formId],
                ['study_id', $studyId],
                ['completed', 1]])
            ->get();

        if (!count($checkForDuplicated)) {

            DB::table('form_answers')->insert($myArray);
            DB::table('study_item_accesses')
                ->where([['user_id', Auth::id()],

                    ['study_items_id', $formId],
                    ['study_id', $studyId]])
                ->update(['completed' => 1]);

        } else {

            return 'no can do';
        }

    }
}
