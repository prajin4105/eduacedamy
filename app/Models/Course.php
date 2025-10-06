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
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
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

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Enrollment::class, 'course_id', 'id', 'id', 'user_id')
                    ->where('enrollments.status', 'completed');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseProgress::class);
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
        });
    }
}
