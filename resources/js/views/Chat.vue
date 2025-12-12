<template>
  <div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Chat</h1>
      <p class="text-sm text-gray-600">Chat with your instructors about enrolled courses.</p>
    </div>

    <div class="bg-white shadow rounded-lg border border-gray-200 grid grid-cols-1 md:grid-cols-3">
      <div class="border-b md:border-b-0 md:border-r border-gray-200 max-h-[70vh] overflow-y-auto">
        <div v-if="loadingChats" class="p-4 text-sm text-gray-500">Loading chats...</div>
        <div v-else-if="chats.length === 0" class="p-4 text-sm text-gray-500">
          No chats yet. Start a conversation from a course page.
        </div>
        <ul v-else class="divide-y divide-gray-100">
          <li
            v-for="chat in chats"
            :key="chat.id"
            @click="selectChat(chat)"
            class="p-4 cursor-pointer hover:bg-indigo-50"
            :class="selectedChat?.id === chat.id ? 'bg-indigo-50' : ''"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ chat.course?.title || 'Course' }}
                </p>
                <p class="text-xs text-gray-500">
                  {{ chat.student?.name }} → {{ chat.instructor?.name }}
                </p>
              </div>
              <div class="flex items-center space-x-2">
                <span
                  v-if="chat.unread_count > 0"
                  class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700"
                >
                  {{ chat.unread_count }}
                </span>
                <span class="text-[10px] text-gray-400">{{ formatTime(chat.last_message_at) }}</span>
              </div>
            </div>
            <p class="text-xs text-gray-600 mt-1 line-clamp-1">
              {{ chat.latest_message?.body || 'No messages yet' }}
            </p>
          </li>
        </ul>
      </div>

      <div class="md:col-span-2 flex flex-col max-h-[70vh]">
        <div v-if="!selectedChat" class="p-6 text-sm text-gray-500">
          Select a chat to view messages.
        </div>

        <div v-else class="flex flex-col h-full">
          <div class="border-b border-gray-200 px-6 py-4">
            <p class="text-sm font-semibold text-gray-900">{{ selectedChat.course?.title }}</p>
            <p class="text-xs text-gray-600">Instructor: {{ selectedChat.instructor?.name }}</p>
          </div>

          <div class="flex-1 overflow-y-auto px-6 py-4 space-y-3 bg-gray-50">
            <div v-if="loadingMessages" class="text-sm text-gray-500"></div>
            <div v-else-if="messages.length === 0" class="text-sm text-gray-500">No messages yet.</div>

            <div
              v-for="message in messages"
              :key="message.id"
              class="flex"
              :class="message.sender_id === currentUser?.id ? 'justify-end' : 'justify-start'"
            >
              <div
                class="max-w-[70%] rounded-xl px-3 py-2 text-sm shadow-sm"
                :class="message.sender_id === currentUser?.id ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-900'"
              >
                <p>{{ message.body }}</p>
                <p class="text-[10px] mt-1" :class="message.sender_id === currentUser?.id ? 'text-indigo-100' : 'text-gray-400'">
                  {{ formatTime(message.created_at) }}
                  <span v-if="message.read_at" class="ml-1">• Read</span>
                </p>
              </div>
            </div>
          </div>

          <div class="border-t border-gray-200 p-4 flex items-center space-x-3">
            <input
              v-model="messageBody"
              @keyup.enter="sendMessage"
              class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
              type="text"
              placeholder="Type your message..."
              :disabled="sending"
            />
            <button
              @click="sendMessage"
              :disabled="sending || !messageBody.trim()"
              class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ sending ? 'Sending...' : 'Send' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted, onUnmounted, ref } from "vue";
import axios from "axios";
import { useAuthStore } from "../stores/auth";

export default {
  name: "Chat",
  props: {
    chatId: {
      type: [String, Number],
      default: null,
    },
  },
  setup(props) {
    const chats = ref([]);
    const loadingChats = ref(true);
    const messages = ref([]);
    const loadingMessages = ref(false);
    const selectedChat = ref(null);
    const messageBody = ref("");
    const sending = ref(false);
    const pollTimer = ref(null);

    const auth = useAuthStore();
    const currentUser = computed(() => auth.user);

    const loadChats = async () => {
      loadingChats.value = true;
      try {
        const { data } = await axios.get("/chats");
        chats.value = data.data || [];

        if (selectedChat.value) {
          const updated = chats.value.find((c) => c.id === selectedChat.value.id);
          if (updated) selectedChat.value = updated;
        } else if (chats.value.length) {
          const preselect = props.chatId
            ? chats.value.find((c) => String(c.id) === String(props.chatId))
            : chats.value[0];
          if (preselect) selectChat(preselect);
        }
      } catch (error) {
        console.error("Failed to load chats", error);
      } finally {
        loadingChats.value = false;
      }
    };

    const loadMessages = async () => {
      if (!selectedChat.value) return;
      loadingMessages.value = true;
      try {
        const { data } = await axios.get(`/chats/${selectedChat.value.id}/messages`);
        messages.value = data.data || [];
        updateUnreadCount(selectedChat.value.id, 0);
      } catch (error) {
        console.error("Failed to load messages", error);
      } finally {
        loadingMessages.value = false;
      }
    };

    const selectChat = (chat) => {
      selectedChat.value = chat;
      loadMessages();
      startPolling();
    };

    const sendMessage = async () => {
      if (!messageBody.value.trim() || !selectedChat.value) return;
      sending.value = true;
      try {
        const { data } = await axios.post(`/chats/${selectedChat.value.id}/messages`, {
          body: messageBody.value.trim(),
        });
        messages.value.push(data.data);
        messageBody.value = "";
        await loadChats();
      } catch (error) {
        console.error("Failed to send message", error);
      } finally {
        sending.value = false;
      }
    };

    const startPolling = () => {
      stopPolling();
      pollTimer.value = setInterval(() => loadMessages(), 8000);
    };

    const stopPolling = () => {
      if (pollTimer.value) {
        clearInterval(pollTimer.value);
        pollTimer.value = null;
      }
    };

    const updateUnreadCount = (chatId, count) => {
      const chat = chats.value.find((c) => c.id === chatId);
      if (chat) chat.unread_count = count;
    };

    const formatTime = (value) => {
      if (!value) return "";
      const date = new Date(value);
      return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    };

    onMounted(() => {
      loadChats();
      startPolling();
    });

    onUnmounted(() => {
      stopPolling();
    });

    return {
      chats,
      loadingChats,
      messages,
      loadingMessages,
      selectedChat,
      messageBody,
      sending,
      currentUser,
      selectChat,
      sendMessage,
      formatTime,
    };
  },
};
</script>
