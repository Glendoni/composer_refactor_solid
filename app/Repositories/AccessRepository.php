<?php
namespace App\Repositories;

use App\Access;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class AccessRepository
{
    protected $access;

    public function __construct(Access $access)
    {
        $this->access = $access;
    }

    public function create($attributes)
    {
        return   $this->access->create($attributes);
    }
}
