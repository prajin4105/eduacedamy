<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@eduacademy.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@eduacademy.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Instructor Users
        User::create([
            'name' => 'John Smith',
            'email' => 'john.smith@eduacademy.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@eduacademy.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Michael Brown',
            'email' => 'michael.brown@eduacademy.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([  
            'name' => 'Emily Davis',
            'email' => 'emily.davis@eduacademy.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        // Student Users
        User::create([
            'name' => 'Alice Wilson',
            'email' => 'alice.wilson@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Martinez',
            'email' => 'bob.martinez@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Carol Thompson',
            'email' => 'carol.thompson@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'David Garcia',
            'email' => 'david.garcia@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Eva Rodriguez',
            'email' => 'eva.rodriguez@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Frank Lee',
            'email' => 'frank.lee@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Grace Kim',
            'email' => 'grace.kim@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Henry Chen',
            'email' => 'henry.chen@student.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Additional random students using factory
        User::factory(20)->create([
            'role' => 'student',
        ]);

        // Additional random instructors using factory
        User::factory(5)->create([
            'role' => 'instructor',
        ]);
    }
}
