<?php

use App\Http\Controllers\BRNCController;
// use Laravel\Passport\Http\Controllers\AccessTokenController;
// use Laravel\Passport\Http\Controllers\AuthorizationController;
// use Laravel\Passport\Http\Controllers\TransientTokenController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PaymentController;
// use Laravel\Passport\Http\Controllers\AccessTokenController;
// use Laravel\Passport\Http\Controllers\AuthorizationController;
// use Laravel\Passport\Http\Controllers\TransientTokenController;
// use Laravel\Passport\Http\Controllers\AccessTokenController;
// use Laravel\Passport\Http\Controllers\AuthorizationController;
// use Laravel\Passport\Http\Controllers\TransientTokenController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Subscription;
use App\Models\User;
// use App\Http\Controllers\NutritionController;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
// use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'throttle'], function () {
//     Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
//     Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);
//     Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
// });
// Route::group(['middleware' => 'throttle'], function () {
//     Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
//     Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);
//     Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
// });

// Route::middleware('auth:api')->group(function () {
//     // Route::get('/assessments', 'NutritionController@index')->middleware('scope:nutrition-specialist');
//     Route::get('/statistics', 'AdminController@index')->middleware('scope:admin');
//     Route::get('/financials', 'AccountantController@index')->middleware('scope:accountant');
// });
// Route::middleware('auth:api')->group(function () {
//     // Route::get('/assessments', 'NutritionController@index')->middleware('scope:nutrition-specialist');
//     Route::get('/statistics', 'AdminController@index')->middleware('scope:admin');
//     Route::get('/financials', 'AccountantController@index')->middleware('scope:accountant');
// });

//     Route::middleware('auth')->get('/user/subscriptions', function () {
//         return Auth::user()->subscriptions()->with('dailySelectedMeals')->get();
//     });
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::middleware(['auth:sanctum'])->group(function () {

//         // Route::middleware(['auth:sanctum'])->group(function () {
//         // إدارة الاشتراكات
//         // Route::get('/subscriptions', [SubscriptionController::class, 'index']);
//         Route::post('/subscriptions', [SubscriptionController::class, 'store']);
//         Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']);
//         Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel']);

//         // إدارة الوجبات
//         // Route::get('/meals', [MealController::class, 'index']);
//         // Route::post('/meals', [MealController::class, 'store']);
//         // Route::get('/meals/{id}', [MealController::class, 'show']);
//         // Route::delete('/meals/{id}', [MealController::class, 'destroy']);
//     });
// ✅ إدارة المدفوعات عبر API
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/payments', [PaymentController::class, 'index']); // عرض كل المدفوعات للمستخدم
//     Route::get('/payments/{id}', [PaymentController::class, 'show']); // تفاصيل دفع معين
//     //    Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']); // بدء الدفع

//     // ✅ API لاسترجاع وجبات الاشتراك الحالي
//     Route::get('/subscriptions/{id}/meals', function ($id) {
//         $subscription = Subscription::findOrFail($id);

//         return response()->json($subscription->meals);
//     });

// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);
// });

// Route::get('/subscriptions', [SubscriptionController::class, 'getSubscriptionsWithMeals']);

// Route::post('/sanctum/token', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (! $user || ! Hash::check($request->password, $user->password)) {
//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }

//     return response()->json([
//         'token' => $user->createToken('payment-token')->plainTextToken,
//     ]);
//     Route::get('/meals', [MealController::class, 'index']);
//     Route::post('/meals', [MealController::class, 'store']);
//     Route::get('/meals/{id}', [MealController::class, 'show']);
//     Route::delete('/meals/{id}', [MealController::class, 'destroy']);

//     // إدارة المدفوعات
//     Route::post('/payments/process', [PaymentController::class, 'processPayment']);

//     Route::get('/payments', [PaymentController::class, 'index']);
//     Route::get('/payments/{id}', [PaymentController::class, 'show']);

//     Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);

// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);
// });

// Route::post('/sanctum/token', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (! $user || ! Hash::check($request->password, $user->password)) {
//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }

//     return response()->json([
//         'token' => $user->createToken('payment-token')->plainTextToken,
//     ]);

// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);
// });

// Route::post('/sanctum/token', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (! $user || ! Hash::check($request->password, $user->password)) {
//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }

//     return response()->json([
//         'token' => $user->createToken('payment-token')->plainTextToken,
//     ]);
// });
// });
Route::post('/contact-us', [BRNCController::class, 'contact'])->name('contact-us');
Route::post('/track-click', [BRNCController::class, 'trackClick']);
