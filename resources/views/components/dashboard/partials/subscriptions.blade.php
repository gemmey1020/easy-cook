<div id="subscriptions">
    <x-slot name="header">
        <h4 class="mb-4">My Subscriptions</h4>
    </x-slot>
    <div class="row">
        @forelse ($subscriptions as $sub)
            <div class="col-lg-4 col-md-6 mb-4 p-4">
                <div class="card subscription-card h-100 mt-4"data-id="{{ $sub->id }}" data-bs-toggle="modal"
                    data-bs-target="#subscriptionModal">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop"
                            class="card-img-top" alt="{{ $sub->name }}" style="height: 200px; object-fit: cover;">
                        <span
                            class="subscription-status badge {{ $sub->status === 'active' ? 'bg-success' : ($sub->status === 'paused' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ ucfirst(str_replace('_', ' ', $sub->subscription_type)) }}</h5>
                        <p class="card-text text-muted">Complete nutrition plan with workout </p>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Progress</small>
                                <small class="text-muted">
                                    {{ $sub->meal_progress_completed }} / {{ $sub->meal_progress_total }}
                                </small>
                            </div>
                            <div class="progress progress-custom">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $sub->meal_progress_percent }}%"
                                    aria-valuenow="{{ $sub->meal_progress_percent }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-success">{{ $sub->price }}</span>
                                <button class="btn btn-outline-success btn-sm">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- No subscriptions available state --}}
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">No Subscriptions Available</h5>
                    <p class="text-muted mb-4">You don't have any active subscriptions yet. Start your fitness journey
                        today!</p>
                </div>
            </div>
        @endforelse

        <!-- Subscription Details Modal -->
        @if (count($subscriptions) > 0)
            @include('components.dashboard.partials.subscription-details-modal')
        @endif

    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to handle the hash and trigger modal
            function handleHash() {
                const url = window.location.href; // e.g., "http://127.0.0.1:8000/user-dashboard/subscriptions#5"
                const hashIndex = url.lastIndexOf('#');
                if (hashIndex !== -1) {
                    const id = url.substring(hashIndex + 1); // Get ID after last '#'
                    if (id) {
                        // Find the subscription card with matching data-id and trigger click
                        const $card = $(`.subscription-card[data-id="${id}"]`);
                        if ($card.length) {
                            $card.trigger('click'); // Simulate click to open modal
                        } else {
                            console.warn(`No subscription card found with data-id="${id}"`);
                        }
                    }
                }
            }

            // Function to handle subscription card click and AJAX
            function setupSubscriptionCardClick() {
                $('.subscription-card').on('click', function() {
                    const subId = $(this).data('id');

                    $.ajax({
                        url: `/subscription/${subId}/details`,
                        type: 'GET',
                        beforeSend: function() {
                            $('#subscription-details-content').html(
                                '<div class="p-5 text-center">Loading...</div>'
                            );
                            // Show the modal (e.g., Bootstrap modal)
                            $('#subscriptionModal').modal('show');
                        },
                        success: function(response) {
                            $('#subscription-details-content').html(response.html);
                        },
                        error: function() {
                            $('#subscription-details-content').html(
                                '<div class="p-5 text-danger text-center">Failed to load details.</div>'
                            );
                        },
                    });
                });
            }

            // Initialize on page load
            setupSubscriptionCardClick();
            handleHash();

            // Listen for hash changes
            $(window).on('hashchange', handleHash);
        });
    </script>

    <script src="{{ asset('assets/js/dashboard/subscriptions.js') }}"></script>



    <script>
        function openSubscriptionDetails() {

            $('#subscriptionModal').modal('show');
            // loadSubscriptionDetails(subscriptionId);
        }
        $(document).ready(function() {
            handleHash();

        });
    </script>

    <script src="{{ asset('assets/js/dashboard/mealExplorer.js') }}"></script>
@endpush
