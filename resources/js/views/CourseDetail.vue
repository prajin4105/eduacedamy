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
                  <span v-if="course.available_plans?.length > 0" class="text-2xl font-bold text-purple-600">Free with Subscription</span>
                  <span v-else class="text-2xl font-bold text-gray-900">₹{{ course.price || '0' }}</span>
                  <span v-if="course.original_price" class="ml-2 text-sm text-gray-500 line-through">₹{{ course.original_price }}</span>
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
                    @click="course.is_enrolled ? goToCourse() : handleEnrollClick()"
                    :disabled="enrolling"
                    :class="getEnrollButtonClass()"
                    class="w-full py-2 px-4 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ getEnrollButtonText() }}
                  </button>

                  <!-- Test Button (shown when course is completed and test is available and not yet passed) -->
                  <button
                    v-if="course.is_enrolled && hasTest && !testPassed"
                    @click="startTest"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Take Final Test
                  </button>

                  <!-- Certificate Button (when completed and eligible) -->
                  <button
                    v-if="course.is_enrolled && course.enrollment_status === 'completed' && (!hasTest || testPassed)"
                    @click="downloadCertificate"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-2"
                  >
                    Download Certificate
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

                  <!-- Show available plans for subscription courses -->
                  <div v-if="course.available_plans?.length > 0" class="mt-4">
                    <h5 class="text-sm font-medium text-gray-900 mb-2">Available in:</h5>
                    <div class="space-y-2">
                      <div v-for="plan in course.available_plans" :key="plan.id" class="flex items-center justify-between bg-purple-50 p-2 rounded">
                        <span class="text-sm font-medium text-purple-900">{{ plan.name }}</span>
                        <span class="text-sm text-purple-600">₹{{ plan.price }}/{{ plan.interval }}</span>
                      </div>
                    </div>
                  </div>
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
          :slug="courseSlug"
          @progress-updated="handleProgressUpdate"
        />
      </div>
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
import { useMaskedNavigation } from '../utils/navigation';

