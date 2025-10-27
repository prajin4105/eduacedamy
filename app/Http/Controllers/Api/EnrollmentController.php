<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with(['course.instructor'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
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

        return response()->json($enrollments);
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

    return response()->json([
        'already_enrolled' => $alreadyEnrolled
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'payment_id' => ['nullable', 'string'],
            'order_id' => ['nullable', 'string'],
            'signature' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        // Check if course allows individual purchase
        if (!$course->allowsIndividualPurchase()) {
            return response()->json([
                'message' => 'This course is only available through subscription plans. Please subscribe to a plan to access this course.',
                'course_type' => $course->course_type,
                'requires_subscription' => true,
                'available_plans' => $course->getAvailablePlans()
            ], 422);
        }

        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'message' => 'You are already enrolled in this course.'
            ], 422);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount_paid' => $course->price,
            'status' => 'completed',
            'enrolled_at' => now(),
            'payment_id' => $request->payment_id,
            'order_id' => $request->order_id,
            'signature' => $request->signature,
        ]);

        return response()->json([
            'message' => 'Successfully enrolled in ' . $course->title,
            'enrollment' => [
                'id' => $enrollment->id,
                'enrolled_at' => $enrollment->enrolled_at,
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                ],
            ],
        ], 201);
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

        return response()->json([
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses,
        ]);
    }
}
