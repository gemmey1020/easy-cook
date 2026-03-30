<div class="container-fluid p-4">
    <div class="section-divider">
        <h4 class="text-success mb-4">
            <i class="fas fa-utensils me-2"></i>Subscription Overview
        </h4>
        <div class="row align-items-center">
            <div class="col-md-4">
                <img src="{{ $subscription->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&h=400&fit=crop' }}"
                     class="img-fluid rounded shadow"
                     alt="{{ $subscription->title }}">
            </div>
            <div class="col-md-8">
                <div class="ps-md-4">
                    <h3 class="mb-3">{{ ucfirst(str_replace('_', ' ', $subscription->subscription_type )) }} </h3>
                    {{-- <p class="text-muted mb-4">{{ $subscription->description }}</p> --}}

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="track-meals-card p-3 rounded text-center">
                                <h5 class="text-success mb-1">{{ $progress['completed'] }}</h5>
                                <small class="text-muted">Completed Meals</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="track-meals-card p-3 rounded text-center">
                                <h5 class="text-success mb-1">{{ $progress['total'] - $progress['completed'] }}</h5>
                                <small class="text-muted">Remaining</small>
                            </div>
                        </div>
                    </div>

                    {{-- Macros --}}
                    <div class="macros d-flex justify-content-center flex-wrap gap-3 mt-3">
                        <div class="cal text-center">
                            <input class="form-control-plaintext fw-bold text-success text-center" value="{{ $macros['kcal'] ?? '0' }}" readonly>
                            <div class="small text-muted">kcal</div>
                        </div>
                        <div class="cal text-center">
                            <input class="form-control-plaintext fw-bold text-success text-center" value="{{ $macros['fat'] ?? '0' }}g" readonly>
                            <div class="small text-muted">fat</div>
                        </div>
                        <div class="cal text-center">
                            <input class="form-control-plaintext fw-bold text-success text-center" value="{{ $macros['protein'] ?? '0' }}g" readonly>
                            <div class="small text-muted">protein</div>
                        </div>
                        <div class="cal text-center">
                            <input class="form-control-plaintext fw-bold text-success text-center" value="{{ $macros['carb'] ?? '0' }}g" readonly>
                            <div class="small text-muted">carb</div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-4 p-2">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Progress</span>
                            <span class="text-success fw-bold">{{ $progress['percent'] }}%</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: {{ $progress['percent'] }}%">
                                <span class="percentage">{{ $progress['percent'] }}%</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Meal Explorer + Calendar --}}
    @include('components.dashboard.partials.meal-explorer', ['subscription' => $subscription , 'mealsData' => $mealsData])

    {{-- Meal Calendar --}}
    @include('components.dashboard.partials.meal-calender', ['subscription' => $subscription , 'mealsData' => $mealsData])
</div>
