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
          <h3 class="text-sm font-medium text-red-800">Error loading video</h3>
          <p class="mt-1 text-sm text-red-700">{{ error }}</p>
          <div v-if="isAuthError" class="mt-3">
            <button
              @click="redirectToLogin"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm"
            >
              Go to Login
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Not Enrolled State -->
    <div v-else-if="course && !isEnrolled" class="max-w-3xl mx-auto mt-12 bg-white rounded-lg shadow-md p-6">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
          <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">You are not enrolled in this course</h2>
        <p class="text-gray-600 mb-6">Please enroll in the course to access the video content.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
            @click="goToCourse"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Go to Course Page
          </button>
          <button
            @click="checkEnrollmentAgain"
            class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
          >
            Refresh Enrollment Status
          </button>
        </div>
      </div>
    </div>

    <!-- Video Content with New Layout -->
    <div v-else-if="course && video && isEnrolled">

      <!-- Header with Course Info -->
      <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <button
                @click="goToCourse"
                class="flex items-center text-blue-600 hover:text-blue-800 transition-colors"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Course
              </button>
              <div class="hidden sm:block">
                <h1 class="text-xl font-semibold text-gray-900">{{ course.title }}</h1>
                <p class="text-sm text-gray-600">by {{ course.instructor.name }}</p>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div class="text-sm text-gray-600">
                {{ Math.round(progressPercentage) }}% Complete
              </div>
              <div class="w-32 bg-gray-200 rounded-full h-2">
                <div
                  class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                  :style="{ width: progressPercentage + '%' }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Completion Notification -->
      <div v-if="isVideoCompleted" class="mx-4 mt-4 bg-green-50 border border-green-200 rounded-md p-4">
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

      <!-- Main Content Area - Video Player Left, Playlist Right -->
      <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- LEFT SIDE - Video Player (2/3 width) -->
          <div class="lg:col-span-2">
            <!-- Video Player Container -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
              <div class="relative aspect-video bg-black">
                <video
                  v-if="video.video_url"
                  ref="videoPlayer"
                  controls
                  class="w-full h-full"
                  :poster="getValidThumbnail(video)"
                  @loadedmetadata="onVideoLoaded"
                  @timeupdate="onTimeUpdate"
                  @error="onVideoError"
                  @poster="onPosterError"
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

            <!-- Video Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ video.title }}</h2>
                  <p class="text-gray-600 mb-4" v-if="video.description">{{ video.description }}</p>

                  <div class="flex items-center space-x-6 text-sm text-gray-500 mb-4">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      {{ formatDuration(video.duration_in_seconds) }}
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      {{ course.instructor.name }}
                    </div>
                    <div class="flex items-center">
                      <span class="text-sm text-gray-500">Video {{ currentVideoIndex + 1 }} of {{ course.videos.length }}</span>
                    </div>
                  </div>

                  <!-- Video Progress Bar -->
                  <div v-if="videoDuration > 0">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                      <span>Video Progress</span>
                      <span>{{ Math.round(videoProgressPercentage) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                      <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="{ width: videoProgressPercentage + '%' }"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                      <span>{{ formatTime(timeWatched) }}</span>
                      <span>{{ formatTime(videoDuration) }}</span>
                    </div>
                  </div>
                </div>

                <div v-if="isVideoCompleted" class="flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium ml-4">
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  Completed
                </div>
              </div>

              <!-- Navigation Buttons -->
              <div class="flex justify-between items-center pt-4 border-t">
                <button
                  v-if="prevVideo"
                  @click="goToVideo(prevVideo)"
                  class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Previous
                </button>
                <div v-else></div>

                <button
                  v-if="nextVideo"
                  @click="goToVideo(nextVideo)"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                  Next
                  <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - Video Playlist (1/3 width) -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg sticky top-6">
              <!-- Playlist Header -->
              <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Course Videos</h3>
                <p class="text-sm text-gray-500">{{ course.videos.length }} {{ course.videos.length === 1 ? 'video' : 'videos' }}</p>
              </div>

              <!-- Video List -->
              <div class="max-h-96 lg:max-h-screen lg:overflow-y-auto">
                <div
                  v-for="(courseVideo, index) in course.videos"
                  :key="courseVideo.id"
                  class="p-3 border-b border-gray-100 cursor-pointer transition-all duration-200 hover:bg-gray-50"
                  :class="courseVideo.id === video.id ? 'bg-blue-50 border-l-4 border-l-blue-500' : ''"
                  @click="goToVideo(courseVideo)"
                >
                  <div class="flex items-start space-x-3">
                    <!-- Video Number -->
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium"
                         :class="courseVideo.id === video.id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600'">
                      {{ index + 1 }}
                    </div>

                    <!-- Video Thumbnail -->
                    <div class="flex-shrink-0">
                      <div class="w-16 h-12 bg-gray-200 rounded overflow-hidden relative">
                        <img
                          v-if="getValidThumbnail(courseVideo)"
                          :src="getValidThumbnail(courseVideo)"
                          :alt="courseVideo.title"
                          class="w-full h-full object-cover"
                          @error="onThumbnailError($event, courseVideo)"
                          loading="lazy"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center">
                          <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                        </div>

                        <!-- Play/Current indicator -->
                        <div v-if="courseVideo.id === video.id" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                          <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                          </svg>
                        </div>

                        <!-- Completion check mark -->
                        <div v-else-if="isVideoAlreadyCompleted(courseVideo.id)" class="absolute top-1 right-1">
                          <svg class="w-4 h-4 text-green-500 bg-white rounded-full" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                          </svg>
                        </div>
                      </div>
                    </div>

                    <!-- Video Info -->
                    <div class="flex-1 min-w-0">
                      <div class="flex items-start justify-between">
                        <h4 class="text-sm font-medium truncate pr-2"
                            :class="courseVideo.id === video.id ? 'text-blue-900' : 'text-gray-900'">
                          {{ courseVideo.title }}
                        </h4>
                      </div>

                      <div class="flex items-center justify-between mt-1">
                        <p class="text-xs text-gray-500">
                          {{ formatDuration(courseVideo.duration_in_seconds) }}
                        </p>
                        <div v-if="isVideoAlreadyCompleted(courseVideo.id)" class="flex-shrink-0">
                          <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                          </svg>
                        </div>
                      </div>

                      <p v-if="courseVideo.description" class="text-xs text-gray-600 mt-1 line-clamp-2">
                        {{ truncateText(courseVideo.description, 50) }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
const isAuthError = ref(false); // Track authentication errors
const course = ref(null);
const video = ref(null);
const videoPlayer = ref(null);
const progress = ref(null);
const isVideoCompleted = ref(false);
const isEnrolled = ref(false);
const timeWatched = ref(0);
const videoDuration = ref(0);
const completionThreshold = 0.9; // 90% watched to mark as completed

// Track failed thumbnails to prevent repeated attempts
const failedThumbnails = ref(new Set());

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

// Enhanced authentication check
// Enhanced authentication check with better token handling
const checkAuthentication = () => {
  // Try multiple possible token storage keys
  const possibleKeys = ['token', 'authToken', 'access_token', 'auth_token', 'jwt_token'];
  let token = null;

  for (const key of possibleKeys) {
    const storedToken = localStorage.getItem(key);
    if (storedToken && storedToken.trim() !== '') {
      token = storedToken;
      console.log(`Found token with key: ${key}`);
      break;
    }
  }

  // Also check sessionStorage as fallback
  if (!token) {
    for (const key of possibleKeys) {
      const storedToken = sessionStorage.getItem(key);
      if (storedToken && storedToken.trim() !== '') {
        token = storedToken;
        console.log(`Found token in sessionStorage with key: ${key}`);
        break;
      }
    }
  }

  if (!token) {
    console.warn('No authentication token found in localStorage or sessionStorage');
    isAuthError.value = true;
    return null;
  }

  // Validate token format (basic check)
  try {
    // If it's a JWT, it should have 3 parts separated by dots
    if (token.includes('.')) {
      const parts = token.split('.');
      if (parts.length === 3) {
        // Try to decode the payload to check if token is expired
        const payload = JSON.parse(atob(parts[1]));
        const currentTime = Math.floor(Date.now() / 1000);

        if (payload.exp && payload.exp < currentTime) {
          console.warn('Token has expired');
          isAuthError.value = true;
          return null;
        }
      }
    }
  } catch (error) {
    console.warn('Token validation failed:', error);
    // Continue anyway, let the server validate
  }

  isAuthError.value = false;
  return token;
};
// Redirect to login
const redirectToLogin = () => {
  router.push('/login');
};

// Enhanced enrollment checking
const checkEnrollmentStatus = async () => {
  if (!course.value?.id) return false;

  const token = checkAuthentication();
  if (!token) return false;

  try {
    // Try multiple approaches to check enrollment

    // Method 1: Use existing enrollment check endpoint
    const enrollmentRes = await axios.post('/enrollments/check', {
      course_id: course.value.id
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json"
      }
    });

    if (enrollmentRes.data.already_enrolled) {
      return true;
    }

    // Method 2: Try the enrollment status endpoint as fallback
    try {
      const statusRes = await axios.get(
        `/courses/${course.value.id}/enrollment-status`,
        {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json"
          }
        }
      );

      return Boolean(statusRes.data.enrolled);
    } catch (statusError) {
      console.warn('Enrollment status endpoint failed:', statusError.response?.status);
    }

    // Method 3: Check if course data indicates enrollment
    if (course.value.is_enrolled === true || course.value.is_enrolled === 1) {
      return true;
    }

    return false;

  } catch (error) {
    console.error('Error checking enrollment status:', error);

    // If it's an auth error, handle appropriately
    if (error.response?.status === 401) {
      isAuthError.value = true;
      return false;
    }

    return false;
  }
};

// Method to refresh enrollment status
const checkEnrollmentAgain = async () => {
  loading.value = true;
  const enrolled = await checkEnrollmentStatus();
  isEnrolled.value = enrolled;

  if (enrolled) {
    await fetchCourseProgress();
  }
  loading.value = false;
};

// Enhanced thumbnail handling
const getValidThumbnail = (video) => {
  if (!video) return null;

  // Check if this thumbnail has already failed
  if (failedThumbnails.value.has(video.id)) {
    return null;
  }

  // Check if thumbnail_url exists and is not null/empty
  if (video.thumbnail_url && video.thumbnail_url.trim() !== '') {
    return video.thumbnail_url;
  }

  return null;
};

// Handle thumbnail loading errors
const onThumbnailError = (event, videoItem) => {
  console.warn(`Thumbnail failed to load for video ${videoItem.id}:`, videoItem.thumbnail_url);
  failedThumbnails.value.add(videoItem.id);
  event.target.style.display = 'none';
};

// Handle video poster errors
const onPosterError = (event) => {
  console.warn('Video poster failed to load:', event.target.poster);
  event.target.removeAttribute('poster');
};

// Handle video loading errors
const onVideoError = (event) => {
  console.error('Video failed to load:', event.target.src);
  error.value = 'Failed to load video. Please check your connection and try again.';
};

// Main data fetching function
const fetchData = async () => {
  try {
    loading.value = true;
    error.value = null;
    isAuthError.value = false;

    // Check authentication first
    const token = checkAuthentication();
    if (!token) {
      error.value = "Please login to access this content.";
      return;
    }

    // 1. Fetch course details (public endpoint, but may include enrollment status)
    const response = await axios.get(`/courses/${route.params.slug}`);
    course.value = response.data;

    if (!course.value || !course.value.videos) {
      error.value = "No videos found for this course";
      return;
    }

    // Find the requested video
    const videoId = route.params.videoId;
    video.value = course.value.videos.find(v => String(v.id) === String(videoId));

    if (!video.value) {
      error.value = "Video not found";
      return;
    }

    // 2. Check enrollment status
    const enrolled = await checkEnrollmentStatus();
    isEnrolled.value = enrolled;

    console.log('Enrollment status:', enrolled);

    // 3. If enrolled, fetch progress data
    if (enrolled) {
      await fetchCourseProgress();
    } else {
      console.log('User not enrolled, skipping progress fetch');
    }

  } catch (err) {
    console.error("Error fetching video data:", err);

    if (err.response?.status === 401) {
      isAuthError.value = true;
      error.value = "Authentication required. Please login again.";
    } else if (err.response?.status === 403) {
      error.value = "Access denied. You may not be enrolled in this course.";
    } else {
      error.value = err.response?.data?.message || "Failed to load video data";
    }
  } finally {
    loading.value = false;
  }
};

const fetchCourseProgress = async () => {
  if (!course.value?.id || !isEnrolled.value) {
    console.log('Skipping progress fetch - not enrolled or no course ID');
    return;
  }

  try {
    const response = await progressService.getCourseProgress(course.value.id);
    if (response.success && response.data) {
      const progressData = response.data.progress;

      // Safely parse video_progress
      let videoProgressArray = [];
      if (progressData.video_progress) {
        try {
          if (typeof progressData.video_progress === 'string') {
            videoProgressArray = JSON.parse(progressData.video_progress);
          } else if (Array.isArray(progressData.video_progress)) {
            videoProgressArray = progressData.video_progress;
          }
        } catch (parseError) {
          console.error('Error parsing video_progress:', parseError);
          videoProgressArray = [];
        }
      }

      // Ensure it's an array and convert all IDs to strings
      videoProgressArray = Array.isArray(videoProgressArray)
        ? videoProgressArray.map(id => String(id))
        : [];

      // Update progress data
      progress.value = {
        progress_percentage: progressData.progress_percentage || 0,
        video_progress: videoProgressArray,
        videos_completed: progressData.videos_completed || 0,
        total_videos: progressData.total_videos || 0,
        time_spent: progressData.time_spent_seconds || 0,
        is_completed: progressData.is_completed === 1
      };

      console.log('Progress data loaded:', progress.value);

      // Update completion status for current video
      if (video.value) {
        isVideoCompleted.value = isVideoAlreadyCompleted(video.value.id);
      }
    }
  } catch (err) {
    console.error('Error fetching course progress:', err);
    if (err.response?.status === 403) {
      console.log('Progress access denied - user may not be enrolled');
      isEnrolled.value = false;
    }
  }
};

// Check if video is already completed
const isVideoAlreadyCompleted = (videoId) => {
  if (!progress.value?.video_progress) {
    return false;
  }

  const videoProgress = Array.isArray(progress.value.video_progress)
    ? progress.value.video_progress
    : [];

  return videoProgress.some(id => String(id) === String(videoId));
};

// Navigate to video
const goToVideo = async (targetVideo) => {
  // Mark current video as completed if it should be (and save immediately)
  if (videoDuration.value > 0 && !isVideoCompleted.value) {
    const watchedPercentage = timeWatched.value / videoDuration.value;
    if (watchedPercentage >= completionThreshold) {
      await markVideoAsCompleted();
    }
  }

  // Update the URL with consistent routing
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
    console.log('Video loaded:', {
      videoId: video.value?.id,
      title: video.value?.title,
      duration: videoDuration.value,
      url: video.value?.video_url
    });
  }
};

