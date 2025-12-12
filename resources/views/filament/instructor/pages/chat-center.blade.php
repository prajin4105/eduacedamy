<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">Chats</h2>
                <button wire:click="loadChats" class="text-xs text-indigo-600 hover:text-indigo-700">Refresh</button>
            </div>

            <div class="max-h-[70vh] overflow-y-auto divide-y divide-gray-100">
                @forelse($chats as $chat)
                    <button
                        wire:click="selectChat({{ $chat['id'] }})"
                        class="w-full text-left px-4 py-3 hover:bg-indigo-50 {{ $selectedChatId === $chat['id'] ? 'bg-indigo-50' : '' }}"
                    >
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $chat['course']['title'] ?? 'Course' }}
                        </p>
                        <p class="text-xs text-gray-600">Student: {{ $chat['student']['name'] ?? 'Unknown' }}</p>
                        <div class="flex items-center justify-between mt-1 text-xs text-gray-500">
                            <span>{{ optional($chat['last_message_at'])->diffForHumans() }}</span>
                            @if(($chat['unread'] ?? 0) > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-100 text-indigo-700">
                                    {{ $chat['unread'] }}
                                </span>
                            @endif
                        </div>
                    </button>
                @empty
                    <div class="p-4 text-sm text-gray-500">No chats yet.</div>
                @endforelse
            </div>
        </div>

        <div class="md:col-span-2 bg-white shadow-sm border border-gray-200 rounded-lg flex flex-col h-[70vh]">
            @if(!$selectedChatId)
                <div class="p-6 text-sm text-gray-500">Select a chat to view messages.</div>
            @else
                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-3 bg-gray-50">
                    @forelse($messages as $message)
                        <div class="flex {{ $message['sender_id'] === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] rounded-xl px-3 py-2 text-sm shadow-sm {{ $message['sender_id'] === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-900' }}">
                                <p>{{ $message['body'] }}</p>
                                <p class="text-[10px] mt-1 {{ $message['sender_id'] === auth()->id() ? 'text-indigo-100' : 'text-gray-400' }}">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                                    @if($message['read_at'])
                                        <span class="ml-1">• Read</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No messages yet.</div>
                    @endforelse
                </div>

                <form wire:submit.prevent="sendMessage" class="border-t border-gray-200 p-4 flex items-center space-x-3">
                    <input
                        wire:model.defer="messageBody"
                        type="text"
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Type your message..."
                    />
                    <x-filament::button type="submit">
                        Send
                    </x-filament::button>
                </form>
            @endif
        </div>
    </div>
</x-filament::page>
