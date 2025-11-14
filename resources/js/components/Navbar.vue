<template>
  <nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">

        <!-- Logo -->
        <div class="flex items-center space-x-8">
          <span @click.prevent="goto('home')" class="text-2xl font-bold text-indigo-600 cursor-pointer">
            EduAcademy
          </span>

          <!-- Desktop Nav Items -->
          <div class="hidden sm:flex space-x-6">
            <span @click.prevent="goto('home')" class="nav-item">Home</span>
            <span @click.prevent="goto('courses')" class="nav-item">Courses</span>
            <span @click.prevent="goto('pricing')" class="nav-item">Pricing</span>
            <span @click.prevent="goto('subscriptions')" class="nav-item">Subscriptions</span>
          </div>
        </div>

        <!-- Right Side -->
        <div class="hidden sm:flex items-center space-x-4">

          <!-- Guest -->
          <template v-if="!isAuthenticated">
  <button type="button" @click="gotoLogin" class="btn-ghost">Log in</button>
  <button type="button" @click="gotoRegister" class="btn-primary">Sign up</button>
</template>


          <!-- Logged In -->
          <template v-else>
            <div class="relative flex items-center space-x-2" ref="dropdownRef">

              <div class="h-10 w-10 rounded-full overflow-hidden border border-indigo-500">
                <img
                  v-if="profilePictureUrl"
                  :src="profilePictureUrl"
                  class="h-full w-full object-cover"
                />
                <div v-else class="avatar-fallback">
                  {{ user?.name?.charAt(0).toUpperCase() }}
                </div>
              </div>

              <button @click="toggleDropdown" class="dropdown-toggle">▼</button>

              <transition name="fade">
                <div
                  v-if="isDropdownOpen"
                  class="dropdown-menu"
                >
                  <span @click.prevent="goto('dashboard')" class="dropdown-item">My Courses</span>
                  <span @click.prevent="goto('certificates')" class="dropdown-item">Certificates</span>
                  <span @click.prevent="goto('profile')" class="dropdown-item">Profile</span>

                  <div class="divider"></div>

                  <button @click="handleLogout" class="dropdown-item text-red-600">
                    Logout
                  </button>
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

// GLOBAL 20-char mask generator
const rand = (length = 20) => {
  return Array.from({ length }, () =>
    Math.random().toString(36)[2] || "x"
  ).join("");
};

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

    const saveMaskState = (mask, state) => {
      try {
        localStorage.setItem(
          STORAGE_PREFIX + mask,
          JSON.stringify({ ...state, ts: Date.now() })
        );
      } catch (e) {}
    };

    const goto = (page, params = {}) => {
      const mask = rand();
      saveMaskState(mask, { page, params });

      router.push({
        name: "MaskedPage",
        params: { mask },
        state: { page, params }
      });
    };

    // ---- CORRECT FIXED VERSION ----
    const gotoLogin = () => goto("login");
    const gotoRegister = () => goto("register");

    const dropdownRef = ref(null);
    const isDropdownOpen = ref(false);

    const toggleDropdown = () => (isDropdownOpen.value = !isDropdownOpen.value);
    const closeDropdown = () => (isDropdownOpen.value = false);

    const handleClickOutside = (e) => {
      if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        isDropdownOpen.value = false;
      }
    };

    onMounted(() => document.addEventListener("click", handleClickOutside));
    onUnmounted(() => document.removeEventListener("click", handleClickOutside));

    const handleLogout = async () => {
      closeDropdown();
      await auth.logout();
      goto("login");
    };

    return {
      goto,
      gotoLogin,
      gotoRegister,
      isAuthenticated,
      user,
      profilePictureUrl,
      dropdownRef,
      isDropdownOpen,
      toggleDropdown,
      closeDropdown,
      handleLogout
    };
  }
};
</script>



<style scoped>
.nav-item {
  @apply cursor-pointer text-gray-600 hover:text-indigo-600 font-medium transition;
}

.btn-ghost {
  @apply text-gray-700 hover:text-indigo-600 font-medium px-3 py-2 transition;
}

.btn-primary {
  @apply bg-indigo-600 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700 transition;
}

.avatar-fallback {
  @apply h-full w-full bg-indigo-600 flex items-center justify-center text-white text-lg;
}

.dropdown-toggle {
  @apply text-gray-600 hover:text-indigo-600 cursor-pointer transition;
}

.dropdown-menu {
  @apply absolute right-0 top-full mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50;
}

.dropdown-item {
  @apply block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer transition;
}

.divider {
  @apply border-t border-gray-200 my-2;
}

/* Smooth fade animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

</style>
