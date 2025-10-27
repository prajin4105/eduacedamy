<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading State -->
    <div v-if="loading || enrollmentLoading" class="flex justify-center items-center py-12">
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

    <!-- Not Accessible State (neither enrolled nor subscribed) -->
    <div v-else-if="course && !canAccess" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Access Required</h1>
          <p class="text-lg text-gray-600 mb-6">Enroll in this course or subscribe to an eligible plan to access the content.</p>

          <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ course.title }}</h2>
            <p class="text-gray-600 mb-4">{{ course.subtitle }}</p>

            <div class="flex items-center justify-center space-x-6 text-sm text-gray-500 mb-4">
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                {{ course.videos?.length || 0 }} Videos
              </div>
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ course.instructor?.name }}
              </div>
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ course.level }}
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              @click="buyCourse(course)"
              :disabled="enrollmentLoading"
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="!enrollmentLoading" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <svg v-else class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ enrollmentLoading ? 'Enrolling...' : 'Enroll' }}
            </button>
            <button
              @click="goToCourseDetail"
              class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              View Course Details
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Course Overview (Shown if enrolled or has subscription access) -->
    <div v-else-if="course && canAccess" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
          <h2 class="text-xl font-semibold text-gray-900"></h2>
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
            <div v-if="progress?.is_completed" class="mt-4">
              <div class="flex items-center justify-between">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  Course Completed
                </span>
                <div class="flex space-x-2">
                  <button
                    v-if="hasTest && !testPassed"
                    @click="startTest"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Take Test
                  </button>
                  <button
                    @click="downloadCertificate"
                    :disabled="downloadingCertificate"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i v-if="downloadingCertificate" class="fas fa-spinner fa-spin mr-1"></i>
                    <i v-else class="fas fa-certificate mr-1"></i>
                    {{ downloadingCertificate ? 'Generating...' : 'Download Certificate' }}
                  </button>
                </div>
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

      <!-- Video List (Only shown for enrolled users) -->
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
              @click="goToVideo(video.id)"
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
                    <span>{{ formatDuration(video.duration_seconds) }}</span>
                    <span :class="getVideoStatusClass(video.id)">{{ getVideoStatus(video.id) }}</span>
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
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const loading = ref(true);
const enrollmentLoading = ref(false);
const error = ref(null);
const course = ref(null);
const progress = ref(null);
const timeSpent = ref(0);
const enrollmentStatus = ref(null); // Added to track enrollment status
const downloadingCertificate = ref(false);
const hasTest = ref(false);
const testPassed = ref(false);
const testData = ref(null);

// Track failed thumbnails
const failedThumbnails = ref(new Set());

// Computed properties
const totalVideos = computed(() => {
  return course.value?.videos?.length || 0;
});

const completedVideos = computed(() => {
  return progress.value?.videos_completed || progress.value?.video_progress?.length || 0;
});

const progressPercentage = computed(() => {
  if (progress.value?.progress_percentage !== undefined) {
    return Math.round(progress.value.progress_percentage);
  }
  if (!totalVideos.value) return 0;
  return Math.round((completedVideos.value / totalVideos.value) * 100);
});

// FIXED: Improved isEnrolled computed property with multiple checks
const isEnrolled = computed(() => {
  // Check multiple sources for enrollment status
  if (enrollmentStatus.value !== null) {
    return enrollmentStatus.value;
  }
  if (course.value?.is_enrolled === true || course.value?.is_enrolled === 1) {
    return true;
  }
  if (progress.value && Object.keys(progress.value).length > 0) {
    return true; // If we have progress data, user must be enrolled
  }
  return false;
});

// New: backend exposes can_access when user has subscription access
const canAccess = computed(() => {
  if (!course.value) return false;
  if (isEnrolled.value) return true;
  // Prefer explicit backend flag when available
  if (typeof course.value.can_access !== 'undefined') {
    return Boolean(course.value.can_access);
  }
  return false;
});

const nextVideo = computed(() => {
  if (!course.value?.videos || !progress.value?.video_progress) {
    return course.value?.videos?.[0] || null;
  }

  return course.value.videos.find(video =>
    !progress.value.video_progress.some(id => String(id) === String(video.id))
  ) || null;
});

