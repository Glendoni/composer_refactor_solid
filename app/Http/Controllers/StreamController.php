<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stream;
use App\Services\StreamService;

class StreamController extends Controller
{

    private $streamService;
    public function __construct(StreamService $streamService){

        $this->streamService = $streamService;

    }
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
       $this->streamService->findStreamById($request->id);
        $streamItem =  $this->streamService->parseStreamResponse();
        print '[' . join($streamItem, ',') . ']';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $streamItem =  $this->streamService->create_new($request);
        return Stream::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
        $this->streamService->findStreamByUniqueId($id);
        $streamItem =  $this->streamService->parseStreamResponse();
        print '[' . join($streamItem, ',') . ']';
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
      return   $this->streamService->update($request, $request->question_uniqid);
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
