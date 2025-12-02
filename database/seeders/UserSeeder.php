<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 admin users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Admin ' . $i,
                'email' => 'admin' . $i . '@eduacademy.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now()->subMonths(rand(3, 12)),
                'updated_at' => now(),
            ]);
        }

        // Create 20 instructor users
        $instructorNames = [
            'Sarah Johnson', 'Michael Chen', 'David Rodriguez', 'Emily Wilson', 'James Smith',
            'Jennifer Lee', 'Robert Taylor', 'Lisa Brown', 'William Davis', 'Patricia Miller',
            'Christopher Anderson', 'Jessica Thomas', 'Daniel Martinez', 'Amanda White', 'Joseph Jackson',
            'Michelle Harris', 'Thomas Martin', 'Stephanie Thompson', 'Charles Garcia', 'Laura Martinez'
        ];

        foreach ($instructorNames as $name) {
            $firstName = explode(' ', $name)[0];
            $email = strtolower($firstName) . '_' . strtolower(Str::random(4)) . '@eduacademy.test';

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'email_verified_at' => now(),
                'bio' => $this->generateBio($name),
                // 'profile_photo_path' => 'instructors/' . strtolower(str_replace(' ', '-', $name)) . '.jpg',
                'created_at' => now()->subMonths(rand(1, 12)),
                'updated_at' => now(),
            ]);
        }

        // Create 200 student users
        for ($i = 1; $i <= 200; $i++) {
            $firstName = $this->randomFirstName();
            $lastName = $this->randomLastName();
            $email = strtolower($firstName . '.' . $lastName . $i . '@student.eduacademy.test');

            User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('student123'),
                'role' => 'student',
                'email_verified_at' => now(),
                'created_at' => now()->subMonths(rand(0, 6))->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateBio(string $name): string
    {
        $titles = ['Senior Instructor', 'Lead Educator', 'Professor', 'Industry Expert', 'Certified Trainer'];
        $specialties = [
            'Web Development', 'Data Science', 'Mobile App Development', 'UI/UX Design',
            'Digital Marketing', 'Business Analytics', 'Cybersecurity', 'Cloud Computing'
        ];

        $title = $titles[array_rand($titles)];
        $specialty = $specialties[array_rand($specialties)];
        $years = rand(3, 15);

        return "$title specializing in $specialty with over $years years of industry and teaching experience. " .
               $this->randomBioSnippet();
    }

    private function randomBioSnippet(): string
    {
        $snippets = [
            "Passionate about sharing knowledge and helping students achieve their learning goals.",
            "Dedicated to creating engaging and effective learning experiences for students of all levels.",
            "Industry professional bringing real-world experience into the classroom.",
            "Award-winning educator with a focus on practical, hands-on learning.",
            "Committed to student success through innovative teaching methods and personalized instruction."
        ];

        return $snippets[array_rand($snippets)];
    }

    private function randomFirstName(): string
    {
        $firstNames = [
            'James', 'Mary', 'John', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda',
            'William', 'Elizabeth', 'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica',
            'Thomas', 'Sarah', 'Charles', 'Karen', 'Christopher', 'Nancy', 'Daniel', 'Lisa',
            'Matthew', 'Margaret', 'Anthony', 'Betty', 'Donald', 'Sandra', 'Mark', 'Ashley'
        ];

        return $firstNames[array_rand($firstNames)];
    }

    private function randomLastName(): string
    {
        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
            'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
            'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Thompson', 'White',
            'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker', 'Young'
        ];

        return $lastNames[array_rand($lastNames)];
    }
}
