<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class SetCompanyContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user && $user->company_id) {
            app(PermissionRegistrar::class)
                ->setPermissionsTeamId($user->company_id);
        }
        dd(getPermissionsTeamId());

        return $next($request);
    }
}
