<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy
{
    /**
     * Determine if the user can view any videos.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view videos
        return true;
    }

    /**
     * Determine if the user can view the video.
     */
    public function view(User $user, Video $video): bool
    {
        // All authenticated users can view published videos
        // Admin and course instructor can view unpublished videos
        if ($video->is_published) {
            return true;
        }

        return $user->role === 'admin' || 
               ($user->role === 'instructor' && $user->id === $video->course->instructor_id);
    }

    /**
     * Determine if the user can create videos.
     */
    public function create(User $user): bool
    {
        // Only admin and instructors can create videos
        return in_array($user->role, ['admin', 'instructor']);
    }

    /**
     * Determine if the user can update the video.
     */
    public function update(User $user, Video $video): bool
    {
        // Admin can update any video
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can only update videos for their own courses
        if ($user->role === 'instructor') {
            return $user->id === $video->course->instructor_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the video.
     */
    public function delete(User $user, Video $video): bool
    {
        // Admin can delete any video
        if ($user->role === 'admin') {
            return true;
        }

        // Instructors can only delete videos for their own courses
        if ($user->role === 'instructor') {
            return $user->id === $video->course->instructor_id;
        }

        return false;
    }
}

