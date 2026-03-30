@if($subscription)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Client Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Client Name:</strong> {{ $subscription->user->first_name . ' ' . $subscription->user->last_name }}</p>
                    <p><strong>Subscription:</strong> {{ $subscription->plan->name }}</p>
                    <p><strong>Status:</strong> {{ $subscription->status }}</p>
                    <p><strong>Phone Number:</strong> {{ $subscription->user->phone }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Start Date:</strong> {{ $subscription->start_date }}</p>
                    <p><strong>End Date:</strong> {{ $subscription->end_date }}</p>
                    <p><strong>Days per week:</strong> {{ $subscription->days_per_week }}</p>
                    <p><strong>Price:</strong> {{ $subscription->price }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
