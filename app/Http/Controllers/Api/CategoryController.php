<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of all active categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'description', 'image']);

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Get courses by category.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function courses(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $courses = $category->courses()
            ->with(['instructor:id,name', 'categories:id,name,slug'])
            ->where('is_published', true)
            ->where('approval_status', 'approved')
            ->withAvg('reviews', 'rating')
            ->withCount('enrollments')
            ->orderByDesc('created_at')
            ->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category->only(['id', 'name', 'slug', 'description']),
                'courses' => $courses
            ]
        ]);
    }

    /**
     * Display the specified category.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail(['id', 'name', 'slug', 'description', 'image']);

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

}
