@php
    $invitationPublicEnabled = $invitationPublicEnabled ?? config('release_flags.invitation_public_enabled');
    $invitationState = $invitationState ?? [
        'token' => null,
        'state' => null,
        'is_valid' => false,
        'code' => null,
        'message' => null,
        'referral_source' => null,
        'referral_source_note' => null,
        'default_coupon' => null,
        'active_coupon' => null,
        'has_override' => false,
    ];

    $referralOptions = [
        'meta' => 'Meta',
        'google' => 'Google',
        'direct_link' => 'Direct Link',
        'captain_gym' => 'Captain / Gym',
        'other' => 'Other',
    ];
@endphp

@include('layouts.partial.invitation-awareness-style')

<style>
    .reg-journey-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    .reg-topbar,
    .reg-hero,
    .reg-form-stage,
    .reg-block,
    .reg-invitation-panel,
    .reg-status-card,
    .reg-context-strip {
        border: 1px solid rgba(37, 138, 67, 0.12);
        background: #ffffff;
        box-shadow: 0 22px 60px rgba(18, 38, 22, 0.08);
    }

    .reg-topbar,
    .reg-hero,
    .reg-form-stage {
        border-radius: 24px;
    }

    .reg-block,
    .reg-invitation-panel,
    .reg-status-card,
    .reg-context-strip {
        border-radius: 20px;
    }

    .reg-alert-stack {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
    }

    .reg-alert-stack .alert {
        margin: 0;
        border: 1px solid rgba(37, 138, 67, 0.12);
        border-radius: 18px;
        padding: 16px 18px;
    }

    .reg-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 16px 20px;
        margin-bottom: 18px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(246, 251, 247, 0.98));
    }

    .reg-topbar-copy {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        color: #24563a;
        font-size: 14px;
        font-weight: 600;
    }

    .reg-topbar-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #258a43;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .reg-topbar-link:hover {
        color: #1d6d35;
        text-decoration: underline;
    }

    .reg-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 36px;
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid rgba(37, 138, 67, 0.14);
        background: rgba(37, 138, 67, 0.06);
        color: #24563a;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .reg-chip--soft {
        background: rgba(37, 138, 67, 0.03);
        color: #5e7868;
    }

    .reg-hero {
        position: relative;
        overflow: hidden;
        padding: 34px;
        margin-bottom: 22px;
        background:
            radial-gradient(circle at top right, rgba(37, 138, 67, 0.14), transparent 26%),
            radial-gradient(circle at bottom left, rgba(37, 138, 67, 0.08), transparent 24%),
            linear-gradient(135deg, rgba(252, 255, 252, 0.98), rgba(244, 250, 246, 0.98));
    }

    .reg-hero::after {
        content: "";
        position: absolute;
        inset: 18px 18px auto auto;
        width: clamp(180px, 24vw, 260px);
        height: clamp(180px, 24vw, 260px);
        border-radius: 50%;
        background:
            radial-gradient(circle at 30% 30%, rgba(37, 138, 67, 0.16), transparent 38%),
            radial-gradient(circle at 70% 70%, rgba(37, 138, 67, 0.08), transparent 52%);
        opacity: 0.9;
        pointer-events: none;
        filter: blur(3px);
    }

    .reg-hero-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
        gap: 22px;
        align-items: start;
    }

    .reg-hero-copy {
        display: grid;
        gap: 16px;
    }

    .reg-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .reg-hero-copy h1,
    .reg-form-stage h2,
    .reg-block h3,
    .reg-status-card h3 {
        color: #163726;
        letter-spacing: -0.02em;
    }

    .reg-hero-copy h1 {
        margin: 0;
        font-size: clamp(2.2rem, 4vw, 3.4rem);
        line-height: 1.04;
        max-width: 12ch;
    }

    .reg-hero-copy p,
    .reg-hero-note,
    .reg-block-subcopy,
    .reg-field-note,
    .reg-status-copy,
    .reg-context-strip p,
    .reg-form-stage > p {
        margin: 0;
        color: #5d7568;
        line-height: 1.8;
    }

    .reg-hero-points {
        display: grid;
        gap: 12px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .reg-hero-points li,
    .reg-status-list li {
        position: relative;
        padding-inline-start: 18px;
        color: #365845;
        line-height: 1.7;
    }

    .reg-hero-points li::before,
    .reg-status-list li::before {
        content: "";
        position: absolute;
        inset-inline-start: 0;
        top: 0.78em;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #258a43;
        box-shadow: 0 0 0 6px rgba(37, 138, 67, 0.10);
    }

    .reg-context-strip {
        display: grid;
        gap: 8px;
        padding: 16px 18px;
        background: linear-gradient(180deg, rgba(248, 252, 248, 0.98), rgba(255, 255, 255, 0.98));
    }

    .reg-context-strip strong,
    .reg-status-card strong {
        color: #173a23;
    }

    .reg-status-card {
        padding: 22px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 252, 249, 0.98));
    }

    .reg-status-card h3 {
        margin: 12px 0 8px;
        font-size: 1.2rem;
    }

    .reg-status-kicker {
        color: #258a43;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .reg-status-list {
        display: grid;
        gap: 12px;
        margin: 16px 0 0;
        padding: 0;
        list-style: none;
    }

    .reg-form-stage {
        padding: 30px;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(247, 251, 248, 0.98));
    }

    .reg-form-stage > h2 {
        margin: 0 0 8px;
        font-size: 1.9rem;
    }

    .reg-stage-grid {
        display: grid;
        gap: 18px;
    }

    .reg-invitation-panel {
        padding: 22px;
        background:
            radial-gradient(circle at top right, rgba(37, 138, 67, 0.12), transparent 28%),
            linear-gradient(180deg, rgba(251, 255, 251, 0.98), rgba(245, 250, 247, 0.98));
    }

    .reg-block {
        padding: 22px;
        background: #ffffff;
    }

    .reg-block-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .reg-block-header h3 {
        margin: 0 0 6px;
        font-size: 1.2rem;
    }

    .reg-step {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #258a43;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .reg-block-emphasis {
        color: #5c7567;
        font-size: 13px;
        font-weight: 600;
    }

    .reg-invite-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(280px, 0.9fr);
        gap: 18px;
        align-items: start;
    }

    .reg-inline-capture {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        align-items: end;
    }

    .reg-field-grid,
    .reg-paired-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .reg-field-grid .full-width,
    .reg-paired-grid .full-width {
        grid-column: 1 / -1;
    }

    .reg-field-row {
        display: grid;
        gap: 8px;
    }

    .reg-field-row label {
        color: #173a23;
        font-size: 14px;
        font-weight: 700;
    }

    .reg-field-row input,
    .reg-field-row select {
        width: 100%;
        min-height: 56px;
        border: 1px solid rgba(37, 138, 67, 0.16);
        border-radius: 16px;
        padding: 14px 16px;
        background: #ffffff;
        color: #173a23;
        font-size: 15px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    .reg-field-row input:focus,
    .reg-field-row select:focus {
        outline: none;
        border-color: rgba(37, 138, 67, 0.4);
        box-shadow: 0 0 0 4px rgba(37, 138, 67, 0.08);
    }

    .reg-field-note {
        font-size: 13px;
    }

    .reg-muted {
        color: #7b8f84;
        font-weight: 500;
    }

    .reg-status-surface {
        display: grid;
        gap: 14px;
        padding: 18px;
        border: 1px solid rgba(37, 138, 67, 0.12);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.82);
    }

    .reg-status-top {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .reg-state-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid rgba(37, 138, 67, 0.14);
        background: rgba(255, 255, 255, 0.92);
        color: #365845;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .reg-state-pill--valid {
        border-color: rgba(37, 138, 67, 0.2);
        background: rgba(37, 138, 67, 0.10);
        color: #1f6f3f;
    }

    .reg-state-pill--invalid {
        border-color: rgba(196, 114, 82, 0.2);
        background: rgba(196, 114, 82, 0.10);
        color: #9f4b30;
    }

    .reg-state-pill--neutral {
        color: #607a6b;
    }

    .reg-inline-code {
        display: inline-block;
        direction: ltr;
        unicode-bidi: isolate;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(37, 138, 67, 0.08);
        color: #195331;
        font-weight: 700;
        font-family: "DejaVu Sans Mono", "Cascadia Mono", monospace;
    }

    .reg-status-meta {
        display: grid;
        gap: 10px;
    }

    .reg-status-item {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        color: #4e675a;
        line-height: 1.7;
    }

    .reg-status-item strong {
        color: #173a23;
    }

    .reg-security-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .reg-password-toggle {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #365845;
        font-weight: 600;
        cursor: pointer;
    }

    .reg-submit-zone {
        display: grid;
        gap: 16px;
        padding-top: 8px;
    }

    .reg-submit-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 18px 20px;
        border: 1px solid rgba(37, 138, 67, 0.12);
        border-radius: 20px;
        background: linear-gradient(180deg, rgba(250, 253, 250, 0.98), rgba(255, 255, 255, 0.98));
    }

    .reg-submit-card h3 {
        margin: 0 0 6px;
        color: #173a23;
        font-size: 1.1rem;
    }

    .reg-submit-card p {
        margin: 0;
        color: #5d7568;
        line-height: 1.7;
    }

    .reg-primary-cta {
        min-width: 240px;
        min-height: 58px;
        padding-inline: 24px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        box-shadow: 0 18px 32px rgba(37, 138, 67, 0.22);
    }

    .reg-login-footnote {
        text-align: center;
        color: #5d7568;
        font-size: 14px;
    }

    .reg-login-footnote a {
        color: #258a43;
        font-weight: 700;
        text-decoration: none;
    }

    .reg-login-footnote a:hover {
        text-decoration: underline;
    }

    @media (max-width: 1080px) {
        .reg-hero-grid,
        .reg-invite-layout,
        .reg-field-grid,
        .reg-paired-grid,
        .reg-security-grid {
            grid-template-columns: 1fr;
        }

        .reg-submit-card {
            flex-direction: column;
            align-items: stretch;
        }

        .reg-primary-cta {
            width: 100%;
            min-width: 0;
        }
    }

    @media (max-width: 767px) {
        .reg-topbar,
        .reg-block-header,
        .reg-inline-capture {
            grid-template-columns: 1fr;
            display: grid;
        }

        .reg-topbar {
            justify-content: stretch;
        }

        .reg-hero,
        .reg-form-stage {
            padding: 22px;
        }

        .reg-block,
        .reg-invitation-panel,
        .reg-status-card,
        .reg-context-strip {
            padding: 18px;
        }
    }
