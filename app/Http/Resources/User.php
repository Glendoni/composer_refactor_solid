<?php

namespace App\Http\Resources;
use App\Access;

use App\Http\Resources\Study as StudyResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Access as AccessResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AccessCollection;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Collection;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'studies' => $this->access,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
