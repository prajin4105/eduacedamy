<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\Api\ReadResourceRequest;
use App\Http\Requests\Api\DeleteResourceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Check authorization - only admin and instructors can view users list
        $this->authorize('viewAny', User::class);

        $query = User::query();

        // Instructors can only see students
        if (Auth::user()->role === 'instructor') {
            $query->where('role', 'student');
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = min($request->get('per_page', 15), 100);
        $users = $query->paginate($perPage);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($users->items());
        }

        return $this->successResponse($users->items(), 'Users retrieved successfully', 200, [
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request)
    {
        // Check authorization - only admin can create users
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return $this->successResponse($user, 'User created successfully', 201);
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Check authorization
        $this->authorize('view', $user);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('GET')) {
            return response()->json($user);
        }

        return $this->successResponse($user, 'User retrieved successfully', 200);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Check authorization
        $this->authorize('update', $user);

        $validated = $request->validated();
        unset($validated['id']);

        // Students can only update their own profile (not role)
        if (Auth::user()->role === 'student' && Auth::id() === $user->id) {
            unset($validated['role']); // Prevent students from changing their role
        }

        // Only admin can change roles
        if (Auth::user()->role !== 'admin' && isset($validated['role'])) {
            unset($validated['role']);
        }

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // For GET requests (frontend compatibility), return direct data
        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            return response()->json($user->fresh());
        }

        return $this->successResponse($user->fresh(), 'User updated successfully', 200);
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Check authorization - only admin can delete users
        $this->authorize('delete', $user);
        
        $user->delete();

        return $this->successResponse(null, 'User deleted successfully', 200);
    }

    // POST-based CRUD methods for Postman
    public function createViaPost(StoreUserRequest $request)
    {
        return $this->store($request);
    }

    public function readViaPost(ReadResourceRequest $request)
    {
        return $this->show($request->id);
    }

    public function updateViaPost(UpdateUserRequest $request)
    {
        $validated = $request->validated();
        $userId = $validated['id'];
        unset($validated['id']);

        $user = User::findOrFail($userId);
        
        // Check authorization
        $this->authorize('update', $user);

        // Students can only update their own profile (not role)
        if (Auth::user()->role === 'student' && Auth::id() === $user->id) {
            unset($validated['role']); // Prevent students from changing their role
        }

        // Only admin can change roles
        if (Auth::user()->role !== 'admin' && isset($validated['role'])) {
            unset($validated['role']);
        }

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return $this->successResponse($user->fresh(), 'User updated successfully', 200);
    }

    public function deleteViaPost(DeleteResourceRequest $request)
    {
        return $this->destroy($request->id);
    }
}

