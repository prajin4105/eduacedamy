<template>
    <div v-if="loading" class="py-12 flex justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
  
    <div v-else-if="course" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Course Header -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h1 class="text-3xl font-bold text-gray-900">{{ course.title }}</h1>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ course.subtitle }}</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Course Info -->
            <div class="md:col-span-2">
              <div class="prose max-w-none" v-html="course.description"></div>
              
              <div v-if="course.learning_outcomes" class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">What you'll learn</h3>
                <ul class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                  <li v-for="(outcome, index) in course.learning_outcomes" :key="index" class="flex items-start">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ outcome }}</span>
                  </li>
                </ul>
              </div>
  
              <div v-if="course.requirements" class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Requirements</h3>
                <ul class="mt-2 space-y-1">
                  <li v-for="(req, index) in course.requirements" :key="index" class="flex items-start">
                    <span class="text-gray-500 mr-2">•</span>
                    <span>{{ req }}</span>
                  </li>
                </ul>
              </div>
            </div>
  
            <!-- Course Sidebar -->
            <div class="md:col-span-1">
              <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="aspect-w-16 aspect-h-9 mb-4">
                  <img :src="course.image_url || 'https://via.placeholder.com/400x225'" :alt="course.title" class="w-full h-full object-cover rounded">
                </div>
                
                <div class="mt-4">
                  <div class="flex items-baseline">
                    <span class="text-2xl font-bold text-gray-900">${{ course.price }}</span>
                    <span v-if="course.original_price" class="ml-2 text-sm text-gray-500 line-through">${{ course.original_price }}</span>
                  </div>
  
                  <div class="mt-4 space-y-4">
                    <!-- Enrollment Status -->
                    <div v-if="course.is_enrolled" class="mb-4">
                      <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-medium text-gray-900">{{ course.enrollment_status === 'completed' ? '100%' : '0%' }}</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                          class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                          :style="`width: ${course.enrollment_status === 'completed' ? '100' : '0'}%`"
                        ></div>
                      </div>
                      <div class="mt-2">
                        <span 
                          :class="[
                            'px-2 py-1 rounded-full text-xs font-medium',
                            course.enrollment_status === 'completed' 
                              ? 'bg-green-100 text-green-800'
                              : course.enrollment_status === 'in_progress'
                              ? 'bg-yellow-100 text-yellow-800'
                              : 'bg-gray-100 text-gray-800'
                          ]"
                        >
                          {{ course.enrollment_status === 'completed' ? 'Completed' : 
                             course.enrollment_status === 'in_progress' ? 'In Progress' : 
                             'Enrolled' }}
                        </span>
                      </div>
                    </div>

                    <button
                      @click="course.is_enrolled ? goToCourse() : enrollCourse()"
                      :class="[
                        'w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2',
                        course.is_enrolled 
                          ? 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500'
                          : 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500'
                      ]"
                    >
                      {{ course.is_enrolled ? 'Start Learning' : 'Enroll Now' }}
                    </button>
  
                    <button
                      @click="toggleWishlist"
                      class="w-full bg-white text-gray-700 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      {{ isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
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
                    </ul>
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
          @progress-updated="handleProgressUpdate"
        />
      </div>

      <!-- Course Content -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h2 class="text-xl font-semibold text-gray-900">Course Content</h2>
        </div>
        <div class="border-t border-gray-200">
          <div v-for="(section, sectionIndex) in courseSections" :key="sectionIndex" class="border-b border-gray-200">
            <button
              @click="toggleSection(sectionIndex)"
              class="w-full px-4 py-4 text-left hover:bg-gray-50 focus:outline-none"
            >
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">{{ section.title }}</h3>
                <span class="text-gray-500">
                  {{ section.lessons.length }} {{ section.lessons.length === 1 ? 'lesson' : 'lessons' }}
                </span>
              </div>
            </button>
            <div v-show="expandedSections.includes(sectionIndex)" class="bg-gray-50">
              <div v-for="(lesson, lessonIndex) in section.lessons" :key="lessonIndex" class="border-t border-gray-200">
                <div class="px-6 py-3 flex items-center">
                  <span class="text-gray-500 text-sm mr-4">{{ lessonIndex + 1 }}</span>
                  <span class="text-gray-700">{{ lesson.title }}</span>
                  <span class="ml-auto text-sm text-gray-500">{{ lesson.duration }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Instructor -->
      <div v-if="course.instructor" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h2 class="text-xl font-semibold text-gray-900">Instructor</h2>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
          <div class="flex items-start">
            <img class="h-16 w-16 rounded-full" :src="course.instructor.avatar_url" :alt="course.instructor.name">
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900">{{ course.instructor.name }}</h3>
              <p class="text-gray-500">{{ course.instructor.title }}</p>
              <p class="mt-2 text-gray-600">{{ course.instructor.bio }}</p>
            </div>
          </div>
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
  
  export default {
    name: 'CourseDetail',
    components: {
      CourseProgressTracker
    },
    setup() {
      const route = useRoute();
      const authStore = useAuthStore();
      const course = ref(null);
      const loading = ref(true);
      const expandedSections = ref([]);
      const isEnrolled = ref(false);
      const isInWishlist = ref(false);
  
      const courseSections = computed(() => {
        return course.value?.sections || [];
      });
  
      const toggleSection = (index) => {
        const sectionIndex = expandedSections.value.indexOf(index);
        if (sectionIndex > -1) {
          expandedSections.value.splice(sectionIndex, 1);
        } else {
          expandedSections.value.push(index);
        }
      };
  
      const fetchCourse = async () => {
        try {
          const response = await axios.get(`/api/courses/${route.params.slug}`);
          course.value = response.data;
          
          // Update enrollment status
          isEnrolled.value = course.value.is_enrolled;
          
          // Expand first section by default
          if (courseSections.value.length > 0) {
            expandedSections.value = [0];
          }
        } catch (error) {
          console.error('Error fetching course:', error);
        } finally {
          loading.value = false;
        }
      };
  
      const enrollCourse = async () => {
        console.log('Enroll course clicked');
        console.log('Auth store authenticated:', authStore.isAuthenticated);
        console.log('Course ID:', course.value?.id);
        
        // Check if user is authenticated using auth store
        if (!authStore.isAuthenticated) {
          console.log('User not authenticated, redirecting to login');
          // Redirect to login with a return URL
          window.location.href = `/login?redirect=${encodeURIComponent(route.fullPath)}`;
          return;
        }

        try {
          console.log('Attempting to enroll in course:', course.value.id);
          // Use the correct enrollment endpoint
          const response = await axios.post('/api/enrollments', {
            course_id: course.value.id
          });
          
          console.log('Enrollment response:', response.data);
          
          if (response.data.message) {
            isEnrolled.value = true;
            // Redirect to course player
            window.location.href = `/course/${route.params.slug}`;
          }
        } catch (error) {
          console.error('Error enrolling in course:', error);
          console.error('Error response:', error.response?.data);
          if (error.response?.status === 401) {
            // Token expired, clear auth and redirect to login
            authStore.clearAuth();
            window.location.href = `/login?redirect=${encodeURIComponent(route.fullPath)}`;
          }
        }
      };
  
      const toggleWishlist = async () => {
        if (!authStore.isAuthenticated) {
          window.location.href = `/login?redirect=${encodeURIComponent(route.fullPath)}`;
          return;
        }
  
        try {
          if (isInWishlist.value) {
            await axios.delete(`/api/courses/${route.params.slug}/wishlist`);
          } else {
            await axios.post(`/api/courses/${route.params.slug}/wishlist`);
          }
          isInWishlist.value = !isInWishlist.value;
        } catch (error) {
          console.error('Error updating wishlist:', error);
        }
      };

      const handleProgressUpdate = (data) => {
        // Handle progress update from CourseProgressTracker
        console.log('Progress updated:', data);
        // You can add additional logic here if needed
      };

      const goToCourse = () => {
        // Redirect to course player
        window.location.href = `/course/${route.params.slug}`;
      };
  
      onMounted(() => {
        fetchCourse();
        
        // Debug authentication state
        console.log('CourseDetail mounted');
        console.log('Auth store authenticated:', authStore.isAuthenticated);
        console.log('Auth store token:', authStore.token);
        console.log('Local storage token:', localStorage.getItem('auth_token'));
        console.log('Axios auth header:', axios.defaults.headers.common['Authorization']);
        
        // Force refresh auth store from localStorage
        const token = localStorage.getItem('auth_token');
        if (token && !authStore.token) {
          console.log('Refreshing auth store from localStorage');
          authStore.setAuth(JSON.parse(localStorage.getItem('user') || '{}'), token);
        }
        
        // Check if user is enrolled
        if (authStore.isAuthenticated) {
          // Add API call to check enrollment status
          // isEnrolled.value = ...
          // isInWishlist.value = ...
        }
      });
  
      return {
        course,
        loading,
        courseSections,
        expandedSections,
        isEnrolled,
        isInWishlist,
        toggleSection,
        enrollCourse,
        toggleWishlist,
        handleProgressUpdate,
        goToCourse
      };
    }
  };
  </script>