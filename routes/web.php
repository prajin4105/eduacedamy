<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication routes for SPA
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'redirect' => match($user->role) {
                'admin' => '/admin',
                'instructor' => '/instructor',
                'student' => '/dashboard',
                default => '/'
            }
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'The provided credentials do not match our records.',
        'errors' => [
            'email' => ['The provided credentials do not match our records.']
        ]
    ], 422);
});

// Provide a named login route for browser redirects
Route::get('/login', function () {
    return view('app');
})->name('login');

// Handle logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->expectsJson()) {
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    return redirect('/');
})->name('logout');

// Vue.js SPA - serve the main app
Route::get('/', function () {
    return view('app');
})->name('home');

// Catch-all route for Vue.js SPA (must be last, but exclude API routes)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
