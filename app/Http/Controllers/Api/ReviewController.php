<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Get all reviews for a specific course.
     */
    public function index(Course $course)
    {
        $reviews = $course->reviews()
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'profile_photo_path');
            }])
            ->latest()
            ->paginate(10);

        // Calculate rating distribution
        $ratingDistribution = $course->reviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Ensure all rating levels are present with 0 count
        $fullDistribution = array_fill_keys(range(1, 5), 0);
        foreach ($ratingDistribution as $rating => $count) {
            $fullDistribution[$rating] = (int) $count;
        }

        return response()->json([
            'data' => $reviews->getCollection(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
            'average_rating' => (float) $course->average_rating,
            'total_reviews' => $course->reviews_count,
            'rating_distribution' => $fullDistribution,
        ]);
    }

    /**
     * Store a new review.
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Check if user is enrolled in the course
        if (!$course->enrollments()->where('user_id', Auth::id())->exists()) {
            return response()->json([
                'message' => 'You need to be enrolled in this course to leave a review.'
            ], 403);
        }

        // Check if user has already reviewed this course
        if ($course->hasUserReviewed(Auth::id())) {
            return response()->json([
                'message' => 'You have already reviewed this course.'
            ], 422);
        }

        $review = $course->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Refresh the course to get updated review counts
        $course->refresh();

        // Get updated rating distribution
        $ratingDistribution = $this->getRatingDistribution($course);

        // Load user relationship for response
        $review->load('user:id,name,profile_photo_path');

        return response()->json([
            'message' => 'Review submitted successfully.',
            'data' => $review,
            'average_rating' => (float) $course->average_rating,
            'total_reviews' => $course->reviews_count,
            'rating_distribution' => $ratingDistribution,
        ], 201);
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $request->validate([
            'rating' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'],
            'comment' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $review->update($request->only(['rating', 'comment']));
        
        // Refresh the course to get updated review counts
        $course = $review->course->fresh();
        
        // Get updated rating distribution
        $ratingDistribution = $this->getRatingDistribution($course);

        return response()->json([
            'message' => 'Review updated successfully.',
            'data' => $review->fresh(['user:id,name,profile_photo_path']),
            'average_rating' => (float) $course->average_rating,
            'total_reviews' => $course->reviews_count,
            'rating_distribution' => $ratingDistribution,
        ]);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        
        $course = $review->course;
        $review->delete();
        
        // Refresh the course to get updated review counts
        $course->refresh();
        
        // Get updated rating distribution
        $ratingDistribution = $this->getRatingDistribution($course);

        return response()->json([
            'message' => 'Review deleted successfully.',
            'average_rating' => (float) $course->average_rating,
            'total_reviews' => $course->reviews_count,
            'rating_distribution' => $ratingDistribution,
        ]);
    }
    
    /**
     * Get rating distribution for a course
     *
     * @param Course $course
     * @return array
     */
    protected function getRatingDistribution(Course $course)
    {
        $ratingDistribution = $course->reviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Ensure all rating levels are present with 0 count
        $fullDistribution = array_fill_keys(range(1, 5), 0);
        foreach ($ratingDistribution as $rating => $count) {
            $fullDistribution[$rating] = (int) $count;
        }

        return $fullDistribution;
    }
}
