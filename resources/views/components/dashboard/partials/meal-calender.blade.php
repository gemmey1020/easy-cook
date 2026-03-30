@php
    // --- Configuration ---
    date_default_timezone_set('UTC');

    // --- Date and Time Initialization ---
    $currentDate = new DateTime();
    $monthName = $currentDate->format('F');
    $year = $currentDate->format('Y');

    // --- 1. REAL MEAL DATA (from your controller) ---
    // This is the data structure you provided.

//    $mealsData = [
//        1 => [ // Week 1
//            1 => [ // Day 1 (Monday)
//                'breakfast' => ['id' => 1, 'name' => "Greek Yogurt Parfait", 'image' => "https://images.unsplash.com/photo-1488477181946-6428a0291777?w=60&h=60&fit=crop"],
//                'snack1' => ['id' => 2, 'name' => "Mixed Nuts", 'image' => "https://images.unsplash.com/photo-1599599810694-57a2ca8276a8?w=60&h=60&fit=crop"],
//                'lunch' => ['id' => 3, 'name' => "Grilled Chicken Salad", 'image' => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=60&h=60&fit=crop"],
//                'snack2' => ['id' => 4, 'name' => "Apple with Peanut Butter", 'image' => "https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?w=60&h=60&fit=crop"],
//                'dinner' => ['id' => 5, 'name' => "Salmon with Quinoa", 'image' => "https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=60&h=60&fit=crop"]
//            ],
//            2 => [ // Day 2 (Tuesday)
//                'breakfast' => ['id' => 6, 'name' => "Oatmeal with Berries", 'image' => "https://images.unsplash.com/photo-1517673132405-a56a62b18caf?w=60&h=60&fit=crop"],
//                'lunch' => ['id' => 3, 'name' => "Grilled Chicken Salad", 'image' => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=60&h=60&fit=crop"],
//            ],
//            // ... more days for week 1
//        ],
//        2 => [ // Week 2
//            1 => [ // Day 1 (Monday of Week 2)
//                 'breakfast' => ['id' => 1, 'name' => "Greek Yogurt Parfait", 'image' => "https://images.unsplash.com/photo-1488477181946-6428a0291777?w=60&h=60&fit=crop"],
//                 'dinner' => ['id' => 5, 'name' => "Salmon with Quinoa", 'image' => "https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=60&h=60&fit=crop"]
//            ]
//            // ... more days for week 2
//        ]
//    ];


    // --- 2. DATA TRANSFORMATION ---
    // This block converts the controller's $mealsData into the simple $mealPlans array needed by the calendar.
    $mealPlans = [];
    // Assumption: The plan starts on the first Monday of the current month.
    $planStartDate = new DateTime('first monday of ' . $monthName . ' ' . $year);

    foreach ($mealsData as $weekNumber => $days) {
        foreach ($days as $dayOfWeekInPlan => $meals) {
            // Calculate how many days to add to the plan's start date
            // ($weekNumber - 1) * 7 accounts for the weeks
            // ($dayOfWeekInPlan - 1) accounts for the days within the week (since Monday is 1)
            $daysToAdd = (($weekNumber - 1) * 7) + ($dayOfWeekInPlan - 1);

            $mealDate = (clone $planStartDate)->modify("+$daysToAdd days");

            // Only add meals that fall within the current month
            if ($mealDate->format('Y-m') === $currentDate->format('Y-m')) {
                $dayOfMonth = (int)$mealDate->format('j');

                $mealsForThisDay = [];
                foreach ($meals as $mealDetails) {
                    $mealsForThisDay[] = [
                        'image' => $mealDetails['image'],
                        'name' => $mealDetails['name']
                    ];
                }
                $mealPlans[$dayOfMonth] = $mealsForThisDay;
            }
        }
    }


    // --- 3. CALENDAR CALCULATION (No changes needed here) ---
    $firstDayOfMonth = new DateTime($currentDate->format('Y-m-01'));
    $daysInMonth = (int)$firstDayOfMonth->format('t');
    $startingDayOfWeek = ((int)$firstDayOfMonth->format('w') + 1) % 7;
    $dayCount = 1;
    $today = (int)$currentDate->format('j');

@endphp

{{-- 4. CALENDAR DISPLAY (No changes needed here) --}}
<div class="card">
{{--    {{$mealsData}}--}}
    <div class="card-header calendar-header text-center">
        <h5 class="mb-0">{{ $monthName }} {{ $year }}</h5>
    </div>
    <div class="card-body p-0">
        {{-- Header row for days of the week --}}
        <div class="row g-0">
            @foreach(['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $day)
                <div class="col text-center py-2 fw-bold calender-custom {{ !$loop->last ? 'border-end' : '' }}">{{ $day }}</div>
            @endforeach
        </div>

        {{-- Calendar grid rows --}}
        @for($week = 0; $week < 6; $week++)
            <div class="row g-0">
                @for($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                    @if($week === 0 && $dayOfWeek < $startingDayOfWeek)
                        <div class="col calendar-day border-end border-bottom"></div>
                    @elseif($dayCount > $daysInMonth)
                        <div class="col calendar-day border-end border-bottom"></div>
                    @else
                        @php
                            // This logic remains the same because we created the $mealPlans array it expects.
                            $hasMeals = array_key_exists($dayCount, $mealPlans);
                            $meals = $hasMeals ? $mealPlans[$dayCount] : [];
                            $dayClasses = 'col calendar-day border-end border-bottom';
                            if ($dayCount == $today) $dayClasses .= ' bg-light';
                            if ($hasMeals) $dayClasses .= ' has-meal';
                        @endphp
                        <div class="{{ $dayClasses }}" style="position: relative;">
                            <div class="p-2">
                                <span class="fw-bold {{ $dayCount == $today ? 'text-success' : '' }}">{{ $dayCount }}</span>
                                @if($hasMeals)
                                    <div class="d-flex flex-wrap" style="gap: 4px; margin-top: 4px;">
                                        @foreach($meals as $index => $meal)
                                            @if($index < 5)
                                                <img src="{{ $meal['image'] }}"
                                                     alt="{{ $meal['name'] }}"
                                                     title="{{ $meal['name'] }}"
                                                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" />
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @php $dayCount++; @endphp
                    @endif
                @endfor
            </div>
            @if($dayCount > $daysInMonth)
                @break
            @endif
        @endfor
    </div>
</div>

<div class="mt-3 text-center">
    <small class="text-muted">
        <i class="fas fa-circle text-success me-1"></i>Days with meals planned (up to 5 meals per day)
    </small>
</div>
