<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = Enrollment::with(['user', 'course'])->get();
        $now = now();
        
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $user = $enrollment->user;
            $videos = Video::where('course_id', $course->id)->orderBy('sort_order')->get();
            
            if ($videos->isEmpty()) {
                continue;
            }
            
            $totalVideos = $videos->count();
            $videosCompleted = 0;
            $timeSpentSeconds = 0;
            $videoProgress = [];
            $lastWatchedAt = null;
            $isCompleted = $enrollment->status === 'completed';
            $completedAt = $isCompleted ? $enrollment->completed_at : null;
            
            // Calculate progress based on enrollment status
            if ($isCompleted) {
                // For completed courses, mark all videos as watched
                $videosCompleted = $totalVideos;
                $progressPercentage = 100;
                $timeSpentSeconds = $this->calculateTotalCourseDuration($videos);
                $videoProgress = $this->generateCompletedVideoProgress($videos, $enrollment->enrolled_at, $completedAt);
                $lastWatchedAt = $completedAt;
            } else {
                // For in-progress courses, random progress
                $videosCompleted = min(
                    (int)($totalVideos * ($enrollment->progress_percentage / 100)),
                    $totalVideos - 1 // Don't mark as completed unless enrollment is completed
                );
                
                $progressPercentage = $enrollment->progress_percentage;
                $videoProgress = $this->generatePartialVideoProgress($videos, $videosCompleted, $enrollment->enrolled_at);
                
                // Calculate time spent based on watched videos and partial progress
                $timeSpentSeconds = $this->calculateTimeSpent($videoProgress);
                $lastWatchedAt = $this->getLastWatchedAt($videoProgress, $enrollment->last_accessed_at);
            }
            
            // Create or update course progress
            CourseProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'video_id' => $this->getCurrentVideoId($videos, $videosCompleted, $totalVideos),
                    'progress_percentage' => $progressPercentage,
                    'videos_completed' => $videosCompleted,
                    'total_videos' => $totalVideos,
                    'time_spent_seconds' => $timeSpentSeconds,
                    'last_watched_at' => $lastWatchedAt,
                    'completed_at' => $completedAt,
                    'is_completed' => $isCompleted,
                    'video_progress' => json_encode($videoProgress),
                    'created_at' => $enrollment->enrolled_at,
                    'updated_at' => $now,
                ]
            );
        }
    }
    
    private function calculateTotalCourseDuration($videos): int
    {
        return $videos->sum('duration_in_seconds');
    }
    
    private function generateCompletedVideoProgress($videos, Carbon $startDate, Carbon $endDate): array
    {
        $videoProgress = [];
        $currentDate = $startDate->copy();
        $totalVideos = $videos->count();
        $totalDuration = $endDate->diffInSeconds($startDate);
        
        // Distribute video watches over the enrollment period
        $timePerVideo = $totalDuration / $totalVideos;
        
        foreach ($videos as $index => $video) {
            $watchDate = $currentDate->copy()->addSeconds(rand(0, (int)$timePerVideo));
            $watchDuration = $video->duration_in_seconds;
            
            $videoProgress[] = [
                'video_id' => $video->id,
                'watched_duration' => $watchDuration,
                'is_completed' => true,
                'last_watched_at' => $watchDate->toDateTimeString(),
                'created_at' => $watchDate->toDateTimeString(),
                'updated_at' => $watchDate->toDateTimeString(),
            ];
            
            $currentDate->addSeconds($timePerVideo);
        }
        
        return $videoProgress;
    }
    
    private function generatePartialVideoProgress($videos, int $videosCompleted, Carbon $startDate): array
    {
        $videoProgress = [];
        $currentDate = $startDate->copy();
        
        // Mark completed videos
        for ($i = 0; $i < $videosCompleted; $i++) {
            $video = $videos[$i];
            $watchDate = $currentDate->copy()->addHours(rand(1, 24 * 7)); // Watch within a week
            
            $videoProgress[] = [
                'video_id' => $video->id,
                'watched_duration' => $video->duration_in_seconds,
                'is_completed' => true,
                'last_watched_at' => $watchDate->toDateTimeString(),
                'created_at' => $watchDate->toDateTimeString(),
                'updated_at' => $watchDate->toDateTimeString(),
            ];
            
            $currentDate = $watchDate;
        }
        
        // Add partial progress for current video if not all videos are completed
        if ($videosCompleted < $videos->count()) {
            $currentVideo = $videos[$videosCompleted];
            $watchDate = $currentDate->copy()->addHours(rand(1, 24 * 7));
            $watchedDuration = rand(1, (int)($currentVideo->duration_in_seconds * 0.9)); // Up to 90% watched
            
            $videoProgress[] = [
                'video_id' => $currentVideo->id,
                'watched_duration' => $watchedDuration,
                'is_completed' => false,
                'last_watched_at' => $watchDate->toDateTimeString(),
                'created_at' => $watchDate->toDateTimeString(),
                'updated_at' => $watchDate->toDateTimeString(),
            ];
        }
        
        return $videoProgress;
    }
    
    private function calculateTimeSpent(array $videoProgress): int
    {
        return array_reduce($videoProgress, function ($total, $progress) {
            return $total + $progress['watched_duration'];
        }, 0);
    }
    
    private function getLastWatchedAt(array $videoProgress, ?Carbon $default = null): ?Carbon
    {
        if (empty($videoProgress)) {
            return $default;
        }
        
        $latest = collect($videoProgress)->sortByDesc('last_watched_at')->first();
        return Carbon::parse($latest['last_watched_at']);
    }
    
    private function getCurrentVideoId($videos, int $videosCompleted, int $totalVideos): ?int
    {
        if ($videosCompleted >= $totalVideos) {
            return $videos->last()->id;
        }
        
        return $videos[$videosCompleted]->id;
    }
}
