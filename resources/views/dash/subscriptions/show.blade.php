@extends('layouts.master')

@section('title', 'Subscription Details')

@section('sidebar')
    @include('components.dashboard-admin.main.sidebar')
@endsection

@section('header')
    @include('components.dashboard-admin.main.header', ['title' => 'Subscriptions Details'])
@endsection

@section('content')
    <div class="row layout-top-spacing">
        <!-- Subscription Details -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Subscription Details - ID: {{ $subscription->id }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Information -->
                            <div class="col-md-6">
                                <h6 class="text-muted">User Information</h6>
                                <p><strong>Name:</strong> {{ $subscription->user->first_name }}
                                    {{ $subscription->user->last_name }}</p>
                                <p><strong>User ID:</strong> {{ $subscription->user->id }}</p>
                            </div>

                            <!-- Plan Information -->
                            <div class="col-md-6">
                                <h6 class="text-muted">Plan Information</h6>
                                <p><strong>Plan Name:</strong> {{ $subscription->plan->name }}</p>
                                <p><strong>Description:</strong> {{ $subscription->plan->description }}</p>
                                <p><strong>Plan ID:</strong> {{ $subscription->plan->id }}</p>
                            </div>

                            <!-- Subscription Details -->
                            <div class="col-md-6 mt-4">
                                <h6 class="text-muted">Subscription Details</h6>
                                <p><strong>Subscription Type:</strong> {{ ucfirst($subscription->subscription_type) }}</p>
                                <p><strong>Calorie Range:</strong> {{ $subscription->calorie_range ?? 'N/A' }}</p>
                                <p><strong>Weeks Count:</strong> {{ $subscription->weeks_count }}</p>
                                <p><strong>Days Per Week:</strong> {{ $subscription->days_per_week }}</p>
                                <p><strong>Excluded Days:</strong> {{ $subscription->excluded_days ?? 'N/A' }}</p>
                                <p><strong>Price:</strong> ${{ $subscription->price }}</p>
                                <p><strong>Total Meals:</strong> {{ $subscription->total_meals ?? 'N/A' }}</p>
                                <p><strong>Status:</strong>
                                    @if ($subscription->status == 'active')
                                        <span class="badge badge-success">{{ ucfirst($subscription->status) }}</span>
                                    @elseif ($subscription->status == 'pending')
                                        <span class="badge badge-warning">{{ ucfirst($subscription->status) }}</span>
                                    @elseif ($subscription->status == 'canceled')
                                        <span class="badge badge-danger">{{ ucfirst($subscription->status) }}</span>
                                    @elseif ($subscription->status == 'completed')
                                        <span class="badge badge-info">{{ ucfirst($subscription->status) }}</span>
                                    @else
                                        <span class="badge badge-secondary">Unknown</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6 mt-4">
                                <h6 class="text-muted">Dates</h6>
                                <p>
                                    <strong>Start Date:</strong> {{ $subscription->start_date }}
                                    @if (\Carbon\Carbon::parse($subscription->start_date)->lte(now()))
                                        <span class="badge badge-success ml-2">Started</span>
                                    @else
                                        <span class="badge badge-warning ml-2">Upcoming</span>
                                    @endif
                                </p>
                                <p>
                                    <strong>End Date:</strong> {{ $subscription->end_date }}
                                    @if (\Carbon\Carbon::parse($subscription->end_date)->lt(now()))
                                        <span class="badge badge-danger ml-2">Ended</span>
                                    @else
                                        <span class="badge badge-info ml-2">Ongoing</span>
                                    @endif
                                </p>
                                <p><strong>Created At:</strong>
                                    {{ \Carbon\Carbon::parse($subscription->created_at)->format('d-m-Y H:i:s') }}</p>
                                <p><strong>Updated At:</strong>
                                    {{ \Carbon\Carbon::parse($subscription->updated_at)->format('d-m-Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Meals -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Daily Meals</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($subscription->dailyMeals as $meal)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6>{{ ucfirst($meal->meal_type) }}: {{ $meal->meal->title }}</h6>
                                            <p><strong>Meal ID:</strong> {{ $meal->id }}</p>
                                            <p><strong>Subscription ID:</strong> {{ $meal->subscription_id }}</p>
                                            <p><strong>Day ID:</strong> {{ $meal->day_id }}</p>
                                            <p><strong>Meal ID (Nested):</strong> {{ $meal->meal_id }}</p>
                                            <p><strong>Calorie Level:</strong> {{ $meal->meal->calorie_level }}</p>
                                            <p><strong>Description:</strong> {{ $meal->meal->description }}</p>

                                            @if ($meal->meal->image)
                                                <img src="{{ asset($meal->meal->image) }}" alt="{{ $meal->meal->title }}"
                                                    class="img-fluid mb-2" style="max-height: 150px;">
                                            @else
                                                <p class="text-muted">No image available</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
