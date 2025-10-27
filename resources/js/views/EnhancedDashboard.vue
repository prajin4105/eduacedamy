<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <div class="bg-indigo-600 text-white py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">My Courses</h1>
        <p class="mt-2 text-indigo-200">
          Welcome back, {{ user?.name || '' }}! Continue your learning journey.
        </p>
      </div>
    </div>

    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
              <i class="fas fa-book text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Enrolled Courses</p>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.total_courses }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-check-circle text-xl"></i>
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
              <i class="fas fa-clock text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">In Progress</p>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.in_progress_courses }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Enrolled Courses -->
      <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">My Enrolled Courses</h2>

        <!-- Loading Spinner -->
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>

        <!-- Course Cards -->
        <div
          v-else-if="enrolledCourses.length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
        >
          <div
            v-for="enrollment in enrolledCourses.slice(0, coursesToShow)"
            :key="enrollment.id"
            class="flex flex-col bg-white rounded-xl shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg"
          >
            <!-- Course Image -->
            <img
              :src="enrollment.course?.image_url || '/placeholder/300/200'"
              :alt="enrollment.course?.title || 'Course'"
              class="w-full h-48 object-cover"
            />

            <!-- Course Details -->
            <div class="flex flex-col flex-1 p-6">
              <div class="flex items-center justify-between mb-3">
                <span
                  class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium"
                >
                  {{ enrollment.course?.level || 'All Levels' }}
                </span>
                <span class="text-sm text-gray-500">
                  Enrolled: {{ formatDate(enrollment.enrolled_at) }}
                </span>
              </div>

              <!-- Course Title (one line only) -->
              <h3
                class="text-lg font-semibold text-gray-900 truncate"
                :title="enrollment.course?.title"
              >
                {{ enrollment.course?.title || 'Untitled Course' }}
              </h3>

              <!-- Progress Bar -->
              <div class="mt-5">
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-700 font-medium">Progress</span>
                  <span class="font-semibold">{{ getProgressPercentage(enrollment) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                  <div
                    class="h-3 rounded-full transition-all duration-500"
                    :class="{
                      'bg-green-500': enrollment.status === 'completed',
                      'bg-indigo-600': enrollment.status === 'in_progress' || enrollment.status === 'active',
                      'bg-yellow-500': enrollment.status === 'pending'
                    }"
                    :style="{ width: getProgressPercentage(enrollment) + '%' }"
                  ></div>
                </div>
              </div>

              <!-- Action Button -->
              <div class="mt-6">
                <router-link
                  v-if="enrollment.course?.slug"
                  :to="`/course/${enrollment.course.slug}`"
                  class="block text-center bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition"
                >
                  {{ enrollment.status === 'completed' ? 'Go to Course' : 'Continue Learning' }}
                </router-link>
                <button
                  v-else
                  disabled
                  class="w-full bg-gray-300 text-gray-600 px-5 py-2.5 rounded-lg font-medium cursor-not-allowed"
                >
                  Course Unavailable
                </button>
              </div>
            </div>
          </div>

          <!-- Load More Button -->
          <div
            v-if="enrolledCourses.length > coursesToShow"
            class="flex justify-center mt-10 col-span-full"
          >
            <button
              @click="loadMoreCourses"
              class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition"
            >
              Load More
            </button>
          </div>
        </div>

        <!-- No Courses -->
        <div v-else class="text-center py-12">
          <i class="fas fa-graduation-cap text-gray-400 text-6xl mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No enrolled courses yet</h3>
          <p class="text-gray-500 mb-6">
            Start your learning journey by enrolling in a course
          </p>
          <router-link
            to="/courses"
            class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700"
          >
            Browse Courses
          </router-link>
        </div>
      </div>
    </div>

    <!-- Messages -->
    <div
      v-if="message"
      :class="messageType === 'success' ? 'bg-green-500' : 'bg-red-500'"
      class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50"
    >
      {{ message }}
      <button @click="clearMessage" class="ml-2 text-white hover:text-gray-200">×</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
const coursesToShow = ref(6); // initially show 6
const loadStep = 3; // show 3 more each time


const router = useRouter();
const user = ref(null);

// Dashboard Data
const loading = ref(true);
const purchasing = ref(false);
const enrolledCourses = ref([]);
const availableCourses = ref([]);
const message = ref('');
const messageType = ref('success');

// Statistics
const statistics = ref({
  total_courses: 0,
  completed_courses: 0,
  in_progress_courses: 0,
  completion_rate: 0,
  total_time_spent_seconds: 0
});
const loadMoreCourses = () => {
  coursesToShow.value += loadStep;
};

// Simplified Progress Percentage Function (perfect for your API structure)
const getProgressPercentage = (enrollment) => {
  if (!enrollment) return 0;

  // Your API directly provides progress_percentage on enrollment object
  if (enrollment.progress_percentage !== undefined && enrollment.progress_percentage !== null) {
    return Math.round(enrollment.progress_percentage);
  }

  // Fallback: Progress from nested progress object
  if (enrollment.progress?.progress_percentage !== undefined && enrollment.progress.progress_percentage !== null) {
    return Math.round(enrollment.progress.progress_percentage);
  }

  // Status-based fallback for completed courses
  if (enrollment.status === 'completed') return 100;
  if (enrollment.status === 'in_progress' || enrollment.status === 'active') return 25;

  return 0;
};

// Helper functions
const formatStatus = (status) => {
  if (!status) return 'Unknown';

  const statusMap = {
    'completed': 'Completed',
    'in_progress': 'In Progress',
    'pending': 'Pending',
    'cancelled': 'Cancelled',
    'active': 'Active'
  };

  return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1);
};

