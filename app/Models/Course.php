<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'title',
        'slug',
        'description',
        'price',
        'image',
        'is_published',
        'published_at',
        'what_you_will_learn',
        'requirements',
        'level',
        'language',
        'duration_in_minutes',
        'approval_status',
        'approval_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
        'approved_at' => 'datetime',
    ];
    protected $appends = ['image_url'];

     public function getImageUrlAttribute()
    {
        if ($this->image) {
            return url('storage/' . $this->image); // e.g., http://127.0.0.1:8000/storage/courses/10.png
        }
        return null;
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
public function wishlistedBy()
{
    return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
}



    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get all reviews for the course.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the average rating of the course.
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) $this->reviews()->avg('rating');
    }

    /**
     * Get the total number of reviews for the course.
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Check if a user has reviewed the course.
     */
    public function hasUserReviewed(int $userId): bool
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }


    /**
     * The categories that belong to the course.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'course_category');
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'course_plan');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Enrollment::class, 'course_id', 'id', 'id', 'user_id')
                    ->where('enrollments.status', 'completed');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function getTotalVideosCount(): int
    {
        return $this->videos()->count();
    }

    public function getEnrollmentCount(): int
    {
        return $this->enrollments()->where('status', 'completed')->count();
    }

    public function getCompletionRate(): float
    {
        $totalEnrollments = $this->getEnrollmentCount();
        if ($totalEnrollments === 0) {
            return 0;
        }

        $completedEnrollments = $this->enrollments()
            ->where('status', 'completed')
            ->where('progress_percentage', '>=', 100)
            ->count();

        return round(($completedEnrollments / $totalEnrollments) * 100, 2);
    }

    /**
     * Check if individual purchase is allowed
     * Course can be purchased individually if it's not in any plan
     */
    public function allowsIndividualPurchase(): bool
    {
        return $this->plans()->count() === 0;
    }

    /**
     * Check if course requires subscription
     * Course requires subscription if it's in any plan
     */
    public function requiresSubscription(): bool
    {
        return $this->plans()->count() > 0;
    }

    /**
     * Get available plans for this course
     */
    public function getAvailablePlans()
    {
        return $this->plans()->where('is_active', true)->get();
    }



/**
 * Get the certificates for the user
 */
public function certificates()
{
    return $this->hasMany(Certificate::class);
}
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (auth()->check() && !$course->instructor_id) {
                $course->instructor_id = auth()->id();
            }
            
            // If instructor is creating/updating, set approval status to pending
            if (auth()->check() && auth()->user()->role === 'instructor') {
                $course->approval_status = 'pending';
                $course->is_published = false; // Prevent direct publishing
            }
        });

        static::created(function ($course) {
            // Notify admins when instructor submits a new course
            if (auth()->check() && auth()->user()->role === 'instructor' && $course->approval_status === 'pending') {
                $admins = \App\Models\User::where('role', 'admin')->get();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewCourseSubmittedNotification($course));
            }
        });

        static::updating(function ($course) {
            // If instructor is updating, reset approval status to pending if course details changed
            if (auth()->check() && auth()->user()->role === 'instructor') {
                $importantFields = ['title', 'description', 'price', 'what_you_will_learn', 'requirements'];
                $wasApproved = $course->getOriginal('approval_status') === 'approved';
                
                if ($course->isDirty($importantFields)) {
                    $course->approval_status = 'pending';
                    $course->is_published = false; // Prevent direct publishing
                    
                    // Notify admins if course was previously approved and is being resubmitted
                    if ($wasApproved) {
                        $admins = \App\Models\User::where('role', 'admin')->get();
                        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewCourseSubmittedNotification($course));
                    }
                }
            }
        });
    }
    protected static function booted()
{
    static::updated(function ($course) {
        // Only trigger if the instructor is changing course details
        // and not when something unrelated is updated in the background
        if ($course->isDirty(['title', 'description', 'price', 'is_published'])) {
            $instructor = $course->instructor;
            if ($instructor) {
                $instructor->notify(new \App\Notifications\CourseContentChanged($course->title));
            }
        }
    });
}

}
