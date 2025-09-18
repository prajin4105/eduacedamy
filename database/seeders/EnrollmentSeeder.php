<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some students and courses
        $students = User::where('role', 'student')->take(5)->get();
        $courses = Course::where('is_published', true)->take(3)->get();
        
        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found. Please run UserSeeder and CourseSeeder first.');
            return;
        }

        // Create some sample enrollments
        $enrollments = [
            [
                'user_id' => $students[0]->id,
                'course_id' => $courses[0]->id,
                'amount_paid' => $courses[0]->price,
                'status' => 'completed',
                'enrolled_at' => now()->subDays(5),
            ],
            [
                'user_id' => $students[0]->id,
                'course_id' => $courses[1]->id,
                'amount_paid' => $courses[1]->price,
                'status' => 'completed',
                'enrolled_at' => now()->subDays(3),
            ],
            [
                'user_id' => $students[1]->id,
                'course_id' => $courses[0]->id,
                'amount_paid' => $courses[0]->price,
                'status' => 'completed',
                'enrolled_at' => now()->subDays(2),
            ],
            [
                'user_id' => $students[2]->id,
                'course_id' => $courses[2]->id,
                'amount_paid' => $courses[2]->price,
                'status' => 'completed',
                'enrolled_at' => now()->subDays(1),
            ],
        ];

        foreach ($enrollments as $enrollmentData) {
            // Check if enrollment already exists
            $existing = Enrollment::where('user_id', $enrollmentData['user_id'])
                ->where('course_id', $enrollmentData['course_id'])
                ->first();

            if (!$existing) {
                Enrollment::create($enrollmentData);
            }
        }

        $this->command->info('Sample enrollments created successfully!');
    }
}
