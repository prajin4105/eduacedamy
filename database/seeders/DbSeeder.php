<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DbSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ([
            'users', 'categories', 'plans', 'courses',
            'videos', 'tests', 'reviews', 'course_category', 'course_plan'
        ] as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ---- USERS ----
        $admins = [];
        $instructors = [];
        $students = [];

        for ($i = 1; $i <= 5; $i++) {
            $admins[] = DB::table('users')->insertGetId([
                'name' => "Admin {$i}",
                'email' => "admin{$i}@eduacademy.test",
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            $instructors[] = DB::table('users')->insertGetId([
                'name' => "Instructor {$i}",
                'email' => "instructor{$i}@eduacademy.test",
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 50; $i++) {
            $students[] = DB::table('users')->insertGetId([
                'name' => $faker->name(),
                'email' => "student{$i}@eduacademy.test",
                'password' => Hash::make('password'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---- CATEGORIES ----
        $categories = [];
        for ($i = 1; $i <= 8; $i++) {
            $categories[] = DB::table('categories')->insertGetId([
                'name' => ucfirst($faker->unique()->word()),
                'slug' => Str::slug($faker->unique()->word()),
                'description' => $faker->sentence(),
                'image' => "categories/{$i}.png",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---- PLANS ----
        $plans = [
            ['Basic', 'basic', 'Access to basic courses', 499, 'INR', 'month', 1],
            ['Pro', 'pro', 'Extended course library', 999, 'INR', 'month', 3],
            ['Premium', 'premium', 'Full access & priority support', 1999, 'INR', 'year', 1],
        ];

        $planIds = [];
        foreach ($plans as [$name, $slug, $desc, $price, $currency, $interval, $count]) {
            $planIds[] = DB::table('plans')->insertGetId([
                'name' => $name,
                'slug' => $slug,
                'description' => $desc,
                'price' => $price,
                'currency' => $currency,
                'interval' => $interval,
                'interval_count' => $count,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---- COURSES ----
        $courses = [];
        for ($i = 1; $i <= 20; $i++) {
            $instructor = $faker->randomElement($instructors);
            $courses[] = DB::table('courses')->insertGetId([
                'instructor_id' => $instructor,
                'title' => "Course $i: " . ucfirst($faker->word()),
                'slug' => "course-$i-" . Str::random(5),
                'description' => $faker->paragraph(),
                'price' => $faker->randomFloat(2, 99, 1999),
                'image' => "courses/" . (($i % 15) + 1) . ".png",
                'is_published' => 1,
                'published_at' => now(),
                'level' => $faker->randomElement(['beginner', 'intermediate', 'advanced']),
                'language' => 'english',
                'duration_in_minutes' => rand(60, 480),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---- COURSE CATEGORIES & PLANS ----
        foreach ($courses as $courseId) {
            DB::table('course_category')->insert([
                'course_id' => $courseId,
                'category_id' => $faker->randomElement($categories),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('course_plan')->insert([
                'course_id' => $courseId,
                'plan_id' => $faker->randomElement($planIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---- VIDEOS (5 per course) ----
        $videoCount = 1;
        foreach ($courses as $courseId) {
            for ($v = 1; $v <= 5; $v++) {
                DB::table('videos')->insert([
                    'course_id' => $courseId,
                    'title' => "Video {$v}",
                    'description' => $faker->sentence(),
                    'video_url' => "videos/{$videoCount}.mp4",
                    'thumbnail_url' => "video-thumbnails/{$videoCount}.jpg",
                    'duration_seconds' => rand(120, 900),
                    'sort_order' => $v,
                    'is_published' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $videoCount = ($videoCount % 15) + 1;
            }

            // ---- TEST ----
            $testId = DB::table('tests')->insertGetId([
                'course_id' => $courseId,
                'title' => "Test for Course #$courseId",
                'description' => $faker->sentence(),
                'passing_score' => rand(60, 80),
                'max_attempts' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add 5 questions for each test
            for ($q = 1; $q <= 5; $q++) {
                DB::table('test_questions')->insert([
                    'test_id' => $testId,
                    'question_text' => $faker->sentence(8),
                    'option_a' => $faker->word(),
                    'option_b' => $faker->word(),
                    'option_c' => $faker->word(),
                    'option_d' => $faker->word(),
                    'correct_option' => $faker->randomElement(['a', 'b', 'c', 'd']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ---- REVIEWS ----
        $studentIds = DB::table('users')->where('role', 'student')->pluck('id')->toArray();

        foreach ($courses as $courseId) {
            foreach (array_slice($faker->randomElements($studentIds, rand(5, 15)), 0, 10) as $studentId) {
                DB::table('reviews')->insert([
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'rating' => rand(1, 5),
                    'comment' => $faker->sentence(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        echo "✅ EduAcademy database seeded successfully.\n";
    }
}
