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

            <!-- Course Type -->
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Course Type</h3>
              <div class="space-y-2">
                <div class="flex items-center">
                  <input
                    id="type-subscription"
                    v-model="selectedTypes"
                    type="checkbox"
                    value="subscription"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label
                    for="type-subscription"
                    class="ml-3 text-sm text-gray-700"
                  >
                    Subscription Plan Courses
                  </label>
                </div>
                <div class="flex items-center">
                  <input
                    id="type-regular"
                    v-model="selectedTypes"
                    type="checkbox"
                    value="regular"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label
                    for="type-regular"
                    class="ml-3 text-sm text-gray-700"
                  >
                    Regular Courses
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
                <span class="font-medium">{{ displayedCourses.length }}</span>
                {{ displayedCourses.length === 1 ? "course" : "courses" }}
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
              v-else-if="displayedCourses.length === 0"
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

            <!-- Course cards with images -->
            <div
              v-else
              class="bg-white shadow overflow-hidden sm:rounded-b-lg"
            >
              <div class="divide-y divide-gray-200">
                <div
                  v-for="course in displayedCourses"
                  :key="course.id"
                  class="px-4 py-6 sm:px-6 hover:bg-gray-50 transition-colors duration-150"
                >
                  <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Course Image -->
                    <div class="lg:w-48 lg:flex-shrink-0">
                      <div class="relative w-full lg:w-48 h-48 lg:h-32 course-image-container">
                        <img
                          :src="getCourseImage(course)"
                          :alt="course.title"
                          class="w-full h-full object-cover rounded-lg"
                          @error="handleImageError"
                        />
                        <!-- Badge overlay for featured/popular courses -->
                        <div
                          v-if="course.is_featured || course.is_popular || course.requires_subscription"
                          class="absolute top-2 left-2"
                        >
                          <span
                            v-if="course.is_featured"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full"
                          >
                            ⭐ Featured
                          </span>
                          <span
                            v-else-if="course.is_popular"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full"
                          >
                            🔥 Popular
                          </span>
                          <span
                            v-else-if="course.requires_subscription"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full"
                          >
                            📦 Included in plan
                          </span>
                        </div>

                        <!-- Price overlay -->
                        <div class="absolute bottom-2 right-2">
                          <div v-if="course.price" class="text-right">
                            <span v-if="course.discounted_price" class="bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">
                              ${{ course.discounted_price }}
                            </span>
                            <span v-else-if="parseFloat(course.price) > 0" class="bg-gray-900 text-white px-2 py-1 rounded text-sm font-bold">
                              ${{ course.price }}
                            </span>
                            <span v-else class="bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">Free</span>
                          </div>
                          <span v-else class="bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">Free</span>
                        </div>
                      </div>
                    </div>

                    <!-- Course Content -->
                    <div class="flex-1 flex flex-col justify-between">
                      <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">
                          <a @click.prevent="goto('courseDetail', { slug: course.slug })" class="hover:text-indigo-600 line-clamp-2 cursor-pointer">
                            {{ course.title }}
                          </a>
                        </h2>

                        <!-- Fixed description rendering with HTML support -->
                        <div
                          class="text-gray-600 mb-3 line-clamp-3"
                          v-html="course.description || course.excerpt || 'No description available'"
                        ></div>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                          <span v-if="course.level" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ course.level.charAt(0).toUpperCase() + course.level.slice(1) }}
                          </span>

                          <div v-if="course.categories && course.categories.length > 0" class="flex flex-wrap gap-1">
                            <span
                              v-for="category in course.categories.slice(0, 2)"
                              :key="category.id"
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                            >
                              {{ category.name }}
                            </span>
                            <span
                              v-if="course.categories.length > 2"
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                            >
                              +{{ course.categories.length - 2 }} more
                            </span>
                          </div>

                          <span v-if="course.rating > 0" class="flex items-center">
                            <div class="flex items-center">
                              <svg
                                v-for="star in 5"
                                :key="star"
                                class="w-4 h-4"
                                :class="star <= Math.floor(course.rating) ? 'text-yellow-400' : 'text-gray-300'"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                              >
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                              </svg>
                            </div>
                            <span class="ml-1">{{ Number(course.rating).toFixed(1) }}/5 ({{ course.reviews_count }})</span>
                          </span>
                        </div>

                        <!-- Course stats -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
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

                          <span v-if="course.updated_at" class="flex items-center text-xs">
                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Updated {{ formatDate(course.updated_at) }}
                          </span>
                        </div>
                      </div>

                      <!-- Action buttons -->
                      <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center space-x-3">
                          <!-- Wishlist button -->
                          <button
                            @click="toggleWishlist(course)"
                            :class="[
                              'p-2 rounded-full transition-colors duration-200',
                              course.is_wishlisted
                                ? 'text-red-600 hover:text-red-700 bg-red-50'
                                : 'text-gray-400 hover:text-red-600 hover:bg-red-50'
                            ]"
                            :title="course.is_wishlisted ? 'Remove from wishlist' : 'Add to wishlist'"
                          >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                          </button>
                          <span v-if="typeof course.wishlist_count !== 'undefined'" class="text-sm text-gray-500">{{ course.wishlist_count }}</span>

                          <!-- Share button -->
                          <button
                            @click="shareCourse(course)"
                            class="p-2 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200"
                            title="Share course"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                            </svg>
                          </button>
                        </div>

                        <!-- Single button for all courses -->
                        <a
                          @click.prevent="goto('courseDetail', { slug: course.slug })"
                          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 cursor-pointer"
                        >
                          {{ course.is_enrolled ? 'Continue Learning' : 'View Details' }}
                          <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </a>
                      </div>
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
import { useMaskedNavigation } from '../utils/navigation';

