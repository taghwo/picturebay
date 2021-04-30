<?php
namespace App\Services\ServiceImpl;

use App\Exceptions\GeneralException;
use App\Exceptions\UnauthenticatedException;
use App\Models\User;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\Contracts\UserContract;
use App\Services\Contracts\AuthServiceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceContract
{
    protected $user;
    public function __construct(UserContract $user, RoleContract $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function registerUser($data)
    {
        $newUser =  $this->user->withModels(['role'])->create($data);
        $newUser->token =  $this->generateAccessToken($newUser);
        $newUser->role =  $this->role->find($newUser->role_id);
        return $newUser;
    }

    public function loginUser(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = auth()->user();
            $user->token = $this->generateAccessToken($user);

            return $user;
        } else {
            throw new UnauthenticatedException("Invalid credentials", 400);
        }
    }

    public function generateAccessToken(Model $user)
    {
        return $user->createToken($user->email)->plainTextToken;
    }
}
