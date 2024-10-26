<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\{JsonResponse, Request};

class LoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            return response()->json([
                'data'         => $user->only('name', 'email'),
                'access_token' => $user->createToken('default')->plainTextToken,
                'token_type'   => 'Bearer',
            ], 200);
        }

        return response()->json(['message' => __('Invalid credentials')], 401);
    }
}
