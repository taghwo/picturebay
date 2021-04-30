<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Contracts\AuthServiceContract;

class AuthenticatedSessionController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceContract $authService)
    {
        $this->authService =  $authService;
    }

    public function currentuser()
    {
        return $this->respondSuccessWithData(auth()->user());
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $data = $this->authService->loginUser($validatedData);

        return $this->respondSuccessWithData($data);
    }

    /**
     * logout auth user
     */
    public function logout()
    {
        auth()->user()->tokens()->delete(); //delete all tokens tied to user

        Session()->flush(); //flush session

        return $this->respondWithSuccess('successfully logged out');
    }
}
