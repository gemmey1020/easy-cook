@php
    $prefix = $prefix ?? 'invitationAwareness';
    $headline = $headline ?? 'Invitation awareness';
    $message = $message ?? 'Server-backed invitation details will appear here when they become meaningful.';
    $hidden = $hidden ?? false;
@endphp

<section class="ec-invite-awareness" id="{{ $prefix }}InlineSurface" @if ($hidden) hidden @endif aria-live="polite">
    <div class="ec-invite-awareness__top">
        <span class="ec-invite-awareness__badge" id="{{ $prefix }}InlineBadge">No saved code</span>
        <span class="ec-invite-awareness__kicker" id="{{ $prefix }}InlineKicker">Server-backed truth</span>
    </div>

    <div class="ec-invite-awareness__body">
        <h3 id="{{ $prefix }}InlineTitle">{{ $headline }}</h3>
        <p id="{{ $prefix }}InlineMessage">{{ $message }}</p>
    </div>

    <div class="ec-invite-awareness__details" id="{{ $prefix }}InlineDetails">
        <div class="ec-invite-awareness__detail ec-invite-awareness__detail--muted">
            Invitation and coupon awareness will stay visible here across this journey.
        </div>
    </div>

    <div class="ec-invite-awareness__actions" id="{{ $prefix }}InlineActions"></div>
</section>
