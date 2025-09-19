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
                    <!-- Enhanced Thumbnail with fallback -->
                    <div class="w-16 h-12 bg-gray-200 rounded overflow-hidden relative">
                      <img
                        v-if="getValidThumbnail(courseVideo)"
                        :src="getValidThumbnail(courseVideo)"
                        :alt="courseVideo.title"
                        class="w-full h-full object-cover"
                        @error="onThumbnailError($event, courseVideo)"
                        loading="lazy"
                      />
                      <!-- Fallback icon when no thumbnail -->
                      <div v-else class="w-full h-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                      <!-- Play overlay for current video -->
                      <div v-if="courseVideo.id === video.id" class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                        </svg>
                      </div>
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
                      {{ formatDuration(courseVideo.duration_in_seconds) }}
                    </p>
                    <p v-if="courseVideo.description" class="text-xs text-gray-600 mt-1 line-clamp-2">
                      {{ truncateText(courseVideo.description, 60) }}
                    </p>
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
                    <span class="font-medium capitalize">{{ course.language || 'Not specified' }}</span>
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
          <!-- <button
            v-if="!isVideoCompleted && videoProgressPercentage > 50"
            @click="markVideoAsCompleted"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Mark as Complete
          </button> -->

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

// Generate fallback thumbnail from video (client-side)
const generateThumbnailFromVideo = (videoUrl) => {
  return new Promise((resolve) => {
    const video = document.createElement('video');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    video.addEventListener('loadeddata', () => {
      canvas.width = 320;
      canvas.height = 180;
      video.currentTime = Math.min(5, video.duration * 0.1); // 5 seconds or 10% into video
    });

    video.addEventListener('seeked', () => {
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      const thumbnail = canvas.toDataURL('image/jpeg', 0.8);
      resolve(thumbnail);
    });

    video.addEventListener('error', () => {
      resolve(null);
    });

    video.src = videoUrl;
    video.load();
  });
};

// Handle thumbnail loading errors
const onThumbnailError = (event, videoItem) => {
  console.warn(`Thumbnail failed to load for video ${videoItem.id}:`, videoItem.thumbnail_url);

  // Mark this thumbnail as failed
  failedThumbnails.value.add(videoItem.id);

  // Hide the broken image by setting src to empty
  event.target.style.display = 'none';

  // Show the fallback icon by triggering a re-render
  // This is handled by the v-else condition in the template
};

// Handle video poster errors
const onPosterError = (event) => {
  console.warn('Video poster failed to load:', event.target.poster);
  // Remove the poster attribute to prevent broken image display
  event.target.removeAttribute('poster');
};

// Handle video loading errors
const onVideoError = (event) => {
  console.error('Video failed to load:', event.target.src);
  error.value = 'Failed to load video. Please check your connection and try again.';
};

// Fetch course and video data
const fetchData = async () => {
  try {
    loading.value = true;
    // Use consistent API endpoint with /api/courses/
    const response = await axios.get(`/courses/${route.params.slug}`);
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
    if (response.success && response.data) {
      // Update progress data
      progress.value = response.data.progress;

      // Update completion status for current video if video is loaded
      if (video.value) {
        const wasCompleted = isVideoCompleted.value;
        const nowCompleted = isVideoAlreadyCompleted(video.value.id);

        // Only update if status actually changed to prevent unnecessary re-renders
        if (wasCompleted !== nowCompleted) {
          isVideoCompleted.value = nowCompleted;
        }
      }
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
  router.push(`/courses/${route.params.slug}`);
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
  if (isVideoCompleted.value) return;

  try {
    console.log('Marking video as completed:', {
      courseId: course.value.id,
      videoId: video.value.id
    });

    const response = await progressService.markVideoCompleted(course.value.id, video.value.id);

    if (response.success) {
      // Immediately update local state
      isVideoCompleted.value = true;

      // Update the progress data immediately without waiting for API call
      if (progress.value) {
        // Initialize video_progress array if it doesn't exist
        if (!progress.value.video_progress) {
          progress.value.video_progress = [];
        }

        // Add current video to completed list if not already there
        if (!progress.value.video_progress.includes(video.value.id)) {
          progress.value.video_progress.push(video.value.id);
        }

        // Update progress percentage immediately
        const totalVideos = course.value.videos.length;
        const completedVideos = progress.value.video_progress.length;
        progress.value.progress_percentage = (completedVideos / totalVideos) * 100;
      }

      console.log('Video marked as completed successfully');

      // Optionally refresh progress in background (don't await)
      fetchCourseProgress().catch(err => console.error('Background progress refresh failed:', err));
    } else {
      console.error('Failed to mark video as completed:', response);
    }
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

// FORMAT TIME FUNCTION - This was missing!
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
