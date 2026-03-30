@extends('layouts.master')

@section('title', 'Discount Ops Partners')

@include('dash.discount-ops.partials.styles')
@include('dash.discount-ops.partials.common-scripts')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
    <div class="coupon-v2-page">
        <div class="coupon-v2-shell">
            <div class="coupon-v2-header">
                <div class="coupon-v2-header-copy">
                    <span class="coupon-v2-kicker">Unified Workspace</span>
                    <h3>Discount Ops Partners</h3>
                    <p>Read partner ownership as an operational story: what they own, what converted, what is active, and what still needs attention.</p>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('discount-ops.index') }}" class="btn btn-outline-secondary">Back to Overview</a>
                </div>
            </div>

            @include('dash.discount-ops.partials.nav', ['activeTab' => 'partners'])

            <div class="card">
                <div class="card-header">Partner Intelligence</div>
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Search by partner, email, or user ID. Open the linked invitation or coupon record from here when you need the next layer of detail.</p>
                    </div>

                    <div class="table-responsive">
                        <table id="discount-ops-partners-table" class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>Partner</th>
                                <th>Email / ID</th>
                                <th>Invitations</th>
                                <th>Coupons</th>
                                <th>Registered Journeys</th>
                                <th>Paid Conversions</th>
                                <th>Active Codes</th>
                                <th>Attention</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($partnerRows as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row->partner_name ?: 'Unknown partner' }}</strong>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-owner">
                                            <span>{{ $row->partner_email ?: 'No email on record' }}</span>
                                            <small>#{{ $row->partner_id }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $row->owned_invitations_count }}</td>
                                    <td>{{ $row->owned_coupons_count }}</td>
                                    <td>{{ $row->registered_journeys_count }}</td>
                                    <td>{{ $row->paid_conversions_count }}</td>
                                    <td>{{ $row->active_codes_count }}</td>
                                    <td>
                                        <span class="coupon-status {{ $row->items_needing_attention_count > 0 ? 'coupon-status--expired' : 'coupon-status--active' }}">
                                            {{ $row->items_needing_attention_count > 0 ? $row->items_needing_attention_count . ' needs attention' : 'Healthy' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="coupon-v2-table-actions">
                                            <a href="{{ $row->invitation_link }}" class="btn btn-sm btn-primary">Invitations</a>
                                            <a href="{{ $row->coupon_link }}" class="btn btn-sm btn-outline-primary">Coupons</a>
                                            <a href="{{ $row->user_link }}" class="btn btn-sm btn-outline-secondary">User</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="discount-ops-empty-state">No partner-owned invitation or coupon records are available yet.</div>
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
            var tableElement = $('#discount-ops-partners-table');

            if (!tableElement.length) {
                return;
            }

            tableElement.DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search partners...',
                    lengthMenu: 'Show _MENU_ partners',
                    info: 'Showing _START_ to _END_ of _TOTAL_ partners',
                    paginate: {
                        previous: 'Prev',
                        next: 'Next'
                    }
                }
            });
        });
    </script>
@endpush
