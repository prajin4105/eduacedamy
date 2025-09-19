<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Video;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class po extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories if they don't exist
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Programming', 'slug' => 'programming'],
            ['name' => 'Data Science', 'slug' => 'data-science'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Design', 'slug' => 'design'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(['slug' => $categoryData['slug']], $categoryData);
        }

        // Get or create instructors
        $instructors = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        ];

        $instructorModels = [];
        foreach ($instructors as $instructorData) {
            $instructorModels[] = User::firstOrCreate(
                ['email' => $instructorData['email']],
                $instructorData
            );
        }

        // Courses data
        $coursesData = [
            [
                'title' => 'Complete JavaScript Mastery',
                'slug' => 'complete-javascript-mastery',
                'description' => '<p>Master JavaScript from basics to advanced concepts. Learn ES6+, async programming, DOM manipulation, and modern frameworks.</p>',
                'price' => 89.99,
                'level' => 'beginner',
                'category' => 'programming',
                'instructor_index' => 0,
                'what_you_will_learn' => "Understand JavaScript fundamentals\nMaster ES6+ features\nBuild interactive web applications\nWork with APIs and async programming\nUnderstand closures and prototypes",
                'requirements' => "Basic HTML knowledge\nText editor or IDE\nWeb browser\nWillingness to practice coding",
                'videos' => [
                    [
                        'title' => 'JavaScript Fundamentals - Variables and Data Types',
                        'description' => 'Learn about JavaScript variables, data types, and basic syntax in this comprehensive introduction.',
                        'duration_in_seconds' => 1245,
                    ],
                    [
                        'title' => 'Functions and Scope in JavaScript',
                        'description' => 'Understand how functions work, different types of functions, and JavaScript scope concepts.',
                        'duration_in_seconds' => 1680,
                    ],
                    [
                        'title' => 'Working with Arrays and Objects',
                        'description' => 'Deep dive into JavaScript arrays and objects, including modern methods and techniques.',
                        'duration_in_seconds' => 1420,
                    ],
                    [
                        'title' => 'DOM Manipulation and Events',
                        'description' => 'Learn how to interact with HTML elements and handle user events in JavaScript.',
                        'duration_in_seconds' => 1890,
                    ],
                    [
                        'title' => 'Asynchronous JavaScript - Promises and Async/Await',
                        'description' => 'Master asynchronous programming with callbacks, promises, and modern async/await syntax.',
                        'duration_in_seconds' => 2100,
                    ],
                ],
            ],
            [
                'title' => 'Python for Data Science',
                'slug' => 'python-for-data-science',
                'description' => '<p>Learn Python programming specifically for data science applications. Cover NumPy, Pandas, Matplotlib, and machine learning basics.</p>',
                'price' => 124.99,
                'level' => 'intermediate',
                'category' => 'data-science',
                'instructor_index' => 1,
                'what_you_will_learn' => "Python programming fundamentals\nData manipulation with Pandas\nData visualization with Matplotlib\nNumPy for numerical computing\nBasic machine learning concepts",
                'requirements' => "Basic programming knowledge\nPython 3.x installed\nJupyter Notebook or similar IDE\nMathematical curiosity",
                'videos' => [
                    [
                        'title' => 'Python Basics for Data Science',
                        'description' => 'Introduction to Python syntax and basic concepts needed for data science work.',
                        'duration_in_seconds' => 1560,
                    ],
                    [
                        'title' => 'NumPy Fundamentals',
                        'description' => 'Learn NumPy arrays, mathematical operations, and numerical computing basics.',
                        'duration_in_seconds' => 1780,
                    ],
                    [
                        'title' => 'Data Manipulation with Pandas',
                        'description' => 'Master Pandas DataFrames, data cleaning, and manipulation techniques.',
                        'duration_in_seconds' => 2340,
                    ],
                    [
                        'title' => 'Data Visualization with Matplotlib',
                        'description' => 'Create compelling visualizations and charts using Matplotlib library.',
                        'duration_in_seconds' => 1920,
                    ],
                ],
            ],
            [
                'title' => 'Modern React Development',
                'slug' => 'modern-react-development',
                'description' => '<p>Build modern web applications with React. Learn hooks, context, routing, and state management.</p>',
                'price' => 149.99,
                'level' => 'intermediate',
                'category' => 'web-development',
                'instructor_index' => 0,
                'what_you_will_learn' => "React components and JSX\nHooks and state management\nReact Router for navigation\nContext API and global state\nBuilding production-ready apps",
                'requirements' => "JavaScript knowledge\nHTML and CSS basics\nNode.js installed\nFamiliarity with ES6+",
                'videos' => [
                    [
                        'title' => 'React Components and JSX',
                        'description' => 'Understanding React components, JSX syntax, and component composition.',
                        'duration_in_seconds' => 1650,
                    ],
                    [
                        'title' => 'State and Props Management',
                        'description' => 'Learn how to manage component state and pass data between components.',
                        'duration_in_seconds' => 1480,
                    ],
                    [
                        'title' => 'React Hooks Deep Dive',
                        'description' => 'Master useState, useEffect, and other essential React hooks.',
                        'duration_in_seconds' => 2220,
                    ],
                    [
                        'title' => 'Routing with React Router',
                        'description' => 'Implement client-side routing and navigation in React applications.',
                        'duration_in_seconds' => 1340,
                    ],
                    [
                        'title' => 'Context API and Global State',
                        'description' => 'Manage application-wide state using React Context API.',
                        'duration_in_seconds' => 1890,
                    ],
                ],
            ],
            [
                'title' => 'Digital Marketing Fundamentals',
                'slug' => 'digital-marketing-fundamentals',
                'description' => '<p>Learn the essentials of digital marketing including SEO, social media marketing, email marketing, and analytics.</p>',
                'price' => 79.99,
                'level' => 'beginner',
                'category' => 'business',
                'instructor_index' => 2,
                'what_you_will_learn' => "SEO and search engine marketing\nSocial media marketing strategies\nEmail marketing campaigns\nGoogle Analytics and tracking\nContent marketing principles",
                'requirements' => "No prior experience needed\nAccess to internet\nWillingness to learn marketing concepts\nBasic computer skills",
                'videos' => [
                    [
                        'title' => 'Introduction to Digital Marketing',
                        'description' => 'Overview of digital marketing landscape and key concepts.',
                        'duration_in_seconds' => 1120,
                    ],
                    [
                        'title' => 'Search Engine Optimization (SEO) Basics',
                        'description' => 'Learn fundamental SEO techniques to improve website visibility.',
                        'duration_in_seconds' => 1890,
                    ],
                    [
                        'title' => 'Social Media Marketing Strategies',
                        'description' => 'Effective strategies for marketing on various social media platforms.',
                        'duration_in_seconds' => 1560,
                    ],
                    [
                        'title' => 'Email Marketing Best Practices',
                        'description' => 'Create effective email campaigns and build subscriber lists.',
                        'duration_in_seconds' => 1340,
                    ],
                    [
                        'title' => 'Google Analytics and Performance Tracking',
                        'description' => 'Set up and use Google Analytics to track marketing performance.',
                        'duration_in_seconds' => 1670,
                    ],
                ],
            ],
        ];

        // Sample random YouTube videos
        $sampleYoutubeVideos = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://www.youtube.com/watch?v=9bZkp7q19f0',
            'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
            'https://www.youtube.com/watch?v=L_jWHffIx5E',
            'https://www.youtube.com/watch?v=fJ9rUzIMcZQ',
        ];

        // Create courses and videos
        foreach ($coursesData as $index => $courseData) {
            $courseImagePath = $this->downloadAndStoreCourseImage($courseData['title'], $index);
            $category = Category::where('slug', $courseData['category'])->first();
            $instructor = $instructorModels[$courseData['instructor_index']];

            $course = Course::create([
                'title' => $courseData['title'],
                'slug' => $courseData['slug'],
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'image' => $courseImagePath,
                'level' => $courseData['level'],
                'instructor_id' => $instructor->id,
                'what_you_will_learn' => $courseData['what_you_will_learn'],
                'requirements' => $courseData['requirements'],
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach category
            $course->categories()->attach($category->id);

            // Create videos
            foreach ($courseData['videos'] as $videoIndex => $videoData) {
                $this->createVideo($course, $videoData, $videoIndex, $sampleYoutubeVideos);
            }

            $this->command->info("Created course: {$courseData['title']} with " . count($courseData['videos']) . " videos");
        }

        $this->command->info('Course seeding completed successfully!');
    }

    private function downloadAndStoreCourseImage($courseTitle, $index): ?string
    {
        try {
            $imageUrl = "https://picsum.photos/800/600?random=" . rand(100, 999);
            $response = Http::timeout(30)->get($imageUrl);
            if ($response->successful()) {
                $fileName = 'courses/' . Str::ulid() . '.jpg';
                Storage::disk('public')->put($fileName, $response->body());
                return Storage::disk('public')->url($fileName);
            }
        } catch (\Exception $e) {
            $this->command->warn("Failed to download course image for: $courseTitle");
        }

        return null;
    }

    private function createVideo($course, $videoData, $index, $sampleYoutubeVideos)
    {
        try {
            $videoUrl = $sampleYoutubeVideos[array_rand($sampleYoutubeVideos)];
            $thumbnailPath = "https://picsum.photos/320/180?random=" . rand(1, 1000);

            Video::create([
                'course_id' => $course->id,
                'title' => $videoData['title'],
                'description' => $videoData['description'],
                'video_url' => $videoUrl,
                'thumbnail_url' => $thumbnailPath,
                'duration_in_seconds' => $videoData['duration_in_seconds'],
                'sort_order' => $index,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } catch (\Exception $e) {
            $this->command->warn("Failed to create video: {$videoData['title']} - " . $e->getMessage());
        }
    }
}