// FIXED: Added method to check enrollment status
const checkEnrollmentStatus = async () => {
  if (!course.value?.id) return;

  try {
    const response = await axios.post('/enrollments/check', {
      course_id: course.value.id
    });

    enrollmentStatus.value = response.data.already_enrolled || false;
    console.log('Enrollment status checked:', enrollmentStatus.value);
  } catch (error) {
    console.error('Error checking enrollment status:', error);
    enrollmentStatus.value = false;
  }
};

// Sync progress from localStorage
const syncProgressFromStorage = () => {
  try {
    const stored = localStorage.getItem(`course_${course.value?.id}_progress`);
    if (stored) {
      const storedProgress = JSON.parse(stored);

      let videoProgressArray = storedProgress.video_progress;
      if (typeof videoProgressArray === 'string') {
        try {
          videoProgressArray = JSON.parse(videoProgressArray);
        } catch (parseError) {
          console.error('Error parsing stored video_progress:', parseError);
          videoProgressArray = [];
        }
      }

      progress.value = {
        ...progress.value,
        ...storedProgress,
        video_progress: (videoProgressArray || []).map(id => String(id))
      };

      console.log('Progress synced from storage:', progress.value);
    }
  } catch (err) {
    console.error('Error syncing progress from storage:', err);
  }
};

// FIXED: Improved fetchCourseData method
const fetchCourseData = async () => {
  try {
    loading.value = true;
    error.value = null;
    enrollmentStatus.value = null; // Reset enrollment status

    // Fetch course data
    const response = await axios.get(`/courses/${route.params.slug}`);
    course.value = response.data;

    if (!course.value) {
      error.value = 'Course not found';
      return;
    }

    console.log('Course data loaded:', course.value);

    // Check enrollment status first
    await checkEnrollmentStatus();

    // Only fetch progress if user can access (enrolled or subscribed)
    if (canAccess.value) {
      await fetchProgress();
      syncProgressFromStorage();
    } else {
      console.log('User not enrolled, skipping progress fetch');
    }

  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load course data';
    console.error('Error fetching course:', err);
  } finally {
    loading.value = false;
  }
};

const fetchProgress = async () => {
  if (!course.value?.id || !isEnrolled.value) {
    console.log('Not enrolled or no course ID, skipping progress fetch');
    return;
  }

  try {
    const response = await axios.get(`/courses/${course.value.id}/progress`);

    if (response.data.success) {
      const progressData = response.data.data.progress;

      let videoProgressArray = [];
      if (progressData.video_progress) {
        try {
          videoProgressArray = JSON.parse(progressData.video_progress);
        } catch (e) {
          console.error("Error parsing video_progress:", e);
          videoProgressArray = [];
        }
      }

      progress.value = {
        progress_percentage: progressData.progress_percentage || 0,
        video_progress: videoProgressArray.map(id => String(id)),
        videos_completed: progressData.videos_completed || 0,
        total_videos: progressData.total_videos || 0,
        time_spent: progressData.time_spent_seconds || 0,
        is_completed: progressData.is_completed === 1
      };

      console.log("Progress data loaded", progress.value);
    } else {
      console.error("Failed to load progress data");
    }
  } catch (error) {
    if (error.response?.status === 403) {
      console.log("Access denied to progress data - user might not be enrolled");
      enrollmentStatus.value = false; // Update enrollment status
      progress.value = {
        progress_percentage: 0,
        video_progress: [],
        videos_completed: 0,
        total_videos: totalVideos.value,
        time_spent: 0,
        is_completed: false
      };
    } else {
      console.error("Error fetching progress:", error);
    }
  }
};

// FIXED: Improved enrollment method with better error handling and state updates
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
    enrollmentLoading.value = true;
    console.log('Starting enrollment process for course:', course);

    await loadRazorpay();

    // Double-check enrollment status
    await checkEnrollmentStatus();
    if (enrollmentStatus.value) {
      alert('You are already enrolled in this course.');
      return;
    }

    // Create order
    console.log('Creating Razorpay order...');
    const { data } = await axios.get(`/create-order?amount=${Math.round(course.price * 100)}`);
    console.log('Order creation response:', data);

    if (!data.orderId) throw new Error("Failed to create Razorpay order");

    // Configure Razorpay
    const options = {
      key: data.key,
      amount: data.amount || course.price * 100,
      currency: data.currency || "INR",
      name: "EduAcademy",
      description: course.title,
      order_id: data.orderId,
      handler: async (response) => {
        try {
          enrollmentLoading.value = true;
          const enrollRes = await axios.post("/enrollments", {
            course_id: course.id,
            payment_id: response.razorpay_payment_id,
            order_id: response.razorpay_order_id,
            signature: response.razorpay_signature,
          });

          console.log('Enrollment successful:', enrollRes.data);

          // Update enrollment status immediately
          enrollmentStatus.value = true;

          // Refresh course data and progress
          await fetchCourseData();

          alert('Successfully enrolled in the course!');
        } catch (err) {
          console.error("Enrollment error:", err.response || err);
          alert(err.response?.data?.message || "Error enrolling in course");
        } finally {
          enrollmentLoading.value = false;
        }
      },
      theme: { color: "#3399cc" },
      modal: {
        escape: true,
        backdropclose: false,
        ondismiss: () => {
          enrollmentLoading.value = false;
        }
      },
    };

    console.log('Opening Razorpay modal...');
    const rzp = new window.Razorpay(options);
    rzp.open();

  } catch (error) {
    console.error("Payment initiation error:", error);
    alert(error.response?.data?.message || error.message || "Error initiating payment");
  } finally {
    enrollmentLoading.value = false;
  }
};

