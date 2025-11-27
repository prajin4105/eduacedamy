<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enrollment;

class EnrollmentPolicy
{
    /**
     * Determine if the user can view any enrollments.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view enrollments (their own)
        return true;
    }

    /**
     * Determine if the user can view the enrollment.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        // Admin can view any enrollment
        if ($user->role === 'admin') {
            return true;
        }

        // Users can view their own enrollments
        if ($user->id === $enrollment->user_id) {
            return true;
        }

        // Instructors can view enrollments for their courses
        if ($user->role === 'instructor' && $enrollment->course && $enrollment->course->instructor_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create enrollments.
     */
    public function create(User $user): bool
    {
        // Students can enroll themselves
        // Admin can create enrollments for anyone
        return $user->role === 'student' || $user->role === 'admin';
    }

    /**
     * Determine if the user can update the enrollment.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        // Admin can update any enrollment
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can update enrollments for their courses
        if ($user->role === 'instructor' && $enrollment->course && $enrollment->course->instructor_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the enrollment.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        // Admin can delete any enrollment
        if ($user->role === 'admin') {
            return true;
        }

        // Users can delete their own enrollments
        if ($user->id === $enrollment->user_id) {
            return true;
        }

        return false;
    }
}

