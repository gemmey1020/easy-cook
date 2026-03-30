<?php

namespace Tests\Feature;

use App\Models\CouponV2Coupon;
use App\Models\CouponV2Usage;
use App\Models\InvitationCode;
use App\Models\InvitationJourney;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use App\Models\user_has_roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountOpsDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('coupon_v2.admin_enabled', true);
    }

    /** @test */
    public function discount_ops_overview_renders_relationship_metrics_and_attention_groups()
    {
        $admin = $this->makeAdminUser();

        $nativeInvitation = $this->makeInvitation('STALE-INV', [
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
        ]);

        InvitationJourney::create([
            'journey_token' => 'stale-journey-token',
            'invitation_code_id' => $nativeInvitation->id,
            'state' => InvitationJourney::STATE_CAPTURED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $nativeInvitation->code,
            'captured_at' => now()->subDays(5),
            'last_seen_at' => now()->subDays(5),
        ]);

        InvitationJourney::create([
            'journey_token' => 'invalid-journey-token',
            'invitation_code_id' => $nativeInvitation->id,
            'state' => InvitationJourney::STATE_INVALID_CODE,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $nativeInvitation->code,
            'last_invalid_code' => 'BAD-CODE',
            'invalid_reason' => 'not_found',
            'invalid_recorded_at' => now()->subDay(),
            'last_seen_at' => now()->subDay(),
        ]);

        $backingCoupon = $this->makeCoupon('BACK-COUPON');
        $this->makeInvitation('BACKED-INV', [
            'default_coupon_v2_coupon_id' => $backingCoupon->id,
            'discount_type' => null,
            'discount_value' => null,
        ]);

        $expiredCoupon = $this->makeCoupon('COUPON-OLD', [
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($admin)->get(route('discount-ops.index'));

        $response->assertOk()
            ->assertSee('Discount Ops', false)
            ->assertSee('System Health', false)
            ->assertSee('Relationship Health', false)
            ->assertSee('Native Invitation Discounts', false)
            ->assertSee('Invitations Backed by Coupon V2', false)
            ->assertSee('STALE-INV', false)
            ->assertSee('COUPON-OLD', false);
    }

    /** @test */
    public function dashboard_home_renders_discount_ops_launchpad_without_legacy_chart_script()
    {
        $admin = $this->makeAdminUser();

        $response = $this->actingAs($admin)->get(route('dashboard.home'));

        $response->assertOk()
            ->assertSee('Open Discount Ops', false)
            ->assertSee('Discount Ops Snapshot', false)
            ->assertDontSee('cdn.jsdelivr.net/npm/chart.js');
    }

    /** @test */
    public function discount_ops_overview_attention_cards_surface_shared_actions_for_partner_owned_records()
    {
        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Captain',
            'last_name' => 'Gym',
        ]);

        $this->makeInvitation('DRIFT-INV', [
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'expires_at' => now()->subDay(),
        ]);

        $this->makeCoupon('DRIFT-COUPON', [
            'owner_type' => CouponV2Coupon::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($admin)->get(route('discount-ops.index'));

        $response->assertOk()
            ->assertSee('Copy Code', false)
            ->assertSee('Deactivate', false)
            ->assertSee('Suspend', false)
            ->assertSee('Open Context', false)
            ->assertSee(route('invitations.index', ['owner_user_id' => $partner->id]), false)
            ->assertSee(route('coupons-v2.index', ['owner_user_id' => $partner->id]), false);
    }

    /** @test */
    public function invitation_and_coupon_indices_surface_shared_action_language()
    {
        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Mariam',
            'last_name' => 'Ops',
        ]);

        $invitation = $this->makeInvitation('INDEX-INV', [
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
        ]);

        $coupon = $this->makeCoupon('INDEX-COUPON', [
            'owner_type' => CouponV2Coupon::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
        ]);

        $this->actingAs($admin)
            ->get(route('invitations.index'))
            ->assertOk()
            ->assertSee('Copy Code', false)
            ->assertSee('Open Context', false)
            ->assertSee(route('invitations.show', $invitation), false)
            ->assertSee(route('invitations.index', ['owner_user_id' => $partner->id]), false);

        $this->actingAs($admin)
            ->get(route('coupons-v2.index'))
            ->assertOk()
            ->assertSee('Copy Code', false)
            ->assertSee('Open Context', false)
            ->assertSee(route('coupons-v2.show', $coupon), false)
            ->assertSee(route('coupons-v2.index', ['owner_user_id' => $partner->id]), false);
    }

    /** @test */
    public function invitation_detail_surfaces_discount_source_owner_and_journey_links()
    {
        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Captain',
            'last_name' => 'Partner',
        ]);

        $linkedCoupon = $this->makeCoupon('PARTNER-BACK', [
            'owner_type' => CouponV2Coupon::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
        ]);

        $invitation = $this->makeInvitation('PARTNER-INV', [
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'default_coupon_v2_coupon_id' => $linkedCoupon->id,
            'discount_type' => null,
            'discount_value' => null,
        ]);

        $subscriber = User::factory()->create([
            'first_name' => 'Nour',
            'last_name' => 'Guest',
        ]);

        $subscription = $this->makeSubscription($subscriber);

        $journey = InvitationJourney::create([
            'journey_token' => 'ops-linked-journey',
            'invitation_code_id' => $invitation->id,
            'user_id' => $subscriber->id,
            'payment_id' => null,
            'state' => InvitationJourney::STATE_CHECKOUT_STARTED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'active_coupon_version' => 'v2',
            'active_coupon_id' => $linkedCoupon->id,
            'active_coupon_code' => $linkedCoupon->code,
            'override_coupon_version' => 'v2',
            'override_coupon_id' => $linkedCoupon->id,
            'override_coupon_code' => $linkedCoupon->code,
            'captured_at' => now()->subDays(2),
            'checkout_started_at' => now()->subDay(),
            'last_seen_at' => now(),
        ]);

        $payment = $this->makePayment($subscriber, $subscription);

        $journey->update([
            'payment_id' => $payment->id,
        ]);

        $response = $this->actingAs($admin)->get(route('invitations.show', $invitation));

        $response->assertOk()
            ->assertSee('Backed by Coupon V2', false)
            ->assertSee('PARTNER-BACK', false)
            ->assertSee('Captain Partner', false)
            ->assertSee('ops-linked-journey', false)
            ->assertSee('Copy Code', false)
            ->assertSee('Open Context', false)
            ->assertSee('data-copy-text="PARTNER-INV"', false)
            ->assertSee('Order details', false)
            ->assertSee('Open override coupon PARTNER-BACK', false);
    }

    /** @test */
    public function coupon_detail_surfaces_backing_invitations_override_journeys_and_usage_links()
    {
        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Mina',
            'last_name' => 'Owner',
        ]);

        $coupon = $this->makeCoupon('OPS-COUPON', [
            'owner_type' => CouponV2Coupon::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
        ]);

        $invitation = $this->makeInvitation('OPS-INVITE', [
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'default_coupon_v2_coupon_id' => $coupon->id,
            'discount_type' => null,
            'discount_value' => null,
        ]);

        $subscriber = User::factory()->create([
            'first_name' => 'Laila',
            'last_name' => 'Buyer',
        ]);

        $subscription = $this->makeSubscription($subscriber);

        $payment = $this->makePayment($subscriber, $subscription);

        InvitationJourney::create([
            'journey_token' => 'override-ops-journey',
            'invitation_code_id' => $invitation->id,
            'user_id' => $subscriber->id,
            'payment_id' => $payment->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'override_coupon_version' => 'v2',
            'override_coupon_id' => $coupon->id,
            'override_coupon_code' => $coupon->code,
            'last_seen_at' => now(),
        ]);

        CouponV2Usage::create([
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $subscriber->id,
            'subscription_id' => $subscription->id,
            'payment_id' => $payment->id,
            'payment_status' => CouponV2Usage::STATUS_PAID,
            'total_before_discount' => 1000,
            'discount_amount' => 100,
            'total_after_discount' => 900,
            'vat_amount' => 126,
            'grand_total' => 1026,
            'consumed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('coupons-v2.show', $coupon));

        $response->assertOk()
            ->assertSee('Invitations Backed By This Coupon', false)
            ->assertSee('Override Visibility', false)
            ->assertSee('OPS-INVITE', false)
            ->assertSee('override-ops-journey', false)
            ->assertSee('Copy Code', false)
            ->assertSee('Open Context', false)
            ->assertSee('data-copy-text="OPS-COUPON"', false)
            ->assertSee('Subscription #' . $subscription->id, false)
            ->assertSee('Order details', false);
    }

    /** @test */
    public function partner_workspace_rolls_up_owned_invitation_and_coupon_metrics()
    {
        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Captain',
            'last_name' => 'Gym',
            'email' => 'captain.gym@example.com',
        ]);

        $coupon = $this->makeCoupon('PARTNER-OPS', [
            'owner_type' => CouponV2Coupon::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
        ]);

        $invitationOne = $this->makeInvitation('PARTNER-ONE', [
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
        ]);

        $invitationTwo = $this->makeInvitation('PARTNER-TWO', [
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'default_coupon_v2_coupon_id' => $coupon->id,
            'discount_type' => null,
            'discount_value' => null,
        ]);

        InvitationJourney::create([
            'journey_token' => 'partner-registered',
            'invitation_code_id' => $invitationOne->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitationOne->code,
            'registered_at' => now(),
            'last_seen_at' => now(),
        ]);

        InvitationJourney::create([
            'journey_token' => 'partner-paid',
            'invitation_code_id' => $invitationTwo->id,
            'state' => InvitationJourney::STATE_PAID,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitationTwo->code,
            'paid_at' => now(),
            'last_seen_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('discount-ops.partners'));

        $response->assertOk()
            ->assertSee('Captain Gym', false)
            ->assertSee('captain.gym@example.com', false)
            ->assertSee(route('invitations.index', ['owner_user_id' => $partner->id]), false)
            ->assertSee(route('coupons-v2.index', ['owner_user_id' => $partner->id]), false);
    }

    private function makeInvitation(string $code, array $overrides = []): InvitationCode
    {
        return InvitationCode::create(array_merge([
            'code' => $code,
            'code_normalized' => InvitationCode::normalizeCode($code),
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'default_coupon_v2_coupon_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ], $overrides));
    }

    private function makeCoupon(string $code, array $overrides = []): CouponV2Coupon
    {
        return CouponV2Coupon::create(array_merge([
            'code' => $code,
            'code_normalized' => CouponV2Coupon::normalizeCode($code),
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'expires_at' => now()->addDays(7),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => false,
        ], $overrides));
    }

    private function makeSubscription(User $user): Subscription
    {
        $plan = Plan::query()->first() ?? Plan::create([
            'name' => '1400 Kcal',
            'description' => 'Operator test plan',
        ]);

        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'subscription_type' => 'weight_loss',
            'calorie_range' => '1300-1500',
            'weeks_count' => 4,
            'days_per_week' => 5,
            'excluded_days' => [],
            'price' => 1000,
            'total_meals' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addWeeks(4)->toDateString(),
            'status' => 'active',
        ]);
    }

    private function makePayment(User $user, Subscription $subscription): \App\Models\Payment
    {
        return \App\Models\Payment::create([
            'user_id' => $user->id,
            'subscription_ids' => [$subscription->id],
            'amount' => 1000,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);
    }

    private function makeAdminUser(): User
    {
        $admin = User::factory()->create();

        Role::query()->updateOrInsert(
            ['id' => Role::ADMIN_ROLE],
            [
                'name' => 'Admin',
                'description' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        user_has_roles::create([
            'user_id' => $admin->id,
            'role_id' => Role::ADMIN_ROLE,
        ]);

        return $admin;
    }
}
