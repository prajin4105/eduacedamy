<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Str;

class TwoCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get an instructor
        $instructor = User::where('role', 'instructor')->first();
        
        if (!$instructor) {
            $this->command->error('No instructor found. Please run UserSeeder first.');
            return;
        }

        // Create 2 courses with the actual images
        $courses = [
            [
                'title' => 'Complete Web Development Bootcamp',
                'description' => 'Learn HTML, CSS, JavaScript, React, Node.js, and MongoDB. Build real-world projects and become a full-stack developer. This comprehensive course covers everything from basic HTML to advanced React concepts and backend development with Node.js.',
                'price' => 99.99,
                'level' => 'beginner',
                'language' => 'english',
                'duration_in_minutes' => 3600,
                'what_you_will_learn' => 'HTML5 and CSS3 fundamentals, JavaScript ES6+, React.js and Redux, Node.js and Express, MongoDB and Mongoose, RESTful API development, Authentication and authorization, Deployment strategies',
                'requirements' => 'Basic computer skills, No prior programming experience required, A computer with internet connection',
                'image' => 'courses/image.png', // Using your uploaded image
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Master Python programming for data analysis, visualization, and machine learning. Learn pandas, numpy, matplotlib, and scikit-learn through hands-on projects and real-world datasets.',
                'price' => 79.99,
                'level' => 'intermediate',
                'language' => 'english',
                'duration_in_minutes' => 2400,
                'what_you_will_learn' => 'Python programming fundamentals, Data manipulation with pandas, Data visualization with matplotlib and seaborn, Statistical analysis techniques, Machine learning with scikit-learn, Working with APIs and databases',
                'requirements' => 'Basic programming knowledge helpful but not required, Python installed on your computer, Enthusiasm for data and analytics',
                'image' => 'courses/01K55W9K5VXGTEGDRGF8BK1BS7.avif', // Using your uploaded image
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create([
                'instructor_id' => $instructor->id,
                'title' => $courseData['title'],
                'slug' => Str::slug($courseData['title']),
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

            // Create some sample videos for each course
            $videoData = [
                [
                    'title' => 'Introduction to ' . $course->title,
                    'description' => 'Get started with this comprehensive course and learn the fundamentals.',
                    'duration_seconds' => 1200, // 20 minutes
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Setting Up Your Development Environment',
                    'description' => 'Learn how to set up your development environment and tools.',
                    'duration_seconds' => 900, // 15 minutes
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Core Concepts and Fundamentals',
                    'description' => 'Master the core concepts and fundamental principles.',
                    'duration_seconds' => 1800, // 30 minutes
                    'sort_order' => 3,
                ],
                [
                    'title' => 'Hands-on Practice and Projects',
                    'description' => 'Apply what you\'ve learned with hands-on practice and real projects.',
                    'duration_seconds' => 2400, // 40 minutes
                    'sort_order' => 4,
                ],
            ];

            foreach ($videoData as $videoInfo) {
                Video::create([
                    'course_id' => $course->id,
                    'title' => $videoInfo['title'],
                    'description' => $videoInfo['description'],
                    'video_url' => 'videos/sample-video-' . $course->id . '-' . $videoInfo['sort_order'] . '.mp4',
                    'thumbnail_url' => 'video-thumbnails/thumbnail-' . $course->id . '-' . $videoInfo['sort_order'] . '.jpg',
                    'duration_seconds' => $videoInfo['duration_seconds'],
                    'sort_order' => $videoInfo['sort_order'],
                    'is_published' => true,
                ]);
            }

            $this->command->info("Created course: {$course->title} with {$course->videos->count()} videos");
        }

        $this->command->info('Successfully created 2 courses with your uploaded images!');
    }
}
