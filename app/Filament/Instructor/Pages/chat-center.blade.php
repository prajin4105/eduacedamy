<x-filament-panels::page>
    <div class="flex h-[calc(100vh-12rem)] gap-4">
        <!-- Chat List Sidebar -->
        <div class="w-80 flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Conversations</h2>
                    <span class="px-2.5 py-0.5 text-xs font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 rounded-full">
                        {{ count($chats) }}
                    </span>
                </div>

                <!-- Search Bar (Optional) -->
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search conversations..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent dark:bg-gray-900 dark:text-white"
                        wire:model.live.debounce.300ms="search"
                    >
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Chat List -->
            <div class="flex-1 overflow-y-auto">
                @forelse($chats as $chat)
                    <button
                        wire:click="selectChat({{ $chat['id'] }})"
                        class="w-full p-4 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 border-b border-gray-100 dark:border-gray-700 {{ $selectedChatId === $chat['id'] ? 'bg-primary-50 dark:bg-primary-900/20 border-l-4 border-l-primary-600' : '' }}"
                    >
                        <!-- Avatar -->
                        <div class="flex-shrink-0 relative">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                {{ strtoupper(substr($chat['student']['name'] ?? 'U', 0, 1)) }}
                            </div>
                            @if($chat['unread'] > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white dark:border-gray-800 rounded-full flex items-center justify-center text-xs font-bold text-white">
                                    {{ $chat['unread'] > 9 ? '9+' : $chat['unread'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Chat Info -->
                        <div class="flex-1 min-w-0 text-left">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white truncate {{ $chat['unread'] > 0 ? 'font-bold' : '' }}">
                                    {{ $chat['student']['name'] ?? 'Unknown' }}
                                </h3>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $chat['course']['title'] ?? 'No course' }}
                            </p>

                            @if($chat['last_message_at'])
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($chat['last_message_at'])->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </button>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">No conversations yet</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Students will appear here when they message you</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($selectedChatId)
                @php
                    $selectedChat = collect($chats)->firstWhere('id', $selectedChatId);
                @endphp

                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-semibold shadow-md">
                            {{ strtoupper(substr($selectedChat['student']['name'] ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $selectedChat['student']['name'] ?? 'Unknown Student' }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $selectedChat['course']['title'] ?? 'No course' }}
                            </p>
                        </div>

                        <!-- Refresh Button -->
                        <button
                            wire:click="loadMessages"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            title="Refresh messages"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages Area -->
                <div
                    class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900/30"
                    id="messages-container"
                    wire:poll.5s="loadMessages"
                >
                    @forelse($messages as $message)
                        @php
                            $isInstructor = $message['sender_id'] === auth()->id();
                        @endphp

                        <div class="flex {{ $isInstructor ? 'justify-end' : 'justify-start' }} animate-fade-in">
                            <div class="flex gap-2 max-w-[75%] {{ $isInstructor ? 'flex-row-reverse' : 'flex-row' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if(!$isInstructor)
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                                            {{ strtoupper(substr($message['sender_name'] ?? 'S', 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'I', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Message Bubble -->
                                <div class="flex flex-col {{ $isInstructor ? 'items-end' : 'items-start' }}">
                                    <div class="px-4 py-2.5 rounded-2xl shadow-sm {{ $isInstructor ? 'bg-primary-600 text-white rounded-tr-sm' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-tl-sm border border-gray-200 dark:border-gray-700' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words leading-relaxed">{{ $message['body'] }}</p>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1 px-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($message['created_at'])->format('g:i A') }}
                                        </span>
                                        @if($isInstructor)
                                            @if($message['read_at'])
                                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd" d="M19.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0" clip-rule="evenodd" opacity="0.5"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No messages yet</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Start the conversation by sending a message below</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <form wire:submit.prevent="sendMessage">
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <textarea
                                    wire:model="messageBody"
                                    rows="2"
                                    placeholder="Type your message... (Press Enter to send, Shift+Enter for new line)"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent dark:bg-gray-900 dark:text-white resize-none transition-all"
                                    @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"
                                ></textarea>
                                @error('messageBody')
                                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="flex-shrink-0 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-md hover:shadow-lg"
                                wire:loading.attr="disabled"
                            >
                                <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="hidden sm:inline">Send</span>
                            </button>
                        </div>
                    </form>

                    <!-- Typing Indicator (Optional) -->
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 h-4">
                        <span wire:loading wire:target="sendMessage">Sending message...</span>
                    </div>
                </div>
            @else
                <!-- No Chat Selected -->
                <div class="flex items-center justify-center h-full bg-gray-50 dark:bg-gray-900/30">
                    <div class="text-center max-w-sm">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Select a conversation</h3>
                        <p class="text-gray-500 dark:text-gray-400">Choose a chat from the sidebar to start viewing and sending messages</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Custom scrollbar */
        #messages-container::-webkit-scrollbar {
            width: 6px;
        }

        #messages-container::-webkit-scrollbar-track {
            background: transparent;
        }

        #messages-container::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .dark #messages-container::-webkit-scrollbar-thumb {
            background: #4a5568;
        }

        #messages-container::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        .dark #messages-container::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
    </style>

    @script
    <script>
        // Auto-scroll to bottom when new messages arrive
        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            }
        }

        // Listen for message sent event
        $wire.on('message-sent', () => {
            setTimeout(scrollToBottom, 100);
        });

        // Scroll to bottom on initial load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(scrollToBottom, 300);
        });

        // Scroll to bottom when messages are loaded
        Livewire.hook('morph.updated', ({ el, component }) => {
            if (el.id === 'messages-container') {
                setTimeout(scrollToBottom, 100);
            }
        });

        // Auto-scroll when new messages appear (polling)
        let lastMessageCount = 0;
        setInterval(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                const messageCount = container.querySelectorAll('.animate-fade-in').length;
                if (messageCount > lastMessageCount) {
                    scrollToBottom();
                }
                lastMessageCount = messageCount;
            }
        }, 1000);
    </script>
    @endscript
</x-filament-panels::page>
