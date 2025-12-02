<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Programming & Development
            'Web Development', 'Mobile Development', 'Game Development', 'Programming Languages',
            'Software Testing', 'Software Engineering', 'Development Tools', 'No-Code Development',
            
            // Business
            'Entrepreneurship', 'Communication', 'Management', 'Sales', 'Business Strategy',
            'Operations', 'Business Law', 'Data & Analytics', 'Home Business', 'E-Commerce',
            
            // Design
            'Web Design', 'Graphic Design', 'Design Tools', 'User Experience', 'Game Design',
            '3D & Animation', 'Fashion Design', 'Architectural Design', 'Interior Design',
            
            // Marketing
            'Digital Marketing', 'Search Engine Optimization', 'Social Media Marketing',
            'Branding', 'Marketing Fundamentals', 'Marketing Analytics', 'Public Relations',
            'Advertising', 'Content Marketing', 'Product Marketing',
            
            // IT & Software
            'IT Certification', 'Network & Security', 'Hardware', 'Operating Systems',
            'Other IT & Software', 'Microsoft', 'Apple', 'Google', 'SAP', 'Oracle',
            
            // Personal Development
            'Personal Transformation', 'Productivity', 'Leadership', 'Personal Finance',
            'Career Development', 'Parenting & Relationships', 'Happiness', 'Religion & Spirituality',
            'Personal Brand Building', 'Creativity', 'Influence', 'Self Esteem', 'Stress Management',
            
            // Health & Fitness
            'Fitness', 'General Health', 'Sports', 'Nutrition', 'Yoga', 'Mental Health',
            'Dieting', 'Self Defense', 'Safety & First Aid', 'Meditation', 'Other Health & Fitness',
            
            // Music
            'Instruments', 'Music Production', 'Music Fundamentals', 'Vocal', 'Music Techniques',
            'Music Software', 'Other Music',
            
            // Teaching & Academics
            'Engineering', 'Humanities', 'Math', 'Science', 'Social Science', 'Language Learning',
            'Teacher Training', 'Test Prep', 'Other Teaching & Academics'
        ];

        // Shuffle and take 30 unique categories
        shuffle($categories);
        $selectedCategories = array_slice(array_unique($categories), 0, 30);

        foreach ($selectedCategories as $category) {
            $slug = Str::slug($category);
            $description = $this->generateCategoryDescription($category);
            
            Category::create([
                'name' => $category,
                'slug' => $slug,
                'description' => $description,
                'image' => 'categories/' . $slug . '.jpg',
                'is_active' => true,
                'sort_order' => rand(1, 100),
                'created_at' => now()->subMonths(rand(1, 12)),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateCategoryDescription(string $category): string
    {
        $templates = [
            "Master the art of {$category} with our comprehensive courses designed for all skill levels.",
            "Learn {$category} from industry experts and take your skills to the next level.",
            "Discover the secrets of {$category} through hands-on projects and real-world applications.",
            "Transform your career with our in-depth {$category} training programs.",
            "From beginner to advanced, our {$category} courses cover everything you need to know.",
            "Join thousands of students learning {$category} with our expert-led courses.",
            "Unlock your potential with our specialized {$category} training and certification.",
        ];

        return $templates[array_rand($templates)];
    }
}
