<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core data
            UserSeeder::class,
            CategorySeeder::class,
            PlanSeeder::class,


            CourseSeeder::class,
            // CategorySeeder::class,



            VideoSeeder::class,
            TestSeeder::class,
            TestQuestionSeeder::class,

            // User interactions
            // EnrollmentSeeder::class,
            // CourseProgressSeeder::class,
            // ReviewSeeder::class,

        ]);
    }
}
