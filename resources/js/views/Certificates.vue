<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Certificates</h1>
        <p class="mt-2 text-gray-600">Download your course completion certificates</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-400"></i>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error</h3>
            <div class="mt-2 text-sm text-red-700">{{ error }}</div>
          </div>
        </div>
      </div>

      <!-- Certificates Grid -->
      <div v-else-if="certificates.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="certificate in certificates"
          :key="certificate.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200"
        >
          <!-- Course Image -->
          <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
            <div v-if="certificate.course?.image" class="w-full h-full">
              <img
                :src="certificate.course.image"
                :alt="certificate.course.title"
                class="w-full h-full object-cover"
              />
            </div>
            <div v-else class="text-white text-center">
              <i class="fas fa-certificate text-6xl mb-2"></i>
              <p class="text-lg font-semibold">Certificate</p>
            </div>
          </div>

          <!-- Certificate Info -->
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
              {{ certificate.course?.title || 'Course Removed' }}
            </h3>

            <p v-if="certificate.course?.instructor" class="text-sm text-gray-600 mb-2">
              <i class="fas fa-user mr-2"></i>
              Instructor: {{ certificate.course.instructor.name }}
            </p>

            <p class="text-sm text-gray-600 mb-4">
              <i class="fas fa-calendar mr-2"></i>
              Completed: {{ formatDate(certificate.issued_at) }}
            </p>

            <!-- Certificate Number -->
            <div class="bg-gray-50 rounded-md p-3 mb-4">
              <p class="text-xs text-gray-500 uppercase tracking-wide">Certificate Number</p>
              <p class="text-sm font-mono text-gray-900">{{ certificate.certificate_number }}</p>
            </div>

            <!-- Download Button -->
            <button
              @click="downloadCertificate(certificate)"
              :disabled="downloading === certificate.id || !certificate.course"
              class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
            >
              <i v-if="downloading === certificate.id" class="fas fa-spinner fa-spin mr-2"></i>
              <i v-else class="fas fa-download mr-2"></i>
              {{ downloading === certificate.id ? 'Downloading...' : 'Download Certificate' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Certificates Yet</h3>
        <p class="text-gray-600 mb-6">
          Complete your first course to earn your first certificate!
        </p>
        <router-link
          to="/courses"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <i class="fas fa-book mr-2"></i>
          Browse Courses
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const certificates = ref([]);
const loading = ref(true);
const error = ref(null);
const downloading = ref(null);

// Set default token if available
const token = localStorage.getItem('auth_token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Fetch certificates
const fetchCertificates = async () => {
  loading.value = true;
  error.value = null;

  try {
    const token = localStorage.getItem('auth_token');
    if (!token) {
      error.value = 'Please log in to view certificates';
      return;
    }

    const response = await axios.get('/certificates', {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json'
      }
    });

    // Filter out certificates whose course was removed OR leave them with course=null
    certificates.value = response.data.data.map(c => ({
      ...c,
      course: c.course || null
    }));

  } catch (err) {
    console.error('Error fetching certificates:', err);
    error.value = err.response?.data?.message || 'Failed to load certificates';
  } finally {
    loading.value = false;
  }
};

// Download certificate
const downloadCertificate = async (certificate) => {
  if (!certificate.course) {
    alert('This certificate’s course has been removed. Cannot download.');
    return;
  }

  downloading.value = certificate.id;

  try {
    const token = localStorage.getItem('auth_token');
    if (!token) {
      alert('Please log in to download certificates');
      return;
    }

    const response = await axios.post(
      `/courses/${certificate.course.id}/certificate/generate`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/pdf',
          'Content-Type': 'application/json',
        },
        responseType: 'blob'
      }
    );

    const blob = new Blob([response.data], { type: 'application/pdf' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute(
      'download',
      certificate.certificate_number
        ? `certificate_${certificate.certificate_number}.pdf`
        : `certificate_${certificate.course.title.replace(/\s+/g, '_')}.pdf`
    );
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);

  } catch (err) {
    console.error('Error downloading certificate:', err);
    alert(err.response?.data?.message || 'Failed to download certificate');
  } finally {
    downloading.value = null;
  }
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

// Test authentication
const testAuthentication = async () => {
  try {
    const token = localStorage.getItem('auth_token');
    if (!token) return false;

    const response = await axios.get('/me', {
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
    });

    console.log('User authenticated:', response.data);
    return true;
  } catch (err) {
    console.error('Authentication failed:', err);
    return false;
  }
};

// On mount
onMounted(async () => {
  const isAuthenticated = await testAuthentication();
  if (isAuthenticated) {
    fetchCertificates();
  } else {
    error.value = 'Please log in to view certificates';
    loading.value = false;
  }
});
</script>
