<template>
  <div class="min-h-screen bg-gray-50">
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
            <div v-if="user">
              <span class="text-gray-700">Welcome, {{ user.name }}</span>
              <button
                @click="logout"
                class="ml-4 text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium"
              >
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>


    <!-- Hero Section -->
    <div class="relative bg-indigo-700 overflow-hidden">
      <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-indigo-700 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
          <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                <span class="block xl:inline">Learn new skills with</span>
                <span class="block text-indigo-200 xl:inline">EduAcademy</span>
              </h1>
              <p class="mt-3 text-base text-indigo-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                Access 1000+ online courses from top instructors. Build skills with courses, certificates, and degrees online from world-class universities and companies.
              </p>
              <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                <div class="rounded-md shadow">
                  <router-link to="/courses" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                    Browse Courses
                  </router-link>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-3">
                  <router-link v-if="!user" to="/login" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                    Get Started
                  </router-link>
                  <router-link v-else-if="user.role === 'student'" to="/dashboard" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                    My Dashboard
                  </router-link>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
      <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="Students learning">
      </div>
    </div>

    <!-- Popular Courses -->
    <div class="py-12 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Courses</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            Popular Courses
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
            Start learning from the world's best instructors.
          </p>
        </div>

        <div class="mt-10">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Course cards will be dynamically loaded here -->
            <div v-for="course in featuredCourses" :key="course.id" class="flex flex-col rounded-lg shadow-lg overflow-hidden">
              <div class="flex-shrink-0">
                <img class="h-48 w-full object-cover" :src="course.image || 'https://via.placeholder.com/400x225'" :alt="course.title">
              </div>
              <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                <div class="flex-1">
                  <p class="text-sm font-medium text-indigo-600">
                    {{ course.category || 'General' }}
                  </p>
                  <router-link :to="`/courses/${course.slug}`" class="block mt-2">
                    <p class="text-xl font-semibold text-gray-900">{{ course.title }}</p>
                    <p class="mt-3 text-base text-gray-500">{{ course.excerpt || 'Learn from industry experts with this comprehensive course.' }}</p>
                  </router-link>
                </div>
                <div class="mt-6 flex items-center justify-between">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <span class="sr-only">{{ course.instructor?.name || 'Instructor' }}</span>
                      <img class="h-10 w-10 rounded-full" :src="course.instructor?.avatar || 'https://ui-avatars.com/api/?name=' + (course.instructor?.name || 'Instructor')" alt="">
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">
                        {{ course.instructor?.name || 'Instructor' }}
                      </p>
                      <div class="flex space-x-1 text-sm text-gray-500">
                        <span class="text-lg font-bold text-green-600">
                          ${{ course.price || '99.99' }}
                        </span>
                        <span aria-hidden="true">
                          &middot;
                        </span>
                        <span>
                          {{ course.level || 'All Levels' }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div>
                    <button
                      v-if="user && user.role === 'student'"
                      @click="buyCourse(course)"
                      :disabled="isEnrolled(course.id)"
                      :class="isEnrolled(course.id) ? 'bg-green-100 text-green-800' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
                      class="px-4 py-2 rounded-lg font-medium"
                    >
                      {{ isEnrolled(course.id) ? 'Enrolled' : 'Enroll Now' }}
                    </button>
                    <router-link v-else to="/login" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium">
                      Enroll now
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            A better way to learn
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
            Get access to high-quality courses taught by industry experts.
          </p>
        </div>

        <div class="mt-10">
          <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
            <div v-for="feature in features" :key="feature.name" class="relative">
              <dt>
                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                  <component :is="feature.icon" class="h-6 w-6" aria-hidden="true" />
                </div>
                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ feature.name }}</p>
              </dt>
              <dd class="mt-2 ml-16 text-base text-gray-500">
                {{ feature.description }}
              </dd>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="message" :class="messageType === 'success' ? 'bg-green-500' : 'bg-red-500'" class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50">
      {{ message }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, inject, computed } from 'vue';
