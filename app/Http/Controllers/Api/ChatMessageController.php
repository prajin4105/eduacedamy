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
            ->get()
            ->map(fn (ChatMessage $message) => [
                'id' => $message->id,
                'body' => $message->body,
                'image_url' => $message->image_url,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'created_at' => $message->created_at,
                'read_at' => $message->read_at,
            ]);

        $this->markRead($chat, $request->user());

        return $this->successResponse($messages);
    }

    public function store(Chat $chat, Request $request)
    {
        Gate::authorize('send', $chat);

        $validated = $request->validate([
            'body' => ['nullable', 'string', 'max:2000', 'required_without:image'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $user = $request->user();
        $senderType = $user->id === $chat->student_id ? 'student' : 'instructor';

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('chat-images', 'public')
            : null;

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'body' => $validated['body'] ?? '',
            'image_path' => $imagePath,
        ]);

        $chat->last_message_at = now();

        if ($senderType === 'student') {
            $chat->instructor_unread_count = ($chat->instructor_unread_count ?? 0) + 1;
        } else {
            $chat->student_unread_count = ($chat->student_unread_count ?? 0) + 1;
        }

        $chat->save();

        $message->load('sender:id,name');

        return $this->successResponse($message, 'Message sent', 201);
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
