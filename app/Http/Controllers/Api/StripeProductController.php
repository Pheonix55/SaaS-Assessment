<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use Stripe\{Price, Product, Stripe};
use Stripe\Exception\ApiErrorException;

class StripeProductController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    /**
     * List all plans
     */
    public function index()
    {
        $plans = Plan::orderBy('id', 'desc')->paginate(5);

        return response()->json(['success' => true, 'plans' => $plans]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric', // PKR
            'interval' => 'required|in:month,year',
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);
        try {
            return DB::transaction(function () use ($request) {

                // 1. Create Stripe Product
                $stripeProduct = Product::create([
                    'name' => $request->name,
                ]);

                // 2. Convert PKR → paise
                $amountInPaise = (int) ($request->amount * 100);

                // 3. Create Stripe Price
                $stripePrice = Price::create([
                    'unit_amount' => $amountInPaise,
                    'currency' => 'pkr',
                    'recurring' => ['interval' => $request->interval],
                    'product' => $stripeProduct->id,
                ]);
                // 4. Save in DB
                $plan = Plan::create([
                    'name' => $request->name,
                    'stripe_product_id' => $stripeProduct->id,
                    'price_id' => $stripePrice->id,
                    'price' => $request->amount,
                    'duration' => $request->interval,
                    'currency' => 'pkr',
                ]);
                if ($request->has('features')) {
                    $plan->features()->sync($request->features);
                }

                return response()->json([
                    'success' => true,
                    'plan' => $plan->load('features'),
                ], 201);
            });

        } catch (ApiErrorException $e) {
            // Stripe failed
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: '.$e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            // DB failed
            return response()->json([
                'success' => false,
                'message' => 'Failed to create plan.'.$e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'nullable|numeric', // PKR
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);

        try {
            return DB::transaction(function () use ($request, $plan) {
                // 1. Update product name in Stripe
                Product::update($plan->stripe_product_id, [
                    'name' => $request->name,
                ]);

                // 2. Update name locally
                $plan->update(['name' => $request->name]);

                // 3. If amount changed → create NEW price
                if (
                    $request->filled('amount') &&
                    $request->amount != $plan->amount
                ) {
                    $amountInPaise = (int) ($request->amount * 100);

                    $stripePrice = Price::create([
                        'unit_amount' => $amountInPaise,
                        'currency' => 'pkr',
                        'recurring' => ['interval' => $plan->interval],
                        'product' => $plan->stripe_product_id,
                    ]);

                    $plan->update([
                        'stripe_price_id' => $stripePrice->id,
                        'amount' => $request->amount,
                    ]);
                }
                if ($request->has('features')) {
                    $plan->features()->sync($request->features);
                }

                return response()->json([
                    'success' => true,
                    'plan' => $plan->fresh(),
                ]);
            });

        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: '.$e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update plan.'.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete Stripe product and DB record
     */
    public function destroy(Plan $plan)
    {
        try {
            if ($plan->stripe_product_id) {
                $stripeProduct = Product::retrieve($plan->stripe_product_id);
                $stripeProduct->delete();
            }

            $plan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Plan deleted successfully',
            ]);

        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: '.$e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete plan: '.$e->getMessage(),
            ], 500);
        }
    }
}
