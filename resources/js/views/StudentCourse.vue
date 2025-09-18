<template>
  <div class="min-h-screen bg-gray-50">
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
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-md p-4 m-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Error loading course</h3>
          <p class="mt-1 text-sm text-red-700">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Course Content -->
    <div v-else-if="course">
      <!-- Course Header -->
      <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex flex-col md:flex-row gap-6">
          <img
            v-if="course.image"
            :src="course.image"
            :alt="course.title"
            class="w-full md:w-64 h-40 object-cover rounded-lg"
          />
          <div v-else class="w-full md:w-64 h-40 bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center rounded-lg">
            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>

          <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ course.title }}</h1>
            <p class="text-gray-600 mb-4">{{ course.description }}</p>

            <div class="flex flex-wrap gap-4 mb-4">
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>{{ course.instructor.name }}</span>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ course.videos.length }} {{ course.videos.length === 1 ? 'Video' : 'Videos' }}</span>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="capitalize">{{ course.level }}</span>
              </div>
            </div>

            <div class="flex items-center">
              <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                Enrolled
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Video List -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-800">Course Content</h3>
              <p class="text-sm text-gray-500">{{ course.videos.length }} {{ course.videos.length === 1 ? 'video' : 'videos' }}</p>
            </div>

            <div class="max-h-96 overflow-y-auto">
              <div v-if="course.videos.length > 0">
                <div
                  v-for="(video, index) in course.videos"
                  :key="video.id"
                  class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer"
                  @click="goToVideo(video)"
                >
                  <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                      <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <h4 class="text-sm font-medium text-gray-900 truncate">{{ video.title }}</h4>
                      <p class="text-xs text-gray-500 mt-1">
                        {{ formatDuration(video.duration_seconds) }}
                      </p>
                      <p v-if="video.description" class="text-xs text-gray-600 mt-1 line-clamp-2">
                        {{ truncateText(video.description, 80) }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="p-8 text-center">
                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No videos available yet</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Course Information -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">About This Course</h3>

            <div v-if="course.what_you_will_learn" class="mb-6">
              <h4 class="text-lg font-medium text-gray-800 mb-3">What You'll Learn</h4>
              <div class="prose prose-sm max-w-none">
                <p v-for="line in course.what_you_will_learn.split('\n')" :key="line" class="mb-2">{{ line }}</p>
              </div>
            </div>

            <div v-if="course.requirements" class="mb-6">
              <h4 class="text-lg font-medium text-gray-800 mb-3">Requirements</h4>
              <div class="prose prose-sm max-w-none">
                <p v-for="line in course.requirements.split('\n')" :key="line" class="mb-2">{{ line }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="font-medium text-gray-800 mb-2">Course Level</h5>
                <span class="capitalize text-gray-600">{{ course.level }}</span>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="font-medium text-gray-800 mb-2">Language</h5>
                <span class="capitalize text-gray-600">{{ course.language }}</span>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="font-medium text-gray-800 mb-2">Duration</h5>
                <span class="text-gray-600">{{ course.duration_in_minutes ? course.duration_in_minutes + ' minutes' : 'Not specified' }}</span>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="font-medium text-gray-800 mb-2">Instructor</h5>
                <span class="text-gray-600">{{ course.instructor.name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const loading = ref(true);
const error = ref(null);
const course = ref(null);

// Fetch course data
const fetchCourseData = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/courses/${route.params.slug}`);
    course.value = response.data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load course data';
    console.error('Error fetching course:', err);
  } finally {
    loading.value = false;
  }
};

// Navigate to video
const goToVideo = (video) => {
  router.push(`/course/${route.params.slug}/video/${video.id}`);
};

// Utility functions
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDuration = (seconds) => {
  if (!seconds) return 'No duration';
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;

  if (hours > 0) {
    return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
  }
  return `${minutes}:${secs.toString().padStart(2, '0')}`;
};

// Initialize
onMounted(fetchCourseData);
</script>
