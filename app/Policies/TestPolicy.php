<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Test;

class TestPolicy
{
    /**
     * Determine if the user can view any tests.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view tests
        return true;
    }

    /**
     * Determine if the user can view the test.
     */
    public function view(User $user, Test $test): bool
    {
        // Students can view tests for courses they're enrolled in
        // Admin and course instructor can view any test
        if ($user->role === 'admin' || 
            ($user->role === 'instructor' && $user->id === $test->course->instructor_id)) {
            return true;
        }

        // Students can view tests if they're enrolled
        if ($user->role === 'student') {
            return $user->enrollments()
                ->where('course_id', $test->course_id)
                ->where('status', 'completed')
                ->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create tests.
     */
    public function create(User $user): bool
    {
        // Only admin and instructors can create tests
        return in_array($user->role, ['admin', 'instructor']);
    }

    /**
     * Determine if the user can update the test.
     */
    public function update(User $user, Test $test): bool
    {
        // Admin can update any test
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can only update tests for their own courses
        if ($user->role === 'instructor') {
            return $user->id === $test->course->instructor_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the test.
     */
    public function delete(User $user, Test $test): bool
    {
        // Admin can delete any test
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can only delete tests for their own courses
        if ($user->role === 'instructor') {
            return $user->id === $test->course->instructor_id;
        }

        return false;
    }
}

