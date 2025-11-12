<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Enrollment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = $request->user();
        $plan = Plan::findOrFail($request->plan_id);

        if ($user->hasActiveSubscription()) {
            return response()->json(['message' => 'You already have an active subscription'], 422);
        }

        $startsAt = now();
        $endsAt = match ($plan->interval) {
            'day' => $startsAt->copy()->addDays($plan->interval_count),
            'week' => $startsAt->copy()->addWeeks($plan->interval_count),
            'month' => $startsAt->copy()->addMonths($plan->interval_count),
            'year' => $startsAt->copy()->addYears($plan->interval_count),
            default => $startsAt->copy()->addMonths(1),
        };

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);

        // Auto-enroll the user into all courses included in this plan
        $plan->courses()->pluck('courses.id')->each(function ($courseId) use ($user) {
            // Create or update the enrollment to completed with zero charge
            // Unique constraint on [user_id, course_id] is respected via updateOrCreate
            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                ],
                [
                    'amount_paid' => 0,
                    'status' => 'completed',
                    'enrolled_at' => now(),
                ]
            );
        });

        return response()->json([
            'message' => 'Subscribed successfully',
            'subscription' => $subscription,
        ], 201);

    }
      public function getPopularPlan()
    {
        $popularPlan = DB::table('subscriptions')
            ->select('plan_id', DB::raw('COUNT(*) as subscription_count'))
            ->where('status', 'active') // Only count active subscriptions
            ->groupBy('plan_id')
            ->orderBy('subscription_count', 'DESC')
            ->first();

        if (!$popularPlan) {
            return response()->json([
                'plan_id' => null,
                'subscription_count' => 0
            ]);
        }

        return response()->json([
            'plan_id' => $popularPlan->plan_id,
            'subscription_count' => $popularPlan->subscription_count
        ]);
    }

    public function cancel(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->latest('starts_at')
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found'], 404);
        }

        $subscription->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        return response()->json(['message' => 'Subscription canceled']);
    }

    public function status(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscriptions()->latest('starts_at')->first();

        return response()->json([
            'has_active' => $user->hasActiveSubscription(),
            'subscription' => $subscription,
        ]);
    }

    public function mySubscriptions(Request $request)
    {
        $subs = $request->user()->subscriptions()
            ->with('plan')
            ->orderByDesc('starts_at')
            ->get();
        return response()->json(['data' => $subs]);
    }
}


