<template>
  <div id="app">
    <Navbar />
    <main class="flex-grow">
      <router-view />
    </main>
    <footer class="bg-white border-t border-gray-200 mt-12">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-gray-500 text-sm">
          &copy; 2025 EduAcademy. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue';

export default {
  name: 'App',
  components: { Navbar },
};
</script>

<style>
/* Add global styles here */
</style>
