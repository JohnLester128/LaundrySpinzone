<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = $request->user('admin');

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        if (!empty($roles) && !$admin->hasRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action. This area is restricted.'], 403);
            }

            $msg = in_array('super_admin', $roles, true)
                ? 'Access restricted: Only the Store Owner can access that section.'
                : 'Access restricted: Store Owners have read-only access to service requests. Only Branch Managers can perform this action.';

            return redirect()->route('admin.dashboard')->with('error', $msg);
        }

        return $next($request);
    }
}
