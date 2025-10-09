<template>
  <div class="mt-8">
    <h3 class="text-xl font-semibold mb-4">
      Reviews
      <span v-if="totalReviews > 0" class="text-sm font-normal text-gray-600">
        (Average: {{ averageRating.toFixed(1) }} · {{ totalReviews }} {{ totalReviews === 1 ? 'review' : 'reviews' }})
      </span>
    </h3>

    <!-- Add Review Form (only for enrolled students who haven't reviewed) -->
    <div v-if="canAddReview" class="mb-8 p-4 bg-white rounded-lg shadow">
      <h4 class="font-medium mb-3">Write a Review</h4>
      <form @submit.prevent="submitReview">
        <div class="mb-3">
          <div class="flex items-center mb-2">
            <span class="mr-2 text-sm text-gray-600">Rating:</span>
            <div class="rating">
              <input 
                v-for="i in 5" 
                :key="i" 
                type="radio" 
                :name="'rating-' + courseId" 
                class="mask mask-star-2 bg-orange-400" 
                :class="{ 'opacity-30': i > newReview.rating }"
                @click="newReview.rating = i"
              />
            </div>
            <span v-if="newReview.rating > 0" class="ml-2 text-sm text-gray-600">
              {{ newReview.rating }} {{ newReview.rating === 1 ? 'star' : 'stars' }}
            </span>
          </div>
          <div v-if="errors.rating" class="text-red-500 text-sm mt-1">
            {{ errors.rating }}
          </div>
        </div>
        
        <div class="mb-3">
          <textarea
            v-model="newReview.comment"
            class="textarea textarea-bordered w-full"
            placeholder="Share your experience with this course..."
            rows="3"
          ></textarea>
          <div v-if="errors.comment" class="text-red-500 text-sm mt-1">
            {{ errors.comment }}
          </div>
        </div>
        
        <div class="flex justify-end">
          <button 
            type="submit" 
            class="btn btn-primary"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting" class="loading loading-spinner"></span>
            {{ isEditing ? 'Update Review' : 'Submit Review' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Reviews List -->
    <div v-if="reviews.length > 0" class="space-y-6">
      <div v-for="review in displayedReviews" :key="review.id" class="border-b pb-4 last:border-0">
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
                    :class="{ 'opacity-30': i > review.rating }"
                    class="mask mask-star-2 bg-orange-400" 
                    :checked="i === review.rating"
                    disabled
                  />
                </div>
                <span class="ml-1">{{ formatDate(review.created_at) }}</span>
              </div>
            </div>
          </div>
          
          <!-- Edit/Delete Buttons (for review owner) -->
          <div v-if="canEditReview(review)" class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
              </svg>
            </label>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-32">
              <li><a @click="editReview(review)">Edit</a></li>
              <li><a @click="confirmDelete(review)" class="text-red-500">Delete</a></li>
            </ul>
          </div>
        </div>
        
        <div class="mt-2 text-gray-700">
          <div :class="[!isCommentExpanded(review.id) ? 'line-clamp-1' : '']">
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
      
      <!-- Read more / Show less reviews -->
      <div v-if="reviews.length > initialVisible" class="text-center mt-6">
        <button
          @click="toggleShowAllReviews"
          class="btn btn-outline"
          type="button"
        >
          {{ showAllReviews ? 'Show less reviews' : 'Read more reviews' }}
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
import { ref, computed, onMounted } from 'vue';
import { useReviewStore } from '@/stores/reviewStore';
import { useAuthStore } from '@/stores/auth';

const props = defineProps({
  courseId: {
    type: [String, Number],
    required: true
  },
  isEnrolled: {
    type: Boolean,
    default: false
  }
});

const authStore = useAuthStore();
const reviewStore = useReviewStore();

// Local state
const newReview = ref({
  rating: 0,
  comment: ''
});

const isEditing = ref(false);
const currentReviewId = ref(null);
const isSubmitting = ref(false);
const errors = ref({});

// Computed properties
const canAddReview = computed(() => {
  if (!authStore.user) return false;
  if (!props.isEnrolled) return false;
  return !reviewStore.hasUserReviewed(authStore.user.id);
});

const reviews = computed(() => reviewStore.reviews);
const averageRating = computed(() => reviewStore.averageRating);
const totalReviews = computed(() => reviewStore.totalReviews);
const isLoading = computed(() => reviewStore.isLoading);

// Show only first N by default with toggle
const initialVisible = 3;
const showAllReviews = ref(false);
const displayedReviews = computed(() => {
  if (showAllReviews.value) return reviews.value;
  return reviews.value.slice(0, initialVisible);
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const validateForm = () => {
  errors.value = {};
  let isValid = true;
  
  if (!newReview.value.rating) {
    errors.value.rating = 'Please select a rating';
    isValid = false;
  }
  
  if (!newReview.value.comment.trim()) {
    errors.value.comment = 'Please write a review';
    isValid = false;
  }
  
  return isValid;
};

const submitReview = async () => {
  if (!validateForm()) return;
  
  isSubmitting.value = true;
  
  try {
    const reviewData = {
      rating: newReview.value.rating,
      comment: newReview.value.comment
    };
    
    if (isEditing.value && currentReviewId.value) {
      await reviewStore.updateReview(currentReviewId.value, reviewData);
    } else {
      await reviewStore.addReview(props.courseId, reviewData);
    }
    
    // Reset form
    resetForm();
  } catch (error) {
    console.error('Error submitting review:', error);
  } finally {
    isSubmitting.value = false;
  }
};

const editReview = (review) => {
  isEditing.value = true;
  currentReviewId.value = review.id;
  newReview.value = {
    rating: review.rating,
    comment: review.comment
  };
  
  // Scroll to the form
  const formElement = document.querySelector('form');
  if (formElement) {
    formElement.scrollIntoView({ behavior: 'smooth' });
  }
};

const confirmDelete = (review) => {
  if (confirm('Are you sure you want to delete this review?')) {
    deleteReview(review.id);
  }
};

const deleteReview = async (reviewId) => {
  try {
    await reviewStore.deleteReview(reviewId);
  } catch (error) {
    console.error('Error deleting review:', error);
  }
};

const resetForm = () => {
  isEditing.value = false;
  currentReviewId.value = null;
  newReview.value = {
    rating: 0,
    comment: ''
  };
  errors.value = {};
};

// Toggle reviews expansion
const toggleShowAllReviews = () => {
  showAllReviews.value = !showAllReviews.value;
};

const canEditReview = (review) => {
  if (!authStore.user) return false;
  return review.user_id === authStore.user.id;
};

// Lifecycle hooks
onMounted(async () => {
  await reviewStore.fetchReviews(props.courseId);
});

// --- Per-review comment expand/collapse ---
const expandedComments = ref(new Set());
const shouldShowCommentToggle = (comment) => {
  return typeof comment === 'string' && comment.length > 60; // heuristic threshold
};
const isCommentExpanded = (id) => expandedComments.value.has(id);
const toggleCommentExpanded = (id) => {
  if (expandedComments.value.has(id)) {
    expandedComments.value.delete(id);
  } else {
    expandedComments.value.add(id);
  }
  // Force reactivity on Set
  expandedComments.value = new Set(expandedComments.value);
};
</script>

<style scoped>
.rating {
  display: inline-flex;
  direction: rtl;
}

.rating input {
  display: none;
}

.rating label {
  cursor: pointer;
  width: 1.5em;
  height: 1.5em;
  font-size: 1.25rem;
  color: #e2e8f0;
  transition: color 0.2s;
}

.rating input:checked ~ label,
.rating input:checked ~ label ~ label {
  color: #f59e0b;
}

.rating:not(:checked) label:hover,
.rating:not(:checked) label:hover ~ label {
  color: #f59e0b;
}
</style>
