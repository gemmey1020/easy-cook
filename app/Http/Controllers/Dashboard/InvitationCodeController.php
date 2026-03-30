<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use App\Models\User;
use App\Services\Dashboard\DiscountOpsRelationshipQuery;
use App\Services\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InvitationCodeController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService,
        private readonly DiscountOpsRelationshipQuery $relationshipQuery
    ) {
    }

    public function index(Request $request): View
    {
        $ownerUserId = $request->integer('owner_user_id') ?: null;

        return view('dash.invitations.index', [
            'codes' => $this->relationshipQuery->invitationIndex($ownerUserId),
            'partners' => $this->partnersQuery()->get(),
            'selectedOwnerUserId' => $ownerUserId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPayload($request);
        $code = blank($data['code'] ?? null)
            || (bool) ($data['auto_generated'] ?? false)
            ? $this->invitationService->generateUniqueCode($data['owner_type'])
            : InvitationCode::normalizeCode($data['code']);

        $this->assertNoCollision($code);

        InvitationCode::create(array_merge($data, [
            'code' => $code,
            'code_normalized' => InvitationCode::normalizeCode($code),
        ]));

        return redirect()
            ->route('invitations.index')
            ->with('success', 'Invitation code created successfully.');
    }

    public function show(InvitationCode $invitation): View
    {
        $detail = $this->relationshipQuery->invitationDetail($invitation);

        return view('dash.invitations.show', [
            'invitation' => $detail['invitation'],
            'journeys' => $detail['journeys'],
            'linkedCoupon' => $detail['linkedCoupon'],
            'partners' => $this->partnersQuery()->get(),
            'stateCounts' => $detail['stateCounts'],
            'invalidAttemptCount' => $detail['invalidAttemptCount'],
        ]);
    }

    public function update(Request $request, InvitationCode $invitation): RedirectResponse
    {
        $data = $this->validatedPayload($request);
        $code = blank($data['code'] ?? null)
            || (bool) ($data['auto_generated'] ?? false)
            ? $invitation->code
            : InvitationCode::normalizeCode($data['code']);

        $this->assertNoCollision($code, $invitation->id);

        $invitation->update(array_merge($data, [
            'code' => $code,
            'code_normalized' => InvitationCode::normalizeCode($code),
        ]));

        return redirect()
            ->route('invitations.show', $invitation)
            ->with('success', 'Invitation code updated successfully.');
    }

    public function toggleStatus(InvitationCode $invitation): RedirectResponse
    {
        $invitation->update([
            'status' => $invitation->status === InvitationCode::STATUS_ACTIVE
                ? InvitationCode::STATUS_INACTIVE
                : InvitationCode::STATUS_ACTIVE,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Invitation code status updated.');
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
                InvitationCode::OWNER_SYSTEM,
                InvitationCode::OWNER_PARTNER,
            ])],
            'owner_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'discount_type' => ['required', Rule::in([
                InvitationCode::TYPE_PERCENT,
                InvitationCode::TYPE_FIXED,
            ])],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'max_redemptions' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in([
                InvitationCode::STATUS_ACTIVE,
                InvitationCode::STATUS_INACTIVE,
            ])],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'auto_generated' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['owner_type'] === InvitationCode::OWNER_PARTNER && empty($data['owner_user_id'])) {
            throw ValidationException::withMessages([
                'owner_user_id' => ['A partner invitation code must be linked to a user.'],
            ]);
        }

        if ($data['owner_type'] === InvitationCode::OWNER_SYSTEM) {
            $data['owner_user_id'] = null;
        }

        if (
            $data['discount_type'] === InvitationCode::TYPE_PERCENT
            && (float) $data['discount_value'] > 100
        ) {
            throw ValidationException::withMessages([
                'discount_value' => ['Percent invitation discounts must not be greater than 100.'],
            ]);
        }

        $data['auto_generated'] = (bool) ($data['auto_generated'] ?? false);

        return $data;
    }

    private function assertNoCollision(string $code, ?int $ignoreId = null): void
    {
        $query = InvitationCode::query()->where('code_normalized', InvitationCode::normalizeCode($code));

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'code' => ['This invitation code already exists.'],
            ]);
        }
    }

    private function partnersQuery()
    {
        return User::query()
            ->select(['id', 'first_name', 'last_name', 'email'])
            ->orderBy('first_name')
            ->orderBy('last_name');
    }

}
