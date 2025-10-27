<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::query()
            ->where('is_active', true)
            ->withCount(['courses'])
            ->orderBy('price')
            ->get(['id', 'name', 'slug', 'description', 'price', 'currency', 'interval', 'interval_count']);

        return response()->json([
            'data' => $plans,
        ]);
    }

    public function show(string $slug)
    {
        $plan = Plan::where('slug', $slug)
            ->where('is_active', true)
            ->with(['courses' => function($q){
                $q->select('courses.id','title','slug','image','level','duration_in_minutes');
            }])
            ->firstOrFail();

        return response()->json($plan);
    }
}


