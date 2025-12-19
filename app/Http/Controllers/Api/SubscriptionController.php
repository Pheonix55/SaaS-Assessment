<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\{Invoice, Subscription};
use App\Http\Controllers\Controller;
use App\Models\{Company, Plan, SubscriptionEvent, Transaction, User};
use Exception;

class SubscriptionController extends Controller
{
    // public function getSubscriptionInfo($id)
    // {
    //     $user = User::find($id);

    //     $info = $user->company()->with('subscriptions.plan')->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $info,
    //         'status' => 200,
    //     ]);
    // }

    public function getActiveSubscriptionInfo($id)
    {
        $company = Company::find($id);

        if (! $company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ], 404);
        }

        $subscription = Subscription::where('company_id', $company->id)
            ->where('stripe_status', 'active')
            ->first();

        return response()->json([
            'success' => true,
            'data' => $subscription,
        ]);
    }

    // public function subscribe(Request $request)
    // {
    //     $request->validate([
    //         'plan_id' => 'required|exists:plans,id',
    //         'payment_method' => 'required|string',
    //     ]);

    //     $company = $request->user()->company;
    //     $plan = Plan::findOrFail($request->plan_id);

    //     if (! $company->hasStripeId()) {
    //         $company->createAsStripeCustomer();
    //     }

    //     $activeSubscription = $company->subscriptions()
    //         ->whereIn('stripe_status', ['active', 'trialing'])
    //         ->latest()
    //         ->first();

    //     if ($activeSubscription) {
    //         return response()->json([
    //             'message' => 'Company already has an active subscription',
    //         ], 400);
    //     }
    //     try {
    //         $planName = $plan->name.' '.ucfirst($plan->duration);

    //         $subscription = $company->newSubscription($planName, $plan->price_id)->create($request->payment_method);
    //         $company->plan_id = $plan->id;
    //         $company->save();
    //         \Log::info($subscription->asStripeSubscription());
    //     } catch (Exception $e) {
    //         dd('try catch ', $e);

    //     }

    //     return response()->json([
    //         'message' => 'Subscription created successfully',
    //         'subscription' => $subscription,
    //     ]);
    // }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method' => 'required|string',
        ]);
        $company = $request->user()->company;
        // dd($company->activeSubscription());
        if ($company->activeSubscription()) {
            return response()->json([
                'message' => 'Subscription already active',
                'error' => 'subscription is already active',
            ], 422);
        }
        $plan = Plan::findOrFail($request->plan_id);

        DB::beginTransaction();

        try {
            // Ensure Stripe customer exists
            if (! $company->hasStripeId()) {
                $company->createAsStripeCustomer();

            }

            $stripeCustomerId = $company->stripe_id;
            $planName = $plan->name.' '.ucfirst($plan->duration);

            $company->addPaymentMethod($request->payment_method);
            $company->updateDefaultPaymentMethod($request->payment_method);

            // Create subscription
            $subscription = $company->newSubscription($planName, $plan->price_id)
                ->create();

            // confirming if stripe requires sca or not
            $paymentIntent = $subscription->latestInvoice()->payment_intent;
            // dd($subscription->latestInvoice());
            if ($paymentIntent && $paymentIntent->status === 'requires_action') {
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ], 201);
            }
            $company->plan_id = $plan->id;
            $subscriptionItem = $subscription->items->first();
            $unitAmount = $subscriptionItem?->price?->unit_amount ?? $plan->price * 100;
            $currency = $subscriptionItem?->price?->currency ?? 'usd';

            // --- Generate temporary invoice download URL ---
            // $invoice = $subscription->latestInvoice();
            // $invoiceUrl = route('download.invoice', ['invoiceId' => $invoice->id, 'plan_id' => $plan->id]);
            $invoice = $subscription->latestInvoice();

            $invoiceUrl = $invoice->invoice_pdf;

            // dd($invoiceUrl);
            $transaction = Transaction::create([
                'company_id' => $company->id,
                'subscription_id' => $subscription->stripe_id,
                'stripe_customer_id' => $stripeCustomerId,
                'amount' => $unitAmount / 100,
                'currency' => $currency,
                'status' => $subscription->stripe_status,
                'payment_method' => $request->payment_method,
                'temp_invoice_url' => $invoiceUrl,
            ]);

            SubscriptionEvent::create([
                'company_id' => $company->id,
                'subscription_id' => $subscription->id,
                'user_id' => $request->user()->id,
                'type' => 'subscription_created',
                'payload' => [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'duration' => $plan->duration,
                    'amount' => $unitAmount / 100,
                    'currency' => $currency,
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $transaction->id ?? null,
                ],
            ]);
            $company->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($subscription)) {
                try {
                    $subscription->cancelNow();
                } catch (\Exception $stripeEx) {
                    \Log::error('Stripe cancellation failed: '.$stripeEx->getMessage());
                }
            }

            \Log::error('Subscription Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Subscription failed',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Subscription created successfully',
            'subscription' => $subscription,
            'invoice_download_url' => $invoiceUrl,
        ]);
    }

    public function cancelSubscription(Request $request, $companyId)
    {
        try {
            $company = Company::findOrFail($companyId);

            $activeSubscription = $company->subscriptions()
                ->whereIn('stripe_status', ['active', 'trialing'])
                ->latest()
                ->first();
            // $subscription = $activeSubscription;
            if (! $activeSubscription) {
                return response()->json(['message' => 'No active subscription found'], 404);
            }

            // $activeSubscription->cancel();
            $activeSubscription->cancelNow();
            $company->plan_id = null;
            $company->save();
            SubscriptionEvent::create([
                'company_id' => $company->id,
                'subscription_id' => $activeSubscription->id,
                'user_id' => $request->user()->id ?? null,
                'type' => 'subscription_cancelled',
                'payload' => [
                    'reason' => 'user requested',
                    'previous_status' => $activeSubscription->stripe_status,
                ],
            ]);

            return response()->json(['message' => 'Subscription cancelled successfully'], 200);
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function getTransactions(Request $request)
    {
        $company_id = $request->user()->company_id;
        if (! $company_id) {
            return response()->json([
                'success' => false,
                'message' => 'company id doesnt exist',
            ], 403
            );
        }

        return response()->json([
            'success' => true,
            'data' => Transaction::with('company')->where('company_id', $company_id)->get(),
        ], 200
        );
    }

    public function download(Request $request, $plan_id)
    {
        // $company = $request->user()->company;
        // $planName = Plan::find($plan_id)->first('name');

        dd($request->query('plan_id'), $request->all());

        return $company->downloadInvoice($invoiceId, [
            'vendor' => config('app.name'),
            'product' => $planName,
        ]);
    }

    public function getSubscriptionEvents(Request $request)
    {
        $company = $request->user()->company;

        if (! $company) {
            return response()->json([
                'message' => 'User not authorized',
            ], 401);
        }

        $subEvents = $company->subscriptionEvents()->latest()->get();
        $data = $subEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'type' => $event->type,
                // 'payload' => $event->payload,
                'created_at' => $event->created_at->diffForHumans(),
                'invoice_url' => $event->temp_invoice_url ?? null,
            ];
        });

        return response()->json($data);
    }
}
