<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Course;

class PlanAndCourseLinkSeeder extends Seeder
{
    public function run(): void
    {
        // Seed five plans
        $plansData = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Access to a curated set of beginner courses.',
                'price' => 9.99,
                'currency' => 'USD',
                'interval' => 'month',
                'interval_count' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Access to most courses across categories.',
                'price' => 19.99,
                'currency' => 'USD',
                'interval' => 'month',
                'interval_count' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Access to all courses including advanced series.',
                'price' => 29.99,
                'currency' => 'USD',
                'interval' => 'month',
                'interval_count' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Pro Annual',
                'slug' => 'pro-annual',
                'description' => 'All-access annual plan with a discount.',
                'price' => 199.00,
                'currency' => 'USD',
                'interval' => 'year',
                'interval_count' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Team',
                'slug' => 'team',
                'description' => 'Team plan suitable for small groups and startups.',
                'price' => 79.00,
                'currency' => 'USD',
                'interval' => 'month',
                'interval_count' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($plansData as $planData) {
            Plan::firstOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }

        // Link courses to plans in the course_plan pivot
        $courseIds = Course::where('is_published', true)->pluck('id')->all();
        if (empty($courseIds)) {
            return; // no courses yet
        }

        $basic = Plan::where('slug', 'basic')->first();
        $standard = Plan::where('slug', 'standard')->first();
        $premium = Plan::where('slug', 'premium')->first();
        $proAnnual = Plan::where('slug', 'pro-annual')->first();
        $team = Plan::where('slug', 'team')->first();

        $total = count($courseIds);
        $first20 = array_slice($courseIds, 0, max(1, (int) floor($total * 0.2)));
        $first60 = array_slice($courseIds, 0, max(1, (int) floor($total * 0.6)));
        $midStart = max(0, (int) floor($total * 0.2));
        $midLen = max(1, (int) floor($total * 0.6));
        $midRange = array_slice($courseIds, $midStart, $midLen);

        if ($basic) {
            $basic->courses()->syncWithoutDetaching($first20);
        }
        if ($standard) {
            $standard->courses()->syncWithoutDetaching($first60);
        }
        if ($premium) {
            $premium->courses()->syncWithoutDetaching($courseIds);
        }
        if ($proAnnual) {
            $proAnnual->courses()->syncWithoutDetaching($courseIds);
        }
        if ($team) {
            $team->courses()->syncWithoutDetaching($midRange);
        }
    }
}


