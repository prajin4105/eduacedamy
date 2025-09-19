<template>
  <div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-xl font-semibold mb-4">Student Feedback</h3>
    
    <div class="md:flex items-start">
      <!-- Overall Rating -->
      <div class="text-center mb-6 md:mb-0 md:mr-8">
        <div class="text-5xl font-bold text-gray-800 mb-1">
          {{ averageRating.toFixed(1) }}
        </div>
        <div class="flex justify-center mb-2">
          <StarRating 
            :model-value="Math.round(averageRating)" 
            :disabled="true"
            :show-text="false"
          />
        </div>
        <div class="text-sm text-gray-600">
          {{ totalReviews }} {{ totalReviews === 1 ? 'review' : 'reviews' }}
        </div>
      </div>
      
      <!-- Rating Distribution -->
      <div class="flex-1">
        <div v-for="i in 5" :key="i" class="flex items-center mb-2">
          <span class="w-8 text-sm text-gray-600">{{ 6 - i }} stars</span>
          <div class="flex-1 mx-2 h-2.5 bg-gray-200 rounded-full overflow-hidden">
            <div 
              class="h-full bg-yellow-400" 
              :style="{ width: getRatingPercentage(6 - i) + '%' }"
            ></div>
          </div>
          <span class="w-8 text-sm text-right text-gray-600">
            {{ getRatingCount(6 - i) }}
          </span>
        </div>
      </div>
    </div>
    
    <!-- Add Review Button (for enrolled students) -->
    <div v-if="isEnrolled && !hasUserReviewed && authStore.user" class="mt-6 pt-4 border-t">
      <button 
        v-if="!showReviewForm" 
        @click="showReviewForm = true"
        class="btn btn-primary"
      >
        Write a Review
      </button>
      
      <div v-else class="mt-4">
        <h4 class="font-medium mb-3">Write a Review</h4>
        <ReviewForm 
          :course-id="courseId"
          @submitted="handleReviewSubmitted"
          @cancel="showReviewForm = false"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useReviewStore } from '@/stores/reviewStore';
import { useAuthStore } from '@/stores/authStore';
import StarRating from '@/Components/UI/StarRating.vue';
import ReviewForm from '@/Components/Reviews/ReviewForm.vue';

const props = defineProps({
  courseId: {
    type: [String, Number],
    required: true
  },
  isEnrolled: {
    type: Boolean,
    default: false
  },
  initialData: {
    type: Object,
    default: () => ({
      average_rating: 0,
      total_reviews: 0,
      rating_distribution: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }
    })
  }
});

const authStore = useAuthStore();
const reviewStore = useReviewStore();

// Local state
const showReviewForm = ref(false);

// Computed properties
const averageRating = computed(() => reviewStore.averageRating || props.initialData.average_rating);
const totalReviews = computed(() => reviewStore.totalReviews || props.initialData.total_reviews);
const ratingDistribution = computed(() => {
  return reviewStore.ratingDistribution || props.initialData.rating_distribution;
});

const hasUserReviewed = computed(() => {
  if (!authStore.user) return false;
  return reviewStore.hasUserReviewed(authStore.user.id);
});

// Methods
const getRatingPercentage = (rating) => {
  if (totalReviews.value === 0) return 0;
  const count = ratingDistribution.value[rating] || 0;
  return (count / totalReviews.value) * 100;
};

const getRatingCount = (rating) => {
  return ratingDistribution.value[rating] || 0;
};

const handleReviewSubmitted = () => {
  showReviewForm.value = false;
  // Refresh reviews to show the new one
  reviewStore.fetchReviews(props.courseId);
};

// Lifecycle hooks
onMounted(() => {
  // If we don't have reviews yet, fetch them
  if (reviewStore.reviews.length === 0) {
    reviewStore.fetchReviews(props.courseId);
  }
});
</script>
