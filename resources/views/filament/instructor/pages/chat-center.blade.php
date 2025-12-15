<x-filament::page class="p-0">
    <style>
        /* Layout */
        .chat-grid { display: grid; grid-template-columns: 1fr; gap: 16px; padding: 24px; }
        @media (min-width: 768px) { .chat-grid { grid-template-columns: 320px 1fr; } }

        .panel { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); overflow: hidden; }
        .dark .panel { background: #0b0b0c; border-color: #222; color: #e6e6e6; }

        /* Left list */
        .left-header { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid #f1f1f1; }
        .left-header .title { font-weight:600; font-size:14px; }
        .search { padding:6px 8px; font-size:13px; border:1px solid #ddd; border-radius:6px; width:130px; }
        .refresh-btn { font-size:12px; color:#4f46e5; background:transparent; border:0; cursor:pointer; }

        .chats-list { max-height: 74vh; overflow-y:auto; }
        .chat-item { display:flex; gap:12px; align-items:center; padding:12px 16px; width:100%; text-align:left; border-bottom:1px solid #f6f6f6; background:white; cursor:pointer; }
        .chat-item:hover { background:#eef2ff; }
        .chat-item.active { background:#eef2ff; }
        .avatar { height:40px; width:40px; border-radius:999px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-weight:700; color:#3730a3; }
        .chat-meta { flex:1; min-width:0; }
        .course { font-weight:600; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .student { font-size:12px; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .chat-right { text-align:right; min-width:68px; font-size:12px; color:#9ca3af; }

        /* Right messages */
        .messages-panel { display:flex; flex-direction:column; height:78vh; }
        .empty-state { padding:48px; text-align:center; color:#6b7280; }
        .messages-area { flex:1; overflow-y:auto; padding:20px; background:transparent; }
        .message-row { display:flex; margin-bottom:12px; position: relative; }
        .message-row.right { justify-content:flex-end; }
        .message-block {
            max-width:75%;
            padding:12px 14px;
            border-radius:18px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            font-size:14px;
            line-height:1.5;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            position: relative;
            cursor: pointer;
        }
        .message-block.incoming { background:#ffffff; border:1px solid #e5e7eb; color:#111827; }
        .message-block.outgoing { background:#4f46e5; color:#fff; }

        .message-sender { font-size:12px; color:#6b7280; margin-bottom:6px; }
        .message-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            white-space: pre-wrap;
        }

        /* Tooltip for time and read status */
        .message-tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .message-block:hover .message-tooltip {
            opacity: 1;
        }

        /* Arrow for tooltip */
        .message-tooltip::before {
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
        }

        /* Tooltip positioning - default bottom */
        .message-tooltip.bottom {
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
        }

        .message-tooltip.bottom::before {
            border-width: 0 6px 6px 6px;
            border-color: transparent transparent rgba(0, 0, 0, 0.85) transparent;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Tooltip positioning - top */
        .message-tooltip.top {
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
        }

        .message-tooltip.top::before {
            border-width: 6px 6px 0 6px;
            border-color: rgba(0, 0, 0, 0.85) transparent transparent transparent;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Composer */
        .composer { border-top:1px solid #f1f1f1; padding:12px; display:flex; gap:10px; align-items:center; background:#fff; }
        .composer input[type="text"] { flex:1; padding:10px 12px; border:1px solid #ddd; border-radius:999px; font-size:14px; }
        .composer button.send { padding:8px 14px; border-radius:999px; background:#4f46e5; color:#fff; border:0; cursor:pointer; font-weight:600; }

        /* Dark adjustments if Filament applies dark mode class on body */
        .dark .left-header, .dark .composer, .dark .chat-item { border-color:#222; background:transparent; }
        .dark .search { background:#0b0b0c; color:#e6e6e6; border-color:#2b2b2b; }
        .dark .chat-item:hover { background:#182028; }
        .dark .avatar { background:#0f172a; color:#c7d2fe; }
        .dark .course, .dark .student, .dark .chat-right { color:#cbd5e1; }
        .dark .message-block.incoming { background:#0b0b0c; border-color:#222; color:#e6e6e6; }
        .dark .message-block.outgoing { background:#3b82f6; color:#fff; }
        .dark .composer input[type="text"] { background:#071023; color:#e6e6e6; border-color:#222; }
        .dark .composer { background:transparent; border-top-color:#222; }
    </style>

    <div x-data="{ showImage:false, imageSrc:null }" x-on:open-chat-image.window="showImage=true; imageSrc=$event.detail">
        <div class="chat-grid">
            <!-- Left -->
            <div class="panel">
            <div class="left-header">
                <div class="title">Chats</div>
                
            </div>

            <div class="chats-list" role="list" aria-label="Chats list">
                @forelse($chats as $chat)
                    <button
                        wire:click="selectChat({{ $chat['id'] }})"
                        type="button"
                        class="chat-item {{ $selectedChatId === $chat['id'] ? 'active' : '' }}"
                        role="listitem"
                        aria-current="{{ $selectedChatId === $chat['id'] ? 'true' : 'false' }}"
                    >
                        <div class="avatar">{{ \Illuminate\Support\Str::upper(substr($chat['student']['name'] ?? 'S', 0, 1)) }}</div>

                        <div class="chat-meta">
                            <div class="course">{{ $chat['course']['title'] ?? 'Course' }}</div>
                            <div class="student">Student: {{ $chat['student']['name'] ?? 'Unknown' }}</div>
                        </div>

                        <div class="chat-right">
                            <div>{{ optional($chat['last_message_at'])->diffForHumans() }}</div>
                            @if(($chat['unread'] ?? 0) > 0)
                                <div style="margin-top:6px; display:inline-block; background:#ef4444; color:#fff; padding:2px 8px; border-radius:999px; font-size:12px;">{{ $chat['unread'] }}</div>
                            @endif
                        </div>
                    </button>
                @empty
                    <div style="padding:14px; color:#6b7280;">No chats yet.</div>
                @endforelse
            </div>
        </div>

            <!-- Right -->
            <div class="panel messages-panel">
            @if(!$selectedChatId)
                <div class="empty-state">
                    <div style="font-weight:600; color:#111827">No chat selected</div>
                    <div style="font-size:13px; margin-top:6px; color:#6b7280">Choose a conversation from the left to view messages.</div>
                </div>
            @else
                <div id="messagesContainer" class="messages-area"
                     x-data
                     x-init="() => {
                         const el = $el;
                         const scrollBottom = () => el.scrollTop = el.scrollHeight;
                         setTimeout(scrollBottom, 50);

                         // Livewire emits from backend: 'messageSent' and 'messagesUpdated'
                         Livewire.on('messageSent', (messageId) => {
                             setTimeout(scrollBottom, 50);
                         });

                         Livewire.on('messagesUpdated', (chatId) => {
                             setTimeout(scrollBottom, 50);
                         });

                         window.addEventListener('message-sent', () => setTimeout(scrollBottom, 50));
                     }"
                     role="log"
                     aria-live="polite"
                >
                    @forelse($messages as $message)
                        <div class="message-row {{ $message['sender_id'] === auth()->id() ? 'right' : 'left' }}">
                            <div class="message-block {{ $message['sender_id'] === auth()->id() ? 'outgoing' : 'incoming' }}"
                                 x-data="{
                                     tooltipPosition: 'bottom',
                                     updateTooltipPosition(event) {
                                         const messageBlock = event.currentTarget;
                                         const tooltip = messageBlock.querySelector('.message-tooltip');
                                         const container = document.getElementById('messagesContainer');

                                         if (!tooltip || !container) return;

                                         const blockRect = messageBlock.getBoundingClientRect();
                                         const containerRect = container.getBoundingClientRect();
                                         const tooltipHeight = 40; // Approximate tooltip height

                                         const spaceBelow = containerRect.bottom - blockRect.bottom;
                                         const spaceAbove = blockRect.top - containerRect.top;

                                         // If not enough space below, show on top
                                         if (spaceBelow < tooltipHeight && spaceAbove > tooltipHeight) {
                                             this.tooltipPosition = 'top';
                                         } else {
                                             this.tooltipPosition = 'bottom';
                                         }
                                     }
                                 }"
                                 @mouseenter="updateTooltipPosition($event)"
                            >
                                @if($message['sender_id'] !== auth()->id())
                                    <div class="message-sender">{{ $message['sender_name'] ?? 'Student' }}</div>
                                @endif

                                @if(!empty($message['body']))
                                    <div class="message-content">{{ $message['body'] }}</div>
                                @endif

                                @if(!empty($message['image_url']))
                                    <div style="margin-top:12px; display:flex; justify-content:center;">
                                        <img
                                            src="{{ $message['image_url'] }}"
                                            alt="Attachment"
                                            style="max-height:220px; max-width:100%; border-radius:12px; border:1px solid #e5e7eb; background:#fff; object-fit:contain; cursor:pointer;"
                                            x-on:click="$dispatch('open-chat-image', '{{ $message['image_url'] }}')"
                                        >
                                    </div>
                                @endif

                                <div class="message-tooltip" :class="tooltipPosition">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i A') }}
                                    @if($message['read_at'])
                                        • Read at {{ \Carbon\Carbon::parse($message['read_at'])->format('H:i A') }}
                                    @else
                                        • Unread
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:14px; color:#6b7280;">No messages yet in this conversation.</div>
                    @endforelse
                </div>

                <div class="composer">
                    <form wire:submit.prevent="sendMessage" style="display:flex; gap:10px; width:100%;">
                        <label style="display:flex; align-items:center; justify-content:center; width:46px; height:46px; border:1px solid #ddd; border-radius:50%; cursor:pointer; background:#f8fafc;">
                            <input type="file" accept="image/*" wire:model.live="attachment" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:22px; height:22px; color:#4f46e5;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </label>

                        <input wire:model.defer="messageBody" wire:keydown.enter.prevent="sendMessage" type="text" placeholder="Type your message..." />
                        <button type="submit" class="send">Send</button>
                    </form>
                    @if($attachment)
                        <div style="margin-top:8px; display:flex; align-items:center; gap:10px; background:#eef2ff; border:1px solid #e5e7eb; border-radius:12px; padding:8px 12px;">
                            <div style="display:flex; align-items:center; gap:6px; color:#3730a3; font-size:13px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px; height:16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.414a4 4 0 00-5.656-5.656L6.343 9.758" />
                                </svg>
                                <span style="font-weight:600;">Attachment ready</span>
                            </div>
                            <div wire:loading wire:target="attachment" style="font-size:12px; color:#6b7280;">Uploading...</div>
                            @if($attachment->temporaryUrl())
                                <img src="{{ $attachment->temporaryUrl() }}" alt="Preview" style="height:42px; width:42px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb;">
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div
            x-show="showImage"
            x-transition
            style="position:fixed; inset:0; background:rgba(0,0,0,0.75); display:flex; align-items:center; justify-content:center; z-index:1000; backdrop-filter: blur(2px);"
            x-on:click.self="showImage=false; imageSrc=null"
        >
            <button
                style="position:absolute; top:18px; right:18px; color:white;"
                x-on:click="showImage=false; imageSrc=null"
                aria-label="Close image preview"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:30px; height:30px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <img x-show="imageSrc" :src="imageSrc" alt="Preview" style="max-height:85vh; max-width:90vw; border-radius:16px; border:1px solid rgba(255,255,255,0.12); box-shadow:0 10px 40px rgba(0,0,0,0.35); object-fit:contain;">
        </div>
    </div>

    <script>
        // For backward compatibility with older code that dispatched window event
        window.addEventListener('message-sent', function () {
            const el = document.getElementById('messagesContainer');
            if (el) setTimeout(() => el.scrollTop = el.scrollHeight, 50);
        });

        // In case you want extra JS reaction to Livewire emit
        Livewire.on('messagesUpdated', function () {
            const el = document.getElementById('messagesContainer');
            if (el) setTimeout(() => el.scrollTop = el.scrollHeight, 50);
        });
    </script>
</x-filament::page>