const getInstructorAvatar = (instructor) => {
  if (!instructor) return `https://ui-avatars.com/api/?name=Unknown`;

  if (instructor.avatar) return instructor.avatar;

  if (instructor.name) {
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(instructor.name)}`;
  }

  return `https://ui-avatars.com/api/?name=Instructor`;
};

const getStatusClass = (status) => {
  const classes = {
    'completed': 'bg-green-100 text-green-800',
    'in_progress': 'bg-blue-100 text-blue-800',
    'pending': 'bg-yellow-100 text-yellow-800',
    'cancelled': 'bg-red-100 text-red-800',
    'active': 'bg-blue-100 text-blue-800'
  };

  return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
  if (!dateString) return 'Unknown';

  try {
    return new Date(dateString).toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  } catch (error) {
    console.error('Error formatting date:', error);
    return 'Unknown';
  }
};

const formatTimeSpent = (timeInSeconds) => {
  if (!timeInSeconds) return '';

  const hours = Math.floor(timeInSeconds / 3600);
  const minutes = Math.floor((timeInSeconds % 3600) / 60);

  if (hours > 0) {
    return `${hours}h ${minutes}m`;
  }
  return `${minutes}m`;
};

const clearMessage = () => {
  message.value = '';
};

// Computed properties for statistics
const completedCount = computed(() => {
  return enrolledCourses.value.filter(e => e.status === 'completed').length;
});

const inProgressCount = computed(() => {
  return enrolledCourses.value.filter(e =>
    e.status === 'pending' ||
    e.status === 'in_progress' ||
    e.status === 'active'
  ).length;
});

// Updated Fetch Dashboard Data - Fixed for your API structure
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    console.log('Fetching dashboard data...');

    // Fetch data using your actual API endpoints
    const [userRes, dashboardRes, coursesRes] = await Promise.all([
      axios.get('/me').catch(err => {
        console.warn('User fetch failed:', err);
        return { data: { name: 'Student' } };
      }),
      // Use your dashboard progress endpoint
      axios.get('/dashboard/progress').catch(err => {
        console.warn('Dashboard progress fetch failed:', err);
        return { data: { data: { completed_courses: [], in_progress_courses: [], not_started_courses: [], statistics: {} } } };
      }),
      axios.get('/courses', {
        params: { limit: 8 }
      }).catch(err => {
        console.warn('Courses fetch failed:', err);
        return { data: [] };
      })
    ]);

    // Set user data
    user.value = userRes.data;

    // Process dashboard data - your API structure
    const dashboardData = dashboardRes.data.data;

    // Combine all enrolled courses from different categories
    const allEnrolledCourses = [
      ...dashboardData.completed_courses || [],
      ...dashboardData.in_progress_courses || [],
      ...dashboardData.not_started_courses || []
    ];

    // Process enrolled courses - map to expected structure
    enrolledCourses.value = allEnrolledCourses.map(item => {
      // Your API structure has enrollment nested inside
      const enrollment = item.enrollment;

      return {
        id: enrollment.id,
        user_id: enrollment.user_id,
        course_id: enrollment.course_id,
        amount_paid: enrollment.amount_paid,
        status: enrollment.status,
        enrolled_at: enrollment.enrolled_at,
        created_at: enrollment.created_at,
        updated_at: enrollment.updated_at,
        progress_percentage: enrollment.progress_percentage, // This is directly available!
        last_accessed_at: enrollment.last_accessed_at,
        payment_id: enrollment.payment_id,
        order_id: enrollment.order_id,
        signature: enrollment.signature,
        course: enrollment.course || item.course, // Use either nested course
        progress: {
          progress_percentage: enrollment.progress_percentage,
          is_completed: enrollment.status === 'completed'
        },
        completed_at: item.completed_at
      };
    });

    // Set statistics from API
    statistics.value = {
      total_courses: dashboardData.statistics?.total_courses || 0,
      completed_courses: dashboardData.statistics?.completed_courses || 0,
      in_progress_courses: dashboardData.statistics?.in_progress_courses || 0,
      completion_rate: dashboardData.statistics?.completion_rate || 0,
      total_time_spent_seconds: dashboardData.statistics?.total_time_spent_seconds || 0
    };

    // Process available courses for recommendations
    let courses = coursesRes.data;
    if (Array.isArray(courses)) {
      availableCourses.value = courses.slice(0, 8);
    } else if (courses && courses.data && Array.isArray(courses.data)) {
      availableCourses.value = courses.data.slice(0, 8);
    } else {
      console.warn('Unexpected courses format:', courses);
      availableCourses.value = [];
    }

    console.log('Dashboard data loaded successfully');
    console.log('Enrolled courses with progress:', enrolledCourses.value);
    console.log('Statistics:', statistics.value);

  } catch (err) {
    console.error('Dashboard fetch error:', err);
    showMessage(err.response?.data?.message || 'Failed to load dashboard data', 'error');

    // Set default values
    user.value = user.value || { name: 'Student' };
    enrolledCourses.value = enrolledCourses.value || [];
    availableCourses.value = availableCourses.value || [];
  } finally {
    loading.value = false;
  }
};

