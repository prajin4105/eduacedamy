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

        $enrollments = $query->where('status', 'completed')
            ->get()
            ->map(function ($enrollment) {
                $course = $enrollment->course;
                $instructor = $course?->instructor;

                return [
                    'id' => $enrollment->id,
                    'enrolled_at' => $enrollment->enrolled_at,
                    'amount_paid' => (float) ($enrollment->amount_paid ?? 0),
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
        ->where('status', 'completed')
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
}
