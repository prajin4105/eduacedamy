<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->



    <!-- Dashboard Header -->
    <div class="bg-indigo-600 text-white py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Student Dashboard</h1>
        <p class="mt-2 text-indigo-200">Welcome back, {{ user?.name }}! Continue your learning journey.</p>
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
              <p class="text-2xl font-bold text-gray-900">{{ enrolledCourses.length }}</p>
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
              <p class="text-2xl font-bold text-gray-900">{{ completedCount }}</p>
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
              <p class="text-2xl font-bold text-gray-900">{{ inProgressCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Enrolled Courses Section -->
      <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">My Enrolled Courses</h2>

        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>

        <div v-else-if="enrolledCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="enrollment in enrolledCourses" :key="enrollment.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <img :src="enrollment.course.image || '/placeholder/300/200'" :alt="enrollment.course.title" class="w-full h-48 object-cover" />
            <div class="p-6">
              <div class="flex items-center justify-between mb-2">
                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm font-medium">
                  {{ enrollment.course.level || 'All Levels' }}
                </span>
                <span :class="getStatusClass(enrollment.status)" class="px-2 py-1 rounded-full text-sm font-medium">
                  {{ enrollment.status }}
                </span>
              </div>

              <h3 class="text-xl font-semibold mb-2">{{ enrollment.course.title }}</h3>
              <p class="text-gray-600 mb-4">{{ enrollment.course.excerpt }}</p>

              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <img :src="enrollment.course.instructor?.avatar || `https://ui-avatars.com/api/?name=${enrollment.course.instructor?.name}`"
                       :alt="enrollment.course.instructor?.name"
                       class="w-8 h-8 rounded-full mr-3" />
                  <span class="text-sm text-gray-700">{{ enrollment.course.instructor?.name }}</span>
                </div>
                <span class="text-sm text-gray-500">
                  Enrolled: {{ formatDate(enrollment.enrolled_at) }}
                </span>
              </div>

              <!-- Progress Bar -->
              <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                  <span>Progress</span>
                  <span>{{ enrollment.progress || 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-indigo-600 h-2 rounded-full" :style="`width: ${enrollment.progress || 0}%`"></div>
                </div>
              </div>

              <div class="flex gap-2">
                <a @click.prevent="goto('studentCourse', { slug: enrollment.course.slug })" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-center cursor-pointer">
                  Continue Learning
                </a>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-12">
          <i class="fas fa-graduation-cap text-gray-400 text-6xl mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No enrolled courses yet</h3>
          <p class="text-gray-500 mb-6">Start your learning journey by enrolling in a course</p>
          <a @click.prevent="goto('courses')" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 cursor-pointer">
            Browse Courses
          </a>
        </div>
      </div>

      <!-- Available Courses Section -->
      <div>
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
                <button @click="buyCourse(course)" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
                  Enroll Now
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="message" :class="messageType === 'success' ? 'bg-green-500' : 'bg-red-500'" class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50">
      {{ message }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, inject } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useMaskedNavigation } from '../utils/navigation';

const { goto } = useMaskedNavigation();

const router = useRouter();
const user = inject('user');

const loading = ref(true);
const enrolledCourses = ref([]);
const availableCourses = ref([]);
const message = ref('');
const messageType = ref('success');
const csrfToken = window.Laravel.csrfToken;

const completedCount = computed(() => enrolledCourses.value.filter(e => e.status === 'completed').length);
const inProgressCount = computed(() => enrolledCourses.value.filter(e => e.status === 'pending' || e.status === 'in_progress').length);

const getStatusClass = (status) => {
  switch (status) {
    case 'completed': return 'bg-green-100 text-green-800';
    case 'pending': return 'bg-yellow-100 text-yellow-800';
    case 'cancelled': return 'bg-red-100 text-red-800';
    default: return 'bg-gray-100 text-gray-800';
  }
};

const formatDate = (dateString) => new Date(dateString).toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' });
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    const [enrollmentsRes, coursesRes] = await Promise.all([
      axios.get('/enrollments'),
      axios.get('/courses')
    ]);

    enrolledCourses.value = enrollmentsRes.data;

    // Fix for API returning object instead of array
    availableCourses.value = Array.isArray(coursesRes.data)
      ? coursesRes.data.slice(0, 8)
      : coursesRes.data.data?.slice(0, 8) || [];

    // Compute statistics
    statistics.value.total_courses = enrolledCourses.value.length;
    statistics.value.completed_courses = completedCourses.value.length;
    statistics.value.in_progress_courses = inProgressCourses.value.length;
    statistics.value.completion_rate = enrolledCourses.value.length
      ? Math.round((completedCourses.value.length / enrolledCourses.value.length) * 100)
      : 0;
    statistics.value.total_time_spent_seconds = enrolledCourses.value.reduce(
      (sum, c) => sum + (c.progress?.time_spent_seconds || 0),
      0
    );

  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = 'Failed to load dashboard';
  } finally {
    loading.value = false;
  }
};


// Load Razorpay SDK dynamically
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
    await loadRazorpay();

    // Step 1: Check if user is already enrolled
    const checkRes = await axios.post('/enrollments/check', { course_id: course.id });
    if (checkRes.data.already_enrolled) {
      message.value = 'You are already enrolled in this course.';
      messageType.value = 'error';
      return;
    }

    // Step 2: Create order
    const { data } = await axios.post("/create-order", { amount: course.price, currency: "INR" });
    if (!data.success) throw new Error(data.message || "Error creating order");

    // Step 3: Configure Razorpay
    const options = {
      key: data.key,
      amount: data.amount || course.price * 100, // paise
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
          message.value = enrollRes.data.message;
          messageType.value = "success";
          await fetchDashboardData();
        } catch (err) {
          console.error("Enroll error:", err.response || err);
          message.value = err.response?.data?.message || "Error enrolling in course";
          messageType.value = "error";
        }
      },
      theme: { color: "#3399cc" },
      modal: { escape: true, backdropclose: false },
    };

    // Step 4: Open Razorpay modal
    const rzp = new window.Razorpay(options);
    rzp.open();

  } catch (error) {
    console.error("Payment initiation error:", error);
    message.value = error.response?.data?.message || error.message || "Error initiating payment";
    messageType.value = "error";
  }
};


onMounted(fetchDashboardData);
</script>
