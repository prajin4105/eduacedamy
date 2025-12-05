<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstructorApplication;
use App\Models\User;
use App\Notifications\NewInstructorApplication;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class InstructorApplicationController extends Controller
{
    use ApiResponse;

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'required|string|max:2000',
            'portfolio_url' => 'nullable|url',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = $request->user();

        // Store document on private disk
        $documentPath = $request->file('document')->store('instructor_docs', 'private');

        // Check if user has existing application
        $existingApp = InstructorApplication::where('user_id', $user->id)->first();

        if ($existingApp && $existingApp->status === 'rejected') {
            // If rejected, update and set to pending
            $existingApp->update([
                'bio' => $validated['bio'],
                'portfolio_url' => $validated['portfolio_url'] ?? null,
                'document_path' => $documentPath,
                'status' => 'pending',
                'applied_at' => now(),
                'rejection_reason' => null,
            ]);
            $application = $existingApp;
        } else {
            // Create or update application
            $application = InstructorApplication::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $validated['bio'],
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                    'document_path' => $documentPath,
                    'status' => 'pending',
                    'applied_at' => now(),
                ]
            );
        }

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewInstructorApplication($application));

        return $this->successResponse([
            'application' => [
                'id' => $application->id,
                'status' => $application->status,
                'applied_at' => $application->applied_at,
            ],
        ], 'Application submitted successfully', 201);
    }
}
