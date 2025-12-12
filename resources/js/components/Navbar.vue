<template>
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Left Side: Logo & Navigation Links -->
        <div class="flex items-center">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <a @click.prevent="goto('home')" class="flex items-center space-x-2 cursor-pointer group">
              <div class="h-10 w-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center transform group-hover:scale-105 transition-transform duration-200">
                <span class="text-white font-bold text-xl">E</span>
              </div>
              <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                EduAcademy
              </span>
            </a>
          </div>

          <!-- Desktop Navigation Links -->
          <div class="hidden md:ml-10 md:flex md:space-x-1">    

            <a @click.prevent="goto('courses')" class="nav-link">
              <!-- svg omitted for brevity (keeps original icons intact) -->
              Courses
            </a>
            <a @click.prevent="goto('pricing')" class="nav-link">Pricing</a>
            <a @click.prevent="goto('subscriptions')" class="nav-link">Subscriptions</a>

            <!-- Become Instructor / Instructor Dashboard Link -->
            <!-- Show Become Instructor only to students (or unauthenticated) - never to admins -->
            <router-link
              v-if="showBecomeInstructor"
              to="/become-instructor"
              class="nav-link bg-indigo-600 text-white hover:bg-indigo-700"
              style="color: white;"
            >
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              Become Instructor
            </router-link>

            <!-- Instructor Dashboard (only for approved instructors) -->
            <a
              v-else-if="showInstructorDashboard"
              @click.prevent="navigateDirectly('/instructor')"
              class="nav-link bg-green-600 text-white hover:bg-green-700"
            >
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              Instructor Dashboard
            </a>

            <!-- Pending state (instructor applied, awaiting approval) -->
            <span
              v-else-if="showInstructorPending"
              class="nav-link bg-yellow-500 text-white"
            >
              Instructor Application Pending
            </span>

          </div>
        </div>

        <!-- Right Side: Auth Buttons / User Menu -->
        <div class="flex items-center space-x-3">
          <!-- Not Authenticated -->
          <template v-if="!isAuthenticated">
            <div class="auth-buttons">
              <router-link to="/login" class="auth-btn login-btn">Login</router-link>
              <router-link to="/register" class="auth-btn register-btn">Get Started</router-link>
            </div>
          </template>

          <!-- Authenticated -->
          <template v-else>
            <!-- User Dropdown -->
            <div class="relative" ref="dropdownRef">
              <button
                @click="toggleDropdown"
                class="flex items-center space-x-3 p-1.5 pr-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 group"
              >
                <!-- Avatar -->
                <div class="h-9 w-9 rounded-full overflow-hidden ring-2 ring-gray-200 group-hover:ring-indigo-500 transition-all duration-200">
                  <img
                    v-if="profilePictureUrl"
                    :src="profilePictureUrl"
                    :alt="user?.name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="h-full w-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                      {{ user?.name?.charAt(0)?.toUpperCase() || '?' }}
                    </span>
                  </div>
                </div>

                <!-- User Name & Role (Hidden on mobile) -->
                <div class="hidden lg:block text-left">
                  <div class="text-sm font-medium text-gray-700 leading-none">
                    {{ user?.name }}
                  </div>
                  <div class="text-xs text-gray-500 mt-0.5 capitalize">
                    {{ displayRole }}
                  </div>
                </div>

                <!-- Chevron Icon -->
                <svg
                  class="w-4 h-4 text-gray-500 transition-transform duration-200"
                  :class="{ 'rotate-180': isDropdownOpen }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div
                  v-if="isDropdownOpen"
                  class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden"
                >
                  <!-- User Info Section -->
                  <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ user?.name }}</p>
                    <p class="text-xs text-gray-600 truncate">{{ user?.email }}</p>
                  </div>

                  <!-- Menu Items -->
                  <div class="py-1">
                    <a @click.prevent="handleMenuClick('dashboard')" class="dropdown-item">
                      My Courses
                    </a>

                    <a @click.prevent="handleMenuClick('chat')" class="dropdown-item">
                      Messages
                    </a>

                    <a @click.prevent="handleMenuClick('certificates')" class="dropdown-item">
                      Certificates
                    </a>

                    <a @click.prevent="handleMenuClick('profile')" class="dropdown-item">
                      Profile Settings
                    </a>
                  </div>

                  <!-- Logout Section -->
                  <div class="border-t border-gray-100">
                    <button @click="handleLogout" class="dropdown-item text-red-600 hover:bg-red-50">
                      Sign Out
                    </button>
                  </div>
                </div>
              </transition>
            </div>
          </template>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="md:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 -translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-1"
    >
      <div v-if="isMobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white">
        <div class="px-4 pt-2 pb-3 space-y-1">
          <a @click.prevent="handleMobileClick('home')" class="mobile-nav-link">Home</a>
          <a @click.prevent="handleMobileClick('courses')" class="mobile-nav-link">Courses</a>
          <a @click.prevent="handleMobileClick('pricing')" class="mobile-nav-link">Pricing</a>
          <a @click.prevent="handleMobileClick('subscriptions')" class="mobile-nav-link">Subscriptions</a>
          <a @click.prevent="handleMobileClick('chat')" class="mobile-nav-link">Messages</a>

          <!-- Mobile: same role-aware logic -->
          <router-link
            v-if="showBecomeInstructor"
            to="/become-instructor"
            class="mobile-nav-link bg-indigo-600 text-white hover:bg-indigo-700"
            @click="closeMobileMenu"
          >
            Become Instructor
          </router-link>

          <a
            v-else-if="showInstructorDashboard"
            @click.prevent="navigateDirectly('/instructor'); closeMobileMenu()"
            class="mobile-nav-link bg-green-600 text-white hover:bg-green-700"
          >
            Instructor Dashboard
          </a>

          <span v-else-if="showInstructorPending" class="mobile-nav-link bg-yellow-500 text-white">
            Instructor Application Pending
          </span>

          <template v-if="!isAuthenticated">
            <div class="pt-4 space-y-2">
              <a @click="navigateDirectly('/login')" class="block w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 cursor-pointer">
                Log in
              </a>
              <a @click="navigateDirectly('/register')" class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg cursor-pointer">
                Get Started
              </a>
            </div>
          </template>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { useMaskedNavigation } from "../utils/navigation";

