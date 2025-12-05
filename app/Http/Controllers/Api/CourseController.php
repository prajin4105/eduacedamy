<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCourseRequest;
use App\Http\Requests\Api\UpdateCourseRequest;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of courses with filters and pagination.
     */
    public function index(Request $request)
    {
        $query = Course::with([
            'instructor:id,name',
            'categories:id,name,slug',
            'videos:id,course_id,title,description,duration_seconds,sort_order,thumbnail_url,video_url'
        ])
        ->withCount(['enrollments', 'videos', 'wishlistedBy as wishlist_count'])
        ->where('is_published', true)
        ->where('approval_status', 'approved');

        // 🔍 Search filter
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

        // 🏷️ Category filter
        if ($request->has('categories')) {
            $categoryIds = is_array($request->categories)
                ? $request->categories
                : explode(',', $request->categories);

            foreach ($categoryIds as $categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }

        // 📊 Level filter
        if ($request->has('levels')) {
            $levels = is_array($request->levels)
                ? $request->levels
                : explode(',', $request->levels);

            $query->whereIn('level', $levels);
        }

        // 👨 Instructor filter
        if ($request->filled('instructor')) {
            $query->where('instructor_id', $request->instructor);
        }

        // 💰 Price filters
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 🧩 Subscription type filter - FIXED TO HANDLE MULTIPLE TYPES
        $types = $request->get('type', $request->get('types', []));

        // Convert to array if it's a string
        if (!is_array($types)) {
            $types = explode(',', $types);
        }

        // Filter out empty values
        $types = array_filter($types);

        if (!empty($types)) {
            $wantSubscription = in_array('subscription', $types);
            $wantRegular = in_array('regular', $types);

            if ($wantSubscription && !$wantRegular) {
                // Only subscription courses
                $query->whereHas('plans');
            } elseif ($wantRegular && !$wantSubscription) {
                // Only regular (non-subscription) courses
                $query->whereDoesntHave('plans');
            }
            // If both are selected, don't filter (show all)
        }

        // 🧭 Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'latest':
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
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            default:
                $query->latest('published_at');
                break;
        }

        // 📄 Pagination
        $perPage = min($request->get('per_page', 12), 50);
        $paginator = $query->paginate($perPage);

        // 💖 Wishlist IDs for logged-in user
        $wishlistIds = $request->user()
            ? $request->user()->wishlist()->pluck('course_id')->map(fn($id) => (int)$id)->toArray()
            : [];

        $user = $request->user();

        // 🧱 Transform data
        $courses = $paginator->getCollection()->map(function ($course) use ($user, $wishlistIds) {
            $isEnrolled = false;
            $enrollmentStatus = null;

            if ($user) {
                $enrollment = $course->enrollments()
                    ->where('user_id', $user->id)
                    ->first();

                if ($enrollment) {
                    $isEnrolled = true;
                    $enrollmentStatus = $enrollment->status;
                }
            }

            $canAccess = $user ? $user->canAccessCourse($course) : false;

            // Format instructor profile photo path
            $instructorPhoto = null;
            if ($course->instructor && $course->instructor->profile_photo_path) {
                $instructorPhoto = asset('storage/' . $course->instructor->profile_photo_path);
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
                'instructor' => [
                    'id' => $course->instructor->id,
                    'name' => $course->instructor->name,
                    'profile_photo_path' => $instructorPhoto,
                ],
                'categories' => $course->categories->map->only(['id', 'name', 'slug']),
                'enrollments_count' => $course->enrollments_count,
                'rating' => (float) $course->average_rating,
                'reviews_count' => (int) $course->reviews_count,
                'lessons_count' => $course->videos_count,
                'is_enrolled' => $isEnrolled,
                'enrollment_status' => $enrollmentStatus,
                'requires_subscription' => $course->relationLoaded('plans') ? $course->plans->isNotEmpty() : $course->plans()->exists(),
                'can_access' => $canAccess,
                'is_wishlisted' => in_array((int)$course->id, $wishlistIds),
                'wishlist_count' => (int) ($course->wishlist_count ?? 0),
                'videos' => $course->videos->map(function ($video) use ($canAccess, $isEnrolled) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'description' => $video->description,
                        'duration_in_seconds' => $video->duration_seconds,
                        'sort_order' => $video->sort_order,
                        'video_url' =>  $video->video_url ,
                        'thumbnail_url' => $video->thumbnail_url,
                    ];
                }),
            ];
        });

        // 📤 Final response
        // For GET requests (frontend compatibility), return direct data with pagination
        // For POST requests (Postman), return standardized format
        if (request()->isMethod('GET')) {
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

        return $this->successResponse($courses, 'Courses retrieved successfully', 200, [
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
        $course = Course::with(['instructor', 'videos', 'plans'])
            ->withCount([
                'enrollments as completed_enrollments_count' => function ($query) {
                    $query->where('status', 'completed');
                },
                'wishlistedBy as wishlist_count'
            ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->where('approval_status', 'approved')
            ->firstOrFail();

        $enrollmentStatus = null;
        $isEnrolled = false;

        if (Auth::check()) {
            $enrollment = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();

            if ($enrollment) {
                $isEnrolled = true;
                $enrollmentStatus = $enrollment->getProgressStatus();
            }
        }

        $canAccess = Auth::check() ? Auth::user()->canAccessCourse($course) : false;

        // Format instructor profile photo
        $instructorPhoto = null;
        if ($course->instructor && $course->instructor->profile_photo_path) {
            $instructorPhoto = asset('storage/' . $course->instructor->profile_photo_path);
        }

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'price' => (float) $course->price,
            'requires_subscription' => $course->plans->isNotEmpty(),
            'available_plans' => $course->plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => (float) $plan->price,
                    'currency' => $plan->currency,
                    'interval' => $plan->interval,
                    'interval_count' => $plan->interval_count,
                ];
            }),
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
                'profile_photo_path' => $instructorPhoto,
            ],
            'videos' => $course->videos->map(function ($video) use ($canAccess) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'duration_in_seconds' => $video->duration_seconds,
                    'sort_order' => $video->sort_order,
                    'video_url' =>  $video->video_url,
                    'thumbnail_url' => $video->thumbnail_url,
                ];
            }),
            'enrollments_count' => $course->completed_enrollments_count,
            'rating' => (float) $course->average_rating,
            'reviews_count' => (int) $course->reviews_count,
            'wishlist_count' => (int) $course->wishlist_count,
            'is_wishlisted' => Auth::check() ? $course->wishlistedBy()->where('user_id', Auth::id())->exists() : false,
            'is_enrolled' => $isEnrolled,
            'enrollment_status' => $enrollmentStatus,
            'can_access' => $canAccess,
        ];

        // For GET requests (frontend compatibility), return direct data
        // For POST requests (Postman), return standardized format
        if (request()->isMethod('GET')) {
            return response()->json($courseData);
        }

        return $this->successResponse($courseData, 'Course retrieved successfully', 200);
    }

    public function instructors()
    {
        $instructors = User::where('role', 'instructor')
            ->withCount(['courses' => function($query) {
                $query->where('is_published', true)
                      ->where('approval_status', 'approved');
            }])
            ->having('courses_count', '>', 0)
            ->get()
            ->map(function ($instructor) {
                // Format profile photo path
                $profilePhoto = null;
                if ($instructor->profile_photo_path) {
                    $profilePhoto = asset('storage/' . $instructor->profile_photo_path);
                }

                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'email' => $instructor->email,
                    'profile_photo_path' => $profilePhoto,
                    'courses_count' => $instructor->courses_count,
                ];
            });

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($instructors);
        }

        return $this->successResponse($instructors, 'Instructors retrieved successfully', 200);
    }

    public function levels()
    {
        $levels = Course::where('is_published', true)
            ->where('approval_status', 'approved')
            ->distinct()
            ->pluck('level')
            ->filter()
            ->map(function ($level) {
                return [
                    'id' => strtolower($level),
                    'name' => ucfirst($level),
                ];
            });

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($levels->values());
        }

        return $this->successResponse($levels->values(), 'Levels retrieved successfully', 200);
    }

    // POST-based CRUD methods for Postman
    public function createViaPost(StoreCourseRequest $request)
    {
        // Check authorization
        $this->authorize('create', Course::class);

        $validated = $request->validated();

        // If user is instructor (not admin), set instructor_id to current user
        if (Auth::user()->role === 'instructor' && !isset($validated['instructor_id'])) {
            $validated['instructor_id'] = Auth::id();
            // Set approval status to pending and prevent direct publishing
            $validated['approval_status'] = 'pending';
            $validated['is_published'] = false;
        }

        // Generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $course = Course::create($validated);

        // Sync categories if provided
        if (isset($validated['categories'])) {
            $course->categories()->sync($validated['categories']);
        }

        return $this->successResponse($course, 'Course created successfully', 201);
    }

    public function readViaPost(ReadResourceRequest $request)
    {
        $course = Course::with(['instructor', 'categories', 'videos', 'plans'])
            ->findOrFail($request->id);

        // Check authorization
        $this->authorize('view', $course);

        return $this->successResponse($course, 'Course retrieved successfully', 200);
    }

    public function updateViaPost(UpdateCourseRequest $request)
    {
        $validated = $request->validated();
        $courseId = $validated['id'];
        unset($validated['id']);

        $course = Course::findOrFail($courseId);

        // Check authorization
        $this->authorize('update', $course);

        // If user is instructor (not admin), prevent changing instructor_id
        if (Auth::user()->role === 'instructor' && isset($validated['instructor_id'])) {
            unset($validated['instructor_id']);
        }

        // If instructor is updating important fields, reset approval status
        if (Auth::user()->role === 'instructor') {
            $importantFields = ['title', 'description', 'price', 'what_you_will_learn', 'requirements'];
            if ($course->isDirty($importantFields)) {
                $validated['approval_status'] = 'pending';
                $validated['is_published'] = false;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($validated);

        // Sync categories if provided
        if (isset($validated['categories'])) {
            $course->categories()->sync($validated['categories']);
        }

        return $this->successResponse($course->fresh(), 'Course updated successfully', 200);
    }

    public function deleteViaPost(DeleteResourceRequest $request)
    {
        $course = Course::findOrFail($request->id);

        // Check authorization
        $this->authorize('delete', $course);

        $course->delete();

        return $this->successResponse(null, 'Course deleted successfully', 200);
    }

    // REST methods
    public function store(StoreCourseRequest $request)
    {
        return $this->createViaPost($request);
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->updateViaPost($request);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Check authorization
        $this->authorize('delete', $course);

        $course->delete();

        // For GET requests (frontend compatibility), return direct response
        if (request()->isMethod('DELETE')) {
            return response()->json(['message' => 'Course deleted successfully'], 200);
        }

        return $this->successResponse(null, 'Course deleted successfully', 200);
    }
    public function filterCourses(Request $request)
{
    try {
        $query = Course::query();

        // Apply filters
        if ($request->has('filters')) {
            $filters = $request->input('filters');

            // Level filter (can be array)
            if (!empty($filters['level'])) {
                if (is_array($filters['level'])) {
                    $query->whereIn('level', $filters['level']);
                } else {
                    $query->where('level', $filters['level']);
                }
            }

            // Price range filter
            if (isset($filters['price_min'])) {
                $query->where('price', '>=', $filters['price_min']);
            }
            if (isset($filters['price_max'])) {
                $query->where('price', '<=', $filters['price_max']);
            }

            // Instructor filter
            if (!empty($filters['instructor_id'])) {
                $query->where('instructor_id', $filters['instructor_id']);
            }

            // Status filter
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Category filter
            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            // Date range filter - created
            if (!empty($filters['created_from'])) {
                $query->whereDate('created_at', '>=', $filters['created_from']);
            }
            if (!empty($filters['created_to'])) {
                $query->whereDate('created_at', '<=', $filters['created_to']);
            }

            // Date range filter - updated
            if (!empty($filters['updated_from'])) {
                $query->whereDate('updated_at', '>=', $filters['updated_from']);
            }
            if (!empty($filters['updated_to'])) {
                $query->whereDate('updated_at', '<=', $filters['updated_to']);
            }

            // Has discount filter
            if (isset($filters['has_discount'])) {
                if ($filters['has_discount']) {
                    $query->where('discount', '>', 0);
                } else {
                    $query->where(function($q) {
                        $q->whereNull('discount')->orWhere('discount', 0);
                    });
                }
            }

            // Search filter (title or description)
            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('title', 'LIKE', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'LIKE', '%' . $filters['search'] . '%');
                });
            }
        }

        // Apply sorting
        $sortField = $request->input('sort.field', 'created_at');
        $sortOrder = $request->input('sort.order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Apply pagination
        $page = $request->input('pagination.page', 1);
        $perPage = $request->input('pagination.per_page', 20);

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Courses filtered successfully',
            'data' => $results->items(),
            'meta' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem()
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'code' => 500,
            'message' => $e->getMessage(),
            'errors' => null
        ], 500);
    }
}

}
