<template>
  <div class="course-progress-tracker">
    <!-- Progress Overview -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Course Progress</h3>
        <span class="text-sm text-gray-500">{{ progress?.videos_completed || 0 }} / {{ progress?.total_videos || 0 }} videos completed</span>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
        <div
          class="bg-indigo-600 h-3 rounded-full transition-all duration-500"
          :style="`width: ${progress?.progress_percentage || 0}%`"
        ></div>
      </div>

      <div class="flex justify-between text-sm text-gray-600">
        <span>{{ progress?.progress_percentage || 0 }}% Complete</span>
        <span v-if="progress?.formatted_time_spent">{{ progress.formatted_time_spent }} spent</span>
      </div>
    </div>

    <!-- Video List with Progress -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Course Content</h3>
      </div>

      <div class="divide-y divide-gray-200">
        <div
          v-for="(video, index) in videos"
          :key="video.id"
          class="px-6 py-4 hover:bg-gray-50 transition-colors"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <!-- Video Number -->
              <div class="flex-shrink-0">
                <div
                  :class="[
                    'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium',
                    isVideoCompleted(video.id)
                      ? 'bg-green-100 text-green-800'
                      : 'bg-gray-100 text-gray-600'
                  ]"
                >
                  <svg v-if="isVideoCompleted(video.id)" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  <span v-else>{{ index + 1 }}</span>
                </div>
              </div>

              <!-- Video Info -->
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-900 truncate">{{ video.title }}</h4>
                <p class="text-sm text-gray-500">{{ formatDuration(video.duration_seconds) }}</p>
              </div>
            </div>

            <!-- Action Button -->
            <div class="flex items-center space-x-2">
            <span
  v-if="isVideoCompleted(video.id)"
  class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100"
>
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
    <path
      fill-rule="evenodd"
      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
      clip-rule="evenodd"
    />
  </svg>
  Completed
</span>

<span
  v-else
  class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100"
>
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
    <path
      fill-rule="evenodd"
      d="M10 2a8 8 0 100 16 8 8 0 000-16zm.75 4a.75.75 0 00-1.5 0v5a.75.75 0 00.37.65l3.5 2a.75.75 0 00.76-1.28l-3.13-1.8V6z"
      clip-rule="evenodd"
    />
  </svg>
  Pending
</span>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Completion Message -->
    <div v-if="progress?.is_completed" class="mt-6 bg-green-50 border border-green-200 rounded-md p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-green-800">Congratulations!</h3>
          <p class="mt-1 text-sm text-green-700">You have successfully completed this course on {{ formatDate(progress.completed_at) }}.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import progressService from '../services/ProgressService';

const props = defineProps({
  courseId: {
    type: [Number, String],
    required: true
  },
  videos: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['progress-updated']);

const progress = ref(null);
const loading = ref(false);
const error = ref(null);

const fetchProgress = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`courses/${props.courseId}/progress`);

    if (response.data.success) {
      progress.value = response.data.data.progress;
    }
  } catch (err) {
    console.error('Error fetching progress:', err);
    error.value = err.response?.data?.message || 'Error loading progress';
  } finally {
    loading.value = false;
  }
};

const markVideoCompleted = async (videoId) => {
  try {
    const response = await progressService.markVideoCompleted(props.courseId, videoId);

    if (response.success) {
      // Update local progress
      progress.value = response.data.progress;

      // Emit event to parent component
      emit('progress-updated', {
        progress: progress.value,
        isCompleted: response.data.is_course_completed
      });
    }
  } catch (err) {
    console.error('Error marking video as completed:', err);
    error.value = err.response?.data?.message || 'Error marking video as completed';
  }
};

const updateTimeSpent = async (seconds) => {
  try {
    await progressService.updateTimeSpent(props.courseId, seconds);
  } catch (err) {
    console.error('Error updating time spent:', err);
  }
};

const isVideoCompleted = (videoId) => {
  if (!progress.value?.video_progress) return false;
  return progress.value.video_progress.includes(videoId);
};

const formatDuration = (seconds) => {
  if (!seconds) return '0:00';

  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;

  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

// Track time spent on page
let startTime = Date.now();
let timeInterval = null;

const startTimeTracking = () => {
  startTime = Date.now();
  timeInterval = setInterval(() => {
    const timeSpent = Math.floor((Date.now() - startTime) / 1000);
    if (timeSpent > 0 && timeSpent % 30 === 0) { // Update every 30 seconds
      updateTimeSpent(30);
    }
  }, 30000);
};

const stopTimeTracking = () => {
  if (timeInterval) {
    clearInterval(timeInterval);
    timeInterval = null;
  }
};

onMounted(() => {
  fetchProgress();
  startTimeTracking();
});

// Cleanup on unmount
onMounted(() => {
  return () => {
    stopTimeTracking();
  };
});
</script>
