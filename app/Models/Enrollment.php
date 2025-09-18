<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'amount_paid',
        'status',
        'enrolled_at',
        'progress_percentage',
        'last_accessed_at',
        'payment_id',
        'order_id',
        'signature',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'enrolled_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function progress(): BelongsTo
    {
        return $this->belongsTo(CourseProgress::class, 'user_id', 'user_id')
                    ->where('course_id', $this->course_id);
    }

    /**
     * Update enrollment progress
     */
    public function updateProgress(int $progressPercentage): void
    {
        $this->progress_percentage = $progressPercentage;
        $this->last_accessed_at = now();
        $this->save();
    }

    /**
     * Check if course is completed
     */
    public function isCompleted(): bool
    {
        return $this->progress_percentage >= 100;
    }

    /**
     * Get progress status
     */
    public function getProgressStatus(): string
    {
        if ($this->isCompleted()) {
            return 'completed';
        } elseif ($this->progress_percentage > 0) {
            return 'in_progress';
        } else {
            return 'not_started';
        }
    }
}
