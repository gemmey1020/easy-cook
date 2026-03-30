{{-- resources/views/components/dashboard/admin/sales/sales-user-table.blade.php --}}
@props(['subscription'])

@php
    $mealsByWeek = $subscription
        ->dailySelectedMeals
        ->groupBy(fn($m) => $m->variant->week);
@endphp

<h4 class="sales-title">User Order</h4>

@if($mealsByWeek->isEmpty())
  <p class="sales-no-data">No subscription data available.</p>
@else



  <div  class="sales-content ">

      <div class="card my-4">
          <div class="card-header">
              <h4 class="card-title">Client Details</h4>
          </div>
          <div class="card-body">
              <div class="row">
                  <div class="col-md-6">
                      <p><strong>Client Name:</strong> {{ $subscription->user->first_name . " " . $subscription->user->last_name }} </p>
                      <p><strong>Subscription:</strong> {{ str_replace('_', ' ', $subscription->subscription_type) }}</p>
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


      {{-- tabs الأسابيع --}}
    <ul class="nav nav-pills sales-week-tabs" role="tablist">
      @foreach($mealsByWeek as $week => $meals)
        <li class="nav-item" role="presentation">
          <button
            class="nav-link sales-week-tab @if($loop->first) active @endif"
            id="week-{{ $week }}-tab"
            data-bs-toggle="pill"
            data-bs-target="#week-{{ $week }}"
            type="button"
            role="tab"
            aria-controls="week-{{ $week }}"
            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
            Week {{ $week }}
          </button>
        </li>
      @endforeach
    </ul>

    {{-- محتوًى كل تاب --}}
    <div class="tab-content sales-tab-content">
      @foreach($mealsByWeek as $week => $meals)
        <div
          class="tab-pane fade sales-tab-pane @if($loop->first) show active @endif"
          id="week-{{ $week }}"
          role="tabpanel"
          aria-labelledby="week-{{ $week }}-tab"
        >

          {{-- 1) الجدول للـ desktop/tablet --}}
          <div class="sales-table-responsive d-none d-sm-block">
            <table class="table sales-meals-table">
              <thead class="sales-table-head">
                <tr>
                  <th>Day</th><th>Breakfast</th><th>Lunch</th>
                  <th>Dinner</th><th>Snack1</th><th>Snack2</th>
                </tr>
              </thead>
              <tbody>
                @foreach(['saturday','sunday','monday','tuesday','wednesday','thursday'] as $day)
                  <tr>
                    <td class="sales-day-cell">{{ ucfirst($day) }}</td>
                    @foreach(['breakfast','lunch','dinner','snack1','snack2'] as $type)
                      @php
                        $item = $meals->first(fn($m)=> $m->meal_type === $type);
                      @endphp
                      <td class="sales-meal-cell">
                        @if($item)
                          <img src="{{ asset($item->variant->image) }}"
                               class="sales-meal-img" alt="">
                          <span class="sales-meal-name">
                            {{ \Str::title(str_replace('_',' ',$item->variant->meal_id)) }}
                          </span>
                        @endif
                      </td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- 2) الأكورديون للـ mobile --}}
          <div class="sales-accordion-wrapper d-block d-sm-none">
            <div class="accordion" id="accordion-week-{{ $week }}">
              @foreach(
                $meals->groupBy(fn($m)=> $m->variant->day)
                as $dayName => $dayMeals
              )
                <div class="accordion-item sales-accordion-item">
                  <h2 class="accordion-header" id="heading-{{ $week }}-{{ $dayName }}">
                    <button
                      class="accordion-button collapsed sales-accordion-button"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#collapse-{{ $week }}-{{ $dayName }}"
                      aria-expanded="false"
                      aria-controls="collapse-{{ $week }}-{{ $dayName }}"
                    >
                      {{ ucfirst($dayName) }}
                    </button>
                  </h2>
                  <div
                    id="collapse-{{ $week }}-{{ $dayName }}"
                    class="accordion-collapse collapse sales-accordion-collapse"
                    aria-labelledby="heading-{{ $week }}-{{ $dayName }}"
                    data-bs-parent="#accordion-week-{{ $week }}"
                  >
                    <div class="accordion-body sales-accordion-body">
                      @foreach(['breakfast','lunch','dinner','snack1','snack2'] as $type)
                        @php
                          $itm = $dayMeals->first(fn($m)=> $m->meal_type === $type);
                        @endphp
                        <div class="sales-accordion-meal">
                          <strong class="sales-accordion-meal-title">
                            {{ ucfirst($type) }}
                          </strong>
                          @if($itm)
                            <div class="sales-accordion-meal-content">
                              <img src="{{ asset($itm->variant->image) }}"
                                   class="sales-accordion-meal-img" alt="">
                              <span class="sales-accordion-meal-name">
                                {{ \Str::title(str_replace('_',' ',$itm->variant->meal_id)) }}
                              </span>
                            </div>
                          @endif
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

        </div>
      @endforeach
    </div>
  </div>

  @push('scripts')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
  @endpush

@endif
