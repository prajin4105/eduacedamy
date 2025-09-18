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
          <h3 class="text-sm font-medium text-red-800">Error loading video</h3>
          <p class="mt-1 text-sm text-red-700">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Video Content -->
    <div v-else-if="course && video">
      <!-- Completion Notification -->
      <div v-if="isVideoCompleted" class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Video Completed!</h3>
            <p class="mt-1 text-sm text-green-700">Great job! You've completed this video. Your progress has been saved.</p>
          </div>
        </div>
      </div>

      <!-- Video Player Section -->
   <div class="bg-white rounded-lg shadow-md mb-8 flex justify-center">
  <div class="aspect-video w-full max-w-3xl bg-black rounded-t-lg">
    <video
      v-if="video.video_url"
      ref="videoPlayer"
      controls
      class="w-full h-full rounded-t-lg"
      :poster="video.thumbnail_url"
      @loadedmetadata="onVideoLoaded"
      @timeupdate="onTimeUpdate"
    >
      <source :src="video.video_url" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <div v-else class="w-full h-full flex items-center justify-center">
      <div class="text-center text-white">
        <svg class="mx-auto h-16 w-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
        </svg>
        <p class="text-xl">Video not available</p>
        <p class="text-sm opacity-75">Please contact the instructor</p>
      </div>
    </div>
  </div>
