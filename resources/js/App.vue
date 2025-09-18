<template>
    <div id="app">
      <!-- Navigation -->
      <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <router-link to="/" class="text-xl font-bold text-indigo-600">
                  EduAcademy
                </router-link>
              </div>
              <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                <router-link 
                  to="/" 
                  class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                  active-class="border-indigo-500"
                  exact
                >
                  Home
                </router-link>
                <router-link 
                  to="/courses" 
                  class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                  active-class="border-indigo-500 text-gray-900"
                >
                  Courses
                </router-link>
              </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
              <template v-if="!isAuthenticated">
                <router-link 
                  to="/login" 
                  class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium"
                >
                  Log in
                </router-link>
                <router-link 
                  to="/register" 
                  class="ml-4 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700"
                >
                  Sign up
                </router-link>
              </template>
              <template v-else>
                <router-link 
                  to="/dashboard" 
                  class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium"
                >
                  Dashboard
                </router-link>
                <button 
                  @click="logout"
                  class="ml-4 text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium"
                >
                  Logout
                </button>
              </template>
            </div>
          </div>
        </div>
      </nav>
  
      <!-- Main Content -->
      <main class="flex-grow">
        <router-view />
      </main>
  
      <!-- Footer -->
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
  import { computed } from 'vue';
  import { useAuthStore } from './stores/auth';
  import { useRouter } from 'vue-router';
  
  export default {
    name: 'App',
    setup() {
      const authStore = useAuthStore();
      const router = useRouter();
  
      const isAuthenticated = computed(() => authStore.isAuthenticated);
  
      const logout = async () => {
        await authStore.logout();
        router.push('/login');
      };
  
      return {
        isAuthenticated,
        logout
      };
    }
  };
  </script>
  
  <style>
  /* Add any global styles here */
  </style>