<template>
  <div class="min-h-screen bg-gray-50">
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

    <!-- Course Overview -->
    <div v-else-if="course" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Course Header -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">{{ course.title }}</h1>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ course.subtitle }}</p>
            </div>
            <div class="flex items-center space-x-3">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Enrolled
              </span>
              <button
                @click="goToCourseDetail"
                class="text-blue-600 hover:text-blue-800 text-sm flex items-center"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Course Info
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Progress -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
          <h2 class="text-xl font-semibold text-gray-900">Your Progress</h2>
          <div class="mt-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Course Completion</span>
              <span class="text-sm font-medium text-gray-900">{{ progressPercentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div
                class="bg-indigo-600 h-3 rounded-full transition-all duration-500"
                :style="{ width: progressPercentage + '%' }"
              ></div>
            </div>
            <div class="mt-2 flex justify-between text-sm text-gray-600">
              <span>{{ completedVideos }} of {{ totalVideos }} videos completed</span>
              <span v-if="timeSpent">{{ formatTimeSpent(timeSpent) }} total time</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Videos</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ totalVideos }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ completedVideos }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Remaining</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ totalVideos - completedVideos }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Continue Learning Section -->
      <div v-if="nextVideo" class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg mb-8">
        <div class="px-6 py-8">
          <div class="flex items-center justify-between">
            <div class="text-white">
              <h3 class="text-lg font-semibold">Continue Learning</h3>
              <p class="mt-1 text-blue-100">{{ nextVideo.title }}</p>
              <p class="mt-2 text-sm text-blue-200">
                {{ formatDuration(nextVideo.duration_in_seconds) }}
              </p>
            </div>
            <button
              @click="goToVideo(nextVideo)"
              class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors flex items-center"
            >
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
              </svg>
              Continue
            </button>
          </div>
        </div>
      </div>

      <!-- Video List -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h2 class="text-xl font-semibold text-gray-900">Course Videos</h2>
          <p class="mt-1 text-sm text-gray-500">{{ course.videos.length }} videos</p>
        </div>
        <div class="border-t border-gray-200">
          <div class="divide-y divide-gray-200">
            <div
              v-for="(video, index) in course.videos"
              :key="video.id"
              class="p-6 hover:bg-gray-50 cursor-pointer transition-colors"
              @click="goToVideo(video)"
            >
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="w-20 h-14 bg-gray-200 rounded overflow-hidden relative">
                    <img
                      v-if="getValidThumbnail(video)"
                      :src="getValidThumbnail(video)"
                      :alt="video.title"
                      class="w-full h-full object-cover"
                      @error="onThumbnailError($event, video)"
                      loading="lazy"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <!-- Play button overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <div class="flex-1 min-w-0">
                  <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-500">{{ index + 1 }}.</span>
                    <h3 class="text-lg font-medium text-gray-900">{{ video.title }}</h3>
                    <div v-if="isVideoCompleted(video.id)" class="flex-shrink-0">
                      <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                  </div>
                  <p v-if="video.description" class="mt-1 text-sm text-gray-600">
                    {{ truncateText(video.description, 120) }}
                  </p>
                  <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                    <span>{{ formatDuration(video.duration_in_seconds) }}</span>
                    <span v-if="isVideoCompleted(video.id)" class="text-green-600 font-medium">Completed</span>
                    <span v-else class="text-blue-600 font-medium">Not Started</span>
                  </div>
                </div>

                <div class="flex-shrink-0">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Information -->
      <div v-if="course.instructor || course.what_you_will_learn" class="mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Instructor Info -->
          <div v-if="course.instructor" class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg font-medium text-gray-900">Your Instructor</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
              <div class="flex items-start">
                <img
                  class="h-12 w-12 rounded-full"
                  :src="getInstructorAvatar(course.instructor)"
                  :alt="course.instructor.name"
                  @error="handleInstructorAvatarError"
                >
                <div class="ml-4">
                  <h4 class="text-lg font-medium text-gray-900">{{ course.instructor.name }}</h4>
                  <p v-if="course.instructor.title" class="text-gray-500">{{ course.instructor.title }}</p>
                  <p v-if="course.instructor.bio" class="mt-2 text-sm text-gray-600">{{ course.instructor.bio }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Learning Outcomes -->
          <div v-if="course.what_you_will_learn" class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg font-medium text-gray-900">What You're Learning</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
              <div class="prose prose-sm max-w-none">
                <p v-for="line in course.what_you_will_learn.split('\n')" :key="line" class="mb-2">{{ line }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
// import progressService from '../services/ProgressService';

const router = useRouter();
const route = useRoute();
const loading = ref(true);
const error = ref(null);
const course = ref(null);
const progress = ref(null);
const timeSpent = ref(0);
// Always treat completed IDs as strings


// Track failed thumbnails
const failedThumbnails = ref(new Set());

// Computed properties
const totalVideos = computed(() => {
  return course.value?.videos?.length || 0;
});

const completedVideos = computed(() => {
  return progress.value?.video_progress?.length || 0;
});

const progressPercentage = computed(() => {
  if (!totalVideos.value) return 0;
  return Math.round((completedVideos.value / totalVideos.value) * 100);
});

const nextVideo = computed(() => {
  if (!course.value?.videos || !progress.value?.video_progress) {
    return course.value?.videos?.[0] || null;
  }

  // Find first incomplete video
  return course.value.videos.find(video =>
    !progress.value.video_progress.includes(video.id)
  ) || null;
});

// Methods
const fetchCourseData = async () => {
  try {
    loading.value = true;
    error.value = null;

    // Fetch course data
    const response = await axios.get(`/courses/${route.params.slug}`);
    course.value = response.data;

    if (!course.value) {
      error.value = 'Course not found';
      return;
    }

    // Fetch progress data
    await fetchProgress();
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load course data';
    console.error('Error fetching course:', err);
  } finally {
    loading.value = false;
  }
};

const fetchProgress = async () => {
  try {
    // Replace with actual API call
    // const response = await progressService.getCourseProgress(course.value.id);

    // Mock data for testing
    const response = {
      success: true,
      data: {
        progress: {
          progress_percentage: 25,
          video_progress: [1], // Completed video IDs
          time_spent: 1800 // seconds
        }
      }
    };

    if (response.success && response.data) {
      progress.value = response.data.progress;
      progress.value.video_progress = (progress.value.video_progress ?? []).map(id => String(id));

      timeSpent.value = response.data.progress.time_spent || 0;
    }
  } catch (err) {
    console.error('Error fetching progress:', err);
  }
};
const completedIdSet = computed(() => {
  const ids = progress.value?.video_progress ?? [];
  return new Set(ids.map(id => String(id)));
});
const isVideoCompleted = (videoId) => {
return completedIdSet.value.has(String(videoId));
};

const goToVideo = (video) => {
  router.push(`/course/${route.params.slug}/video/${video.id}`);
};

const goToCourseDetail = () => {
  router.push(`/courses/${route.params.slug}`);
};

// Utility functions
const getValidThumbnail = (video) => {
  if (!video || failedThumbnails.value.has(video.id)) return null;
  return video.thumbnail_url && video.thumbnail_url.trim() !== '' ? video.thumbnail_url : null;
};

const onThumbnailError = (event, video) => {
  failedThumbnails.value.add(video.id);
  event.target.style.display = 'none';
};

const getInstructorAvatar = (instructor) => {
  if (!instructor) return '';
  if (instructor.avatar_url && instructor.avatar_url.trim() !== '') {
    return instructor.avatar_url;
  }
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(instructor.name)}&size=48&background=6366f1&color=ffffff`;
};

const handleInstructorAvatarError = (event) => {
  if (course.value?.instructor?.name) {
    event.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(course.value.instructor.name)}&size=48&background=6366f1&color=ffffff`;
  }
};

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

const formatTimeSpent = (seconds) => {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);

  if (hours > 0) {
    return `${hours}h ${minutes}m`;
  }
  return `${minutes}m`;
};

// Initialize
onMounted(fetchCourseData);
</script>
