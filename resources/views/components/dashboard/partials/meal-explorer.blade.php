<div class="section-divider">
    <h4 class="text-success mb-4">
        <i class="fas fa-filter me-2"></i>Meal Explorer
    </h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom" id="filterBreadcrumb">
            <li class="breadcrumb-item active">Select Week</li>
        </ol>
    </nav>
    <div class="filter-container" id="filterContainer">
        <div class="filter-level" id="weekFilter">
            <h6><i class="fas fa-calendar-week me-2"></i>Choose Week:</h6>
            <div class="d-flex flex-wrap">
                <span class="filter-pill" onclick="selectWeek(1)">Week 1</span>
                <span class="filter-pill" onclick="selectWeek(2)">Week 2</span>
                <span class="filter-pill" onclick="selectWeek(3)">Week 3</span>
                <span class="filter-pill" onclick="selectWeek(4)">Week 4</span>
            </div>
        </div>
{{--                            {{ $subscription->DailySelectedMeals }}--}}

        <div class="filter-level d-none" id="dayFilter">
            <h6><i class="fas fa-calendar-day me-2"></i>Choose Day:</h6>
            <button class="back-btn me-2" onclick="backToWeeks()">← Back to Weeks</button>
            <div class="d-flex flex-wrap" id="dayOptions"></div>
        </div>
        <div class="filter-level d-none" id="mealTypeFilter">
            <h6><i class="fas fa-utensils me-2"></i>Choose Meal Type:</h6>
            <button class="back-btn me-2" onclick="backToDays()">← Back to Days</button>
            <div class="d-flex flex-wrap">
                <span class="filter-pill" onclick="selectMealType('breakfast')">Breakfast</span>
                <span class="filter-pill" onclick="selectMealType('snack1')">Snack 1</span>
                <span class="filter-pill" onclick="selectMealType('lunch')">Lunch</span>
                <span class="filter-pill" onclick="selectMealType('snack2')">Snack 2</span>
                <span class="filter-pill" onclick="selectMealType('dinner')">Dinner</span>
            </div>
        </div>
    </div>
    <div id="mealsDisplay">
        <div class="empty-state">
            <i class="fas fa-utensils"></i>
            <h5>Select filters above to view meals</h5>
            <p>Choose a week, then a day, then a meal type to see your planned meals.</p>
        </div>
    </div>
</div>
{{--<script>--}}
{{--    console.log('MAnuka loaded from Blade.');--}}
{{--</script>--}}



<script>

    let meals = @json($mealsData);

    initializeMealExplorer(meals);


</script>
<script src="{{ asset('assets/js/dashboard/mealExplorer.js') }}"></script>
<script>
    console.log('Meal Explorer script loaded from Blade.');
</script>


{{--@push('scripts')--}}{{-- @endpush--}}
{{--    <script src="{{ asset('assets/js/dashboard/mealExplorer.js') }}"></script>--}}
{{--    <script>--}}
{{--        console.log('Script loaded from Blade.');--}}
{{--    </script>--}}
{{--@endpush--}}
