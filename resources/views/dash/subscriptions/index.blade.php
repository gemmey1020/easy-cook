@extends('layouts.master')

@section('title', 'Subscriptions')

@section('sidebar')
    @include('components.dashboard-admin.main.sidebar')
@endsection

@section('header')
    @include('components.dashboard-admin.main.header', ['title' => 'Subscriptions'])
@endsection
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <table id="subscription-table" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subscription Type</th>
                            <th>Weeks</th>
                            <th>Days Per Week</th>
                            <th>Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $sub)
                            <tr>
                                <td>{{ $sub->user->first_name }}</td>
                                <td>{{ @str_replace('_', ' ', $sub->subscription_type) }}</td>
                                <td>{{ $sub->weeks_count }}</td>
                                <td>{{ $sub->days_per_week }}</td>
                                <td>{{ $sub->price }} LE</td>
                                <td>
                                    {{ $sub->start_date }}
                                    @if (\Carbon\Carbon::parse($sub->start_date)->lte(now()))
                                        <span class="badge badge-success ml-2">Started</span>
                                    @else
                                        <span class="badge badge-warning ml-2">Upcoming</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $sub->end_date }}
                                    @if (\Carbon\Carbon::parse($sub->end_date)->lt(now()))
                                        <span class="badge badge-danger ml-2">Ended</span>
                                    @else
                                        <span class="badge badge-info ml-2">Ongoing</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($sub->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif ($sub->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($sub->status == 'canceled')
                                        <span class="badge badge-danger">Canceled</span>
                                    @elseif ($sub->status == 'completed')
                                        <span class="badge badge-info">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('subscriptions.show', $sub->id) }}" class="btn btn-primary btn-sm">View</a>
{{--                                    <form action="{{ route('subscriptions.destroy', $sub->id) }}" method="POST" style="display:inline;">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>--}}
{{--                                    </form>--}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashboard/plugins/table/datatable/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#subscription-table').DataTable({
                responsive: true,
                dom: `
                    <'dt--top-section'
                        <'row'
                            <'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>
                            <'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>
                        >
                    >
                    <'table-responsive'tr>
                    <'dt--bottom-section d-sm-flex justify-content-sm-between text-center'
                        <'dt--pages-count mb-sm-0 mb-3'i>
                        <'dt--pagination'p>
                    >
                `,
                language: {
                    paginate: {
                        first: '«',
                        previous: '‹',
                        next: '›',
                        last: '»'
                    },
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries'
                },
                lengthMenu: [7, 10, 20, 50],
                pageLength: 7,
                columnDefs: [{
                        targets: 0,
                        orderData: [0, 1]
                    },
                    {
                        targets: 1,
                        orderData: [1, 0]
                    },
                    {
                        targets: 4,
                        orderData: [4, 0]
                    }
                ]
            });
        });
    </script>
@endpush
