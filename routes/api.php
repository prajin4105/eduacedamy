<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseProgressController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SubscriptionCourseController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TournamentController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ============================================
// PUBLIC ROUTES (NO AUTHENTICATION)
// ============================================

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
Route::get('/forgot-password', function () {
    return redirect('/forgot-password');
});

// Public category routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::get('/categories/{category:slug}/courses', [CategoryController::class, 'courses']);

// Public course routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/instructors', [CourseController::class, 'instructors']);
Route::get('/levels', [CourseController::class, 'levels']);

// Public plan routes
Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{slug}', [PlanController::class, 'show']);
Route::get('/subscriptions/popular-plan', [SubscriptionController::class, 'getPopularPlan']);

// Public certificate verification
Route::get('/verify-certificate/{certificateNumber}', [CertificateController::class, 'verifyCertificate']);

// Public payment route
Route::match(['get', 'post'], '/create-order', [PaymentController::class, 'createOrder']);

// ============================================
// PROTECTED ROUTES (REQUIRES AUTHENTICATION)
// ============================================

Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // ============================================
    // FILTER ROUTES - MUST COME BEFORE {id} ROUTES
    // ============================================
    Route::post('/users/filter', [UserController::class, 'filterUsers']);
    Route::post('/courses/filter', [CourseController::class, 'filterCourses']);
    Route::post('/enrollments/filter', [EnrollmentController::class, 'filterEnrollments']);
    Route::post('/uploads/filter', [UploadController::class, 'filterUploads']);
    Route::post('/teams/filter', [TeamController::class, 'filterTeams']);
    Route::post('/tournaments/filter', [TournamentController::class, 'filterTournaments']);
    Route::post('/leaderboards/filter', [LeaderboardController::class, 'filterLeaderboards']);
    Route::post('/videos/filter', [VideoController::class, 'filterVideos']);
    Route::post('/tests/filter', [TestController::class, 'filterTests']);

    // ============================================
    // POST-BASED CRUD ENDPOINTS FOR POSTMAN
    // ============================================

    // Users
    Route::post('/users/create', [UserController::class, 'createViaPost']);
    Route::post('/users/read', [UserController::class, 'readViaPost']);
    Route::post('/users/update', [UserController::class, 'updateViaPost']);
    Route::post('/users/delete', [UserController::class, 'deleteViaPost']);

    // Courses
    Route::post('/courses/create', [CourseController::class, 'createViaPost']);
    Route::post('/courses/read', [CourseController::class, 'readViaPost']);
    Route::post('/courses/update', [CourseController::class, 'updateViaPost']);
    Route::post('/courses/delete', [CourseController::class, 'deleteViaPost']);

    // Enrollments
    Route::post('/enrollments/create', [EnrollmentController::class, 'createViaPost']);
    Route::post('/enrollments/read', [EnrollmentController::class, 'readViaPost']);
    Route::post('/enrollments/update', [EnrollmentController::class, 'updateViaPost']);
    Route::post('/enrollments/delete', [EnrollmentController::class, 'deleteViaPost']);
    Route::post('/enrollments/check', [EnrollmentController::class, 'checkEnrollment']);


    // Videos
    Route::post('/videos/create', [VideoController::class, 'createViaPost']);
    Route::post('/videos/read', [VideoController::class, 'readViaPost']);
    Route::post('/videos/update', [VideoController::class, 'updateViaPost']);
    Route::post('/videos/delete', [VideoController::class, 'deleteViaPost']);

    // Tests
    Route::post('/tests/create', [TestController::class, 'createViaPost']);
    Route::post('/tests/read', [TestController::class, 'readViaPost']);
    Route::post('/tests/update', [TestController::class, 'updateViaPost']);
    Route::post('/tests/delete', [TestController::class, 'deleteViaPost']);

    // ============================================
    // ENROLLMENT ROUTES
    // ============================================
    Route::apiResource('enrollments', EnrollmentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('/dashboard', [EnrollmentController::class, 'dashboard']);

    // ============================================
    // COURSE PROGRESS ROUTES
    // ============================================
    Route::get('/courses/{courseId}/progress', [CourseProgressController::class, 'getCourseProgress']);
    Route::post('/courses/{courseId}/videos/{videoId}/complete', [CourseProgressController::class, 'markVideoCompleted']);
    Route::post('/courses/{courseId}/time-spent', [CourseProgressController::class, 'updateTimeSpent']);
    Route::get('/dashboard/progress', [CourseProgressController::class, 'getDashboardProgress']);
    Route::get('/courses/{courseId}/enrollment-status', [CourseProgressController::class, 'getCourseEnrollmentStatus']);

    // ============================================
    // CERTIFICATE ROUTES
    // ============================================
    Route::get('/certificates', [CertificateController::class, 'index']);
    Route::post('/courses/{courseId}/certificate/generate', [CertificateController::class, 'generate']);
    Route::get('/courses/{courseId}/certificate/preview', [CertificateController::class, 'preview']);
    Route::get('/courses/{courseId}/certificate/check-eligibility', [CertificateController::class, 'checkEligibility']);
    Route::get('/certificates/{certificateId}/download', [CertificateController::class, 'download']);
    Route::get('/certificate/download/{courseId}', [CertificateController::class, 'downloadByCourse']);

    // ============================================
    // TEST ROUTES
    // ============================================
    Route::get('/tests', [TestController::class, 'index']);
    Route::get('/test/{courseId}', [TestController::class, 'showTest']);
    Route::post('/test/submit', [TestController::class, 'submitTest']);
    Route::get('/test/status/{courseId}', [TestController::class, 'status']);
    Route::apiResource('tests', TestController::class)->except(['index']);

    // ============================================
    // VIDEO ROUTES
    // ============================================
    Route::get('/videos', [VideoController::class, 'index']);
    Route::apiResource('videos', VideoController::class)->except(['index']);

    // ============================================
    // REVIEW ROUTES
    // ============================================
    Route::post('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    Route::get('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);

    // ============================================
    // SUBSCRIPTION ROUTES
    // ============================================
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
    Route::get('/subscriptions/status', [SubscriptionController::class, 'status']);
    Route::get('/subscriptions/courses', [SubscriptionCourseController::class, 'index']);
    Route::get('/subscriptions', [SubscriptionController::class, 'mySubscriptions']);

    // ============================================
    // WISHLIST ROUTES
    // ============================================
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{course}', [WishlistController::class, 'destroy']);

    // ============================================
    // UPLOAD ROUTES
    // ============================================
    Route::post('/uploads', [UploadController::class, 'store']);
    Route::post('/uploads/multiple', [UploadController::class, 'storeMultiple']);

    // ============================================
    // API RESOURCE ROUTES
    // ============================================
    Route::apiResource('users', UserController::class);
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('tournaments', TournamentController::class);
    Route::apiResource('leaderboards', LeaderboardController::class);

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/{key}', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);

    // Instructor Application routes (rate limited: 5 per hour)
    Route::post('/instructor/apply', [App\Http\Controllers\Api\InstructorApplicationController::class, 'apply'])
        ->middleware('throttle:5,60');
});

// ============================================
// COURSES ROUTE - MUST BE LAST (has {slug} parameter)
// ============================================
Route::get('/courses/{slug}', [CourseController::class, 'show']);
Route::post('/test-filter', function() {
    return response()->json(['message' => 'Filter route is working!']);
});
