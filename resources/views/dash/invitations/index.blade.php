@extends('layouts.master')

@section('title', 'Invitation Codes')

@include('dash.discount-ops.partials.styles')
@include('dash.discount-ops.partials.common-scripts')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
    @php
        $selectedPartner = $selectedOwnerUserId
            ? $partners->firstWhere('id', $selectedOwnerUserId)
            : null;
    @endphp

    <div class="coupon-v2-page">
        <div class="coupon-v2-shell">
            <div class="coupon-v2-header">
                <div class="coupon-v2-header-copy">
                    <span class="coupon-v2-kicker">Discount Ops</span>
                    <h3>Invitation Codes</h3>
                    <p>Operate invitation records as part of one diagnostic workspace. Read discount source, owner story, journey progress, and attention flags without digging through raw IDs.</p>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('discount-ops.index') }}" class="btn btn-outline-primary">Overview</a>
                    <a href="{{ route('discount-ops.partners') }}" class="btn btn-outline-secondary">Partners</a>
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

            @if($selectedPartner)
                <div class="discount-ops-inline-hint">
                    Partner focus is currently locked to <strong>{{ $selectedPartner->first_name }} {{ $selectedPartner->last_name }}</strong> (#{{ $selectedPartner->id }}). Change the partner filter below to widen or narrow the workspace.
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">Create Invitation Code</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invitations.store') }}" data-coupon-owner-form>
                        @csrf
                        <div class="row g-3 coupon-v2-form-grid">
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Leave blank to auto-generate">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Owner Type</label>
                                <select name="owner_type" class="form-control" data-coupon-owner-type>
                                    <option value="system" @selected(old('owner_type', 'system') === 'system')>System</option>
                                    <option value="partner" @selected(old('owner_type') === 'partner')>Partner</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field" data-partner-field>
                                <label class="form-label">Partner User</label>
                                <select name="owner_user_id" class="form-control" data-partner-select>
                                    <option value="">None</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}" @selected((string) old('owner_user_id') === (string) $partner->id)>
                                            {{ $partner->first_name }} {{ $partner->last_name }} (#{{ $partner->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Starts At</label>
                                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') ? \Illuminate\Support\Carbon::parse(old('starts_at'))->format('Y-m-d\\TH:i') : '' }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Expires At</label>
                                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') ? \Illuminate\Support\Carbon::parse(old('expires_at'))->format('Y-m-d\\TH:i') : now()->addDays(14)->format('Y-m-d\\TH:i') }}">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-control">
                                    <option value="percent" @selected(old('discount_type', 'percent') === 'percent')>Percent</option>
                                    <option value="fixed" @selected(old('discount_type') === 'fixed')>Fixed</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Value</label>
                                <input type="number" name="discount_value" min="0" step="0.01" class="form-control" value="{{ old('discount_value') }}" placeholder="10 or 150">
                            </div>
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Max Redemptions</label>
                                <input type="number" name="max_redemptions" min="1" step="1" class="form-control" value="{{ old('max_redemptions', 1) }}">
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="coupon-v2-toggle">
                                    <div class="form-check">
                                        <input type="checkbox" name="auto_generated" id="invitation_auto_generated" value="1" class="form-check-input" @checked(old('auto_generated'))>
                                        <label class="form-check-label" for="invitation_auto_generated">Auto generate code</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 coupon-v2-field">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="coupon-v2-actions">
                            <button type="submit" class="btn btn-primary">Create Invitation</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">All Invitation Records</div>
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Search by code, then narrow by ownership, discount source, funnel stage, or attention state. The table should tell the operational story immediately.</p>
                        <div class="coupon-v2-filter-group">
                            <select id="invitation-partner-focus" class="form-control">
                                <option value="">All partner ownership</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" @selected($selectedOwnerUserId === $partner->id)>
                                        {{ $partner->first_name }} {{ $partner->last_name }} (#{{ $partner->id }})
                                    </option>
                                @endforeach
                            </select>
                            <select id="invitation-owner-filter" class="form-control">
                                <option value="">All owners</option>
                                <option value="system">System</option>
                                <option value="partner">Partner</option>
                            </select>
                            <select id="invitation-status-filter" class="form-control">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="expired">Expired</option>
                                <option value="exhausted">Exhausted</option>
                            </select>
                            <select id="invitation-source-filter" class="form-control">
                                <option value="">All source types</option>
                                <option value="native_invitation">Native Invitation Discount</option>
                                <option value="backed_coupon_v2">Backed by Coupon V2</option>
                                <option value="missing_setup">Needs Setup</option>
                            </select>
                            <select id="invitation-funnel-filter" class="form-control">
                                <option value="">All funnel signals</option>
                                <option value="registered">Registered</option>
                                <option value="checkout_started">Checkout Started</option>
                                <option value="paid">Paid</option>
                                <option value="invalid">Invalid</option>
                                <option value="override_seen">Override Seen</option>
                            </select>
                            <select id="invitation-attention-filter" class="form-control">
                                <option value="">All attention states</option>
                                <option value="needs_setup">Needs Setup</option>
                                <option value="invalid_attempts">Invalid Attempts</option>
                                <option value="override_seen">Override Seen</option>
                                <option value="stale_open">Stale Journeys</option>
                                <option value="closed_while_active">Expired / Exhausted Drift</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="invitation-table" class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Discount Source</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Remaining</th>
                                <th>Journey Health</th>
                                <th>Attention</th>
                                <th>Window</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($codes as $code)
                                @php($derivedStatus = $code->derivedStatus())
                                @php($statusTone = match ($derivedStatus) {
                                    'active' => 'active',
                                    'inactive' => 'suspended',
                                    'expired' => 'expired',
                                    'exhausted' => 'exhausted',
                                    default => 'suspended',
                                })
                                <tr
                                    data-owner-type="{{ $code->owner_type }}"
                                    data-status="{{ $derivedStatus }}"
                                    data-source-type="{{ $code->dashboard_source_type }}"
                                    data-funnel-keys="{{ $code->dashboard_funnel_keys }}"
                                    data-attention-keys="{{ $code->dashboard_attention_keys }}"
                                >
                                    <td>{{ $code->id }}</td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span><strong>{{ $code->code }}</strong></span>
                                            <small>{{ $code->discountLabel() ?? 'No invitation discount configured yet' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $code->dashboard_source_label }}</span>
                                            @if($code->defaultCouponV2)
                                                <small>
                                                    <a href="{{ route('coupons-v2.show', $code->defaultCouponV2) }}">Linked coupon {{ $code->defaultCouponV2->code }}</a>
                                                </small>
                                            @else
                                                <small>{{ $code->dashboard_source_type === 'native_invitation' ? 'Invitation itself carries the discount.' : 'No linked coupon attached.' }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ ucfirst($code->owner_type) }}</span>
                                            @if($code->ownerUser)
                                                <small>
                                                    <a href="{{ route('users.edit', $code->ownerUser->id) }}">{{ $code->ownerUser->first_name }} {{ $code->ownerUser->last_name }} (#{{ $code->ownerUser->id }})</a>
                                                </small>
                                            @else
                                                <small>System-owned invitation</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="coupon-status coupon-status--{{ $statusTone }}">
                                            {{ ucfirst(str_replace('_', ' ', $derivedStatus)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($code->remainingRedemptions() !== null)
                                            {{ $code->remainingRedemptions() }} left / {{ $code->max_redemptions }}
                                        @else
                                            Open
                                        @endif
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $code->journeys_count }} journeys tracked</span>
                                            <small>Registered {{ $code->registered_count }} · Checkout {{ $code->checkout_started_count }} · Paid {{ $code->paid_count }} · Invalid {{ $code->invalid_code_count }}</small>
                                        </div>
                                        <div class="discount-ops-pills mt-2">
                                            @forelse(array_filter(explode(',', (string) $code->dashboard_funnel_keys)) as $flag)
                                                <span class="discount-ops-pill">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                                            @empty
                                                <span class="discount-ops-pill">No downstream signal yet</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $code->dashboard_attention_label }}</span>
                                            <small>{{ $code->dashboard_attention_keys ? 'Operator follow-up recommended.' : 'No immediate attention flag.' }}</small>
                                        </div>
                                        <div class="discount-ops-pills mt-2">
                                            @forelse(array_filter(explode(',', (string) $code->dashboard_attention_keys)) as $flag)
                                                <span class="discount-ops-pill discount-ops-pill--warning">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                                            @empty
                                                <span class="discount-ops-pill discount-ops-pill--success">Healthy</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        {{ optional($code->starts_at)->format('Y-m-d h:i A') ?? 'Now' }}<br>
                                        <small>{{ optional($code->expires_at)->format('Y-m-d h:i A') ?? 'Open end' }}</small>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-table-actions">
                                            <a href="{{ route('invitations.show', $code) }}" class="btn btn-sm btn-primary">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $code->code }}" data-copy-label="Copy Code">
                                                Copy Code
                                            </button>
                                            <form action="{{ route('invitations.toggle-status', $code) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    {{ $code->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            @if($code->ownerUser)
                                                <a href="{{ route('invitations.index', ['owner_user_id' => $code->ownerUser->id]) }}" class="btn btn-sm btn-outline-primary">Open Context</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="coupon-v2-empty-state">No invitation records yet.</div>
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

