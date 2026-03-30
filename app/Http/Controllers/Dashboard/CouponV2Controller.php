<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CouponV2Coupon;
use App\Models\User;
use App\Services\Dashboard\DiscountOpsRelationshipQuery;
use App\Services\CouponV2Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CouponV2Controller extends Controller
{
    public function __construct(
        private readonly CouponV2Service $couponV2Service,
        private readonly DiscountOpsRelationshipQuery $relationshipQuery
    ) {
    }

    public function index(Request $request): View
    {
        $ownerUserId = $request->integer('owner_user_id') ?: null;

        return view('dash.coupons-v2.index', [
            'coupons' => $this->relationshipQuery->couponIndex($ownerUserId),
            'partners' => $this->partnersQuery()->get(),
            'selectedOwnerUserId' => $ownerUserId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPayload($request);
        $codePayload = $this->couponV2Service->prepareCode(
            $data['code'] ?? null,
            (bool) ($data['auto_generated'] ?? false),
            $data['owner_type']
        );
        $this->couponV2Service->assertNoCodeCollision($codePayload['code']);

        CouponV2Coupon::create(array_merge($data, $codePayload));

        return redirect()
            ->route('coupons-v2.index')
            ->with('success', 'Coupon V2 created successfully.');
    }

    public function show(CouponV2Coupon $couponV2): View
    {
        $detail = $this->relationshipQuery->couponDetail($couponV2);

        return view('dash.coupons-v2.show', [
            'coupon' => $detail['coupon'],
            'backingInvitations' => $detail['backingInvitations'],
            'overrideJourneys' => $detail['overrideJourneys'],
            'usages' => $detail['usages'],
            'partners' => $this->partnersQuery()->get(),
        ]);
    }

    public function update(Request $request, CouponV2Coupon $couponV2): RedirectResponse
    {
        $data = $this->validatedPayload($request);
        $codePayload = $this->couponV2Service->prepareCode(
            $data['code'] ?? $couponV2->code,
            (bool) ($data['auto_generated'] ?? false),
            $data['owner_type']
        );
        $this->couponV2Service->assertNoCodeCollision($codePayload['code'], $couponV2->id);

        $couponV2->update(array_merge($data, $codePayload));

        return redirect()
            ->route('coupons-v2.show', $couponV2)
            ->with('success', 'Coupon V2 updated successfully.');
    }

    public function toggleStatus(CouponV2Coupon $couponV2): RedirectResponse
    {
        $couponV2->update([
            'status' => $couponV2->status === CouponV2Coupon::STATUS_ACTIVE
                ? CouponV2Coupon::STATUS_SUSPENDED
                : CouponV2Coupon::STATUS_ACTIVE,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Coupon status updated.');
    }

    public function partners(): RedirectResponse
    {
        return redirect()->route('discount-ops.partners');
    }

    private function validatedPayload(Request $request): array
    {
        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:100'],
            'owner_type' => ['required', Rule::in([
                CouponV2Coupon::OWNER_SYSTEM,
                CouponV2Coupon::OWNER_PARTNER,
            ])],
            'owner_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'discount_type' => ['required', Rule::in([
                CouponV2Coupon::TYPE_PERCENT,
                CouponV2Coupon::TYPE_FIXED,
            ])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'max_redemptions' => ['required', 'integer', 'min:1'],
            'expires_at' => ['required', 'date', 'after:now'],
            'status' => ['required', Rule::in([
                CouponV2Coupon::STATUS_ACTIVE,
                CouponV2Coupon::STATUS_SUSPENDED,
            ])],
            'auto_generated' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['owner_type'] === CouponV2Coupon::OWNER_PARTNER && empty($data['owner_user_id'])) {
            throw ValidationException::withMessages([
                'owner_user_id' => ['A partner coupon must be linked to a user.'],
            ]);
        }

        if ($data['owner_type'] === CouponV2Coupon::OWNER_SYSTEM) {
            $data['owner_user_id'] = null;
        }

        if (
            $data['discount_type'] === CouponV2Coupon::TYPE_PERCENT
            && (float) $data['discount_value'] > 100
        ) {
            throw ValidationException::withMessages([
                'discount_value' => ['Percent discount cannot exceed 100.'],
            ]);
        }

        $data['auto_generated'] = (bool) ($data['auto_generated'] ?? false);

        return $data;
    }

    private function partnersQuery()
    {
        return User::query()
            ->select(['id', 'first_name', 'last_name', 'email'])
            ->orderBy('first_name')
            ->orderBy('last_name');
    }

}
