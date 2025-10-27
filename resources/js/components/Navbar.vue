<template>
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
              class="border-b-2 text-sm font-medium inline-flex items-center px-1 pt-1"
              :class="$route.path === '/' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
            >
              Home
            </router-link>
            <router-link
              to="/courses"
              class="border-b-2 text-sm font-medium inline-flex items-center px-1 pt-1"
              :class="$route.path === '/courses' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
            >
              Courses
            </router-link>
            <router-link
              to="/pricing"
              class="border-b-2 text-sm font-medium inline-flex items-center px-1 pt-1"
              :class="$route.path === '/pricing' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
            >
              Pricing
            </router-link>
             <router-link
              to="/subscriptions"
              class="border-b-2 text-sm font-medium inline-flex items-center px-1 pt-1"
              :class="$route.path === '/pricing' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
            >
              My Subscriptions
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
              My Courses
            </router-link>
            <router-link
              to="/certificates"
              class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium"
            >
              Certificates
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
</template>

<script>
import { computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

export default {
  name: 'Navbar',
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
