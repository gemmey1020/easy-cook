@extends('layouts.master')

@section('title', 'Partner Coupons')

@include('dash.discount-ops.partials.styles')
@include('dash.discount-ops.partials.common-scripts')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
    <div class="coupon-v2-page">
        <div class="coupon-v2-shell">
            <div class="coupon-v2-header">
                <div class="coupon-v2-header-copy">
                    <span class="coupon-v2-kicker">Partner Visibility</span>
                    <h3>Partner Coupons</h3>
                    <p>Review every partner-owned Coupon V2 record in one searchable, mobile-friendly table.</p>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('coupons-v2.index') }}" class="btn btn-outline-secondary">Back to Coupons</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">All Partner Coupon Records</div>
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Search by partner, email, ID, or coupon code, then filter by derived status.</p>
                        <div class="coupon-v2-filter-group">
                            <select id="partner-coupon-status-filter" class="form-control">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="expired">Expired</option>
                                <option value="exhausted">Exhausted</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="partner-coupon-table" class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>Partner</th>
                                <th>Email / ID</th>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Usage</th>
                                <th>Expires At</th>
                                <th>Copy</th>
                                <th>Open</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($partnerCoupons as $coupon)
                                @php($partner = $coupon->ownerUser)
                                <tr data-status="{{ $coupon->derivedStatus() }}">
                                    <td>{{ $partner?->first_name }} {{ $partner?->last_name }}</td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $partner?->email ?? 'Unknown partner' }}</span>
                                            <small>#{{ $partner?->id ?? $coupon->owner_user_id }}</small>
                                        </div>
                                    </td>
                                    <td><strong>{{ $coupon->code }}</strong></td>
                                    <td>
                                        @if($coupon->discount_type === 'percent')
                                            {{ number_format($coupon->discount_value, 2) }}%
                                        @else
                                            {{ number_format($coupon->discount_value, 2) }} EGP
                                        @endif
                                    </td>
                                    <td>@include('dash.coupons-v2.partials.status-badge', ['coupon' => $coupon])</td>
                                    <td>@include('dash.coupons-v2.partials.usage-meter', ['coupon' => $coupon])</td>
                                    <td>{{ optional($coupon->expires_at)->format('Y-m-d h:i A') }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn"
                                            data-copy-text="{{ $coupon->code }}"
                                            data-copy-label="Copy"
                                        >
                                            Copy
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ route('coupons-v2.show', $coupon) }}" class="btn btn-sm btn-primary">Open</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="coupon-v2-empty-state">No partner coupons found yet.</div>
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
            var tableElement = $('#partner-coupon-table');

            if (!tableElement.length) {
                return;
            }

            var statusFilter = $('#partner-coupon-status-filter');

            $.fn.dataTable.ext.search.push(function(settings, searchData, dataIndex) {
                if (settings.nTable !== tableElement.get(0)) {
                    return true;
                }

                var rowNode = settings.aoData[dataIndex] ? settings.aoData[dataIndex].nTr : null;
                var selectedStatus = statusFilter.val();

                if (!rowNode || !selectedStatus) {
                    return true;
                }

                return (rowNode.getAttribute('data-status') || '') === selectedStatus;
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
                    search: '',
                    searchPlaceholder: 'Search partners or coupons...',
                    lengthMenu: 'Show _MENU_ partner coupons',
                    info: 'Showing _START_ to _END_ of _TOTAL_ partner coupons',
                    emptyTable: 'No partner coupons found yet.'
                },
                lengthMenu: [7, 10, 20, 50],
                pageLength: 10,
                columnDefs: [
                    { targets: 0, responsivePriority: 1 },
                    { targets: 1, responsivePriority: 5 },
                    { targets: 2, responsivePriority: 2 },
                    { targets: 3, responsivePriority: 6 },
                    { targets: 4, responsivePriority: 3 },
                    { targets: 5, responsivePriority: 4 },
                    { targets: 6, responsivePriority: 7 },
                    { targets: 7, responsivePriority: 8, orderable: false, searchable: false },
                    { targets: 8, responsivePriority: 1, orderable: false, searchable: false }
                ]
            });

            statusFilter.on('change', function() {
                table.draw();
            });

            table.on('draw', function() {
                window.CouponV2Admin.bindCopyButtons(tableElement.get(0));
            });

            window.CouponV2Admin.bindCopyButtons(tableElement.get(0));
        });
    </script>
@endpush
