<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCouponV2AdminEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('coupon_v2.admin_enabled')) {
            abort(404);
        }

        return $next($request);
    }
}