</div>


      <!-- Course Navigation -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Video List Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-800">Course Videos</h3>
              <p class="text-sm text-gray-500">{{ course.videos.length }} {{ course.videos.length === 1 ? 'video' : 'videos' }}</p>
            </div>

            <div class="max-h-96 overflow-y-auto">
              <div
                v-for="courseVideo in course.videos"
                :key="courseVideo.id"
                class="p-4 border-b border-gray-100 cursor-pointer transition-colors"
                :class="courseVideo.id === video.id ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'hover:bg-gray-50'"
                @click="goToVideo(courseVideo)"
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
                    <div class="flex items-center space-x-2">
                      <h4 class="text-sm font-medium truncate" :class="courseVideo.id === video.id ? 'text-blue-900' : 'text-gray-900'">
                        {{ courseVideo.title }}
                      </h4>
                      <!-- Completion Status -->
                      <div v-if="isVideoAlreadyCompleted(courseVideo.id)" class="flex-shrink-0">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                      </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                      {{ formatDuration(courseVideo.duration_seconds) }}
                    </p>
                    <p v-if="courseVideo.description" class="text-xs text-gray-600 mt-1 line-clamp-2">
                      {{ truncateText(courseVideo.description, 60) }}
                    </p>
                  </div>
                  <div v-if="courseVideo.id === video.id" class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Course Information -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-semibold text-gray-800">Course: {{ course.title }}</h3>
              <button
                @click="goToCourse"
                class="text-blue-600 hover:text-blue-800 text-sm flex items-center"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Course
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h4 class="text-lg font-medium text-gray-800 mb-3">Course Details</h4>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600">Instructor:</span>
                    <span class="font-medium">{{ course.instructor.name }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Level:</span>
                    <span class="font-medium capitalize">{{ course.level }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Language:</span>
                    <span class="font-medium capitalize">{{ course.language }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Total Videos:</span>
                    <span class="font-medium">{{ course.videos.length }}</span>
                  </div>
                </div>
              </div>

              <div>
                <h4 class="text-lg font-medium text-gray-800 mb-3">Progress</h4>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600">Current Video:</span>
                    <span class="font-medium">{{ currentVideoIndex + 1 }} of {{ course.videos.length }}</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" :style="{ width: progressPercentage + '%' }"></div>
                  </div>
                  <p class="text-sm text-gray-500">
                    {{ Math.round(progressPercentage) }}% Complete
                  </p>

                  <!-- Video Progress -->
                  <div v-if="videoDuration > 0" class="mt-3">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                      <span>Video Progress</span>
                      <span>{{ Math.round(videoProgressPercentage) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1">
                      <div class="bg-green-500 h-1 rounded-full transition-all duration-300" :style="{ width: videoProgressPercentage + '%' }"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                      {{ formatTime(timeWatched) }} / {{ formatTime(videoDuration) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="course.what_you_will_learn" class="mt-6">
              <h4 class="text-lg font-medium text-gray-800 mb-3">What You'll Learn</h4>
              <div class="prose prose-sm max-w-none">
                <p v-for="line in course.what_you_will_learn.split('\n')" :key="line" class="mb-2">{{ line }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="flex justify-between items-center mt-8">
        <div>
          <button
            v-if="prevVideo"
            @click="goToVideo(prevVideo)"
            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Previous Video
          </button>
        </div>

        <div class="flex items-center space-x-4">
          <!-- Manual Complete Button -->
          <button
            v-if="!isVideoCompleted && videoProgressPercentage > 50"
            @click="markVideoAsCompleted"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Mark as Complete
          </button>

          <button
            v-if="nextVideo"
            @click="goToVideo(nextVideo)"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Next Video
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import progressService from '../services/ProgressService';

const router = useRouter();
const route = useRoute();
const loading = ref(true);
const error = ref(null);
const course = ref(null);
const video = ref(null);
const videoPlayer = ref(null);
const progress = ref(null);
const isVideoCompleted = ref(false);
const timeWatched = ref(0);
const videoDuration = ref(0);
const completionThreshold = 0.9; // 90% watched to mark as completed

// Computed properties
const currentVideoIndex = computed(() => {
  if (!course.value || !video.value) return 0;
  return course.value.videos.findIndex(v => v.id === video.value.id);
});

const prevVideo = computed(() => {
  if (!course.value || currentVideoIndex.value <= 0) return null;
  return course.value.videos[currentVideoIndex.value - 1];
});

const nextVideo = computed(() => {
  if (!course.value || currentVideoIndex.value >= course.value.videos.length - 1) return null;
  return course.value.videos[currentVideoIndex.value + 1];
});

const progressPercentage = computed(() => {
  if (!progress.value) return 0;
  return progress.value.progress_percentage || 0;
});

const videoProgressPercentage = computed(() => {
  if (!videoDuration.value) return 0;
  return (timeWatched.value / videoDuration.value) * 100;
});

// Fetch course and video data
const fetchData = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/courses/${route.params.slug}`);
    course.value = response.data;

    if (!course.value || !course.value.videos) {
      error.value = 'No videos found for this course';
      return;
    }

    const videoId = route.params.videoId;
    video.value = course.value.videos.find(v => String(v.id) === String(videoId));

    if (!video.value) {
      error.value = 'Video not found';
      return;
    }

    // Fetch course progress
    await fetchCourseProgress();
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load video data';
    console.error('Error fetching video:', err);
  } finally {
    loading.value = false;
  }
};

// Fetch course progress
const fetchCourseProgress = async () => {
  try {
    const response = await progressService.getCourseProgress(course.value.id);
    if (response.success) {
      progress.value = response.data.progress;
      isVideoCompleted.value = isVideoAlreadyCompleted(video.value.id);
    }
  } catch (err) {
    console.error('Error fetching course progress:', err);
  }
};

// Check if video is already completed
const isVideoAlreadyCompleted = (videoId) => {
  if (!progress.value?.video_progress) return false;
  return progress.value.video_progress.includes(videoId);
};


// Navigate to video
const goToVideo = (targetVideo) => {
  router.push(`/course/${route.params.slug}/video/${targetVideo.id}`);
};

// Navigate to course
const goToCourse = () => {
  router.push(`/course/${route.params.slug}`);
};

// Video event handlers
const onVideoLoaded = () => {
  if (videoPlayer.value) {
    videoDuration.value = videoPlayer.value.duration;
  }
};

const onTimeUpdate = () => {
  if (videoPlayer.value) {
    timeWatched.value = videoPlayer.value.currentTime;

    // Check if video should be marked as completed
    if (videoDuration.value > 0 && !isVideoCompleted.value) {
      const watchedPercentage = timeWatched.value / videoDuration.value;

      if (watchedPercentage >= completionThreshold) {
        markVideoAsCompleted();
      }
    }

    // Update time spent every 30 seconds
    if (Math.floor(timeWatched.value) % 30 === 0 && timeWatched.value > 0) {
      updateTimeSpent();
    }
  }
};

// Mark video as completed
const markVideoAsCompleted = async () => {
  if (isVideoCompleted.value) return;

  try {
    console.log('Marking video as completed:', {
      courseId: course.value.id,
      videoId: video.value.id
    });

    await progressService.markVideoCompleted(course.value.id, video.value.id);
    isVideoCompleted.value = true;

    // Refresh progress
    await fetchCourseProgress();

    console.log('Video marked as completed successfully');
  } catch (err) {
    console.error('Error marking video as completed:', err);
  }
};

// Update time spent
const updateTimeSpent = async () => {
  try {
    await progressService.updateTimeSpent(course.value.id, 30); // 30 seconds
  } catch (err) {
    console.error('Error updating time spent:', err);
  }
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

// Watch for route changes
watch(() => route.params.videoId, async () => {
  if (course.value) {
    const videoId = parseInt(route.params.videoId);
    video.value = course.value.videos.find(v => v.id === videoId);

    // Reset video progress tracking
    timeWatched.value = 0;
    videoDuration.value = 0;
    isVideoCompleted.value = isVideoAlreadyCompleted(videoId);

    // Fetch updated progress
    await fetchCourseProgress();
  }
});

// Initialize
onMounted(fetchData);
</script>
