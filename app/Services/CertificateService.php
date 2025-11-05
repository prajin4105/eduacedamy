<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\Test;
use App\Models\User;
use App\Models\UserTest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class CertificateService
{
    /**
     * Default passing score if not set on test
     */
    const DEFAULT_PASSING_SCORE = 75;

    /**
     * Check certificate eligibility and issue certificate if eligible
     * 
     * Rule 1: If course has tests, user must pass the test
     * Rule 2: If course has no tests, user must have 100% progress and is_completed = 1
     * 
     * @param User $user
     * @param Course $course
     * @return Certificate|null
     */
    public function checkAndIssueCertificate(User $user, Course $course): ?Certificate
    {
        // Recheck safeguard: Verify user doesn't already have a certificate
        $existingCertificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingCertificate) {
            return $existingCertificate;
        }

        // Check if course has tests
        $test = Test::where('course_id', $course->id)->first();

        if ($test) {
            // Rule 1: Course has test - user must pass the test
            return $this->issueCertificateForTest($user, $course, $test);
        } else {
            // Rule 2: Course has no test - user must complete course
            return $this->issueCertificateForCompletion($user, $course);
        }
    }

    /**
     * Issue certificate when user passes test (Rule 1)
     * 
     * @param User $user
     * @param Course $course
     * @param Test $test
     * @return Certificate|null
     */
    protected function issueCertificateForTest(User $user, Course $course, Test $test): ?Certificate
    {
        // Find the user's test result
        $userTest = UserTest::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->where('passed', true)
            ->first();

        if (!$userTest) {
            return null; // User hasn't passed the test
        }

        // Check if score meets passing threshold
        $passingScore = $test->passing_score ?? self::DEFAULT_PASSING_SCORE;
        
        if ($userTest->score < $passingScore) {
            return null; // Score doesn't meet passing threshold
        }

        // Issue certificate
        return $this->createCertificate($user, $course);
    }

    /**
     * Issue certificate when user completes course without test (Rule 2)
     * 
     * @param User $user
     * @param Course $course
     * @return Certificate|null
     */
    protected function issueCertificateForCompletion(User $user, Course $course): ?Certificate
    {
        $progress = CourseProgress::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$progress) {
            return null; // No progress record
        }

        // Check conditions: progress_percentage == 100 AND is_completed == 1
        if ($progress->progress_percentage == 100 && $progress->is_completed == 1) {
            return $this->createCertificate($user, $course);
        }

        return null; // Conditions not met
    }

    /**
     * Create certificate record and generate PDF
     * 
     * @param User $user
     * @param Course $course
     * @return Certificate
     */
    protected function createCertificate(User $user, Course $course): Certificate
    {
        // Generate unique certificate number
        do {
            $certificateNumber = 'CERT-' . strtoupper(Str::random(8));
        } while (Certificate::where('certificate_number', $certificateNumber)->exists());

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
        ]);

        // Generate PDF and save path
        try {
            $certificatePath = $this->generateCertificatePdf($certificate, $user, $course);
            $certificate->update(['certificate_path' => $certificatePath]);
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate PDF', [
                'certificate_id' => $certificate->id,
                'error' => $e->getMessage()
            ]);
            // Continue even if PDF generation fails - certificate is still issued
        }

        // TODO: Dispatch event/notification for certificate issuance
        // Event::dispatch(new CertificateIssued($certificate));

        return $certificate;
    }

    /**
     * Generate PDF certificate and return path
     * 
     * @param Certificate $certificate
     * @param User $user
     * @param Course $course
     * @return string
     */
    protected function generateCertificatePdf(Certificate $certificate, User $user, Course $course): string
    {
        $course->load('instructor');

        $certificateData = [
            'student_name' => $user->name,
            'course_title' => $course->title,
            'certificate_number' => $certificate->certificate_number,
            'issue_date' => $certificate->issued_at->format('F j, Y'),
            'instructor_name' => $course->instructor->name ?? 'Course Instructor'
        ];

        $html = view('certificates.template', $certificateData)->render();

        // Save PDF in public storage
        $pdfPath = 'certificates/' . $certificate->certificate_number . '.pdf';
        $fullPath = storage_path('app/public/' . $pdfPath);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Generate PDF using Browsershot
        Browsershot::html($html)
            ->margins(0, 0, 0, 0)
            ->format('A4')
            ->landscape()
            ->showBackground()
            ->save($fullPath);

        return $pdfPath;
    }
}

