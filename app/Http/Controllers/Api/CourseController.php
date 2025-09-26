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
    /**
     * Display a listing of courses with filters and pagination.
     */
 public function index(Request $request)
{
    $query = Course::with([
        'instructor:id,name,profile_photo_path',
        'categories:id,name,slug',
        'enrollments',
        'videos'
    ])
    ->withCount(['enrollments', 'videos'])
    ->where('is_published', true);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('instructor', function ($instructorQuery) use ($search) {
                  $instructorQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Category filter
    if ($request->has('categories')) {
        $categoryIds = is_array($request->categories)
            ? $request->categories
            : explode(',', $request->categories);

        $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        }, '>=', count($categoryIds));
    }

    // Level filter
    if ($request->has('levels')) {
        $levels = is_array($request->levels)
            ? $request->levels
            : explode(',', $request->levels);

        $query->whereIn('level', $levels);
    }

    // Instructor filter
    if ($request->filled('instructor')) {
        $query->where('instructor_id', $request->instructor);
    }

    // Price filters
    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Sorting
    $sortBy = $request->get('sort', 'newest');
    switch ($sortBy) {
        case 'newest':
            $query->latest('published_at');
            break;
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
            $query->orderBy('enrollments_count', 'desc');
            break;
        default:
            $query->latest('published_at');
    }

    // Pagination
    $perPage = min($request->get('per_page', 12), 50);
    $page = $request->get('page', 1);
    $paginator = $query->paginate($perPage, ['*'], 'page', $page);

    // Fetch wishlist IDs once for the authenticated user
    $wishlistIds = $request->user()
        ? $request->user()->wishlist()->pluck('course_id')->map(fn($id) => (int)$id)->toArray()
        : [];

    // Transform response
    $courses = $paginator->getCollection()->map(function ($course) use ($request, $wishlistIds) {
        $enrollmentStatus = null;
        $isEnrolled = false;

        if ($request->user()) {
            $enrollment = $course->enrollments()
                ->where('user_id', $request->user()->id)
                ->first();

            if ($enrollment) {
                $isEnrolled = true;
                $enrollmentStatus = $enrollment->status;
            }
        }

        return [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'price' => $course->price,
            'image' => $course->image ? asset('storage/' . $course->image) : null,
            'level' => $course->level,
            'duration' => $course->duration,
            'instructor' => $course->instructor->only(['id', 'name', 'profile_photo_path']),
            'categories' => $course->categories->map->only(['id', 'name', 'slug']),
            'enrollments_count' => $course->enrollments_count,
            'rating' => 0,
            'reviews_count' => 0,
            'lessons_count' => $course->videos_count,
            'is_enrolled' => $isEnrolled,
            'enrollment_status' => $enrollmentStatus,
            'is_wishlisted' => in_array((int)$course->id, $wishlistIds),
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
        ];
    });

    // Return final JSON response
    return response()->json([
        'data' => $courses,
        'pagination' => [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ]
    ]);
}


    public function show($slug)
    {
        $course = Course::with(['instructor', 'videos', 'enrollments'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $enrollmentStatus = null;
        $isEnrolled = false;

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
            'rating' => 0,
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
