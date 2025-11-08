<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            // Check by exact role name
            if ($user->hasRole($role)) {
                return $next($request);
            }

            // Check by role level (e.g., 'level:7' means level >= 7)
            if (str_starts_with($role, 'level:')) {
                $requiredLevel = (int) substr($role, 6);
                if ($user->hasMinLevel($requiredLevel)) {
                    return $next($request);
                }
            }

            // Check by role type (admin, manager, user)
            if ($role === 'admin' && $user->isAdmin()) {
                return $next($request);
            }
            if ($role === 'manager' && ($user->isManager() || $user->isAdmin() || ($user->role && $user->role->level >= 7))) {
                return $next($request);
            }
            if ($role === 'superadmin' && $user->isSuperAdmin()) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
