<?php

namespace App\Repositories;

use App\Stream;

class StreamRepository
{
    protected $stream;

    public function __construct(Stream $streamItem)
    {
        $this->stream = $streamItem;
    }

    public function creates($attributes)
    {
        return $this->stream->create($attributes);
    }

    public function update($id, string $attributes)
    {
        return $this->stream->where('question_uniqid', $id)->update(['questions' => $attributes]);
    }

    public function where_stream($field, $id)
    {
        return $this->stream->where($field, $id)->get();
    }
}
