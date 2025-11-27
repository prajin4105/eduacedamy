<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of videos for a course.
     */
    public function index(Request $request)
    {
        $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $course = Course::findOrFail($request->course_id);
        
        // Check if user can view the course
        $this->authorize('view', $course);

        $query = Video::where('course_id', $request->course_id);

        // Students can only see published videos
        if (Auth::user()->role === 'student') {
            $query->where('is_published', true);
        }

        $videos = $query->orderBy('sort_order')->get();

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($videos);
        }

        return $this->successResponse($videos, 'Videos retrieved successfully', 200);
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', Video::class);

        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_url' => ['required', 'string'],
            'thumbnail_url' => ['nullable', 'string'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'thumbnail_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        // Instructors can only create videos for their own courses
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            return $this->errorResponse('You can only create videos for your own courses.', 403);
        }

        // Handle thumbnail file upload
        if ($request->hasFile('thumbnail_file')) {
            // Store returns path relative to disk root (e.g., 'video-thumbnails/filename.jpg')
            // Model accessor will add 'storage/' prefix when accessed
            $validated['thumbnail_url'] = $request->file('thumbnail_file')->store('video-thumbnails', 'public');
        }

        $video = Video::create($validated);

        return $this->successResponse($video, 'Video created successfully', 201);
    }

    /**
     * Display the specified video.
     */
    public function show($id)
    {
        $video = Video::with('course')->findOrFail($id);

        // Check authorization
        $this->authorize('view', $video);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($video);
        }

        return $this->successResponse($video, 'Video retrieved successfully', 200);
    }

    /**
     * Update the specified video.
     */
    public function update(Request $request, $id)
    {
        $video = Video::with('course')->findOrFail($id);

        // Check authorization
        $this->authorize('update', $video);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_url' => ['sometimes', 'string'],
            'thumbnail_url' => ['nullable', 'string'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'thumbnail_file' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle thumbnail file upload
        if ($request->hasFile('thumbnail_file')) {
            // Delete old thumbnail if exists
            if ($video->thumbnail_url) {
                // Get original value from database (without accessor modification)
                $oldPath = $video->getOriginal('thumbnail_url');
                Storage::disk('public')->delete($oldPath);
            }
            // Store returns path relative to disk root
            $validated['thumbnail_url'] = $request->file('thumbnail_file')->store('video-thumbnails', 'public');
        }

        $video->update($validated);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            return response()->json($video->fresh());
        }

        return $this->successResponse($video->fresh(), 'Video updated successfully', 200);
    }

    /**
     * Remove the specified video.
     */
    public function destroy($id)
    {
        $video = Video::with('course')->findOrFail($id);

        // Check authorization
        $this->authorize('delete', $video);

        // Delete thumbnail file if exists
        if ($video->thumbnail_url) {
            // Get original value from database (without accessor modification)
            $thumbnailPath = $video->getOriginal('thumbnail_url');
            Storage::disk('public')->delete($thumbnailPath);
        }

        $video->delete();

        return $this->successResponse(null, 'Video deleted successfully', 200);
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
        $request->merge(['id' => $request->id]);
        return $this->update($request, $request->id);
    }

    public function deleteViaPost(DeleteResourceRequest $request)
    {
        return $this->destroy($request->id);
    }
}

