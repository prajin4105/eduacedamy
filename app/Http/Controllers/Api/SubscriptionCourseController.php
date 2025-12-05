<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class SubscriptionCourseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $courses = Course::with(['instructor:id,name', 'categories:id,name,slug'])
            ->where('is_published', true)
            ->where('approval_status', 'approved')
            ->whereHas('plans', function ($q) use ($user) {
                $active = $user->activeSubscription()->first();
                if ($active) {
                    $q->where('plans.id', $active->plan_id);
                } else {
                    // If no active subscription, return none
                    $q->whereRaw('1 = 0');
                }
            })
            ->paginate(min((int)$request->get('per_page', 12), 50));

        return response()->json($courses);
    }
}


