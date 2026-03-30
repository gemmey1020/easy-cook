<?php

namespace App\Http\Controllers;

use App\Models\InvitationJourney;
use App\Services\InvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {
    }

    public function capture(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:100'],
            'journey_token' => ['nullable', 'string', 'max:100'],
            'capture_channel' => ['nullable', Rule::in([
                InvitationJourney::CHANNEL_LINK,
                InvitationJourney::CHANNEL_MANUAL,
            ])],
            'referral_source' => ['nullable', Rule::in(InvitationJourney::referralSources())],
            'referral_source_note' => ['nullable', 'string', 'max:255'],
        ]);

        $journey = $this->invitationService->resolveJourney(
            $data['journey_token'] ?? null,
            auth()->user(),
            $request
        );

        $journey = $this->invitationService->capture(
            code: $data['code'],
            channel: $data['capture_channel'] ?? InvitationJourney::CHANNEL_MANUAL,
            referralSource: $data['referral_source'] ?? null,
            referralSourceNote: $data['referral_source_note'] ?? null,
            journey: $journey
        );

        $snapshot = $this->invitationService->buildSnapshot($journey);

        return response()->json([
            'success' => true,
            'message' => $snapshot['message'],
            'invitation' => $snapshot,
        ]);
    }

    public function forget(Request $request): JsonResponse
    {
        $data = $request->validate([
            'journey_token' => ['nullable', 'string', 'max:100'],
        ]);

        $this->invitationService->forgetActiveJourney($data['journey_token'] ?? null);

        return response()->json([
            'success' => true,
        ]);
    }
}
