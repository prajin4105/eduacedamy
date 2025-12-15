<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
          <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
          </svg>
          Chat
        </h1>
        <p class="text-gray-600 mt-1">
          Chat with your instructors about enrolled courses
        </p>
      </div>

      <!-- Main Chat Container -->
      <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden" style="height: calc(100vh - 180px);">
        <div class="grid grid-cols-1 lg:grid-cols-3 h-full">

          <!-- Chat List Sidebar -->
          <div class="border-r border-gray-200 flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
              <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Your Conversations
              </h2>
            </div>

            <!-- Chat List -->
            <div class="flex-1 overflow-y-auto">
              <div v-if="loadingChats" class="p-8 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="text-sm text-gray-500 mt-3">Loading chats...</p>
              </div>

              <div v-else-if="chats.length === 0" class="p-8 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-sm text-gray-500">No chats available</p>
                <p class="text-xs text-gray-400 mt-2">Enroll in courses to chat with instructors</p>
              </div>

              <div v-else>
                <div
                  v-for="chat in chats"
                  :key="chat.id"
                  @click="selectChat(chat)"
                  class="p-4 border-b border-gray-100 cursor-pointer transition-all hover:bg-indigo-50"
                  :class="selectedChat?.id === chat.id ? 'bg-indigo-50 border-l-4 border-l-indigo-600' : ''"
                >
                  <div class="flex items-start gap-3 mb-2">
                    <!-- Instructor Avatar -->
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                      {{ getInitials(chat.instructor?.name) }}
                    </div>

                    <div class="flex-1 min-w-0">
                      <div class="flex justify-between items-start">
                        <div class="flex-1">
                          <h3 class="font-semibold text-gray-900 text-sm truncate">
                            {{ chat.course?.title || 'Unknown Course' }}
                          </h3>
                          <p class="text-xs text-gray-600 mt-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ chat.instructor?.name || 'Instructor' }}
                          </p>
                        </div>
                        <div class="flex flex-col items-end gap-1 ml-2">
                          <span
                            v-if="chat.unread_count > 0"
                            class="bg-indigo-600 text-white text-xs font-bold rounded-full px-2 py-0.5 min-w-[20px] text-center shadow-sm"
                          >
                            {{ chat.unread_count }}
                          </span>
                          <span class="text-xs text-gray-400">
                            {{ formatTime(chat.last_message_at) }}
                          </span>
                        </div>
                      </div>
                      <p class="text-xs text-gray-600 truncate mt-1">
                        {{ chat.latest_message?.body || 'Start a conversation' }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Messages Area -->
          <div class="lg:col-span-2 flex flex-col overflow-hidden">
            <!-- Empty State -->
            <div v-if="!selectedChat" class="flex-1 flex items-center justify-center p-8">
              <div class="text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Select a conversation</h3>
                <p class="text-sm text-gray-500">Choose a chat from the list to start messaging</p>
              </div>
            </div>

            <!-- Chat View -->
            <div v-else class="flex flex-col h-full overflow-hidden">
              <!-- Chat Header -->
              <div class="p-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex items-center gap-3">
                  <!-- Instructor Avatar -->
                  <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                    {{ getInitials(selectedChat.instructor?.name) }}
                  </div>
                  <div class="flex-1">
                    <h2 class="font-semibold text-gray-900">
                      {{ selectedChat.course?.title }}
                    </h2>
                    <p class="text-sm text-gray-600 flex items-center gap-2 mt-0.5">
                      <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                      Instructor: {{ selectedChat.instructor?.name }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Messages Container -->
              <div
                ref="messageContainer"
                @scroll="handleScroll"
                class="flex-1 overflow-y-auto p-4 space-y-4 bg-gradient-to-b from-gray-50 to-white"
                style="max-height: calc(100vh - 320px); min-height: 300px;"
              >
                <!-- Load More Indicator -->
                <div v-if="loadingMessages && !isInitialLoad" class="text-center py-2">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600 mx-auto"></div>
                </div>

                <!-- Initial Loading -->
                <div v-if="isInitialLoad" class="flex items-center justify-center h-full">
                  <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-3"></div>
                    <p class="text-sm text-gray-500">Loading messages...</p>
                  </div>
                </div>

                <!-- Messages -->
                <div v-else-if="messages.length === 0" class="flex items-center justify-center h-full">
                  <div class="text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No messages yet. Start the conversation!</p>
                  </div>
                </div>

                <div
                  v-for="msg in messages"
                  :key="msg.id"
                  class="flex animate-fadeIn"
                  :class="msg.sender_id === currentUser?.id ? 'justify-end' : 'justify-start'"
                >
                  <div
                    class="max-w-[75%] rounded-2xl px-4 py-3 shadow-sm"
                    :class="msg.sender_id === currentUser?.id
                      ? 'bg-indigo-600 text-white rounded-br-sm'
                      : 'bg-white border border-gray-200 text-gray-900 rounded-bl-sm'"
                  >
                    <p class="text-sm whitespace-pre-wrap break-words">{{ msg.body }}</p>
                    <div class="flex items-center gap-2 mt-2 text-xs opacity-75">
                      <span>{{ formatMessageTime(msg.created_at) }}</span>
                      <span v-if="msg.sender_id === currentUser?.id">
                        <svg v-if="msg.read_at" class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13l4 4L23 7"></path>
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Message Input -->
              <div class="p-4 border-t border-gray-200 bg-white flex-shrink-0">
                <div class="flex gap-3">
                  <input
                    v-model="messageBody"
                    @keyup.enter="sendMessage"
                    :disabled="sending"
                    type="text"
                    placeholder="Type your message..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                  />
                  <button
                    @click="sendMessage"
                    :disabled="sending || !messageBody.trim()"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all flex items-center gap-2"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span v-if="!sending">Send</span>
                    <span v-else>Sending...</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted, nextTick } from "vue";
import axios from "axios";
import { io } from "socket.io-client";
import { useAuthStore } from "../stores/auth";

let socket = null;

export default {
  name: "ChatApp",
  setup() {
    // State
    const chats = ref([]);
    const messages = ref([]);
    const selectedChat = ref(null);
    const loadingChats = ref(false);
    const loadingMessages = ref(false);
    const isInitialLoad = ref(false);
    const messageBody = ref("");
    const sending = ref(false);
    const cursor = ref(null);
    const hasMore = ref(true);
    const messageContainer = ref(null);
    const messageIds = ref(new Set());

    const auth = useAuthStore();
    const currentUser = computed(() => auth.user);

    // Load chats from API
    const loadChats = async () => {
      try {
        loadingChats.value = true;
        const { data } = await axios.get("/chats");
        chats.value = data.data || [];
      } catch (error) {
        console.error("Error loading chats:", error);
      } finally {
        loadingChats.value = false;
      }
    };

    // Load messages for selected chat
    const loadMessages = async (initial = false) => {
      if (!selectedChat.value) return;
      if (!initial && !hasMore.value) return;

      try {
        if (initial) {
          isInitialLoad.value = true;
          messages.value = [];
          messageIds.value.clear();
          cursor.value = null;
          hasMore.value = true;
        } else {
          loadingMessages.value = true;
        }

        const { data } = await axios.get(
          `/chats/${selectedChat.value.id}/messages`,
          { params: { cursor: cursor.value } }
        );

        const newMessages = data.data || [];

        if (initial) {
          // For initial load, set messages directly
          messages.value = newMessages;
          newMessages.forEach(msg => messageIds.value.add(msg.id));
        } else {
          // For pagination, prepend messages
          const uniqueMessages = newMessages.filter(msg => !messageIds.value.has(msg.id));
          messages.value = [...uniqueMessages, ...messages.value];
          uniqueMessages.forEach(msg => messageIds.value.add(msg.id));
        }

        cursor.value = data.next_cursor || null;
        hasMore.value = !!data.next_cursor;

        // Mark chat as read
        if (socket && socket.connected) {
          socket.emit("chat:read", { chat_id: selectedChat.value.id });
        }

        // Scroll to bottom on initial load
        if (initial) {
          await nextTick();
          scrollToBottom();
        }
      } catch (error) {
        console.error("Error loading messages:", error);
      } finally {
        loadingMessages.value = false;
        isInitialLoad.value = false;
      }
    };

    // Select a chat
    const selectChat = async (chat) => {
      // Leave previous chat room
      if (selectedChat.value && socket && socket.connected) {
        socket.emit("chat:leave", selectedChat.value.id);
      }

      selectedChat.value = chat;

      // Reset unread count
      chat.unread_count = 0;

      // Load messages
      await loadMessages(true);

      // Join new chat room
      if (socket && socket.connected) {
        socket.emit("chat:join", chat.id);
      }
    };

    // Send message
    const sendMessage = async () => {
      if (!messageBody.value.trim() || !selectedChat.value || sending.value) {
        return;
      }

      const body = messageBody.value.trim();
      messageBody.value = "";
      sending.value = true;

      try {
        const { data } = await axios.post(
          `/chats/${selectedChat.value.id}/messages`,
          { body }
        );

        const newMessage = data.data;

        // Add message if not already in list
        if (!messageIds.value.has(newMessage.id)) {
          messages.value.push(newMessage);
          messageIds.value.add(newMessage.id);

          // Emit to socket
          if (socket && socket.connected) {
            socket.emit("message:send", newMessage);
          }

          // Update chat list
          const chat = chats.value.find(c => c.id === selectedChat.value.id);
          if (chat) {
            chat.latest_message = newMessage;
            chat.last_message_at = newMessage.created_at;
          }

          await nextTick();
          scrollToBottom();
        }
      } catch (error) {
        console.error("Error sending message:", error);
        messageBody.value = body; // Restore message on error
      } finally {
        sending.value = false;
      }
    };

    // Socket setup
    const initializeSocket = () => {
      const socketUrl = import.meta.env.VITE_SOCKET_URL || 'http://localhost:3000';

      socket = io(socketUrl, {
        auth: {
          token: localStorage.getItem("token"),
        },
        transports: ['polling', 'websocket'],
        reconnection: true,
        reconnectionDelay: 1000,
        reconnectionAttempts: 5,
        timeout: 10000
      });

      socket.on("connect", () => {
        console.log("✅ Socket connected successfully");
        if (selectedChat.value) {
          socket.emit("chat:join", selectedChat.value.id);
        }
      });

      socket.on("connect_error", (error) => {
        console.warn("⚠️ Socket connection error:", error.message);
        console.log("💡 App will continue working without real-time updates");
      });

      socket.on("disconnect", (reason) => {
        console.log("🔌 Socket disconnected:", reason);
      });

      socket.on("message:new", (msg) => {
        if (msg.chat_id === selectedChat.value?.id) {
          if (!messageIds.value.has(msg.id)) {
            messages.value.push(msg);
            messageIds.value.add(msg.id);

            nextTick(() => {
              scrollToBottom();
            });

            socket.emit("chat:read", { chat_id: msg.chat_id });
          }
        }

        const chat = chats.value.find(c => c.id === msg.chat_id);
        if (chat) {
          chat.latest_message = msg;
          chat.last_message_at = msg.created_at;

          if (msg.chat_id !== selectedChat.value?.id && msg.sender_id !== currentUser.value?.id) {
            chat.unread_count = (chat.unread_count || 0) + 1;
          }

          const index = chats.value.indexOf(chat);
          if (index > 0) {
            chats.value.splice(index, 1);
            chats.value.unshift(chat);
          }
        }
      });

      socket.on("chat:updated", (data) => {
        const chat = chats.value.find(c => c.id === data.chat_id);
        if (chat) {
          Object.assign(chat, data);
        }
      });
    };

    // Handle scroll for pagination
    const handleScroll = (e) => {
      if (e.target.scrollTop === 0 && hasMore.value && !loadingMessages.value) {
        const prevHeight = e.target.scrollHeight;
        loadMessages(false).then(() => {
          nextTick(() => {
            const newHeight = e.target.scrollHeight;
            e.target.scrollTop = newHeight - prevHeight;
          });
        });
      }
    };

    // Scroll to bottom
    const scrollToBottom = () => {
      if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
      }
    };

    // Format time
    const formatTime = (timestamp) => {
      if (!timestamp) return "";
      const date = new Date(timestamp);
      const now = new Date();
      const diff = now - date;

      if (diff < 60000) return "Just now";
      if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
      if (diff < 86400000) return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      if (diff < 604800000) return date.toLocaleDateString([], { weekday: 'short' });
      return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
    };

    const formatMessageTime = (timestamp) => {
      if (!timestamp) return "";
      return new Date(timestamp).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
      });
    };

    // Get initials for avatar
    const getInitials = (name) => {
      if (!name) return "?";
      const parts = name.trim().split(" ");
      if (parts.length >= 2) {
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
      }
      return name.substring(0, 2).toUpperCase();
    };

    // Lifecycle
    onMounted(() => {
      loadChats();

      if (import.meta.env.VITE_SOCKET_URL) {
        initializeSocket();
      } else {
        console.warn("⚠️ Socket URL not configured. Real-time features disabled.");
        console.log("💡 Set VITE_SOCKET_URL in your .env file to enable real-time chat");
      }
    });

    onUnmounted(() => {
      if (socket) {
        socket.off("message:new");
        socket.off("chat:updated");
        socket.disconnect();
        socket = null;
      }
    });

    return {
      chats,
      messages,
      selectedChat,
      loadingChats,
      loadingMessages,
      isInitialLoad,
      messageBody,
      sending,
      currentUser,
      messageContainer,
      selectChat,
      sendMessage,
      handleScroll,
      formatTime,
      formatMessageTime,
      getInitials,
    };
  },
};
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
  transition: background 0.2s;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.overflow-y-auto {
  overflow-y: auto;
  overflow-x: hidden;
  -webkit-overflow-scrolling: touch;
}

[ref="messageContainer"] {
  display: flex;
  flex-direction: column;
  overflow-y: auto !important;
  overflow-x: hidden;
}
</style>
