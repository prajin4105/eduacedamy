<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Video;
use App\Models\Enrollment;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@eduacademy.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $instructor = User::firstOrCreate(
            ['email' => 'instructor@eduacademy.com'],
            ['name' => 'John Instructor', 'password' => Hash::make('password'), 'role' => 'instructor']
        );

        $student = User::firstOrCreate(
            ['email' => 'student@eduacademy.com'],
            ['name' => 'Jane Student', 'password' => Hash::make('password'), 'role' => 'student']
        );

        // Categories
        $dev = Category::firstOrCreate(['name' => 'Development', 'slug' => 'development']);
        $design = Category::firstOrCreate(['name' => 'Design', 'slug' => 'design']);

        // Courses
        $course = Course::firstOrCreate(
            ['slug' => 'vue-for-beginners'],
            [
                'instructor_id' => $instructor->id,
                'title' => 'Vue for Beginners',
                'description' => 'Learn Vue 3 from scratch and build modern SPAs.',
                'price' => 49.00,
                'is_published' => true,
            ]
        );
        $course->categories()->sync([$dev->id]);

        // Videos
        $videos = [
            [
                'title' => 'Introduction',
                'sort_order' => 0,
                'duration_seconds' => 300,
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'thumbnail_url' => null,
            ],
            [
                'title' => 'Components & Props',
                'sort_order' => 1,
                'duration_seconds' => 900,
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                'thumbnail_url' => null,
            ],
            [
                'title' => 'Routing Basics',
                'sort_order' => 2,
                'duration_seconds' => 800,
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
                'thumbnail_url' => null,
            ],
        ];

        foreach ($videos as $v) {
            Video::firstOrCreate(
                ['course_id' => $course->id, 'title' => $v['title']],
                [
                    'sort_order' => $v['sort_order'],
                    'duration_seconds' => $v['duration_seconds'],
                    'video_url' => $v['video_url'],
                    'thumbnail_url' => $v['thumbnail_url'],
                    'is_published' => true,
                ]
            );
        }

        // Enrollment
        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course->id],
            [
                'amount_paid' => 49.00,
                'status' => 'completed',
                'progress_percentage' => 0,
                'enrolled_at' => now(),
            ]
        );
    }
}