export default {
  name: 'CourseDetail',
  components: {
    CourseProgressTracker,
    ReviewSummary,
    ReviewList
  },
  props: {
    slug: {
      type: String,
      default: null
    },
    id: {
      type: [String, Number],
      default: null
    }
  },
  setup(props) {
    const route = useRoute();
    const authStore = useAuthStore();
    const { goto } = useMaskedNavigation();
    const course = ref(null);
    const loading = ref(true);
    const expandedSections = ref([]);
    const isInWishlist = ref(false);
    const enrolling = ref(false);
    const imageLoading = ref(true);
    const imageError = ref(false);

    // Enrollment modal (removed - using simplified logic)
    // const showEnrollModal = ref(false);
    // const selectedEnrollOption = ref(null);
    // const processingEnrollment = ref(false);

    // Test-related variables
    const hasTest = ref(false);
    const testPassed = ref(false);
    const testData = ref(null);

    // Computed for course sections
    const courseSections = computed(() => {
      return course.value?.sections || [];
    });

    // Computed for requirements
  const formattedRequirements = computed(() => {
  if (!course.value?.requirements) return []

  if (Array.isArray(course.value.requirements)) {
    return course.value.requirements
  }

  if (typeof course.value.requirements === 'string') {
    return course.value.requirements
      .split('•')
      .map(r => r.replace(/^[-–•\s]+/, '').trim())
      .filter(r => r.length > 0)
  }

  return []
})


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

      const imageFields = ['image_url', 'image', 'thumbnail', 'thumbnail_url', 'cover_image'];

      for (const field of imageFields) {
        if (courseData[field] && courseData[field].trim() !== '') {
          return courseData[field];
        }
      }

      return '';
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

    // Fetch course function
    const fetchCourse = async () => {
      try {
        loading.value = true;
        imageLoading.value = true;
        imageError.value = false;

        // Get course ID/slug from props (masked routing) or route params (fallback)
        const courseId = props.slug || props.id || route.params.id || route.params.slug;
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

    // Handle enroll button click
    const handleEnrollClick = () => {
      if (!authStore.isAuthenticated) {
        goto('login');
        return;
      }

      // Check if course requires subscription (is in any plan)
      if (course.value.requires_subscription || course.value.available_plans?.length > 0) {
        // Redirect to pricing page for subscription courses
        goto('pricing');
      } else {
        // Direct enrollment for regular courses (not in any plan)
        enrollCourse();
      }
    };

    // Select enrollment option (removed - using simplified logic)
    // const selectOption = (option) => {
    //   selectedEnrollOption.value = option;
    // };

    // Close modal (removed - using simplified logic)
    // const closeEnrollModal = () => {
    //   if (!processingEnrollment.value) {
    //     showEnrollModal.value = false;
    //     selectedEnrollOption.value = null;
    //   }
    // };

    // Proceed with selected enrollment option (removed - using simplified logic)
    // const proceedWithEnrollment = async () => {
    //   if (!selectedEnrollOption.value) return;
    //   processingEnrollment.value = true;
    //   try {
    //     if (selectedEnrollOption.value === 'subscription') {
    //       window.location.href = '/pricing';
    //     } else if (selectedEnrollOption.value === 'purchase') {
    //       await enrollCourse();
    //       closeEnrollModal();
    //     }
    //   } catch (error) {
    //     console.error('Error processing enrollment:', error);
    //   } finally {
    //     processingEnrollment.value = false;
    //   }
    // };

    // Enroll in course
    const enrollCourse = async () => {
      if (!authStore.isAuthenticated) {
        goto('login');
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
            goto('studentCourse', { slug: props.slug || course.value?.slug });
          }, 1000);
        }
      } catch (error) {
        console.error('Error enrolling in course:', error);

        if (error.response?.status === 422 && error.response.data.requires_subscription) {
          // Course requires subscription - redirect to pricing
          goto('pricing');
        } else if (error.response?.status === 401) {
          authStore.clearAuth();
          goto('login');
        } else {
          // Show error message
          console.error('Enrollment error:', error.response?.data?.message || 'Failed to enroll');
        }
      } finally {
        enrolling.value = false;
      }
    };

    const toggleWishlist = async () => {
      if (!authStore.isAuthenticated) {
        goto('login');
        return;
      }

      try {
        const courseSlug = props.slug || course.value?.slug;
        if (!courseSlug) {
          console.error('No course slug available');
          return;
        }
        if (isInWishlist.value) {
          await axios.delete(`/courses/${courseSlug}/wishlist`);
        } else {
          await axios.post(`/courses/${courseSlug}/wishlist`);
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
      goto('studentCourse', { slug: props.slug || course.value?.slug });
    };

    // Test-related methods
    const checkTestAvailability = async () => {
      if (!course.value?.id) return;

      try {
        const statusRes = await axios.get(`/test/status/${course.value.id}`);
        const st = statusRes.data;
        hasTest.value = !!st.hasTest;
        testPassed.value = !!st.passed;
        testData.value = st;
      } catch (error) {
        console.log('No test available for this course');
        hasTest.value = false;
      }
    };

    const checkTestStatus = async () => {
      if (!course.value?.id) return;
      try {
        const statusRes = await axios.get(`/test/status/${course.value.id}`);
        testPassed.value = !!statusRes.data.passed;
      } catch (e) {}
    };

    const startTest = () => {
      goto('courseTest', { courseId: course.value.id });
    };

    const downloadCertificate = async () => {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(`/certificate/download/${course.value.id}` , {
          headers: { Authorization: `Bearer ${token}`, Accept: 'application/pdf' },
          responseType: 'blob'
        });
        const blob = new Blob([response.data], { type: 'application/pdf' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `certificate_${course.value.id}.pdf`;
        a.click();
        window.URL.revokeObjectURL(url);
      } catch (e) {
        alert('Certificate download failed');
      }
    };

    // Helper methods for enrollment button
    const getEnrollButtonText = () => {
      if (enrolling.value) {
        return 'Enrolling...';
      }

      if (course.value?.is_enrolled) {
        return 'Start Learning';
      }

      // Check if course is in any plan (subscription course)
      if (course.value?.requires_subscription || course.value?.available_plans?.length > 0) {
        return 'Subscribe to Access';
      }

      return 'Buy Now';
    };

    const getEnrollButtonClass = () => {
      if (course.value?.is_enrolled) {
        return 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500';
      }

      // Check if course is in any plan (subscription course)
      if (course.value?.requires_subscription || course.value?.available_plans?.length > 0) {
        return 'bg-purple-600 text-white hover:bg-purple-700 focus:ring-purple-500';
      }

      return 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500';
    };

    onMounted(async () => {
      const token = localStorage.getItem('auth_token');
      if (token && !authStore.token) {
        authStore.setAuth(JSON.parse(localStorage.getItem('user') || '{}'), token);
      }

      await fetchCourse();

      if (course.value?.id) {
        await checkTestAvailability();
        await checkTestStatus();
      }
    });

    // Computed property for slug to use in template
    const courseSlug = computed(() => props.slug || course.value?.slug);

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
      courseSlug, // Expose slug for template
      // showEnrollModal, (removed)
      // selectedEnrollOption, (removed)
      // processingEnrollment, (removed)
      getCourseImage,
      handleImageError,
      handleImageLoad,
      getProgressPercentage,
      getEnrollmentStatusText,
      handleEnrollClick,
      // selectOption, (removed)
      // closeEnrollModal, (removed)
      // proceedWithEnrollment, (removed)
      enrollCourse,
      toggleWishlist,
      handleProgressUpdate,
      goToCourse,
      hasTest,
      testPassed,
      testData,
      startTest,
      downloadCertificate,
      getEnrollButtonText,
      getEnrollButtonClass
    };
  }
};
</script>

<style scoped>
/* Modal animations */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Smooth transitions */
.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Radio button styling */
input[type="radio"]:checked {
  background-color: #4f46e5;
  border-color: #4f46e5;
}

/* Hover effects for options */
.border-2:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Backdrop blur effect */
.bg-opacity-75 {
  backdrop-filter: blur(4px);
}

/* Smooth scroll */
.overflow-y-auto {
  scroll-behavior: smooth;
}

/* Focus styles */
button:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
}

