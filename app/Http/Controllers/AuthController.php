<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($request->validated());
        $device = substr($request->userAgent() ?? '', 0, 255);

        $user->cart()->create();
        
        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken($device)->plainTextToken,
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'The provided credentials are incorrect.',
            ], 422);
        }


        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken($device)->plainTextToken,
        ]);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'User successfully logged out',
        ], 200);
    }
}
