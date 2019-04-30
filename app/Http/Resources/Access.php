<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Access extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $request = parent::toArray($request);


        return [
            'data' => [
                $request['name'],
                $request['description']
            ],
            'links' => [
                'self' => 'link-value_A',
            ],
        ];
    }
}
