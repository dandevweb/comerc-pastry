<?php

namespace App\Http\Controllers\Auth;

use App\Models\{PasswordResetTokens, User};
use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => ['required', 'string', 'exists:password_reset_tokens,token'],
            'email'    => ['required', 'exists:users,email'],
            'password' => ['required', 'min:8', 'max:40', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/', 'confirmed'],
        ]);

        $email    = $request->email;
        $token    = $request->token;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        $user->update([
            'password' => $password,
        ]);

        PasswordResetTokens::whereEmail($email)->delete();

        return response()->json(['message' => __('Password reset successfully')]);
    }
}
