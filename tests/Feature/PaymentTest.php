<?php

namespace Tests\Feature;

use App\Models\CouponV2Coupon;
use App\Models\CouponV2Usage;
use App\Models\InvitationJourney;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymobService;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PlansSeeder::class);
    }

    /** @test */
    public function payment_initiation_uses_the_stored_subscription_price_snapshot_sum()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();
        $subscriptionA = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 20109.6,
            'status' => 'pending',
        ]);
        $subscriptionB = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 8493.0,
            'status' => 'pending',
        ]);

        $this->mock(PaymobService::class, function ($mock) {
            $mock->shouldReceive('createIntention')
                ->once()
                ->withArgs(function ($amount) {
                    return $amount === 28603;
                })
                ->andReturn([
                    'client_secret' => 'secret_123',
                    'intention_id' => 'intent_123',
                    'order_id' => 'order_123',
                ]);

            $mock->shouldReceive('getCheckoutUrl')
                ->once()
                ->with('secret_123')
                ->andReturn('https://pay.example/checkout');
        });

        $response = $this->postJson(route('payment.checkout'), [
            'subscription_ids' => [$subscriptionA->id, $subscriptionB->id],
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('payment_url', 'https://pay.example/checkout');

        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'amount' => 28603,
            'payment_status' => 'pending',
        ]);
    }

    /** @test */
    public function payment_success_marks_coupon_v2_usage_as_paid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();
        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-PAID',
            'code_normalized' => 'SYS-PAID',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 20109.6,
            'status' => 'pending',
            'coupon_v2_coupon_id' => $coupon->id,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_ids' => [$subscription->id],
            'amount' => 20109.6,
            'payment_status' => 'pending',
            'payment_method' => 'card',
            'transaction_id' => 'txn_123',
            'order_id' => 'ord_123',
        ]);

        CouponV2Usage::create([
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'payment_id' => null,
            'payment_status' => CouponV2Usage::STATUS_PENDING_PAYMENT,
            'total_before_discount' => 19600,
            'discount_amount' => 1960,
            'total_after_discount' => 17640,
            'vat_amount' => 2469.6,
            'grand_total' => 20109.6,
        ]);

        $response = $this->get(route('payment.success', [
            'order' => $payment->order_id,
            'success' => 'true',
        ]));

        $response->assertRedirect(route('payment.thank-q'));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('coupon_v2_usages', [
            'subscription_id' => $subscription->id,
            'payment_id' => $payment->id,
            'payment_status' => CouponV2Usage::STATUS_PAID,
        ]);

        $this->assertNotNull(CouponV2Usage::where('subscription_id', $subscription->id)->first()->consumed_at);
    }

    /** @test */
    public function failed_payment_does_not_consume_coupon_v2_usage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();
        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-FAIL',
            'code_normalized' => 'SYS-FAIL',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 20109.6,
            'status' => 'pending',
            'coupon_v2_coupon_id' => $coupon->id,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_ids' => [$subscription->id],
            'amount' => 20109.6,
            'payment_status' => 'pending',
            'payment_method' => 'card',
            'transaction_id' => 'txn_456',
            'order_id' => 'ord_456',
        ]);

        CouponV2Usage::create([
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'payment_id' => null,
            'payment_status' => CouponV2Usage::STATUS_PENDING_PAYMENT,
            'total_before_discount' => 19600,
            'discount_amount' => 1960,
            'total_after_discount' => 17640,
            'vat_amount' => 2469.6,
            'grand_total' => 20109.6,
        ]);

        $response = $this->get(route('payment.success', [
            'order' => $payment->order_id,
            'success' => 'false',
        ]));

        $response->assertRedirect(route('payment.thank-q'));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'failed',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('coupon_v2_usages', [
            'subscription_id' => $subscription->id,
            'payment_id' => $payment->id,
            'payment_status' => CouponV2Usage::STATUS_FAILED,
        ]);

        $this->assertNull(CouponV2Usage::where('subscription_id', $subscription->id)->first()->consumed_at);
    }

    /** @test */
    public function payment_success_marks_the_linked_invitation_journey_as_paid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 20109.6,
            'status' => 'pending',
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'payment-journey-token',
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_CHECKOUT_STARTED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => 'INV-PAY',
            'referral_source' => 'direct_link',
            'checkout_started_at' => now(),
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'invitation_journey_id' => $journey->id,
            'subscription_ids' => [$subscription->id],
            'amount' => 20109.6,
            'payment_status' => 'pending',
            'payment_method' => 'card',
            'transaction_id' => 'txn_journey',
            'order_id' => 'ord_journey',
        ]);

        $response = $this->get(route('payment.success', [
            'order' => $payment->order_id,
            'success' => 'true',
        ]));

        $response->assertRedirect(route('payment.thank-q'));

        $this->assertDatabaseHas('invitation_journeys', [
            'id' => $journey->id,
            'payment_id' => $payment->id,
            'state' => InvitationJourney::STATE_PAID,
        ]);
    }

    /** @test */
    public function paymob_callback_returns_a_safe_acknowledgement()
    {
        $response = $this->postJson('/paymob/callback', [
            'order' => 'ord_abc',
            'success' => true,
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'acknowledged',
            ]);
    }
}
