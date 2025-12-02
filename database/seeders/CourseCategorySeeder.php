<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
// use App\Models\Category;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        $categories = Category::all();

        foreach ($courses as $course) {
            // Each course can have 1-3 categories
            $categoryCount = rand(1, 3);
            $selectedCategories = $categories->random($categoryCount);

            foreach ($selectedCategories as $category) {
                // Check if this relationship already exists to avoid duplicates
                $exists = Category::where('course_id', $course->id)
                    ->where('category_id', $category->id)
                    ->exists();

                if (!$exists) {
                Category::create([
                        'course_id' => $course->id,
                        'category_id' => $category->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
