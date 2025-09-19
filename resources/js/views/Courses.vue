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
                  <svg
                    class="h-5 w-5 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817
                      4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Categories -->
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>
              <div class="space-y-2">
                <div
                  v-for="category in categories"
                  :key="category.id"
                  class="flex items-center"
                >
                  <input
                    :id="`category-${category.id}`"
                    v-model="selectedCategories"
                    type="checkbox"
                    :value="category.id || category.name"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label
                    :for="`category-${category.id}`"
                    class="ml-3 text-sm text-gray-700"
                  >
                    {{ category.name }}
                    <span v-if="category.courses_count" class="text-gray-500">({{ category.courses_count }})</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Levels -->
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Level</h3>
              <div class="space-y-2">
                <div
                  v-for="level in levels"
                  :key="level.value"
                  class="flex items-center"
                >
                  <input
                    :id="`level-${level.value}`"
                    v-model="selectedLevels"
                    type="checkbox"
                    :value="level.value"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label
                    :for="`level-${level.value}`"
                    class="ml-3 text-sm text-gray-700"
                  >
                    {{ level.name }}
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Course list -->
          <div class="w-full md:w-3/4">
            <!-- Sort + info -->
            <div
              class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6 rounded-t-lg flex flex-col sm:flex-row sm:items-center justify-between"
            >
              <p class="text-sm text-gray-700 mb-2 sm:mb-0">
                Showing
                <span class="font-medium">{{ courses.length }}</span>
                {{ courses.length === 1 ? "course" : "courses" }}
                <span v-if="lastPage > 1">
                  (Page {{ currentPage }} of {{ lastPage }})
                </span>
              </p>
              <div class="flex items-center">
                <label
                  for="sort"
                  class="mr-2 text-sm font-medium text-gray-700"
                  >Sort by:</label
                >
                <select
                  id="sort"
                  v-model="sortBy"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300
                  focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                  sm:text-sm rounded-md"
                >
                  <option value="latest">Latest</option>
                  <option value="popular">Most Popular</option>
                  <option value="rating">Highest Rated</option>
                  <option value="price_asc">Price: Low to High</option>
                  <option value="price_desc">Price: High to Low</option>
                </select>
              </div>
            </div>

            <!-- Loader -->
            <div
              v-if="loading"
              class="flex justify-center items-center py-12 bg-white rounded-b-lg"
            >
              <div
                class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"
              ></div>
              <span class="ml-3 text-gray-600">Loading courses...</span>
            </div>

            <!-- No results -->
            <div
              v-else-if="courses.length === 0"
              class="bg-white p-8 text-center rounded-b-lg shadow"
            >
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.5-.611-6.395-1.709" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">
                No courses found
              </h3>
              <p class="mt-1 text-sm text-gray-500">
                Try adjusting your search or filter criteria.
              </p>
              <div class="mt-6">
                <button
                  type="button"
                  @click="resetFilters"
                  class="inline-flex items-center px-4 py-2 border border-transparent
                  shadow-sm text-sm font-medium rounded-md text-white
                  bg-indigo-600 hover:bg-indigo-700 focus:outline-none
                  focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Reset all filters
                </button>
              </div>
            </div>

            <!-- Course cards -->
            <div
              v-else
              class="bg-white shadow overflow-hidden sm:rounded-b-lg"
            >
              <div class="divide-y divide-gray-200">
                <div
                  v-for="course in courses"
                  :key="course.id"
                  class="px-4 py-6 sm:px-6 hover:bg-gray-50 transition-colors duration-150"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h2 class="text-xl font-bold text-gray-900 mb-2 truncate">
                        <router-link :to="`/courses/${course.slug}`" class="hover:text-indigo-600">
                          {{ course.title }}
                        </router-link>
                      </h2>
                      <p class="text-gray-600 mb-3">
                        {{ course.description || course.excerpt || "No description available" }}
                      </p>

                      <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                        <span v-if="course.level" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          {{ course.level.charAt(0).toUpperCase() + course.level.slice(1) }}
                        </span>

                        <div v-if="course.categories && course.categories.length > 0" class="flex flex-wrap gap-1">
                          <span
                            v-for="category in course.categories"
                            :key="category.id"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                          >
                            {{ category.name }}
                          </span>
                        </div>

                        <span v-if="course.rating > 0" class="flex items-center">
                          <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                          </svg>
                          {{ course.rating }}/5 ({{ course.reviews_count }})
                        </span>

                        <span v-if="course.enrollments_count > 0" class="flex items-center">
                          <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                          </svg>
                          {{ course.enrollments_count }} {{ course.enrollments_count === 1 ? 'student' : 'students' }}
                        </span>

                        <span v-if="course.lessons_count > 0" class="flex items-center">
                          <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2h4a1 1 0 0 1 0 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a1 1 0 1 1 0-2h4zM9 3v1h6V3H9z" />
                          </svg>
                          {{ course.lessons_count }} {{ course.lessons_count === 1 ? 'lesson' : 'lessons' }}
                        </span>

                        <span v-if="course.duration" class="flex items-center">
                          <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ course.duration }}
                        </span>

                        <span v-if="course.instructor" class="flex items-center">
                          <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                          {{ course.instructor.name }}
                        </span>
                      </div>
                    </div>

                    <div class="ml-4 flex flex-col items-end">
                      <div v-if="course.price" class="text-right mb-2">
                        <span v-if="course.discounted_price" class="text-lg font-bold text-green-600">
                          ${{ course.discounted_price }}
                        </span>
                        <span v-if="course.discounted_price" class="text-sm text-gray-500 line-through ml-2">
                          ${{ course.price }}
                        </span>
                        <span v-else-if="parseFloat(course.price) > 0" class="text-lg font-bold text-gray-900">
                          ${{ course.price }}
                        </span>
                        <span v-else class="text-lg font-bold text-green-600">Free</span>
                      </div>
                      <span v-else class="text-lg font-bold text-green-600">Free</span>

                      <router-link
                        :to="`/courses/${course.slug}`"
                        class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        {{ course.is_enrolled ? 'Continue' : 'View Course' }}
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
              <div
                v-if="lastPage > 1"
                class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
              >
                <div class="flex-1 flex justify-between sm:hidden">
                  <button
                    @click="currentPage = Math.max(1, currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md',
                      currentPage === 1
                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                        : 'bg-white text-gray-700 hover:bg-gray-50'
                    ]"
                  >
                    Previous
                  </button>
                  <button
                    @click="currentPage = Math.min(lastPage, currentPage + 1)"
                    :disabled="currentPage === lastPage"
                    :class="[
                      'ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md',
                      currentPage === lastPage
                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                        : 'bg-white text-gray-700 hover:bg-gray-50'
                    ]"
                  >
                    Next
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Page <span class="font-medium">{{ currentPage }}</span> of
                      <span class="font-medium">{{ lastPage }}</span>
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button
                        @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        :class="[
                          'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500',
                          currentPage === 1 ? 'cursor-not-allowed' : 'hover:bg-gray-50'
                        ]"
                      >
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>

                      <button
                        v-for="page in visiblePages"
                        :key="page"
                        @click="currentPage = page"
                        :class="[
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                          currentPage === page
                            ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                        ]"
                      >
                        {{ page }}
                      </button>

                      <button
                        @click="currentPage = Math.min(lastPage, currentPage + 1)"
                        :disabled="currentPage === lastPage"
                        :class="[
                          'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500',
                          currentPage === lastPage ? 'cursor-not-allowed' : 'hover:bg-gray-50'
                        ]"
                      >
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end course list -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();

