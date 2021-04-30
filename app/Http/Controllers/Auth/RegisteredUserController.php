<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Contracts\AuthServiceContract;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceContract $authService)
    {
        $this->authService =  $authService;
    }
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role_id' => 'required|integer|exists:roles,id',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $data = $this->authService->registerUser($validatedData);

        return $this->respondSuccessWithData($data, 201);
    }
}