export default {
  setup() {
    const router = useRouter();
    const auth = useAuthStore();
    const { goto } = useMaskedNavigation();

    const isAuthenticated = computed(() => auth.isAuthenticated);
    const user = computed(() => auth.user || null);

    // Role helpers
    const isAdmin = computed(() => user.value?.role === 'admin');
    const isStudent = computed(() => user.value?.role === 'student' || !user.value?.role);
    const isInstructor = computed(() => user.value?.role === 'instructor');

    // Status helpers
    const isInstructorApproved = computed(() => isInstructor.value && user.value?.instructor_status === 'approved');
    const isInstructorPending = computed(() => isInstructor.value && user.value?.instructor_status === 'pending');

    // Derived UI flags
    const showInstructorDashboard = computed(() => isAuthenticated.value && isInstructorApproved.value);
    // show Become Instructor to:
    // - unauthenticated users (so they can sign up and apply)
    // - authenticated students who are not approved
    // Do NOT show to admins or already-approved instructors.
    const showBecomeInstructor = computed(() => {
      if (!isAuthenticated.value) return true;
      if (isAdmin.value) return false;
      if (isStudent.value && user.value?.instructor_status !== 'approved') return true;
      return false;
    });
    const showInstructorPending = computed(() => isAuthenticated.value && isInstructorPending.value);

    const profilePictureUrl = computed(() => {
      const p = user.value?.profile_picture;
      return p ? (p.startsWith("http") ? p : `/storage/${p}`) : null;
    });

    const displayRole = computed(() => {
      // Normalize and display a friendly role string
      if (!user.value?.role) return 'Student';
      return user.value.role.toString().replace(/_/g, ' ').toLowerCase();
    });

    // Dropdown
    const dropdownRef = ref(null);
    const isDropdownOpen = ref(false);
    const toggleDropdown = () => isDropdownOpen.value = !isDropdownOpen.value;
    const closeDropdown = () => isDropdownOpen.value = false;

    // Mobile Menu
    const isMobileMenuOpen = ref(false);
    const toggleMobileMenu = () => isMobileMenuOpen.value = !isMobileMenuOpen.value;
    const closeMobileMenu = () => isMobileMenuOpen.value = false;

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

    const handleMenuClick = (page) => {
      closeDropdown();
      goto(page);
    };

    const handleMobileClick = (page) => {
      closeMobileMenu();
      goto(page);
    };

    const navigateDirectly = (path) => {
      window.location.href = path;
    };

    return {
      goto,
      isAuthenticated,
      user,
      isInstructorApproved,
      profilePictureUrl,
      dropdownRef,
      isDropdownOpen,
      toggleDropdown,
      closeDropdown,
      handleLogout,
      handleMenuClick,
      isMobileMenuOpen,
      toggleMobileMenu,
      closeMobileMenu,
      handleMobileClick,
      navigateDirectly,

      // expose computed UI flags
      showBecomeInstructor,
      showInstructorDashboard,
      showInstructorPending,
      displayRole,
    };
  }
};
</script>

<style scoped>
.nav-link {
  @apply flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 cursor-pointer;
}

.dropdown-item {
  @apply flex items-center space-x-3 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 cursor-pointer;
}

.mobile-nav-link {
  @apply block px-3 py-2 text-base font-medium text-gray-700 rounded-lg hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 cursor-pointer;
}
.auth-buttons {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.auth-btn {
  padding: 0.6rem 1.3rem;
  border-radius: 6px;
  font-size: 0.95rem;
  text-decoration: none;
  font-weight: 600;
  transition: 0.2s ease;
}

/* login button */
.login-btn {
  background: transparent;
  border: 2px solid #4f46e5;
  color: #4f46e5;
}
.login-btn:hover {
  background: #4f46e5;
  color: #fff;
}

/* register button */
.register-btn {
  background: #4f46e5;
  color: #fff;
}
.register-btn:hover {
  background: #4338ca;
}
</style>
