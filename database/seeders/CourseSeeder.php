<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    private array $courseTemplates = [
        // Web Development
        [
            'templates' => [
                'Complete [Language] Bootcamp',
                '[Language] from Zero to Hero',
                'Mastering [Language]',
                'The Complete [Language] Course',
                '[Language] for Beginners',
                'Advanced [Language] Concepts',
            ],
            'languages' => ['JavaScript', 'Python', 'PHP', 'Ruby', 'Java', 'C#', 'Go', 'TypeScript', 'Swift', 'Kotlin'],
            'descriptions' => [
                'Learn [Language] from scratch and build real-world applications. This comprehensive course covers everything from the basics to advanced concepts.',
                'Become a [Language] developer with this hands-on course. Build projects, solve problems, and master the language.',
                'From absolute beginner to advanced, this course will take you through all the essential [Language] concepts with practical examples.'
            ],
            'levels' => ['beginner', 'intermediate', 'advanced'],
            'durations' => [120, 180, 240, 300, 360, 420, 480, 540, 600],
            'prices' => [9.99, 12.99, 14.99, 19.99, 24.99, 29.99, 34.99, 39.99, 49.99, 59.99, 74.99, 99.99, 129.99, 149.99, 199.99],
        ],
        // Data Science
        [
            'templates' => [
                'Data Science with [Tool]',
                'Machine Learning with [Tool]',
                'AI and [Tool] Masterclass',
                'Data Analysis with [Tool]',
                'Deep Learning with [Tool]',
            ],
            'languages' => ['Python', 'R', 'TensorFlow', 'PyTorch', 'Keras', 'Pandas', 'NumPy', 'Scikit-learn'],
            'descriptions' => [
                'Master data science and machine learning with [Tool]. Learn to analyze data, build models, and make predictions.',
                'This comprehensive course will teach you how to use [Tool] for data analysis, visualization, and machine learning.',
                'From data preprocessing to model deployment, learn the complete data science workflow using [Tool].'
            ],
            'levels' => ['intermediate', 'advanced'],
            'durations' => [300, 360, 420, 480, 540, 600, 720],
            'prices' => [29.99, 39.99, 49.99, 59.99, 79.99, 99.99, 129.99, 149.99],
        ],
        // Web Design
        [
            'templates' => [
                'Complete [Tool] Course',
                'UI/UX Design with [Tool]',
                'Web Design Masterclass: [Tool]',
                'Responsive Design with [Tool]',
            ],
            'languages' => ['Figma', 'Adobe XD', 'Sketch', 'Photoshop', 'Illustrator', 'Webflow', 'WordPress', 'Elementor'],
            'descriptions' => [
                'Learn to design beautiful websites and user interfaces with [Tool]. This course covers everything from basic principles to advanced techniques.',
                'Master [Tool] and create stunning web designs. Learn about typography, color theory, layout, and more.',
                'From wireframing to prototyping, this course will teach you how to design professional websites using [Tool].'
            ],
            'levels' => ['beginner', 'intermediate', 'advanced'],
            'durations' => [120, 180, 240, 300, 360, 420],
            'prices' => [19.99, 24.99, 29.99, 34.99, 39.99, 49.99, 59.99, 79.99],
        ],
        // Business
        [
            'templates' => [
                '[Topic] for Beginners',
                'Mastering [Topic]',
                'The Complete Guide to [Topic]',
                '[Topic] Fundamentals',
                'Advanced [Topic] Techniques',
            ],
            'languages' => ['Digital Marketing', 'Social Media Marketing', 'Content Marketing', 'SEO', 'Email Marketing', 'Business Strategy', 'Project Management', 'Leadership'],
            'descriptions' => [
                'Learn the essential skills and strategies for successful [Topic]. This course covers everything you need to know to get started.',
                'Master the art of [Topic] with this comprehensive course. Learn from industry experts and gain practical skills.',
                'From basic concepts to advanced strategies, this course will help you become an expert in [Topic].'
            ],
            'levels' => ['beginner', 'intermediate', 'advanced'],
            'durations' => [60, 120, 180, 240, 300],
            'prices' => [9.99, 14.99, 19.99, 24.99, 29.99, 39.99, 49.99],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructors = User::where('role', 'instructor')->get();
        $totalCourses = 200;
        
        for ($i = 0; $i < $totalCourses; $i++) {
            $template = $this->courseTemplates[array_rand($this->courseTemplates)];
            $language = $template['languages'][array_rand($template['languages'])];
            $titleTemplate = $template['templates'][array_rand($template['templates'])];
            $title = str_replace('[Language]', $language, $titleTemplate);
            $title = str_replace('[Tool]', $language, $title);
            $title = str_replace('[Topic]', $language, $title);
            
            $description = str_replace(
                ['[Language]', '[Tool]', '[Topic]'], 
                [$language, $language, $language], 
                $template['descriptions'][array_rand($template['descriptions'])]
            );
            
            $slug = Str::slug($title) . '-' . Str::random(6);
            $level = $template['levels'][array_rand($template['levels'])];
            $duration = $template['durations'][array_rand($template['durations'])];
            $price = $template['prices'][array_rand($template['prices'])];
            $instructor = $instructors->random();
            $isPublished = rand(0, 10) > 1; // 90% chance of being published
            $publishedAt = $isPublished ? now()->subDays(rand(1, 180)) : null;
            
            $course = Course::create([
                'instructor_id' => $instructor->id,
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'price' => $price,
                'image' => 'courses/' . Str::random(10) . '.jpg',
                'is_published' => $isPublished,
                'published_at' => $publishedAt,
                'what_you_will_learn' => $this->generateLearningOutcomes($language, $level),
                'requirements' => $this->generateRequirements($level),
                'level' => $level,
                'language' => $this->getRandomLanguage(),
                'duration_in_minutes' => $duration,
                'created_at' => now()->subDays(rand(0, 365))->subHours(rand(0, 23)),
                'updated_at' => now(),
            ]);
            
            // Randomly set some courses as free (about 10%)
            if (rand(1, 10) === 1) {
                $course->price = 0.00;
                $course->save();
            }
        }
    }
    
    private function generateLearningOutcomes(string $topic, string $level): string
    {
        $outcomes = [];
        $count = rand(4, 8);
        
        $baseOutcomes = [
            "Understand the fundamental concepts of {$topic}",
            "Learn how to apply {$topic} in real-world scenarios",
            "Master the essential tools and techniques for {$topic}",
            "Develop practical skills in {$topic}",
            "Gain hands-on experience with {$topic}",
            "Learn best practices for {$topic}",
            "Build projects using {$topic}",
            "Troubleshoot common issues in {$topic}",
        ];
        
        if ($level === 'intermediate' || $level === 'advanced') {
            $baseOutcomes = array_merge($baseOutcomes, [
                "Implement advanced techniques in {$topic}",
                "Optimize performance for {$topic} applications",
                "Design scalable solutions using {$topic}",
                "Master complex concepts in {$topic}",
                "Develop production-ready applications with {$topic}",
            ]);
        }
        
        // Shuffle and pick $count unique outcomes
        shuffle($baseOutcomes);
        $selectedOutcomes = array_slice($baseOutcomes, 0, $count);
        
        return implode("\n", array_map(fn($outcome) => "• $outcome", $selectedOutcomes));
    }
    
    private function generateRequirements(string $level): string
    {
        $requirements = [
            'Basic computer skills',
            'A computer with internet connection',
            'Willingness to learn',
            'No prior experience required',
            'Basic understanding of programming concepts',
            'A text editor',
        ];
        
        if ($level === 'intermediate') {
            $requirements = array_merge($requirements, [
                'Basic knowledge of programming',
                'Familiarity with basic concepts',
                'Some experience with similar tools',
                'A GitHub account',
            ]);
        } elseif ($level === 'advanced') {
            $requirements = array_merge($requirements, [
                'Strong programming background',
                'Experience with similar technologies',
                'Understanding of core concepts',
                'Development environment set up',
            ]);
        }
        
        shuffle($requirements);
        $count = rand(3, 6);
        $selectedRequirements = array_slice($requirements, 0, $count);
        
        return implode("\n", array_map(fn($req) => "• $req", $selectedRequirements));
    }
    
    private function getRandomLanguage(): string
    {
        $languages = [
            'English', 'Spanish', 'French', 'German', 'Portuguese', 'Russian', 
            'Japanese', 'Chinese', 'Hindi', 'Arabic', 'Italian', 'Dutch',
            'Korean', 'Turkish', 'Polish', 'Ukrainian', 'Romanian', 'Greek',
            'Swedish', 'Norwegian', 'Finnish', 'Danish', 'Czech', 'Hungarian'
        ];
        
        return $languages[array_rand($languages)];
    }
}
