<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stream;
use Illuminate\Support\Arr;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function studyQuestions(Request $request)
    {
        $flight = Stream::where('studyId', $request->id)->get();
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
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uniqueID = uniqid();
        $incomingStream = $request->post();
        $incomingStream['question_uniqid'] = $uniqueID;
        $incomingStream = json_encode($incomingStream);

        $stream = new Stream;
        $stream->questions = $incomingStream;
        $stream->question_uniqid = $uniqueID;
        $stream->studyId = $request->studyId;
        $stream->save();

        return Stream::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $flight = Stream::where('question_uniqid', $id)->get();
        $flight = $flight->pluck('questions');
        $flight = json_decode($flight);
        $flight = Arr::flatten($flight);

        print '[' . join($flight, ',') . ']';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $questions = json_encode($request->post());
        Stream::where('question_uniqid', $request->question_uniqid)
            ->update(['questions' => $questions]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
