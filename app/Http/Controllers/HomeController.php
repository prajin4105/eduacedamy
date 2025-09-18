<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $level = $request->get('level');
        $instructor = $request->get('instructor');

        $coursesQuery = Course::with('instructor')
            ->where('is_published', true);

        if ($search) {
            $coursesQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($level) {
            $coursesQuery->where('level', $level);
        }

        if ($instructor) {
            $coursesQuery->where('instructor_id', $instructor);
        }

        $courses = $coursesQuery->latest('published_at')->paginate(12);
        $latestCourses = Course::with('instructor')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        $instructors = User::where('role', 'instructor')
            ->withCount(['enrolledCourses' => function($query) {
                $query->where('is_published', true);
            }])
            ->having('enrolled_courses_count', '>', 0)
            ->get();

        $levels = Course::where('is_published', true)
            ->distinct()
            ->pluck('level')
            ->filter();

        return view('home', compact('courses', 'latestCourses', 'instructors', 'levels', 'search', 'level', 'instructor'));
    }
}
