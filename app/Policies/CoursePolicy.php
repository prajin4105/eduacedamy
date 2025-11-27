<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;

class CoursePolicy
{
    /**
     * Determine if the user can view any courses.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view courses list
        return true;
    }

    /**
     * Determine if the user can view the course.
     */
    public function view(User $user, Course $course): bool
    {
        // All authenticated users can view published courses
        return $course->is_published || $user->role === 'admin' || $user->id === $course->instructor_id;
    }

    /**
     * Determine if the user can create courses.
     */
    public function create(User $user): bool
    {
        // Only admin and instructors can create courses
        return in_array($user->role, ['admin', 'instructor']);
    }

    /**
     * Determine if the user can update the course.
     */
    public function update(User $user, Course $course): bool
    {
        // Admin can update any course
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can only update their own courses
        if ($user->role === 'instructor') {
            return $user->id === $course->instructor_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the course.
     */
    public function delete(User $user, Course $course): bool
    {
        // Admin can delete any course
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can only delete their own courses
        if ($user->role === 'instructor') {
            return $user->id === $course->instructor_id;
        }

        return false;
    }
}

