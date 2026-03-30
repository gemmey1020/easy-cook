<?php

use App\Helpers\SubscriptionDetails;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoPageController;
use App\Http\Controllers\TipController;
use App\Http\Controllers\UserDashboardController;
use App\Services\SubscriptionPricingService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home-light');
})->name('home');

Route::get('/dark', function () {
    return view('pages.home-dark');
});
Route::get('/light2', function () {
    return view('pages.home-light2');
});
Route::get('/contact', function () {
    return view('pages.contact');
});
Route::get('/contact2', function () {
    return view('pages.contact-v2');
});
Route::get('/about', function () {
    return view('pages.about');
});
Route::get('/about2', function () {
    return view('pages.about-v2');
});
Route::get('/about', function () {
    return view('pages.about');
});

Route::get('/blogs', function () {
    return view('pages.blogs');
});

Route::get('/5-reasons-to-switch-to-a-healthy-meal-delivery-service', function () {
    return view('pages.blog-details-1');
})->name('blog-details-1');

Route::get('/how-easycook-saves-you-time-and-energy-in-the-kitchen', function () {
    return view('pages.blog-details-2');
})->name('blog-details-2');

Route::get('/healthy-eating-made-easy-with-easycook-simplify-your-lifestyle', function () {
    return view('pages.blog-details-3');
})->name('blog-details-3');

// Short URLs - Redirects
Route::get('/blog-details-1', function () {
    return redirect()->route('blog-details-1');
});

Route::get('/blog-details-2', function () {
    return redirect()->route('blog-details-2');
});

Route::get('/blog-details-3', function () {
    return redirect()->route('blog-details-3');
});
//Route::get('/menu-fast', function () {
//    return view('pages.menu-fast');
//});

Route::get('/menu-fast-v2', function () {
    return view('pages.menu-fast-v2');
});
Route::get('/plans', function () {
    $pricingService = app(SubscriptionPricingService::class);
    $threeWeekUiEnabled = $pricingService->threeWeekUiEnabled();

    return view('pages.plans', [
        'plansPricingCatalog' => $pricingService->plansCatalog($threeWeekUiEnabled),
        'threeWeekUiEnabled' => $threeWeekUiEnabled,
        'vatRate' => SubscriptionPricingService::VAT_RATE,
    ]);
})->name('plans');
Route::get('/plan_2', function () {
    $pricingService = app(SubscriptionPricingService::class);
    $threeWeekUiEnabled = $pricingService->threeWeekUiEnabled();

    return view('pages.plan_2', [
        'plansPricingCatalog' => $pricingService->plansCatalog($threeWeekUiEnabled),
        'threeWeekUiEnabled' => $threeWeekUiEnabled,
        'vatRate' => SubscriptionPricingService::VAT_RATE,
    ]);
})->name('plan_2');

Route::get('/menu-rest-v2', function () {
    return view('pages.menu-rest-v2');
});
Route::get('/menu-rest-v2', function () {
    return view('pages.menu-rest-v2');
});

Route::get('/faq', function () {
    return view('pages.faq');
});
Route::get('/privacy', function () {
    return view('pages.privacy');
});
Route::get('/refund', function () {
    return view('pages.refund');
});
Route::get('/terms', function () {
    return view('pages.terms');
});
Route::get('/shipping', function () {
    return view('pages.shipping');
});
Route::get('/how-it-work', function () {
    return view('pages.how-it-work');
});
Route::get('/recipes', function () {
    return view('pages.recipes');
});
Route::get('/recipe-details', function () {
    return view('pages.recipe-details');
});
Route::get('/kids', function () {
    return view('pages.kids');
});

Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('/checkout-page', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
Route::post('/invitation/capture', [InvitationController::class, 'capture'])->middleware('invitation-public')->name('invitation.capture');
Route::post('/invitation/forget', [InvitationController::class, 'forget'])->middleware('invitation-public')->name('invitation.forget');

// Route::get('/complete-profile', [AuthController::class, 'showCompleteProfile'])->name('complete-profile.show')->middleware('auth');

// Route::post('/complete-profile', [AuthController::class, 'completeProfile'])->name('complete-profile.post')->middleware('auth');

Route::post('/payment-initiate', [PaymentController::class, 'initiatePayment'])->name('payment.checkout')->middleware('auth');

// Route::get('/cart', function () {
//     return view('pages.cart');
// })->name('cart');

//Route::get('/shop', function () {
//    return view('pages.shop');
//});

//Route::get('/wishlist', function () {
//    return view('pages.wishlist');
//});

// Route::get('/shop-details', function () {
//     return view('pages.shop-details');
// });

// Route::get('/login', function () {
//     return view('pages.login');
// });
// Route::get('/register', function () {
//     return view('pages.register');
// });
// Route::get('/user-dashboard', function () {
//     return view('pages.user-dashboard');
// });

// Route::get('/login', function () {
//     return view('pages.login');
// });
// Route::get('/register', function () {
//     return view('pages.register');
// });
// Route::get('/user-dashboard', function () {
//     return view('pages.user-dashboard');
// });

Route::get('/easy-tips', function () {
    return view('pages.tips');
});

// Route::get('/tips', [TipController::class, 'index'])->name('tips.index');
Route::post('/tips', [TipController::class, 'store'])->name('tips.store');
Route::get('/tips/search', [TipController::class, 'search'])->name('tips.search');

// Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
Route::get('/recipes/download/{id}', [RecipeController::class, 'download'])->name('recipes.download');

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/food-prepared-meals-in-egypt-fresh-and-ready-to-eat-daily', function () {
    return view('pages.blog-details-4');
})->name('blog-details-4');

