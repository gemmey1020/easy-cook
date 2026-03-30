@extends('layouts.master')

@section('title', 'Invitation Code Details')

@include('dash.discount-ops.partials.styles')
@include('dash.discount-ops.partials.common-scripts')

@section('content')
    @php
        $derivedStatus = $invitation->derivedStatus();
        $statusTone = match ($derivedStatus) {
            'active' => 'active',
            'inactive' => 'suspended',
            'expired' => 'expired',
            'exhausted' => 'exhausted',
            default => 'suspended',
        };
    @endphp

    <div class="coupon-v2-page">
        <div class="coupon-v2-shell">
            <div class="coupon-v2-header">
                <div class="coupon-v2-header-copy">
                    <span class="coupon-v2-kicker">Discount Ops</span>
                    <h3>Invitation Code: {{ $invitation->code }}</h3>
                    <div class="coupon-v2-header-stat">
                        <span class="coupon-status coupon-status--{{ $statusTone }}">
                            {{ ucfirst(str_replace('_', ' ', $derivedStatus)) }}
                        </span>
                        <span class="coupon-v2-empty-copy">{{ $invitation->dashboard_source_label }}</span>
                        <strong>{{ $journeys->count() }} journeys</strong>
                    </div>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('discount-ops.index') }}" class="btn btn-outline-primary">Overview</a>
                    <a href="{{ route('invitations.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button type="button" class="btn btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $invitation->code }}" data-copy-label="Copy Code">Copy Code</button>
                    @if($invitation->ownerUser)
                        <a href="{{ route('invitations.index', ['owner_user_id' => $invitation->ownerUser->id]) }}" class="btn btn-outline-primary">Open Context</a>
                    @endif
                </div>
            </div>

            @include('dash.discount-ops.partials.nav', ['activeTab' => 'invitations'])

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">Owner & Navigation</div>
                        <div class="card-body">
                            <div class="coupon-v2-owner mb-3">
                                <span>{{ ucfirst($invitation->owner_type) }}</span>
                                <small>{{ $invitation->ownerUser ? 'Partner-owned invitation record.' : 'System-owned invitation record.' }}</small>
                            </div>
                            <div class="discount-ops-link-stack">
                                @if($invitation->ownerUser)
                                    <a href="{{ route('users.edit', $invitation->ownerUser->id) }}">Open owner user: {{ $invitation->ownerUser->first_name }} {{ $invitation->ownerUser->last_name }} (#{{ $invitation->ownerUser->id }})</a>
                                    <a href="{{ route('invitations.index', ['owner_user_id' => $invitation->ownerUser->id]) }}">Open filtered invitation list</a>
                                @endif
                                <a href="{{ route('discount-ops.partners') }}">Open partner intelligence</a>
                            </div>
                            <div class="mt-4">
                                <strong>Validity window</strong><br>
                                <small>{{ optional($invitation->starts_at)->format('Y-m-d h:i A') ?? 'Starts immediately' }}</small><br>
                                <small>{{ optional($invitation->expires_at)->format('Y-m-d h:i A') ?? 'No expiry configured' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">Discount Source</div>
                        <div class="card-body">
                            <div class="coupon-v2-owner mb-3">
                                <span>{{ $invitation->dashboard_source_label }}</span>
                                <small>{{ $invitation->discountLabel() ?? 'No usable invitation discount configured yet.' }}</small>
                            </div>
                            @if($linkedCoupon)
                                <div class="discount-ops-link-stack">
                                    <a href="{{ route('coupons-v2.show', $linkedCoupon) }}">Open linked coupon {{ $linkedCoupon->code }}</a>
                                    @if($linkedCoupon->ownerUser)
                                        <a href="{{ route('users.edit', $linkedCoupon->ownerUser->id) }}">Open coupon owner user</a>
                                    @endif
                                </div>
                            @else
                                <div class="discount-ops-empty-state">This invitation is not backed by Coupon V2. The invitation record itself carries the discount truth.</div>
                            @endif
                            <div class="mt-4">
                                <strong>Override visibility</strong><br>
                                <small>{{ (int) ($invitation->override_seen_count ?? 0) }} journey(s) have seen another coupon override.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">Funnel & Attention</div>
                        <div class="card-body">
                            <div class="discount-ops-pills mb-3">
                                <span class="discount-ops-pill">Captured {{ $stateCounts['captured'] ?? 0 }}</span>
                                <span class="discount-ops-pill">Registered {{ $stateCounts['registered'] ?? 0 }}</span>
                                <span class="discount-ops-pill">Checkout {{ $stateCounts['checkout_started'] ?? 0 }}</span>
                                <span class="discount-ops-pill">Paid {{ $stateCounts['paid'] ?? 0 }}</span>
                                <span class="discount-ops-pill discount-ops-pill--warning">Invalid {{ $stateCounts['invalid_code'] ?? 0 }}</span>
                            </div>
                            <div class="coupon-v2-owner mb-3">
                                <span>{{ $invitation->dashboard_attention_label }}</span>
                                <small>{{ $invalidAttemptCount }} invalid attempt(s) recorded across this invitation.</small>
                            </div>
                            <div class="discount-ops-pills">
                                @forelse(array_filter(explode(',', (string) $invitation->dashboard_attention_keys)) as $flag)
                                    <span class="discount-ops-pill discount-ops-pill--warning">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                                @empty
                                    <span class="discount-ops-pill discount-ops-pill--success">Healthy</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Edit Invitation Code</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invitations.update', $invitation) }}" data-coupon-owner-form>
                        @csrf
                        @method('PUT')

                        <div class="row g-3 coupon-v2-form-grid">
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', $invitation->code) }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Owner Type</label>
                                <select name="owner_type" class="form-control" data-coupon-owner-type>
                                    <option value="system" @selected(old('owner_type', $invitation->owner_type) === 'system')>System</option>
                                    <option value="partner" @selected(old('owner_type', $invitation->owner_type) === 'partner')>Partner</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field" data-partner-field>
                                <label class="form-label">Partner User</label>
                                <select name="owner_user_id" class="form-control" data-partner-select>
                                    <option value="">None</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}" @selected((string) old('owner_user_id', $invitation->owner_user_id) === (string) $partner->id)>
                                            {{ $partner->first_name }} {{ $partner->last_name }} (#{{ $partner->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Starts At</label>
                                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', optional($invitation->starts_at)->format('Y-m-d\\TH:i')) }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Expires At</label>
                                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', optional($invitation->expires_at)->format('Y-m-d\\TH:i')) }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" @selected(old('status', $invitation->status) === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $invitation->status) === 'inactive')>Inactive</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-control">
                                    <option value="percent" @selected(old('discount_type', $invitation->discount_type) === 'percent')>Percent</option>
                                    <option value="fixed" @selected(old('discount_type', $invitation->discount_type) === 'fixed')>Fixed</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Value</label>
                                <input type="number" name="discount_value" min="0" step="0.01" class="form-control" value="{{ old('discount_value', $invitation->discount_value) }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Max Redemptions</label>
                                <input type="number" name="max_redemptions" min="1" step="1" class="form-control" value="{{ old('max_redemptions', $invitation->max_redemptions) }}">
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="coupon-v2-toggle">
                                    <div class="form-check">
                                        <input type="checkbox" name="auto_generated" id="invitation_auto_generated_detail" value="1" class="form-check-input" @checked(old('auto_generated', $invitation->auto_generated))>
                                        <label class="form-check-label" for="invitation_auto_generated_detail">Auto generate code</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 coupon-v2-field">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invitation->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="coupon-v2-actions">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                    <form action="{{ route('invitations.toggle-status', $invitation) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-warning">{{ $invitation->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Journey Log</div>
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Read each journey as an operator story: who owns it, where it stopped, which discount is active, and which linked records you can open next.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>Journey</th>
                                <th>User</th>
                                <th>State</th>
                                <th>Discount Story</th>
                                <th>Deep Links</th>
                                <th>Last Seen</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($journeys as $journey)
                                @php($journeyStateTone = match ($journey->state) {
                                    'paid' => 'active',
                                    'invalid_code' => 'expired',
                                    'checkout_started' => 'suspended',
                                    default => 'suspended',
                                })
                                <tr>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span><code>{{ $journey->journey_token }}</code></span>
                                            <small>{{ $journey->referral_source ?? 'No referral source' }}{{ $journey->referral_source_note ? ' · ' . $journey->referral_source_note : '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($journey->user)
                                            <div class="discount-ops-link-stack">
                                                <a href="{{ route('users.edit', $journey->user->id) }}">{{ $journey->user->first_name }} {{ $journey->user->last_name }} (#{{ $journey->user->id }})</a>
                                            </div>
                                        @else
                                            <span class="coupon-v2-empty-copy">Guest / not linked yet</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span class="coupon-status coupon-status--{{ $journeyStateTone }}">{{ $journey->dashboard_state_label }}</span>
                                            @if($journey->last_invalid_code)
                                                <small>Last invalid code: {{ $journey->last_invalid_code }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $journey->dashboard_discount_label }}</span>
                                            <small>{{ $journey->dashboard_flow_label }}</small>
                                        </div>
                                        @if($journey->hasActiveOverride() && $journey->override_coupon_version === 'v2' && $journey->override_coupon_id)
                                            <div class="discount-ops-link-stack mt-2">
                                                <a href="{{ route('coupons-v2.show', $journey->override_coupon_id) }}">Open override coupon {{ $journey->override_coupon_code }}</a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="discount-ops-link-stack">
                                            @if($journey->dashboard_primary_subscription_id)
                                                <a href="{{ route('subscriptions.show', $journey->dashboard_primary_subscription_id) }}">Subscription #{{ $journey->dashboard_primary_subscription_id }}</a>
                                                <a href="{{ route('order-details', $journey->dashboard_primary_subscription_id) }}">Order details</a>
                                            @endif
                                            @if($journey->payment_id)
                                                <span>Payment #{{ $journey->payment_id }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ optional($journey->last_seen_at)->format('Y-m-d h:i A') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="coupon-v2-empty-state">No journeys yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
