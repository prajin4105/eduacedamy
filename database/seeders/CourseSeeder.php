<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Wipe old records (safe in dev environment)
        Course::truncate();

        // Ensure we have some instructors
        $instructors = User::where('role', 'instructor')->get();
        if ($instructors->isEmpty()) {
            $instructors = collect([
                User::create([
                    'name' => 'John Smith',
                    'email' => 'john.smith@eduacademy.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@eduacademy.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Michael Chen',
                    'email' => 'michael.chen@eduacademy.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'email_verified_at' => now(),
                ]),
                User::create([
                    'name' => 'Emily Davis',
                    'email' => 'emily.davis@eduacademy.com',
                    'password' => bcrypt('password'),
                    'role' => 'instructor',
                    'email_verified_at' => now(),
                ]),
            ]);
        }

        $courses = [
            [
                'title' => 'Complete Web Development Bootcamp',
                'description' => 'Learn HTML, CSS, JavaScript, React, Node.js, and MongoDB. Build real-world projects and become a full-stack developer.',
                'price' => 99.99,
                'level' => 'beginner',
                'language' => 'english',
                'duration_in_minutes' => 3600,
                'what_you_will_learn' => 'HTML, CSS, JS, React, Node.js, MongoDB, APIs, Auth',
                'requirements' => 'Basic computer skills, Internet connection',
                'image' => 'courses/web-development.jpg',
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Master Python programming for data analysis, visualization, and machine learning.',
                'price' => 79.99,
                'level' => 'intermediate',
                'language' => 'english',
                'duration_in_minutes' => 2400,
                'what_you_will_learn' => 'Python, Pandas, NumPy, Matplotlib, Scikit-learn',
                'requirements' => 'Basic programming knowledge helpful but not required',
                'image' => 'courses/python-data-science.jpg',
            ],
            [
                'title' => 'Digital Marketing Mastery',
                'description' => 'Comprehensive strategies including SEO, social media, email marketing, and ads.',
                'price' => 89.99,
                'level' => 'beginner',
                'language' => 'english',
                'duration_in_minutes' => 1800,
                'what_you_will_learn' => 'SEO, Social media, Ads, Content marketing, Analytics',
                'requirements' => 'No prior marketing experience required',
                'image' => 'courses/digital-marketing.jpg',
            ],
        ];

        foreach ($courses as $courseData) {
            $instructor = $instructors->random();

            Course::create([
                'instructor_id' => $instructor->id,
                'title' => $courseData['title'],
                'slug' => $this->generateUniqueSlug($courseData['title']),
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'level' => $courseData['level'],
                'language' => $courseData['language'],
                'duration_in_minutes' => $courseData['duration_in_minutes'],
                'what_you_will_learn' => $courseData['what_you_will_learn'],
                'requirements' => $courseData['requirements'],
                'image' => $courseData['image'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }

    /**
     * Generate a unique slug for a course.
     */
    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Keep looping until slug is unique
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}
