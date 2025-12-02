<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Test;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            // Each course has 1-2 tests (60% chance for 1 test, 40% for 2 tests)
            $testCount = rand(1, 100) <= 60 ? 1 : 2;

            for ($i = 0; $i < $testCount; $i++) {
                $isFinal = $i === $testCount - 1; // Last test is the final test

                Test::create([
                    'course_id' => $course->id,
                    'title' => $this->generateTestTitle($course, $i, $testCount, $isFinal),
                    'description' => $this->generateTestDescription($course, $isFinal),
                    'passing_score' => $this->getRandomPassingScore($isFinal),
                    'max_attempts' => $isFinal ? 3 : 5, // Final tests have fewer attempts
                    // 'time_limit_minutes' => $isFinal ? 60 : 30, // Final tests are longer
                    // 'is_published' => true,
                    // 'is_timed' => true,
                    'created_at' => $course->created_at->addDays(rand(1, 7)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function generateTestTitle(Course $course, int $index, int $totalTests, bool $isFinal): string
    {
        $courseTitle = $course->title;

        if ($isFinal) {
            $templates = [
                "Final Assessment: {$courseTitle}",
                "{$courseTitle} Final Exam",
                "Comprehensive Test: {$courseTitle}",
                "Course Completion Test: {$courseTitle}",
            ];
        } else {
            $moduleNumber = $index + 1;
            $templates = [
                "Module {$moduleNumber} Quiz: {$courseTitle}",
                "Checkpoint {$moduleNumber}: {$courseTitle} Assessment",
                "Knowledge Check: {$courseTitle} - Part {$moduleNumber}",
                "Practice Test: {$courseTitle} - Section {$moduleNumber}",
            ];
        }

        return $templates[array_rand($templates)];
    }

    private function generateTestDescription(Course $course, bool $isFinal): string
    {
        $templates = [
            "This test will evaluate your understanding of the key concepts covered in this course. Make sure to review all the materials before attempting.",
            "Assess your knowledge and track your learning progress with this comprehensive test. Good luck!",
            "This assessment is designed to test your comprehension of the course material. Take your time and read each question carefully.",
            "Test your knowledge and reinforce your learning with this interactive quiz. You can review your answers before submitting.",
        ];

        $description = $templates[array_rand($templates)];

        if ($isFinal) {
            $description .= " This is the final assessment for the course. Passing this test is required to receive your certificate of completion.";
        }

        return $description;
    }

    private function getRandomPassingScore(bool $isFinal): int
    {
        // Final tests have higher passing scores
        if ($isFinal) {
            return rand(70, 80); // 70-80% for final tests
        }
        return rand(60, 75); // 60-75% for regular tests
    }
}
