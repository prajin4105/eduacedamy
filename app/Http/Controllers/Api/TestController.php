<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\UserTest;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TestController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of tests.
     */
    public function index(Request $request)
    {
        $query = Test::with('course');

        // Filter by course_id if provided
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Students can only see tests for courses they're enrolled in
        if (Auth::user()->role === 'student') {
            $enrolledCourseIds = Auth::user()->enrollments()
                ->where('status', 'completed')
                ->pluck('course_id');
            $query->whereIn('course_id', $enrolledCourseIds);
        }
        // Instructors can only see tests for their courses
        elseif (Auth::user()->role === 'instructor') {
            $query->whereHas('course', function ($q) {
                $q->where('instructor_id', Auth::id());
            });
        }

        $tests = $query->get();

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($tests);
        }

        return $this->successResponse($tests, 'Tests retrieved successfully', 200);
    }

    /**
     * Store a newly created test.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', Test::class);

        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'total_questions' => ['nullable', 'integer', 'min:1'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        // Instructors can only create tests for their own courses
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            return $this->errorResponse('You can only create tests for your own courses.', 403);
        }

        $test = Test::create($validated);

        return $this->successResponse($test, 'Test created successfully', 201);
    }

    /**
     * Display the specified test.
     */
    public function show($id)
    {
        $test = Test::with('course')->findOrFail($id);

        // Check authorization
        $this->authorize('view', $test);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($test);
        }

        return $this->successResponse($test, 'Test retrieved successfully', 200);
    }

    /**
     * Update the specified test.
     */
    public function update(Request $request, $id)
    {
        $test = Test::with('course')->findOrFail($id);

        // Check authorization
        $this->authorize('update', $test);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'total_questions' => ['nullable', 'integer', 'min:1'],
        ]);

        $test->update($validated);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            return response()->json($test->fresh());
        }

        return $this->successResponse($test->fresh(), 'Test updated successfully', 200);
    }

    /**
     * Remove the specified test.
     */
    public function destroy($id)
    {
        $test = Test::with('course')->findOrFail($id);

        // Check authorization
        $this->authorize('delete', $test);

        $test->delete();

        return $this->successResponse(null, 'Test deleted successfully', 200);
    }

    /**
     * Show test for student to take (existing functionality)
     */
    public function showTest(int $courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        // Check authorization - students must be enrolled
        if ($user->role === 'student') {
            $test = Test::where('course_id', $courseId)->first();
            if ($test) {
                $this->authorize('view', $test);
            }
        }

        if (!$this->hasCompletedCourse($user->id, $courseId)) {
            return $this->errorResponse('Complete all videos to access the test', 403);
        }

        $test = Test::where('course_id', $courseId)->first();

        if (!$test) {
            return $this->errorResponse('No test available for this course', 404);
        }

        // If already passed, do not show test
        $hasPassed = UserTest::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->where('passed', true)
            ->exists();
        if ($hasPassed) {
            return $this->errorResponse('Test already passed', 403);
        }

        // Fetch only random limited questions
        $questions = TestQuestion::where('test_id', $test->id)
            ->inRandomOrder()
            ->take($test->total_questions ?? 5)
            ->get()
            ->map(function (TestQuestion $q) {
                return [
                    'id' => $q->id,
                    'question_text' => $q->question_text,
                    'options' => [
                        'a' => $q->option_a,
                        'b' => $q->option_b,
                        'c' => $q->option_c,
                        'd' => $q->option_d,
                    ],
                ];
            });

        $response = [
            'test' => [
                'id' => $test->id,
                'title' => $test->title,
                'description' => $test->description,
                'course_id' => $test->course_id,
                'max_attempts' => $test->max_attempts ?? 3,
                'total_questions' => $test->total_questions ?? 5,
            ],
            'questions' => $questions,
        ];

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($response);
        }

        return $this->successResponse($response, 'Test retrieved successfully', 200);
    }


    public function submitTest(Request $request)
    {
        $user = Auth::user();

        // Updated validation to allow nullable selected_option for auto-submit
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|exists:courses,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer|exists:test_questions,id',
            'answers.*.selected_option' => 'nullable|in:a,b,c,d',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $courseId = (int) $request->input('course_id');

        if (!$this->hasCompletedCourse($user->id, $courseId)) {
            return $this->errorResponse('Complete all videos before submitting the test', 403);
        }

        $test = Test::with('course')->where('course_id', $courseId)->first();
        if (!$test) {
            return $this->errorResponse('No test available for this course', 404);
        }

        // Get max_attempts from test (default to 3 if not set)
        $maxAttempts = $test->max_attempts ?? 3;

        // Attempts logic: allow max attempts from database; block if already passed
        $attempts = UserTest::where('user_id', $user->id)->where('test_id', $test->id)->orderBy('attempted_at')->get();
        if ($attempts->firstWhere('passed', true)) {
            return $this->errorResponse('You have already passed this test', 409);
        }
        if ($attempts->count() >= $maxAttempts) {
            return $this->errorResponse('Maximum attempts reached', 429);
        }

        $answers = collect($request->input('answers'));
        $questionIds = $answers->pluck('question_id')->all();

        $questions = TestQuestion::whereIn('id', $questionIds)->where('test_id', $test->id)->get();
        if ($questions->count() === 0) {
            return $this->errorResponse('No valid questions submitted', 422);
        }

        $numCorrect = 0;
        $answersStored = [];
        foreach ($questions as $question) {
            $answerData = $answers->firstWhere('question_id', $question->id);
            $selected = $answerData['selected_option'] ?? null;

            // Only count as correct if an answer was selected and it matches
            $isCorrect = $selected && $selected === $question->correct_option;
            if ($isCorrect) {
                $numCorrect++;
            }

            $answersStored[] = [
                'question_id' => $question->id,
                'selected' => $selected, // Can be null for unanswered questions
                'correct' => $question->correct_option,
                'is_correct' => (bool) $isCorrect,
            ];
        }

        $total = max($questions->count(), 1);
        $scorePercent = (int) floor(($numCorrect / $total) * 100);

        // Use test's passing_score or default to 75
        $passingScore = $test->passing_score ?? CertificateService::DEFAULT_PASSING_SCORE;
        $passed = $scorePercent >= $passingScore;

        DB::beginTransaction();
        try {
            $userTest = UserTest::create([
                'user_id' => $user->id,
                'test_id' => $test->id,
                'score' => $scorePercent,
                'passed' => $passed,
                'num_correct' => $numCorrect,
                'num_questions' => $total,
                'attempt_number' => $attempts->count() + 1,
                'answers' => json_encode($answersStored),
                'attempted_at' => now(),
            ]);

            if ($passed) {
                // Use CertificateService to check and issue certificate
                $certificateService = new CertificateService();
                $certificateService->checkAndIssueCertificate($user, $test->course);

                // Update course progress to 100% and mark as completed
                $courseProgress = CourseProgress::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $courseId],
                    [
                        'total_videos' => $test->course->getTotalVideosCount(),
                        'videos_completed' => 0,
                        'progress_percentage' => 0,
                    ]
                );
                $courseProgress->update([
                    'progress_percentage' => 100,
                    'is_completed' => true,
                    'completed_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to record test attempt: ' . $e->getMessage(), 500);
        }

        $response = [
            'score' => $scorePercent,
            'passed' => $passed,
            'attempt_number' => $attempts->count() + 1,
        ];

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('POST')) {
            // Check if it's a POST request from frontend (not standardized)
            $acceptHeader = request()->header('Accept', '');
            if (str_contains($acceptHeader, 'application/json') && !request()->expectsJson()) {
                return response()->json($response);
            }
        }

        return $this->successResponse($response, 'Test submitted successfully', 200);
    }

    public function status(int $courseId)
    {
        $user = Auth::user();
        $test = Test::where('course_id', $courseId)->first();

        if (!$test) {
            // no test for this course
            $response = [
                'hasTest' => false,
                'passed' => null,
                'attempts' => [],
                'attemptsRemaining' => null,
                'hasCertificate' => false,
            ];

            // For GET requests (frontend compatibility), return direct data
            if (request()->isMethod('GET')) {
                return response()->json($response);
            }

            return $this->successResponse($response, 'Test status retrieved', 200);
        }

        // Get max_attempts from test (default to 3 if not set)
        $maxAttempts = $test->max_attempts ?? 3;

        $attempts = UserTest::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->orderBy('attempted_at', 'asc')
            ->get()
            ->map(function (UserTest $a) {
                return [
                    'id' => $a->id,
                    'score' => $a->score,
                    'passed' => (bool) $a->passed,
                    'attempt_number' => $a->attempt_number,
                    'num_correct' => $a->num_correct,
                    'num_questions' => $a->num_questions,
                    'answers' => $a->answers ? json_decode($a->answers, true) : [],
                    'attempted_at' => $a->attempted_at,
                ];
            });

        $passed = $attempts->firstWhere('passed', true) ? true : false;
        $attemptsRemaining = $passed ? 0 : max(0, $maxAttempts - $attempts->count());

        // Check if certificate exists (only if test is passed)
        $hasCertificate = false;
        if ($passed) {
            $certificate = Certificate::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->first();
            $hasCertificate = $certificate !== null;
        }

        $response = [
            'hasTest' => true,
            'passed' => $passed,
            'attempts' => $attempts,
            'attemptsRemaining' => $attemptsRemaining,
            'hasCertificate' => $hasCertificate,
            'maxAttempts' => $maxAttempts,
        ];

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($response);
        }

        return $this->successResponse($response, 'Test status retrieved successfully', 200);
    }

    // POST-based CRUD methods for Postman
    public function createViaPost(Request $request)
    {
        return $this->store($request);
    }

    public function readViaPost(ReadResourceRequest $request)
    {
        return $this->show($request->id);
    }

    public function updateViaPost(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        return $this->update($request, $request->id);
    }

    public function deleteViaPost(DeleteResourceRequest $request)
    {
        return $this->destroy($request->id);
    }

    private function hasCompletedCourse(int $userId, int $courseId): bool
    {
        return DB::table('course_progress')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('progress_percentage', '>=', 100)
            ->exists();
    }
}
