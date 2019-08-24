<?php

namespace App\Services;

use App\Repositories\StreamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StreamService
{

    protected $streamRepo;
    protected $streamItem;

    public function __construct(StreamRepository $stream)
    {
        $this->streamRepo = $stream;
    }

    public function create_new($request)
    {
        $incomingStream = $request->post();
        $uniqueID = uniqid();
        $incomingStream['question_uniqid'] = $uniqueID;
        $incomingStream = json_encode($incomingStream);

        $saveStream['questions'] = $incomingStream;
        $saveStream['question_uniqid'] = $uniqueID;
        $saveStream['studyId'] = $request['studyId']['id'];
        return $this->streamRepo->creates($saveStream);
    }

    public function update(Request $request, $id)
    {
        $attributes = json_encode($request->post());

        return $this->streamRepo->update($id, $attributes);
    }

    public function findStreamById(int $id)
    {
        $this->streamItem = $this->streamRepo->where_stream('studyId', $id);
    }

    public function findStreamByUniqueId($id)
    {
        $this->streamItem = $this->streamRepo->where_stream('question_uniqid', $id);
    }

    public function parseStreamResponse()
    {
        $streamItem = $this->streamItem->pluck('questions');
        $streamItem = json_decode($streamItem);
        $streamItem = Arr::flatten($streamItem);
        return $streamItem;
    }
}
