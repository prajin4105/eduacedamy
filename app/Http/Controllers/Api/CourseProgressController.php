<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\Enrollment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseProgressController extends Controller
{
    /**
     * Get course progress for authenticated user
     */
    public function getCourseProgress(Request $request, $courseId): JsonResponse
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail($courseId);

            // Check if user is enrolled
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('status', 'completed')
                ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not enrolled in this course'
                ], 403);
            }

            // Get or create progress record
          // Fetch the progress data
$progress = CourseProgress::firstOrCreate(
    ['user_id' => $user->id, 'course_id' => $courseId],
    [
        'total_videos' => $course->getTotalVideosCount(),
        'videos_completed' => 0,
        'progress_percentage' => 0,
    ]
);

// Ensure video_progress is a valid stringified JSON array
if (is_array($progress->video_progress)) {
    $progress->video_progress = json_encode($progress->video_progress);
}

// Update total videos count if course videos changed
if ($progress->total_videos !== $course->getTotalVideosCount()) {
    $progress->total_videos = $course->getTotalVideosCount();
    $progress->progress_percentage = $progress->calculateProgressPercentage();
    $progress->save();
}

// Update enrollment progress
$enrollment->updateProgress($progress->progress_percentage);

return response()->json([
    'success' => true,
    'data' => [
        'progress' => $progress,
        'enrollment' => $enrollment,
        'course' => $course->load('videos'),
        'formatted_time_spent' => $progress->formatted_time_spent,
        'status' => $progress->status,
    ]
]);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching course progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark video as completed
     */
    public function markVideoCompleted(Request $request, $courseId, $videoId): JsonResponse
    {
        try {
            $user = Auth::user();
            \Log::info('Marking video as completed', [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'video_id' => $videoId
            ]);

            $video = Video::where('course_id', $courseId)->findOrFail($videoId);

            // Check if user is enrolled
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('status', 'completed')
                ->first();

            if (!$enrollment) {
                \Log::warning('User not enrolled in course', [
                    'user_id' => $user->id,
                    'course_id' => $courseId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You are not enrolled in this course'
                ], 403);
            }

            // Get or create progress record
            $progress = CourseProgress::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $courseId],
                [
                    'total_videos' => $video->course->getTotalVideosCount(),
                    'videos_completed' => 0,
                    'progress_percentage' => 0,
                ]
            );

            \Log::info('Progress record found/created', [
                'progress_id' => $progress->id,
                'current_progress' => $progress->progress_percentage,
                'videos_completed' => $progress->videos_completed
            ]);

            // Mark video as completed
            $progress->markVideoCompleted($videoId);

            // Update enrollment progress
            $enrollment->updateProgress($progress->progress_percentage);

            \Log::info('Video marked as completed successfully', [
                'new_progress' => $progress->progress_percentage,
                'is_course_completed' => $progress->is_completed
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video marked as completed',
                'data' => [
                    'progress' => $progress,
                    'enrollment' => $enrollment,
                    'is_course_completed' => $progress->is_completed,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error marking video as completed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error marking video as completed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update time spent on course
     */
    public function updateTimeSpent(Request $request, $courseId): JsonResponse
    {
        try {
            $user = Auth::user();
            $seconds = $request->input('seconds', 0);

            if ($seconds <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid time value'
                ], 400);
            }

            // Check if user is enrolled
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('status', 'completed')
                ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not enrolled in this course'
                ], 403);
            }

            // Get or create progress record
            $progress = CourseProgress::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $courseId],
                [
                    'total_videos' => Course::find($courseId)->getTotalVideosCount(),
                    'videos_completed' => 0,
                    'progress_percentage' => 0,
                ]
            );

            // Update time spent
            $progress->updateTimeSpent($seconds);

            return response()->json([
                'success' => true,
                'message' => 'Time updated successfully',
                'data' => [
                    'time_spent_seconds' => $progress->time_spent_seconds,
                    'formatted_time_spent' => $progress->formatted_time_spent,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating time spent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's dashboard progress data
     */
    public function getDashboardProgress(): JsonResponse
    {
        try {
            $user = Auth::user();

            // Get all enrollments with progress
            $enrollments = Enrollment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->with(['course', 'progress'])
                ->get();

            // Get completed courses
            $completedCourses = $enrollments->filter(function ($enrollment) {
                return $enrollment->isCompleted();
            });

            // Get in-progress courses
            $inProgressCourses = $enrollments->filter(function ($enrollment) {
                return !$enrollment->isCompleted() && $enrollment->progress_percentage > 0;
            });

            // Get not started courses
            $notStartedCourses = $enrollments->filter(function ($enrollment) {
                return $enrollment->progress_percentage === 0;
            });

            // Calculate statistics
            $totalCourses = $enrollments->count();
            $completedCount = $completedCourses->count();
            $inProgressCount = $inProgressCourses->count();
            $notStartedCount = $notStartedCourses->count();

            $totalTimeSpent = $enrollments->sum(function ($enrollment) {
                return $enrollment->progress ? $enrollment->progress->time_spent_seconds : 0;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        'total_courses' => $totalCourses,
                        'completed_courses' => $completedCount,
                        'in_progress_courses' => $inProgressCount,
                        'not_started_courses' => $notStartedCount,
                        'total_time_spent_seconds' => $totalTimeSpent,
                        'completion_rate' => $totalCourses > 0 ? round(($completedCount / $totalCourses) * 100, 2) : 0,
                    ],
                    'completed_courses' => $completedCourses->map(function ($enrollment) {
                        return [
                            'enrollment' => $enrollment,
                            'course' => $enrollment->course,
                            'progress' => $enrollment->progress,
                            'completed_at' => $enrollment->progress ? $enrollment->progress->completed_at : null,
                        ];
                    }),
                    'in_progress_courses' => $inProgressCourses->map(function ($enrollment) {
                        return [
                            'enrollment' => $enrollment,
                            'course' => $enrollment->course,
                            'progress' => $enrollment->progress,
                        ];
                    }),
                    'not_started_courses' => $notStartedCourses->map(function ($enrollment) {
                        return [
                            'enrollment' => $enrollment,
                            'course' => $enrollment->course,
                            'progress' => $enrollment->progress,
                        ];
                    }),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course enrollment status for a user
     */
    public function getCourseEnrollmentStatus($courseId): JsonResponse
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail($courseId);

            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'is_enrolled' => false,
                        'status' => null,
                        'progress_percentage' => 0,
                    ]
                ]);
            }

            $progress = CourseProgress::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_enrolled' => $enrollment->status === 'completed',
                    'status' => $enrollment->getProgressStatus(),
                    'progress_percentage' => $enrollment->progress_percentage,
                    'enrollment' => $enrollment,
                    'progress' => $progress,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enrollment status: ' . $e->getMessage()
            ], 500);
        }
    }
}
