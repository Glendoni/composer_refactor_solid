<?php

namespace App\Services;

use App\Access;
use App\Repositories\AccessRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AccessService
{
    protected $access;

    public function __construct(AccessRepository $accessRepository)
    {
        $this->access = $accessRepository;
    }

    public function index()
    {
        return $this->access->all();
    }

    public function create_new( int $new_study)
    {


        $unique_id = uniqid();
        $access['user_id'] = Auth::id();
        $access['invitee_email'] = Auth::user()->email;
        $access['study_id'] = $new_study;
        $access['created_by'] = Auth::id();
        $access['email_confirmation_id'] = $unique_id;
        $access['active'] = 1;

        return  $this->access->create($access);

    }


}
