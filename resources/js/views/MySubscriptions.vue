<template>
  <div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-6">My Subscriptions</h1>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div v-if="subs.length === 0" class="text-gray-600">No subscriptions yet.</div>
      <div v-else class="space-y-4">
        <div v-for="s in subs" :key="s.id" class="bg-white rounded shadow p-4 flex items-center justify-between">
          <div>
            <div class="font-semibold">{{ s.plan?.name }}</div>
            <div class="text-sm text-gray-600">Status: {{ s.status }} · Ends: {{ s.ends_at || '—' }}</div>
          </div>
          <router-link :to="`/pricing/${s.plan?.slug}`" class="text-indigo-600 text-sm">View plan</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const subs = ref([]);
const loading = ref(false);

onMounted(async () => {
  loading.value = true;
  try {
    const { data } = await axios.get('/subscriptions');
    subs.value = data.data || [];
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
</style>


