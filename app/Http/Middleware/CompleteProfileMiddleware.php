<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompleteProfileMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->hasCompletedProfile()) {
            session(['subscription_ids' => $request->query('subscriptions', [])]);

            return redirect()->route('complete-profile.show');
        }

        return $next($request);
    }
}
