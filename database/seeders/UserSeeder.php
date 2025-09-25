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
    /**
     * Create a user if it doesn't already exist.
     *
     * @param array $userData
     * @return User
     */
    private function createUserIfNotExists($userData)
    {
        return User::firstOrCreate(
            ['email' => $userData['email']], // Unique identifier
            [
                'name' => $userData['name'],
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'],
                'email_verified_at' => now(),
            ]
        );
    }

    public function run()
    {
        // Admin Users
        $this->createUserIfNotExists([
            'name' => 'Admin User',
            'email' => 'admin@eduacademy.com',
            'password' => 'password',
            'role' => 'admin'
        ]);

        $this->createUserIfNotExists([
            'name' => 'Super Admin',
            'email' => 'superadmin@eduacademy.com',
            'password' => 'password',
            'role' => 'admin'
        ]);

        // Instructor Users
        // $instructors = [
        //     [
        //         'name' => 'John Smith',
        //         'email' => 'john.smith@eduacademy.com',
        //         'password' => 'password',
        //         'role' => 'instructor'
        //     ],
        //     [
        //         'name' => 'Sarah Johnson',
        //         'email' => 'sarah.johnson@eduacademy.com',
        //         'password' => 'password',
        //         'role' => 'instructor'
        //     ],
        //     [
        //         'name' => 'Michael Chen',
        //         'email' => 'michael.chen@eduacademy.com',
        //         'password' => 'password',
        //         'role' => 'instructor'
        //     ],
        //     [
        //         'name' => 'Emily Davis',
        //         'email' => 'emily.davis@eduacademy.com',
        //         'password' => 'password',
        //         'role' => 'instructor'
        //     ],
        //     [
        //         'name' => 'Michael Brown',
        //         'email' => 'michael.brown@eduacademy.com',
        //         'password' => 'password',
        //         'role' => 'instructor'
        //     ],
        //     [
        //         'name' => 'Emma Wilson',
        //         'email' => 'emma.wilson@eduacademy.com',
        //         'password' => 'password',
        //         'role' => 'instructor'
        //     ]
        // ];

        foreach ($instructors as $instructor) {
            $this->createUserIfNotExists($instructor);
        }

        // Student Users
        // $students = [
        //     [
        //         'name' => 'Alice Wilson',
        //         'email' => 'alice.wilson@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'Bob Martinez',
        //         'email' => 'bob.martinez@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'Carol Thompson',
        //         'email' => 'carol.thompson@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'David Garcia',
        //         'email' => 'david.garcia@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'Eva Rodriguez',
        //         'email' => 'eva.rodriguez@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'Frank Lee',
        //         'email' => 'frank.lee@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'Grace Kim',
        //         'email' => 'grace.kim@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ],
        //     [
        //         'name' => 'Henry Chen',
        //         'email' => 'henry.chen@student.com',
        //         'password' => 'password',
        //         'role' => 'student'
        //     ]
        // ];

        foreach ($students as $student) {
            $this->createUserIfNotExists($student);
        }

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
