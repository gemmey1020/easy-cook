@extends('layouts.user-dashboard')

@section('title', 'Dashboard')

@section('user-dashboard-content')


<div class="tab-pane h-100 " id="macronutrients">
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
                                    {{-- <script src="{{ asset('assets/js/dashboard/profile.js') }}"></script> --}}
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

@endsection
@push('scripts')
    <script src="{{ asset('assets/js/dashboard/macronutrients.js') }}"></script>
{{-- /opt/lampp/htdocs/2nd_epoche/10_2_2025_easy/public/assets/js/dashboard/.js --}}

@endpush
