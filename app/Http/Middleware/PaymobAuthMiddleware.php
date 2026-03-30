<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PaymobAuthMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $orderId = session('order_id');

        \Log::info('PaymobAuthMiddleware order ID', [
            'order_id' => $orderId,
        ]);
        if (Auth::check() && Str::contains(url()->previous(), 'payment-success')) {
            // Check if the order belongs to this user
            $payment = \App\Models\Payment::where('order_id', $orderId)
                ->where('user_id', Auth::id())
                ->first();

            if ($payment) {
                // 🚫 Deny if the user is trying to access their own payment success from payment-success URL
                return $next($request);

            }
        }

        // Not logged in or invalid referer → redirect to login
        return redirect()->route('login_web')->with('error', 'u cannot access this page directly. Please log in first.');
    }

}
