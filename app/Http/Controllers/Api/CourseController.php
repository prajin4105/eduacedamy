<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'enrollments', 'videos'])
            ->where('is_published', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('instructor', function($instructorQuery) use ($search) {
                      $instructorQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Level filter
        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
        }

        // Instructor filter
        if ($request->has('instructor') && $request->instructor) {
            $query->where('instructor_id', $request->instructor);
        }

        // Sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest('published_at');
                break;
        }

        $courses = $query->get()->map(function ($course) use ($request) {
            $enrollmentStatus = null;
            $isEnrolled = false;
            
            // Check enrollment status if user is authenticated
            if (Auth::check()) {
                $enrollment = Enrollment::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->first();
                
                if ($enrollment) {
                    $isEnrolled = $enrollment->status === 'completed';
                    $enrollmentStatus = $enrollment->getProgressStatus();
                }
            }
            
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'excerpt' => substr($course->description, 0, 150) . '...',
                'price' => (float) $course->price,
                'image' => $course->image ? asset('storage/' . $course->image) : null,
                'level' => $course->level,
                'language' => $course->language,
                'duration_in_minutes' => $course->duration_in_minutes,
                'created_at' => $course->created_at,
                'published_at' => $course->published_at,
                'instructor' => [
                    'id' => $course->instructor->id,
                    'name' => $course->instructor->name,
                    'email' => $course->instructor->email,
                    'avatar' => null, // optional avatar
                ],
                'enrollments_count' => $course->enrollments->where('status', 'completed')->count(),
                'rating' => 4.5,
                'reviews_count' => 0,
                'lessons_count' => $course->videos->count(),
                'is_enrolled' => $isEnrolled,
                'enrollment_status' => $enrollmentStatus,
                // 👇 Add video list also (short version)
                'videos' => $course->videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'video_url' => $video->video_url ? asset('storage/' . $video->video_url) : null,
                        'thumbnail_url' => $video->thumbnail_url ? asset('storage/' . $video->thumbnail_url) : null,
                        'duration_in_seconds' => $video->duration_seconds,
                        'sort_order' => $video->sort_order,
                        'is_published' => $video->is_published,
                    ];
                }),
            ];
        });

        return response()->json($courses);
    }

    public function show($slug)
    {
        $course = Course::with(['instructor', 'videos', 'enrollments'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $enrollmentStatus = null;
        $isEnrolled = false;
        
        // Check enrollment status if user is authenticated
        if (Auth::check()) {
            $enrollment = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();
            
            if ($enrollment) {
                $isEnrolled = $enrollment->status === 'completed';
                $enrollmentStatus = $enrollment->getProgressStatus();
            }
        }

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'price' => (float) $course->price,
            'image' => $course->image ? asset('storage/' . $course->image) : null,
            'level' => $course->level,
            'language' => $course->language,
            'duration_in_minutes' => $course->duration_in_minutes,
            'what_you_will_learn' => $course->what_you_will_learn,
            'requirements' => $course->requirements,
            'created_at' => $course->created_at,
            'published_at' => $course->published_at,
            'instructor' => [
                'id' => $course->instructor->id,
                'name' => $course->instructor->name,
                'email' => $course->instructor->email,
            ],
            'videos' => $course->videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'duration_in_seconds' => $video->duration_seconds,
                    'sort_order' => $video->sort_order,
                    'video_url' => $video->video_url,
                    'thumbnail_url' => $video->thumbnail_url,
                ];
            }),
            'enrollments_count' => $course->enrollments->where('status', 'completed')->count(),
            'rating' => 4.5,
            'reviews_count' => 0,
            'is_enrolled' => $isEnrolled,
            'enrollment_status' => $enrollmentStatus,
        ]);
    }

    public function instructors()
    {
        $instructors = User::where('role', 'instructor')
            ->withCount(['enrolledCourses' => function($query) {
                $query->where('is_published', true);
            }])
            ->having('enrolled_courses_count', '>', 0)
            ->get()
            ->map(function ($instructor) {
                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'email' => $instructor->email,
                    'courses_count' => $instructor->enrolled_courses_count,
                ];
            });

        return response()->json($instructors);
    }

    public function levels()
    {
        $levels = Course::where('is_published', true)
            ->distinct()
            ->pluck('level')
            ->filter()
            ->map(function ($level) {
                return [
                    'id' => strtolower($level),
                    'name' => ucfirst($level),
                ];
            });

        return response()->json($levels->values());
    }
}
