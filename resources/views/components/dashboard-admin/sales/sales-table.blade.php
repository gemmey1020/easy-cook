<div class="container">

    <table class="table table-bordered mt-5">
        <thead>
        <tr>
            <th>#</th>
            <th>Client Name</th>
            <th>Subscription</th>
            <th>Status</th>
            <th>Phone Number</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Days per week</th>
            <th>price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $subscription->user->first_name . ' ' . $subscription->user->last_name }}</td>
                    <td>{{ $subscription->plan->name }}</td>
                    <td>{{ $subscription->status }}</td>
                    <td>{{ $subscription->user->phone }}</td>
                    <th>{{ $subscription->start_date }}</th>
                    <th>{{ $subscription->end_date }}</th>
                    <th>{{ $subscription->days_per_week }}</th>
                    <th>{{ $subscription->price }}</th>
                    <td>
                        <a href="{{ route('order-details', ['id' => $subscription->id]) }}">
                            <i class="fa fa-eye"></i>Show
                        </a>
                        |
                        <a href="{{ route('user-dashboard.subscription-invoice', [
                                'id' => $subscription->id,
                                'userId' => $subscription->user_id,
                            ]) }}">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $subscriptions->links('pagination::bootstrap-5') }}
    </div>
</div>
