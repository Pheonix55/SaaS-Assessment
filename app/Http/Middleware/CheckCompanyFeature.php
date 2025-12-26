<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCompanyFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, string $feature)
    {
        $company = $request->user()->company;

        if (! $company || ! $company->hasFeature($feature)) {
            return response()->json([
                'message' => 'Feature not available on your plan',
            ], 403);
        }

        return $next($request);
    }
}
