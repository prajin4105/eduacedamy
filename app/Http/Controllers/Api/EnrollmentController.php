<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEnrollmentRequest;
use App\Http\Requests\Api\UpdateEnrollmentRequest;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Middleware\RedirectIfNotInstructor;
use App\Http\Middleware\RedirectIfNotStudent;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;



class EnrollmentController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();

        // Check authorization
        $this->authorize('viewAny', Enrollment::class);

        $query = Enrollment::with(['course.instructor']);

        // Students can only see their own enrollments
        if ($user->role === 'student') {
            $query->where('user_id', $user->id);
        }
        // Instructors can see enrollments for their courses
        elseif ($user->role === 'instructor') {
            $query->whereHas('course', function ($q) use ($user) {
                $q->where('instructor_id', $user->id);
            });
        }
        // Admin can see all enrollments (no filter)

        $enrollments = $query
            ->get()
            ->map(function ($enrollment) {
                $course = $enrollment->course;
                $instructor = $course?->instructor;

                return [
                    'id' => $enrollment->id,
                    'enrolled_at' => $enrollment->enrolled_at,
                    'amount_paid' => (float) ($enrollment->amount_paid ?? 0),
                    'status' => $enrollment->status,
                    'course_id' => $course?->id ?? $enrollment->course_id,
                    'course' => $course ? [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'description' => $course->description,
                        'image' => $course->image ? asset('storage/' . $course->image) : null,
                        'level' => $course->level,
                        'instructor' => [
                            'name' => $instructor?->name,
                        ],
                    ] : null,
                ];
            });

        // For GET requests (frontend compatibility), return direct data (array)
        // For POST requests (Postman), return standardized format
        if (request()->isMethod('GET')) {
            return response()->json($enrollments);
        }

        return $this->successResponse($enrollments, 'Enrollments retrieved successfully', 200);
    }
    public function checkEnrollment(Request $request)
{
    $request->validate([
        'course_id' => 'required|integer|exists:courses,id',
    ]);

    $user = Auth::user();

    $alreadyEnrolled = Enrollment::where('user_id', $user->id)
        ->where('course_id', $request->course_id)
        ->exists();

    // For GET requests (frontend compatibility), return direct data
    if (request()->isMethod('GET')) {
        return response()->json([
            'already_enrolled' => $alreadyEnrolled
        ]);
    }

    return $this->successResponse([
        'already_enrolled' => $alreadyEnrolled
    ], 'Enrollment status checked', 200);
}


    public function store(StoreEnrollmentRequest $request)
    {
        // Check authorization
        $this->authorize('create', Enrollment::class);

        $validated = $request->validated();
        $user = Auth::user();
        $course = Course::findOrFail($validated['course_id']);

        // Students can only enroll themselves (unless admin)
        if ($user->role === 'student' && isset($validated['user_id']) && $validated['user_id'] != $user->id) {
            return $this->errorResponse('You can only enroll yourself.', 403);
        }

        // Set user_id to current user if not admin
        if ($user->role !== 'admin') {
            $validated['user_id'] = $user->id;
        }

        // Check if course allows individual purchase
        if (!$course->allowsIndividualPurchase()) {
            return $this->errorResponse(
                'This course is only available through subscription plans. Please subscribe to a plan to access this course.',
                422,
                [
                    'course_type' => $course->course_type ?? 'subscription',
                    'requires_subscription' => true,
                    'available_plans' => $course->getAvailablePlans() ?? []
                ]
            );
        }

        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();

        if ($existingEnrollment) {
            return $this->errorResponse('You are already enrolled in this course.', 422);
        }

        $enrollment = Enrollment::create([
            'user_id' => $validated['user_id'] ?? $user->id,
            'course_id' => $course->id,
            'amount_paid' => $course->price,
            'status' => 'completed',
            'enrolled_at' => now(),
            'payment_id' => $validated['payment_id'] ?? null,
            'order_id' => $validated['order_id'] ?? null,
            'signature' => $validated['signature'] ?? null,
        ]);

        return $this->successResponse([
            'id' => $enrollment->id,
            'enrolled_at' => $enrollment->enrolled_at,
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
        ], 'Successfully enrolled in ' . $course->title, 201);
    }

    public function dashboard()
    {
        $user = Auth::user();

        $enrolledCourses = Course::whereHas('enrollments', function($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'completed');
        })->with('instructor')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'image' => $course->image ? asset('storage/' . $course->image) : null,
                'level' => $course->level,
                'instructor' => [
                    'name' => $course->instructor?->name,
                ],
            ];
        });

        $availableCourses = Course::where('is_published', true)
            ->where('approval_status', 'approved')
            ->whereDoesntHave('enrollments', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'completed');
            })
            ->with('instructor')
            ->latest('published_at')
            ->take(8)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'price' => (float) $course->price,
                    'image' => $course->image ? asset('storage/' . $course->image) : null,
                    'level' => $course->level,
                    'instructor' => [
                        'name' => $course->instructor?->name,
                    ],
                ];
            });

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json([
                'enrolled_courses' => $enrolledCourses,
                'available_courses' => $availableCourses,
            ]);
        }

        return $this->successResponse([
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses,
        ], 'Dashboard data retrieved successfully', 200);
    }

    // POST-based CRUD methods for Postman
    public function createViaPost(StoreEnrollmentRequest $request)
    {
        return $this->store($request);
    }

    public function readViaPost(ReadResourceRequest $request)
    {
        $enrollment = Enrollment::with(['course', 'user'])
            ->findOrFail($request->id);

        // Check authorization
        $this->authorize('view', $enrollment);

        return $this->successResponse($enrollment, 'Enrollment retrieved successfully', 200);
    }

    public function updateViaPost(UpdateEnrollmentRequest $request)
    {
        $validated = $request->validated();
        $enrollmentId = $validated['id'];
        unset($validated['id']);

        $enrollment = Enrollment::findOrFail($enrollmentId);

        // Check authorization
        $this->authorize('update', $enrollment);

        // Students cannot update enrollments (only admin and instructors can)
        if (Auth::user()->role === 'student') {
            return $this->errorResponse('You do not have permission to update enrollments.', 403);
        }

        $enrollment->update($validated);

        return $this->successResponse($enrollment->fresh(), 'Enrollment updated successfully', 200);
    }

    public function deleteViaPost(DeleteResourceRequest $request)
    {
        $enrollment = Enrollment::findOrFail($request->id);

        // Check authorization
        $this->authorize('delete', $enrollment);

        $enrollment->delete();

        return $this->successResponse(null, 'Enrollment deleted successfully', 200);
    }

    // REST methods
    public function update(UpdateEnrollmentRequest $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // Check authorization
        $this->authorize('update', $enrollment);

        // Students cannot update enrollments
        if (Auth::user()->role === 'student') {
            return $this->errorResponse('You do not have permission to update enrollments.', 403);
        }

        $request->merge(['id' => $id]);
        return $this->updateViaPost($request);
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // Check authorization
        $this->authorize('delete', $enrollment);

        $enrollment->delete();

        // For GET requests (frontend compatibility), return direct response
        if (request()->isMethod('DELETE')) {
            return response()->json(['message' => 'Enrollment deleted successfully'], 200);
        }

        return $this->successResponse(null, 'Enrollment deleted successfully', 200);
    }
