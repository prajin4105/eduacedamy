<template>
  <div v-if="loading" class="py-12 flex justify-center">
    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
  </div>

  <!-- Course Details -->
  <div v-else-if="course" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Course Header -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ course.title }}</h1>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ course.subtitle }}</p>
      </div>

      <!-- Enrollment Status -->
      <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Course Info -->
          <div class="md:col-span-2">
            <div class="prose max-w-none" v-html="course.description"></div>

            <!-- What you'll learn -->
            <div v-if="course.learning_outcomes" class="mt-8">
              <h3 class="text-lg font-medium text-gray-900">What you'll learn</h3>
              <ul class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                <li v-for="(outcome, index) in formattedLearningOutcomes" :key="index" class="flex items-start">
                  <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>{{ outcome }}</span>
                </li>
              </ul>
            </div>

            <!-- Requirements -->
            <div v-if="course.requirements" class="mt-8">
              <h3 class="text-lg font-medium text-gray-900">Requirements</h3>
              <ul class="mt-2 space-y-1">
                <li v-for="(req, index) in formattedRequirements" :key="index" class="flex items-start">
                  <span class="text-gray-500 mr-2">•</span>
                  <span>{{ req }}</span>
                </li>
              </ul>
            </div>

            <!-- Rating summary above reviews -->
            <div class="mt-10">
              <ReviewSummary
                :course-id="course.id"
                :is-enrolled="!!course.is_enrolled"
                :initial-data="{ average_rating: course.rating || 0, total_reviews: course.reviews_count || 0 }"
              />
            </div>

            <!-- Reviews list below description/learn/requirements -->
            <div class="mt-8">
              <ReviewList :course-id="course.id" :is-enrolled="!!course.is_enrolled" />
            </div>
          </div>

          <!-- Course Sidebar -->
          <div class="md:col-span-1">
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
              <div class="aspect-w-16 aspect-h-9 mb-4">
                <div class="relative w-full h-48 bg-gray-200 rounded overflow-hidden">
                  <img
                    :src="getCourseImage(course)"
                    :alt="course.title"
                    class="w-full h-full object-cover"
                    @error="handleImageError"
                    @load="handleImageLoad"
                  />
                  <div v-if="imageLoading" class="absolute inset-0 flex items-center justify-center bg-gray-200">
                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-500"></div>
                  </div>
                  <div v-if="imageError" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                    <div class="text-center text-gray-500">
                      <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                      </svg>
                      <p class="text-sm">Course Image</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-4">
                <div class="flex items-baseline">
                  <span class="text-2xl font-bold text-gray-900">${{ course.price || '0' }}</span>
                  <span v-if="course.original_price" class="ml-2 text-sm text-gray-500 line-through">${{ course.original_price }}</span>
                </div>

                <div class="mt-4 space-y-4">
                  <!-- Enrollment Status -->
                  <div v-if="course.is_enrolled" class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                      <span class="text-sm font-medium text-gray-700">Progress</span>
                      <span class="text-sm font-medium text-gray-900">{{ getProgressPercentage() }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div
                        class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                        :style="`width: ${getProgressPercentage()}%`"
                      ></div>
                    </div>
                    <div class="mt-2">
                      <span
                        :class="[ 'px-2 py-1 rounded-full text-xs font-medium',
                        course.enrollment_status === 'completed' ? 'bg-green-100 text-green-800' :
                        course.enrollment_status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800']"
                      >
                        {{ getEnrollmentStatusText() }}
                      </span>
                    </div>
                  </div>

                  <button
                    @click="course.is_enrolled ? goToCourse() : enrollCourse()"
                    :disabled="enrolling"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ enrolling ? 'Enrolling...' : (course.is_enrolled ? 'Start Learning' : 'Enroll Now') }}
                  </button>

                </div>

                <div class="mt-6 border-t border-gray-200 pt-4">
                  <h4 class="text-sm font-medium text-gray-900">This course includes:</h4>
                  <ul class="mt-2 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      {{ course.duration || 'Lifetime' }} access
                    </li>
                    <li class="flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Certificate of completion
                    </li>
                    <li v-if="course.videos && course.videos.length" class="flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      {{ course.videos.length }} video{{ course.videos.length !== 1 ? 's' : '' }}
                    </li>
                  </ul>
                </div>


              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Progress Tracker (for enrolled users) -->
      <div v-if="course.is_enrolled" class="mt-8">
        <CourseProgressTracker
          :course-id="course.id"
          :videos="course.videos"
          @progress-updated="handleProgressUpdate"
        />
      </div>

      <!-- Course Content only visible if the user is enrolled -->


      <!-- If the user is NOT enrolled, show the enroll message -->
      <!-- <div v-else class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h2 class="text-xl font-semibold text-gray-900">Course Content</h2>
          <p v-if="course.videos && course.videos.length" class="text-sm text-gray-500">{{ course.videos.length }} video{{ course.videos.length !== 1 ? 's' : '' }} available</p>
        </div>
        <div class="border-t border-gray-200 p-8 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <p>To access the course content, please enroll first.</p>
          <button
            @click="enrollCourse"
            :disabled="enrolling"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ enrolling ? 'Enrolling...' : 'Enroll Now' }}
          </button>
        </div>
      </div> -->


    </div>
  </div>
