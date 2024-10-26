<?php

namespace App\Http\Controllers\Auth;

use App\Models\{PasswordResetTokens, User};
use Illuminate\Support\Str;
use App\Events\ForgotPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};

class RecoveryPasswordController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $token = Str::random(60);

        $userPasswordReset = PasswordResetTokens::find($user->email);

        if ($userPasswordReset) {
            $userPasswordReset->token = $token;
            $userPasswordReset->save();
        } else {
            PasswordResetTokens::create([
                'email' => $user->email,
                'token' => $token,
            ]);
        }

        event(new ForgotPassword($user, $token));

        return response()->json(['message' => __('E-mail sent successfully!')]);
    }
}
