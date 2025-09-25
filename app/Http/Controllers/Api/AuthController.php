<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 422);
        }

        // Revoke existing tokens for this device name if provided
        $deviceName = $request->string('device_name')->toString() ?: $request->userAgent();

        // Optionally, you could delete existing tokens with same name
        $user->tokens()->where('name', $deviceName)->delete();

        $plainTextToken = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke current access token
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6', 'confirmed'], // requires password_confirmation
    ]);

    // Create user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'student', // default role
    ]);

    // Create token
    $deviceName = $request->string('device_name')->toString() ?: $request->userAgent();
    $token = $user->createToken($deviceName)->plainTextToken;

    return response()->json([
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ], 201);
}

}


