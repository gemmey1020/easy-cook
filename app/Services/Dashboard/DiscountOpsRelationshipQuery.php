<?php

namespace App\Services\Dashboard;

use App\Models\CouponV2Coupon;
use App\Models\CouponV2Usage;
use App\Models\InvitationCode;
use App\Models\InvitationJourney;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DiscountOpsRelationshipQuery
{
    public const STALE_JOURNEY_DAYS = 3;

    public function invitationIndex(?int $ownerUserId = null): Collection
    {
        return $this->baseInvitationQuery($ownerUserId)
            ->latest()
            ->get()
            ->map(fn (InvitationCode $code) => $this->decorateInvitation($code))
            ->values();
    }

    public function invitationDetail(InvitationCode $invitation): array
    {
        /** @var InvitationCode $invitation */
        $invitation = $this->baseInvitationQuery()
            ->whereKey($invitation->id)
            ->firstOrFail();

        $invitation->load([
            'ownerUser',
            'defaultCouponV2.ownerUser',
            'journeys' => fn ($query) => $query->latest('updated_at'),
            'journeys.user',
            'journeys.payment',
        ]);

        $invitation = $this->decorateInvitation($invitation);
        $journeys = $invitation->journeys
            ->map(fn (InvitationJourney $journey) => $this->decorateJourney($journey))
            ->values();

        return [
            'invitation' => $invitation,
            'journeys' => $journeys,
            'linkedCoupon' => $invitation->defaultCouponV2,
            'stateCounts' => $this->normalizeStateCounts($journeys),
            'invalidAttemptCount' => (int) $journeys->whereNotNull('invalid_recorded_at')->count(),
        ];
    }

    public function couponIndex(?int $ownerUserId = null): Collection
    {
        return $this->baseCouponQuery($ownerUserId)
            ->latest()
            ->get()
            ->map(fn (CouponV2Coupon $coupon) => $this->decorateCoupon($coupon))
            ->values();
    }

    public function couponDetail(CouponV2Coupon $coupon): array
    {
        /** @var CouponV2Coupon $coupon */
        $coupon = $this->baseCouponQuery()
            ->whereKey($coupon->id)
            ->firstOrFail();

        $coupon->load([
            'ownerUser',
            'backingInvitations.ownerUser',
            'overrideInvitationJourneys.user',
            'overrideInvitationJourneys.invitationCode',
            'overrideInvitationJourneys.payment',
            'usages.user',
            'usages.subscription',
            'usages.payment',
        ]);

        $coupon = $this->decorateCoupon($coupon);

        return [
            'coupon' => $coupon,
            'backingInvitations' => $coupon->backingInvitations
                ->map(fn (InvitationCode $invitation) => $this->decorateInvitation($invitation))
                ->values(),
            'overrideJourneys' => $coupon->overrideInvitationJourneys
                ->sortByDesc('updated_at')
                ->map(fn (InvitationJourney $journey) => $this->decorateJourney($journey))
                ->values(),
            'usages' => $coupon->usages
                ->sortByDesc(fn (CouponV2Usage $usage) => $usage->consumed_at ?? $usage->created_at)
                ->values(),
        ];
    }

    public function partnerRows(): Collection
    {
        $invitations = $this->invitationIndex()
            ->filter(fn (InvitationCode $code) => $code->owner_type === InvitationCode::OWNER_PARTNER);

        $coupons = $this->couponIndex()
            ->filter(fn (CouponV2Coupon $coupon) => $coupon->owner_type === CouponV2Coupon::OWNER_PARTNER);

        $partnerIds = $invitations->pluck('owner_user_id')
            ->merge($coupons->pluck('owner_user_id'))
            ->filter()
            ->unique()
            ->values();

        $partners = User::query()
            ->select(['id', 'first_name', 'last_name', 'email'])
            ->whereIn('id', $partnerIds)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->keyBy('id');

        return $partnerIds->map(function (int $partnerId) use ($partners, $invitations, $coupons) {
            /** @var User|null $partner */
            $partner = $partners->get($partnerId);
            $partnerInvitations = $invitations->where('owner_user_id', $partnerId)->values();
            $partnerCoupons = $coupons->where('owner_user_id', $partnerId)->values();

            $invitationAttention = $partnerInvitations->filter(
                fn (InvitationCode $invitation) => filled($invitation->dashboard_attention_keys)
            )->count();

            $couponAttention = $partnerCoupons->filter(
                fn (CouponV2Coupon $coupon) => filled($coupon->dashboard_attention_keys)
            )->count();

            return (object) [
                'partner' => $partner,
                'partner_id' => $partnerId,
                'partner_name' => trim(($partner?->first_name ?? '') . ' ' . ($partner?->last_name ?? '')),
                'partner_email' => $partner?->email,
                'owned_invitations_count' => $partnerInvitations->count(),
                'owned_coupons_count' => $partnerCoupons->count(),
                'registered_journeys_count' => (int) $partnerInvitations->sum('registered_count'),
                'paid_conversions_count' => (int) $partnerInvitations->sum('paid_count') + (int) $partnerCoupons->sum('paid_usage_count'),
                'active_codes_count' => $partnerInvitations->filter(
                    fn (InvitationCode $invitation) => $invitation->derivedStatus() === InvitationCode::STATUS_ACTIVE
                )->count() + $partnerCoupons->filter(
                    fn (CouponV2Coupon $coupon) => $coupon->derivedStatus() === CouponV2Coupon::STATUS_ACTIVE
                )->count(),
                'items_needing_attention_count' => $invitationAttention + $couponAttention,
                'invitation_link' => route('invitations.index', ['owner_user_id' => $partnerId]),
                'coupon_link' => route('coupons-v2.index', ['owner_user_id' => $partnerId]),
                'user_link' => route('users.edit', $partnerId),
            ];
        })->values();
    }

    public function homeLaunchpadSummary(): array
    {
        $invitations = $this->invitationIndex();
        $coupons = $this->couponIndex();

        return [
            'active_invitations' => $invitations->filter(
                fn (InvitationCode $code) => $code->derivedStatus() === InvitationCode::STATUS_ACTIVE
            )->count(),
            'active_coupons' => $coupons->filter(
                fn (CouponV2Coupon $coupon) => $coupon->derivedStatus() === CouponV2Coupon::STATUS_ACTIVE
            )->count(),
            'override_journeys' => InvitationJourney::query()
                ->whereNotNull('override_coupon_id')
                ->count(),
            'invalid_attempts' => InvitationJourney::query()
                ->whereNotNull('invalid_recorded_at')
                ->count(),
        ];
    }

    private function baseInvitationQuery(?int $ownerUserId = null): Builder
    {
        $staleBefore = now()->subDays(self::STALE_JOURNEY_DAYS);

        return InvitationCode::query()
            ->with(['ownerUser', 'defaultCouponV2.ownerUser'])
            ->when($ownerUserId !== null, fn (Builder $query) => $query->where('owner_user_id', $ownerUserId))
            ->withCount([
                'journeys',
                'journeys as registered_count' => fn ($query) => $query->where('state', InvitationJourney::STATE_REGISTERED),
                'journeys as checkout_started_count' => fn ($query) => $query->where('state', InvitationJourney::STATE_CHECKOUT_STARTED),
                'journeys as paid_count' => fn ($query) => $query->where('state', InvitationJourney::STATE_PAID),
                'journeys as invalid_code_count' => fn ($query) => $query->where('state', InvitationJourney::STATE_INVALID_CODE),
                'journeys as invalid_attempt_count' => fn ($query) => $query->whereNotNull('invalid_recorded_at'),
                'journeys as override_seen_count' => fn ($query) => $query->whereNotNull('override_coupon_id'),
                'journeys as stale_open_count' => fn ($query) => $query
                    ->whereIn('state', [
                        InvitationJourney::STATE_CAPTURED,
                        InvitationJourney::STATE_REGISTERED,
                    ])
                    ->where('last_seen_at', '<', $staleBefore),
            ]);
    }

    private function baseCouponQuery(?int $ownerUserId = null): Builder
    {
        return CouponV2Coupon::query()
            ->with('ownerUser')
            ->when($ownerUserId !== null, fn (Builder $query) => $query->where('owner_user_id', $ownerUserId))
            ->withCount([
                'usages',
                'usages as paid_users_count' => function ($query) {
                    $query
                        ->selectRaw('count(distinct user_id)')
                        ->where('payment_status', CouponV2Usage::STATUS_PAID);
                },
                'usages as paid_usage_count' => fn ($query) => $query->where('payment_status', CouponV2Usage::STATUS_PAID),
                'usages as pending_usage_count' => fn ($query) => $query->where('payment_status', CouponV2Usage::STATUS_PENDING_PAYMENT),
                'usages as failed_usage_count' => fn ($query) => $query->where('payment_status', CouponV2Usage::STATUS_FAILED),
                'backingInvitations',
                'overrideInvitationJourneys',
            ]);
    }

    private function decorateInvitation(InvitationCode $code): InvitationCode
    {
        $funnelKeys = [];

        if ($this->attributeInt($code, 'registered_count') > 0) {
            $funnelKeys[] = 'registered';
        }

        if ($this->attributeInt($code, 'checkout_started_count') > 0) {
            $funnelKeys[] = 'checkout_started';
        }

        if ($this->attributeInt($code, 'paid_count') > 0) {
            $funnelKeys[] = 'paid';
        }

        if ($this->attributeInt($code, 'invalid_code_count') > 0) {
            $funnelKeys[] = 'invalid';
        }

        if ($this->attributeInt($code, 'override_seen_count') > 0) {
            $funnelKeys[] = 'override_seen';
        }

        $attention = [];

        if ($code->dashboardDiscountSourceType() === 'missing_setup') {
            $attention[] = 'needs_setup';
        }

        if ($this->attributeInt($code, 'invalid_attempt_count') > 0) {
            $attention[] = 'invalid_attempts';
        }

        if ($this->attributeInt($code, 'override_seen_count') > 0) {
            $attention[] = 'override_seen';
        }

        if ($this->attributeInt($code, 'stale_open_count') > 0) {
            $attention[] = 'stale_open';
        }

        if (
            $code->status === InvitationCode::STATUS_ACTIVE
            && in_array($code->derivedStatus(), ['expired', 'exhausted'], true)
        ) {
            $attention[] = 'closed_while_active';
        }

        $code->setAttribute('dashboard_source_type', $code->dashboardDiscountSourceType());
        $code->setAttribute('dashboard_source_label', $code->dashboardDiscountSourceLabel());
        $code->setAttribute('dashboard_funnel_keys', implode(',', $funnelKeys));
        $code->setAttribute('dashboard_attention_keys', implode(',', $attention));
        $code->setAttribute('dashboard_attention_label', $this->summarizeFlags($attention, [
            'needs_setup' => 'Needs setup',
            'invalid_attempts' => 'Invalid attempts',
            'override_seen' => 'Override seen',
            'stale_open' => 'Stale journeys',
            'closed_while_active' => 'Expired or exhausted',
        ], 'Healthy'));

        return $code;
    }

    private function decorateCoupon(CouponV2Coupon $coupon): CouponV2Coupon
    {
        $roleTypes = $coupon->dashboardRoleTypes();
        $usageFlags = [];
        $attention = [];

        if ($this->attributeInt($coupon, 'usages_count') > 0) {
            $usageFlags[] = 'has_usage';
        } else {
            $usageFlags[] = 'no_usage';
        }

        if ($this->attributeInt($coupon, 'backing_invitations_count') > 0) {
            $usageFlags[] = 'backing_invitation';
        }

        if ($this->attributeInt($coupon, 'override_invitation_journeys_count') > 0) {
            $usageFlags[] = 'seen_as_override';
        }

        if ($coupon->derivedStatus() === 'expired' && $this->attributeInt($coupon, 'usages_count') === 0) {
            $usageFlags[] = 'expired_before_usage';
            $attention[] = 'expired_before_usage';
        }

        if ($coupon->derivedStatus() === 'exhausted') {
            $usageFlags[] = 'exhausted';
            $attention[] = 'exhausted';
        }

        if (
            $coupon->derivedStatus() === CouponV2Coupon::STATUS_ACTIVE
            && $coupon->expires_at !== null
            && now()->diffInDays($coupon->expires_at, false) <= 7
        ) {
            $usageFlags[] = 'expiring_soon';
            $attention[] = 'expiring_soon';
        }

        $coupon->setAttribute('dashboard_role_keys', implode(',', $roleTypes));
        $coupon->setAttribute('dashboard_role_label', collect($roleTypes)
            ->map(fn (string $role) => $coupon->dashboardRoleLabel($role))
            ->implode(' + '));
        $coupon->setAttribute('dashboard_usage_story', $coupon->dashboardUsageStory());
        $coupon->setAttribute('dashboard_usage_keys', implode(',', array_unique($usageFlags)));
        $coupon->setAttribute('dashboard_attention_keys', implode(',', array_unique($attention)));
        $coupon->setAttribute('dashboard_attention_label', $this->summarizeFlags(array_unique($attention), [
            'expired_before_usage' => 'Expired before usage',
            'exhausted' => 'Exhausted',
            'expiring_soon' => 'Expiring soon',
        ], 'Healthy'));

        return $coupon;
    }

    private function decorateJourney(InvitationJourney $journey): InvitationJourney
    {
        $journey->setAttribute('dashboard_state_label', $journey->dashboardStateLabel());
        $journey->setAttribute(
            'dashboard_discount_label',
            $journey->hasActiveOverride()
                ? 'Overridden by ' . $journey->active_coupon_code
                : ($journey->active_coupon_code ?: 'No active discount')
        );

        $journey->setAttribute(
            'dashboard_flow_label',
            $journey->hasActiveOverride() ? 'Overridden' : 'Invitation-led'
        );

        $primarySubscriptionId = null;

        if ($journey->payment !== null && is_array($journey->payment->subscription_ids) && $journey->payment->subscription_ids !== []) {
            $primarySubscriptionId = (int) $journey->payment->subscription_ids[0];
        }

        $journey->setAttribute('dashboard_primary_subscription_id', $primarySubscriptionId);

        return $journey;
    }

    private function normalizeStateCounts(Collection $journeys): array
    {
        $counts = [
            InvitationJourney::STATE_CAPTURED => 0,
            InvitationJourney::STATE_REGISTERED => 0,
            InvitationJourney::STATE_CHECKOUT_STARTED => 0,
            InvitationJourney::STATE_PAID => 0,
            InvitationJourney::STATE_INVALID_CODE => 0,
        ];

        foreach ($journeys->countBy('state') as $state => $total) {
            $counts[$state] = (int) $total;
        }

        return $counts;
    }

    private function attributeInt(object $model, string $key): int
    {
        return (int) ($model->{$key} ?? 0);
    }

    private function summarizeFlags(array $flags, array $labels, string $default): string
    {
        if ($flags === []) {
            return $default;
        }

        return collect($flags)
            ->map(fn (string $flag) => $labels[$flag] ?? ucfirst(str_replace('_', ' ', $flag)))
            ->take(2)
            ->implode(' · ');
    }
}
