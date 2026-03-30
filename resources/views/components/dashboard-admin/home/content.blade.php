@php
    $summary = $discountOpsSummary ?? [
        'active_invitations' => 0,
        'active_coupons' => 0,
        'override_journeys' => 0,
        'invalid_attempts' => 0,
    ];
@endphp

<div class="dashboard-launchpad">
    <div class="dashboard-launchpad__hero">
        <span class="coupon-v2-kicker">Operator Launchpad</span>
        <h1>Welcome {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</h1>
        <p>This dashboard is now focused on real operator work. The primary launch surface for coupon and invitation administration is the unified <strong>Discount Ops</strong> workspace.</p>

        @if(config('coupon_v2.admin_enabled'))
            <div class="dashboard-launchpad__actions">
                <a href="{{ route('discount-ops.index') }}" class="btn btn-primary">Open Discount Ops</a>
                <a href="{{ route('invitations.index') }}" class="btn btn-outline-primary">Invitation Records</a>
                <a href="{{ route('coupons-v2.index') }}" class="btn btn-outline-secondary">Coupon Records</a>
            </div>
        @endif
    </div>

    @if(config('coupon_v2.admin_enabled'))
        <div class="dashboard-launchpad__card">
            <h3>Discount Ops Snapshot</h3>
            <p>Use this quick read when you land on the dashboard, then jump into the full ops workspace for the actual relationship map and attention queues.</p>

            <div class="dashboard-launchpad__stats mt-4">
                <div class="dashboard-launchpad__stat">
                    <span>Active Invitations</span>
                    <strong>{{ $summary['active_invitations'] }}</strong>
                </div>
                <div class="dashboard-launchpad__stat">
                    <span>Active Coupons</span>
                    <strong>{{ $summary['active_coupons'] }}</strong>
                </div>
                <div class="dashboard-launchpad__stat">
                    <span>Override Journeys</span>
                    <strong>{{ $summary['override_journeys'] }}</strong>
                </div>
                <div class="dashboard-launchpad__stat">
                    <span>Invalid Attempts</span>
                    <strong>{{ $summary['invalid_attempts'] }}</strong>
                </div>
            </div>
        </div>
    @endif

    <div class="dashboard-launchpad__grid">
        <div class="dashboard-launchpad__card">
            <h3>Subscriptions</h3>
            <p>Review live and historical subscription records, then jump from Discount Ops into the exact subscription when an operator needs customer-level detail.</p>
            <div class="dashboard-launchpad__actions">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-primary">Open Subscriptions</a>
            </div>
        </div>

        <div class="dashboard-launchpad__card">
            <h3>Users</h3>
            <p>User records remain the authoritative place for partner ownership and operator follow-up when a discount record is linked to a specific person.</p>
            <div class="dashboard-launchpad__actions">
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">Open Users</a>
            </div>
        </div>

        <div class="dashboard-launchpad__card">
            <h3>Sales</h3>
            <p>Use sales and order details as the payment-side drill-down once Discount Ops has identified the exact subscription or journey you need to investigate.</p>
            <div class="dashboard-launchpad__actions">
                <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">Open Sales</a>
            </div>
        </div>
    </div>
</div>
