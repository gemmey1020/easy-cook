<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    // 🔹 عرض جميع الاشتراكات الخاصة بالمستخدم مع التفاصيل
    // public function index()
    // {
    //     $subscriptions = Subscription::where('user_id', Auth::id())->with('meals.meal')->get();

    //     return response()->json($subscriptions);
    // }

    // 🔹 إنشاء اشتراك جديد
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'plan_id' => 'required|exists:plans,id',
    //         'weeks_count' => 'required|integer|min:1|max:4',
    //         'days_per_week' => 'required|integer|in:5,6',
    //         'start_date' => 'required|date|after_or_equal:' . now()->addDays(2)->toDateString(),
    //         'subscription_type' => 'required|in:weight_loss,fitness,weight_gain',
    //         'calorie_range' => 'required|string',
    //         'excluded_days' => 'nullable|array',
    //         'price' => 'required|numeric|min:0',
    //     ]);

    //     // جلب تفاصيل الخطة
    //     $plan = Plan::findOrFail($request->plan_id);
    //     $end_date = Carbon::parse($request->start_date)->addWeeks($request->weeks_count)->toDateString();

    //     // إنشاء الاشتراك
    //     $subscription = Subscription::create([
    //         'user_id' => Auth::id(),
    //         'plan_id' => $plan->id,
    //         'subscription_type' => $request->subscription_type,
    //         'calorie_range' => $request->calorie_range,
    //         'weeks_count' => $request->weeks_count,
    //         'days_per_week' => $request->days_per_week,
    //         'excluded_days' => json_encode($request->excluded_days),
    //         'price' => $request->price,
    //         'total_meals' => $request->weeks_count * $request->days_per_week * 5, // 5 وجبات يوميًا
    //         'start_date' => $request->start_date,
    //         'end_date' => $end_date,
    //         'status' => 'pending',
    //     ]);

    //     // 🔹 تعيين الوجبات تلقائيًا للاشتراك
    //     $this->assignMealsToSubscription($subscription);

    //     return response()->json([
    //         'message' => 'Subscription created successfully',
    //         'subscription' => $subscription->load('meals.meal'),
    //     ]);
    // }

    // 🔹 عرض تفاصيل اشتراك معين مع الوجبات
    // public function show($id)
    // {
    //     $subscription = Subscription::where('id', $id)->where('user_id', Auth::id())->with('meals.meal')->firstOrFail();

    //     return response()->json($subscription);
    // }

    // 🔹 إلغاء الاشتراك مع التحقق من حالته
    // public function cancel($id)
    // {
    //     $subscription = Subscription::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

    //     if ($subscription->status === 'active') {
    //         return response()->json(['message' => 'You cannot cancel an active subscription'], 403);
    //     }

    //     $subscription->update(['status' => 'canceled']);

    //     return response()->json(['message' => 'Subscription canceled successfully']);
    // }

    // 🔹 تعيين وجبات للاشتراك بناءً على نوعه
    // private function assignMealsToSubscription(Subscription $subscription)
    // {
    //     $startDate = Carbon::parse($subscription->start_date);
    //     $days = collect(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday']);
    //     $excludedDays = json_decode($subscription->excluded_days, true) ?? [];

    //     $validDays = $days->reject(fn($day) => in_array($day, $excludedDays));

    //     foreach ($validDays as $day) {
    //         for ($week = 0; $week < $subscription->weeks_count; $week++) {
    //             $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2'];
    //             foreach ($mealTypes as $mealType) {
    //                 $meal = Meal::where('calorie_level', $subscription->calorie_range)
    //                     ->where('day', $day)
    //                     ->where('meal_type', $mealType)
    //                     ->inRandomOrder()
    //                     ->first();

    //                 if ($meal) {
    //                     SubscriptionMeal::create([
    //                         'subscription_id' => $subscription->id,
    //                         'meal_id' => $meal->id,
    //                         'meal_date' => $startDate->copy()->addWeeks($week)->format('Y-m-d'),
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    // }

    // public function getSubscriptionsWithMeals()
    // {
    //     $subscriptions = Subscription::where('user_id', Auth::id())
    //         ->with('meals.meal')
    //         ->get();

    //     // $subscriptions = Subscription::where('user_id', 2)
    //     // ->with('meals.meal')
    //     // ->get();
    //     return response()->json($subscriptions);
    // }

    public function getSubscriptionMeals()
    {
        $subscriptions = Subscription::with('user:id,first_name,last_name')->paginate(10);

        return view('dash.subscriptions.index', compact('subscriptions'));
    }

    public function showSubscriptionInDashboard($id)
    {
        $subscription = Subscription::where('id', $id)->with('user:id,first_name,last_name', 'plan:id,name,description', 'dailyMeals.meal')->firstOrFail();

        return view('dash.subscriptions.show', compact('subscription'));
    }

    //     public function getOrderHistory()
    // {
    //     $orders = Subscription::with(['user', 'payment', 'plan'])
    //         ->latest()
    //         ->get()
    //         ->map(function ($subscription) {
    //             $orderId = 'SO-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT);

    //             $userName = $subscription->user?->name ?? 'N/A';

    //             // نحاول نجيب المبلغ من payment أو plan
    //             $amount = $subscription->payment?->amount ?? $subscription->plan?->price ?? 0;

    //             return [
    //                 'order_no' => $orderId,
    //                 'order_date' => $subscription->created_at->format('Y-m-d'),
    //                 'bill_to_name' => $userName,
    //                 'total' => number_format($amount, 2) . ' EGP',
    //             ];
    //         });

    //     return view('orders.history', compact('orders'));
    // }
}
