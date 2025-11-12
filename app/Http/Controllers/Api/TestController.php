<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
   public function showTest(int $courseId)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $course = Course::findOrFail($courseId);

    if (!$this->hasCompletedCourse($user->id, $courseId)) {
        return response()->json(['message' => 'Complete all videos to access the test'], 403);
    }

    $test = Test::where('course_id', $courseId)->first();

    if (!$test) {
        return response()->json(['message' => 'No test available for this course'], 404);
    }

    // If already passed, do not show test
    $hasPassed = UserTest::where('user_id', $user->id)
        ->where('test_id', $test->id)
        ->where('passed', true)
        ->exists();
    if ($hasPassed) {
        return response()->json(['message' => 'Test already passed'], 403);
    }

    // Fetch only random limited questions
    $questions = TestQuestion::where('test_id', $test->id)
        ->inRandomOrder()
        ->take($test->total_questions ?? 5) // fallback to 5 if null
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

    return response()->json([
        'test' => [
            'id' => $test->id,
            'title' => $test->title,
            'description' => $test->description,
            'course_id' => $test->course_id,
            'max_attempts' => $test->max_attempts ?? 3,
            'total_questions' => $test->total_questions ?? 5,
        ],
        'questions' => $questions,
    ]);
}


    public function submitTest(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Updated validation to allow nullable selected_option for auto-submit
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|exists:courses,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer|exists:test_questions,id',
            'answers.*.selected_option' => 'nullable|in:a,b,c,d', // Changed to nullable
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $courseId = (int) $request->input('course_id');

        if (!$this->hasCompletedCourse($user->id, $courseId)) {
            return response()->json(['message' => 'Complete all videos before submitting the test'], 403);
        }

        $test = Test::with('course')->where('course_id', $courseId)->first();
        if (!$test) {
            return response()->json(['message' => 'No test available for this course'], 404);
        }

        // Get max_attempts from test (default to 3 if not set)
        $maxAttempts = $test->max_attempts ?? 3;

        // Attempts logic: allow max attempts from database; block if already passed
        $attempts = UserTest::where('user_id', $user->id)->where('test_id', $test->id)->orderBy('attempted_at')->get();
        if ($attempts->firstWhere('passed', true)) {
            return response()->json(['message' => 'You have already passed this test'], 409);
        }
        if ($attempts->count() >= $maxAttempts) {
            return response()->json(['message' => 'Maximum attempts reached'], 429);
        }

        $answers = collect($request->input('answers'));
        $questionIds = $answers->pluck('question_id')->all();

        $questions = TestQuestion::whereIn('id', $questionIds)->where('test_id', $test->id)->get();
        if ($questions->count() === 0) {
            return response()->json(['message' => 'No valid questions submitted'], 422);
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
            return response()->json(['message' => 'Failed to record test attempt', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'score' => $scorePercent,
            'passed' => $passed,
            'attempt_number' => $attempts->count() + 1,
        ]);
    }

    public function status(int $courseId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $test = Test::where('course_id', $courseId)->first();
        if (!$test) {
            // no test for this course
            return response()->json([
                'hasTest' => false,
                'passed' => null,
                'attempts' => [],
                'attemptsRemaining' => null,
                'hasCertificate' => false,
            ]);
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

        return response()->json([
            'hasTest' => true,
            'passed' => $passed,
            'attempts' => $attempts,
            'attemptsRemaining' => $attemptsRemaining,
            'hasCertificate' => $hasCertificate,
            'maxAttempts' => $maxAttempts, // Include max_attempts in response
        ]);
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