const { goto } = useMaskedNavigation();

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
// NEW: course types (subscription/regular)
const selectedTypes = ref(
  Array.isArray(route.query.type)
    ? route.query.type
    : (route.query.type ? [route.query.type] : [])
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
  const total = lastPage.value;  // total pages
  const current = currentPage.value;
  const pages = [];

  // If total pages <= 5, show all
  if (total <= 5) {
    for (let i = 1; i <= total; i++) pages.push(i);
    return pages;
  }

  // Near the start (page 1–3)
  if (current <= 3) {
    pages.push(1, 2, "...",total-1, total);
  }
  // Near the end (last 3 pages)
  else if (current >= total - 2) {
    pages.push(1, "...", total - 2, total - 1, total);
  }
  // In the middle
  else {
    pages.push(1, "...", current, "...", total);
  }

  return pages;
});




// ✅ Displayed list after applying *local* type filter
const displayedCourses = computed(() => {
  // If no type filter selected, show everything fetched
  if (!selectedTypes.value.length) return courses.value;

  const wantSub = selectedTypes.value.includes("subscription");
  const wantReg = selectedTypes.value.includes("regular");

  return courses.value.filter((c) => {
    const isSub = !!c.requires_subscription; // subscription courses
    const isReg = !isSub;                    // everything else = regular
    return (wantSub && isSub) || (wantReg && isReg);
  });
});

// Image handling functions
const getCourseImage = (course) => {
  if (course.image) {
    if (course.image.startsWith('http')) {
      return course.image;
    }
    return `${axios.defaults.baseURL || ''}/storage/${course.image}`;
  }

  if (course.thumbnail) {
    return course.thumbnail.startsWith('http')
      ? course.thumbnail
      : `${axios.defaults.baseURL || ''}/storage/${course.thumbnail}`;
  }

  if (course.featured_image) {
    return course.featured_image.startsWith('http')
      ? course.featured_image
      : `${axios.defaults.baseURL || ''}/storage/${course.featured_image}`;
  }

  if (course.cover_image) {
    return course.cover_image.startsWith('http')
      ? course.cover_image
      : `${axios.defaults.baseURL || ''}/storage/${course.cover_image}`;
  }

  return `https://via.placeholder.com/400x300/6366f1/ffffff?text=${encodeURIComponent(course.title?.substring(0, 20) || 'Course')}`;
};

const handleImageError = (event) => {
  event.target.src = `https://via.placeholder.com/400x300/6366f1/ffffff?text=${encodeURIComponent('Course Image')}`;
};

// Utility functions
const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffTime = Math.abs(now - date);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays === 1) return 'Yesterday';
  if (diffDays < 7) return `${diffDays} days ago`;
  if (diffDays < 30) return `${Math.ceil(diffDays / 7)} weeks ago`;
  if (diffDays < 365) return `${Math.ceil(diffDays / 30)} months ago`;
  return `${Math.ceil(diffDays / 365)} years ago`;
};

