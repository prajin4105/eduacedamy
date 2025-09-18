<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Page header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">All Courses</h1>
      </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="flex flex-col md:flex-row gap-6">
          <!-- Filters sidebar -->
          <div class="w-full md:w-1/4 space-y-6">
            <!-- Search -->
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Search</h3>
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search courses..."
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Categories -->
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>
              <div class="space-y-2">
                <div v-for="category in categories" :key="category.id" class="flex items-center">
                  <input
                    :id="`category-${category.id}`"
                    v-model="selectedCategories"
                    type="checkbox"
                    :value="category.id"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label :for="`category-${category.id}`" class="ml-3 text-sm text-gray-700">
                    {{ category.name }} ({{ category.course_count }})
                  </label>
                </div>
              </div>
            </div>

            <!-- Level -->
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Level</h3>
              <div class="space-y-2">
                <div v-for="level in levels" :key="level.id" class="flex items-center">
                  <input
                    :id="`level-${level.id}`"
                    v-model="selectedLevels"
                    type="checkbox"
                    :value="level.id"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label :for="`level-${level.id}`" class="ml-3 text-sm text-gray-700">
                    {{ level.name }}
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Course list -->
          <div class="w-full md:w-3/4">
            <!-- Sort and filter bar -->
            <div class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6 rounded-t-lg">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                <p class="text-sm text-gray-700 mb-2 sm:mb-0">
                  Showing
                  <span class="font-medium">{{ filteredCourses.length }}</span>
                  {{ filteredCourses.length === 1 ? 'course' : 'courses' }}
                </p>
                <div class="flex items-center">
                  <label for="sort" class="mr-2 text-sm font-medium text-gray-700">Sort by:</label>
                  <select
                    id="sort"
                    v-model="sortBy"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                  >
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                    <option value="rating">Highest Rated</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Loading state -->
            <div v-if="loading" class="flex justify-center items-center py-12">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
            </div>

            <!-- No results -->
            <div v-else-if="filteredCourses.length === 0" class="bg-white p-8 text-center rounded-b-lg">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
              <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
              <div class="mt-6">
                <button
                  type="button"
                  @click="resetFilters"
                  class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Reset all filters
                </button>
              </div>
            </div>

            <!-- Course grid -->
            <div v-else class="bg-white shadow overflow-hidden sm:rounded-b-lg">
              <ul role="list" class="divide-y divide-gray-200">
                <li v-for="course in paginatedCourses" :key="course.id" class="px-4 py-6 sm:px-6 hover:bg-gray-50">
                  <div class="flex flex-col sm:flex-row">
                    <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6">
                      <img class="h-32 w-full sm:w-48 object-cover rounded-lg" :src="course.image || 'https://via.placeholder.com/300x200'" :alt="course.title" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center text-sm text-gray-500 mb-1">
                        <span class="text-indigo-600 font-medium">{{ course.category || 'General' }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ course.level || 'All Levels' }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ course.lessons_count || 0 }} {{ course.lessons_count === 1 ? 'Lesson' : 'Lessons' }}</span>
                      </div>
                      <h2 class="text-xl font-bold text-gray-900 truncate">
                        <router-link :to="`/courses/${course.slug}`" class="hover:text-indigo-600">
                          {{ course.title }}
                        </router-link>
                      </h2>
                      <p class="mt-1 text-gray-600 line-clamp-2">{{ course.excerpt || 'No description available' }}</p>
                      
                      <div class="mt-3 flex items-center">
                        <div class="flex items-center">
                          <div class="flex items-center">
                            <svg v-for="i in 5" :key="i" 
                                 :class="[i <= Math.round(course.rating || 0) ? 'text-yellow-400' : 'text-gray-300', 'h-4 w-4 flex-shrink-0']" 
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          </div>
                          <p class="ml-2 text-sm text-gray-600">
                            <span class="text-gray-900 font-medium">{{ course.rating?.toFixed(1) || '0.0' }}</span>
                            <span class="text-gray-500">({{ course.reviews_count || 0 }})</span>
                          </p>
                        </div>
                        <span class="mx-2 text-gray-300">•</span>
                        <div class="text-sm text-gray-500">
                          {{ course.enrollments_count || 0 }} {{ course.enrollments_count === 1 ? 'student' : 'students' }}
                        </div>
                      </div>
                      
                      <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                          <div class="flex-shrink-0">
                            <img class="h-8 w-8 rounded-full" :src="course.instructor?.avatar || `https://ui-avatars.com/api/?name=${course.instructor?.name || 'Instructor'}`" :alt="course.instructor?.name" />
                          </div>
                          <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                              {{ course.instructor?.name || 'Instructor' }}
                            </p>
                          </div>
                        </div>
                        <div class="flex items-center space-x-3">
                          <div class="text-lg font-bold text-gray-900">
                            {{ course.price > 0 ? `$${course.price.toFixed(2)}` : 'Free' }}
                          </div>
                          <!-- Enrollment Status -->
                          <div v-if="course.is_enrolled" class="flex items-center">
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
                      </div>
                    </div>
                  </div>
                </li>
              </ul>

              <!-- Pagination -->
              <div v-if="totalPages > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <button
                    @click="currentPage = Math.max(1, currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    Previous
                  </button>
                  <button
                    @click="currentPage = Math.min(totalPages, currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    Next
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Showing <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                      to <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredCourses.length) }}</span>
                      of <span class="font-medium">{{ filteredCourses.length }}</span> results
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button
                        @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                      >
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>
                      
                      <button
                        v-for="page in visiblePages"
                        :key="page"
                        @click="currentPage = page"
                        :class="{
                          'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': currentPage === page,
                          'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page
                        }"
                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                      >
                        {{ page }}
                      </button>
                      
                      <button
                        @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                      >
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </nav>
                  </div>
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
import { ref, computed, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(true);
const courses = ref([]);
const categories = ref([
  { id: 'web', name: 'Web Development', course_count: 24 },
  { id: 'mobile', name: 'Mobile Development', course_count: 15 },
  { id: 'design', name: 'Design', course_count: 18 },
  { id: 'marketing', name: 'Marketing', course_count: 12 },
  { id: 'business', name: 'Business', course_count: 20 },
]);

const levels = [
  { id: 'beginner', name: 'Beginner' },
  { id: 'intermediate', name: 'Intermediate' },
  { id: 'advanced', name: 'Advanced' },
];

const searchQuery = ref('');
const selectedCategories = ref([]);
const selectedLevels = ref([]);
const sortBy = ref('newest');
const currentPage = ref(1);
const itemsPerPage = 10;

// Fetch courses from API
const fetchCourses = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/courses');
    courses.value = response.data;
  } catch (error) {
    console.error('Error fetching courses:', error);
  } finally {
    loading.value = false;
  }
};

