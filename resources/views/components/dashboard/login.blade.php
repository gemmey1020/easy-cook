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
        'owner_type' => null,
        'owner_user_id' => null,
        'registered_with_same_code' => false,
        'is_discount_eligible' => false,
        'eligibility_message' => null,
        'discount_label' => null,
        'invalid_reason' => null,
        'last_invalid_code' => null,
    ];
@endphp

@include('layouts.partial.invitation-awareness-style')

<style>
    .login-journey-shell {
        max-width: 1120px;
        margin: 0 auto;
    }

    .login-topbar,
    .login-hero,
    .login-stage,
    .login-context-strip,
    .login-benefit-card,
    .login-form-card {
        border: 1px solid rgba(37, 138, 67, 0.12);
        background: #ffffff;
        box-shadow: 0 22px 60px rgba(18, 38, 22, 0.08);
    }

    .login-topbar,
    .login-hero,
    .login-stage {
        border-radius: 24px;
    }

    .login-context-strip,
    .login-benefit-card,
    .login-form-card {
        border-radius: 20px;
    }

    .login-alert-stack {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
    }

    .login-alert-stack .alert {
        margin: 0;
        border: 1px solid rgba(37, 138, 67, 0.12);
        border-radius: 18px;
        padding: 16px 18px;
    }

    .login-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 16px 20px;
        margin-bottom: 18px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(246, 251, 247, 0.98));
    }

    .login-topbar-copy {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        color: #24563a;
        font-size: 14px;
        font-weight: 600;
    }

    .login-topbar-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #258a43;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .login-topbar-link:hover {
        color: #1d6d35;
        text-decoration: underline;
    }

    .login-chip {
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

    .login-chip--soft {
        background: rgba(37, 138, 67, 0.03);
        color: #5e7868;
    }

    .login-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        margin-bottom: 22px;
        background:
            radial-gradient(circle at top right, rgba(37, 138, 67, 0.14), transparent 28%),
            radial-gradient(circle at bottom left, rgba(37, 138, 67, 0.09), transparent 24%),
            linear-gradient(135deg, rgba(252, 255, 252, 0.98), rgba(244, 250, 246, 0.98));
    }

    .login-hero::after {
        content: "";
        position: absolute;
        inset: auto -30px -50px auto;
        width: clamp(200px, 24vw, 280px);
        height: clamp(200px, 24vw, 280px);
        border-radius: 50%;
        background:
            radial-gradient(circle at 30% 30%, rgba(37, 138, 67, 0.16), transparent 38%),
            radial-gradient(circle at 70% 70%, rgba(37, 138, 67, 0.08), transparent 52%);
        opacity: 0.88;
        pointer-events: none;
        filter: blur(4px);
    }

    .login-hero-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(300px, 0.85fr);
        gap: 22px;
        align-items: start;
    }

    .login-hero-copy {
        display: grid;
        gap: 16px;
    }

    .login-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .login-hero-copy h1,
    .login-stage h2,
    .login-form-card h3,
    .login-benefit-card h3 {
        color: #163726;
        letter-spacing: -0.02em;
    }

    .login-hero-copy h1 {
        margin: 0;
        font-size: clamp(2.1rem, 3.8vw, 3.2rem);
        line-height: 1.05;
        max-width: 12ch;
    }

    .login-hero-copy p,
    .login-stage > p,
    .login-context-strip p,
    .login-form-subcopy,
    .login-benefit-card p,
    .login-field-note,
    .login-submit-copy p {
        margin: 0;
        color: #5d7568;
        line-height: 1.8;
    }

    .login-hero-points,
    .login-benefit-list {
        display: grid;
        gap: 12px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .login-hero-points li,
    .login-benefit-list li {
        position: relative;
        padding-inline-start: 18px;
        color: #365845;
        line-height: 1.7;
    }

    .login-hero-points li::before,
    .login-benefit-list li::before {
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

    .login-context-strip {
        display: grid;
        gap: 8px;
        padding: 16px 18px;
        background: linear-gradient(180deg, rgba(248, 252, 248, 0.98), rgba(255, 255, 255, 0.98));
    }

    .login-context-strip strong,
    .login-benefit-card strong,
    .login-submit-copy strong {
        color: #173a23;
    }

    .login-benefit-card {
        padding: 24px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 252, 249, 0.98));
    }

    .login-benefit-kicker {
        color: #258a43;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .login-benefit-card h3 {
        margin: 12px 0 8px;
        font-size: 1.2rem;
    }

    .login-stage {
        padding: 30px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(247, 251, 248, 0.98));
    }

    .login-stage > h2 {
        margin: 0 0 8px;
        font-size: 1.9rem;
    }

    .login-form-grid {
        display: grid;
        gap: 18px;
        margin-top: 22px;
    }

    .login-form-card {
        padding: 24px;
        background:
            radial-gradient(circle at top right, rgba(37, 138, 67, 0.10), transparent 26%),
            linear-gradient(180deg, rgba(251, 255, 251, 0.98), rgba(245, 250, 247, 0.98));
    }

    .login-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .login-step {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #258a43;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .login-card-header h3 {
        margin: 0 0 6px;
        font-size: 1.24rem;
    }

    .login-block-emphasis {
        color: #5c7567;
        font-size: 13px;
        font-weight: 600;
        text-align: right;
    }

    .login-field-grid {
        display: grid;
        gap: 16px;
    }

    .login-field-row {
        display: grid;
        gap: 8px;
    }

    .login-field-row label,
    .login-password-links a,
    .login-password-toggle span,
    .login-footnote {
        color: #244734;
    }

    .login-field-row label {
        font-size: 14px;
        font-weight: 700;
    }

    .login-field-row input {
        min-height: 56px;
        border-radius: 16px;
        border: 1px solid rgba(36, 71, 52, 0.14);
        background: #ffffff;
        padding: 14px 16px;
        color: #1f3328;
        font-size: 15px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .login-field-row input:focus {
        border-color: rgba(37, 138, 67, 0.45);
        outline: none;
        box-shadow: 0 0 0 4px rgba(37, 138, 67, 0.12);
    }

    .login-password-links {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .login-password-links a {
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .login-password-links a:hover,
    .login-footnote a:hover {
        color: #1d6d35;
        text-decoration: underline;
    }

    .login-password-toggle {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
    }

    .login-password-toggle input {
        width: 18px;
        height: 18px;
        accent-color: #258a43;
    }

    .login-submit-zone {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-top: 6px;
        flex-wrap: wrap;
    }

    .login-submit-card {
        flex: 1 1 320px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 20px 22px;
        border-radius: 18px;
        border: 1px solid rgba(37, 138, 67, 0.10);
        background: rgba(255, 255, 255, 0.92);
    }

    .login-submit-copy {
        display: grid;
        gap: 6px;
    }

    .login-submit-copy h3 {
        margin: 0;
        font-size: 1.08rem;
    }

    .login-primary-cta {
        min-width: 224px;
        min-height: 56px;
        font-size: 15px;
        font-weight: 700;
        box-shadow: 0 16px 32px rgba(37, 138, 67, 0.18);
    }

    .login-footnote {
        margin: 18px 0 0;
        font-size: 14px;
        font-weight: 600;
    }

    .login-footnote a {
        color: #258a43;
        text-decoration: none;
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .login-hero-grid {
            grid-template-columns: 1fr;
        }

        .login-submit-card {
            flex-direction: column;
            align-items: stretch;
        }

        .login-primary-cta {
            width: 100%;
            min-width: 0;
        }
    }

    @media (max-width: 767px) {
        .login-topbar,
        .login-hero,
        .login-stage {
            padding: 22px;
        }

        .login-card-header,
        .login-password-links,
        .login-submit-zone {
            flex-direction: column;
            align-items: stretch;
        }

        .login-block-emphasis {
            text-align: left;
        }
    }
</style>

<div class="th-checkout-wrapper space-top space-extra-bottom">
    <div class="container">
        <div class="login-journey-shell">
            <div class="login-alert-stack">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <section class="login-topbar">
                <div class="login-topbar-copy">
                    <span class="login-chip">Returning client</span>
                    <span>Need a new account instead?</span>
                </div>

                <a href="{{ route('register_web') }}" class="login-topbar-link">
                    <span>Create account</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </section>

            <section class="login-hero">
                <div class="login-hero-grid">
                    <div class="login-hero-copy">
                        <div class="login-chip-row">
                            <span class="login-chip">Easy Cook</span>
                            <span class="login-chip login-chip--soft">Secure sign-in</span>
                            <span class="login-chip login-chip--soft">Checkout-aware</span>
                        </div>

                        <h1>Sign back in without losing your momentum.</h1>
                        <p>
                            This is a private sign-in surface designed to bring you back into your Easy Cook journey quickly,
                            keep your current context intact, and move you to the right next step without extra friction.
                        </p>

                        <div class="login-context-strip" id="loginAssessmentStrip" hidden>
                            <strong>Assessment context saved</strong>
                            <p>Your nutrition details are already stored in this browser and can continue with you after sign-in.</p>
                        </div>

                        <div class="login-context-strip" id="loginJourneyStrip" hidden>
                            <strong>Journey continuity ready</strong>
                            <p>If you already started with an invitation or plan selection, signing in will reconnect that path before checkout.</p>
                        </div>

                        <ul class="login-hero-points">
                            <li>Your sign-in stays aligned with the same Easy Cook visual language used across the site.</li>
                            <li>Checkout and assessment context can continue after login when this browser already holds them.</li>
                            <li>Existing clients get a faster route back to subscription decisions without a cluttered auth screen.</li>
                        </ul>
                    </div>

                    <aside class="login-benefit-card">
                        <span class="login-benefit-kicker">Why this page exists</span>
                        <h3>Fast access for high-intent returning clients</h3>
                        <p>
                            The goal is not to slow you down with extra choices. The goal is to restore trust, continue your
                            current flow when it exists, and keep account access clear for premium checkout journeys.
                        </p>

                        <ul class="login-benefit-list">
                            <li>The site header and Easy Cook logo stay part of the experience.</li>
                            <li>The login form matches the calmer registration language without becoming a duplicate.</li>
                            <li>Checkout continuity stays visible, but sign-in remains the main job of the page.</li>
                        </ul>
                    </aside>
                </div>
            </section>

            <section class="login-stage">
                <h2>Sign in to continue your Easy Cook journey</h2>
                <p>Use the same email address you created your account with and we will take you to the right next step.</p>

                @if ($invitationPublicEnabled)
                    @include('layouts.partial.invitation-awareness-inline', [
                        'prefix' => 'loginInvitation',
                        'headline' => 'Invitation awareness follows this browser',
                        'message' => 'If this browser already captured an invitation or coupon path, it will stay visible here while you sign in.',
                    ])
                @else
                    <div class="login-context-strip">
                        <strong>Discount awareness is currently unavailable</strong>
                        <p>Sign in normally. Invitation-linked discount surfaces are closed for this release and will stay hidden until they are enabled again.</p>
                    </div>
                @endif

                <form id="loginForm" action="{{ route('post_login_web') }}" method="POST" class="login-form-grid woocommerce-form-login mb-0">
                    @csrf

                    <input type="hidden" id="finalDataInput" name="final_data" value="{{ old('final_data', '{}') }}" />
                    <input type="hidden" id="postLoginDestination" name="post_login_destination" value="{{ old('post_login_destination', 'default') }}" />

                    <section class="login-form-card">
                        <div class="login-card-header">
                            <div>
                                <span class="login-step">Step 1</span>
                                <h3>Account access</h3>
                                <p class="login-form-subcopy">Sign in with your email and password to continue with checkout or return to your dashboard.</p>
                            </div>
                            <span class="login-block-emphasis">Email and password only</span>
                        </div>

                        <div class="login-field-grid">
                            <div class="login-field-row">
                                <label for="email">Email address</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    class="form-control"
                                    placeholder="name@example.com"
                                    autocomplete="email"
                                    required
                                />
                            </div>

                            <div class="login-field-row">
                                <label for="password">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Password"
                                    autocomplete="current-password"
                                    required
                                />
                                <span class="login-field-note">Forgot your password? Use the recovery link below and we will help you get back in.</span>
                            </div>

                            <div class="login-password-links">
                                <a href="{{ route('get.password.forget') }}">Forgot password</a>
                                <a href="{{ route('register_web') }}">Create account instead</a>
                            </div>
                        </div>

                        <div class="login-submit-zone">
                            <label class="login-password-toggle" for="showLoginPassword">
                                <input type="checkbox" id="showLoginPassword" />
                                <span>Show password</span>
                            </label>

                            <div class="login-submit-card">
                                <div class="login-submit-copy">
                                    <h3>Ready to continue</h3>
                                    <p id="loginContextMessage">You will sign in now and return to the most relevant next step for your current journey.</p>
                                </div>

                                <button type="submit" class="th-btn rounded-2 login-primary-cta">Sign in and continue</button>
                            </div>
                        </div>
                    </section>
                </form>

                <p class="login-footnote">
                    New to Easy Cook?
                    <a href="{{ route('register_web') }}">Create your account here</a>
                </p>
            </section>
        </div>
    </div>
</div>

@include('layouts.partial.invitation-awareness-orchestrator')

<script>
    const invitationState = @json($invitationState);
    const invitationAutoCaptured = @json($invitationAutoCaptured ?? false);
    const invitationPublicEnabled = @json($invitationPublicEnabled);

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

    function hasJourneyContextReferrer() {
        return /checkout|checkout-page|assessment|plans|cart|register_new_client/i.test(document.referrer || '');
    }

    function resolvePostLoginDestination() {
        if (hasAssessmentContext() || hasPlanSelectionContext() || hasJourneyContextReferrer()) {
            return 'checkout';
        }

        return 'default';
    }

    function renderContextCues() {
        const finalData = localStorage.getItem('final_data');
        const assessmentStrip = document.getElementById('loginAssessmentStrip');
        const journeyStrip = document.getElementById('loginJourneyStrip');
        const destinationInput = document.getElementById('postLoginDestination');
        const finalDataInput = document.getElementById('finalDataInput');
        const contextMessage = document.getElementById('loginContextMessage');
        const destination = resolvePostLoginDestination();

        destinationInput.value = destination;
        finalDataInput.value = finalData ? finalData : '{}';

        assessmentStrip.hidden = !hasAssessmentContext();
        journeyStrip.hidden = !(destination === 'checkout' || invitationState?.token);

        contextMessage.textContent = destination === 'checkout'
            ? 'You will sign in now and continue with your current checkout or assessment flow.'
            : 'You will sign in now and return to your Easy Cook account dashboard.';
    }

    function buildLoginAwarenessModel(context = {}) {
        if (!invitationPublicEnabled) {
            return null;
        }

        return window.EasyCookInvitationAwareness.buildModel({
            surface: 'login',
            rawState: invitationState,
            context,
        });
    }

    function bindLoginAwarenessActions(model) {
        if (!model) {
            return;
        }

        document.querySelectorAll('#loginInvitationInlineActions [data-awareness-action]').forEach((button) => {
            button.addEventListener('click', () => handleLoginAwarenessAction(button.dataset.awarenessAction, model));
        });
    }

    function renderLoginAwareness(context = {}) {
        const model = buildLoginAwarenessModel(context);

        if (!model) {
            return;
        }

        window.EasyCookInvitationAwareness.syncInlineSurface({
            prefix: 'loginInvitation',
            model,
        });

        bindLoginAwarenessActions(model);
    }

    function focusLoginForm() {
        document.getElementById('email')?.focus();
    }

    function handleLoginAwarenessAction(action) {
        switch (action) {
            case 'create_account':
            case 'replace_invitation':
                window.location.href = '{{ route('register_web') }}';
                return;
            case 'sign_in':
            case 'continue':
            default:
                focusLoginForm();
                return;
        }
    }

    async function showLoginAwarenessModal(context = {}) {
        if (!invitationPublicEnabled) {
            return;
        }

        const model = buildLoginAwarenessModel(context);
        const result = await window.EasyCookInvitationAwareness.prompt(model);

        if (result.action) {
            handleLoginAwarenessAction(result.action, model);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderContextCues();
        renderLoginAwareness();

        document.getElementById('showLoginPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            renderContextCues();
        });

        if (invitationPublicEnabled && invitationState?.token) {
            showLoginAwarenessModal({
                entryMode: invitationAutoCaptured ? 'link' : 'restore',
            });
        }
    });
</script>
