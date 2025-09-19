<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Video;

class CourseVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the course by slug
        $course = Course::where('slug', 'advanced-laravel-techniques')->first();

        if (!$course) {
            $this->command->info('Course not found!');
            return;
        }

        // Base video info
        $videoInfo = [
            'title' => 'Introduction to Python Programming',
            'description' => 'Master Python programming for data analysis, visualization, and machine learning.',
            'duration_seconds' => 15,
        ];

        // Loop to create 5 videos
        for ($index = 0; $index < 5; $index++) {
            Video::create([
                'course_id' => $course->id,
                'title' => $videoInfo['title'] . ' - Part ' . ($index + 1),
                'description' => $videoInfo['description'],
                'video_url' => 'videos/01K5DRF28GD5PEVFZZ42T9VHVX.mp4',
                'thumbnail_url' => 'video-thumbnails/01K5DRF2AJJW33DDDWXHF3JSAV.jpg',
                'duration_seconds' => $videoInfo['duration_seconds'],
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }

        $this->command->info('Added 5 videos to course: ' . $course->title);
    }
}
