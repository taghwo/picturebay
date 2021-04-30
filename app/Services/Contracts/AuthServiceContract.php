<?php
namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AuthServiceContract
{
    public function registerUser(array $data);
    public function loginUser(array $data);
    public function generateAccessToken(Model $user);
}
