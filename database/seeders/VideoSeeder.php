<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoSeeder extends Seeder
{
    private array $videoTemplates = [
        // Introduction videos
        [
            'templates' => [
                'Introduction to [Topic]',
                'Welcome to [Course]',
                'Getting Started with [Topic]',
                '[Topic] Overview',
                'What You Will Learn in This Course',
            ],
            'durations' => [120, 180, 240, 300],
            'sort_order' => 1,
        ],
        // Theory/concept videos
        [
            'templates' => [
                'Understanding [Concept]',
                '[Concept] Explained',
                'The Fundamentals of [Concept]',
                'Introduction to [Concept]',
                'What is [Concept]?',
                'The Theory Behind [Concept]',
            ],
            'durations' => [180, 240, 300, 360, 420, 480, 540, 600],
            'sort_order' => 'increment',
        ],
        // Practical/hands-on videos
        [
            'templates' => [
                'Hands-on: [Task]',
                'Building [Project]',
                'Creating [Element]',
                'Implementing [Feature]',
                'Step-by-Step: [Task]',
                'Project: [Project Name]',
                'Tutorial: [Task]',
            ],
            'durations' => [300, 360, 420, 480, 540, 600, 720, 900, 1200],
            'sort_order' => 'increment',
        ],
        // Advanced topics
        [
            'templates' => [
                'Advanced [Topic] Techniques',
                'Mastering [Concept]',
                'Optimizing [Feature]',
                'Best Practices for [Topic]',
                'Advanced Strategies for [Topic]',
                'Deep Dive: [Topic]',
            ],
            'durations' => [300, 360, 420, 480, 540, 600, 720],
            'sort_order' => 'increment',
        ],
        // Conclusion/summary videos
        [
            'templates' => [
                'Course Summary',
                'What We\'ve Learned',
                'Next Steps',
                'Where to Go From Here',
                'Congratulations!',
                'Final Thoughts',
            ],
            'durations' => [120, 180, 240, 300],
            'sort_order' => 999,
        ],
    ];

    private array $concepts = [
        'Variables', 'Functions', 'Loops', 'Conditionals', 'Objects', 'Arrays', 'APIs', 'Databases',
        'Authentication', 'State Management', 'Responsive Design', 'Algorithms', 'Data Structures',
        'Asynchronous Programming', 'RESTful Services', 'GraphQL', 'Testing', 'Deployment',
        'Performance Optimization', 'Security Best Practices', 'Version Control', 'Debugging',
        'Design Patterns', 'Cloud Computing', 'Containers', 'Microservices', 'CI/CD', 'DevOps'
    ];

    private array $tasks = [
        'a To-Do App', 'a Blog', 'an E-commerce Site', 'a Portfolio Website', 'a REST API',
        'a Mobile App', 'a Dashboard', 'a Chat Application', 'a Game', 'a Social Media Platform',
        'a Weather App', 'a Recipe App', 'a Task Manager', 'a Note-taking App', 'a Quiz App'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::with('videos')->get();

        foreach ($courses as $course) {
            $videoCount = rand(5, 15); // 5-15 videos per course
            $sortOrder = 1;

            // Skip if course already has videos
            if ($course->videos->count() > 0) {
                continue;
            }

            // Add introduction video
            $this->createVideo($course, $this->videoTemplates[0], $sortOrder++);

            // Add main content videos
            for ($i = 1; $i < $videoCount - 1; $i++) {
                $templateType = $this->getWeightedRandomTemplateType($i, $videoCount);
                $this->createVideo($course, $this->videoTemplates[$templateType], $sortOrder++);
            }

            // Add conclusion video
            $this->createVideo($course, end($this->videoTemplates), $sortOrder);
        }
    }

    private function createVideo(Course $course, array $template, int $sortOrder): void
    {
        $titleTemplate = $template['templates'][array_rand($template['templates'])];
        $duration = $template['durations'][array_rand($template['durations'])];

        // Replace placeholders in the title
        $title = str_replace(
            ['[Topic]', '[Course]', '[Concept]', '[Task]', '[Project]', '[Feature]', '[Element]'],
            [
                $course->title,
                $course->title,
                $this->concepts[array_rand($this->concepts)],
                $this->tasks[array_rand($this->tasks)],
                $this->tasks[array_rand($this->tasks)],
                $this->concepts[array_rand($this->concepts)],
                $this->concepts[array_rand($this->concepts)],
            ],
            $titleTemplate
        );

        // Generate a unique slug
        $slug = Str::slug($title) . '-' . Str::random(6);

        // Create the video
        Video::create([
            'course_id' => $course->id,
            'title' => $title,
            // 'slug' => $slug,
            'description' => $this->generateVideoDescription($title, $course->title),
            'duration_seconds' => $duration,
            'video_url' => 'videos/' . $course->id . '/' . Str::random(10) . '.mp4',
            'thumbnail_url' => 'thumbnails/' . $course->id . '/' . Str::random(10) . '.jpg',
            'is_published' => true,
            // 'is_preview' => $sortOrder === 1, // First video is always a preview
            'sort_order' => $sortOrder,
            'created_at' => $course->created_at->copy()->addHours($sortOrder),
            'updated_at' => now(),
        ]);
    }

    private function getWeightedRandomTemplateType(int $currentIndex, int $totalVideos): int
    {
        // Adjust weights based on position in the course
        $position = $currentIndex / $totalVideos;

        if ($position < 0.2) {
            // Early in the course: more theory and basics
            $weights = [30, 50, 15, 5];
        } elseif ($position < 0.8) {
            // Middle of the course: balanced mix
            $weights = [10, 30, 50, 10];
        } else {
            // End of the course: more advanced topics
            $weights = [5, 20, 50, 25];
        }

        $rand = mt_rand(1, array_sum($weights));
        $total = 0;

        // Skip the first template type (introduction)
        for ($i = 1; $i <= 4; $i++) {
            $total += $weights[$i - 1];
            if ($rand <= $total) {
                return $i;
            }
        }

        return 2; // Default to theory/concept videos
    }

    private function generateVideoDescription(string $title, string $courseTitle): string
    {
        $descriptions = [
            "In this video, we'll explore {$title}. This is part of our comprehensive course on {$courseTitle}.",
            "Welcome to {$title}. In this lesson, we'll dive deep into the concepts and techniques you need to know.",
            "Learn everything you need to know about {$title} in this comprehensive tutorial from our {$courseTitle} course.",
            "{$title} is a crucial concept in {$courseTitle}. In this video, we'll break it down step by step.",
            "Join us as we explore {$title} in this engaging lesson from our {$courseTitle} course.",
            "Master {$title} with this in-depth tutorial. Perfect for students of our {$courseTitle} course.",
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
