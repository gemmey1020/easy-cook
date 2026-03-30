@extends('layouts.user-dashboard')

@section('title', 'Dashboard')

@section('user-dashboard-content')


<div class="tab-pane  h-100" id="dashboard">
    <div class="">
        <h2 class="mb-4">Welcome, {{ Auth::user()->first_name }} 👋</h2>

        <div class="dashboard-grid">
            {{-- NOTE:All these are same compoenent structure --}}
            {{-- Profile --}}
            <!-- From Uiverse.io by abrahamcalsin -->
            <div class="dashboard-card">
                <h5>👤 Profile</h5>
                <p>
                    <!-- Profile completion: <span id="profileProgressText2">44%</span><br> -->
                    Email: {{ Auth::user()->email }}<br>
                    Phone: {{ Auth::user()->phone }}
                </p>
                <div class="social-media">
                    <a href="{{ route('user-dashboard.profile') }}" class="btn btn-outline-light">View
                        Profile</a>
                </div>
            </div>

            {{-- Order History --}}
            <div class="dashboard-card">
                <h5>📦 Order History</h5>
                <p>View and track your previous orders.</p>
                <a href="{{ route('user-dashboard.orders') }}" class="btn btn-outline-light">Go to
                    Orders</a>
            </div>

            {{-- Subscriptions --}}
            <div class="dashboard-card">
                <h5>📋 My Subscriptions</h5>
                <p>Active subscription: <strong>None</strong><br>Subscribe to a plan
                    now!</p>
                <a href="{{ route('user-dashboard.subscriptions') }}" class="btn btn-outline-light">View
                    Subscriptions</a>
            </div>

            {{-- Meals Tracking --}}
            <div class="dashboard-card">
                <h5>🍽️ Meals Tracking</h5>
                <p>Track your daily meals and check their status.</p>
                <a href="{{ route('user-dashboard.meals.tracking') }}" class="btn btn-outline-light">Track Meals</a>
            </div>

            {{-- Macronutrients --}}
            <!-- <div class="dashboard-card">
                <h5>🥗 Macronutrients</h5>
                <p>
                    Total Meals: 0<br>
                    Completed: 0<br>
                    Remaining: 0
                </p>
                <a href="{{ route('user-dashboard.macronutrients') }}" class="btn btn-outline-light">View
                    Progress</a>
            </div> -->

        </div>
    </div>


</div>


@endsection

@push('scripts')
    <script src="{{ asset('assets/js/dashboard/profile.js') }}"></script>
@endpush
