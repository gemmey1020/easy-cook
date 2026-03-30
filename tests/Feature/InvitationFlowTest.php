<?php

namespace Tests\Feature;

use App\Models\CouponV2Coupon;
use App\Models\Day;
use App\Models\InvitationCode;
use App\Models\InvitationJourney;
use App\Models\MealVariant;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use App\Models\user_has_roles;
use Database\Seeders\PlansSeeder;
use App\Services\InvitationService;
use App\Services\SubscriptionPricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PlansSeeder::class);
    }

    /** @test */
    public function valid_invitation_capture_creates_a_server_backed_journey()
    {
        $invitation = InvitationCode::create([
            'code' => 'CAPTAIN-LAUNCH',
            'code_normalized' => 'CAPTAIN-LAUNCH',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 15,
            'max_redemptions' => 10,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $response = $this->postJson(route('invitation.capture'), [
            'code' => strtolower($invitation->code),
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'referral_source' => 'meta',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('invitation.state', InvitationJourney::STATE_CAPTURED)
            ->assertJsonPath('invitation.is_valid', true)
            ->assertJsonPath('invitation.default_coupon.version', 'invitation')
            ->assertJsonPath('invitation.default_coupon.code', $invitation->code)
            ->assertJsonPath('invitation.discount_type', InvitationCode::TYPE_PERCENT)
            ->assertJsonPath('invitation.discount_value', 15);

        $this->assertDatabaseHas('invitation_journeys', [
            'invitation_code_id' => $invitation->id,
            'state' => InvitationJourney::STATE_CAPTURED,
            'referral_source' => 'meta',
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
        ]);
    }

    /** @test */
    public function invalid_invitation_capture_is_soft_and_persists_invalid_truth()
    {
        $response = $this->postJson(route('invitation.capture'), [
            'code' => 'missing-code',
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'referral_source' => 'google',
        ]);

        $response->assertOk()
            ->assertJsonPath('invitation.state', InvitationJourney::STATE_INVALID_CODE)
            ->assertJsonPath('invitation.is_valid', false)
            ->assertJsonPath('invitation.invalid_reason', 'not_found')
            ->assertJsonPath('message', 'Invitation code missing-code is invalid. You can continue without it.');

        $this->assertDatabaseHas('invitation_journeys', [
            'state' => InvitationJourney::STATE_INVALID_CODE,
            'last_invalid_code' => 'missing-code',
            'referral_source' => 'google',
        ]);
    }

    /** @test */
    public function invitation_capture_route_is_closed_when_public_invitation_flag_is_disabled()
    {
        config()->set('release_flags.invitation_public_enabled', false);

        $this->postJson(route('invitation.capture'), [
            'code' => 'CAPTAIN-LAUNCH',
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
        ])->assertNotFound();
    }

    /** @test */
    public function forgetting_the_active_invitation_journey_clears_server_backed_session_truth()
    {
        $invitation = InvitationCode::create([
            'code' => 'RESET-ME',
            'code_normalized' => 'RESET-ME',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 6,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'forget-journey-token',
            'invitation_code_id' => $invitation->id,
            'state' => InvitationJourney::STATE_CAPTURED,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $invitation->code,
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'last_seen_at' => now(),
        ]);

        $response = $this
            ->withSession([InvitationService::SESSION_KEY => $journey->journey_token])
            ->postJson(route('invitation.forget'), [
                'journey_token' => $journey->journey_token,
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $response->assertSessionMissing(InvitationService::SESSION_KEY);
    }

    /** @test */
    public function valid_invitation_capture_with_native_discount_is_still_treated_as_valid()
    {
        $invitation = InvitationCode::create([
            'code' => 'ATTR-ONLY',
            'code_normalized' => 'ATTR-ONLY',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_FIXED,
            'discount_value' => 120,
            'max_redemptions' => 4,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $response = $this->postJson(route('invitation.capture'), [
            'code' => $invitation->code,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'referral_source' => 'direct_link',
        ]);

        $response->assertOk()
            ->assertJsonPath('invitation.state', InvitationJourney::STATE_CAPTURED)
            ->assertJsonPath('invitation.is_valid', true)
            ->assertJsonPath('invitation.default_coupon.version', 'invitation')
            ->assertJsonPath('invitation.active_coupon.version', 'invitation')
            ->assertJsonPath('invitation.discount_type', InvitationCode::TYPE_FIXED)
            ->assertJsonPath('invitation.discount_value', 120);

        $this->assertDatabaseHas('invitation_journeys', [
            'invitation_code_id' => $invitation->id,
            'state' => InvitationJourney::STATE_CAPTURED,
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
        ]);
    }

    /** @test */
    public function registration_attaches_journey_and_updates_user_origin_for_partner_owned_invites()
    {
        $partner = User::factory()->create([
            'first_name' => 'Captain',
            'last_name' => 'Owner',
        ]);

        $invitation = InvitationCode::create([
            'code' => 'CAPTAIN-50',
            'code_normalized' => 'CAPTAIN-50',
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $captureResponse = $this->postJson(route('invitation.capture'), [
            'code' => $invitation->code,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'referral_source' => 'captain_gym',
        ]);

        $journeyToken = $captureResponse->json('invitation.token');

        $registerResponse = $this->postJson(route('post_register_web'), [
            'first_name' => 'Tarek',
            'last_name' => 'Partner',
            'email' => 'tarek.partner@gmail.com',
            'phone' => '01012345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'street_address' => '12 Nile Street',
            'town_city' => 'Cairo',
            'company_name' => 'Easy Cook Partner',
            'final_data' => '{}',
            'invitation_journey_token' => $journeyToken,
            'invitation_code' => $invitation->code,
            'referral_source' => 'captain_gym',
        ]);

        $registerResponse->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('invitation.registered_with_same_code', true)
            ->assertJsonPath('invitation.is_discount_eligible', true);

        $userId = $registerResponse->json('user_id');

        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'user_origin_id' => $partner->id,
        ]);

        $this->assertDatabaseHas('invitation_journeys', [
            'journey_token' => $journeyToken,
            'user_id' => $userId,
            'state' => InvitationJourney::STATE_REGISTERED,
        ]);
    }

    /** @test */
    public function registration_page_keeps_the_site_logo_visible_and_surfaces_the_new_checkout_framing()
    {
        $response = $this->get(route('register_web'));

        $response->assertOk()
            ->assertSee('alt="Easy Cook"', false)
            ->assertSeeText('Already have an account? Sign in')
            ->assertSeeText('Continue to checkout')
            ->assertSeeText('Private onboarding');
    }

    /** @test */
    public function web_registration_redirects_to_checkout_when_checkout_destination_is_requested()
    {
        $response = $this->from(route('register_web'))->post(route('post_register_web'), [
            'first_name' => 'Amina',
            'last_name' => 'Checkout',
            'email' => 'amina.checkout@gmail.com',
            'phone' => '01011112222',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'street_address' => '15 Garden City Street',
            'town_city' => 'Cairo',
            'company_name' => 'Easy Cook',
            'final_data' => '{}',
            'referral_source' => 'meta',
            'post_registration_destination' => 'checkout',
        ]);

        $response->assertRedirect(route('checkout.show'));
        $this->assertAuthenticated();
    }

    /** @test */
    public function web_registration_from_checkout_context_redirects_to_checkout_page_even_without_an_explicit_destination_flag()
    {
        $response = $this->from(route('checkout.show'))->post(route('post_register_web'), [
            'first_name' => 'Salma',
            'last_name' => 'Context',
            'email' => 'salma.context@gmail.com',
            'phone' => '01011114444',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'street_address' => '12 Zamalek Street',
            'town_city' => 'Cairo',
            'company_name' => null,
            'final_data' => '{}',
            'referral_source' => 'captain_gym',
            'post_registration_destination' => 'default',
        ]);

        $response->assertRedirect(route('checkout.show'));
        $this->assertAuthenticated();
    }

    /** @test */
    public function web_registration_keeps_the_default_fallback_when_checkout_destination_is_not_requested()
    {
        $response = $this->from(route('register_web'))->post(route('post_register_web'), [
            'first_name' => 'Nada',
            'last_name' => 'Dashboard',
            'email' => 'nada.dashboard@gmail.com',
            'phone' => '01011113333',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'street_address' => '88 Maadi Street',
            'town_city' => 'Cairo',
            'company_name' => null,
            'final_data' => '{}',
            'referral_source' => 'google',
            'post_registration_destination' => 'default',
        ]);

        $response->assertRedirect(route('user-dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    /** @test */
    public function checkout_uses_the_invitation_discount_only_when_it_is_explicitly_selected()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Day::create(['name' => 'Sunday']);
        MealVariant::create([
            'meal_id' => '1400_week1_sunday_breakfast_1',
            'calorie_level' => 1400,
            'week' => 1,
            'day' => 'sunday',
            'meal_type' => 'breakfast',
            'option_no' => 1,
            'price' => 0,
        ]);

        $invitation = InvitationCode::create([
            'code' => 'INV-CHECKOUT',
            'code_normalized' => 'INV-CHECKOUT',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 5,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'journey-checkout-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'referral_source' => 'direct_link',
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'registered_at' => now(),
        ]);

        $response = $this->postJson(route('checkout'), [
            'subscriptions' => [[
                'user_id' => $user->id,
                'plan_id' => '1400',
                'weeks_count' => 4,
                'days_per_week' => 5,
                'type' => 'standard',
                'meals' => [[
                    'meal_id' => '1400_week1_sunday_breakfast_1',
                    'day' => 'Sunday',
                    'meal_type' => 'breakfast',
                ]],
            ]],
            'billing_address' => [
                'town_city' => 'Cairo',
                'street_address' => '123 Omega Street',
            ],
            'applied_coupon' => [
                'version' => 'invitation',
                'id' => $invitation->id,
                'code' => $invitation->code,
            ],
            'invitation_journey_token' => $journey->journey_token,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('invitation.state', InvitationJourney::STATE_CHECKOUT_STARTED);

        $subscription = Subscription::first();
        $expectedPricing = app(SubscriptionPricingService::class)->calculateForSubscription([
            'plan_id' => '1400',
            'weeks_count' => 4,
            'days_per_week' => 5,
            'type' => 'standard',
        ], [
            'type' => InvitationCode::TYPE_PERCENT,
            'value' => 10,
        ]);

        $this->assertNotNull($subscription);
        $this->assertNull($subscription->coupon_v2_coupon_id);
        $this->assertSame((float) $expectedPricing['grand_total'], (float) $subscription->price);

        $this->assertDatabaseHas('invitation_journeys', [
            'id' => $journey->id,
            'state' => InvitationJourney::STATE_CHECKOUT_STARTED,
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
        ]);
    }

    /** @test */
    public function checkout_uses_canonical_three_week_pricing_for_invitation_discounts()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Day::create(['name' => 'Sunday']);
        MealVariant::create([
            'meal_id' => '1400_week1_sunday_breakfast_1',
            'calorie_level' => 1400,
            'week' => 1,
            'day' => 'sunday',
            'meal_type' => 'breakfast',
            'option_no' => 1,
            'price' => 0,
        ]);

        $invitation = InvitationCode::create([
            'code' => 'INV-3WEEK',
            'code_normalized' => 'INV-3WEEK',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'journey-3week-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'referral_source' => 'direct_link',
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'registered_at' => now(),
        ]);

        $response = $this->postJson(route('checkout'), [
            'subscriptions' => [[
                'user_id' => $user->id,
                'plan_id' => '1400',
                'weeks_count' => 3,
                'days_per_week' => 5,
                'type' => 'standard',
                'meals' => [[
                    'meal_id' => '1400_week1_sunday_breakfast_1',
                    'day' => 'Sunday',
                    'meal_type' => 'breakfast',
                ]],
            ]],
            'billing_address' => [
                'town_city' => 'Cairo',
                'street_address' => '123 Omega Street',
            ],
            'applied_coupon' => [
                'version' => 'invitation',
                'id' => $invitation->id,
                'code' => $invitation->code,
            ],
            'invitation_journey_token' => $journey->journey_token,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $subscription = Subscription::first();
        $expectedPricing = app(SubscriptionPricingService::class)->calculateForSubscription([
            'plan_id' => '1400',
            'weeks_count' => 3,
            'days_per_week' => 5,
            'type' => 'standard',
        ], [
            'type' => InvitationCode::TYPE_PERCENT,
            'value' => 10,
        ]);

        $this->assertNotNull($subscription);
        $this->assertSame(3, $subscription->weeks_count);
        $this->assertSame((float) $expectedPricing['grand_total'], (float) $subscription->price);
        $this->assertSame(15287.4, (float) $subscription->price);
    }

    /** @test */
    public function checkout_does_not_auto_apply_the_invitation_discount_without_an_explicit_selection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Day::create(['name' => 'Sunday']);
        MealVariant::create([
            'meal_id' => '1400_week1_sunday_breakfast_1',
            'calorie_level' => 1400,
            'week' => 1,
            'day' => 'sunday',
            'meal_type' => 'breakfast',
            'option_no' => 1,
            'price' => 0,
        ]);

        $invitation = InvitationCode::create([
            'code' => 'INV-NO-AUTO',
            'code_normalized' => 'INV-NO-AUTO',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 5,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'journey-no-auto-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'referral_source' => 'direct_link',
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'registered_at' => now(),
        ]);

        $response = $this->postJson(route('checkout'), [
            'subscriptions' => [[
                'user_id' => $user->id,
                'plan_id' => '1400',
                'weeks_count' => 4,
                'days_per_week' => 5,
                'type' => 'standard',
                'meals' => [[
                    'meal_id' => '1400_week1_sunday_breakfast_1',
                    'day' => 'Sunday',
                    'meal_type' => 'breakfast',
                ]],
            ]],
            'billing_address' => [
                'town_city' => 'Cairo',
                'street_address' => '123 Omega Street',
            ],
            'invitation_journey_token' => $journey->journey_token,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('invitation.state', InvitationJourney::STATE_CHECKOUT_STARTED);

        $subscription = Subscription::first();
        $expectedPricing = app(SubscriptionPricingService::class)->calculateForSubscription([
            'plan_id' => '1400',
            'weeks_count' => 4,
            'days_per_week' => 5,
            'type' => 'standard',
        ]);

        $this->assertNotNull($subscription);
        $this->assertSame((float) $expectedPricing['grand_total'], (float) $subscription->price);
    }

    /** @test */
    public function applying_a_different_coupon_requires_confirmation_when_invitation_default_exists()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $defaultCoupon = $this->makeCoupon('SYS-DEFAULT');
        $overrideCoupon = $this->makeCoupon('SYS-OVERRIDE');

        $invitation = InvitationCode::create([
            'code' => 'OVERRIDE-GATE',
            'code_normalized' => 'OVERRIDE-GATE',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'journey-override-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $invitation->code,
            'referral_source' => 'meta',
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'registered_at' => now(),
        ]);

        $subscriptionContext = [
            'plan_id' => '1400',
            'days_per_week' => 5,
            'weeks_count' => 4,
            'type' => 'standard',
        ];

        $firstResponse = $this->postJson(route('coupon.apply'), [
            'code' => $overrideCoupon->code,
            'subscription' => $subscriptionContext,
            'invitation_journey_token' => $journey->journey_token,
        ]);

        $firstResponse->assertStatus(409)
            ->assertJsonPath('requires_confirmation', true);

        $secondResponse = $this->postJson(route('coupon.apply'), [
            'code' => $overrideCoupon->code,
            'subscription' => $subscriptionContext,
            'invitation_journey_token' => $journey->journey_token,
            'confirm_invitation_override' => true,
        ]);

        $secondResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('invitation.has_override', true);

        $this->assertDatabaseHas('invitation_journeys', [
            'id' => $journey->id,
            'override_coupon_version' => 'v2',
            'override_coupon_id' => $overrideCoupon->id,
        ]);
    }

    /** @test */
    public function invitation_discount_blocks_second_paid_usage_for_the_same_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $invitation = InvitationCode::create([
            'code' => 'ONE-USER-ONLY',
            'code_normalized' => 'ONE-USER-ONLY',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 15,
            'max_redemptions' => 5,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        InvitationJourney::create([
            'journey_token' => 'paid-usage-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_PAID,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'paid_at' => now(),
        ]);

        $activeJourney = InvitationJourney::create([
            'journey_token' => 'retry-usage-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $invitation->code,
            'registered_at' => now(),
        ]);

        $response = $this->postJson(route('coupon.apply'), [
            'code' => $invitation->code,
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
            'invitation_journey_token' => $activeJourney->journey_token,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'You have already used this invitation code.');
    }

    /** @test */
    public function guest_user_must_register_with_the_invitation_code_before_using_the_discount()
    {
        $invitation = InvitationCode::create([
            'code' => 'REGISTER-FIRST',
            'code_normalized' => 'REGISTER-FIRST',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 12,
            'max_redemptions' => 5,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $captureResponse = $this->postJson(route('invitation.capture'), [
            'code' => $invitation->code,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'referral_source' => 'meta',
        ]);

        $response = $this->postJson(route('coupon.apply'), [
            'code' => $invitation->code,
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
            'invitation_journey_token' => $captureResponse->json('invitation.token'),
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Register your account with this invitation code first to unlock the discount in checkout.');
    }

    /** @test */
    public function a_user_cannot_apply_an_invitation_discount_from_another_accounts_registration_journey()
    {
        $registeredUser = User::factory()->create();
        $otherUser = User::factory()->create();

        $invitation = InvitationCode::create([
            'code' => 'SAME-ACCOUNT-ONLY',
            'code_normalized' => 'SAME-ACCOUNT-ONLY',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 8,
            'max_redemptions' => 5,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $journey = InvitationJourney::create([
            'journey_token' => 'same-account-only-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $registeredUser->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $invitation->code,
            'registered_at' => now(),
        ]);

        $this->actingAs($otherUser);

        $response = $this->postJson(route('coupon.apply'), [
            'code' => $invitation->code,
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
            'invitation_journey_token' => $journey->journey_token,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'This invitation discount is available only for accounts registered with this invitation code.');
    }

    /** @test */
    public function invitation_discount_respects_the_total_redemption_cap()
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $invitation = InvitationCode::create([
            'code' => 'LIMIT-ONE',
            'code_normalized' => 'LIMIT-ONE',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_FIXED,
            'discount_value' => 100,
            'max_redemptions' => 1,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        InvitationJourney::create([
            'journey_token' => 'cap-paid-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $firstUser->id,
            'state' => InvitationJourney::STATE_PAID,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'paid_at' => now(),
        ]);

        $activeJourney = InvitationJourney::create([
            'journey_token' => 'cap-retry-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $secondUser->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $invitation->code,
            'registered_at' => now(),
        ]);

        $this->actingAs($secondUser);

        $response = $this->postJson(route('coupon.apply'), [
            'code' => $invitation->code,
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
            'invitation_journey_token' => $activeJourney->journey_token,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Invitation code LIMIT-ONE has reached its usage limit.');
    }

    /** @test */
    public function admin_can_create_and_view_invitation_dashboard_records()
    {
        config()->set('coupon_v2.admin_enabled', true);

        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Partner',
            'last_name' => 'Gym',
        ]);

        $storeResponse = $this->actingAs($admin)->post(route('invitations.store'), [
            'code' => 'partner-launch',
            'owner_type' => InvitationCode::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 12,
            'max_redemptions' => 25,
            'status' => InvitationCode::STATUS_ACTIVE,
            'expires_at' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'notes' => 'Partner invitation launch',
        ]);

        $storeResponse->assertRedirect(route('invitations.index'));

        $code = InvitationCode::firstOrFail();

        $this->actingAs($admin)
            ->get(route('invitations.index'))
            ->assertOk()
            ->assertSee('PARTNER-LAUNCH', false)
            ->assertSee('Invitation Codes', false);

        $this->actingAs($admin)
            ->get(route('invitations.show', $code))
            ->assertOk()
            ->assertSee('PARTNER-LAUNCH', false);
    }

    private function makeCoupon(string $code, string $ownerType = InvitationCode::OWNER_SYSTEM, ?int $ownerUserId = null): CouponV2Coupon
    {
        return CouponV2Coupon::create([
            'code' => $code,
            'code_normalized' => strtoupper($code),
            'owner_type' => $ownerType === InvitationCode::OWNER_PARTNER
                ? CouponV2Coupon::OWNER_PARTNER
                : CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => $ownerUserId,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 10,
            'expires_at' => now()->addDays(15),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => false,
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
