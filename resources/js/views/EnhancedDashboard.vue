<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->

    <!-- Dashboard Header -->
    <div class="bg-indigo-600 text-white py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Learning Dashboard</h1>
        <p class="mt-2 text-indigo-200">Track your progress and continue your learning journey</p>
      </div>
    </div>

    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Courses</p>
                <p class="text-2xl font-bold text-gray-900">{{ statistics.total_courses }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ statistics.completed_courses }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-6">
                <p class="text-sm font-medium text-gray-600">In Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ statistics.in_progress_courses }}</p>
              </div>
            </div>
          </div>

          <!-- <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Time Spent</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatTime(statistics.total_time_spent_seconds) }}</p>
              </div>
            </div>
          </div> -->
        </div>

        <!-- Progress Overview -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Learning Progress Overview</h2>
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-gray-700">Overall Completion Rate</span>
            <span class="text-sm font-medium text-gray-900">{{ statistics.completion_rate }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-3">
            <div
              class="bg-indigo-600 h-3 rounded-full transition-all duration-500"
              :style="`width: ${statistics.completion_rate}%`"
            ></div>
          </div>
        </div>

        <!-- Completed Courses Section -->
        <div v-if="completedCourses.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Completed Courses</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="course in completedCourses" :key="course.enrollment.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <img :src="course.course.image || '/placeholder/300/200'" :alt="course.course.title" class="w-full h-48 object-cover" />
              <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                  <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">
                    Completed
                  </span>
                  <span class="text-sm text-gray-500">
                    Completed: {{ formatDate(course.progress?.completed_at) }}
                  </span>
                </div>

                <h3 class="text-xl font-semibold mb-2">{{ course.course.title }}</h3>
                <p class="text-gray-600 mb-4">{{ truncateText(course.course.description, 100) }}</p>

                <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center">
                    <img :src="course.course.instructor?.avatar || `https://ui-avatars.com/api/?name=${course.course.instructor?.name}`"
                         :alt="course.course.instructor?.name"
                         class="w-8 h-8 rounded-full mr-3" />
                    <span class="text-sm text-gray-700">{{ course.course.instructor?.name }}</span>
                  </div>
                  <span class="text-sm text-gray-500">
                    Time: {{ formatTime(course.progress?.time_spent_seconds || 0) }}
                  </span>
                </div>

                <div class="flex gap-2">
                  <router-link :to="`/course/${course.course.slug}`" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-center">
                    Review Course
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- In Progress Courses Section -->
        <div v-if="inProgressCourses.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Continue Learning</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="course in inProgressCourses" :key="course.enrollment.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <img :src="course.course.image || '/placeholder/300/200'" :alt="course.course.title" class="w-full h-48 object-cover" />
              <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                  <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm font-medium">
                    In Progress
                  </span>
                  <span class="text-sm text-gray-500">
                    Last accessed: {{ formatDate(course.enrollment.last_accessed_at) }}
                  </span>
                </div>

                <h3 class="text-xl font-semibold mb-2">{{ course.course.title }}</h3>
                <p class="text-gray-600 mb-4">{{ truncateText(course.course.description, 100) }}</p>

                <!-- Progress Bar -->
                <div class="mb-4">
                  <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Progress</span>
                    <span>{{ course.enrollment.progress_percentage }}%</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" :style="`width: ${course.enrollment.progress_percentage}%`"></div>
                  </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center">
                    <img :src="course.course.instructor?.avatar || `https://ui-avatars.com/api/?name=${course.course.instructor?.name}`"
                         :alt="course.course.instructor?.name"
                         class="w-8 h-8 rounded-full mr-3" />
                    <span class="text-sm text-gray-700">{{ course.course.instructor?.name }}</span>
                  </div>
                  <span class="text-sm text-gray-500">
                    Time: {{ formatTime(course.progress?.time_spent_seconds || 0) }}
                  </span>
                </div>

                <div class="flex gap-2">
                  <router-link :to="`/course/${course.course.slug}`" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-center">
                    Continue Learning
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Not Started Courses Section -->
        <div v-if="notStartedCourses.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Ready to Start</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="course in notStartedCourses" :key="course.enrollment.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <img :src="course.course.image || '/placeholder/300/200'" :alt="course.course.title" class="w-full h-48 object-cover" />
              <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm font-medium">
                    Not Started
                  </span>
                  <span class="text-sm text-gray-500">
                    Enrolled: {{ formatDate(course.enrollment.enrolled_at) }}
                  </span>
                </div>

                <h3 class="text-xl font-semibold mb-2">{{ course.course.title }}</h3>
                <p class="text-gray-600 mb-4">{{ truncateText(course.course.description, 100) }}</p>

                <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center">
                    <img :src="course.course.instructor?.avatar || `https://ui-avatars.com/api/?name=${course.course.instructor?.name}`"
                         :alt="course.course.instructor?.name"
                         class="w-8 h-8 rounded-full mr-3" />
                    <span class="text-sm text-gray-700">{{ course.course.instructor?.name }}</span>
                  </div>
                  <span class="text-sm text-gray-500">
                    {{ course.course.videos?.length || 0 }} lessons
                  </span>
                </div>

                <div class="flex gap-2">
                  <router-link :to="`/course/${course.course.slug}`" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-center">
                    Start Learning
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="statistics.total_courses === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No enrolled courses yet</h3>
          <p class="text-gray-500 mb-6">Start your learning journey by enrolling in a course</p>
          <router-link to="/courses" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
            Browse Courses
          </router-link>
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
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import progressService from '../services/ProgressService';

const router = useRouter();
// user injection not required; using token-based API

const loading = ref(true);
const error = ref(null);
const message = ref('');
const messageType = ref('success');

// Dashboard data
const statistics = ref({
  total_courses: 0,
  completed_courses: 0,
  in_progress_courses: 0,
  not_started_courses: 0,
  total_time_spent_seconds: 0,
  completion_rate: 0,
});

const completedCourses = ref([]);
const inProgressCourses = ref([]);
const notStartedCourses = ref([]);

// UTILITY FUNCTIONS FOR HTML STRIPPING
const stripHtml = (html) => {
  if (!html) return '';
  // Create a temporary div element to parse HTML
  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

const truncateText = (text, length = 100) => {
  if (!text) return '';
  const cleanText = stripHtml(text);
  return cleanText.length > length ? cleanText.substring(0, length) + '...' : cleanText;
};

const fetchDashboardProgress = async () => {
  try {
    loading.value = true;
    // Ensure auth header is present
    const token = localStorage.getItem('auth_token');
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
    const response = await axios.get('/dashboard/progress');

    if (response.data.success) {
      const data = response.data.data;

      statistics.value = data.statistics;
      completedCourses.value = data.completed_courses;
      inProgressCourses.value = data.in_progress_courses;
      notStartedCourses.value = data.not_started_courses;
    }
  } catch (err) {
    console.error('Error fetching dashboard progress:', err);
    error.value = err.response?.data?.message || 'Error loading dashboard data';
  } finally {
    loading.value = false;
  }
};

// Handle real-time progress updates
const handleProgressUpdate = (data) => {
  statistics.value = data.statistics;
  completedCourses.value = data.completed_courses;
  inProgressCourses.value = data.in_progress_courses;
  notStartedCourses.value = data.not_started_courses;
};

const handleVideoCompleted = (data) => {
  // Refresh progress data when a video is completed
  fetchDashboardProgress();
};

const handleProgressError = (error) => {
  console.error('Progress update error:', error);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatTime = (seconds) => {
  if (!seconds) return '0s';

  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;

  if (hours > 0) {
    return `${hours}h ${minutes}m`;
  } else if (minutes > 0) {
    return `${minutes}m ${secs}s`;
  } else {
    return `${secs}s`;
  }
};

const logout = async () => {
  try {
    await axios.post('/logout');
    localStorage.removeItem('auth_token');
    router.push('/login');
  } catch (error) {
    console.error('Logout error:', error);
  }
};

onMounted(() => {
  fetchDashboardProgress();

  // Start real-time progress updates
  progressService.start(30000); // Update every 30 seconds

  // Add event listeners
  progressService.addEventListener('progress-updated', handleProgressUpdate);
  progressService.addEventListener('video-completed', handleVideoCompleted);
  progressService.addEventListener('progress-error', handleProgressError);
});

onUnmounted(() => {
  // Stop real-time updates and remove listeners
  progressService.stop();
  progressService.removeEventListener('progress-updated', handleProgressUpdate);
  progressService.removeEventListener('video-completed', handleVideoCompleted);
  progressService.removeEventListener('progress-error', handleProgressError);
});
</script>
