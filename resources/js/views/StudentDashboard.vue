<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
        <p class="mt-2 text-gray-600">Welcome back, {{ user?.name }}!</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Error loading dashboard</h3>
              <p class="mt-1 text-sm text-red-700">{{ error }}</p>
            </div>
          </div>
        </div>

        <!-- Dashboard Content -->
        <div v-else>
          <!-- My Enrolled Courses -->
          <div v-if="enrolledCourses.length > 0" class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">My Enrolled Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="course in enrolledCourses" :key="course.id" class="bg-white rounded-lg shadow-md overflow-hidden">
                <img
                  v-if="course.image"
                  :src="course.image"
                  :alt="course.title"
                  class="w-full h-40 object-cover"
                />
                <div v-else class="w-full h-40 bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                  <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>
                </div>

                <div class="p-4">
                  <h4 class="text-lg font-semibold mb-2">{{ course.title }}</h4>
                  <p class="text-gray-600 text-sm mb-3">{{ truncateText(course.description, 80) }}</p>

                  <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500">
                      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      {{ course.instructor.name }}
                    </span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                      Enrolled
                    </span>
                  </div>

                  <button
                    @click="goToCourse(course)"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors"
                  >
                    Continue Learning
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Available Courses -->
          <div>
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-semibold text-gray-800">Available Courses</h3>
              <div class="space-x-4">
                <router-link to="/courses" class="text-blue-600 hover:underline">View All Courses</router-link>
                <router-link to="/subscriptions" class="text-indigo-600 hover:underline">My Subscriptions</router-link>
              </div>
            </div>

            <div v-if="availableCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <div v-for="course in availableCourses" :key="course.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <img
                  v-if="course.image"
                  :src="course.image"
                  :alt="course.title"
                  class="w-full h-32 object-cover"
                />
                <div v-else class="w-full h-32 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>
                </div>

                <div class="p-4">
                  <h4 class="text-md font-semibold mb-2">{{ course.title }}</h4>
                  <p class="text-gray-600 text-xs mb-3">{{ truncateText(course.description, 60) }}</p>

                  <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">
                      <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      {{ course.instructor.name }}
                    </span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                      {{ capitalize(course.level) }}
                    </span>
                  </div>

                  <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-green-600">${{ course.price }}</span>
                    <button
                      @click="enrollInCourse(course)"
                      class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm transition-colors"
                    >
                      Buy Now
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="bg-white p-8 rounded-lg shadow-md text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
              <h4 class="text-lg font-semibold text-gray-600 mb-2">No Available Courses</h4>
              <p class="text-gray-500">Check back later for new courses or browse all courses.</p>
              <router-link to="/courses" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Browse All Courses
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(true);
const error = ref(null);
const message = ref('');
const messageType = ref('success');
const user = ref(null);
const enrolledCourses = ref([]);
const availableCourses = ref([]);

const ensureAuthHeader = () => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    return true;
  }
  return false;
};

// Fetch dashboard data
const fetchDashboardData = async () => {
  try {
    loading.value = true;

    if (!ensureAuthHeader()) {
      error.value = 'Please login to view your dashboard.';
      return;
    }

    const [meRes, enrollmentsRes, coursesRes] = await Promise.all([
      axios.get('/me'),
      axios.get('/enrollments'),
      axios.get('/courses')
    ]);

    user.value = meRes.data;

    // enrollments endpoint returns detailed enrollment data
    enrolledCourses.value = enrollmentsRes.data.map(e => ({
      id: e.course.id,
      title: e.course.title,
      slug: e.course.slug,
      description: e.course.description,
      image: e.course.image,
      level: e.course.level,
      instructor: e.course.instructor,
    }));

    availableCourses.value = coursesRes.data.slice(0, 8);

  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = err.response?.data?.message || 'Error loading dashboard data';
  } finally {
    loading.value = false;
  }
};

// Navigate to course
const goToCourse = (course) => {
  router.push(`/course/${course.slug}`);
};

// Enroll in course
const enrollInCourse = async (course) => {
  try {
    if (!ensureAuthHeader()) {
      error.value = 'Please login to enroll in a course.';
      return;
    }

    await axios.post(`/enrollments`, {
      course_id: course.id
    });

    // Refresh dashboard data
    await fetchDashboardData();

    message.value = `Successfully enrolled in ${course.title}!`;
    messageType.value = 'success';
  } catch (err) {
    const msg = err.response?.data?.message || 'Failed to enroll in course';
    message.value = msg;
    messageType.value = 'error';
    console.error('Error enrolling in course:', err);
  }
};

// Utility functions
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const capitalize = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

// Initialize
onMounted(fetchDashboardData);
</script>
