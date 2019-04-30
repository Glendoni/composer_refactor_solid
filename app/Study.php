<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Study extends Model
{

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'description'
    ];

}
