@extends('layouts.master')

@section('title', 'Coupon V2 Details')

@include('dash.discount-ops.partials.styles')
@include('dash.discount-ops.partials.common-scripts')

@section('content')
    <div class="coupon-v2-page">
        <div class="coupon-v2-shell">
            <div class="coupon-v2-header">
                <div class="coupon-v2-header-copy">
                    <span class="coupon-v2-kicker">Discount Ops</span>
                    <h3>Coupon V2: {{ $coupon->code }}</h3>
                    <div class="coupon-v2-header-stat">
                        @include('dash.coupons-v2.partials.status-badge', ['coupon' => $coupon])
                        <span class="coupon-v2-empty-copy">{{ $coupon->dashboard_role_label }}</span>
                        <strong>{{ $coupon->dashboard_usage_story }}</strong>
                    </div>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('discount-ops.index') }}" class="btn btn-outline-primary">Overview</a>
                    <a href="{{ route('coupons-v2.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button type="button" class="btn btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $coupon->code }}" data-copy-label="Copy Code">Copy Code</button>
                    @if($coupon->ownerUser)
                        <a href="{{ route('coupons-v2.index', ['owner_user_id' => $coupon->ownerUser->id]) }}" class="btn btn-outline-primary">Open Context</a>
                    @endif
                </div>
            </div>

            @include('dash.discount-ops.partials.nav', ['activeTab' => 'coupons'])

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
                                <span>{{ ucfirst($coupon->owner_type) }}</span>
                                <small>{{ $coupon->ownerUser ? 'Partner-owned coupon record.' : 'System-owned coupon record.' }}</small>
                            </div>
                            <div class="discount-ops-link-stack">
                                @if($coupon->ownerUser)
                                    <a href="{{ route('users.edit', $coupon->ownerUser->id) }}">Open owner user: {{ $coupon->ownerUser->first_name }} {{ $coupon->ownerUser->last_name }} (#{{ $coupon->ownerUser->id }})</a>
                                    <a href="{{ route('coupons-v2.index', ['owner_user_id' => $coupon->ownerUser->id]) }}">Open filtered coupon list</a>
                                @endif
                                <a href="{{ route('discount-ops.partners') }}">Open partner intelligence</a>
                            </div>
                            <div class="mt-4">
                                <strong>Expiry window</strong><br>
                                <small>{{ optional($coupon->expires_at)->format('Y-m-d h:i A') ?? 'No expiry configured' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">Role & Relationships</div>
                        <div class="card-body">
                            <div class="discount-ops-pills mb-3">
                                @foreach(array_filter(explode(',', (string) $coupon->dashboard_role_keys)) as $flag)
                                    <span class="discount-ops-pill">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                                @endforeach
                            </div>
                            <div class="coupon-v2-owner mb-3">
                                <span>{{ $coupon->dashboard_role_label }}</span>
                                <small>{{ $coupon->dashboard_usage_story }}</small>
                            </div>
                            <div class="coupon-v2-owner">
                                <small>Backing invitations: {{ $backingInvitations->count() }}</small>
                                <small>Override journeys: {{ $overrideJourneys->count() }}</small>
                                <small>Usage records: {{ $usages->count() }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">Usage Health</div>
                        <div class="card-body">
                            @include('dash.coupons-v2.partials.usage-meter', ['coupon' => $coupon])
                            <div class="discount-ops-pills mt-3">
                                @foreach(array_filter(explode(',', (string) $coupon->dashboard_usage_keys)) as $flag)
                                    <span class="discount-ops-pill {{ in_array($flag, ['expiring_soon', 'expired_before_usage', 'exhausted'], true) ? 'discount-ops-pill--warning' : '' }}">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                                @endforeach
                            </div>
                            <div class="coupon-v2-owner mt-3">
                                <span>{{ $coupon->dashboard_attention_label }}</span>
                                <small>{{ $coupon->paidUsersCount() }} paid user(s) out of {{ $coupon->max_redemptions }} max redemptions.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Edit Coupon V2</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('coupons-v2.update', $coupon) }}" data-coupon-owner-form>
                        @csrf
                        @method('PUT')

                        <div class="row g-3 coupon-v2-form-grid">
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Owner Type</label>
                                <select name="owner_type" class="form-control" data-coupon-owner-type>
                                    <option value="system" @selected(old('owner_type', $coupon->owner_type) === 'system')>System</option>
                                    <option value="partner" @selected(old('owner_type', $coupon->owner_type) === 'partner')>Partner</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field" data-partner-field>
                                <label class="form-label">Partner User</label>
                                <select name="owner_user_id" class="form-control" data-partner-select>
                                    <option value="">None</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}" @selected((string) old('owner_user_id', $coupon->owner_user_id) === (string) $partner->id)>
                                            {{ $partner->first_name }} {{ $partner->last_name }} (#{{ $partner->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-control">
                                    <option value="percent" @selected(old('discount_type', $coupon->discount_type) === 'percent')>Percent</option>
                                    <option value="fixed" @selected(old('discount_type', $coupon->discount_type) === 'fixed')>Fixed</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Value</label>
                                <input type="number" step="0.01" min="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}">
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Max Redemptions</label>
                                <input type="number" min="1" name="max_redemptions" class="form-control" value="{{ old('max_redemptions', $coupon->max_redemptions) }}">
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Expires At</label>
                                <input
                                    type="datetime-local"
                                    name="expires_at"
                                    class="form-control"
                                    value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\\TH:i') ?: now()->addDay()->startOfDay()->format('Y-m-d\\TH:i')) }}"
                                    data-expires-at
                                >
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" @selected(old('status', $coupon->status) === 'active')>Active</option>
                                    <option value="suspended" @selected(old('status', $coupon->status) === 'suspended')>Suspended</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coupon-v2-toggle">
                                    <div class="form-check">
                                        <input type="checkbox" name="auto_generated" id="auto_generated" value="1" class="form-check-input" @checked(old('auto_generated', $coupon->auto_generated))>
                                        <label class="form-check-label" for="auto_generated">Auto generate code</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 coupon-v2-field">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $coupon->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="coupon-v2-actions">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                    <form action="{{ route('coupons-v2.toggle-status', $coupon) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-warning">{{ $coupon->status === 'active' ? 'Suspend' : 'Activate' }}</button>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header">Invitations Backed By This Coupon</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                    <tr>
                                        <th>Invitation</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th>Journey Story</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($backingInvitations as $invitation)
                                        @php($backingStatus = $invitation->derivedStatus())
                                        @php($backingTone = match ($backingStatus) {
                                            'active' => 'active',
                                            'inactive' => 'suspended',
                                            'expired' => 'expired',
                                            'exhausted' => 'exhausted',
                                            default => 'suspended',
                                        })
                                        <tr>
                                            <td>
                                                <div class="coupon-v2-owner">
                                                    <span>{{ $invitation->code }}</span>
                                                    <small>{{ $invitation->dashboard_source_label }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($invitation->ownerUser)
                                                    <a href="{{ route('users.edit', $invitation->ownerUser->id) }}">{{ $invitation->ownerUser->first_name }} {{ $invitation->ownerUser->last_name }}</a>
                                                @else
                                                    System
                                                @endif
                                            </td>
                                            <td><span class="coupon-status coupon-status--{{ $backingTone }}">{{ ucfirst(str_replace('_', ' ', $backingStatus)) }}</span></td>
                                            <td>{{ $invitation->journeys_count }} journeys · Paid {{ $invitation->paid_count }}</td>
                                            <td><a href="{{ route('invitations.show', $invitation) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="discount-ops-empty-state">This coupon is not currently backing any invitation record.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header">Override Visibility</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                    <tr>
                                        <th>Journey</th>
                                        <th>Invitation</th>
                                        <th>User</th>
                                        <th>State</th>
                                        <th>Links</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($overrideJourneys as $journey)
                                        <tr>
                                            <td><code>{{ $journey->journey_token }}</code></td>
                                            <td>
                                                @if($journey->invitationCode)
                                                    <a href="{{ route('invitations.show', $journey->invitationCode) }}">{{ $journey->invitationCode->code }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($journey->user)
                                                    <a href="{{ route('users.edit', $journey->user->id) }}">{{ $journey->user->first_name }} {{ $journey->user->last_name }}</a>
                                                @else
                                                    Guest
                                                @endif
                                            </td>
                                            <td>{{ $journey->dashboard_state_label }}</td>
                                            <td>
                                                <div class="discount-ops-link-stack">
                                                    @if($journey->dashboard_primary_subscription_id)
                                                        <a href="{{ route('subscriptions.show', $journey->dashboard_primary_subscription_id) }}">Subscription #{{ $journey->dashboard_primary_subscription_id }}</a>
                                                        <a href="{{ route('order-details', $journey->dashboard_primary_subscription_id) }}">Order details</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="discount-ops-empty-state">No invitation journey is currently using this coupon as an override.</div>
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

            <div class="card">
                <div class="card-header">Usage Log</div>
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Use this ledger when you need the payment-side truth for a coupon: which user used it, which subscription it touched, and what totals were captured.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Subscription / Order</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Pricing</th>
                                <th>Consumed At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($usages as $usage)
                                <tr>
                                    <td>
                                        @if($usage->user)
                                            <div class="discount-ops-link-stack">
                                                <a href="{{ route('users.edit', $usage->user->id) }}">{{ $usage->user->first_name }} {{ $usage->user->last_name }}</a>
                                                <span>#{{ $usage->user->id }}</span>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="discount-ops-link-stack">
                                            <a href="{{ route('subscriptions.show', $usage->subscription_id) }}">Subscription #{{ $usage->subscription_id }}</a>
                                            <a href="{{ route('order-details', $usage->subscription_id) }}">Order details</a>
                                        </div>
                                    </td>
                                    <td>{{ $usage->payment_id ? '#'.$usage->payment_id : '-' }}</td>
                                    <td><span class="coupon-status coupon-status--{{ $usage->payment_status === 'paid' ? 'active' : ($usage->payment_status === 'failed' ? 'expired' : 'suspended') }}">{{ ucfirst(str_replace('_', ' ', $usage->payment_status)) }}</span></td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>Before {{ number_format($usage->total_before_discount, 2) }} EGP · Discount {{ number_format($usage->discount_amount, 2) }} EGP</span>
                                            <small>After {{ number_format($usage->total_after_discount, 2) }} EGP · VAT {{ number_format($usage->vat_amount, 2) }} EGP · Total {{ number_format($usage->grand_total, 2) }} EGP</small>
                                        </div>
                                    </td>
                                    <td>{{ optional($usage->consumed_at)->format('Y-m-d h:i A') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="coupon-v2-empty-state">No usage yet.</div>
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
