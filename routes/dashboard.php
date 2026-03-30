<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\Dashboard\CouponV2Controller;
use App\Http\Controllers\Dashboard\DiscountOpsController;
use App\Http\Controllers\Dashboard\InvitationCodeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BRNCController;
use App\Services\Dashboard\DiscountOpsRelationshipQuery;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('dashboard.login');

Route::post('/dashboard/login', [\App\Http\Controllers\AuthController::class, 'postLoginWeb'])->name('dashboard.login.post');

Route::group(['prefix' => 'dashboard', 'middleware' => [\App\Http\Middleware\SystemDashboardMiddleware::class]], function () {
    Route::get('/', function () {
        $discountOpsSummary = config('coupon_v2.admin_enabled')
            ? app(DiscountOpsRelationshipQuery::class)->homeLaunchpadSummary()
            : null;

        return view('dash.dash_home', compact('discountOpsSummary'));
    })->name('dashboard.home');

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('dashboard.logout');

    Route::resource('roles', RoleController::class);

    Route::prefix('sales')->group(function () {
        Route::get('/', [\App\Http\Controllers\SalesController::class, 'index'])->name('sales.index');
    });

    Route::get('/order-details/{id}', [\App\Http\Controllers\SalesController::class, 'orderDetails'])->name('order-details');

    Route::prefix('assessments')->group(function () {
        Route::get('/', [AssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/{id}', [AssessmentController::class, 'show'])->name('assessments.show');
        Route::post('/{id}/update-status', [AssessmentController::class, 'updateStatus'])->name('assessments.updateStatus');
    });

    // مسارات أخصائي التغذية
    Route::prefix('nutrition')->group(function () {
        Route::get('/', [NutritionController::class, 'index'])->name('nutrition.index');
        Route::get('/{id}', [NutritionController::class, 'show'])->name('nutrition.show');
        Route::post('/{id}/update-status', [NutritionController::class, 'updateStatus'])->name('nutrition.updateStatus');
        Route::post('/{id}/send-notes', [NutritionController::class, 'sendNotes'])->name('nutrition.sendNotes');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/{id}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'getSubscriptionMeals'])->name('subscriptions.index');
        Route::get('/{id}', [SubscriptionController::class, 'showSubscriptionInDashboard'])->name('subscriptions.show');
        Route::post('/{id}/delete', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    });

    Route::prefix('brnc')->group(function () {
        Route::view('/', 'dash.brnc.index')->name('brnc.index');
        route::get('/get-data', [BRNCController::class, 'getData'])->name('brnc.getData');
    });

    Route::middleware('coupon-v2-admin')->prefix('coupons-v2')->group(function () {
        Route::get('/', [CouponV2Controller::class, 'index'])->name('coupons-v2.index');
        Route::post('/', [CouponV2Controller::class, 'store'])->name('coupons-v2.store');
        Route::get('/partners', [CouponV2Controller::class, 'partners'])->name('coupons-v2.partners');
        Route::get('/{couponV2}', [CouponV2Controller::class, 'show'])->name('coupons-v2.show');
        Route::put('/{couponV2}', [CouponV2Controller::class, 'update'])->name('coupons-v2.update');
        Route::post('/{couponV2}/toggle-status', [CouponV2Controller::class, 'toggleStatus'])->name('coupons-v2.toggle-status');
    });

    Route::middleware('coupon-v2-admin')->prefix('invitations')->group(function () {
        Route::get('/', [InvitationCodeController::class, 'index'])->name('invitations.index');
        Route::post('/', [InvitationCodeController::class, 'store'])->name('invitations.store');
        Route::get('/partners', [InvitationCodeController::class, 'partners'])->name('invitations.partners');
        Route::get('/{invitation}', [InvitationCodeController::class, 'show'])->name('invitations.show');
        Route::put('/{invitation}', [InvitationCodeController::class, 'update'])->name('invitations.update');
        Route::post('/{invitation}/toggle-status', [InvitationCodeController::class, 'toggleStatus'])->name('invitations.toggle-status');
    });

    Route::middleware('coupon-v2-admin')->prefix('discount-ops')->group(function () {
        Route::get('/', [DiscountOpsController::class, 'index'])->name('discount-ops.index');
        Route::get('/partners', [DiscountOpsController::class, 'partners'])->name('discount-ops.partners');
    });
});
