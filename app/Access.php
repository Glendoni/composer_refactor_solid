<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $visible = ['id','study_id','invitee_id', 'user_id'];

    protected $fillable = ['user_id'];
    //
    public function studies() {

        return $this->belongsToMany('App\Study')->withPivot('access_id', 'study_id');
    }

    public function user(){

        return $this->belongsToMany('App\User', 'access_study')->withPivot('user_id', 'user_id');;
    }

     public function access() {


        return  $this->belongsToMany('App\User', 'access_study')->withPivot('user_id', 'user_id');
     }

    public function cool() {


        return  $this->hasMany('App\User','id', 'user_id');
    }

    function sublist(){

        return  $this->hasMany('App\Study_item',  'study_id', 'study_id');

    }


    function subStudyPartisipants(){

        return  $this->belongsToMany('App\User', 'access_user', 'id');
    }

}
