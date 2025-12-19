<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Course;
use App\Models\Enrollment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{
    use ApiResponse;

    /**
     * Get all chats for authenticated user
     * - Students: get chats for all enrolled courses
     * - Instructors: get chats for all teaching courses
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Chat::query()
            ->with(['course:id,title,slug', 'student:id,name,email', 'instructor:id,name,email', 'latestMessage'])
            ->withCount('messages')
            ->orderByDesc('last_message_at')
            ->orderByDesc('id');

        // Filter based on user role
        if ($user->role === 'instructor') {
            $query->where('instructor_id', $user->id);
        } else {
            // Student: show all chats for enrolled courses
            $query->where('student_id', $user->id);
        }

        $chats = $query->get()->map(function (Chat $chat) use ($user) {
            $latest = $chat->latestMessage;

            // Get unread count based on user role
            $unread = $user->id === $chat->student_id
                ? $chat->student_unread_count
                : $chat->instructor_unread_count;

            return [
                'id' => $chat->id,
                'course' => $chat->course,
                'student' => $chat->student,
                'instructor' => $chat->instructor,
                'status' => $chat->status,
                'last_message_at' => $chat->last_message_at,
                'latest_message' => $latest ? [
                    'body' => $latest->body,
                    'sender_type' => $latest->sender_type,
                    'created_at' => $latest->created_at,
                ] : null,
                'unread_count' => $unread,
            ];
        });

        return $this->successResponse($chats);
    }

    /**
     * Create or retrieve a chat for a course
     * Only students can initiate chats with instructors
     */
    public function store(Request $request, Course $course)
    {
        $user = $request->user();

        // Only students can start chats
        if ($user->role !== 'student') {
            return $this->errorResponse('Only students can start chats', 403);
        }

        // Check if student is enrolled in the course
        $hasEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['enrolled', 'in_progress', 'completed'])
            ->exists();

        if (!$hasEnrollment) {
            return $this->errorResponse('You must be enrolled in this course to chat with the instructor', 403);
        }

        // Check if course has an instructor
        if (!$course->instructor_id) {
            return $this->errorResponse('Course does not have an assigned instructor yet', 422);
        }

        // Create or get existing chat
        $chat = Chat::firstOrCreate(
            [
                'course_id' => $course->id,
                'student_id' => $user->id,
            ],
            [
                'instructor_id' => $course->instructor_id,
                'status' => 'open',
                'last_message_at' => now(),
            ]
        );

        $chat->load(['course:id,title,slug', 'student:id,name,email', 'instructor:id,name,email']);

        return $this->successResponse($chat, 'Chat ready');
    }

    /**
     * Get a specific chat with details
     */
    public function show(Chat $chat, Request $request)
    {
        Gate::authorize('view', $chat);

        $chat->load(['course:id,title,slug', 'student:id,name,email', 'instructor:id,name,email']);

        $latest = $chat->messages()->latest()->first();
        $user = $request->user();

        $unread = $user->id === $chat->student_id
            ? $chat->student_unread_count
            : $chat->instructor_unread_count;

        return $this->successResponse([
            'id' => $chat->id,
            'course' => $chat->course,
            'student' => $chat->student,
            'instructor' => $chat->instructor,
            'status' => $chat->status,
            'last_message_at' => $chat->last_message_at,
            'latest_message' => $latest,
            'unread_count' => $unread,
        ]);
    }

    /**
     * Update chat status (e.g., close, archive)
     */
    public function update(Request $request, Chat $chat)
    {
        Gate::authorize('update', $chat);

        $validated = $request->validate([
            'status' => 'required|in:open,closed,archived',
        ]);

        $chat->update($validated);

        return $this->successResponse($chat, 'Chat updated successfully');
    }

    /**
     * Delete a chat (soft delete)
     */
    public function destroy(Chat $chat)
    {
        Gate::authorize('delete', $chat);

        $chat->delete();

        return $this->successResponse(null, 'Chat deleted successfully');
    }
}
