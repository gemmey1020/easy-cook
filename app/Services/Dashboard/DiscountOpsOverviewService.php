<?php

namespace App\Services\Dashboard;

use App\Models\CouponV2Coupon;
use App\Models\InvitationCode;
use App\Models\InvitationJourney;
use Illuminate\Support\Collection;

class DiscountOpsOverviewService
{
    public function __construct(
        private readonly DiscountOpsRelationshipQuery $relationshipQuery
    ) {
    }

    public function overview(): array
    {
        $invitations = $this->relationshipQuery->invitationIndex();
        $coupons = $this->relationshipQuery->couponIndex();

        $journeyFunnel = [
            InvitationJourney::STATE_CAPTURED => InvitationJourney::query()->where('state', InvitationJourney::STATE_CAPTURED)->count(),
            InvitationJourney::STATE_REGISTERED => InvitationJourney::query()->where('state', InvitationJourney::STATE_REGISTERED)->count(),
            InvitationJourney::STATE_CHECKOUT_STARTED => InvitationJourney::query()->where('state', InvitationJourney::STATE_CHECKOUT_STARTED)->count(),
            InvitationJourney::STATE_PAID => InvitationJourney::query()->where('state', InvitationJourney::STATE_PAID)->count(),
            InvitationJourney::STATE_INVALID_CODE => InvitationJourney::query()->where('state', InvitationJourney::STATE_INVALID_CODE)->count(),
        ];

        return [
            'systemHealth' => [
                [
                    'label' => 'Active Invitations',
                    'value' => $invitations->filter(fn (InvitationCode $code) => $code->derivedStatus() === InvitationCode::STATUS_ACTIVE)->count(),
                    'tone' => 'active',
                ],
                [
                    'label' => 'Active Coupons',
                    'value' => $coupons->filter(fn (CouponV2Coupon $coupon) => $coupon->derivedStatus() === CouponV2Coupon::STATUS_ACTIVE)->count(),
                    'tone' => 'active',
                ],
                [
                    'label' => 'Closed Windows',
                    'value' => $invitations->filter(fn (InvitationCode $code) => in_array($code->derivedStatus(), ['expired', 'exhausted'], true))->count()
                        + $coupons->filter(fn (CouponV2Coupon $coupon) => in_array($coupon->derivedStatus(), ['expired', 'exhausted'], true))->count(),
                    'tone' => 'warning',
                ],
                [
                    'label' => 'Suspended / Inactive',
                    'value' => $invitations->filter(fn (InvitationCode $code) => $code->derivedStatus() === InvitationCode::STATUS_INACTIVE)->count()
                        + $coupons->filter(fn (CouponV2Coupon $coupon) => $coupon->derivedStatus() === CouponV2Coupon::STATUS_SUSPENDED)->count(),
                    'tone' => 'muted',
                ],
            ],
            'journeyFunnel' => [
                [
                    'label' => 'Captured',
                    'value' => $journeyFunnel[InvitationJourney::STATE_CAPTURED],
                    'tone' => 'default',
                ],
                [
                    'label' => 'Registered',
                    'value' => $journeyFunnel[InvitationJourney::STATE_REGISTERED],
                    'tone' => 'default',
                ],
                [
                    'label' => 'Checkout Started',
                    'value' => $journeyFunnel[InvitationJourney::STATE_CHECKOUT_STARTED],
                    'tone' => 'default',
                ],
                [
                    'label' => 'Paid',
                    'value' => $journeyFunnel[InvitationJourney::STATE_PAID],
                    'tone' => 'active',
                ],
                [
                    'label' => 'Invalid',
                    'value' => $journeyFunnel[InvitationJourney::STATE_INVALID_CODE],
                    'tone' => 'warning',
                ],
            ],
            'relationshipHealth' => [
                [
                    'label' => 'Native Invitation Discounts',
                    'value' => $invitations->where('dashboard_source_type', 'native_invitation')->count(),
                ],
                [
                    'label' => 'Invitations Backed by Coupon V2',
                    'value' => $invitations->where('dashboard_source_type', 'backed_coupon_v2')->count(),
                ],
                [
                    'label' => 'Journeys With Active Override',
                    'value' => InvitationJourney::query()->whereNotNull('override_coupon_id')->count(),
                ],
                [
                    'label' => 'Coupons Acting as Backing / Override',
                    'value' => $coupons->filter(
                        fn (CouponV2Coupon $coupon) => str_contains((string) $coupon->dashboard_role_keys, 'backing_invitation')
                            || str_contains((string) $coupon->dashboard_role_keys, 'seen_as_override')
                    )->count(),
                ],
            ],
            'needsAttention' => [
                'statusDrift' => $this->statusDriftItems($invitations, $coupons),
                'exhausted' => $this->exhaustedItems($invitations, $coupons),
                'invalidAttempts' => $this->invalidAttemptItems($invitations),
                'staleJourneys' => $this->staleJourneyItems($invitations),
            ],
        ];
    }

