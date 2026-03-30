@extends('layouts.master')

@section('title', 'Coupon V2')

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
                    <h3>Coupon V2</h3>
                    <p>Operate coupon records as real operator assets: understand who owns them, what relationship role they play, how much usage they have, and which linked invitation or checkout records matter.</p>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('discount-ops.index') }}" class="btn btn-outline-primary">Overview</a>
                    <a href="{{ route('discount-ops.partners') }}" class="btn btn-outline-secondary">Partners</a>
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

            @if($selectedPartner)
                <div class="discount-ops-inline-hint">
                    Partner focus is currently locked to <strong>{{ $selectedPartner->first_name }} {{ $selectedPartner->last_name }}</strong> (#{{ $selectedPartner->id }}).
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">Create Coupon V2</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('coupons-v2.store') }}" data-coupon-owner-form>
                        @csrf
                        <div class="row g-3 coupon-v2-form-grid">
                            <div class="col-lg-4 col-md-6 coupon-v2-field">
                                <label class="form-label">Code</label>
                                <input
                                    type="text"
                                    name="code"
                                    class="form-control"
                                    value="{{ old('code') }}"
                                    placeholder="Leave blank to auto-generate"
                                >
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
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-control">
                                    <option value="percent" @selected(old('discount_type', 'percent') === 'percent')>Percent</option>
                                    <option value="fixed" @selected(old('discount_type') === 'fixed')>Fixed</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Discount Value</label>
                                <input type="number" step="0.01" min="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}">
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Max Redemptions</label>
                                <input type="number" min="1" name="max_redemptions" class="form-control" value="{{ old('max_redemptions', 1) }}">
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Expires At</label>
                                <input
                                    type="datetime-local"
                                    name="expires_at"
                                    class="form-control"
                                    value="{{ old('expires_at', now()->addDay()->startOfDay()->format('Y-m-d\\TH:i')) }}"
                                    data-expires-at
                                >
                            </div>
                            <div class="col-lg-3 col-md-6 coupon-v2-field">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                    <option value="suspended" @selected(old('status') === 'suspended')>Suspended</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coupon-v2-toggle">
                                    <div class="form-check">
                                        <input type="checkbox" name="auto_generated" id="auto_generated" value="1" class="form-check-input" @checked(old('auto_generated'))>
                                        <label class="form-check-label" for="auto_generated">Auto generate code</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 coupon-v2-field">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="coupon-v2-actions">
                            <button type="submit" class="btn btn-primary">Create Coupon</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">All Coupon V2 Records</div>
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Search by code, then narrow by owner, relationship role, status, and usage health. The table should make coupon intent obvious even in a low-volume dataset.</p>
                        <div class="coupon-v2-filter-group">
                            <select id="coupon-v2-partner-focus" class="form-control">
                                <option value="">All partner ownership</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" @selected($selectedOwnerUserId === $partner->id)>
                                        {{ $partner->first_name }} {{ $partner->last_name }} (#{{ $partner->id }})
                                    </option>
                                @endforeach
                            </select>
                            <select id="coupon-v2-owner-filter" class="form-control">
                                <option value="">All owners</option>
                                <option value="system">System</option>
                                <option value="partner">Partner</option>
                            </select>
                            <select id="coupon-v2-status-filter" class="form-control">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="expired">Expired</option>
                                <option value="exhausted">Exhausted</option>
                            </select>
                            <select id="coupon-v2-role-filter" class="form-control">
                                <option value="">All relationship roles</option>
                                <option value="standalone">Standalone Coupon</option>
                                <option value="backing_invitation">Backing Invitation</option>
                                <option value="seen_as_override">Seen As Override</option>
                            </select>
                            <select id="coupon-v2-usage-filter" class="form-control">
                                <option value="">All usage states</option>
                                <option value="has_usage">Has usage</option>
                                <option value="no_usage">No usage</option>
                                <option value="expired_before_usage">Expired before usage</option>
                                <option value="expiring_soon">Expiring soon</option>
                                <option value="exhausted">Exhausted</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="coupon-v2-table" class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Role</th>
                                <th>Owner</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Usage Health</th>
                                <th>Attention</th>
                                <th>Expires At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($coupons as $coupon)
                                <tr
                                    data-status="{{ $coupon->derivedStatus() }}"
                                    data-owner-type="{{ $coupon->owner_type }}"
                                    data-role-keys="{{ $coupon->dashboard_role_keys }}"
                                    data-usage-keys="{{ $coupon->dashboard_usage_keys }}"
                                >
                                    <td>{{ $coupon->id }}</td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span><strong>{{ $coupon->code }}</strong></span>
                                            <small>{{ $coupon->dashboard_usage_story }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $coupon->dashboard_role_label }}</span>
                                            <small>Backing invitations {{ $coupon->backing_invitations_count ?? 0 }} · Override journeys {{ $coupon->override_invitation_journeys_count ?? 0 }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ ucfirst($coupon->owner_type) }}</span>
                                            @if($coupon->ownerUser)
                                                <small>
                                                    <a href="{{ route('users.edit', $coupon->ownerUser->id) }}">{{ $coupon->ownerUser->first_name }} {{ $coupon->ownerUser->last_name }} (#{{ $coupon->ownerUser->id }})</a>
                                                </small>
                                            @else
                                                <small>System-owned coupon</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon->discount_type === 'percent')
                                            {{ number_format($coupon->discount_value, 2) }}%
                                        @else
                                            {{ number_format($coupon->discount_value, 2) }} EGP
                                        @endif
                                    </td>
                                    <td>@include('dash.coupons-v2.partials.status-badge', ['coupon' => $coupon])</td>
                                    <td>
                                        @include('dash.coupons-v2.partials.usage-meter', ['coupon' => $coupon])
                                        <div class="discount-ops-pills mt-2">
                                            @foreach(array_filter(explode(',', (string) $coupon->dashboard_usage_keys)) as $flag)
                                                <span class="discount-ops-pill {{ in_array($flag, ['expiring_soon', 'expired_before_usage', 'exhausted'], true) ? 'discount-ops-pill--warning' : '' }}">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $coupon->dashboard_attention_label }}</span>
                                            <small>{{ $coupon->dashboard_attention_keys ? 'Follow-up recommended.' : 'No immediate attention flag.' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ optional($coupon->expires_at)->format('Y-m-d h:i A') }}</td>
                                    <td>
                                        <div class="coupon-v2-table-actions">
                                            <a href="{{ route('coupons-v2.show', $coupon) }}" class="btn btn-sm btn-primary">Open</a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn"
                                                data-copy-text="{{ $coupon->code }}"
                                                data-copy-label="Copy Code"
                                            >
                                                Copy Code
                                            </button>
                                            <form action="{{ route('coupons-v2.toggle-status', $coupon) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    {{ $coupon->status === 'active' ? 'Suspend' : 'Activate' }}
                                                </button>
                                            </form>
                                            @if($coupon->ownerUser)
                                                <a href="{{ route('coupons-v2.index', ['owner_user_id' => $coupon->ownerUser->id]) }}" class="btn btn-sm btn-outline-primary">Open Context</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="coupon-v2-empty-state">No Coupon V2 records yet.</div>
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
            var tableElement = $('#coupon-v2-table');

            if (!tableElement.length) {
                return;
            }

            var ownerFilter = $('#coupon-v2-owner-filter');
            var statusFilter = $('#coupon-v2-status-filter');
            var roleFilter = $('#coupon-v2-role-filter');
            var usageFilter = $('#coupon-v2-usage-filter');
            var partnerFocus = $('#coupon-v2-partner-focus');

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
                var rowRoles = rowNode.getAttribute('data-role-keys') || '';
                var rowUsage = rowNode.getAttribute('data-usage-keys') || '';

                if (ownerFilter.val() && rowOwner !== ownerFilter.val()) {
                    return false;
                }

                if (statusFilter.val() && rowStatus !== statusFilter.val()) {
                    return false;
                }

                if (roleFilter.val() && rowRoles.indexOf(roleFilter.val()) === -1) {
                    return false;
                }

                if (usageFilter.val() && rowUsage.indexOf(usageFilter.val()) === -1) {
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
                    info: 'Showing _START_ to _END_ of _TOTAL_ coupon records',
                    search: '',
                    searchPlaceholder: 'Search coupons...',
                    lengthMenu: 'Show _MENU_ records'
                }
            });

            ownerFilter.on('change', function() { table.draw(); });
            statusFilter.on('change', function() { table.draw(); });
            roleFilter.on('change', function() { table.draw(); });
            usageFilter.on('change', function() { table.draw(); });

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
