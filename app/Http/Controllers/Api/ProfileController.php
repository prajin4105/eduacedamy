<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update the authenticated user's profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Build validation rules
        $rules = [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'date_of_birth' => ['sometimes', 'nullable', 'date', 'before:today'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'profile_picture' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:3048'],
        ];

        // Only validate password if it's provided and not empty
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $validated = $request->validate($rules);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // Handle password update - only if password is provided
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update user profile
        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ]);
    }
}