public function filterEnrollments(Request $request)
{
    // Validate / sanitize incoming params (keeps controller predictable)
    $validated = $request->validate([
        'filters' => 'nullable|array',
        'filters.user_id' => 'nullable|integer|exists:users,id',
        'filters.course_id' => 'nullable|integer|exists:courses,id',
        'filters.status' => 'nullable',
        'filters.progress_min' => 'nullable|numeric|min:0|max:100',
        'filters.progress_max' => 'nullable|numeric|min:0|max:100',
        'filters.enrolled_from' => 'nullable|date',
        'filters.enrolled_to' => 'nullable|date',
        'filters.completed_from' => 'nullable|date',
        'filters.completed_to' => 'nullable|date',
        'filters.certificate_issued' => 'nullable|boolean',

        'sort.field' => 'nullable|string',
        'sort.order' => 'nullable|in:asc,desc',

        'pagination.page' => 'nullable|integer|min:1',
        'pagination.per_page' => 'nullable|integer|min:1|max:100',
    ]);

    try {
        $query = Enrollment::query();

        $filters = $validated['filters'] ?? [];

        // Use whereWhen pattern to keep it tidy and safe
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['status'])) {
            if (is_array($filters['status'])) {
                $query->whereIn('status', $filters['status']);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        if (isset($filters['progress_min'])) {
            $query->where('progress_percentage', '>=', $filters['progress_min']);
        }
        if (isset($filters['progress_max'])) {
            $query->where('progress_percentage', '<=', $filters['progress_max']);
        }

        if (!empty($filters['enrolled_from'])) {
            $query->whereDate('enrolled_at', '>=', $filters['enrolled_from']);
        }
        if (!empty($filters['enrolled_to'])) {
            $query->whereDate('enrolled_at', '<=', $filters['enrolled_to']);
        }

        if (!empty($filters['completed_from'])) {
            $query->whereDate('completed_at', '>=', $filters['completed_from']);
        }
        if (!empty($filters['completed_to'])) {
            $query->whereDate('completed_at', '<=', $filters['completed_to']);
        }

        if (isset($filters['certificate_issued'])) {
            $cert = filter_var($filters['certificate_issued'], FILTER_VALIDATE_BOOLEAN);
            if ($cert) {
                $query->whereNotNull('certificate_issued_at');
            } else {
                $query->whereNull('certificate_issued_at');
            }
        }

        // ---- Safe sorting: whitelist allowed columns ----
        $allowedSortFields = ['enrolled_at', 'completed_at', 'progress_percentage', 'created_at', 'updated_at'];
        $sortField = $request->input('sort.field', 'enrolled_at');
        if (!in_array($sortField, $allowedSortFields, true)) {
            $sortField = 'enrolled_at'; // default fallback
        }

        $sortOrder = $request->input('sort.order', 'desc');
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';

        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $page = (int) $request->input('pagination.page', 1);
        $perPage = (int) $request->input('pagination.per_page', 15);
        $perPage = max(1, min($perPage, 100)); // safety caps

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Enrollments filtered successfully',
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

    } catch (QueryException $e) {
        // log full details, but return a safe message to client
        Log::error('Enrollment filter - DB error', [
            'message' => $e->getMessage(),
            'sql' => method_exists($e, 'getSql') ? $e->getSql() : null,
            'bindings' => method_exists($e, 'getBindings') ? $e->getBindings() : null,
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'code' => 500,
            'message' => 'A database error occurred. Please try again later.',
            'errors' => null
        ], 500);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'code' => 422,
            'message' => 'Validation failed.',
            'errors' => $e->errors()
        ], 422);
    } catch (\Throwable $e) {
        // catch Throwable to include Error as well as Exception
        Log::error('Enrollment filter - unexpected error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'code' => 500,
            'message' => 'Something went wrong. Please try again later.',
            'errors' => null
        ], 500);
    }
}

}
