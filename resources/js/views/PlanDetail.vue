<template>
  <div class="max-w-6xl mx-auto py-10 px-4">
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="mb-6">
        <h1 class="text-3xl font-bold">{{ plan.name }}</h1>
        <p class="text-gray-600 mt-2">{{ plan.description }}</p>
        <div class="mt-3 text-xl font-semibold">
          {{ currency(plan.currency) }}{{ Number(plan.price).toFixed(2) }} · Every {{ plan.interval_count }} {{ plan.interval }}<span v-if="plan.interval_count>1">s</span>
        </div>
        <button @click="subscribe(plan)" class="mt-4 inline-flex items-center px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Subscribe</button>
      </div>

      <h2 class="text-2xl font-semibold mb-3">Courses included</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="course in plan.courses" :key="course.id" class="bg-white p-4 rounded shadow">
          <div class="h-36 overflow-hidden rounded">
            <img :src="course.image_url || course.image || placeholder(course.title)" class="w-full h-full object-cover" />
          </div>
          <div class="mt-3 font-medium">{{ course.title }}</div>
          <router-link :to="`/courses/${course.slug}`" class="text-indigo-600 text-sm">View course</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const plan = ref({ courses: [] });
const loading = ref(false);

const currency = (code) => ({ USD: '$', EUR: '€', INR: '₹' }[code] || '');
const placeholder = (t) => `https://via.placeholder.com/400x300/6366f1/ffffff?text=${encodeURIComponent(t?.slice(0,20)||'Course')}`;

const subscribe = async (p) => {
  try {
    await axios.post('/subscriptions/subscribe', { plan_id: p.id });
    alert('Subscribed!');
  } catch (e) {
    alert(e?.response?.data?.message || 'Failed to subscribe');
  }
};

onMounted(async () => {
  loading.value = true;
  try {
    const { data } = await axios.get(`/plans/${route.params.slug}`);
    plan.value = data;
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
</style>


