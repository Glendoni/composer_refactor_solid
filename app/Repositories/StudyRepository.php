<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Study;
use App\User;
use Illuminate\Support\Facades\Auth;

class StudyRepository
{

    protected $study;


    public function __construct(Study $study)
    {
        $this->study = $study;
    }
 public function all(){

  return   User::find(Auth::id())->access()
         ->where('accesses.active', true)
         ->where('accesses.user_id', Auth::id())
         ->get();
 }
    public function create($attributes)
    {
        return   $this->study->create($attributes);
    }

    public function update($id, array $attributes)
    {
        return   $this->study->find($id)->update($attributes);
    }

    public function where_study_name($name)
    {
       return $this->study->where([['name', $name],['user_id', Auth::id()]])->get();
    }

    public function where_study_id($id)
    {
        return $this->study->find($id);
    }

}
