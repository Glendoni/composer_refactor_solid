<?php

namespace App\Http\Resources;

use App\Http\Resources\Study as StudyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Study extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            //'access' =>  StudyResource::collection($this->access),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