const onTimeUpdate = () => {
  if (videoPlayer.value && videoDuration.value > 0) {
    timeWatched.value = videoPlayer.value.currentTime;

    // Check if video should be marked as completed
    if (!isVideoCompleted.value) {
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
  if (isVideoCompleted.value || !isEnrolled.value) return;

  try {
    const response = await progressService.markVideoCompleted(course.value.id, video.value.id);

    if (response.success) {
      isVideoCompleted.value = true;

      // Update using the data structure from your API
      const updatedProgress = response.data.progress;

      // Parse video_progress JSON string
      let videoProgressArray = [];
      if (updatedProgress.video_progress) {
        try {
          videoProgressArray = JSON.parse(updatedProgress.video_progress);
        } catch (parseError) {
          videoProgressArray = progress.value?.video_progress || [];
        }
      }

      progress.value = {
        progress_percentage: updatedProgress.progress_percentage || 0,
        video_progress: videoProgressArray.map(id => String(id)),
        videos_completed: updatedProgress.videos_completed || 0,
        total_videos: updatedProgress.total_videos || 0,
        time_spent: updatedProgress.time_spent_seconds || 0,
        is_completed: updatedProgress.is_completed === 1
      };

      // Save to localStorage in the correct format
      localStorage.setItem(`course_${course.value.id}_progress`, JSON.stringify({
        ...progress.value,
        video_progress: JSON.stringify(progress.value.video_progress) // Store as JSON string like DB
      }));

      console.log('Video marked as completed successfully');
    }
  } catch (err) {
    console.error('Error marking video as completed:', err);
  }
};

// Update time spent
const updateTimeSpent = async () => {
  if (!isEnrolled.value) return;

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

// FORMAT TIME FUNCTION
const formatTime = (timeInSeconds) => {
  if (!timeInSeconds || timeInSeconds < 0) return '0:00';

  const hours = Math.floor(timeInSeconds / 3600);
  const minutes = Math.floor((timeInSeconds % 3600) / 60);
  const seconds = Math.floor(timeInSeconds % 60);

  if (hours > 0) {
    return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
  }
  return `${minutes}:${seconds.toString().padStart(2, '0')}`;
};

// Watch for route changes
watch(() => route.params.videoId, async (newVideoId, oldVideoId) => {
  if (course.value && newVideoId !== oldVideoId) {
    const videoId = parseInt(newVideoId);
    const newVideo = course.value.videos.find(v => v.id === videoId);

    if (newVideo) {
      // Reset video player first
      if (videoPlayer.value) {
        videoPlayer.value.pause();
        videoPlayer.value.currentTime = 0;
      }

      // Update video reference
      video.value = newVideo;

      // Reset video progress tracking
      timeWatched.value = 0;
      videoDuration.value = 0;

      // Set completion status immediately from current progress data
      isVideoCompleted.value = isVideoAlreadyCompleted(videoId);

      // Force video element to reload the new source
      if (videoPlayer.value && newVideo.video_url) {
        // Small delay to ensure DOM has updated
        await new Promise(resolve => setTimeout(resolve, 100));
        videoPlayer.value.load(); // This forces the video to reload with new source
      }

      // Refresh progress data in background (non-blocking)
      fetchCourseProgress().catch(err => console.error('Progress refresh failed:', err));
    }
  }
});

// Initialize
onMounted(fetchData);
</script>
