<?php

namespace Tests\Feature;

use App\Mail\InvoicePaidMail;
use App\Models\Coupon;
use App\Models\CouponV2Coupon;
use App\Models\CouponV2Usage;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionPricingService;
use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SubscriptionPricingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PlansSeeder::class);
    }

    /** @test */
    public function pricing_catalog_matches_the_official_supported_combinations()
    {
        $service = app(SubscriptionPricingService::class);

        $this->assertSame(14900.0, $service->tierFor('1400', 5, 3)['without_vat']);
        $this->assertSame(17850.0, $service->tierFor('1400', 6, 3)['without_vat']);
        $this->assertSame(16500.0, $service->tierFor('1700', 5, 3)['without_vat']);
        $this->assertSame(19800.0, $service->tierFor('1700', 6, 3)['without_vat']);
        $this->assertSame(17400.0, $service->tierFor('2000', 5, 3)['without_vat']);
        $this->assertSame(20900.0, $service->tierFor('2000', 6, 3)['without_vat']);
        $this->assertSame(19600.0, $service->tierFor('1400', 5, 4)['without_vat']);
        $this->assertSame(23500.0, $service->tierFor('1400', 6, 4)['without_vat']);
        $this->assertSame(21700.0, $service->tierFor('1700', 5, 4)['without_vat']);
        $this->assertSame(26000.0, $service->tierFor('1700', 6, 4)['without_vat']);
        $this->assertSame(22900.0, $service->tierFor('2000', 5, 4)['without_vat']);
        $this->assertSame(27500.0, $service->tierFor('2000', 6, 4)['without_vat']);
    }

    /** @test */
    public function plans_catalog_adaptor_groups_tiers_by_weeks_then_days()
    {
        $service = app(SubscriptionPricingService::class);
        $catalog = $service->plansCatalog(true);

        $this->assertSame(14900.0, (float) $catalog[1400][3][5]['without_vat']);
        $this->assertSame(20349.0, (float) $catalog[1400][3][6]['with_vat']);
        $this->assertSame(21700.0, (float) $catalog[1700][4][5]['without_vat']);
        $this->assertSame(31350.0, (float) $catalog[2000][4][6]['with_vat']);
    }

    /** @test */
    public function plans_catalog_can_hide_three_week_tiers_when_ui_flag_is_disabled()
    {
        $service = app(SubscriptionPricingService::class);
        $catalog = $service->plansCatalog(false);

        $this->assertArrayNotHasKey(3, $catalog[1400]);
        $this->assertSame(19600.0, (float) $catalog[1400][4][5]['without_vat']);
    }

    /** @test */
    public function plans_surfaces_receive_the_backend_pricing_catalog_when_three_week_ui_is_enabled()
    {
        config()->set('subscription_pricing.three_week_ui_enabled', true);

        $plansResponse = $this->get(route('plans'));
        $plansResponse->assertOk()
            ->assertSeeText('3 Weeks')
            ->assertSeeText('18 Days or 15 Days')
            ->assertViewHas('threeWeekUiEnabled', true)
            ->assertViewHas('plansPricingCatalog', function (array $plansPricingCatalog) {
                return (float) $plansPricingCatalog[1400][3][5]['without_vat'] === 14900.0
                    && (float) $plansPricingCatalog[2000][4][6]['with_vat'] === 31350.0;
            });

        $planTwoResponse = $this->get(route('plan_2'));
        $planTwoResponse->assertOk()
            ->assertSeeText('3 Weeks')
            ->assertSeeText('18 Days or 15 Days')
            ->assertViewHas('threeWeekUiEnabled', true)
            ->assertViewHas('plansPricingCatalog', function (array $plansPricingCatalog) {
                return (float) $plansPricingCatalog[1700][3][6]['without_vat'] === 19800.0
                    && (float) $plansPricingCatalog[1700][4][6]['with_vat'] === 29640.0;
            });
    }

    /** @test */
    public function plans_surfaces_hide_three_week_copy_when_the_ui_flag_is_disabled()
    {
        config()->set('subscription_pricing.three_week_ui_enabled', false);

        $plansResponse = $this->get(route('plans'));
        $plansResponse->assertOk()
            ->assertDontSeeText('3 Weeks')
            ->assertDontSeeText('18 Days or 15 Days')
            ->assertViewHas('threeWeekUiEnabled', false)
            ->assertViewHas('plansPricingCatalog', function (array $plansPricingCatalog) {
                return ! isset($plansPricingCatalog[1400][3])
                    && (float) $plansPricingCatalog[1400][4][5]['without_vat'] === 19600.0;
            });

        $planTwoResponse = $this->get(route('plan_2'));
        $planTwoResponse->assertOk()
            ->assertDontSeeText('3 Weeks')
            ->assertDontSeeText('18 Days or 15 Days')
            ->assertViewHas('threeWeekUiEnabled', false);
    }

    /** @test */
    public function checkout_surface_receives_the_canonical_weeks_aware_pricing_matrix()
    {
        $response = $this->get(route('checkout.show'));

        $response->assertOk()
            ->assertViewHas('pricingMatrix', function (array $pricingMatrix) {
                return (float) $pricingMatrix[1400][5][4]['without_vat'] === 19600.0
                    && (float) $pricingMatrix[1400][5][3]['without_vat'] === 14900.0
                    && (float) $pricingMatrix[2000][6][4]['with_vat'] === 31350.0;
            });
    }

    /** @test */
    public function stored_subscription_breakdown_reconstructs_legacy_percent_coupon_from_snapshot()
    {
        $service = app(SubscriptionPricingService::class);
        $user = User::factory()->create();
        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();

        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'discount' => 10,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 20109.6,
            'days_per_week' => 5,
            'coupon_id' => $coupon->id,
        ]);

        $pricing = $service->calculateForStoredSubscription($subscription);

        $this->assertSame(19600.0, $pricing['total_before_discount']);
        $this->assertSame(1960.0, $pricing['discount_amount']);
        $this->assertSame(17640.0, $pricing['total_after_discount']);
        $this->assertSame(2469.6, $pricing['vat_amount']);
        $this->assertSame(20109.6, $pricing['grand_total']);
        $this->assertSame('10%', $pricing['discount_label']);
    }

    /** @test */
    public function stored_subscription_breakdown_prefers_recorded_coupon_v2_usage_when_available()
    {
        $service = app(SubscriptionPricingService::class);
        $user = User::factory()->create();
        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();

        $coupon = CouponV2Coupon::create([
            'code' => 'SYS-FIXED250',
            'code_normalized' => 'SYS-FIXED250',
            'owner_type' => CouponV2Coupon::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => CouponV2Coupon::TYPE_FIXED,
            'discount_value' => 250,
            'max_redemptions' => 10,
            'expires_at' => now()->addDay(),
            'status' => CouponV2Coupon::STATUS_ACTIVE,
            'auto_generated' => true,
        ]);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 22059,
            'days_per_week' => 5,
            'coupon_v2_coupon_id' => $coupon->id,
        ]);

        CouponV2Usage::create([
            'coupon_v2_coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'payment_id' => null,
            'payment_status' => CouponV2Usage::STATUS_PAID,
            'total_before_discount' => 19600,
            'discount_amount' => 250,
            'total_after_discount' => 19350,
            'vat_amount' => 2709,
            'grand_total' => 22059,
            'consumed_at' => now(),
        ]);

        $pricing = $service->calculateForStoredSubscription($subscription);

        $this->assertSame(19600.0, $pricing['total_before_discount']);
        $this->assertSame(250.0, $pricing['discount_amount']);
        $this->assertSame(19350.0, $pricing['total_after_discount']);
        $this->assertSame(2709.0, $pricing['vat_amount']);
        $this->assertSame(22059.0, $pricing['grand_total']);
        $this->assertSame('250.00 LE', $pricing['discount_label']);
    }

    /** @test */
    public function invoice_mailable_uses_the_unified_pricing_breakdown()
    {
        $user = User::factory()->create();
        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();
        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'discount' => 10,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 20109.6,
            'days_per_week' => 5,
            'coupon_id' => $coupon->id,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_ids' => [$subscription->id],
            'amount' => 20109.6,
            'payment_status' => 'paid',
            'payment_method' => 'card',
            'transaction_id' => 'txn_invoice',
            'order_id' => 'ord_invoice',
        ]);

        $domPdfFake = Mockery::mock(PDF::class);
        $domPdfFake->shouldReceive('setPaper')->once()->andReturnSelf();
        $domPdfFake->shouldReceive('output')->andReturn('fake-pdf');

        DomPDF::shouldReceive('loadView')
            ->once()
            ->withArgs(function (string $view, array $data) {
                return $view === 'invoices.subscription'
                    && (float) $data['sub_total'] === 19600.0
                    && (float) $data['discount_amount'] === 1960.0
                    && (float) $data['vat_amount'] === 2469.6
                    && (float) $data['grand_total'] === 20109.6
                    && $data['discount_label'] === '10%';
            })
            ->andReturn($domPdfFake);

        $mail = new InvoicePaidMail($user, $payment, collect([$subscription]));
        $attachments = $mail->attachments();

        $this->assertCount(1, $attachments);
    }

    /** @test */
    public function stored_subscription_fallback_recompute_is_weeks_aware_for_three_week_values()
    {
        $service = app(SubscriptionPricingService::class);
        $user = User::factory()->create();
        $plan = Plan::where('name', 'like', '%1400%')->firstOrFail();

        $coupon = Coupon::create([
            'code' => 'FREE3W',
            'discount' => 100,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'price' => 0,
            'weeks_count' => 3,
            'days_per_week' => 5,
            'coupon_id' => $coupon->id,
        ]);

        $pricing = $service->calculateForStoredSubscription($subscription);

        $this->assertSame(3, $pricing['weeks_count']);
        $this->assertSame(14900.0, $pricing['total_before_discount']);
        $this->assertSame(14900.0, $pricing['discount_amount']);
        $this->assertSame(0.0, $pricing['grand_total']);
    }
}