Route::get('/healthy-meals-in-egypt-fresh-balanced-and-ready-to-eat', function () {
    return view('pages.blog-details-5');
})->name('blog-details-5');

Route::middleware('auth')->group(function () {

    Route::post('/save-meals', [MealController::class, 'saveMeals']);

    Route::match(['GET', 'POST'], '/user-dashboard', [UserDashboardController::class, 'index'])->name('user-dashboard');

    Route::post('/logoutx', [AuthController::class, 'logout'])->name('logout');

    // *_________________________ User Address _________________________*/

    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    // render partial view
    Route::get('/get_ajax_addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{id}/make-primary', [AddressController::class, 'makePrimary'])->name('addresses.makePrimary');
});

Route::middleware('guest')->group(function () {
    Route::get('/register_new_client', [AuthController::class, 'showRegistrationForm_web'])->name('register_web');
    Route::post('/register_new_client', [AuthController::class, 'register_web'])->name('post_register_web');

    Route::get('/login', [AuthController::class, 'showLoginForm_web'])->name('login_web');
    Route::post('/login', [AuthController::class, 'postLoginWeb'])->name('post_login_web');
});

Route::get('/assessment', function () {
    return view('online_assessments');
})->name('online_assessments');

//  *_________________________ Forgot Password _________________________*/

Route::get('/forgot-password', [ForgetPasswordController::class, 'showForgotForm'])->name('get.password.forget');
Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLink'])->middleware('throttle:1,1')->name('post.password.forget');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('password.update');
Route::middleware('auth')->group(function () {

    Route::post('/save-meals', [MealController::class, 'saveMeals']);

    Route::match(['GET', 'POST'], '/user-dashboard', [UserDashboardController::class, 'index'])->name('user-dashboard');

    Route::get('/subscription/{id}/details', function ($id) {
        $html = SubscriptionDetails::getSubscriptionDetails($id);

        return response()->json(['html' => $html]);
    })->name('subscription.details');

    Route::post('/logoutx', [AuthController::class, 'logout'])->name('logout');

    // *_________________________ User Address _________________________*/

    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    // render partial view
    Route::get('/get_ajax_addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{id}/make-primary', [AddressController::class, 'makePrimary'])->name('addresses.makePrimary');

});

Route::middleware('guest')->group(function () {
    Route::get('/register_new_client', [AuthController::class, 'showRegistrationForm_web'])->name('register_web');
    Route::post('/register_new_client', [AuthController::class, 'register_web'])->name('post_register_web');

    Route::get('/login', [AuthController::class, 'showLoginForm_web'])->name('login_web');
    Route::post('/login', [AuthController::class, 'postLoginWeb'])->name('post_login_web');
});

Route::get('/assessment', function () {
    return view('online_assessments');
})->name('online_assessments');

//  *_________________________ Forgot Password _________________________*/

Route::get('/forgot-password', [ForgetPasswordController::class, 'showForgotForm'])->name('get.password.forget');
Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLink'])->middleware('throttle:1,1')->name('post.password.forget');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('password.update');

/* ___________________________ Dashboard Admin _________________________ */

Route::get('/xxxadmin', function () {
    return view('pages.dashboard-admin.admin');
});

Route::get('/registerx', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/registerx', [AuthController::class, 'register']);

Route::get('/loginx', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/loginx', [AuthController::class, 'login']);

Route::post('/logoutx', [AuthController::class, 'logout'])->name('logout');

// Route::get('/login', function () {
//     return view('pages.login');
// });
// Route::get('/register_new_client', function () {
//     return view('pages.register');
// });

// handdle webhok
Route::post('/paymob/callback', [PaymentController::class, 'handlePaymobCallback']);
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

 Route::get('/payment/thank-q', function () {
     return view('payments.success');
 })->name('payment.thank-q')->middleware(\App\Http\Middleware\PaymobAuthMiddleware::class);

Route::get('check-login', function () {
    if (! auth()->check()) {
        return response()->json([
            'isLoggedIn' => false,
            'customized' => false,
            'standard' => false,
        ]);
    }

    $user = auth()->user();

    $hasCustomized = $user->subscriptions()
        ->where('subscription_plan_type', 'customized')
        ->exists();

    $hasStandard = $user->subscriptions()
        ->where('subscription_plan_type', 'standard')
        ->exists();

    return response()->json([
        'isLoggedIn' => true,
        'haveCustomized' => $hasCustomized,
        'haveStandard' => $hasStandard,
    ]);
})->name('check-login');

Route::post('/apply-coupon', [CouponController::class, 'apply'])->middleware('discount-code-public')->name('coupon.apply');



Route::get('/{service}/{area}', [SeoPageController::class, 'show'])
    ->where('service', 'meal-plans|prepared-meals|nutrition-planning|meal-prep')
    ->where('area', 'maadi|nasr-city|new-cairo|heliopolis|6th-of-october|sheikh-zayed|mokattam')
    ->name('seo.landing');