const completedIdSet = computed(() => {
  const ids = progress.value?.video_progress ?? [];
  return new Set(ids.map(id => String(id)));
});

const isVideoCompleted = (videoId) => {
  return progress.value?.video_progress?.includes(String(videoId)) || false;
};

const getVideoStatus = (videoId) => {
  if (isVideoCompleted(videoId)) {
    return 'Completed';
  }
  return 'Not Started';
};

const getVideoStatusClass = (videoId) => {
  if (isVideoCompleted(videoId)) {
    return 'text-green-600 font-medium';
  }
  return 'text-blue-600 font-medium';
};

const goToVideo = (videoOrId) => {
  let videoId;

  if (typeof videoOrId === 'object' && videoOrId !== null) {
    videoId = videoOrId.id;
  } else {
    videoId = videoOrId;
  }

  router.push(`/course/${route.params.slug}/video/${videoId}`);
};

const goToCourseDetail = () => {
  router.push(`/courses/${route.params.slug}`);
};

const handleStorageChange = (event) => {
  if (event.key === `course_${course.value?.id}_progress`) {
    console.log('Progress updated in another tab, syncing...');
    syncProgressFromStorage();
  }
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

const downloadCertificate = async () => {
  if (!course.value?.id) return;

  try {
    downloadingCertificate.value = true;

    // Get the current token and ensure it's set
    const token = localStorage.getItem('auth_token');
    if (!token) {
      alert('Please log in to download certificates');
      return;
    }

    // Generate and download certificate
    const response = await axios.post(`/courses/${course.value.id}/certificate/generate`, {}, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });

    if (response.data.success) {
      // Open download URL in new tab
      const downloadUrl = response.data.certificate.download_url;
      window.open(downloadUrl, '_blank');
    }
  } catch (error) {
    console.error('Error downloading certificate:', error);

    if (error.response?.status === 401) {
      console.error('Authentication failed. Token might be invalid or expired.');
      console.error('Current token:', localStorage.getItem('auth_token'));
      alert('Authentication failed. Please try logging out and logging in again.');
    } else {
      alert(error.response?.data?.message || 'Failed to download certificate');
    }
  } finally {
    downloadingCertificate.value = false;
  }
};

// Test-related methods
const checkTestAvailability = async () => {
  if (!course.value?.id) return;

  try {
    const response = await axios.get(`/api/courses/${course.value.id}/test`);
    if (response.data.success) {
      hasTest.value = true;
      testData.value = response.data.data;
    }
  } catch (error) {
    console.log('No test available for this course');
    hasTest.value = false;
  }
};

const checkTestStatus = async () => {
  if (!course.value?.id) return;

  try {
    const response = await axios.get(`/api/courses/${course.value.id}/test-attempts`);
    if (response.data.success) {
      const attempts = response.data.data.attempts;
      testPassed.value = attempts.some(attempt => attempt.is_passed);
    }
  } catch (error) {
    console.log('Error checking test status:', error);
  }
};

const startTest = () => {
  router.push(`/student/course/${course.value.id}/test`);
};

// Initialize
onMounted(async () => {
  await fetchCourseData();
  window.addEventListener('storage', handleStorageChange);

  // Check test availability and status after course data is loaded
  if (course.value?.id) {
    await checkTestAvailability();
    await checkTestStatus();
  }
});

// Cleanup on unmount
onUnmounted(() => {
  window.removeEventListener('storage', handleStorageChange);
});
</script>
