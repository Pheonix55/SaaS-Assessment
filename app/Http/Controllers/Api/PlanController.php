<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function getAllPlans()
    {
        $plans = Plan::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $plans,
            'status' => 200
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string|in:monthly,yearly',
            'price_id' => 'required|string',
        ]);
        $plan = Plan::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'price_id' => $request->price_id,
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $plan,
            'status' => 201
        ], 201);
    }
    public function delete($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully'], 200);
    }
}
