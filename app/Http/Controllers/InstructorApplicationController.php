<?php

namespace App\Http\Controllers;

use App\Models\InstructorApplication;
use App\Models\User;
use App\Notifications\NewInstructorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class InstructorApplicationController extends Controller
{
    public function showForm()
    {
        return view('app'); // Vue SPA will handle the form
    }

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

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Application submitted successfully',
                'application' => [
                    'id' => $application->id,
                    'status' => $application->status,
                    'applied_at' => $application->applied_at,
                ],
            ], 201);
        }

        return back()->with('success', 'Application submitted successfully. We will review it soon.');
    }

    public function downloadDocument($id)
    {
        $application = InstructorApplication::findOrFail($id);
        
        // Check if user is admin or the applicant
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->id() !== $application->user_id)) {
            abort(403, 'Unauthorized');
        }

        if (!$application->document_path) {
            abort(404, 'Document not found');
        }

        if (!Storage::disk('private')->exists($application->document_path)) {
            abort(404, 'Document file not found');
        }

        return Storage::disk('private')->download($application->document_path);
    }
}
