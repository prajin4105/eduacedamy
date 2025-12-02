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
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

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

        $this->authorize('view', $course);

        $query = Video::where('course_id', $request->course_id);

        // Students can only see published videos
        if (Auth::user()->role === 'student') {
            $query->where('is_published', true);
        }

        $videos = $query->orderBy('sort_order')->get();

        // For GET requests (frontend compatibility), return direct data
        if ($request->isMethod('GET')) {
            return response()->json($videos);
        }

        return $this->successResponse($videos, 'Videos retrieved successfully', 200);
    }

    /**
     * Store a newly created video.
     * Accepts either a remote video_url string or an uploaded file in 'video_file' form field.
     * Thumbnails via 'thumbnail_file'.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Video::class);

        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string'],
            // video_file is in KB: max: 512000 => 500 MB
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,mkv,webm', 'max:512000'],
            'thumbnail_url' => ['nullable', 'string'],
            'thumbnail_file' => ['nullable', 'image', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            return $this->errorResponse('You can only create videos for your own courses.', 403);
        }

        // Handle thumbnail file upload
        if ($request->hasFile('thumbnail_file')) {
            $validated['thumbnail_url'] = $this->storeUploadedFileToPublicStorage($request->file('thumbnail_file'), 'video-thumbnails');
        }

        // Handle video file upload (preferred)
        if ($request->hasFile('video_file')) {
            $validated['video_url'] = $this->storeUploadedFileToPublicStorage($request->file('video_file'), 'videos');
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

        $this->authorize('view', $video);

        if (request()->isMethod('GET')) {
            return response()->json($video);
        }

        return $this->successResponse($video, 'Video retrieved successfully', 200);
    }

    /**
     * Update the specified video.
     * Supports replacing uploaded file via 'video_file' and/or 'thumbnail_file'.
     */
    public function update(Request $request, $id)
    {
        $video = Video::with('course')->findOrFail($id);

        $this->authorize('update', $video);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,mkv,webm', 'max:512000'],
            'thumbnail_url' => ['nullable', 'string'],
            'thumbnail_file' => ['nullable', 'image', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        // Replace thumbnail if provided
        if ($request->hasFile('thumbnail_file')) {
            if ($video->thumbnail_url) {
                $this->deleteStorageFileReferencedByUrl($video->getOriginal('thumbnail_url'));
            }
            $validated['thumbnail_url'] = $this->storeUploadedFileToPublicStorage($request->file('thumbnail_file'), 'video-thumbnails');
        }

        // Replace video file if provided
        if ($request->hasFile('video_file')) {
            if ($video->video_url) {
                $this->deleteStorageFileReferencedByUrl($video->getOriginal('video_url'));
            }
            $validated['video_url'] = $this->storeUploadedFileToPublicStorage($request->file('video_file'), 'videos');
        }

        $video->update($validated);

        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
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

        $this->authorize('delete', $video);

        if ($video->thumbnail_url) {
            $this->deleteStorageFileReferencedByUrl($video->getOriginal('thumbnail_url'));
        }

        if ($video->video_url) {
            $this->deleteStorageFileReferencedByUrl($video->getOriginal('video_url'));
        }

        $video->delete();

        return $this->successResponse(null, 'Video deleted successfully', 200);
    }

    /**
     * POST wrappers (for Postman / clients that only send POST)
     */
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

    /* ----------------------------------------------------------------
     | Helper functions
     |-----------------------------------------------------------------
     | These ensure we store files under storage/app/public/... and return
     | a URL that is directly usable by frontends: '/storage/...'.
     | Make sure you ran: php artisan storage:link
     ----------------------------------------------------------------*/

    /**
     * Store uploaded file to storage/app/public/{subdir} and return a public URL string.
     * Returns relative path like '/storage/videos/xyz.mp4' (NOT full URL)
     *
     * @param UploadedFile $file
     * @param string $subdir
     * @return string Relative URL starting with '/storage/...'
     */
    protected function storeUploadedFileToPublicStorage(UploadedFile $file, string $subdir): string
    {
        // Store returns path like 'videos/abc123.mp4'
        $path = $file->store($subdir, 'public');

        // Return RELATIVE URL only, not full domain URL
        return $path;
    }

    /**
     * Delete a file stored on the 'public' disk referenced by either:
     *  - storage URL '/storage/xxx'
     *  - storage path 'videos/xxx'
     *  - full URL 'https://yourdomain/storage/...'
     *
     * This does best-effort deletion and returns bool.
     *
     * @param string|null $reference
     * @return bool
     */
    protected function deleteStorageFileReferencedByUrl(?string $reference): bool
    {
        if (empty($reference)) {
            return false;
        }

        // If full URL, strip domain
        $parsed = parse_url($reference);
        if ($parsed !== false && isset($parsed['path'])) {
            $path = $parsed['path'];
        } else {
            $path = $reference;
        }

        // Normalize: remove leading '/storage/' if present to get storage relative path
        if (Str::startsWith($path, '/storage/')) {
            $storagePath = ltrim(substr($path, strlen('/storage/')), '/');
            return Storage::disk('public')->delete($storagePath);
        }

        // If it's already a storage path like 'videos/xxx.mp4'
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        // If it looks like an absolute public path (e.g., '/videos/...'), attempt to remove leading slash
        if (Str::startsWith($path, '/')) {
            $maybe = ltrim($path, '/');
            if (Storage::disk('public')->exists($maybe)) {
                return Storage::disk('public')->delete($maybe);
            }
        }

        // Nothing matched
        return false;
    }
}
