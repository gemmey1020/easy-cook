<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Subscription;
use App\Models\user_has_roles;

class SalesController extends Controller
{
    public function index()
    {
        $isAccountant = user_has_roles::where([
            'user_id' => auth()->id(),
            'role_id' => Role::ACCOUNTANT_ROLE,
        ])->exists();

        $subscriptions = Subscription::query()
            ->select([
                'id', 'subscription_plan_type', 'plan_id', 'days_per_week',
                'price', 'start_date', 'end_date', 'status', 'user_id'
            ])
            ->when($isAccountant, fn ($q) => $q->where('status', 'active'))
            ->with([
                'plan:id,name',
                'user:id,first_name,last_name,phone',
            ])
            ->paginate(15);

        return view('dash.sales.index', compact('subscriptions'));
    }

    public function orderDetails($id)
    {
        $subscription = Subscription::with(['plan:id,name', 'user:id,first_name,last_name,phone', 'dailySelectedMeals.variant.nutrition'])->findOrFail($id);

        return view('pages.order-details', compact('subscription'));
    }
}
