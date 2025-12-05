<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',              // Add this
        'otp_expires_at',
        'phone',
        'profile_picture',
        'bio',
        'date_of_birth',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',

        'otp',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }


    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->latest('starts_at');
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function canAccessCourse(Course $course): bool
    {
        // Always allow if user has completed/purchased enrollment
        $hasCompletedEnrollment = $this->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->exists();

        if ($hasCompletedEnrollment) {
            return true;
        }

        // If the course is tied to any plan, require active subscription to one of those plans
        $coursePlanIds = $course->plans()->pluck('plans.id');
        if ($coursePlanIds->isEmpty()) {
            return false; // Course requires purchase and no enrollment found
        }

        $activeSub = $this->activeSubscription()->first();
        if (!$activeSub) {
            return false;
        }

        return $coursePlanIds->contains($activeSub->plan_id);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->wherePivot('status', 'completed')
                    ->withPivot('enrolled_at', 'amount_paid')
                    ->withTimestamps();
    }

    public function createdCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function courseProgress(): HasMany
    {
        return $this->hasMany(CourseProgress::class);
    }


/**
 * Get the certificates for the user
 */
public function certificates()
{
    return $this->hasMany(Certificate::class);
}

    public function isEnrolledIn($courseId): bool
    {
        return $this->enrollments()->where('course_id', $courseId)->where('status', 'completed')->exists();
    }

    public function getEnrollmentStatus($courseId): ?string
    {
        $enrollment = $this->enrollments()->where('course_id', $courseId)->first();
        return $enrollment ? $enrollment->getProgressStatus() : null;
    }

    public function getCompletedCourses()
    {
        return $this->enrollments()
                    ->where('status', 'completed')
                    ->where('progress_percentage', '>=', 100)
                    ->with('course')
                    ->get();
    }

    public function getInProgressCourses()
    {
        return $this->enrollments()
                    ->where('status', 'completed')
                    ->where('progress_percentage', '>', 0)
                    ->where('progress_percentage', '<', 100)
                    ->with('course')
                    ->get();
    }
    public function wishlist()
{
    return $this->belongsToMany(Course::class, 'wishlists')->withTimestamps();
}

    public function instructorApplication(): HasOne
    {
        return $this->hasOne(InstructorApplication::class);
    }


}
