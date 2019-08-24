<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $fillable = [
        'studyName', 'studyId', 'question_uniqid','questions'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    protected $visible = ['id','questions'];
}