// Filter and sort courses
const filteredCourses = computed(() => {
  let result = [...courses.value];
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(course => 
      course.title.toLowerCase().includes(query) || 
      course.description?.toLowerCase().includes(query) ||
      course.instructor?.name?.toLowerCase().includes(query)
    );
  }
  
  // Apply category filter
  if (selectedCategories.value.length > 0) {
    result = result.filter(course => 
      selectedCategories.value.includes(course.category?.toLowerCase())
    );
  }
  
  // Apply level filter
  if (selectedLevels.value.length > 0) {
    result = result.filter(course => 
      selectedLevels.value.includes(course.level?.toLowerCase())
    );
  }
  
  // Apply sorting
  switch (sortBy.value) {
    case 'newest':
      result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      break;
    case 'oldest':
      result.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
      break;
    case 'price_asc':
      result.sort((a, b) => a.price - b.price);
      break;
    case 'price_desc':
      result.sort((a, b) => b.price - a.price);
      break;
    case 'popular':
      result.sort((a, b) => (b.enrollments_count || 0) - (a.enrollments_count || 0));
      break;
    case 'rating':
      result.sort((a, b) => (b.rating || 0) - (a.rating || 0));
      break;
  }
  
  return result;
});

// Pagination
const totalPages = computed(() => Math.ceil(filteredCourses.value.length / itemsPerPage));

const paginatedCourses = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredCourses.value.slice(start, end);
});

const visiblePages = computed(() => {
  const pages = [];
  const maxVisiblePages = 5;
  let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
  let endPage = startPage + maxVisiblePages - 1;
  
  if (endPage > totalPages.value) {
    endPage = totalPages.value;
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  
  return pages;
});

// Reset filters
const resetFilters = () => {
  searchQuery.value = '';
  selectedCategories.value = [];
  selectedLevels.value = [];
  sortBy.value = 'newest';
  currentPage.value = 1;
};

// Watch for route changes to update filters
watch(() => router.currentRoute.value.query, (newQuery) => {
  if (newQuery.category) {
    selectedCategories.value = Array.isArray(newQuery.category) 
      ? newQuery.category 
      : [newQuery.category];
  }
  
  if (newQuery.level) {
    selectedLevels.value = Array.isArray(newQuery.level)
      ? newQuery.level
      : [newQuery.level];
  }
  
  if (newQuery.search) {
    searchQuery.value = newQuery.search;
  }
  
  if (newQuery.sort) {
    sortBy.value = newQuery.sort;
  }
  
  if (newQuery.page) {
    const page = parseInt(newQuery.page);
    if (!isNaN(page) && page > 0 && page <= totalPages.value) {
      currentPage.value = page;
    }
  }
}, { immediate: true });

// Watch for filter changes to update URL
watch([searchQuery, selectedCategories, selectedLevels, sortBy, currentPage], () => {
  const query = {};
  
  if (searchQuery.value) {
    query.search = searchQuery.value;
  }
  
  if (selectedCategories.value.length > 0) {
    query.category = selectedCategories.value;
  }
  
  if (selectedLevels.value.length > 0) {
    query.level = selectedLevels.value;
  }
  
  if (sortBy.value !== 'newest') {
    query.sort = sortBy.value;
  }
  
  if (currentPage.value > 1) {
    query.page = currentPage.value;
  }
  
  // Update URL without reloading the page
  router.replace({ query });
});

// Initialize
onMounted(fetchCourses);
</script>
