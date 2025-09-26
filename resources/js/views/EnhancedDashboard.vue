<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <div class="bg-indigo-600 text-white py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">My Courses</h1>
        <p class="mt-2 text-indigo-200">Welcome back, {{ user?.name || '' }}! Continue your learning journey.</p>
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

        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>

        <div v-else-if="enrolledCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="enrollment in enrolledCourses" :key="enrollment.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <img :src="enrollment.course?.image || '/placeholder/300/200'" :alt="enrollment.course?.title || 'Course'" class="w-full h-48 object-cover" />
            <div class="p-6">
              <div class="flex items-center justify-between mb-2">
                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm font-medium">
                  {{ enrollment.course?.level || 'All Levels' }}
                </span>
                <span :class="getStatusClass(enrollment.status)" class="px-2 py-1 rounded-full text-sm font-medium">
                  {{ formatStatus(enrollment.status) }}
                </span>
              </div>

              <h3 class="text-xl font-semibold mb-2">{{ enrollment.course?.title || 'Untitled Course' }}</h3>
              <p class="text-gray-600 mb-4">{{ enrollment.course?.excerpt || 'No description available' }}</p>

              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <img :src="getInstructorAvatar(enrollment.course?.instructor)"
                       :alt="enrollment.course?.instructor?.name || 'Instructor'"
                       class="w-8 h-8 rounded-full mr-3" />
                  <span class="text-sm text-gray-700">{{ enrollment.course?.instructor?.name || 'Unknown Instructor' }}</span>
                </div>
                <span class="text-sm text-gray-500">
                  Enrolled: {{ formatDate(enrollment.enrolled_at) }}
                </span>
              </div>

              <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                  <span>Progress</span>
                  <span>{{ getProgressPercentage(enrollment) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-indigo-600 h-2 rounded-full" :style="`width: ${getProgressPercentage(enrollment)}%`"></div>
                </div>
              </div>

              <div class="flex gap-2">
                <router-link
                  v-if="enrollment.course?.slug"
                  :to="`/course/${enrollment.course.slug}`"
                  class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-center"
                >
                  {{ enrollment.status === 'completed' ? 'Review Course' : 'Continue Learning' }}
                </router-link>
                <button
                  v-else
                  disabled
                  class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed text-center"
                >
                  Course Unavailable
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-12">
          <i class="fas fa-graduation-cap text-gray-400 text-6xl mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No enrolled courses yet</h3>
          <p class="text-gray-500 mb-6">Start your learning journey by enrolling in a course</p>
          <router-link to="/courses" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
            Browse Courses
          </router-link>
        </div>
      </div>

      <!-- Recommended Courses -->
      <div v-if="!loading">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Recommended Courses</h2>

        <div v-if="availableCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="course in availableCourses" :key="course.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <img :src="course.image || '/placeholder/300/200'" :alt="course.title" class="w-full h-40 object-cover" />
            <div class="p-4">
              <h3 class="text-lg font-semibold mb-2">{{ course.title }}</h3>
              <p class="text-gray-600 text-sm mb-3">{{ course.excerpt }}</p>

              <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">
                  <i class="fas fa-user"></i> {{ course.instructor?.name }}
                </span>
                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">
                  {{ course.level }}
                </span>
              </div>

              <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-green-600">${{ course.price }}</span>
                <button
                  @click="buyCourse(course)"
                  :disabled="purchasing"
                  class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 disabled:opacity-50"
                >
                  {{ purchasing ? 'Processing...' : 'Enroll Now' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-8">
          <p class="text-gray-500">No recommended courses available at the moment.</p>
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

// Helper functions
const getProgressPercentage = (enrollment) => {
  if (!enrollment) return 0;

  // Check multiple possible progress fields
  if (enrollment.progress_percentage !== undefined) {
    return Math.round(enrollment.progress_percentage);
  }

  if (enrollment.progress !== undefined) {
    return Math.round(enrollment.progress);
  }

  if (enrollment.completion_percentage !== undefined) {
    return Math.round(enrollment.completion_percentage);
  }

  // If status is completed, return 100%
  if (enrollment.status === 'completed') {
    return 100;
  }

  return 0;
};

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

// Fetch Dashboard Data
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    console.log('Fetching dashboard data...');

    // Fetch data with better error handling
    const requests = [
      axios.get('/me').catch(err => {
        console.warn('User fetch failed:', err);
        return { data: { name: 'Student' } };
      }),
      axios.get('/enrollments').catch(err => {
        console.warn('Enrollments fetch failed:', err);
        return { data: [] };
      }),
      axios.get('/courses').catch(err => {
        console.warn('Courses fetch failed:', err);
        return { data: [] };
      })
    ];

    const [userRes, enrollmentsRes, coursesRes] = await Promise.all(requests);

    // Set user data
    user.value = userRes.data;

    // Process enrollments data
    let enrollments = enrollmentsRes.data;
    if (Array.isArray(enrollments)) {
      enrolledCourses.value = enrollments;
    } else if (enrollments && enrollments.data && Array.isArray(enrollments.data)) {
      enrolledCourses.value = enrollments.data;
    } else {
      console.warn('Unexpected enrollments format:', enrollments);
      enrolledCourses.value = [];
    }

    // Process courses data
    let courses = coursesRes.data;
    if (Array.isArray(courses)) {
      availableCourses.value = courses.slice(0, 8);
    } else if (courses && courses.data && Array.isArray(courses.data)) {
      availableCourses.value = courses.data.slice(0, 8);
    } else {
      console.warn('Unexpected courses format:', courses);
      availableCourses.value = [];
    }

    // Update statistics
    updateStatistics();

    console.log('Dashboard data loaded successfully');

  } catch (err) {
    console.error('Dashboard fetch error:', err);
    message.value = err.response?.data?.message || 'Failed to load dashboard data';
    messageType.value = 'error';

    // Set default values
    user.value = { name: 'Student' };
    enrolledCourses.value = [];
    availableCourses.value = [];
  } finally {
    loading.value = false;
  }
};

const updateStatistics = () => {
  const totalCourses = enrolledCourses.value.length;
  const completed = completedCount.value;
  const inProgress = inProgressCount.value;

  statistics.value = {
    total_courses: totalCourses,
    completed_courses: completed,
    in_progress_courses: inProgress,
    completion_rate: totalCourses > 0 ? Math.round((completed / totalCourses) * 100) : 0,
    total_time_spent_seconds: enrolledCourses.value.reduce((sum, enrollment) => {
      return sum + (enrollment.time_spent_seconds || 0);
    }, 0)
  };
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
      message.value = 'You are already enrolled in this course.';
      messageType.value = 'error';
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

          message.value = enrollRes.data.message || 'Successfully enrolled in course!';
          messageType.value = "success";

          // Refresh dashboard data
          await fetchDashboardData();

        } catch (err) {
          console.error('Enrollment error:', err);
          message.value = err.response?.data?.message || "Error enrolling in course";
          messageType.value = "error";
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
    message.value = error.response?.data?.message || error.message || "Error initiating payment";
    messageType.value = "error";
  } finally {
    purchasing.value = false;
  }
};

// Auto-clear messages after 5 seconds
let messageTimeout;
const showMessage = (msg, type = 'success') => {
  message.value = msg;
  messageType.value = type;

  if (messageTimeout) clearTimeout(messageTimeout);
  messageTimeout = setTimeout(() => {
    message.value = '';
  }, 5000);
};

onMounted(fetchDashboardData);
</script>