</style>

<div class="th-checkout-wrapper space-top space-extra-bottom">
    <div class="container reg-journey-shell">
        <div class="reg-topbar">
            <div class="reg-topbar-copy">
                <span class="reg-chip">Private onboarding</span>
                <span class="reg-chip reg-chip--soft">Easy Cook invitation-aware registration</span>
            </div>

            <a href="{{ route('login_web') }}" class="reg-topbar-link">
                <i class="far fa-user"></i>
                <span>Already have an account? Sign in</span>
            </a>
        </div>

        <div class="reg-alert-stack">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <section class="reg-hero">
            <div class="reg-hero-grid">
                <div class="reg-hero-copy">
                    <div class="reg-chip-row">
                        <span class="reg-chip">Easy Cook</span>
                        <span class="reg-chip reg-chip--soft">Secure account setup</span>
                        <span class="reg-chip reg-chip--soft">Invitation first</span>
                    </div>

                    <h1>Create your account without slowing down your next step.</h1>
                    <p>
                        This is a private onboarding surface built to capture your invitation truth, collect the essentials,
                        and move you smoothly toward checkout when your plan context is already in motion.
                    </p>

                    <div class="reg-context-strip" id="assessmentContextStrip" hidden>
                        <strong>Assessment context saved</strong>
                        <p>Your nutrition details are already stored in this browser and will continue with you after account creation.</p>
                    </div>

                    <ul class="reg-hero-points">
                        <li>Your invitation stays synced from capture through checkout.</li>
                        <li>Invalid invitation codes are clearly flagged, but you can still continue.</li>
                        <li>Your account is created with the same Easy Cook visual language already used across the site.</li>
                    </ul>
                </div>

                <aside class="reg-status-card">
                    <span class="reg-status-kicker">Why this page exists</span>
                    <h3>Fast-track setup for high-intent clients</h3>
                    <p class="reg-status-copy">
                        The goal is not to collect endless data. The goal is to capture what matters now, preserve your current journey,
                        and let checkout handle the next commitment step with less friction.
                    </p>

                    <ul class="reg-status-list">
                        <li>Invitation capture stays visible from the start.</li>
                        <li>Required fields are grouped instead of feeling like one long database form.</li>
                        <li>The site header and Easy Cook logo remain part of the experience.</li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="reg-form-stage">
            <h2>Create your Easy Cook account</h2>
            <p>We will keep your invitation details in sync and guide you to the right next step after registration.</p>

            <form id="registerForm" action="{{ route('post_register_web') }}" method="post" class="reg-stage-grid woocommerce-checkout">
                @csrf

                <input type="hidden" id="finalDataInput" name="final_data" />
                <input type="hidden" id="invitationJourneyToken" name="invitation_journey_token" value="{{ old('invitation_journey_token', $invitationState['token'] ?? '') }}" />
                <input type="hidden" id="invitationCodeHidden" name="invitation_code" value="{{ old('invitation_code', $invitationState['code'] ?? '') }}" />
                <input type="hidden" id="postRegistrationDestination" name="post_registration_destination" value="{{ old('post_registration_destination', 'default') }}" />

                <section class="reg-invitation-panel">
                    <div class="reg-block-header">
                        <div>
                            <span class="reg-step">Step 1</span>
                            <h3>Capture your invitation first</h3>
                            <p class="reg-block-subcopy">
                                {{ $invitationPublicEnabled ? 'Enter your invitation code now so your access truth stays connected before account creation.' : 'Invitation codes are currently closed for this release. You can continue creating your account normally.' }}
                            </p>
                        </div>
                        <span class="reg-block-emphasis">{{ $invitationPublicEnabled ? 'Optional for access, helpful for discount and attribution' : 'Release-gated' }}</span>
                    </div>

                    <div class="reg-invite-layout">
                        @if ($invitationPublicEnabled)
                            <div class="reg-field-grid">
                                <div class="reg-field-row full-width">
                                    <label for="invitationCodeInput">Invitation code</label>
                                    <div class="reg-inline-capture">
                                        <input
                                            id="invitationCodeInput"
                                            type="text"
                                            class="form-control"
                                            value="{{ old('invitation_code', $invitationState['code'] ?? '') }}"
                                            placeholder="Enter your invitation code or continue without one"
                                        />
                                        <button type="button" class="th-btn rounded-2" id="captureInvitationBtn">Capture</button>
                                    </div>
                                    <span class="reg-field-note">If the code is invalid, we will tell you clearly and still allow registration to continue.</span>
                                </div>
                            </div>

                            @include('layouts.partial.invitation-awareness-inline', [
                                'prefix' => 'registerInvitation',
                                'headline' => 'Invitation awareness stays visible here',
                                'message' => 'Your saved invitation truth, status, and next action will stay visible here while you register.',
                            ])
                        @else
                            <div class="alert alert-light border-0 mb-0">
                                Invitation codes are currently unavailable in this release. Account creation and checkout can continue without them.
                            </div>
                        @endif
                    </div>
                </section>

                <div class="reg-paired-grid">
                    <section class="reg-block">
                        <div class="reg-block-header">
                            <div>
                                <span class="reg-step">Step 2</span>
                                <h3>Account details</h3>
                                <p class="reg-block-subcopy">Tell us who this subscription is for and how to reach you.</p>
                            </div>
                        </div>

                        <div class="reg-field-grid">
                            <div class="reg-field-row">
                                <label for="first_name">First name</label>
                                <input type="text" id="first_name" value="{{ old('first_name') }}" name="first_name" placeholder="First name" required />
                            </div>

                            <div class="reg-field-row">
                                <label for="last_name">Last name</label>
                                <input type="text" id="last_name" value="{{ old('last_name') }}" name="last_name" placeholder="Last name" required />
                            </div>

                            <div class="reg-field-row full-width">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required />
                            </div>

                            <div class="reg-field-row">
                                <label for="phone">Phone number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="01xxxxxxxxx" required />
                            </div>

                            <div class="reg-field-row">
                                <label for="company_name">Company name <span class="reg-muted">(Optional)</span></label>
                                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Company name" />
                            </div>
                        </div>
                    </section>

                    <section class="reg-block">
                        <div class="reg-block-header">
                            <div>
                                <span class="reg-step">Step 3</span>
                                <h3>Delivery and referral details</h3>
                                <p class="reg-block-subcopy">We only ask for the essentials needed to continue your current journey.</p>
                            </div>
                        </div>

                        <div class="reg-field-grid">
                            <div class="reg-field-row">
                                <label for="referralSourceSelect">How did you hear about us?</label>
                                <select name="referral_source" id="referralSourceSelect" required>
                                    <option value="">Select source</option>
                                    @foreach ($referralOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(old('referral_source', $invitationState['referral_source'] ?? '') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="reg-field-row" id="referralOtherNoteRow" style="display: none;">
                                <label for="referralSourceNote">Other source note</label>
                                <input
                                    type="text"
                                    id="referralSourceNote"
                                    name="referral_source_note"
                                    maxlength="255"
                                    value="{{ old('referral_source_note', $invitationState['referral_source_note'] ?? '') }}"
                                    placeholder="Tell us where it came from"
                                />
                            </div>

                            <div class="reg-field-row full-width">
                                <label for="street_address">Street address</label>
                                <input type="text" id="street_address" value="{{ old('street_address') }}" name="street_address" placeholder="Street address" required />
                            </div>

                            <div class="reg-field-row full-width">
                                <label for="town_city">Town / City</label>
                                <input type="text" id="town_city" value="{{ old('town_city') }}" name="town_city" placeholder="Town / City" required />
                            </div>
                        </div>
                    </section>
                </div>

                <section class="reg-block">
                    <div class="reg-block-header">
                        <div>
                            <span class="reg-step">Step 4</span>
                            <h3>Secure your account</h3>
                            <p class="reg-block-subcopy">Create your password now so your checkout and account experience stay connected.</p>
                        </div>
                    </div>

                    <div class="reg-security-grid">
                        <div class="reg-field-row">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Password" required />
                        </div>

                        <div class="reg-field-row">
                            <label for="password_confirmation">Confirm password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required />
                        </div>
                    </div>

                    <div class="reg-submit-zone">
                        <label class="reg-password-toggle" for="showPassword">
                            <input type="checkbox" class="form-check-input" id="showPassword" onclick="toggleAllPasswords()">
                            <span>Show password</span>
                        </label>

                        <div class="reg-submit-card">
                            <div>
                                <h3>Ready for the next step</h3>
                                <p id="registrationContextMessage">Your account will be created now, and your next step will follow your current journey.</p>
                            </div>

                            <button type="submit" class="th-btn rounded-2 reg-primary-cta">Continue to checkout</button>
                        </div>
                    </div>
                </section>
            </form>

            <p class="reg-login-footnote">
                Returning client?
                <a href="{{ route('login_web') }}">Sign in here</a>
            </p>
        </section>
    </div>
</div>

@include('layouts.partial.invitation-awareness-orchestrator')

<script>
    const invitationState = @json($invitationState);
    const invitationAutoCaptured = @json($invitationAutoCaptured ?? false);
    const invitationPublicEnabled = @json($invitationPublicEnabled);

    function toggleAllPasswords() {
        document.querySelectorAll('#password, #password_confirmation').forEach((field) => {
            field.type = field.type === 'password' ? 'text' : 'password';
        });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function updateReferralNoteVisibility() {
        const select = document.getElementById('referralSourceSelect');
        const row = document.getElementById('referralOtherNoteRow');
        row.style.display = select.value === 'other' ? 'grid' : 'none';
    }

    function readStoredJson(key) {
        try {
            const raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : null;
        } catch (error) {
            return null;
        }
    }

    function hasAssessmentContext() {
        const finalData = readStoredJson('final_data');
        return !!(finalData && typeof finalData === 'object' && Object.keys(finalData).length > 0);
    }

    function hasPlanSelectionContext() {
        if (window.EasyCookSelectionStorage?.normalizeAllSelectionStorage) {
            window.EasyCookSelectionStorage.normalizeAllSelectionStorage();
        }

        if (window.EasyCookSelectionStorage?.hasPlanSelectionContext) {
            return window.EasyCookSelectionStorage.hasPlanSelectionContext();
        }

        const customizeMeals = readStoredJson('customizeMealsSave');
        const standardPackages = readStoredJson('standerdPackages');

        const hasCustomize = customizeMeals && typeof customizeMeals === 'object' && Object.keys(customizeMeals).length > 0;
        const hasStandard = standardPackages && typeof standardPackages === 'object' && Object.keys(standardPackages).length > 0;

        return !!(hasCustomize || hasStandard);
    }

    function hasCheckoutReferrer() {
        return /checkout|assessment|plans|cart/i.test(document.referrer || '');
    }

    function resolvePostRegistrationDestination() {
        if (hasAssessmentContext() || hasPlanSelectionContext() || hasCheckoutReferrer()) {
            return 'checkout';
        }

        return 'default';
    }

    function renderAssessmentContext() {
        const strip = document.getElementById('assessmentContextStrip');
        const destinationInput = document.getElementById('postRegistrationDestination');
        const contextMessage = document.getElementById('registrationContextMessage');
        const destination = resolvePostRegistrationDestination();

        destinationInput.value = destination;

        if (hasAssessmentContext()) {
            strip.hidden = false;
        } else {
            strip.hidden = true;
        }

        contextMessage.textContent = destination === 'checkout'
            ? 'Your account will be created now, and your saved plan context will continue in checkout.'
            : 'Your account will be created now, and we will keep your invitation details aligned with your current journey.';
    }

    function syncInvitationFields(state) {
        document.getElementById('invitationJourneyToken').value = state?.token || '';
        document.getElementById('invitationCodeHidden').value = state?.code || '';

        if (state?.code) {
            document.getElementById('invitationCodeInput').value = state.code;
        }

        if (state?.referral_source) {
            document.getElementById('referralSourceSelect').value = state.referral_source;
        }

        if (state?.referral_source_note) {
            document.getElementById('referralSourceNote').value = state.referral_source_note;
        }

        updateReferralNoteVisibility();
    }

    function buildRegisterAwarenessModel(state, context = {}) {
        if (!invitationPublicEnabled) {
            return null;
        }

        return window.EasyCookInvitationAwareness.buildModel({
            surface: 'register',
            rawState: state,
            context,
        });
    }

    function bindRegisterAwarenessActions(model) {
        if (!model) {
            return;
        }

        document.querySelectorAll('#registerInvitationInlineActions [data-awareness-action]').forEach((button) => {
            button.addEventListener('click', () => handleRegisterAwarenessAction(button.dataset.awarenessAction, model));
        });
    }

    function renderInvitationState(state, context = {}) {
        const model = buildRegisterAwarenessModel(state, context);

        if (!model) {
            return;
        }

        window.EasyCookInvitationAwareness.syncInlineSurface({
            prefix: 'registerInvitation',
            model,
        });

        bindRegisterAwarenessActions(model);
    }

    function focusRegistrationFields() {
        const firstField = document.getElementById('first_name');
        firstField?.focus();
    }

    async function clearServerBackedInvitationJourney() {
        const journeyToken = document.getElementById('invitationJourneyToken').value || invitationState?.token || null;

        if (!journeyToken) {
            return;
        }

        try {
            await fetch('{{ route('invitation.forget') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    journey_token: journeyToken,
                }),
            });
        } catch (error) {
            console.warn('Could not clear invitation journey', error);
        }
    }

    async function resetInvitationInput() {
        await clearServerBackedInvitationJourney();
        document.getElementById('invitationCodeInput').value = '';
        document.getElementById('invitationCodeHidden').value = '';
        document.getElementById('invitationJourneyToken').value = '';
        invitationState.token = null;
        invitationState.state = null;
        invitationState.is_valid = false;
        invitationState.code = null;
        invitationState.message = null;
        invitationState.default_coupon = null;
        invitationState.active_coupon = null;
        invitationState.has_override = false;
        invitationState.registered_with_same_code = false;
        invitationState.is_discount_eligible = false;
        invitationState.eligibility_message = null;
        invitationState.discount_label = null;
        invitationState.invalid_reason = null;
        invitationState.last_invalid_code = null;
        renderInvitationState(invitationState);
        document.getElementById('invitationCodeInput').focus();
    }

    async function handleRegisterAwarenessAction(action) {
        switch (action) {
            case 'replace_invitation':
                await resetInvitationInput();
                return;
            case 'sign_in':
                window.location.href = '{{ route('login_web') }}';
                return;
            case 'create_account':
            case 'keep':
                focusRegistrationFields();
                return;
            case 'continue':
            default:
                return;
        }
    }

    async function showInvitationAlert(state, context = {}) {
        if (!invitationPublicEnabled) {
            return;
        }

        const model = buildRegisterAwarenessModel(state, context);
        const result = await window.EasyCookInvitationAwareness.prompt(model);

        if (result.action) {
            await handleRegisterAwarenessAction(result.action, model);
        }
    }

    async function captureInvitation() {
        if (!invitationPublicEnabled) {
            return;
        }

        const code = document.getElementById('invitationCodeInput').value.trim();
        const referralSource = document.getElementById('referralSourceSelect').value;
        const referralSourceNote = document.getElementById('referralSourceNote').value.trim();
        const journeyToken = document.getElementById('invitationJourneyToken').value;

        if (!code) {
            Swal.fire({
                icon: 'warning',
                title: 'Invitation Required',
                text: 'Please enter an invitation code first.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#258a43',
            });
            return;
        }

        const response = await fetch('{{ route('invitation.capture') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                code,
                journey_token: journeyToken || null,
                capture_channel: 'manual',
                referral_source: referralSource || null,
                referral_source_note: referralSourceNote || null,
            }),
        });

        const payload = await response.json();

        if (!response.ok || !payload.success) {
            Swal.fire({
                icon: 'error',
                title: 'Invitation Error',
                text: payload.message || 'Could not capture the invitation code.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#258a43',
            });
            return;
        }

        Object.assign(invitationState, payload.invitation || {});
        syncInvitationFields(invitationState);
        renderInvitationState(invitationState);
        showInvitationAlert(invitationState, { entryMode: 'manual' });
    }

    function bindInputSanitizers() {
        document.querySelectorAll('#first_name, #last_name, #town_city').forEach((input) => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^A-Za-z\u0600-\u06FF\s'\-]/g, '');
            });
        });

        document.getElementById('street_address').addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z\u0600-\u06FF0-9\s.,\-/#'"،]/g, '');
        });

        document.getElementById('phone').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        syncInvitationFields(invitationState);
        renderInvitationState(invitationState);
        updateReferralNoteVisibility();
        renderAssessmentContext();
        bindInputSanitizers();

        if (invitationPublicEnabled && invitationState?.token) {
            showInvitationAlert(invitationState, {
                entryMode: invitationAutoCaptured ? 'link' : 'restore',
            });
        }

        document.getElementById('referralSourceSelect').addEventListener('change', updateReferralNoteVisibility);
        document.getElementById('captureInvitationBtn')?.addEventListener('click', captureInvitation);

        document.getElementById('registerForm').addEventListener('submit', function() {
            const finalData = localStorage.getItem('final_data');
            document.getElementById('finalDataInput').value = finalData ? finalData : '{}';
            document.getElementById('postRegistrationDestination').value = resolvePostRegistrationDestination();
        });
    });
</script>
