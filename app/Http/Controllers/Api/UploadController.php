<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Handle file upload
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB max
            'folder' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $folder = $request->input('folder', 'uploads');
        
        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($folder, $filename, 'public');

        return $this->successResponse([
            'path' => $path,
            'url' => asset('storage/' . $path),
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ], 'File uploaded successfully', 201);
    }

    /**
     * Handle multiple file uploads
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => ['required', 'file', 'max:10240'],
            'folder' => ['nullable', 'string', 'max:255'],
        ]);

        $folder = $request->input('folder', 'uploads');
        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($folder, $filename, 'public');

            $uploadedFiles[] = [
                'path' => $path,
                'url' => asset('storage/' . $path),
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
        }

        return $this->successResponse($uploadedFiles, 'Files uploaded successfully', 201);
    }
}

