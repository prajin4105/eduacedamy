<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Services\CertificateService;

class CourseProgress extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'video_id',
        'progress_percentage',
        'videos_completed',
        'total_videos',
        'time_spent_seconds',
        'last_watched_at',
        'completed_at',
        'is_completed',
        'video_progress',
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'video_progress' => 'array',
    ];


public static function createForUserCourse($user, $course)
{
    return self::firstOrCreate(
        [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ],
        [
            'total_videos' => $course->videos()->count(), // ✅ set total_videos
            'progress_percentage' => 0,
            'videos_completed' => 0,
            'time_spent_seconds' => 0,
        ]
    );
}

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Calculate progress percentage based on completed videos
     */
    public function calculateProgressPercentage(): int
    {
        if ($this->total_videos === 0) {
            return 0;
        }

        return (int) round(($this->videos_completed / $this->total_videos) * 100);
    }

    /**
     * Update progress when a video is completed
     */
    public function markVideoCompleted(int $videoId): void
    {
        $videoProgress = $this->video_progress ?? [];

        if (!in_array($videoId, $videoProgress)) {
            $videoProgress[] = $videoId;
            $this->video_progress = $videoProgress;
            $this->videos_completed = count($videoProgress);
            $this->progress_percentage = $this->calculateProgressPercentage();
            $this->last_watched_at = now();

            // Mark course as completed if all videos are watched
            if ($this->videos_completed >= $this->total_videos) {
                $this->is_completed = true;
                $this->completed_at = now();

                // Check and issue certificate using CertificateService
                // Rule 1: Courses with tests - certificate only issued when test is passed (handled in TestController)
                // Rule 2: Courses without tests - certificate issued when 100% progress and is_completed = 1
                // CertificateService will check if course has test and handle accordingly
                $certificateService = new CertificateService();
                $certificateService->checkAndIssueCertificate($this->user, $this->course);
            }

            $this->save();
        }
    }

    /**
     * Update time spent on course
     */
    public function updateTimeSpent(int $seconds): void
    {
        $this->time_spent_seconds += $seconds;
        $this->last_watched_at = now();
        $this->save();
    }

    /**
     * Get formatted time spent
     */
    protected function formattedTimeSpent(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hours = floor($this->time_spent_seconds / 3600);
                $minutes = floor(($this->time_spent_seconds % 3600) / 60);
                $seconds = $this->time_spent_seconds % 60;

                if ($hours > 0) {
                    return sprintf('%dh %dm', $hours, $minutes);
                } elseif ($minutes > 0) {
                    return sprintf('%dm %ds', $minutes, $seconds);
                } else {
                    return sprintf('%ds', $seconds);
                }
            }
        );
    }

    /**
     * Get progress status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_completed) {
                    return 'completed';
                } elseif ($this->progress_percentage > 0) {
                    return 'in_progress';
                } else {
                    return 'not_started';
                }
            }
        );
    }
}
