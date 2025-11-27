<template>
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
            <a @click.prevent="goto('home')" class="text-xl font-bold text-indigo-600 cursor-pointer">EduAcademy</a>
          </div>

          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <a @click.prevent="goto('home')" class="nav-link">Home</a>
            <a @click.prevent="goto('courses')" class="nav-link">Courses</a>
            <a @click.prevent="goto('pricing')" class="nav-link">Pricing</a>
            <a @click.prevent="goto('subscriptions')" class="nav-link">Subscriptions</a>
          </div>
        </div>

        <div class="hidden sm:flex sm:items-center">
          <template v-if="!isAuthenticated">
            <a @click.prevent="goto('login')" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Log in</a>
            <a @click.prevent="goto('register')" class="ml-4 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Sign up</a>
          </template>

          <template v-else>
            <div class="relative ml-3 flex items-center space-x-2" ref="dropdownRef">
              <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-indigo-500">
                <img v-if="profilePictureUrl" :src="profilePictureUrl" class="h-full w-full object-cover"/>
                <div v-else class="avatar-fallback">{{ user?.name?.charAt(0).toUpperCase() }}</div>
              </div>

              <button @click="toggleDropdown" class="dropdown-btn">▼</button>

              <transition name="fade">
                <div v-if="isDropdownOpen" class="dropdown-menu">
                  <a @click.prevent="goto('dashboard')" class="dropdown-item">My Courses</a>
                  <a @click.prevent="goto('certificates')" class="dropdown-item">Certificates</a>
                  <a @click.prevent="goto('profile')" class="dropdown-item">Profile</a>
                  <div class="divider"></div>
                  <button @click="handleLogout" class="dropdown-item text-red-600">Logout</button>
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
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const rand = () => Math.random().toString(36).substring(2, 10);
const STORAGE_PREFIX = "masked:";

export default {
  setup() {
    const router = useRouter();
    const auth = useAuthStore();

    const isAuthenticated = computed(() => auth.isAuthenticated);
    const user = computed(() => auth.user);

    const profilePictureUrl = computed(() => {
      const p = user.value?.profile_picture;
      return p ? (p.startsWith("http") ? p : `/storage/${p}`) : null;
    });

    // Save mask state: mask -> { page, params, timestamp }
    const saveMaskState = (mask, state) => {
      try {
        localStorage.setItem(STORAGE_PREFIX + mask, JSON.stringify({ ...state, ts: Date.now() }));
      } catch (e) { console.warn("Failed to save mask state", e); }
    };

    // Generic goto that saves a state and navigates to a new mask
    const goto = (page, params = {}) => {
      const mask = rand();
      // save for refresh
      saveMaskState(mask, { page, params });
      // use named route + params so router recognizes different route instances
      router.push({ name: "MaskedPage", params: { mask }, state: { page, params }});
    };

    // Example helpers used by template (could be expanded)
    const gotoLogin = () => goto("login", {});
    const gotoRegister = () => goto("register", {});

    // dropdown
    const dropdownRef = ref(null);
    const isDropdownOpen = ref(false);
    const toggleDropdown = () => isDropdownOpen.value = !isDropdownOpen.value;
    const closeDropdown = () => isDropdownOpen.value = false;
    const handleClickOutside = (e) => {
      if (dropdownRef.value && !dropdownRef.value.contains(e.target)) isDropdownOpen.value = false;
    };

    onMounted(() => document.addEventListener("click", handleClickOutside));
    onUnmounted(() => document.removeEventListener("click", handleClickOutside));

    const handleLogout = async () => {
      closeDropdown();
      await auth.logout();
      // after logout, send to login masked
      goto("login");
    };

    return {
      goto, gotoLogin, gotoRegister,
      isAuthenticated, user, profilePictureUrl,
      dropdownRef, isDropdownOpen, toggleDropdown, closeDropdown, handleLogout
    };
  }
};
</script>

<style scoped>
.nav-link {
  @apply cursor-pointer border-b-2 text-sm font-medium px-1 pt-1 text-gray-500 hover:text-gray-700 hover:border-gray-300;
}
.avatar-fallback {
  @apply h-full w-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-lg;
}
.dropdown-btn { @apply text-gray-600 hover:text-indigo-600 cursor-pointer; }
.dropdown-menu { @apply absolute right-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-xl z-50; }
.dropdown-item { @apply flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer; }
.divider { @apply border-t border-gray-100 my-1; }
</style>
