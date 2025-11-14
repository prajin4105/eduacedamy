<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
      <h1 class="text-6xl font-extrabold text-gray-900">404</h1>
      <p class="mt-4 text-gray-600">Page not found.</p>
      <a @click.prevent="goto('home')" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 cursor-pointer">Go Home</a>
    </div>
  </div>
</template>

<script setup>
import { useMaskedNavigation } from '../utils/navigation';

const { goto } = useMaskedNavigation();
</script>
