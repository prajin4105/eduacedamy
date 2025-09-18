<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseProgressController;

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
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

// Public API routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{slug}', [CourseController::class, 'show']);
Route::get('/instructors', [CourseController::class, 'instructors']);
Route::get('/levels', [CourseController::class, 'levels']);

// Protected API routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
	Route::apiResource('enrollments', EnrollmentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
	Route::get('/dashboard', [EnrollmentController::class, 'dashboard']);
	
	// Course Progress Tracking Routes
	Route::get('/courses/{courseId}/progress', [CourseProgressController::class, 'getCourseProgress']);
	Route::post('/courses/{courseId}/videos/{videoId}/complete', [CourseProgressController::class, 'markVideoCompleted']);
	Route::post('/courses/{courseId}/time-spent', [CourseProgressController::class, 'updateTimeSpent']);
	Route::get('/dashboard/progress', [CourseProgressController::class, 'getDashboardProgress']);
	Route::get('/courses/{courseId}/enrollment-status', [CourseProgressController::class, 'getCourseEnrollmentStatus']);
});
use App\Http\Controllers\PaymentController;
Route::match(['get', 'post'], '/create-order', [PaymentController::class, 'createOrder']);
Route::post('/enrollments/check', [EnrollmentController::class, 'checkEnrollment']);
