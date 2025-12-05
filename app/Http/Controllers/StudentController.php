<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get enrolled courses
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
                    'name' => $course->instructor->name,
                ],
            ];
        });

        // Get available courses (not enrolled)
        $availableCourses = Course::where('is_published', true)
            ->where('approval_status', 'approved')
            ->whereDoesntHave('enrollments', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'completed');
            })
            ->with('instructor')
            ->latest('published_at')
            ->take(8)
            ->get()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'price' => (float) $course->price,
                    'image' => $course->image ? asset('storage/' . $course->image) : null,
                    'level' => $course->level,
                    'instructor' => [
                        'name' => $course->instructor->name,
                    ],
                ];
            });

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses,
        ]);
    }

    public function showCourse(Course $course)
    {
        $user = Auth::user();
        
        // Allow access if enrolled or has active subscription covering the course
        if (!$user->isEnrolledIn($course->id) && !$user->canAccessCourse($course)) {
            return response()->json(['error' => 'Access denied. Subscription or enrollment required.'], 403);
        }

        // Load course with videos and instructor
        $course->load(['videos' => function($query) {
            $query->where('is_published', true)->orderBy('sort_order');
        }, 'instructor']);

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'image' => $course->image ? asset('storage/' . $course->image) : null,
            'level' => $course->level,
            'language' => $course->language,
            'duration_in_minutes' => $course->duration_in_minutes,
            'what_you_will_learn' => $course->what_you_will_learn,
            'requirements' => $course->requirements,
            'instructor' => [
                'name' => $course->instructor->name,
            ],
            'videos' => $course->videos->map(function ($video) use ($course, $user) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'video_url' => $user->canAccessCourse($course) && $video->video_url ? asset('storage/' . $video->video_url) : null,
                    'thumbnail_url' => $video->thumbnail_url ? asset('storage/' . $video->thumbnail_url) : null,
                    'duration_seconds' => $video->duration_seconds,
                    'sort_order' => $video->sort_order,
                ];
            }),
        ]);
    }

    public function showVideo(Course $course, $videoId)
    {
        $user = Auth::user();
        
        // Allow access if enrolled or has active subscription covering the course
        if (!$user->isEnrolledIn($course->id) && !$user->canAccessCourse($course)) {
            return response()->json(['error' => 'Access denied. Subscription or enrollment required.'], 403);
        }

        // Load course with videos
        $course->load(['videos' => function($query) {
            $query->where('is_published', true)->orderBy('sort_order');
        }, 'instructor']);

        // Find the specific video
        $video = $course->videos->find($videoId);
        
        if (!$video) {
            return response()->json(['error' => 'Video not found.'], 404);
        }

        return response()->json([
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'image' => $course->image ? asset('storage/' . $course->image) : null,
                'level' => $course->level,
                'language' => $course->language,
                'duration_in_minutes' => $course->duration_in_minutes,
                'what_you_will_learn' => $course->what_you_will_learn,
                'requirements' => $course->requirements,
                'instructor' => [
                    'name' => $course->instructor->name,
                ],
                'videos' => $course->videos->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'title' => $v->title,
                        'description' => $v->description,
                        'video_url' => $v->video_url ? asset('storage/' . $v->video_url) : null,
                        'thumbnail_url' => $v->thumbnail_url ? asset('storage/' . $v->thumbnail_url) : null,
                        'duration_seconds' => $v->duration_seconds,
                        'sort_order' => $v->sort_order,
                    ];
                }),
            ],
            'video' => [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description,
                'video_url' => $user->canAccessCourse($course) && $video->video_url ? asset('storage/' . $video->video_url) : null,
                'thumbnail_url' => $video->thumbnail_url ? asset('storage/' . $video->thumbnail_url) : null,
                'duration_seconds' => $video->duration_seconds,
                'sort_order' => $video->sort_order,
            ],
        ]);
    }

    public function buyCourse(Request $request, Course $course)
    {
        $user = Auth::user();

        // Check if already enrolled
        if ($user->isEnrolledIn($course->id)) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount_paid' => $course->price,
            'status' => 'completed', // In real app, this would be 'pending' until payment
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Successfully enrolled in ' . $course->title);
    }
}