const toggleWishlist = async (course) => {
  try {
    if (course.is_wishlisted) {
      await axios.delete(`/wishlist/${course.id}`);
      course.is_wishlisted = false;
    } else {
      await axios.post(`/wishlist`, { course_id: course.id });
      course.is_wishlisted = true;
    }
  } catch (error) {
    console.error('Error toggling wishlist:', error);
  }
};

const shareCourse = async (course) => {
  const shareData = {
    title: course.title,
    text: course.description || course.excerpt || 'Check out this course!',
    url: `${window.location.origin}/courses/${course.slug}`
  };

  try {
    if (navigator.share) {
      await navigator.share(shareData);
    } else {
      await navigator.clipboard.writeText(shareData.url);
      console.log('Course URL copied to clipboard!');
    }
  } catch (error) {
    console.error('Error sharing course:', error);
  }
};

// 🔄 Fetch categories
const fetchCategories = async () => {
  try {
    const { data } = await axios.get("/categories");
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
      per_page: 12, // 👈 ADD THIS LINE — tell backend how many to return
    };

    if (searchQuery.value && searchQuery.value.trim()) {
      params.search = searchQuery.value.trim();
    }

    if (selectedCategories.value.length > 0) {
      params.categories = selectedCategories.value;
    }

    if (selectedLevels.value.length > 0) {
      params.levels = selectedLevels.value;
    }

    if (selectedTypes.value.length > 0) {
      // send to backend (supports either 'type' or 'types')
      params.type = selectedTypes.value;
      params.types = selectedTypes.value;
    }

    if (sortBy.value && sortBy.value !== "latest") {
      params.sort = sortBy.value;
    }

    const { data } = await axios.get("/courses", { params });

    if (data.data && Array.isArray(data.data)) {
      courses.value = data.data;

      if (data.pagination) {
        currentPage.value = data.pagination.current_page || page;
        lastPage.value = data.pagination.last_page || 1;
      } else {
        currentPage.value = page;
        lastPage.value = 1;
      }
    } else if (Array.isArray(data)) {
      courses.value = data;
      currentPage.value = page;
      lastPage.value = 1;
    } else {
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
  selectedTypes.value = [];
  sortBy.value = "latest";
  currentPage.value = 1;
  updateUrlAndFetch();
};

// 👀 Watch for filter changes & update URL + fetch courses
watch(
  [searchQuery, selectedCategories, selectedLevels, selectedTypes, sortBy],
  () => {
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
  if (selectedTypes.value.length > 0) query.type = selectedTypes.value;
  if (sortBy.value !== "latest") query.sort = sortBy.value;
  if (currentPage.value > 1) query.page = currentPage.value;

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

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

.hover\:bg-gray-50:hover {
  background-color: rgb(249 250 251);
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .5; }
}
.animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: #f1f1f1; }
::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #a1a1a1; }

.course-image-container { position: relative; overflow: hidden; border-radius: 0.5rem; }
.course-image-container img { transition: transform 0.3s ease; }
.course-image-container:hover img { transform: scale(1.05); }

@keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.badge-enter { animation: fadeInUp 0.3s ease-out; }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }

button:focus { outline: 2px solid transparent; outline-offset: 2px; }
button:focus-visible { box-shadow: 0 0 0 2px #4f46e5; }

.course-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.course-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }

.price-badge { backdrop-filter: blur(4px); background-color: rgba(0,0,0,0.8); }

@media (max-width: 640px) {
  .course-image-container { height: 200px; }
  .line-clamp-2 { -webkit-line-clamp: 1; }
  .line-clamp-3 { -webkit-line-clamp: 2; }
}

@media (max-width: 768px) {
  .flex-wrap { gap: 0.5rem; }
  .text-xl { font-size: 1.125rem; line-height: 1.75rem; }
}

@media (prefers-color-scheme: dark) {
  .course-image-container img { filter: brightness(0.9); }
  .bg-white { background-color: #1f2937; color: white; }
  .text-gray-900 { color: #f9fafb; }
  .text-gray-600 { color: #d1d5db; }
  .text-gray-500 { color: #9ca3af; }
}
</style>
