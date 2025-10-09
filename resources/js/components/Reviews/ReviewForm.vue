<template>
  <form @submit.prevent="submitForm" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Your Rating <span class="text-red-500">*</span>
      </label>
      <StarRating v-model="form.rating" :show-text="true" size="lg" />
      <div v-if="errors.rating" class="mt-1 text-sm text-red-600">
        {{ errors.rating }}
      </div>
    </div>

    <div>
      <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
        Your Review <span class="text-red-500">*</span>
      </label>
      <textarea
        id="comment"
        v-model="form.comment"
        rows="4"
        maxlength="70"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        :class="{ 'border-red-300': errors.comment }"
        placeholder="Share your experience with this course (max 70 characters)..."
      ></textarea>

      <!-- ✅ Character counter -->
      <div class="flex justify-between mt-1 text-sm">
        <span v-if="errors.comment" class="text-red-600">{{ errors.comment }}</span>
        <span :class="form.comment.length > 70 ? 'text-red-500' : 'text-gray-500'">
          {{ form.comment.length }}/70
        </span>
      </div>
    </div>

    <div class="flex justify-end space-x-3 pt-2">
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        :disabled="isSubmitting"
      >
        Cancel
      </button>
      <button
        type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
        :disabled="isSubmitting"
      >
        <span v-if="isSubmitting" class="inline-flex items-center">
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Submitting...
        </span>
        <span v-else>
          {{ isEditing ? 'Update Review' : 'Submit Review' }}
        </span>
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import StarRating from '@/components/UI/StarRating.vue';
import { useReviewStore } from '@/stores/reviewStore';

const props = defineProps({
  courseId: {
    type: [String, Number],
    required: true
  },
  review: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['submitted', 'cancel']);
const reviewStore = useReviewStore();

// Form state
const form = reactive({
  rating: 0,
  comment: ''
});

const isSubmitting = ref(false);
const errors = ref({});
const isEditing = computed(() => !!props.review);

onMounted(() => {
  if (props.review) {
    form.rating = props.review.rating;
    form.comment = props.review.comment;
  }
});

// ✅ Updated validation
const validateForm = () => {
  errors.value = {};
  let isValid = true;

  if (!form.rating) {
    errors.value.rating = 'Please select a rating';
    isValid = false;
  }

  if (!form.comment.trim()) {
    errors.value.comment = 'Please write your review';
    isValid = false;
  } else if (form.comment.trim().length < 10) {
    errors.value.comment = 'Review must be at least 10 characters';
    isValid = false;
  } else if (form.comment.trim().length > 70) {
    errors.value.comment = 'Review must not exceed 70 characters';
    isValid = false;
  }

  return isValid;
};

const submitForm = async () => {
  if (!validateForm()) return;

  isSubmitting.value = true;
  errors.value = {};

  try {
    const reviewData = {
      rating: form.rating,
      comment: form.comment.trim()
    };

    if (isEditing.value) {
      await reviewStore.updateReview(props.review.id, reviewData);
    } else {
      await reviewStore.addReview(props.courseId, reviewData);
    }

    form.rating = 0;
    form.comment = '';
    emit('submitted');
  } catch (error) {
    console.error('Error submitting review:', error);
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      errors.value.general = error.response?.data?.message || 'An error occurred while submitting your review. Please try again.';
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>