button:focus-visible {
  box-shadow: 0 0 0 2px #4f46e5;
}

/* Disabled state */
button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

/* Loading spinner */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Responsive modal */
@media (max-width: 640px) {
  .sm\:max-w-lg {
    max-width: calc(100% - 2rem);
  }

  .modal-panel {
    margin: 1rem;
  }
}

/* Prose styling for description */
.prose {
  max-width: none;
}

.prose h1,
.prose h2,
.prose h3 {
  font-weight: 600;
  margin-top: 1.5em;
  margin-bottom: 0.75em;
}

.prose p {
  margin-bottom: 1em;
}

.prose ul {
  list-style-type: disc;
  padding-left: 1.5em;
  margin-bottom: 1em;
}

.prose ol {
  list-style-type: decimal;
  padding-left: 1.5em;
  margin-bottom: 1em;
}

/* Progress bar animation */
.bg-indigo-600 {
  transition: width 0.5s ease-in-out;
}

/* Card hover effects */
.rounded-lg:hover {
  transform: translateY(-2px);
  transition: transform 0.2s ease-in-out;
}

/* Image loading placeholder */
.bg-gray-200 {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

/* Checkmark animation */
.text-green-500 {
  animation: checkmark 0.3s ease-in-out;
}

@keyframes checkmark {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}

/* Modal content spacing */
.space-y-1 > * + * {
  margin-top: 0.25rem;
}

.space-y-2 > * + * {
  margin-top: 0.5rem;
}

/* Button ripple effect */
button {
  position: relative;
  overflow: hidden;
}

button::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

button:active::after {
  width: 300px;
  height: 300px;
}

/* Smooth color transitions */
.border-indigo-200,
.border-gray-200 {
  transition: border-color 0.3s ease;
}

/* Selected option highlight */
input[type="radio"]:checked + div {
  background-color: rgba(99, 102, 241, 0.05);
}

/* Price emphasis */
.text-xl.font-bold {
  letter-spacing: -0.025em;
}

/* Feature list styling */
.flex.items-center.text-xs {
  line-height: 1.5;
}

/* Modal shadow */
.shadow-xl {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Responsive text sizing */
@media (max-width: 768px) {
  .text-lg {
    font-size: 1rem;
  }

  .text-xl {
    font-size: 1.125rem;
  }

  .text-2xl {
    font-size: 1.5rem;
  }
}

/* Accessibility improvements */    
*:focus-visible {
  outline: 2px solid #4f46e5;
  outline-offset: 2px;
}

/* Print styles */
@media print {
  .fixed {
    display: none;
  }
}
</style>
