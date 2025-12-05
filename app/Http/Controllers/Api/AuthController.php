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

        // delete existing tokens with same name
        $user->tokens()->where('name', $deviceName)->delete();

        // 👉 અહીં expiry વાળો નવો code
        $newToken = $user->createToken($deviceName);
        $plainTextToken = $newToken->plainTextToken;

        $accessToken = $newToken->accessToken;
        $accessToken->expires_at = now()->addMinutes(120);
        $accessToken->save();

        // Load instructor application relationship
        $user->load('instructorApplication');

        return $this->successResponse([
            'token'      => $plainTextToken,
            'token_type' => 'Bearer',
            'user'       => $user,
            'instructor_status' => $user->instructorApplication?->status ?? null,
            'expires_in' => 300,
        ], 'Login successful', 200);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->successResponse(null, 'Logged out successfully', 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('instructorApplication');

        $responseData = [
            'user' => $user,
            'instructor_status' => $user->instructorApplication?->status ?? null,
        ];

        if ($request->isMethod('GET')) {
            return response()->json($responseData);
        }

        return $this->successResponse($responseData, 'User retrieved successfully', 200);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'student',
        ]);

        $deviceName = $request->string('device_name')->toString() ?: $request->userAgent();

        // 👉 register વખતે પણ same logic
        $newToken = $user->createToken($deviceName);
        $plainTextToken = $newToken->plainTextToken;

        $accessToken = $newToken->accessToken;
        $accessToken->expires_at = now()->addMinutes(120);
        $accessToken->save();

        // Load instructor application relationship
        $user->load('instructorApplication');

        return $this->successResponse([
            'token'      => $plainTextToken,
            'token_type' => 'Bearer',
            'user'       => $user,
            'instructor_status' => $user->instructorApplication?->status ?? null,
            'expires_in' => 300,
        ], 'Registration successful', 201);
    }
}
