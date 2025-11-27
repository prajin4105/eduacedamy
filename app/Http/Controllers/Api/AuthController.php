<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return $this->errorResponse('The provided credentials are incorrect.', 422);
        }

        // Revoke existing tokens for this device name if provided
        $deviceName = $request->string('device_name')->toString() ?: $request->userAgent();

        // Optionally, you could delete existing tokens with same name
        $user->tokens()->where('name', $deviceName)->delete();

        $plainTextToken = $user->createToken($deviceName)->plainTextToken;

        return $this->successResponse([
            'token' => $plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 'Login successful', 200);
    }

    public function logout(Request $request)
    {
        // Revoke current access token
        $request->user()?->currentAccessToken()?->delete();

        return $this->successResponse(null, 'Logged out successfully', 200);
    }

    public function me(Request $request)
    {
        // For GET requests (frontend compatibility), return direct data
        // For POST requests (Postman), return standardized format
        if ($request->isMethod('GET')) {
            return response()->json($request->user());
        }

        return $this->successResponse($request->user(), 'User retrieved successfully', 200);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'student',
        ]);

        // Create token
        $deviceName = $request->string('device_name')->toString() ?: $request->userAgent();
        $token = $user->createToken($deviceName)->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 'Registration successful', 201);
    }
}


