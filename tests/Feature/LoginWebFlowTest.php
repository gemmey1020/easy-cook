<?php

namespace Tests\Feature;

use App\Models\InvitationCode;
use App\Models\InvitationJourney;
use App\Models\Role;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginWebFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_keeps_the_site_logo_visible_and_surfaces_the_new_fast_track_copy()
    {
        $response = $this->get(route('login_web'));

        $response->assertOk()
            ->assertSee('alt="Easy Cook"', false)
            ->assertSeeText('Create account')
            ->assertSeeText('Sign in and continue')
            ->assertSeeText('Email address')
            ->assertSeeText('Forgot password')
            ->assertSeeText('Sign back in without losing your momentum.')
            ->assertSeeText('Invitation awareness follows this browser');
    }

    /** @test */
    public function login_page_receives_invitation_snapshot_when_an_incoming_code_is_present()
    {
        $invitation = InvitationCode::create([
            'code' => 'LOGIN-AWARE',
            'code_normalized' => 'LOGIN-AWARE',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 15,
            'max_redemptions' => 5,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $response = $this->get(route('login_web', ['invitation_code' => $invitation->code]));

        $response->assertOk()
            ->assertViewHas('invitationAutoCaptured', true)
            ->assertViewHas('invitationState', function (array $state) use ($invitation) {
                return ($state['token'] ?? null) !== null
                    && ($state['code'] ?? null) === $invitation->code
                    && ($state['is_valid'] ?? false) === true;
            });
    }

    /** @test */
    public function successful_login_prefers_the_existing_registered_journey_for_the_same_invitation_code()
    {
        $user = User::factory()->create([
            'email' => 'journey.login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $invitation = InvitationCode::create([
            'code' => 'LOGIN-SYNC',
            'code_normalized' => 'LOGIN-SYNC',
            'owner_type' => InvitationCode::OWNER_SYSTEM,
            'owner_user_id' => null,
            'discount_type' => InvitationCode::TYPE_PERCENT,
            'discount_value' => 10,
            'max_redemptions' => 8,
            'status' => InvitationCode::STATUS_ACTIVE,
            'auto_generated' => false,
        ]);

        $capturedJourney = InvitationJourney::create([
            'journey_token' => 'captured-login-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => null,
            'state' => InvitationJourney::STATE_CAPTURED,
            'capture_channel' => InvitationJourney::CHANNEL_LINK,
            'raw_invitation_code' => $invitation->code,
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'last_seen_at' => now(),
        ]);

        $registeredJourney = InvitationJourney::create([
            'journey_token' => 'registered-login-token',
            'invitation_code_id' => $invitation->id,
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'capture_channel' => InvitationJourney::CHANNEL_MANUAL,
            'raw_invitation_code' => $invitation->code,
            'active_coupon_version' => 'invitation',
            'active_coupon_id' => $invitation->id,
            'active_coupon_code' => $invitation->code,
            'captured_at' => now(),
            'registered_at' => now(),
            'last_seen_at' => now(),
        ]);

        $response = $this
            ->withSession([InvitationService::SESSION_KEY => $capturedJourney->journey_token])
            ->from(route('login_web'))
            ->post(route('post_login_web'), [
                'email' => $user->email,
                'password' => 'password123',
                'final_data' => '{}',
                'post_login_destination' => 'checkout',
            ]);

        $response->assertRedirect(route('checkout.show'));
        $response->assertSessionHas(InvitationService::SESSION_KEY, $registeredJourney->journey_token);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function web_login_redirects_to_checkout_page_when_checkout_destination_is_requested()
    {
        $user = User::factory()->create([
            'email' => 'checkout.login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('login_web'))->post(route('post_login_web'), [
            'email' => $user->email,
            'password' => 'password123',
            'final_data' => '{}',
            'post_login_destination' => 'checkout',
        ]);

        $response->assertRedirect(route('checkout.show'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_from_checkout_context_redirects_to_checkout_even_without_an_explicit_destination_flag()
    {
        $user = User::factory()->create([
            'email' => 'referrer.login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('checkout.show'))->post(route('post_login_web'), [
            'email' => $user->email,
            'password' => 'password123',
            'final_data' => '{}',
            'post_login_destination' => 'default',
        ]);

        $response->assertRedirect(route('checkout.show'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function web_login_keeps_the_default_fallback_when_checkout_destination_is_not_requested()
    {
        $user = User::factory()->create([
            'email' => 'dashboard.login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('login_web'))->post(route('post_login_web'), [
            'email' => $user->email,
            'password' => 'password123',
            'final_data' => '{}',
            'post_login_destination' => 'default',
        ]);

        $response->assertRedirect(route('user-dashboard.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function admin_login_still_redirects_to_dashboard_home_before_any_checkout_logic()
    {
        DB::table('roles')->insert([
            'id' => Role::ADMIN_ROLE,
            'name' => 'Admin',
            'description' => 'Admin role',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = User::factory()->create([
            'email' => 'admin.login@example.com',
            'password' => Hash::make('password123'),
        ]);

        DB::table('user_has_roles')->insert([
            'user_id' => $admin->id,
            'role_id' => Role::ADMIN_ROLE,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->from(route('login_web'))->post(route('post_login_web'), [
            'email' => $admin->email,
            'password' => 'password123',
            'final_data' => '{}',
            'post_login_destination' => 'checkout',
        ]);

        $response->assertRedirect(route('dashboard.home'));
        $this->assertAuthenticatedAs($admin);
    }
}
