<div style="font-family: Arial, sans-serif; padding: 20px; color: #333; background: #f9f9f9; border-radius:8px;">
    <h2 style="color:#0c7a7a; text-align:center; margin-top:0;">Payment Received ✅</h2>

    <p>Hi <strong>{{ $user->first_name }}</strong>,</p>

    <p>We’ve received your payment for order <strong>#{{ $payment->order_id }}</strong>.
        Your subscription{{ $subscriptions->count() > 1 ? 's have' : ' has' }} been activated.</p>

    <hr style="margin: 16px 0; border-color:#ddd;">

    <p>We’ve attached your invoice{{ $subscriptions->count() > 1 ? 's' : '' }} as PDF.</p>

    <ul>
        @foreach($subscriptions as $s)
            <li>
                Subscription #{{ $s->id }} — {{ $s->plan->name ?? 'Plan' }}
                ({{ $s->days_per_week }} days/week)
            </li>
        @endforeach
    </ul>

    <p style="margin-top:20px;">Thanks for choosing <strong>Easy Cook</strong>!</p>
    <p style="color:#888; font-size:12px;">If you have any questions, just reply to this email.</p>
</div>
