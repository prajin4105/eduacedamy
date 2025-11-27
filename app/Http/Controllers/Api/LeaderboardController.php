<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of leaderboards.
     */
    public function index(Request $request)
    {
        // Placeholder - implement when Leaderboard model exists
        return $this->successResponse([], 'Leaderboards retrieved successfully', 200);
    }

    /**
     * Store a newly created leaderboard.
     */
    public function store(Request $request)
    {
        // Placeholder - implement when Leaderboard model exists
        return $this->errorResponse('Leaderboard creation not yet implemented', 501);
    }

    /**
     * Display the specified leaderboard.
     */
    public function show($id)
    {
        // Placeholder - implement when Leaderboard model exists
        return $this->errorResponse('Leaderboard retrieval not yet implemented', 501);
    }

    /**
     * Update the specified leaderboard.
     */
    public function update(Request $request, $id)
    {
        // Placeholder - implement when Leaderboard model exists
        return $this->errorResponse('Leaderboard update not yet implemented', 501);
    }

    /**
     * Remove the specified leaderboard.
     */
    public function destroy($id)
    {
        // Placeholder - implement when Leaderboard model exists
        return $this->errorResponse('Leaderboard deletion not yet implemented', 501);
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

