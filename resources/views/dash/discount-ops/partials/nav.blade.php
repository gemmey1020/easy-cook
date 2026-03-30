@php
    $activeTab = $activeTab ?? 'overview';
    $tabs = [
        'overview' => ['label' => 'Overview', 'url' => route('discount-ops.index')],
        'invitations' => ['label' => 'Invitations', 'url' => route('invitations.index')],
        'coupons' => ['label' => 'Coupons', 'url' => route('coupons-v2.index')],
        'partners' => ['label' => 'Partners', 'url' => route('discount-ops.partners')],
    ];
@endphp

<div class="discount-ops-tabs" role="tablist" aria-label="Discount Ops Sections">
    @foreach($tabs as $key => $tab)
        <a
            href="{{ $tab['url'] }}"
            class="discount-ops-tab {{ $activeTab === $key ? 'is-active' : '' }}"
            aria-current="{{ $activeTab === $key ? 'page' : 'false' }}"
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
