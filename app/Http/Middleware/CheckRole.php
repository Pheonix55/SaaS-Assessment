<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($user->company_id);
        if (! $user || ! $user->hasRole($role)) {
            abort(403, 'Unauthorized '.'Only'.' '.$role.' '.'access is allowed.');
        }

        return $next($request);
    }
}
