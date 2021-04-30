<?php

namespace App\Http\Controllers\Auth\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\User;
use App\PasswordReset;
class PasswordResetController extends Controller
{
    
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'status' => 'failed',
                'message' => 'This password reset token is invalid.'
            ], 404);
            //password reset link expired
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status' => 'failed',
                'message' => 'This password reset token is invalid.'
            ], 417);
        }
        return response()->json([
            'status' => 'success',
            'data' => $passwordReset->only('token','email')
        ],200);
    }
    
}