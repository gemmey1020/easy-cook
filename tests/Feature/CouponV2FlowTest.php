<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\CouponV2Coupon;
use App\Models\CouponV2Usage;
use App\Models\Day;
use App\Models\MealVariant;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use App\Models\user_has_roles;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponV2FlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PlansSeeder::class);
    }

    /** @test */
    public function apply_v2_percent_coupon_returns_pricing_breakdown()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-SAVE50',
            'code_normalized' => 'SYS-SAVE50',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 50,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $response = $this->postJson('/apply-coupon', [
            'code' => strtolower($coupon->code),
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'version' => 'v2',
                'discount_type' => 'percent',
                'discount_value' => 50.0,
            ])
            ->assertJsonPath('pricing.total_before_discount', 19600)
            ->assertJsonPath('pricing.discount_amount', 9800)
            ->assertJsonPath('pricing.total_after_discount', 9800)
            ->assertJsonPath('pricing.vat_amount', 1372)
            ->assertJsonPath('pricing.grand_total', 11172);
    }

    /** @test */
    public function apply_coupon_falls_back_to_legacy_coupon_when_v2_is_missing()
    {
        Coupon::create([
            'code' => 'SAVE10',
            'discount' => 10,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $response = $this->postJson('/apply-coupon', [
            'code' => 'save10',
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'version' => 'v1',
                'discount_type' => 'percent',
                'discount_value' => 10.0,
            ])
            ->assertJsonPath('pricing.total_before_discount', 19600)
            ->assertJsonPath('pricing.discount_amount', 1960)
            ->assertJsonPath('pricing.total_after_discount', 17640)
            ->assertJsonPath('pricing.vat_amount', 2469.6)
            ->assertJsonPath('pricing.grand_total', 20109.6);
    }

    /** @test */
    public function public_coupon_codes_are_rejected_when_coupon_release_flag_is_disabled()
    {
        config()->set('release_flags.invitation_public_enabled', true);
        config()->set('release_flags.coupon_public_enabled', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-FLAG50',
            'code_normalized' => 'SYS-FLAG50',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 50,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $this->postJson('/apply-coupon', [
            'code' => strtolower($coupon->code),
            'subscription' => [
                'plan_id' => '1400',
                'days_per_week' => 5,
                'weeks_count' => 4,
                'type' => 'standard',
            ],
        ])->assertStatus(422)
            ->assertJsonPath('message', 'Coupon codes are not available right now.');
    }

    /** @test */
    public function dashboard_store_rejects_case_insensitive_collision_with_active_legacy_coupon()
    {
        config()->set('coupon_v2.admin_enabled', true);

        Coupon::create([
            'code' => 'SAVE10',
            'discount' => 10,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $admin = $this->makeAdminUser();

        $response = $this->actingAs($admin)->post(route('coupons-v2.store'), [
            'code' => 'save10',
            'owner_type' => 'system',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'max_redemptions' => 5,
            'expires_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertDatabaseCount('coupon_v2_coupons', 0);
    }

    /** @test */
    public function admin_can_create_view_group_and_toggle_coupon_v2_records()
    {
        config()->set('coupon_v2.admin_enabled', true);

        $admin = $this->makeAdminUser();
        $partner = User::factory()->create([
            'first_name' => 'Partner',
            'last_name' => 'Owner',
        ]);

        $storeResponse = $this->actingAs($admin)->post(route('coupons-v2.store'), [
            'code' => 'ptr-launch',
            'owner_type' => CouponV2Coupon::OWNER_PARTNER,
            'owner_user_id' => $partner->id,
            'discount_type' => CouponV2Coupon::TYPE_FIXED,
            'discount_value' => 250,
            'max_redemptions' => 6,
            'expires_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'notes' => 'Partner launch coupon',
        ]);

        $storeResponse->assertRedirect(route('coupons-v2.index'));

        $coupon = CouponV2Coupon::query()->firstOrFail();

        $this->assertSame('PTR-LAUNCH', $coupon->code);
        $this->assertSame($partner->id, $coupon->owner_user_id);

        $this->actingAs($admin)
            ->get(route('coupons-v2.index'))
            ->assertOk()
            ->assertSee('PTR-LAUNCH', false)
            ->assertSee('coupon-v2-owner-filter', false)
            ->assertSee('coupon-v2-status-filter', false)
            ->assertSee('data-copy-text="PTR-LAUNCH"', false)
            ->assertSee('data-coupon-owner-type', false);

        $this->actingAs($admin)
            ->get(route('coupons-v2.show', $coupon))
            ->assertOk()
            ->assertSee('PTR-LAUNCH', false)
            ->assertSee('data-coupon-owner-type', false)
            ->assertSee('data-partner-select', false);

        $this->actingAs($admin)
            ->get(route('coupons-v2.partners'))
            ->assertRedirect(route('discount-ops.partners'));

        $this->actingAs($admin)
            ->get(route('discount-ops.partners'))
            ->assertOk()
            ->assertSee('Partner', false)
            ->assertSee(route('coupons-v2.index', ['owner_user_id' => $partner->id]), false)
            ->assertSee(route('invitations.index', ['owner_user_id' => $partner->id]), false);

        $this->actingAs($admin)
            ->post(route('coupons-v2.toggle-status', $coupon))
            ->assertRedirect();

        $this->assertDatabaseHas('coupon_v2_coupons', [
            'id' => $coupon->id,
            'status' => CouponV2Coupon::STATUS_SUSPENDED,
        ]);
    }

    /** @test */
    public function checkout_with_v2_coupon_creates_subscription_and_pending_usage()
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

        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-CHECK50',
            'code_normalized' => 'SYS-CHECK50',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 50,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $payload = [
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
            'applied_coupon' => [
                'version' => 'v2',
                'id' => $coupon->id,
                'code' => $coupon->code,
            ],
            'billing_address' => [
                'town_city' => 'Cairo',
                'street_address' => '123 Omega Street',
            ],
        ];

        $response = $this->postJson('/checkout', $payload);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
            ]);

        $subscription = Subscription::first();

        $this->assertNotNull($subscription);
        $this->assertSame($coupon->id, $subscription->coupon_v2_coupon_id);
        $this->assertSame(11172.0, (float) $subscription->price);

        $this->assertDatabaseHas('coupon_v2_usages', [
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'payment_status' => CouponV2Usage::STATUS_PENDING_PAYMENT,
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'town_city' => 'Cairo',
            'street_address' => '123 Omega Street',
            'primary' => 1,
        ]);
    }

    /** @test */
    public function checkout_uses_canonical_three_week_pricing_for_coupon_discounts()
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

        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-CHECK3W',
            'code_normalized' => 'SYS-CHECK3W',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 50,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $payload = [
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
            'applied_coupon' => [
                'version' => 'v2',
                'id' => $coupon->id,
                'code' => $coupon->code,
            ],
            'billing_address' => [
                'town_city' => 'Cairo',
                'street_address' => '123 Omega Street',
            ],
        ];

        $response = $this->postJson('/checkout', $payload);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $subscription = Subscription::first();

        $this->assertNotNull($subscription);
        $this->assertSame(3, $subscription->weeks_count);
        $this->assertSame(8493.0, (float) $subscription->price);

        $this->assertDatabaseHas('coupon_v2_usages', [
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'total_before_discount' => 14900,
            'grand_total' => 8493,
        ]);
    }

    /** @test */
    public function checkout_rejects_coupon_v2_if_user_has_already_paid_with_it()
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

        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-ONCE',
            'code_normalized' => 'SYS-ONCE',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_PERCENT,
            'discount_value' => 20,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $previousSubscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => Plan::where('name', 'like', '%1400%')->firstOrFail()->id,
            'price' => 17875.2,
            'coupon_v2_coupon_id' => $coupon->id,
        ]);

        CouponV2Usage::create([
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'subscription_id' => $previousSubscription->id,
            'payment_id' => null,
            'payment_status' => CouponV2Usage::STATUS_PAID,
            'total_before_discount' => 19600,
            'discount_amount' => 3920,
            'total_after_discount' => 15680,
            'vat_amount' => 2195.2,
            'grand_total' => 17875.2,
            'consumed_at' => now(),
        ]);

        $response = $this->postJson('/checkout', [
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
            'applied_coupon' => [
                'version' => 'v2',
                'id' => $coupon->id,
                'code' => $coupon->code,
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Validation error.');
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
