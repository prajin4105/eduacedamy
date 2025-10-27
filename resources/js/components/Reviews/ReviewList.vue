<template>
  <div class="mt-8">
    <h3 class="text-xl font-semibold mb-4">
      Reviews
      <span v-if="totalReviews > 0" class="text-sm font-normal text-gray-600">
        (Average: {{ averageRating.toFixed(1) }} · {{ totalReviews }} {{ totalReviews === 1 ? 'review' : 'reviews' }})
      </span>
    </h3>

    <!-- Reviews List -->
    <div v-if="reviews.length > 0" class="space-y-6">
      <div
        v-for="review in displayedReviews"
        :key="review.id"
        class="border-b pb-4 last:border-0"
      >
        <div class="flex items-start justify-between mb-2">
          <div class="flex items-center">
            <div class="avatar mr-3">
              <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-600 font-medium">
                  {{ review.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                </span>
              </div>
            </div>
            <div>
              <h4 class="font-medium">{{ review.user?.name || 'Anonymous' }}</h4>
              <div class="flex items-center text-sm text-gray-500">
                <div class="rating rating-xs">
                  <input
                    v-for="i in 5"
                    :key="i"
                    type="radio"
                    class="mask mask-star-2 bg-orange-400"
                    :class="{ 'opacity-30': i > review.rating }"
                    :checked="i === review.rating"
                    disabled
                  />
                </div>
                <span class="ml-1">{{ formatDate(review.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Comment section -->
        <div class="mt-2 text-gray-700">
          <div
            :class="[
              !isCommentExpanded(review.id) ? 'line-clamp-2' : '',
              'transition-all duration-300'
            ]"
          >
            {{ review.comment }}
          </div>

          <button
            v-if="shouldShowCommentToggle(review.comment)"
            @click="toggleCommentExpanded(review.id)"
            class="mt-1 text-xs text-indigo-600 hover:text-indigo-700"
            type="button"
          >
            {{ isCommentExpanded(review.id) ? 'Show less' : 'Read more' }}
          </button>
        </div>
      </div>

      <!-- Load More Button -->
      <div
        v-if="visibleCount < reviews.length"
        class="text-center mt-6"
      >
        <button
          @click="loadMoreReviews"
          class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          type="button"
        >
          Load More Reviews
        </button>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-8 text-gray-500">
      No reviews yet. Be the first to review this course!
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useReviewStore } from '@/stores/reviewStore'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  courseId: {
    type: [String, Number],
    required: true
  }
})

const reviewStore = useReviewStore()
const authStore = useAuthStore()

// Load reviews
onMounted(async () => {
  await reviewStore.fetchReviews(props.courseId)
})

// Reviews data
const reviews = computed(() => reviewStore.reviews)
const averageRating = computed(() => reviewStore.averageRating)
const totalReviews = computed(() => reviewStore.totalReviews)

// --- Load more logic ---
const visibleCount = ref(3)
const displayedReviews = computed(() =>
  reviews.value.slice(0, visibleCount.value)
)

const loadMoreReviews = () => {
  visibleCount.value += 2
}

// --- Comment expand/collapse logic ---
const expandedComments = ref(new Set())

const shouldShowCommentToggle = (comment) => {
  return typeof comment === 'string' && comment.length > 100
}

const isCommentExpanded = (id) => expandedComments.value.has(id)

const toggleCommentExpanded = (id) => {
  if (expandedComments.value.has(id)) {
    expandedComments.value.delete(id)
  } else {
    expandedComments.value.add(id)
  }
  expandedComments.value = new Set(expandedComments.value)
}

// --- Date formatting ---
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2; /* ✅ limit to 2 lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
