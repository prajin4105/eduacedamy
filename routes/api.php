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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Token-based auth (PUBLIC - no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']); // POST version for Postman
Route::post('/auth/register', [AuthController::class, 'register']); // POST version for Postman
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// Redirect GET requests to forgot-password to the frontend page
Route::get('/forgot-password', function () {
    return redirect('/forgot-password');
});



Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::get('/me', [AuthController::class, 'me']);
	Route::post('/auth/me', [AuthController::class, 'me']); // POST version for Postman
	Route::post('/auth/logout', [AuthController::class, 'logout']); // POST version for Postman
	// Profile routes
	Route::get('/profile', [ProfileController::class, 'show']);
	Route::post('/profile', [ProfileController::class, 'update']);
});

// Public category routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::get('/categories/{category:slug}/courses', [CategoryController::class, 'courses']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Public API
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{slug}', [CourseController::class, 'show']);
Route::get('/instructors', [CourseController::class, 'instructors']);
Route::get('/levels', [CourseController::class, 'levels']);
Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{slug}', [PlanController::class, 'show']);
Route::get('/subscriptions/popular-plan', [SubscriptionController::class, 'getPopularPlan']);

// Public review routes
    Route::get('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);

// Public certificate verification route
    Route::get('/verify-certificate/{certificateNumber}', [\App\Http\Controllers\Api\CertificateController::class, 'verifyCertificate']);

// Protected API routes (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
	Route::apiResource('enrollments', EnrollmentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
	Route::get('/dashboard', [EnrollmentController::class, 'dashboard']);

	// Course Progress Tracking Routes
	Route::get('/courses/{courseId}/progress', [CourseProgressController::class, 'getCourseProgress']);
	Route::post('/courses/{courseId}/videos/{videoId}/complete', [CourseProgressController::class, 'markVideoCompleted']);
	Route::post('/courses/{courseId}/time-spent', [CourseProgressController::class, 'updateTimeSpent']);
	Route::get('/dashboard/progress', [CourseProgressController::class, 'getDashboardProgress']);

	// Certificate routes
	Route::get('/certificates', [CertificateController::class, 'index']);
	Route::post('/courses/{courseId}/certificate/generate', [CertificateController::class, 'generate']);
	Route::get('/courses/{courseId}/certificate/preview', [CertificateController::class, 'preview']);
	Route::get('/courses/{courseId}/certificate/check-eligibility', [CertificateController::class, 'checkEligibility']);
	Route::get('/certificates/{certificateId}/download', [CertificateController::class, 'download']);

	// Test routes (existing - for students taking tests)
	Route::get('/test/{courseId}', [TestController::class, 'showTest']);
	Route::post('/test/submit', [TestController::class, 'submitTest']);
	Route::get('/test/status/{courseId}', [TestController::class, 'status']);
	Route::get('/certificate/download/{courseId}', [CertificateController::class, 'downloadByCourse']);

	// Videos routes
	Route::get('/videos', [VideoController::class, 'index']);
	Route::apiResource('videos', VideoController::class)->except(['index']);

	// Tests CRUD routes (for admin/instructors)
	Route::get('/tests', [TestController::class, 'index']);
	Route::apiResource('tests', TestController::class)->except(['index']);

	// Review Routes
	Route::post('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
	Route::put('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update']);
	Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    Route::get('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);

	Route::get('/courses/{courseId}/enrollment-status', [CourseProgressController::class, 'getCourseEnrollmentStatus']);
});
    Route::match(['get', 'post'], '/create-order', [PaymentController::class, 'createOrder']);

// Protected enrollment check route
    Route::middleware('auth:sanctum')->group(function () {
    Route::post('/enrollments/check', [EnrollmentController::class, 'checkEnrollment']);
    // Subscription routes
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
    Route::get('/subscriptions/status', [SubscriptionController::class, 'status']);
    Route::get('/subscriptions/courses', [SubscriptionCourseController::class, 'index']);
    Route::get('/subscriptions', [SubscriptionController::class, 'mySubscriptions']);
    // routes/api.php or web.php

});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{course}', [WishlistController::class, 'destroy']);

    // ============================================
    // POST-BASED CRUD ENDPOINTS FOR POSTMAN
    // ============================================
    
    // Users POST endpoints
    Route::post('/users/create', [UserController::class, 'createViaPost']);
    Route::post('/users/read', [UserController::class, 'readViaPost']);
    Route::post('/users/update', [UserController::class, 'updateViaPost']);
    Route::post('/users/delete', [UserController::class, 'deleteViaPost']);
    Route::apiResource('users', UserController::class);

    // Courses POST endpoints
    Route::post('/courses/create', [CourseController::class, 'createViaPost']);
    Route::post('/courses/read', [CourseController::class, 'readViaPost']);
    Route::post('/courses/update', [CourseController::class, 'updateViaPost']);
    Route::post('/courses/delete', [CourseController::class, 'deleteViaPost']);

    // Enrollments POST endpoints
    Route::post('/enrollments/create', [EnrollmentController::class, 'createViaPost']);
    Route::post('/enrollments/read', [EnrollmentController::class, 'readViaPost']);
    Route::post('/enrollments/update', [EnrollmentController::class, 'updateViaPost']);
    Route::post('/enrollments/delete', [EnrollmentController::class, 'deleteViaPost']);

    // Teams POST endpoints
    Route::post('/teams/create', [TeamController::class, 'createViaPost']);
    Route::post('/teams/read', [TeamController::class, 'readViaPost']);
    Route::post('/teams/update', [TeamController::class, 'updateViaPost']);
    Route::post('/teams/delete', [TeamController::class, 'deleteViaPost']);
    Route::apiResource('teams', TeamController::class);

    // Tournaments POST endpoints
    Route::post('/tournaments/create', [TournamentController::class, 'createViaPost']);
    Route::post('/tournaments/read', [TournamentController::class, 'readViaPost']);
    Route::post('/tournaments/update', [TournamentController::class, 'updateViaPost']);
    Route::post('/tournaments/delete', [TournamentController::class, 'deleteViaPost']);
    Route::apiResource('tournaments', TournamentController::class);

    // Leaderboards POST endpoints
    Route::post('/leaderboards/create', [LeaderboardController::class, 'createViaPost']);
    Route::post('/leaderboards/read', [LeaderboardController::class, 'readViaPost']);
    Route::post('/leaderboards/update', [LeaderboardController::class, 'updateViaPost']);
    Route::post('/leaderboards/delete', [LeaderboardController::class, 'deleteViaPost']);
    Route::apiResource('leaderboards', LeaderboardController::class);

    // Settings POST endpoints
    Route::post('/settings/read', [SettingsController::class, 'readViaPost']);
    Route::post('/settings/update', [SettingsController::class, 'updateViaPost']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/{key}', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);

    // Uploads
    Route::post('/uploads', [UploadController::class, 'store']);
    Route::post('/uploads/multiple', [UploadController::class, 'storeMultiple']);

    // Videos POST endpoints
    Route::post('/videos/create', [VideoController::class, 'createViaPost']);
    Route::post('/videos/read', [VideoController::class, 'readViaPost']);
    Route::post('/videos/update', [VideoController::class, 'updateViaPost']);
    Route::post('/videos/delete', [VideoController::class, 'deleteViaPost']);

    // Tests POST endpoints
    Route::post('/tests/create', [TestController::class, 'createViaPost']);
    Route::post('/tests/read', [TestController::class, 'readViaPost']);
    Route::post('/tests/update', [TestController::class, 'updateViaPost']);
    Route::post('/tests/delete', [TestController::class, 'deleteViaPost']);
});
