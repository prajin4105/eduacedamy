import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import axios from 'axios';

export const useReviewStore = defineStore('review', () => {
    const authStore = useAuthStore();
    const reviews = ref([]);
    const averageRating = ref(0);
    const totalReviews = ref(0);
    const ratingDistribution = ref({ 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 });
    const isLoading = ref(false);
    const error = ref(null);

    // Computed property to get the current user's review
    const currentUserReview = computed(() => {
      if (!authStore.user) return null;
      return reviews.value.find(review => review.user_id === authStore.user.id) || null;
    });

    const fetchReviews = async (courseId) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await axios.get(`/courses/${courseId}/reviews`);
            reviews.value = response.data.data;
            averageRating.value = parseFloat(response.data.average_rating) || 0;
            totalReviews.value = response.data.total_reviews || 0;

            // Update rating distribution if available
            if (response.data.rating_distribution) {
                ratingDistribution.value = response.data.rating_distribution;
            } else {
                // Calculate distribution from reviews if not provided
                calculateRatingDistribution();
            }

            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch reviews';
            throw error.value;
        } finally {
            isLoading.value = false;
        }
    };

    // Calculate rating distribution from reviews
    const calculateRatingDistribution = () => {
        const distribution = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };

        reviews.value.forEach(review => {
            const rating = Math.round(review.rating);
            if (rating >= 1 && rating <= 5) {
                distribution[rating]++;
            }
        });

        ratingDistribution.value = distribution;
    };

    const addReview = async (courseId, reviewData) => {
        try {
            const response = await axios.post(`/courses/${courseId}/reviews`, reviewData);
            // Add the new review to the beginning of the list
            reviews.value.unshift(response.data.data);

            // Update stats
            averageRating.value = parseFloat(response.data.average_rating) || 0;
            totalReviews.value = response.data.total_reviews || 0;

            // Update rating distribution
            if (response.data.rating_distribution) {
                ratingDistribution.value = response.data.rating_distribution;
            } else {
                calculateRatingDistribution();
            }

            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to add review';
            throw err; // Re-throw to allow component to handle specific errors
        }
    };

    const updateReview = async (reviewId, reviewData) => {
        try {
            const response = await axios.put(`/reviews/${reviewId}`, reviewData);
            const index = reviews.value.findIndex(r => r.id === reviewId);

            if (index !== -1) {
                reviews.value[index] = response.data.data;
            }

            // Update stats
            averageRating.value = parseFloat(response.data.average_rating) || 0;

            // Update rating distribution
            if (response.data.rating_distribution) {
                ratingDistribution.value = response.data.rating_distribution;
            } else {
                calculateRatingDistribution();
            }

            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to update review';
            throw err; // Re-throw to allow component to handle specific errors
        }
    };

    const deleteReview = async (reviewId) => {
        try {
            const response = await axios.delete(`/reviews/${reviewId}`);
            reviews.value = reviews.value.filter(r => r.id !== reviewId);

            // Update stats
            averageRating.value = parseFloat(response.data.average_rating) || 0;
            totalReviews.value = response.data.total_reviews || 0;

            // Update rating distribution
            if (response.data.rating_distribution) {
                ratingDistribution.value = response.data.rating_distribution;
            } else {
                calculateRatingDistribution();
            }

            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to delete review';
            throw err; // Re-throw to allow component to handle specific errors
        }
    };

    const hasUserReviewed = (userId) => {
        return reviews.value.some(review => review.user_id === userId);
    };

    return {
        reviews,
        averageRating,
        totalReviews,
        ratingDistribution,
        isLoading,
        error,
        currentUserReview,
        fetchReviews,
        addReview,
        updateReview,
        deleteReview,
        hasUserReviewed,
    };
});
