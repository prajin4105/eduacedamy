<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'description' => 'Perfect for beginners who want to explore our platform',
                'price' => 9.99,
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'Access to basic courses',
                    'Community support',
                    'Email support',
                    '1 active course at a time',
                    'Basic analytics',
                ],
            ],
            [
                'name' => 'Pro',
                'description' => 'For serious learners who want to advance their skills',
                'price' => 29.99,
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'Access to all courses',
                    'Priority support',
                    '5 active courses at a time',
                    'Downloadable resources',
                    'Advanced analytics',
                    'Offline access',
                ],
            ],
            [
                'name' => 'Enterprise',
                'description' => 'For organizations and power users',
                'price' => 99.99,
                'interval' => 'month',
                'interval_count' => 1,
                'features' => [
                    'Unlimited course access',
                    '24/7 priority support',
                    'Unlimited active courses',
                    'All Pro features',
                    'Team management',
                    'Custom learning paths',
                    'API access',
                    'Dedicated account manager',
                ],
            ],
        ];

        foreach ($plans as $planData) {
            $slug = Str::slug($planData['name']);
            $description = $planData['description'] . '\n\n' . implode('\n• ', $planData['features']);
            
            Plan::create([
                'name' => $planData['name'],
                'slug' => $slug,
                'description' => $description,
                'price' => $planData['price'],
                'currency' => 'USD',
                'interval' => $planData['interval'],
                'interval_count' => $planData['interval_count'],
                'is_active' => true,
                'created_at' => now()->subYear(),
                'updated_at' => now(),
            ]);
        }
    }
}
