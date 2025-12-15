<?php

namespace App\Filament\Instructor\Pages;

use App\Models\Chat;
use App\Models\ChatMessage;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class ChatCenter extends Page
{
    use WithFileUploads;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected string $view = 'filament.instructor.pages.chat-center';
    protected static ?string $navigationLabel = 'Chat';
    protected static ?string $title = 'Chat';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'chat';

    public array $chats = [];
    public ?int $selectedChatId = null;
    public array $messages = [];
    public string $messageBody = '';
    public $attachment = null;

    /**
     * Initialize the component
     */
    public function mount(): void
    {
        $this->loadChats();

        // Auto-select first chat if available
        if (!$this->selectedChatId && count($this->chats) > 0) {
            $this->selectedChatId = $this->chats[0]['id'];
            $this->loadMessages();
        }
    }

    /**
     * Load all chats for the current instructor
     */
    public function loadChats(): void
    {
        $this->chats = Chat::query()
            ->where('instructor_id', Auth::id())
            ->with(['student:id,name', 'course:id,title'])
            ->orderByDesc('last_message_at')
            ->get()
            ->map(fn (Chat $chat) => [
                'id' => $chat->id,
                'course' => $chat->course?->only(['id', 'title']) ?? ['id' => null, 'title' => 'No Course'],
                'student' => $chat->student?->only(['id', 'name']) ?? ['id' => null, 'name' => 'Unknown Student'],
                'last_message_at' => $chat->last_message_at,
                'unread' => $chat->instructor_unread_count ?? 0,
            ])
            ->toArray();
    }

    /**
     * Select a chat and load its messages
     */
    public function selectChat(int $chatId): void
    {
        $this->selectedChatId = $chatId;
        $this->loadMessages();
    }

    /**
     * Load messages for the selected chat
     */
    public function loadMessages(): void
    {
        if (!$this->selectedChatId) {
            $this->messages = [];
            return;
        }

        $chat = Chat::where('instructor_id', Auth::id())->find($this->selectedChatId);

        if (!$chat) {
            $this->messages = [];
            $this->selectedChatId = null;
            return;
        }

        $this->messages = $chat->messages()
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get()
            ->map(fn (ChatMessage $message) => [
                'id' => $message->id,
                'body' => $message->body,
                'image_url' => $message->image_url,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender?->name ?? 'Unknown',
                'created_at' => $message->created_at,
                'read_at' => $message->read_at,
            ])
            ->toArray();

        // Mark messages as read
        $this->markAsRead($chat);
        $this->refreshChatUnread($chat->id, 0);
    }

    /**
     * Send a new message
     */
    public function sendMessage(): void
    {
        // Validate message
        $this->validate([
            'messageBody' => ['nullable', 'string', 'max:2000', 'required_without:attachment'],
            'attachment' => ['nullable', 'image', 'max:5120'],
        ]);

        if (!$this->selectedChatId) {
            return;
        }

        $chat = Chat::where('instructor_id', Auth::id())->find($this->selectedChatId);

        if (!$chat) {
            return;
        }

        $imagePath = $this->attachment
            ? $this->attachment->store('chat-images', 'public')
            : null;

        // Create message
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'instructor',
            'body' => trim($this->messageBody) ?: '',
            'image_path' => $imagePath,
        ]);

        // Update chat metadata
        $chat->update([
            'last_message_at' => now(),
            'student_unread_count' => ($chat->student_unread_count ?? 0) + 1,
        ]);

        // Reset input and reload
        $this->reset(['messageBody', 'attachment']);
        $this->loadMessages();
        $this->loadChats();

        // Dispatch event for auto-scroll
        $this->dispatch('message-sent');
    }

    /**
     * Mark all unread messages in a chat as read
     */
    protected function markAsRead(Chat $chat): void
    {
        ChatMessage::where('chat_id', $chat->id)
            ->where('sender_type', 'student')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $chat->update(['instructor_unread_count' => 0]);
    }

    /**
     * Update unread count for a specific chat in the list
     */
    protected function refreshChatUnread(int $chatId, int $value): void
    {
        $this->chats = array_map(function ($chat) use ($chatId, $value) {
            if ($chat['id'] === $chatId) {
                $chat['unread'] = $value;
            }
            return $chat;
        }, $this->chats);
    }

    /**
     * Refresh chats periodically (for polling)
     */
    public function refreshChats(): void
    {
        $currentSelectedId = $this->selectedChatId;
        $this->loadChats();

        // Restore selected chat
        if ($currentSelectedId) {
            $this->selectedChatId = $currentSelectedId;
        }
    }

    /**
     * Delete a message (optional feature)
     */
    public function deleteMessage(int $messageId): void
    {
        $message = ChatMessage::where('sender_id', Auth::id())
            ->where('sender_type', 'instructor')
            ->find($messageId);

        if ($message) {
            $message->delete();
            $this->loadMessages();
        }
    }

    /**
     * Search chats (optional feature)
     */
    public function searchChats(string $search): void
    {
        if (empty(trim($search))) {
            $this->loadChats();
            return;
        }

        $this->chats = Chat::query()
            ->where('instructor_id', Auth::id())
            ->with(['student:id,name', 'course:id,title'])
            ->whereHas('student', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('course', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderByDesc('last_message_at')
            ->get()
            ->map(fn (Chat $chat) => [
                'id' => $chat->id,
                'course' => $chat->course?->only(['id', 'title']) ?? ['id' => null, 'title' => 'No Course'],
                'student' => $chat->student?->only(['id', 'name']) ?? ['id' => null, 'name' => 'Unknown Student'],
                'last_message_at' => $chat->last_message_at,
                'unread' => $chat->instructor_unread_count ?? 0,
            ])
            ->toArray();
    }

    /**
     * Get polling interval (for real-time updates)
     */
    public function getPollingInterval(): ?string
    {
        // Poll every 5 seconds when chat is open
        return $this->selectedChatId ? '5s' : null;
    }
}
