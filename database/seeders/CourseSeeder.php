<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert courses into the database
        DB::table('courses')->insert([
            [
                'instructor_id' => 1, // Replace with valid instructor_id
                'title' => 'Introduction to Laravel',
                'slug' => Str::slug('Introduction to Laravel'),
                'description' => 'Learn the basics of Laravel framework and build your first web application.',
                'price' => 99.99,
                'level' => 'beginner',
                'duration_in_minutes' => 120,
                'what_you_will_learn' => 'Basic computer skills, Laravel framework basics',
                'requirements' => 'Basic computer skills, Internet connection',
                'image' => 'https://via.placeholder.com/640x360',
                'is_published' => 1,
                'published_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'instructor_id' => 1, // Replace with valid instructor_id
                'title' => 'Advanced Laravel Techniques',
                'slug' => Str::slug('Advanced Laravel Techniques'),
                'description' => 'Deep dive into advanced features of the Laravel framework.',
                'price' => 149.99,
                'level' => 'advanced',
                'duration_in_minutes' => 180,
                'what_you_will_learn' => 'Laravel advanced features, Design patterns, Testing',
                'requirements' => 'Intermediate knowledge of Laravel, Basic computer skills',
                'image' => 'https://via.placeholder.com/640x360',
                'is_published' => 1,
                'published_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ],
            // Add more courses as needed
        ]);
    }
}
