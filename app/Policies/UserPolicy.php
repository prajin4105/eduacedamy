<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        // Only admin and instructors can view users list
        return in_array($user->role, ['admin', 'instructor']);
    }

    /**
     * Determine if the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        // Admin can view any user
        if ($user->role === 'admin') {
            return true;
        }

        // Users can view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Instructors can view students (for their courses)
        if ($user->role === 'instructor' && $model->role === 'student') {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        // Only admin can create users
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // Admin can update any user
        if ($user->role === 'admin') {
            return true;
        }

        // Users can update their own profile
        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admin can delete users
        return $user->role === 'admin';
    }
}