    private function statusDriftItems(Collection $invitations, Collection $coupons): Collection
    {
        $invitationItems = $invitations
            ->filter(fn (InvitationCode $code) => str_contains((string) $code->dashboard_attention_keys, 'closed_while_active'))
            ->map(fn (InvitationCode $code) => $this->makeAttentionItem(
                title: $code->code,
                meta: 'Invitation is flagged active but currently ' . str_replace('_', ' ', $code->derivedStatus()) . '.',
                url: route('invitations.show', $code),
                surface: 'Invitation',
                copyText: $code->code,
                contextUrl: $code->ownerUser
                    ? route('invitations.index', ['owner_user_id' => $code->owner_user_id])
                    : null,
                toggleUrl: route('invitations.toggle-status', $code),
                toggleLabel: $code->status === InvitationCode::STATUS_ACTIVE ? 'Deactivate' : 'Activate',
            ));

        $couponItems = $coupons
            ->filter(fn (CouponV2Coupon $coupon) => $coupon->status === CouponV2Coupon::STATUS_ACTIVE && in_array($coupon->derivedStatus(), ['expired', 'exhausted'], true))
            ->map(fn (CouponV2Coupon $coupon) => $this->makeAttentionItem(
                title: $coupon->code,
                meta: 'Coupon is flagged active but currently ' . str_replace('_', ' ', $coupon->derivedStatus()) . '.',
                url: route('coupons-v2.show', $coupon),
                surface: 'Coupon',
                copyText: $coupon->code,
                contextUrl: $coupon->ownerUser
                    ? route('coupons-v2.index', ['owner_user_id' => $coupon->owner_user_id])
                    : null,
                toggleUrl: route('coupons-v2.toggle-status', $coupon),
                toggleLabel: $coupon->status === CouponV2Coupon::STATUS_ACTIVE ? 'Suspend' : 'Activate',
            ));

        return collect($invitationItems->all())
            ->merge($couponItems->all())
            ->take(6)
            ->values();
    }

    private function exhaustedItems(Collection $invitations, Collection $coupons): Collection
    {
        $invitationItems = $invitations
            ->filter(fn (InvitationCode $code) => $code->derivedStatus() === 'exhausted')
            ->map(fn (InvitationCode $code) => $this->makeAttentionItem(
                title: $code->code,
                meta: 'Invitation has no redemptions left.',
                url: route('invitations.show', $code),
                surface: 'Invitation',
                copyText: $code->code,
                contextUrl: $code->ownerUser
                    ? route('invitations.index', ['owner_user_id' => $code->owner_user_id])
                    : null,
            ));

        $couponItems = $coupons
            ->filter(fn (CouponV2Coupon $coupon) => $coupon->derivedStatus() === 'exhausted')
            ->map(fn (CouponV2Coupon $coupon) => $this->makeAttentionItem(
                title: $coupon->code,
                meta: 'Coupon has reached its paid usage limit.',
                url: route('coupons-v2.show', $coupon),
                surface: 'Coupon',
                copyText: $coupon->code,
                contextUrl: $coupon->ownerUser
                    ? route('coupons-v2.index', ['owner_user_id' => $coupon->owner_user_id])
                    : null,
            ));

        return collect($invitationItems->all())
            ->merge($couponItems->all())
            ->take(6)
            ->values();
    }

    private function invalidAttemptItems(Collection $invitations): Collection
    {
        return $invitations
            ->filter(fn (InvitationCode $code) => (int) ($code->invalid_attempt_count ?? 0) > 0)
            ->sortByDesc('invalid_attempt_count')
            ->take(6)
            ->map(fn (InvitationCode $code) => $this->makeAttentionItem(
                title: $code->code,
                meta: (int) $code->invalid_attempt_count . ' invalid attempt(s) tracked against this invitation.',
                url: route('invitations.show', $code),
                surface: 'Invitation',
                copyText: $code->code,
                contextUrl: $code->ownerUser
                    ? route('invitations.index', ['owner_user_id' => $code->owner_user_id])
                    : null,
            ))
            ->values();
    }

    private function staleJourneyItems(Collection $invitations): Collection
    {
        return $invitations
            ->filter(fn (InvitationCode $code) => (int) ($code->stale_open_count ?? 0) > 0)
            ->sortByDesc('stale_open_count')
            ->take(6)
            ->map(fn (InvitationCode $code) => $this->makeAttentionItem(
                title: $code->code,
                meta: (int) $code->stale_open_count . ' captured or registered journey(s) have been idle for more than '
                    . DiscountOpsRelationshipQuery::STALE_JOURNEY_DAYS . ' days.',
                url: route('invitations.show', $code),
                surface: 'Invitation',
                copyText: $code->code,
                contextUrl: $code->ownerUser
                    ? route('invitations.index', ['owner_user_id' => $code->owner_user_id])
                    : null,
            ))
            ->values();
    }

    private function makeAttentionItem(
        string $title,
        string $meta,
        string $url,
        string $surface,
        string $copyText,
        ?string $contextUrl = null,
        ?string $toggleUrl = null,
        ?string $toggleLabel = null,
    ): array {
        return [
            'title' => $title,
            'meta' => $meta,
            'url' => $url,
            'surface' => $surface,
            'copy_text' => $copyText,
            'context_url' => $contextUrl,
            'toggle_url' => $toggleUrl,
            'toggle_label' => $toggleLabel,
        ];
    }
}
