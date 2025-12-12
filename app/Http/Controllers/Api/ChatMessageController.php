<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChatMessageController extends Controller
{
    use ApiResponse;

    public function index(Chat $chat, Request $request)
    {
        Gate::authorize('view', $chat);

        $messages = $chat->messages()
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get();

        $this->markRead($chat, $request->user());

        return $this->successResponse($messages);
    }

    public function store(Chat $chat, Request $request)
    {
        Gate::authorize('send', $chat);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $senderType = $user->id === $chat->student_id ? 'student' : 'instructor';

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'body' => $validated['body'],
        ]);

        $chat->last_message_at = now();

        if ($senderType === 'student') {
            $chat->instructor_unread_count = ($chat->instructor_unread_count ?? 0) + 1;
        } else {
            $chat->student_unread_count = ($chat->student_unread_count ?? 0) + 1;
        }

        $chat->save();

        return $this->successResponse($message->load('sender:id,name'), 'Message sent', 201);
    }

    public function markAsRead(Chat $chat, Request $request)
    {
        Gate::authorize('view', $chat);

        $this->markRead($chat, $request->user());

        return $this->successResponse(null, 'Marked as read');
    }

    protected function markRead(Chat $chat, $user): void
    {
        $isStudent = $user->id === $chat->student_id;
        $oppositeType = $isStudent ? 'instructor' : 'student';

        ChatMessage::where('chat_id', $chat->id)
            ->where('sender_type', $oppositeType)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($isStudent) {
            $chat->student_unread_count = 0;
        } else {
            $chat->instructor_unread_count = 0;
        }

        $chat->save();
    }
}