import { AcademicCapIcon, BookOpenIcon, ClockIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const injectedUser = inject('user');
const injectedIsAuthenticated = inject('isAuthenticated');
const authStore = useAuthStore();

// Use auth store for authentication state, fallback to injected values
const user = computed(() => authStore.user || injectedUser);
const isAuthenticated = computed(() => authStore.isAuthenticated || injectedIsAuthenticated);

const featuredCourses = ref([]);
const enrolledCourses = ref([]);
const message = ref('');
const messageType = ref('success');
const csrfToken = window.Laravel.csrfToken;

const features = [
  {
    name: 'Expert Instructors',
    description: 'Learn from industry experts with years of practical experience in their field.',
    icon: AcademicCapIcon,
  },
  {
    name: 'Learn at Your Own Pace',
    description: 'Lifetime access to course materials so you can learn on your own schedule.',
    icon: ClockIcon,
  },
  {
    name: 'Project-Based Learning',
    description: 'Apply your knowledge with hands-on projects and real-world examples.',
    icon: BookOpenIcon,
  },
  {
    name: 'Community Support',
    description: 'Join a community of like-minded learners and get help when you need it.',
    icon: UserGroupIcon,
  },
];

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const isEnrolled = (courseId) => {
  return enrolledCourses.value.includes(courseId);
};

const ensureAuthHeader = () => {
  const token = localStorage.getItem('auth_token');
  console.log('Home.vue - ensureAuthHeader called');
  console.log('Token from localStorage:', token);
  console.log('Auth store user:', authStore.user);
  console.log('Auth store authenticated:', authStore.isAuthenticated);
  console.log('Computed user:', user.value);
  console.log('Computed isAuthenticated:', isAuthenticated.value);

  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    console.log('Auth header set:', axios.defaults.headers.common['Authorization']);
    return true;
  }
  console.log('No token found, returning false');
  return false;
};

const fetchEnrolledCourses = async () => {
  if (!user.value || user.value.role !== 'student') return;
  if (!ensureAuthHeader()) return; // not logged in via token

  try {
    const response = await axios.get('/api/enrollments');
    enrolledCourses.value = response.data.map(enrollment => enrollment.course.id);
  } catch (error) {
    console.error('Error fetching enrolled courses:', error);
  }
};

const loadRazorpay = () => new Promise((resolve, reject) => {
  if (window.Razorpay) return resolve(true);
  const script = document.createElement("script");
  script.src = "https://checkout.razorpay.com/v1/checkout.js";
  script.onload = () => resolve(true);
  script.onerror = () => reject(new Error("Razorpay SDK failed to load"));
  document.body.appendChild(script);
});
const buyCourse = async (course) => {
  try {
    console.log('Home.vue - buyCourse called for course:', course);
    console.log('Course price:', course.price);

    await loadRazorpay();

    // Step 1: Check if user is already enrolled
    console.log('Checking enrollment status...');
    const checkRes = await axios.post('/api/enrollments/check', { course_id: course.id });
    console.log('Enrollment check response:', checkRes.data);

    if (checkRes.data.already_enrolled) {
      message.value = 'You are already enrolled in this course.';
      messageType.value = 'error';
      return;
    }
// Step 2: Create order
console.log('Creating Razorpay order...');
const { data } = await axios.get(`/api/create-order?amount=${Math.round(course.price * 100)}`);
console.log('Order creation response:', data);

if (!data.orderId) throw new Error("Failed to create Razorpay order");

    // Step 3: Configure Razorpay
    const options = {
      key: data.key,
      amount: data.amount || course.price * 100, // paise
      currency: data.currency || "INR",
      name: "EduAcademy",
      description: course.title,
      order_id: data.orderId,
      handler: async (response) => {
        try {
          const enrollRes = await axios.post("/api/enrollments", {
            course_id: course.id,
            payment_id: response.razorpay_payment_id,
            order_id: response.razorpay_order_id,
            signature: response.razorpay_signature,
          });
           message.value = enrollRes.data.message;
           messageType.value = "success";
           await fetchEnrolledCourses();
        } catch (err) {
          console.error("Enroll error:", err.response || err);
          message.value = err.response?.data?.message || "Error enrolling in course";
          messageType.value = "error";
        }
      },
      theme: { color: "#3399cc" },
      modal: { escape: true, backdropclose: false },
    };

    console.log('Razorpay options:', options);

    // Step 4: Open Razorpay modal
    const rzp = new window.Razorpay(options);
    rzp.open();

  } catch (error) {
    console.error("Payment initiation error:", error);
    message.value = error.response?.data?.message || error.message || "Error initiating payment";
    messageType.value = "error";
  }
};

// Initialize auth store from localStorage
const initializeAuth = () => {
  const token = localStorage.getItem('auth_token');
  const userData = localStorage.getItem('user');

  if (token && userData && !authStore.isAuthenticated) {
    console.log('Initializing auth store from localStorage');
    try {
      authStore.setAuth(JSON.parse(userData), token);
    } catch (error) {
      console.error('Error parsing user data:', error);
    }
  }
};

// Logout function
const logout = async () => {
  try {
    // Call logout API
    await axios.post('/api/logout');
  } catch (error) {
    console.error('Logout API error:', error);
  } finally {
    // Clear auth store and localStorage
    authStore.clearAuth();
    // Redirect to home page
    window.location.href = '/';
  }
};

// Fetch featured courses
onMounted(async () => {
  // Initialize auth store first
  initializeAuth();

  try {
    const response = await axios.get('/api/courses?limit=6');
    featuredCourses.value = response.data.slice(0, 6);
    await fetchEnrolledCourses();
  } catch (error) {
    console.error('Error fetching featured courses:', error);
  }
});
</script>
