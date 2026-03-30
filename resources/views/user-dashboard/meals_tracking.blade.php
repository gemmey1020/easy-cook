@extends('layouts.user-dashboard')

@section('title', 'Meals Tracking')

@section('user-dashboard-content')
<div class="tab-pane h-100" id="mealsTracking">
<div class="p-4 h-100 d-flex flex-column">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success mb-0">
            <i class="fas fa-chart-line me-2"></i>Meals Tracking
        </h2>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        @foreach($subscriptions as $subscription)
            @php
                $total = $subscription->meal_progress_total ?? 0;
                $completed = $subscription->meal_progress_completed ?? 0;
                $remaining = $total - $completed;
                $percentage = $subscription->meal_progress_percent ?? 0;
                $progressClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
            @endphp

            <div class="col-md-4">
                <div class="stats-card subscription-card cursor-pointer" onclick="loadSubscriptionMeals({{ $subscription->id }})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-success mb-1">Subscription #{{ $subscription->id }}</h5>
                            <p class="mb-0">Total: {{ $total }} | Done: {{ $completed }} | Left: {{ $remaining }}</p>
                        </div>
                        <i class="fas fa-utensils fa-2x opacity-75"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Progress</h6>
                        <span class="text-success fw-bold">{{ $percentage }}%</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-bar-custom {{ $progressClass }}" style="width: {{ $percentage }}%">
                            <span class="progress-text">{{ $percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <ul class="nav nav-pills justify-content-center" id="filterTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-filter="all">All</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="day">Day</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="week">Week</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="month">Month</button></li>
        </ul>
    </div>

    <!-- Dynamic Table Container -->
    <div class="mt-4 table-responsive" id="subscriptionMealsContainer">
<div id="loadingSpinner" class="text-center my-4" style="display: none;">
    <div class="spinner-border text-success" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
        <h5 class="mb-3">Meals for Subscription</h5>
        <table class="table table-striped table-hover table-bordered align-middle text-center responsive nowrap" id="mealsTable" style="width: 100%;">

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
        </table>

    </div>
</div>
</div>
@endsection


@push('extra-css')
    <style>
.page-link {
    color: var(--success-color) !important;
    border: 1px solid var(--success-color) !important;
    background-color: transparent !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    margin: 0 3px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease-in-out !important;
}

.page-link:hover {
    background-color: var(--success-color) !important;
    color: #fff !important;
    border-color: var(--success-color) !important;
}

.page-item.active .page-link {
    background-color: var(--success-color) !important;
    color: #fff !important;
    border-color: var(--success-color) !important;
    font-weight: bold !important;
}

.page-item.disabled .page-link {
    background-color: #eaeaea !important;
    color: #aaa !important;
    border-color: #ddd !important;
    cursor: not-allowed !important;
}
/* Base pagination buttons */

/* Green expand icon */
/* Custom responsive expand icon styling */
table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child:before,
table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child:before {
    background-color: transparent !important;
    color: #198754 !important;
    border: none !important;
    border-radius: 0 !important;
    font-weight: bold;
    animation: pulseExpand 1.5s infinite;
    font-size: 1rem;
    box-shadow: none !important;
    transition: all 0.3s ease-in-out;
}

/* Pulse animation (soft green glow) */
@keyframes pulseExpand {
    0% {
        text-shadow: 0 0 0 rgba(25, 135, 84, 0.6);
    }
    70% {
        text-shadow: 0 0 6px rgba(25, 135, 84, 0.6);
    }
    100% {
        text-shadow: 0 0 0 rgba(25, 135, 84, 0.6);
    }
}

    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    @endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>


<script>
let mealsTableInstance = null;
let currentFilter = 'all';
function loadSubscriptionMeals(subscriptionId) {
    $('#loadingSpinner').show();
     currentFilter = 'all'; // reset filter on reload

    if (mealsTableInstance) {
        mealsTableInstance.destroy();
    }

    $('#mealsTable').html(`
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
    `);

    mealsTableInstance = $('#mealsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
       ajax: {
            url: `/user-dashboard/meals/data/${subscriptionId}`,
            data: function (d) {
                d.filter_range = currentFilter; // send filter to backend
            }
        },
       columns: [
        { data: 'date', render: data => data },
        { data: 'meal', render: data => data },
        {
            data: 'type',
            render: function(data) {
                return data || '<span class="bg-secondary text-white">Not Set</span>';
            }
        },
        { data: 'macros', render: data => data },
        { data: 'calories', render: data => data },
        { data: 'status', render: data => data }
    ],
     language: {
        emptyTable: `<div class="text-center p-4">
                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                        <p class="mb-0">No meals found for this filter.</p>
                    </div>`,
        processing: `<div class="text-success fw-bold py-2">Loading...</div>`
    },

        drawCallback: function () {
            $('#loadingSpinner').hide();
        }
    });

    document.getElementById('subscriptionMealsContainer').scrollIntoView({ behavior: 'smooth' });
}

// Optional filters logic (you can reuse your old filterMeals function here)

// function filterMeals(range = 'all') {
//     alert(range);
//     const visibleTable = document.querySelector('.subscription-meals-table:not([style*="display: none"]) table');

//     if (!visibleTable) return;

//     const today = new Date();
//     const todayStr = today.toISOString().slice(0, 10); // yyyy-mm-dd

//     const startOfWeek = new Date(today);
//     startOfWeek.setDate(today.getDate() - today.getDay()); // Saturday

//     const endOfWeek = new Date(today);
//     endOfWeek.setDate(startOfWeek.getDate() + 6); // Friday

//     visibleTable.querySelectorAll('tbody tr').forEach(row => {
//      const dateStr = row.querySelector('td')?.getAttribute('data-date');
//     const date = new Date(dateStr); // Now safe

//         let show = true;

//         if (range === 'day') {
//             show = formatted === todayStr;
//         } else if (range === 'week') {
//             show = date >= startOfWeek && date <= endOfWeek;
//         } else if (range === 'month') {
//             show = date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear();
//         }

//         row.style.display = show ? '' : 'none';
//     });
// }

// document.querySelectorAll('.filter-tabs button[data-filter]').forEach(button => {
//     button.addEventListener('click', () => {
//         // remove active class
//         document.querySelectorAll('.filter-tabs .nav-link').forEach(btn => btn.classList.remove('active'));
//         button.classList.add('active');

//         const filter = button.getAttribute('data-filter');
//         filterMeals(filter);
//     });
// });
document.querySelectorAll('.filter-tabs button[data-filter]').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.filter-tabs .nav-link').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        currentFilter = button.getAttribute('data-filter');

        if (mealsTableInstance) {
            mealsTableInstance.ajax.reload();
        }
    });
});
</script>
@endpush
