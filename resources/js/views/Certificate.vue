<template>
  <div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Certificate</h1>
    <div class="space-y-4">
      <input v-model.number="courseId" type="number" class="border p-2 rounded w-full" placeholder="Enter Course ID" />
      <button @click="download" class="bg-indigo-600 text-white px-4 py-2 rounded">Download Certificate</button>
      <div v-if="error" class="text-red-600">{{ error }}</div>
    </div>
  </div>
</template>

<script setup>  
import { ref } from 'vue';
import axios from 'axios';

const courseId = ref(null);
const error = ref(null);

const download = async () => {
  error.value = null;
  if (!courseId.value) {
    error.value = 'Course ID required';
    return;
  }
  const token = localStorage.getItem('auth_token');
  try {
    const response = await axios.get(`/certificate/download/${courseId.value}`, {
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/pdf' },
      responseType: 'blob',
    });
    const blob = new Blob([response.data], { type: 'application/pdf' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `certificate_${courseId.value}.pdf`;
    link.click();
  } catch (e) {
    error.value = e.response?.data?.message || 'Download failed';
  }
};
</script>

<style scoped>
</style>


