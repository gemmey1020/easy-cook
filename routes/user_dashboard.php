<?php

use App\Http\Controllers\UserDashboardController;
use App\Models\Address;
use App\Models\Subscription;
use App\Services\SubscriptionPricingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::middleware(['auth'])->group(function () {
    // 'user-dashboard.
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update/{id}', [UserDashboardController::class, 'userProfileUpdate'])->name('profile.update');
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('orders');
    Route::get('/subscriptions', [UserDashboardController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/meals-tracking', [UserDashboardController::class, 'mealsTracking'])->name('meals.tracking');
    Route::get('/macro-nutrients', [UserDashboardController::class, 'macronutrients'])->name('macronutrients');
    Route::get('/assessment', [UserDashboardController::class, 'assessment'])->name('assessment');

    Route::get('/subscription-invoice/{id}', function (Request $request, $id) {
        $userId = $request->query('userId') ?? Auth::id();

        $subscription = Subscription::with('coupon', 'couponV2', 'couponV2Usage', 'plan', 'user.addresses')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
        $pricing = app(SubscriptionPricingService::class)->calculateForStoredSubscription($subscription);

        $address = $subscription->user->addresses()->primary()->first();

        $pdf = Pdf::loadView('invoices.subscription', [
            'subscription'          => $subscription,
            'address'               => $address,

            // pricing breakdown for the Blade
            'sub_total'             => $pricing['total_before_discount'],
            'discount'              => $pricing['discount_value'],
            'discount_label'        => $pricing['discount_label'],
            'discount_amount'       => $pricing['discount_amount'],
            'after_discount'        => $pricing['total_after_discount'],
            'vat_rate'              => $pricing['vat_rate'],
            'vat_amount'            => $pricing['vat_amount'],
            'grand_total'           => $pricing['grand_total'],

            // optional (if you display list price anywhere)
            'list_with_vat'         => $pricing['list_with_vat'],
        ])->setPaper('a4', 'portrait');

        $fileName = 'invoice-'.$subscription->id.'.pdf';
        return $pdf->download($fileName);
    })->name('subscription-invoice');

    // Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/meals/data/{subscription}', [UserDashboardController::class, 'getMealsData'])->name('meals.data');

});
