<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of tournaments.
     */
    public function index(Request $request)
    {
        // Placeholder - implement when Tournament model exists
        return $this->successResponse([], 'Tournaments retrieved successfully', 200);
    }

    /**
     * Store a newly created tournament.
     */
    public function store(Request $request)
    {
        // Placeholder - implement when Tournament model exists
        return $this->errorResponse('Tournament creation not yet implemented', 501);
    }

    /**
     * Display the specified tournament.
     */
    public function show($id)
    {
        // Placeholder - implement when Tournament model exists
        return $this->errorResponse('Tournament retrieval not yet implemented', 501);
    }

    /**
     * Update the specified tournament.
     */
    public function update(Request $request, $id)
    {
        // Placeholder - implement when Tournament model exists
        return $this->errorResponse('Tournament update not yet implemented', 501);
    }

    /**
     * Remove the specified tournament.
     */
    public function destroy($id)
    {
        // Placeholder - implement when Tournament model exists
        return $this->errorResponse('Tournament deletion not yet implemented', 501);
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
        return $this->update($request, $request->id);
    }

    public function deleteViaPost(DeleteResourceRequest $request)
    {
        return $this->destroy($request->id);
    }
}

