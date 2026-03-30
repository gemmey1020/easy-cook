<section class="my__account--section d-flex mb-5 flex-column p-0">
    <div class="container-fluid h-100 px-0">
        <p class="account__welcome--text fs-5 text-center py-3 mb-0 bg-light">
            Hello, {{ Auth::user()->first_name }} welcome to your dashboard!
        </p>
        <div class="d-flex">
            <aside class="sidebar">
                <h4 class="text-white mb-4">My Profile</h4>
                <ul class="nav flex-column" id="profile-tabs">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#profile">Profile</a></li>
                    <li class="nav-item orderhistory"><a class="nav-link" data-bs-toggle="tab" href="#order-history">Order
                            History</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                            href="#subscriptions">Subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#mealsTracking">Meals
                            Tracking</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                            href="#macronutrients">Macronutrients</a></li>
                    <li class="nav-item"><a class="nav-link bg-danger w-50 text-white" href="{{ route('logout') }}">Log
                            Out</a></li>
                </ul>
            </aside>
            <div class="tab-content main-content h-100">

                {{-- Dashboard --}}

                <div class="tab-pane  h-100" id="dashboard">
                    <div class="">
                        <h2 class="mb-4">Welcome, {{ Auth::user()->first_name }} 👋</h2>

                        <div class="dashboard-grid">

                            {{-- Profile --}}
                            <div class="dashboard-card">
                                <h5>👤 Profile</h5>
                                <p>
                                    Profile completion: <strong>44%</strong><br>
                                    Email: {{ Auth::user()->email }}<br>
                                    Phone: {{ Auth::user()->phone }}
                                </p>
                                <a href="#profile" class="btn btn-outline-success">View
                                    Profile</a>
                            </div>

                            {{-- Order History --}}
                            <div class="dashboard-card">
                                <h5>📦 Order History</h5>
                                <p>View and track your previous orders.</p>
                                <a href="#order-history" class="btn btn-outline-success">Go to
                                    Orders</a>
                            </div>

                            {{-- Subscriptions --}}
                            <div class="dashboard-card">
                                <h5>📋 My Subscriptions</h5>
                                <p>Active subscription: <strong>None</strong><br>Subscribe to a plan
                                    now!</p>
                                <a href="#subscriptions" class="btn btn-outline-success">View
                                    Subscriptions</a>
                            </div>

                            {{-- Meals Tracking --}}
                            <div class="dashboard-card">
                                <h5>🍽 Meals Tracking</h5>
                                <p>Track your daily meals and check their status.</p>
                                <a href="#mealsTracking" class="btn btn-outline-success">Track Meals</a>
                            </div>

                            {{-- Macronutrients --}}
                            <div class="dashboard-card">
                                <h5>🥗 Macronutrients</h5>
                                <p>
                                    Total Meals: 0<br>
                                    Completed: 0<br>
                                    Remaining: 0
                                </p>
                                <a href="#macronutrients" class="btn btn-outline-success">View
                                    Progress</a>
                            </div>

                        </div>
                    </div>


                </div>

                {{-- Profile --}}

                <div class="tab-pane fade h-100" id="profile">
                    <div class=" h-100 d-flex flex-column">

                        {{-- ✅ Top Row: Profile Completion + Edit Button --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="mb-1">Profile Completion</h4>
                                <small class="text-muted">Complete your profile to get the most out of
                                    Easy Cook.</small>
                            </div>
                            <button type="button" class="btn btn-outline-success" id="editProfileBtn">
                                Edit Profile
                            </button>
                        </div>

                        {{-- ✅ Progress Bar --}}
                        <div class="mb-4">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar" id="profileProgressBar"
                                    style="width: 0%;">
                                    <span id="profileProgressText">🟢 80% Complete</span>

                                </div>
                            </div>
                        </div>

                        {{-- ✅ Profile Info --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="firstName" placeholder="First Name"
                                    value="{{ Auth::user()->first_name ?? '' }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="lastName" placeholder="Last Name"
                                    value="{{ Auth::user()->last_name ?? '' }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="email" class="form-control" id="email" placeholder="Email Address"
                                    value="{{ Auth::user()->email ?? '' }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="tel" class="form-control" id="phone" placeholder="Phone number"
                                    value="{{ Auth::user()->phone ?? '' }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="text" class="form-control" id="company"
                                    placeholder="Your Company Name" value="{{ Auth::user()->company ?? '' }}"
                                    disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="text" class="form-control" id="street"
                                    placeholder="Street Address" value="{{ Auth::user()->street ?? '' }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="text" class="form-control" id="apartment"
                                    placeholder="Apartment, suite, unit etc. (optional)"
                                    value="{{ Auth::user()->apartment ?? '' }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="city" placeholder="Town / City"
                                    value="{{ Auth::user()->city ?? '' }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="postalCode"
                                    placeholder="Postal Code (e.g., 12345)"
                                    value="{{ Auth::user()->postal_code ?? '' }}" disabled>
                            </div>
                            {{-- ✅ Save Button (hidden initially) --}}
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-success d-none" id="saveProfileBtn">Save
                                    Changes
                                </button>
                            </div>

                        </div>

                        {{-- ✅ Addresses Section --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                            <h4 class="mb-0">Addresses</h4>
                            <button type="button" class="btn btn-success" id="addAddressBtn"
                                data-url="{{ route('addresses.store') }}">Add a
                                new address
                            </button>
                        </div>

                        <div id="addressList" class="flex-grow-1 overflow-auto">
                            @include('partials.addresses')
                        </div>
                    </div>
                </div>


                {{-- <div class="tab-pane fade h-100" id="addresses">
                                                    <div class=" h-100 d-flex flex-column">
                                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                                            <h4 class="mb-0">Addresses</h4>
                                                            <button class="btn btn-success" id="addAddressBtn">Add a new
                                                                address
                                                            </button>
                                                        </div>
                                                        <div id="addressList" class="flex-grow-1 overflow-auto">
                                                            @include('partials.addresses')
                                                        </div>
                                                    </div>
                                                </div> --}}
                {{-- @include('dash.partials.addresses') --}}

                {{-- Oreder History --}}

                <div class="tab-pane fade h-100" id="order-history">
                    <div class=" h-100 d-flex flex-column">
                        <h4 class="mb-4">Order History</h4>
                        <!-- From Uiverse.io by fthisilak -->
                        <a class="pay-btn" href="/plans">
                            <span class="btn-text">NEW ORDER</span>
                            <div class="icon-container">
                                <svg viewBox="0 0 24 24" class="icon card-icon">
                                    <path
                                        d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18C2,19.11 2.89,20 4,20H20C21.11,20 22,19.11 22,18V6C22,4.89 21.11,4 20,4Z"
                                        fill="currentColor"></path>
                                </svg>
                                <svg viewBox="0 0 24 24" class="icon payment-icon">
                                    <path
                                        d="M2,17H22V21H2V17M6.25,7H9V6H6V3H18V6H15V7H17.75L19,17H5L6.25,7M9,10H15V8H9V10M9,13H15V11H9V13Z"
                                        fill="currentColor"></path>
                                </svg>
                                <svg viewBox="0 0 24 24" class="icon dollar-icon">
                                    <path
                                        d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"
                                        fill="currentColor"></path>
                                </svg>

                                <svg viewBox="0 0 24 24" class="icon wallet-icon default-icon">
                                    <path
                                        d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                                        fill="currentColor"></path>
                                </svg>

                                <svg viewBox="0 0 24 24" class="icon check-icon">
                                    <path d="M9,16.17L4.83,12L3.41,13.41L9,19L21,7L19.59,5.59L9,16.17Z"
                                        fill="currentColor">
                                    </path>
                                </svg>
                            </div>
                        </a>

                        <div id="saved-meals2" class="flex-grow-1 overflow-auto mt-4">

                        </div>
                    </div>
                </div>

                @include('components.dashboard.partials.subscriptions')

                {{-- Meals Tracking --}}

                <div class="tab-pane fade h-100 " id="mealsTracking">
                    <div class=" h-100 d-flex flex-column">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="text-success mb-0">
                                <i class="fas fa-chart-line me-2"></i>Meals Tracking
                            </h2>

                        </div>

                        <!-- Stats Cards Row -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 id="totalMeals" class="mb-1">0</h3>
                                            <p class="mb-0">Total Meals</p>
                                        </div>
                                        <i class="fas fa-utensils fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 id="completedMeals" class="mb-1">0</h3>
                                            <p class="mb-0">Completed Meals</p>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 id="remainingMeals" class="mb-1">0</h3>
                                            <p class="mb-0">Remaining Meals</p>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Overall Progress</h5>
                                <span id="progressPercentage" class="text-success font-weight-bold">0%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div id="progressFill" class="progress-fill" style="width: 0%">
                                    <span class="progress-text">0%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <ul class="nav nav-pills justify-content-center" id="filterTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="day-tab" data-bs-toggle="pill"
                                        data-bs-target="#day" type="button" role="tab"
                                        onclick="filterMeals('day')">
                                        <i class="fas fa-calendar-day me-2"></i>Day
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="week-tab" data-bs-toggle="pill"
                                        data-bs-target="#week" type="button" role="tab"
                                        onclick="filterMeals('week')">
                                        <i class="fas fa-calendar-week me-2"></i>Week
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="month-tab" data-bs-toggle="pill"
                                        data-bs-target="#month" type="button" role="tab"
                                        onclick="filterMeals('month')">
                                        <i class="fas fa-calendar-alt me-2"></i>Month
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Meals Table -->
                        <div class="meals-table-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <span id="currentFilter">Today's</span> Meals
                                </h5>
                                <div class="d-flex align-items-center">
                                    <span class="me-3 text-muted" id="dateRange"></span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showCompleted" checked>
                                        <label class="form-check-label" for="showCompleted">
                                            Show Completed
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="loadingSpinner" class="loading-spinner" style="display: none;">
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table meals-table" id="mealsTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Meal</th>
                                            <th>Type</th>
                                            <th>Macros</th>
                                            <th>Calories</th>
                                            <th>Status</th>

                                        </tr>
                                    </thead>
                                    {{--                                                    Meals table body --}}
                                    <tbody id="mealsTableBody">
                                        <tr class="meal-row completed">
                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold">May 28, 2025</div>
                                                    <small class="text-muted">Wed</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <img src="https://images.unsplash.com/photo-1488477181946-6428a0291777?w=300&h=200&fit=crop"
                                                        alt="Greek Yogurt Parfait with Berries"
                                                        class="meal-image me-3">
                                                    <div class="text-start">
                                                        <div class="meal-name">Greek Yogurt Parfait
                                                            with Berries</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark">Breakfast</span>
                                            </td>
                                            <td>
                                                <div class="macros-container">
                                                    <div class="macro-item">
                                                        <div class="macro-value">20g</div>
                                                        <div class="macro-label">Pro</div>
                                                    </div>
                                                    <div class="macro-item">
                                                        <div class="macro-value">35g</div>
                                                        <div class="macro-label">Carb</div>
                                                    </div>
                                                    <div class="macro-item">
                                                        <div class="macro-value">8g</div>
                                                        <div class="macro-label">Fat</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-fire text-warning me-1"></i><strong>320</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-completed">
                                                    <i class="fas fa-check me-1"></i>Completed
                                                </span>
                                            </td>

                                        </tr>

                                        <tr class="meal-row">
                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold">May 28, 2025</div>
                                                    <small class="text-muted">Wed</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <img src="https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=300&h=200&fit=crop"
                                                        alt="Salmon with Quinoa and Vegetables"
                                                        class="meal-image me-3">
                                                    <div class="text-start">
                                                        <div class="meal-name">Salmon with Quinoa and
                                                            Vegetables</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark">Dinner</span>
                                            </td>
                                            <td>
                                                <div class="macros-container">
                                                    <div class="macro-item">
                                                        <div class="macro-value">42g</div>
                                                        <div class="macro-label">Pro</div>
                                                    </div>
                                                    <div class="macro-item">
                                                        <div class="macro-value">35g</div>
                                                        <div class="macro-label">Carb</div>
                                                    </div>
                                                    <div class="macro-item">
                                                        <div class="macro-value">22g</div>
                                                        <div class="macro-label">Fat</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-fire text-warning me-1"></i><strong>520</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-pending">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            </td>

                                        </tr>
                                    </tbody>

                                </table>
                            </div>

                            <div id="emptyState" class="empty-state" style="display: none;">
                                <i class="fas fa-utensils"></i>
                                <h4>No meals found</h4>
                                <p>No meals available for the selected time period.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- macronutrients  -->
                <div class="tab-pane fade h-100 " id="macronutrients">
                    <div class=" h-100 d-flex flex-column">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="text-success mb-0">
                                <i class="fas fa-chart-line me-2"></i>Macronutrients
                            </h2>
                            <button class="btn btn-outline-success" onclick="">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                        </div>

                        <!-- Stats Cards Row -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 id="totalMeals" class="mb-1">0</h3>
                                            <p class="mb-0">Total Meals</p>
                                        </div>
                                        <i class="fas fa-utensils fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 id="completedMeals" class="mb-1">0</h3>
                                            <p class="mb-0">Completed Meals</p>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 id="remainingMeals" class="mb-1">0</h3>
                                            <p class="mb-0">Remaining Meals</p>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Overall Progress</h5>
                                <span id="progressPercentage" class="text-success font-weight-bold">0%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div id="progressFill" class="progress-fill" style="width: 0%">
                                    <span class="progress-text">0%</span>
                                </div>
                            </div>
                        </div>


                        <div class="chart-container">

                            <!-- ================================
                               1. Donut Chart الأساسي
                          ================================= -->
                            <div class="donut-chart" id="myDonutChart">
                                <!-- ضع اللوجو في منتصف الدونت -->
                                <div class="donut-logo">
                                    <img src="{{ asset('assets/img/logo.png')}}" alt="Logo" />
                                </div>
                            </div>

                            <!-- ================================
                               2. قائمة الشرح (Legend)
                          ================================= -->
                            <ul class="legend" id="myLegend">
                                <li class="carbs">
                                    <span class="legend-color"></span>
                                    Carbs
                                    <span class="legend-perc">40%</span>
                                </li>
                                <li class="protein">
                                    <span class="legend-color"></span>
                                    Protein
                                    <span class="legend-perc">25%</span>
                                </li>
                                <li class="cal">
                                    <span class="legend-color"></span>
                                    Calories
                                     <span class="legend-perc">20%</span>
                                </li>
                                <li class="fat">
                                    <span class="legend-color"></span>
                                    Fat
                                    <span class="legend-perc">15%</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

</section>


<script>
    // ////////////////////////////////////////
    // Subscriptions Details
    // ////////////////////////////////////////

    // Function to open subscription details modal
    function openSubscriptionDetails() {
        $('#subscriptionModal').modal('show');
        // loadSubscriptionDetails(subscriptionId);
    }

    // End Subscription Details






</script>
