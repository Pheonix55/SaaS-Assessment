<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Plan, User};

class SubscriptionController extends Controller
{

    public function getSubscriptionInfo($id)
    {
        $user = User::find($id);
        $info = $user->company()->with('subscriptions.plan')->get();
        return response()->json([
            'success' => true,
            'data' => $info,
            'status' => 200
        ]);
    }
     public function getActiveSubscriptionInfo($id)
    {
        $user = User::find($id);
        $info = $user->company()->with(['subscriptions' => function ($query) {
            $query->where('status', 'active');
        }, 'subscriptions.plan'])->get();
        return response()->json([
            'success' => true,
            'data' => $info,
            'status' => 200
        ]);
    }
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);
        $plan = Plan::find($request->plan_id);
        if (!$plan) {
            return response()->json(['message' => 'Invalid plan selected'], 400);
        }

        $company = $request->user()->company;
        $activeSubscription = $company->subscriptions()->where('status', 'active')->first();
        // dd($plan, $company, $activeSubscription);
        if ($activeSubscription) {
            return response()->json(['message' => 'Company already has an active subscription'], 400);
        }

        $subscription = $company->subscriptions()->create([
            'plan_id' => $request->plan_id,
            'start_date' => now(),
            'end_date' => $plan->duration = 'monthly' ? now()->addMonth() : now()->addYear(),
            'status' => 'active',
        ]);

        return response()->json(['message' => 'Subscription created successfully', 'data' => $subscription], 201);
    }
    public function cancelSubscription($id)
    {
        $company = User::find($id)->company;
        $activeSubscription = $company->subscriptions()->where('status', 'active')->first();
        if (!$activeSubscription) {
            return response()->json(['message' => 'No active subscription found'], 404);
        }
        $activeSubscription->status = 'cancelled';
        $activeSubscription->save();
        return response()->json(['message' => 'Subscription cancelled successfully'], 200);
    }
}
