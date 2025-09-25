<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class CertificateController extends Controller
{
    /**
     * Get all certificates for the authenticated user
     */

public function index(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // ✅ Step 1: Check completed courses in course_progress
        $completedCourses = \DB::table('course_progress')
    ->where('user_id', $user->id)
    ->where('progress_percentage', 100) // ✅ check 100% progress
    ->pluck('course_id');


        foreach ($completedCourses as $courseId) {
            // Create certificate if not already exists
            \App\Models\Certificate::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $courseId],
                [
                    'certificate_number' => $this->generateCertificateNumber(),
                    'issued_at' => now(),
                ]
            );
        }

        // ✅ Step 2: Fetch certificates with course & instructor
        $certificates = Certificate::with(['course' => function ($query) {
                $query->with('instructor');
            }])
            ->where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->get();

        // ✅ Step 3: Transform certificates
        $transformedCertificates = $certificates->map(function ($certificate) {
            return [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'issued_at' => $certificate->issued_at,
                'course' => $certificate->course ? [
                    'id' => $certificate->course?->id,
                    'title' => $certificate->course?->title,
                    'image' => $certificate->course?->image_url ?? $certificate->course?->image ?? null,
                    'instructor' => [
                        'name' => $certificate->course->instructor?->name ?? 'Unknown Instructor'
                    ]
                ] : null
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $transformedCertificates
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve certificates',
            'error' => $e->getMessage()
        ], 500);
    }
}



    /**
     * Generate and download certificate for a course
     */
    public function generate(Request $request, $courseId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $course = Course::with('instructor')->findOrFail($courseId);

            $existingCertificate = Certificate::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $courseId],
                [
                    'certificate_number' => $this->generateCertificateNumber(),
                    'issued_at' => now(),
                ]
            );

            $certificateData = [
                'student_name' => $user->name,
                'course_title' => $course->title,
                'certificate_number' => $existingCertificate->certificate_number,
                'issue_date' => $existingCertificate->issued_at->format('F j, Y'),
                'instructor_name' => $course->instructor->name ?? 'Course Instructor'
            ];

            $html = view('certificates.template', $certificateData)->render();

            $pdfPath = storage_path('app/public/certificate_' . $existingCertificate->certificate_number . '.pdf');

            Browsershot::html($html)
                ->margins(0, 0, 0, 0)
                ->format('A4')
                ->landscape()
                ->showBackground()
                ->save($pdfPath);

            return response()->download($pdfPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Certificate generation failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to generate certificate',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview certificate inline
     */
    public function preview(Request $request, $courseId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $course = Course::with('instructor')->findOrFail($courseId);

            $certificate = Certificate::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->first();

            $certificateData = [
                'student_name' => $user->name,
                'course_title' => $course->title,
                'certificate_number' => $certificate ? $certificate->certificate_number : 'CERT-PREVIEW',
                'issue_date' => $certificate ? $certificate->issued_at->format('F j, Y') : now()->format('F j, Y'),
                'instructor_name' => $course->instructor->name ?? 'Course Instructor'
            ];

            $html = view('certificates.template', $certificateData)->render();

            $pdfPath = storage_path('app/public/certificate_preview.pdf');

            Browsershot::html($html)
                ->margins(0, 0, 0, 0)
                ->format('A4')
                ->landscape()
                ->showBackground()
                ->save($pdfPath);

            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="certificate_preview.pdf"',
            ]);
        } catch (\Exception $e) {
            Log::error('Certificate preview failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to generate certificate preview',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user is eligible for a certificate
     */
    public function checkEligibility(Request $request, $courseId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $course = Course::findOrFail($courseId);
            $enrollment = $user->enrollments()->where('course_id', $courseId)->first();

            if (!$enrollment) {
                return response()->json([
                    'eligible' => false,
                    'reason' => 'Not enrolled in this course'
                ]);
            }

            $isCompleted = $this->checkCourseCompletion($user, $courseId);
            $existingCertificate = Certificate::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->first();

            return response()->json([
                'eligible' => $isCompleted,
                'reason' => $isCompleted ? 'Eligible for certificate' : 'Course not completed',
                'already_issued' => $existingCertificate ? true : false,
                'certificate' => $existingCertificate ? [
                    'id' => $existingCertificate->id,
                    'certificate_number' => $existingCertificate->certificate_number,
                    'issued_at' => $existingCertificate->issued_at
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to check eligibility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download certificate by ID
     */
    public function download($certificateId)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $certificate = Certificate::with('course.instructor')->findOrFail($certificateId);

        if ($certificate->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $course = $certificate->course;
        $certificateData = [
            'student_name' => $user->name,
            'course_title' => $course->title,
            'certificate_number' => $certificate->certificate_number,
            'issue_date' => $certificate->issued_at->format('F j, Y'),
            'instructor_name' => $course->instructor->name ?? 'Course Instructor'
        ];

        $html = view('certificates.template', $certificateData)->render();

        $pdfPath = storage_path('app/public/certificate_' . $certificate->certificate_number . '.pdf');

        // Browsershot with custom size (no page split)
        Browsershot::html($html)
    ->margins(0, 0, 0, 0)
    ->showBackground()
    ->fullPage() // ensures entire HTML content fits on one PDF page
    ->save($pdfPath);



        return response()->download($pdfPath)->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        Log::error('Certificate download failed: ' . $e->getMessage());

        return response()->json([
            'message' => 'Failed to download certificate',
            'error' => $e->getMessage()
        ], 500);
    }
}


    private function checkCourseCompletion($user, $courseId)
    {
        return true; // replace with real completion logic
    }

    private function generateCertificateNumber()
    {
        do {
            $number = 'CERT-' . strtoupper(Str::random(8));
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }
}