const updateStatistics = () => {
  // Statistics are now directly provided by your API
  // No need to recalculate as they come from /dashboard/progress endpoint
  console.log('Statistics updated from API:', statistics.value);
};

// Razorpay Integration
const loadRazorpay = () => new Promise((resolve, reject) => {
  if (window.Razorpay) return resolve(true);

  const script = document.createElement("script");
  script.src = "https://checkout.razorpay.com/v1/checkout.js";
  script.onload = () => resolve(true);
  script.onerror = () => reject(new Error("Razorpay SDK failed to load"));
  document.body.appendChild(script);
});

const buyCourse = async (course) => {
  if (purchasing.value) return;

  try {
    purchasing.value = true;
    await loadRazorpay();

    // Check if already enrolled
    const checkRes = await axios.post('/enrollments/check', {
      course_id: course.id
    });

    if (checkRes.data.already_enrolled) {
      showMessage('You are already enrolled in this course.', 'error');
      return;
    }

    // Create payment order
    const { data } = await axios.post("/create-order", {
      amount: course.price,
      currency: "INR"
    });

    if (!data.success) {
      throw new Error(data.message || "Error creating payment order");
    }

    const options = {
      key: data.key,
      amount: data.amount || course.price * 100,
      currency: data.currency || "INR",
      name: "EduAcademy",
      description: course.title,
      order_id: data.orderId,
      handler: async (response) => {
        try {
          const enrollRes = await axios.post("/enrollments", {
            course_id: course.id,
            payment_id: response.razorpay_payment_id,
            order_id: response.razorpay_order_id,
            signature: response.razorpay_signature,
          });

          showMessage(enrollRes.data.message || 'Successfully enrolled in course!', 'success');

          // Refresh dashboard data
          await fetchDashboardData();

        } catch (err) {
          console.error('Enrollment error:', err);
          showMessage(err.response?.data?.message || "Error enrolling in course", 'error');
        }
      },
      prefill: {
        name: user.value?.name || '',
        email: user.value?.email || '',
        contact: user.value?.phone || ''
      },
      theme: {
        color: "#4F46E5"
      },
      modal: {
        escape: true,
        backdrop_close: false
      },
    };

    new window.Razorpay(options).open();

  } catch (error) {
    console.error('Purchase error:', error);
    showMessage(error.response?.data?.message || error.message || "Error initiating payment", 'error');
  } finally {
    purchasing.value = false;
  }
};

// Message handling
let messageTimeout;
const showMessage = (msg, type = 'success') => {
  message.value = msg;
  messageType.value = type;

  if (messageTimeout) clearTimeout(messageTimeout);
  messageTimeout = setTimeout(() => {
    message.value = '';
  }, 5000);
};

// Debug function
const debugProgressData = () => {
  console.log('=== PROGRESS DEBUG ===');
  enrolledCourses.value.forEach((enrollment, index) => {
    console.log(`Enrollment ${index + 1}:`, {
      id: enrollment.id,
      course_title: enrollment.course?.title,
      status: enrollment.status,
      progress_object: enrollment.progress,
      progress_percentage: enrollment.progress_percentage,
      completed_lessons: enrollment.completed_lessons,
      total_lessons: enrollment.total_lessons,
      calculated_progress: getProgressPercentage(enrollment)
    });
  });
  console.log('=== END DEBUG ===');
};

onMounted(fetchDashboardData);

// Expose debug function to global scope for testing
if (process.env.NODE_ENV === 'development') {
  window.debugProgressData = debugProgressData;
}
</script>
