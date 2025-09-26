<?php
namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->wishlist()->get();
    }

    public function store(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $request->user()->wishlist()->syncWithoutDetaching([$course->id]);

        return response()->json(['success' => true, 'message' => 'Added to wishlist']);
    }

    public function destroy(Request $request, $courseId)
    {
        $request->user()->wishlist()->detach($courseId);

        return response()->json(['success' => true, 'message' => 'Removed from wishlist']);
    }
}
