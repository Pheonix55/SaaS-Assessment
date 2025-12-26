<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Subscription;
use App\Enums\CompanyStatus;
use App\Http\Controllers\Controller;
use App\Models\{AuditLog, Company, Feature, Invitation, SubscriptionEvent, Transaction, User};
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $pendingCompanies = Company::where('status', 'pending')->count();
        $totalUsers = User::count();

        $totalRevenue = Transaction::where('status', 'active')->sum('amount');
        $newInvitations = Invitation::where('status', 'pending')->count();
        $subscriptionsCount = Subscription::where('stripe_status', 'active')->count();
        $transactionsCount = Transaction::count();

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
        return DB::transaction(function () use ($id) {

            $company = Company::with('owner')->lockForUpdate()->find($id);

            if (! $company) {
                abort(404, 'Company not found');
            }

            if ($company->status === CompanyStatus::ACTIVE) {
                abort(409, 'Company is already active');
            }

            if (! $company->owner) {
                abort(404, 'Company owner not found');
            }

            app(PermissionRegistrar::class)
                ->setPermissionsTeamId($company->id);

            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
                'company_id' => $company->id,
            ]);
            $adminRole->syncPermissions([
                'manage-users',
                'invite-users',
                'view-dashboard',
            ]);
            if (! $company->owner->hasRole($adminRole)) {
                $company->owner->assignRole($adminRole);
            }

            $company->update([
                'status' => CompanyStatus::ACTIVE,
            ]);

            return response()->json([
                'message' => 'Company approved successfully',
                'company_id' => $company->id,
                'status' => $company->status->value,
            ]);
        });
    }

    public function listApprovals()
    {
        $companies = Company::where('status', CompanyStatus::PENDING)->latest()->paginate(10);

        return response()->json([
            'companies' => $companies,
        ]);
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user', 'company')
            ->latest()
            ->paginate(5);

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }

    public function subscriptionEvents()
    {
        $subscriptions = SubscriptionEvent::with('user', 'company')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
        ]);
    }

    public function getFeatures()
    {
        if (! auth()->user()->can('manage-companies')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $features = Feature::all(); 

        return response()->json([
            'success' => true,
            'features' => $features,
        ]);
    }
}