</template>




<script>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import CourseProgressTracker from '../components/CourseProgressTracker.vue';
import ReviewSummary from '@/components/Reviews/ReviewSummary.vue';
import ReviewList from '@/components/Reviews/ReviewList.vue';

export default {
  name: 'CourseDetail',
  components: {
    CourseProgressTracker,
    ReviewSummary,
    ReviewList
  },
  setup() {
    const route = useRoute();
    const authStore = useAuthStore();
    const course = ref(null);
    const loading = ref(true);
    const expandedSections = ref([]);
    const isInWishlist = ref(false);
    const enrolling = ref(false);
    const imageLoading = ref(true);
    const imageError = ref(false);

    // Computed for course sections
    const courseSections = computed(() => {
      return course.value?.sections || [];
    });

    // Computed for requirements (supports both array and comma-separated string)
    const formattedRequirements = computed(() => {
      if (!course.value?.requirements) return [];

      if (Array.isArray(course.value.requirements)) {
        return course.value.requirements;
      }

      if (typeof course.value.requirements === 'string') {
        return course.value.requirements
          .split(',')
          .map(r => r.trim())
          .filter(r => r.length > 0);
      }

      return [];
    });

    // Computed for learning outcomes
    const formattedLearningOutcomes = computed(() => {
      if (!course.value?.learning_outcomes) return [];

      if (Array.isArray(course.value.learning_outcomes)) {
        return course.value.learning_outcomes;
      }

      if (typeof course.value.learning_outcomes === 'string') {
        return course.value.learning_outcomes
          .split(',')
          .map(o => o.trim())
          .filter(o => o.length > 0);
      }

      return [];
    });

    // UTILITY FUNCTIONS
    const getCourseImage = (courseData) => {
      if (!courseData) return '';

      // Check multiple possible image field names
      const imageFields = ['image_url', 'image', 'thumbnail', 'thumbnail_url', 'cover_image'];

      for (const field of imageFields) {
        if (courseData[field] && courseData[field].trim() !== '') {
          return courseData[field];
        }
      }

      // Return empty string to let the error handler show fallback
      return '';
    };

    const getInstructorAvatar = (instructor) => {
      if (!instructor) return '';

      if (instructor.avatar_url && instructor.avatar_url.trim() !== '') {
        return instructor.avatar_url;
      }

      if (instructor.avatar && instructor.avatar.trim() !== '') {
        return instructor.avatar;
      }

      // Generate avatar using UI Avatars service
      return `https://ui-avatars.com/api/?name=${encodeURIComponent(instructor.name)}&size=64&background=6366f1&color=ffffff`;
    };

    const handleImageError = (event) => {
      console.log('Course image failed to load');
      imageError.value = true;
      imageLoading.value = false;
    };

    const handleImageLoad = (event) => {
      console.log('Course image loaded successfully');
      imageError.value = false;
      imageLoading.value = false;
    };

    const handleInstructorAvatarError = (event) => {
      console.log('Instructor avatar failed to load, using fallback');
      if (course.value?.instructor?.name) {
        event.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(course.value.instructor.name)}&size=64&background=6366f1&color=ffffff`;
      }
    };

    const getProgressPercentage = () => {
      if (!course.value) return 0;

      if (course.value.enrollment_status === 'completed') return 100;
      if (course.value.progress_percentage) return Math.round(course.value.progress_percentage);

      return 0;
    };

    const getEnrollmentStatusText = () => {
      if (!course.value) return 'Enrolled';

      switch (course.value.enrollment_status) {
        case 'completed':
          return 'Completed';
        case 'in_progress':
          return 'In Progress';
        default:
          return 'Enrolled';
      }
    };

    const truncateText = (text, length) => {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    };

    const formatDuration = (seconds) => {
      if (!seconds) return '';

      const hours = Math.floor(seconds / 3600);
      const minutes = Math.floor((seconds % 3600) / 60);
      const secs = seconds % 60;

      if (hours > 0) {
        return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
      }
      return `${minutes}:${secs.toString().padStart(2, '0')}`;
    };

    const toggleSection = (index) => {
      const sectionIndex = expandedSections.value.indexOf(index);
      if (sectionIndex > -1) {
        expandedSections.value.splice(sectionIndex, 1);
      } else {
        expandedSections.value.push(index);
      }
    };

    // Fetch course function
    const fetchCourse = async () => {
      try {
        loading.value = true;
        imageLoading.value = true;
        imageError.value = false;

        const courseId = route.params.id || route.params.slug;
        console.log('Fetching course with ID/Slug:', courseId);

        if (!courseId) {
          throw new Error('No course ID or slug provided');
        }

        const response = await axios.get(`/courses/${courseId}`);
        course.value = response.data;

        console.log('Course data:', course.value);

        if (authStore.isAuthenticated) {
          await checkEnrollmentStatus();
        }
      } catch (error) {
        console.error('Error fetching course:', error);
      } finally {
        loading.value = false;
      }
    };

    // Check enrollment status
    const checkEnrollmentStatus = async () => {
      try {
        const enrollmentResponse = await axios.get('/enrollments');
        const enrolledCourses = enrollmentResponse.data;

        const isEnrolled = enrolledCourses.some(enrollment => {
          const courseId = enrollment.course?.id || enrollment.course_id;
          return courseId === course.value.id;
        });

        course.value.is_enrolled = isEnrolled;

        if (isEnrolled) {
          const enrollment = enrolledCourses.find(e => {
            const courseId = e.course?.id || e.course_id;
            return courseId === course.value.id;
          });
          course.value.enrollment_status = enrollment.status || 'enrolled';
          course.value.progress_percentage = enrollment.progress_percentage || 0;
        }
      } catch (error) {
        console.error('Error checking enrollment status:', error);
        course.value.is_enrolled = false;
      }
    };
const enrollCourse = async () => {
  if (!authStore.isAuthenticated) {
    window.location.href = `/login?redirect=${encodeURIComponent(route.fullPath)}`;
    return;
  }

  try {
    enrolling.value = true;
    const response = await axios.post('/enrollments', {
      course_id: course.value.id
    });

    if (response.data.message) {
      course.value.is_enrolled = true;
      course.value.enrollment_status = 'enrolled';

      setTimeout(() => {
        window.location.href = `/course/${route.params.slug}`;
      }, 1000);
    }
  } catch (error) {
    console.error('Error enrolling in course:', error);
    if (error.response?.status === 401) {
      authStore.clearAuth();
      window.location.href = `/login?redirect=${encodeURIComponent(route.fullPath)}`;
    }
  } finally {
    enrolling.value = false;
  }
};



    const toggleWishlist = async () => {
      if (!authStore.isAuthenticated) {
        window.location.href = `/login?redirect=${encodeURIComponent(route.fullPath)}`;
        return;
      }

      try {
        if (isInWishlist.value) {
          await axios.delete(`/courses/${route.params.slug}/wishlist`);
        } else {
          await axios.post(`/courses/${route.params.slug}/wishlist`);
        }
        isInWishlist.value = !isInWishlist.value;
      } catch (error) {
        console.error('Error updating wishlist:', error);
      }
    };

    const handleProgressUpdate = (data) => {
      if (data.status) {
        course.value.enrollment_status = data.status;
      }
      if (data.progress_percentage !== undefined) {
        course.value.progress_percentage = data.progress_percentage;
      }
    };

    const goToCourse = () => {
      window.location.href = `/course/${route.params.slug}`;
    };

    onMounted(async () => {
      const token = localStorage.getItem('auth_token');
      if (token && !authStore.token) {
        authStore.setAuth(JSON.parse(localStorage.getItem('user') || '{}'), token);
      }

      await fetchCourse();
    });

    return {
      course,
      loading,
      courseSections,
      expandedSections,
      isInWishlist,
      enrolling,
      imageLoading,
      imageError,
      formattedRequirements,
      formattedLearningOutcomes,
      getCourseImage,
      getInstructorAvatar,
      handleImageError,
      handleImageLoad,
      handleInstructorAvatarError,
      getProgressPercentage,
      getEnrollmentStatusText,
      truncateText,
      formatDuration,
      toggleSection,
      enrollCourse,
      toggleWishlist,
      handleProgressUpdate,
      goToCourse
    };
  }
};
</script>

