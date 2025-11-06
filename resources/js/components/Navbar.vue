<template>
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Left: Logo + Links -->
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
              :class="$route.path === '/subscriptions' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
            >
              My Subscriptions
            </router-link>
          </div>
        </div>

        <!-- Right: Auth Buttons / Profile -->
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
            <!-- Profile & Dropdown -->
            <div class="relative ml-3 flex items-center space-x-2" ref="dropdownRef">
              <!-- Profile Picture (non-clickable) -->
              <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-indigo-500">
                <img
                  v-if="profilePictureUrl"
                  :src="profilePictureUrl"
                  :alt="user?.name || 'User'"
                  class="h-full w-full object-cover"
                />
                <div
                  v-else
                  class="h-full w-full bg-indigo-600 flex items-center justify-center"
                >
                  <span class="text-white font-semibold text-lg">
                    {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
                  </span>
                </div>
              </div>

              <!-- Dropdown Arrow -->
              <button
                @click="toggleDropdown"
                class="text-gray-600 hover:text-indigo-600 focus:outline-none"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 transition-transform duration-200"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  :class="{ 'transform rotate-180': isDropdownOpen }"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <transition name="fade">
                <div
                  v-if="isDropdownOpen"
                  class="absolute right-0 top-full mt-2 w-56 bg-white shadow-xl rounded-lg border border-gray-100 z-50"
                >
                  <div class="py-2">
                    <router-link
                      to="/dashboard"
                      @click="closeDropdown"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                      </svg>
                      My Courses
                    </router-link>

                    <router-link
                      to="/certificates"
                      @click="closeDropdown"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                      </svg>
                      Certificates
                    </router-link>

                    <router-link
                      to="/profile"
                      @click="closeDropdown"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      Profile
                    </router-link>

                    <div class="border-t border-gray-100 my-1"></div>

                    <button
                      @click="handleLogout"
                      class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                    >
                      <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7" />
                      </svg>
                      Logout
                    </button>
                  </div>
                </div>
              </transition>
            </div>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

export default {
  name: 'Navbar',
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const user = computed(() => authStore.user);
    const isDropdownOpen = ref(false);
    const dropdownRef = ref(null);

    const getProfilePictureUrl = (path) => {
      if (!path) return null;
      if (path.startsWith('http')) return path;
      return `/storage/${path}`;
    };

    const profilePictureUrl = computed(() => {
      return user.value?.profile_picture
        ? getProfilePictureUrl(user.value.profile_picture)
        : null;
    });

    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value;
    };

    const closeDropdown = () => {
      isDropdownOpen.value = false;
    };

    const handleLogout = async () => {
      closeDropdown();
      await authStore.logout();
      router.push('/login');
    };

    const handleClickOutside = (event) => {
      if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isDropdownOpen.value = false;
      }
    };

    onMounted(() => {
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      isAuthenticated,
      user,
      profilePictureUrl,
      isDropdownOpen,
      dropdownRef,
      toggleDropdown,
      closeDropdown,
      handleLogout
    };
  }
};
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
