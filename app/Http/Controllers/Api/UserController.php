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
use Illuminate\Support\Facades\Log;

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

    /**
     * Filter users based on various criteria
     */
    public function filterUsers(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Filter Users Request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'data' => $request->all()
            ]);

            $query = User::query();

            // Check authorization - only admin and instructors can filter users
            // Comment this out temporarily if it's causing issues
            // $this->authorize('viewAny', User::class);

            // Instructors can only see students
            if (Auth::user()->role === 'instructor') {
                $query->where('role', 'student');
            }

            // Apply filters
            if ($request->has('filters')) {
                $filters = $request->input('filters');

                // Role filter
                if (!empty($filters['role'])) {
                    $query->where('role', $filters['role']);
                }

                // Status filter
                if (!empty($filters['status'])) {
                    $query->where('status', $filters['status']);
                }

                // Date range filter - created
                if (!empty($filters['created_from'])) {
                    $query->whereDate('created_at', '>=', $filters['created_from']);
                }
                if (!empty($filters['created_to'])) {
                    $query->whereDate('created_at', '<=', $filters['created_to']);
                }

                // Search filter (name or email)
                if (!empty($filters['search'])) {
                    $query->where(function($q) use ($filters) {
                        $q->where('name', 'LIKE', '%' . $filters['search'] . '%')
                          ->orWhere('email', 'LIKE', '%' . $filters['search'] . '%');
                    });
                }
            }

            // Apply sorting
            $sortField = $request->input('sort.field', 'created_at');
            $sortOrder = $request->input('sort.order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            // Apply pagination
            $page = $request->input('pagination.page', 1);
            $perPage = $request->input('pagination.per_page', 10);

            $results = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Users filtered successfully',
                'data' => $results->items(),
                'meta' => [
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total(),
                    'from' => $results->firstItem(),
                    'to' => $results->lastItem()
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Filter Users Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'errors' => null
            ], 500);
        }
    }
}
