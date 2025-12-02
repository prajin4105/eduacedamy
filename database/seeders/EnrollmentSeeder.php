<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Course::where('is_published', true)->get();
        $enrollments = [];
        $now = now();
        
        // Ensure each student is enrolled in 3-10 courses
        foreach ($students as $student) {
            $enrollmentCount = rand(3, 10);
            $enrolledCourseIds = [];
            
            // Get random courses that the student isn't already enrolled in
            $availableCourses = $courses->whereNotIn('id', $enrolledCourseIds);
            $coursesToEnroll = $availableCourses->random(min($enrollmentCount, $availableCourses->count()));
            
            foreach ($coursesToEnroll as $course) {
                $enrolledAt = $this->getRandomEnrollmentDate($course->created_at);
                $status = $this->getRandomStatus($enrolledAt);
                $completedAt = $status === 'completed' ? $this->getCompletionDate($enrolledAt) : null;
                
                $enrollments[] = [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'amount_paid' => $this->getAmountPaid($course, $status),
                    'payment_id' => $status !== 'pending' ? 'pay_' . strtoupper(Str::random(14)) : null,
                    'order_id' => $status !== 'pending' ? 'order_' . strtoupper(Str::random(10)) : null,
                    'signature' => $status !== 'pending' ? hash('sha256', $student->id . $course->id . $now) : null,
                    'status' => $status,
                    'progress_percentage' => $this->getProgressPercentage($status),
                    'enrolled_at' => $enrolledAt,
                    'last_accessed_at' => $this->getLastAccessedAt($enrolledAt, $status, $completedAt),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                
                $enrolledCourseIds[] = $course->id;
                
                // Chunk inserts to avoid memory issues
                if (count($enrollments) >= 1000) {
                    DB::table('enrollments')->insert($enrollments);
                    $enrollments = [];
                }
            }
        }
        
        // Insert any remaining enrollments
        if (!empty($enrollments)) {
            DB::table('enrollments')->insert($enrollments);
        }
    }
    
    private function getRandomEnrollmentDate(Carbon $courseCreatedAt): Carbon
    {
        // Enrollment can't be before the course was created
        $startDate = $courseCreatedAt->copy()->addDays(rand(1, 30));
        $endDate = now();
        
        return $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate)));
    }
    
    private function getRandomStatus(Carbon $enrolledAt): string
    {
        $daysSinceEnrollment = $enrolledAt->diffInDays(now());
        
        // The longer since enrollment, the higher chance of completion
        if ($daysSinceEnrollment > 60) {
            // 70% completed, 20% in-progress, 10% cancelled
            $rand = rand(1, 10);
            return $rand <= 7 ? 'completed' : ($rand <= 9 ? 'in_progress' : 'cancelled');
        } elseif ($daysSinceEnrollment > 30) {
            // 40% completed, 50% in-progress, 10% cancelled
            $rand = rand(1, 10);
            return $rand <= 4 ? 'completed' : ($rand <= 9 ? 'in_progress' : 'cancelled');
        } else {
            // 10% completed, 70% in-progress, 20% cancelled
            $rand = rand(1, 10);
            return $rand === 1 ? 'completed' : ($rand <= 8 ? 'in_progress' : 'cancelled');
        }
    }
    
    private function getCompletionDate(Carbon $enrolledAt): Carbon
    {
        // Completion is between 1 day and 6 months after enrollment
        $daysToComplete = rand(1, 180);
        return $enrolledAt->copy()->addDays($daysToComplete);
    }
    
    private function getAmountPaid(Course $course, string $status): float
    {
        if ($status === 'cancelled') {
            // For cancelled enrollments, maybe they got a refund
            return rand(0, 1) ? $course->price : 0;
        }
        
        // 90% chance of paying full price, 10% chance of a discount
        if (rand(1, 10) === 1) {
            // Apply a 10-50% discount
            $discount = rand(10, 50) / 100;
            return round($course->price * (1 - $discount), 2);
        }
        
        return $course->price;
    }
    
    private function getProgressPercentage(string $status): int
    {
        return match($status) {
            'completed' => 100,
            'in_progress' => rand(5, 95),
            'cancelled' => rand(0, 100), // Could be any progress if cancelled
            default => 0,
        };
    }
    
    private function getLastAccessedAt(Carbon $enrolledAt, string $status, ?Carbon $completedAt): ?Carbon
    {
        if ($status === 'completed') {
            // For completed courses, last access is the completion date
            return $completedAt;
        }
        
        if ($status === 'cancelled') {
            // For cancelled courses, last access is between enrollment and now
            return $enrolledAt->copy()->addDays(rand(0, $enrolledAt->diffInDays(now())));
        }
        
        // For in-progress courses, last access is recent (within last 7 days)
        return now()->subDays(rand(0, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
    }
}
