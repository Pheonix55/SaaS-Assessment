<?php

namespace App\Http\Middleware;

use App\Enums\CompanyStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyId
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $company = $user->company;

        if (! $company) {
            return response()->json([
                'message' => 'Company not associated with user',
            ], 403);
        }

        if ($company->status !== CompanyStatus::ACTIVE) {
            return response()->json([
                'message' => match ($company->status) {
                    CompanyStatus::PENDING => 'Company approval pending',
                    CompanyStatus::REJECTED => 'Company registration rejected',
                    CompanyStatus::SUSPENDED => 'Company account suspended',
                },
                'status' => $company->status->value,
            ], 403);
        }

       

        $request->merge([
            'company_id' => $company->id,
        ]);

        return $next($request);
    }
}
