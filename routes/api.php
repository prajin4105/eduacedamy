<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseProgressController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\PaymentController;

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

// Token-based auth
Route::match(['get', 'post'], '/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::get('/me', [AuthController::class, 'me']);
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

// Public review routes
Route::get('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);

// Payment routes
Route::match(['get', 'post'], '/create-order', [PaymentController::class, 'createOrder']);

// Protected API routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
	Route::apiResource('enrollments', EnrollmentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
	Route::get('/dashboard', [EnrollmentController::class, 'dashboard']);
	Route::post('/enrollments/check', [EnrollmentController::class, 'checkEnrollment']);

	// Course Progress Tracking Routes
	Route::get('/courses/{courseId}/progress', [CourseProgressController::class, 'getCourseProgress']);
	Route::post('/courses/{courseId}/videos/{videoId}/complete', [CourseProgressController::class, 'markVideoCompleted']);
	Route::post('/courses/{courseId}/time-spent', [CourseProgressController::class, 'updateTimeSpent']);
	Route::get('/dashboard/progress', [CourseProgressController::class, 'getDashboardProgress']);
	Route::get('/courses/{courseId}/enrollment-status', [CourseProgressController::class, 'getCourseEnrollmentStatus']);

	// Review Routes
	Route::post('/courses/{course}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
	Route::put('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update']);
	Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);

	// Certificate Routes - Consolidated and Fixed
	Route::get('/certificates', [CertificateController::class, 'index']);
	Route::get('/courses/{courseId}/certificate/eligibility', [CertificateController::class, 'checkEligibility']);
	Route::post('/courses/{courseId}/certificate/generate', [CertificateController::class, 'generate']);
	Route::get('/certificates/{certificateId}/download', [CertificateController::class, 'download']);
});
