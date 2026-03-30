@extends('layouts.master')

@section('title', 'Invitation Partner View')

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
                    <span class="coupon-v2-kicker">Owner View</span>
                    <h3>Partner Invitation Codes</h3>
                    <p>Review partner-owned invitation discounts, remaining cap, and journey truth from one grouped surface.</p>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('invitations.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="coupon-v2-filter-bar">
                        <p class="coupon-v2-filter-copy">Filter partner-owned invitation codes by active window or status.</p>
                        <div class="coupon-v2-filter-group">
                            <select id="partner-invitation-status-filter" class="form-control">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="expired">Expired</option>
                                <option value="exhausted">Exhausted</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="partner-invitation-table" class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th>Partner</th>
                                <th>Invitation Code</th>
                                <th>Discount</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Journeys</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($partnerInvitations as $code)
                                <tr data-status="{{ $code->derivedStatus() }}">
                                    <td>
                                        @if($code->ownerUser)
                                            {{ $code->ownerUser->first_name }} {{ $code->ownerUser->last_name }} (#{{ $code->ownerUser->id }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><strong>{{ $code->code }}</strong></td>
                                    <td>{{ $code->discountLabel() ?? 'Needs setup' }}</td>
                                    <td>
                                        @if($code->remainingRedemptions() !== null)
                                            {{ $code->remainingRedemptions() }} left / {{ $code->max_redemptions }}
                                        @else
                                            Open
                                        @endif
                                    </td>
                                    <td>
                                        <span class="coupon-status coupon-status--{{ $code->derivedStatus() === 'active' ? 'active' : ($code->derivedStatus() === 'inactive' ? 'suspended' : 'expired') }}">
                                            {{ ucfirst(str_replace('_', ' ', $code->derivedStatus())) }}
                                        </span>
                                    </td>
                                    <td>{{ $code->journeys_count }}</td>
                                    <td>
                                        <div class="coupon-v2-table-actions">
                                            <a href="{{ route('invitations.show', $code) }}" class="btn btn-sm btn-primary">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $code->code }}" data-copy-label="Copy">
                                                Copy
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="coupon-v2-empty-state">No partner invitation codes yet.</div>
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
            var tableElement = $('#partner-invitation-table');
            if (!tableElement.length) {
                return;
            }

            var statusFilter = $('#partner-invitation-status-filter');

            $.fn.dataTable.ext.search.push(function(settings, searchData, dataIndex) {
                if (settings.nTable !== tableElement.get(0)) {
                    return true;
                }

                var rowNode = settings.aoData[dataIndex] ? settings.aoData[dataIndex].nTr : null;
                var selectedStatus = statusFilter.val();

                if (!rowNode) {
                    return true;
                }

                if (selectedStatus && (rowNode.getAttribute('data-status') || '') !== selectedStatus) {
                    return false;
                }

                return true;
            });

            var table = tableElement.DataTable({
                pageLength: 25,
                order: [[1, 'asc']],
            });

            statusFilter.on('change', function() {
                table.draw();
            });
        });
    </script>
@endpush
