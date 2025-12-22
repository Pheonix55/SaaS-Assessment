<?php

namespace App\Http\Controllers\Api;

use App\Enums\CompanyStatus;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

class SuperAdminController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $pendingCompanies = Company::where('status', 'pending')->count();
        $totalUsers = User::count();

        $totalRevenue = Transaction::sum('amount');
        $newInvitations = Invitation::where('status', 'pending')->count();
        $subscriptionsCount = Subscription::count();
        $transactionsCount = Transaction::count();

        // Progress Bar Section: Example plan usage or onboarding
        // $planUsage = Company::select('plan', \DB::raw('COUNT(*) as count'))
        //     ->groupBy('plan')
        //     ->get()
        //     ->map(function ($item) use ($totalCompanies) {
        //         return [
        //             'name' => $item->plan ?? 'Unknown',
        //             'percentage' => round(($item->count / max($totalCompanies, 1)) * 100),
        //         ];
        //     });

        $todayTasks = [
            '5 companies registered today',
            '10 new invitations sent',
        ];

        $recentUpdates = [
            'Company XYZ upgraded subscription',
            'Company ABC user John accepted invitation',
        ];

        $companies = Company::with(['users', 'invitations', 'subscriptions', 'transactions'])
            ->limit(5)
            ->get();

        return response()->json([
            'total_companies' => $totalCompanies,
            'active_companies' => $activeCompanies,
            'pending_companies' => $pendingCompanies,
            'total_users' => $totalUsers,
            'total_revenue' => $totalRevenue,
            'new_invitations' => $newInvitations,
            'subscriptions_count' => $subscriptionsCount,
            'transactions_count' => $transactionsCount,
            // 'plan_usage' => $planUsage,
            'today_tasks' => $todayTasks,
            'recent_updates' => $recentUpdates,
            'companies' => $companies,
        ]);
    }

    public function approveCompany(int $id)
    {
        $company = Company::find($id);

        if (! $company) {
            return response()->json([
                'message' => 'Company not found',
            ], 404);
        }
        if ($company->status === CompanyStatus::ACTIVE) {
            return response()->json([
                'message' => 'Company is already active',
            ], 409);
        }

        $company->status = CompanyStatus::ACTIVE;
        $company->save();

        return response()->json([
            'message' => 'Company approved successfully',
            'company_id' => $company->id,
            'status' => $company->status->value,
        ]);
    }

    public function listApprovals()
    {
        $companies = Company::where('status', CompanyStatus::PENDING)->latest()->paginate(10);
        // dd($companies);

        return response()->json([
            'companies' => $companies,
        ]);
    }
}
