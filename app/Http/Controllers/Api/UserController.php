<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserContract;

class UserController extends Controller
{
    protected UserContract $user;
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }

    /**
     * Display photographers resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function photographers()
    {
        return $this->respondWithPaginatedData($this->user->withModels(['role:id,name'])->findWherePaginate('role_id', photographerId()));
    }

    /**
    * Display buyers resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function buyers()
    {
        return $this->respondWithPaginatedData($this->user->withModels(['role:id,name'])->findWherePaginate('role_id', buyerId()));
    }
}
