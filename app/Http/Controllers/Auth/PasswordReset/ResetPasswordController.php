<?php

namespace App\Http\Controllers\Auth\PasswordReset;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetUpdateRequest;
use App\Traits\ResetPasswordTrait;
use App\User;
use App\PasswordReset;
use App\Notifications\PasswordResetSuccess;
use Notifications;
class ResetPasswordController extends Controller
{
    use ResetPasswordTrait;
/**

     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(PasswordResetUpdateRequest $request)
    {
        
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset) {
           return $this->invalidTokenResponse();
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user){
            return $this->userWasNotFoundResponse();
        }

        $user->password = $request->password;

        $user->save();

        $passwordReset->delete();

       $user->notify(new PasswordResetSuccess($user));

        return $this->passwordResetSuccessfulResponse($user);
    }


    private function passwordResetSuccessfulResponse($user)
    {

        $freshToken = $user->createToken('NAMEL data collection app')->accessToken;

        auth()->loginUsingId($user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset was successful',
            'token' =>  $freshToken,
            'data' => $user,
            'url' => $user->isAdmin()||$user->isStaff()?'/admin/dashboard':'/partner/dashboard'
            ]);
    }
}