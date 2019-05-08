<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getFormUser() {

     $formUser = User::find(Auth::id());
        return response()->json($formUser);
    }
}
