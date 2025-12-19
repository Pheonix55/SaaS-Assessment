<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

class DashboardController extends Controller
{
    public function getPlansSubscriptions(Request $request)
    {
        $plans = Plan::all();

        $company = $request->user()->company;

        if (! $company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ], 404);
        }

        $subscription = Subscription::where('company_id', $company->id)
            ->where('stripe_status', 'active')
            ->first();
        $currentPlan = Plan::where('id', $company->plan_id)->first();
        // dd($company, $subscription, $currentPlan);

        return response()->json([
            'data' => [
                'plans' => $plans,
                'subscription' => $subscription,
                'currentPlan' => $currentPlan,
            ],
        ]);

    }
}
