<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users for all roles
        $this->call(UserSeeder::class);

        // Seed categories
        $this->call(CategorySeeder::class);
        
        // Seed courses with instructors
        $this->call(CourseSeeder::class);
    }
}
