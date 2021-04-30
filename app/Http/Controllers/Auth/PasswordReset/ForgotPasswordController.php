<?php

namespace App\Http\Controllers\Auth\PasswordReset;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetCreateRequest;
use App\User;
use App\Traits\ResetPasswordTrait;
use App\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Notifications;

class ForgotPasswordController extends Controller
{
    use ResetPasswordTrait;

    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(passwordResetCreateRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->userWasNotFoundResponse();
        }
       
        try {
            $passwordReset = $this->initPasswordReset($user);
        if ($user && $passwordReset) {
            $user->notify(new PasswordResetRequest($passwordReset->token));
            return $this->passWordResetLinkWasSent();
        }

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Sorry the server could not proccess your request at this time. Please try again later'
            ],417);
        }
        
    }

    private function initPasswordReset($user)
    {
        // dd($user);
        try {
            return PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => str_random(60)
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Sorry the server could not proccess your request at this time. Please try again later'
            ],417);
        }
      
    }
}
