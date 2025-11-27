<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get all settings
     */
    public function index(Request $request)
    {
        // Placeholder - implement when Settings model exists
        // For now, return empty or cached settings
        $settings = Cache::get('app_settings', []);
        
        return $this->successResponse($settings, 'Settings retrieved successfully', 200);
    }

    /**
     * Get a specific setting
     */
    public function show($key)
    {
        $settings = Cache::get('app_settings', []);
        $value = $settings[$key] ?? null;

        if ($value === null) {
            return $this->errorResponse('Setting not found', 404);
        }

        return $this->successResponse(['key' => $key, 'value' => $value], 'Setting retrieved successfully', 200);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => ['required', 'array'],
        ]);

        $currentSettings = Cache::get('app_settings', []);
        $newSettings = array_merge($currentSettings, $request->settings);
        
        Cache::put('app_settings', $newSettings, now()->addDays(30));

        return $this->successResponse($newSettings, 'Settings updated successfully', 200);
    }

    // POST-based CRUD methods for Postman
    public function readViaPost(Request $request)
    {
        $request->validate(['key' => 'required|string']);
        return $this->show($request->key);
    }

    public function updateViaPost(Request $request)
    {
        return $this->update($request);
    }
}

