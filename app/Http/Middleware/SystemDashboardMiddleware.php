<?php

namespace App\Http\Middleware;

use App\Models\user_has_roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemDashboardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $roles = [1, 2, 3, 4];

        if(Auth()->check() && user_has_roles::where('user_id', Auth()->user()->id)->whereIn('role_id', $roles)->exists()) {
            return $next($request);
        }
        return redirect()->route('dashboard.login')->with('error', 'You do not have permission to access this page.');
    }
}