@push('scripts')
    <script src="{{ asset('assets/dashboard/plugins/table/datatable/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            var tableElement = $('#invitation-table');
            if (!tableElement.length) {
                return;
            }

            var ownerFilter = $('#invitation-owner-filter');
            var statusFilter = $('#invitation-status-filter');
            var sourceFilter = $('#invitation-source-filter');
            var funnelFilter = $('#invitation-funnel-filter');
            var attentionFilter = $('#invitation-attention-filter');
            var partnerFocus = $('#invitation-partner-focus');

            $.fn.dataTable.ext.search.push(function(settings, searchData, dataIndex) {
                if (settings.nTable !== tableElement.get(0)) {
                    return true;
                }

                var rowNode = settings.aoData[dataIndex] ? settings.aoData[dataIndex].nTr : null;
                if (!rowNode) {
                    return true;
                }

                var rowOwner = rowNode.getAttribute('data-owner-type') || '';
                var rowStatus = rowNode.getAttribute('data-status') || '';
                var rowSource = rowNode.getAttribute('data-source-type') || '';
                var rowFunnel = rowNode.getAttribute('data-funnel-keys') || '';
                var rowAttention = rowNode.getAttribute('data-attention-keys') || '';

                if (ownerFilter.val() && rowOwner !== ownerFilter.val()) {
                    return false;
                }

                if (statusFilter.val() && rowStatus !== statusFilter.val()) {
                    return false;
                }

                if (sourceFilter.val() && rowSource !== sourceFilter.val()) {
                    return false;
                }

                if (funnelFilter.val() && rowFunnel.indexOf(funnelFilter.val()) === -1) {
                    return false;
                }

                if (attentionFilter.val() && rowAttention.indexOf(attentionFilter.val()) === -1) {
                    return false;
                }

                return true;
            });

            var table = tableElement.DataTable({
                responsive: true,
                autoWidth: false,
                order: [],
                dom: "<'dt--top-section'<'row'<'col-12 col-lg-5 d-flex justify-content-lg-start justify-content-center'l><'col-12 col-lg-7 d-flex justify-content-lg-end justify-content-center mt-lg-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                language: {
                    paginate: {
                        first: '«',
                        previous: '‹',
                        next: '›',
                        last: '»'
                    },
                    info: 'Showing _START_ to _END_ of _TOTAL_ invitation records',
                    search: '',
                    searchPlaceholder: 'Search invitations...',
                    lengthMenu: 'Show _MENU_ records'
                }
            });

            ownerFilter.on('change', function() { table.draw(); });
            statusFilter.on('change', function() { table.draw(); });
            sourceFilter.on('change', function() { table.draw(); });
            funnelFilter.on('change', function() { table.draw(); });
            attentionFilter.on('change', function() { table.draw(); });

            partnerFocus.on('change', function() {
                var selected = partnerFocus.val();
                var target = new URL(window.location.href);

                if (selected) {
                    target.searchParams.set('owner_user_id', selected);
                } else {
                    target.searchParams.delete('owner_user_id');
                }

                window.location.href = target.toString();
            });
        });
    </script>
@endpush
