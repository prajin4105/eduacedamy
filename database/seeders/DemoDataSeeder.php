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
use App\Models\Review;
use App\Models\Plan;

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

        // Additional demo students
        $student2 = User::firstOrCreate(
            ['email' => 'student2@eduacademy.com'],
            ['name' => 'Mark Learner', 'password' => Hash::make('password'), 'role' => 'student']
        );
        $student3 = User::firstOrCreate(
            ['email' => 'student3@eduacademy.com'],
            ['name' => 'Sara Coder', 'password' => Hash::make('password'), 'role' => 'student']
        );

        // Create 50 additional instructors
        $instructors = [$instructor];
        for ($i = 1; $i <= 50; $i++) {
            $inst = User::firstOrCreate(
                ['email' => "instructor{$i}@eduacademy.com"],
                [
                    'name' => "Instructor {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'instructor'
                ]
            );
            $instructors[] = $inst;
        }

        // Create 100 additional students
        $students = [$student, $student2, $student3];
        for ($i = 1; $i <= 100; $i++) {
            $std = User::firstOrCreate(
                ['email' => "student{$i}@eduacademy.com"],
                [
                    'name' => "Student {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'student'
                ]
            );
            $students[] = $std;
        }

        // Categories
        $dev = Category::firstOrCreate(['name' => 'Development', 'slug' => 'development']);
        $design = Category::firstOrCreate(['name' => 'Design', 'slug' => 'design']);
        $business = Category::firstOrCreate(['name' => 'Business', 'slug' => 'business']);
        $marketing = Category::firstOrCreate(['name' => 'Marketing', 'slug' => 'marketing']);
        $photography = Category::firstOrCreate(['name' => 'Photography', 'slug' => 'photography']);

        $categories = [$dev, $design, $business, $marketing, $photography];

        // Course titles for variety
        $courseTopics = [
            ['title' => 'Vue for Beginners', 'description' => 'Learn Vue 3 from scratch and build modern SPAs.', 'cat' => 0],
            ['title' => 'React Mastery', 'description' => 'Master React hooks, state management, and performance optimization.', 'cat' => 0],
            ['title' => 'Angular Complete Course', 'description' => 'Full-stack Angular development with TypeScript.', 'cat' => 0],
            ['title' => 'Web Design Fundamentals', 'description' => 'Create beautiful and responsive web designs.', 'cat' => 1],
            ['title' => 'UI/UX Design Principles', 'description' => 'Learn the principles of great user interface and experience.', 'cat' => 1],
            ['title' => 'Business Strategy 101', 'description' => 'Develop effective business strategies for growth.', 'cat' => 2],
            ['title' => 'Digital Marketing Essentials', 'description' => 'Master digital marketing channels and tactics.', 'cat' => 3],
            ['title' => 'Photography for Beginners', 'description' => 'Learn photography basics and composition.', 'cat' => 4],
            ['title' => 'Node.js Backend Development', 'description' => 'Build scalable backends with Node.js and Express.', 'cat' => 0],
            ['title' => 'Python for Data Science', 'description' => 'Analyze data using Python and popular libraries.', 'cat' => 0],
        ];

        // Create 100 courses
        $courses = [];
        for ($i = 0; $i < 100; $i++) {
            $topicIndex = $i % count($courseTopics);
            $topic = $courseTopics[$topicIndex];
            $instructorIndex = $i % count($instructors);
            $catIndex = $topic['cat'];

            $courseSlug = Str::slug($topic['title'] . ' ' . ($i + 1));
            $course = Course::updateOrCreate(
                ['slug' => $courseSlug],
                [
                    'instructor_id' => $instructors[$instructorIndex]->id,
                    'title' => $topic['title'] . ' - Part ' . ($i + 1),
                    'description' => $topic['description'],
                    'price' => 29.99 + ($i % 5) * 20,
                    'is_published' => true,
                    'image' => "courses/" . (($i % 15) + 1) . ".png",
                ]
            );

            $course->categories()->sync([$categories[$catIndex]->id]);
            $courses[] = $course;

            // Add videos to each course (3-5 videos per course)
            $videoCount = 3 + ($i % 3);
            for ($v = 1; $v <= $videoCount; $v++) {
                $videoNumber = (($i + $v - 1) % 15) + 1;
                $thumbnailNumber = (($i + $v) % 15) + 1;

                Video::updateOrCreate(
                    ['course_id' => $course->id, 'title' => 'Video ' . $v],
                    [
                        'title' => 'Lesson ' . $v . ': ' . ucwords(Str::random(5)),
                        'sort_order' => $v - 1,
                        'duration_seconds' => 300 + ($v * 200),
                        'video_url' => "videos/{$videoNumber}.mp4",
                        'thumbnail_url' => "video-thumbnails/{$thumbnailNumber}.jpg",
                        'is_published' => true,
                    ]
                );
            }
        }

        // Create enrollments for students in courses
        foreach ($students as $std) {
            // Each student enrolls in 5-15 random courses
            $enrollmentCount = 5 + rand(0, 10);
            $enrolledCourses = array_rand($courses, min($enrollmentCount, count($courses)));

            // Handle single course selection
            if (!is_array($enrolledCourses)) {
                $enrolledCourses = [$enrolledCourses];
            }

            foreach ($enrolledCourses as $courseIndex) {
                $course = $courses[$courseIndex];

        Enrollment::firstOrCreate(
                    ['user_id' => $std->id, 'course_id' => $course->id],
                    [
                        'amount_paid' => $course->price,
                        'status' => collect(['pending', 'completed', 'cancelled'])->random(),
                        'progress_percentage' => rand(0, 100),
                        'enrolled_at' => now()->subDays(rand(1, 365)),
            ]
        );
    }
}

        // Add wishlist likes
        foreach ($students as $std) {
            $wishlistCount = rand(3, 10);
            $wishlistCourses = array_rand($courses, min($wishlistCount, count($courses)));

            if (!is_array($wishlistCourses)) {
                $wishlistCourses = [$wishlistCourses];
            }

            $courseIds = array_map(fn($idx) => $courses[$idx]->id, $wishlistCourses);
            $std->wishlist()->syncWithoutDetaching($courseIds);
        }

        // Create reviews for enrolled courses
        foreach ($students as $std) {
            $enrollments = $std->enrollments()->inRandomOrder()->take(rand(2, 5))->get();

            foreach ($enrollments as $enrollment) {
                Review::firstOrCreate(
                    ['user_id' => $std->id, 'course_id' => $enrollment->course_id],
                    [
                        'rating' => rand(1, 5),
                        'comment' => $this->getRandomReview(),
                    ]
                );
            }
        }
    }

    private function getRandomReview(): string
    {
        $reviews = [
            'Excellent course! Very informative and well-structured.',
            'Great instructor, easy to follow and understand.',
            'Good content with practical examples.',
            'Very helpful and comprehensive.',
            'Learned a lot from this course.',
            'The instructor explains things very clearly.',
            'Outstanding quality and great value.',
            'Highly recommended for beginners.',
            'Could be more in-depth but still good.',
            'Great foundational knowledge.',
            'Well-organized and professional.',
            'Very engaging and interactive.',
            'Helped me advance my skills significantly.',
            'Amazing course with real-world applications.',
            'Perfect for learning new concepts.',
        ];

        return $reviews[array_rand($reviews)];
    }
}
