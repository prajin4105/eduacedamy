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
    public function run()
    {
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Learn to build modern web applications',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Build apps for iOS and Android',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Data Science',
                'description' => 'Master data analysis and machine learning',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Business',
                'description' => 'Business, finance, and entrepreneurship courses',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Design',
                'description' => 'Graphic design, UI/UX, and more',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Digital marketing, social media, and growth',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Photography',
                'description' => 'Photography and videography courses',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Music',
                'description' => 'Music theory and instrument lessons',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Health & Fitness',
                'description' => 'Stay healthy and fit with our courses',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Language',
                'description' => 'Learn new languages',
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);
            
            // Check if category with this slug already exists
            $existingCategory = Category::where('slug', $slug)->first();
            
            if (!$existingCategory) {
                Category::create([
                    'name' => $category['name'],
                    'slug' => $slug,
                    'description' => $category['description'],
                    'is_active' => $category['is_active'],
                    'sort_order' => $category['sort_order'],
                ]);
            }
        }
    }
}
