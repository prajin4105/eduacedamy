<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
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
}
