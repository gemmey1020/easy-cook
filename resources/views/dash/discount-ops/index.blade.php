@extends('layouts.master')

@section('title', 'Discount Ops')

@include('dash.discount-ops.partials.styles')
@include('dash.discount-ops.partials.common-scripts')

@section('content')
    <div class="coupon-v2-page">
        <div class="coupon-v2-shell">
            <div class="coupon-v2-header">
                <div class="coupon-v2-header-copy">
                    <span class="coupon-v2-kicker">Unified Workspace</span>
                    <h3>Discount Ops</h3>
                    <p>Operate invitations and Coupon V2 from one diagnostic workspace. Track ownership, relationship roles, journey progress, and attention items without opening the code.</p>
                </div>
                <div class="coupon-v2-header-actions">
                    <a href="{{ route('invitations.index') }}" class="btn btn-outline-primary">Open Invitations</a>
                    <a href="{{ route('coupons-v2.index') }}" class="btn btn-outline-secondary">Open Coupons</a>
                </div>
            </div>

            @include('dash.discount-ops.partials.nav', ['activeTab' => 'overview'])

            <div class="discount-ops-grid">
                <div class="card">
                    <div class="card-header">System Health</div>
                    <div class="card-body">
                        <div class="discount-ops-card-grid">
                            @foreach($overview['systemHealth'] as $item)
                                <div class="discount-ops-metric discount-ops-metric--{{ $item['tone'] }}">
                                    <span class="discount-ops-metric__label">{{ $item['label'] }}</span>
                                    <strong class="discount-ops-metric__value">{{ $item['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>
                        <p class="discount-ops-note">Low-volume environments still matter here. Zero means the system is currently quiet, not that the relationship layer is missing.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Journey Funnel</div>
                    <div class="card-body">
                        <div class="discount-ops-card-grid">
                            @foreach($overview['journeyFunnel'] as $item)
                                <div class="discount-ops-metric discount-ops-metric--{{ $item['tone'] }}">
                                    <span class="discount-ops-metric__label">{{ $item['label'] }}</span>
                                    <strong class="discount-ops-metric__value">{{ $item['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Relationship Health</div>
                    <div class="card-body">
                        <div class="discount-ops-card-grid">
                            @foreach($overview['relationshipHealth'] as $item)
                                <div class="discount-ops-metric">
                                    <span class="discount-ops-metric__label">{{ $item['label'] }}</span>
                                    <strong class="discount-ops-metric__value">{{ $item['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Needs Attention</div>
                    <div class="card-body">
                        <div class="discount-ops-attention-grid">
                            <div class="discount-ops-list-card">
                                <h4>Status Drift</h4>
                                @forelse($overview['needsAttention']['statusDrift'] as $item)
                                    <div class="discount-ops-list-item">
                                        <div class="discount-ops-list-item__content">
                                            <span class="discount-ops-list-item__title">{{ $item['title'] }}</span>
                                            <small>{{ $item['surface'] }} · {{ $item['meta'] }}</small>
                                        </div>
                                        <div class="discount-ops-action-rail">
                                            <a href="{{ $item['url'] }}" class="btn btn-sm btn-primary">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $item['copy_text'] }}" data-copy-label="Copy Code">Copy Code</button>
                                            @if(!empty($item['toggle_url']))
                                                <form action="{{ $item['toggle_url'] }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">{{ $item['toggle_label'] }}</button>
                                                </form>
                                            @endif
                                            @if(!empty($item['context_url']))
                                                <a href="{{ $item['context_url'] }}" class="btn btn-sm btn-outline-primary">Open Context</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="discount-ops-empty-state">No active-looking records are closed right now.</div>
                                @endforelse
                            </div>

                            <div class="discount-ops-list-card">
                                <h4>Exhausted Codes</h4>
                                @forelse($overview['needsAttention']['exhausted'] as $item)
                                    <div class="discount-ops-list-item">
                                        <div class="discount-ops-list-item__content">
                                            <span class="discount-ops-list-item__title">{{ $item['title'] }}</span>
                                            <small>{{ $item['surface'] }} · {{ $item['meta'] }}</small>
                                        </div>
                                        <div class="discount-ops-action-rail">
                                            <a href="{{ $item['url'] }}" class="btn btn-sm btn-primary">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $item['copy_text'] }}" data-copy-label="Copy Code">Copy Code</button>
                                            @if(!empty($item['context_url']))
                                                <a href="{{ $item['context_url'] }}" class="btn btn-sm btn-outline-primary">Open Context</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="discount-ops-empty-state">Nothing has reached its limit yet.</div>
                                @endforelse
                            </div>

                            <div class="discount-ops-list-card">
                                <h4>Invalid Attempts</h4>
                                @forelse($overview['needsAttention']['invalidAttempts'] as $item)
                                    <div class="discount-ops-list-item">
                                        <div class="discount-ops-list-item__content">
                                            <span class="discount-ops-list-item__title">{{ $item['title'] }}</span>
                                            <small>{{ $item['surface'] }} · {{ $item['meta'] }}</small>
                                        </div>
                                        <div class="discount-ops-action-rail">
                                            <a href="{{ $item['url'] }}" class="btn btn-sm btn-primary">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $item['copy_text'] }}" data-copy-label="Copy Code">Copy Code</button>
                                            @if(!empty($item['context_url']))
                                                <a href="{{ $item['context_url'] }}" class="btn btn-sm btn-outline-primary">Open Context</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="discount-ops-empty-state">No invalid-attempt pressure is visible right now.</div>
                                @endforelse
                            </div>

                            <div class="discount-ops-list-card">
                                <h4>Stale Open Journeys</h4>
                                @forelse($overview['needsAttention']['staleJourneys'] as $item)
                                    <div class="discount-ops-list-item">
                                        <div class="discount-ops-list-item__content">
                                            <span class="discount-ops-list-item__title">{{ $item['title'] }}</span>
                                            <small>{{ $item['surface'] }} · {{ $item['meta'] }}</small>
                                        </div>
                                        <div class="discount-ops-action-rail">
                                            <a href="{{ $item['url'] }}" class="btn btn-sm btn-primary">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary coupon-v2-copy-btn" data-copy-text="{{ $item['copy_text'] }}" data-copy-label="Copy Code">Copy Code</button>
                                            @if(!empty($item['context_url']))
                                                <a href="{{ $item['context_url'] }}" class="btn btn-sm btn-outline-primary">Open Context</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="discount-ops-empty-state">No captured or registered journeys have gone stale.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