// 🔎 Filters - Initialize from URL params
const searchQuery = ref(route.query.search || "");
const selectedCategories = ref(
  Array.isArray(route.query.category)
    ? route.query.category
    : (route.query.category ? [route.query.category] : [])
);
const selectedLevels = ref(
  Array.isArray(route.query.level)
    ? route.query.level
    : (route.query.level ? [route.query.level] : [])
);
const sortBy = ref(route.query.sort || "latest");

// 📖 Pagination
const currentPage = ref(parseInt(route.query.page) || 1);
const lastPage = ref(1);

// 📚 Data
const courses = ref([]);
const categories = ref([]);
const loading = ref(false);

// Static levels definition
const levels = [
  { value: "beginner", name: "Beginner" },
  { value: "intermediate", name: "Intermediate" },
  { value: "advanced", name: "Advanced" },
];

// Computed for pagination
const visiblePages = computed(() => {
  const pages = [];
  const maxVisible = 5;
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let end = start + maxVisible - 1;

  if (end > lastPage.value) {
    end = lastPage.value;
    start = Math.max(1, end - maxVisible + 1);
  }

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

// 🔄 Fetch categories
const fetchCategories = async () => {
  try {
    const { data } = await axios.get("/categories");
    // Handle both direct array and nested data structure
    categories.value = Array.isArray(data) ? data : (data.data || []);
  } catch (error) {
    console.error("Error fetching categories:", error);
    categories.value = [];
  }
};

// 🔄 Fetch courses with enhanced error handling
const fetchCourses = async (page = 1) => {
  loading.value = true;
  try {
    const params = {
      page,
    };

    // Add search parameter
    if (searchQuery.value && searchQuery.value.trim()) {
      params.search = searchQuery.value.trim();
    }

    // Add category filters - send as array or single value based on your API
    if (selectedCategories.value.length > 0) {
      params.categories = selectedCategories.value; // or 'category' if your API expects that
    }

    // Add level filters
    if (selectedLevels.value.length > 0) {
      params.levels = selectedLevels.value; // or 'level' if your API expects that
    }

    // Add sort parameter
    if (sortBy.value && sortBy.value !== "latest") {
      params.sort = sortBy.value;
    }

    console.log("Fetching courses with params:", params); // Debug log

    const { data } = await axios.get("/courses", { params });

    console.log("API Response:", data); // Debug log

    // Handle your specific API response structure
    if (data.data && Array.isArray(data.data)) {
      courses.value = data.data;

      // Handle pagination from your API structure
      if (data.pagination) {
        currentPage.value = data.pagination.current_page || page;
        lastPage.value = data.pagination.last_page || 1;
      } else {
        currentPage.value = page;
        lastPage.value = 1;
      }
    } else if (Array.isArray(data)) {
      // Direct array response fallback
      courses.value = data;
      currentPage.value = page;
      lastPage.value = 1;
    } else {
      // Fallback
      courses.value = [];
      currentPage.value = 1;
      lastPage.value = 1;
    }
  } catch (error) {
    console.error("Error fetching courses:", error);
    courses.value = [];
    currentPage.value = 1;
    lastPage.value = 1;
  } finally {
    loading.value = false;
  }
};

// Reset all filters
const resetFilters = () => {
  searchQuery.value = "";
  selectedCategories.value = [];
  selectedLevels.value = [];
  sortBy.value = "latest";
  currentPage.value = 1;

  // Manually trigger fetch after reset
  updateUrlAndFetch();
};

// 👀 Watch for filter changes & update URL + fetch courses
watch(
  [searchQuery, selectedCategories, selectedLevels, sortBy],
  () => {
    // Reset to page 1 when filters change
    currentPage.value = 1;
    updateUrlAndFetch();
  },
  { deep: true }
);

// Watch page changes separately
watch(currentPage, () => {
  updateUrlAndFetch();
});

// Helper function to update URL and fetch data
const updateUrlAndFetch = () => {
  const query = {};

  if (searchQuery.value) query.search = searchQuery.value;
  if (selectedCategories.value.length > 0) query.category = selectedCategories.value;
  if (selectedLevels.value.length > 0) query.level = selectedLevels.value;
  if (sortBy.value !== "latest") query.sort = sortBy.value;
  if (currentPage.value > 1) query.page = currentPage.value;

  // Update URL without page reload
  router.replace({ query });
  fetchCourses(currentPage.value);
};

// 🚀 Initial load
onMounted(async () => {
  await Promise.all([
    fetchCategories(),
    fetchCourses(currentPage.value)
  ]);
});
</script>
