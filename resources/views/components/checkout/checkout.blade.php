@php
    $invitationPublicEnabled = $invitationPublicEnabled ?? config('release_flags.invitation_public_enabled');
    $couponPublicEnabled = $couponPublicEnabled ?? config('release_flags.coupon_public_enabled');
    $discountEntryEnabled = $invitationPublicEnabled || $couponPublicEnabled;
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
        'registered_with_same_code' => false,
        'is_discount_eligible' => false,
        'eligibility_message' => null,
        'discount_label' => null,
    ];
@endphp

<style>
    .meal-table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        margin: 20px 0;
    }

    .meal-table th,
    .meal-table td {
        border: 2px solid #28a745;
        padding: 10px;
        font-size: 16px;
    }

    .meal-table th {
        background-color: #28a745;
        color: white;
    }

    .text-reset {
        color: #258a43 !important;
        font-weight: bold;
    }

    .meal-avatar {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 8px;
        vertical-align: middle;
    }

    .meal-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dropdown-btn {
        background-color: #28a745;
        color: var(--black-color);
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        text-align: left;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .dropdown-btn.active + .dropdown-content {
        display: block;
    }

    .dropdown-content {
        display: none;
    }

    .meal-details-container {
        display: none;
    }

    .cart-productimage img {
        border-radius: 8px;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .checkout-discount-panel {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(23, 58, 35, 0.08);
        border-radius: 24px;
        padding: 28px;
        margin: 24px 0 28px;
        background:
            radial-gradient(circle at top right, rgba(191, 224, 201, 0.32), transparent 32%),
            linear-gradient(180deg, #ffffff 0%, #f6fbf7 100%);
        box-shadow: 0 20px 60px rgba(16, 52, 31, 0.08);
    }

    .checkout-discount-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.24), transparent 55%);
        pointer-events: none;
    }

    .checkout-discount-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .checkout-discount-topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
    }

    .checkout-discount-heading {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .checkout-discount-heading h4 {
        margin: 0;
        color: #143625;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .checkout-discount-heading p {
        margin: 0;
        color: #607a6b;
        font-size: 14px;
        line-height: 1.7;
        max-width: 540px;
    }

    .checkout-discount-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .checkout-discount-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 9px 16px;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 12px;
        line-height: 1;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .checkout-discount-pill--type-coupon {
        background: rgba(20, 54, 37, 0.08);
        border-color: rgba(20, 54, 37, 0.1);
        color: #143625;
    }

    .checkout-discount-pill--type-invitation {
        background: rgba(37, 138, 67, 0.1);
        border-color: rgba(37, 138, 67, 0.16);
        color: #1a6b3a;
    }

    .checkout-discount-pill--type-none {
        background: rgba(98, 122, 107, 0.08);
        border-color: rgba(98, 122, 107, 0.12);
        color: #607a6b;
    }

    .checkout-discount-pill--state-active,
    .checkout-discount-pill--state-ready,
    .checkout-discount-pill--state-override {
        background: rgba(37, 138, 67, 0.12);
        border-color: rgba(37, 138, 67, 0.2);
        color: #1f6f3f;
    }

    .checkout-discount-pill--state-warning,
    .checkout-discount-pill--state-invalid {
        background: rgba(218, 139, 94, 0.12);
        border-color: rgba(218, 139, 94, 0.18);
        color: #a05331;
    }

    .checkout-discount-pill--state-neutral {
        background: rgba(98, 122, 107, 0.08);
        border-color: rgba(98, 122, 107, 0.12);
        color: #607a6b;
    }

    .checkout-discount-controls .form-label {
        color: #193f2b;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .checkout-discount-controls .form-control {
        min-height: 56px;
        border-radius: 16px;
        border: 1px solid rgba(20, 54, 37, 0.12);
        background: rgba(255, 255, 255, 0.9);
        color: #143625;
        font-size: 17px;
        font-weight: 500;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .checkout-discount-controls .form-control:focus {
        border-color: rgba(37, 138, 67, 0.36);
        box-shadow: 0 0 0 4px rgba(37, 138, 67, 0.08);
    }

    .checkout-discount-controls .th-btn {
        min-height: 56px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        box-shadow: 0 16px 30px rgba(37, 138, 67, 0.2);
    }

    .checkout-discount-story {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .checkout-discount-message {
        margin: 0;
        padding: 18px 20px;
        border-radius: 18px;
        border: 1px solid rgba(20, 54, 37, 0.08);
        background: rgba(255, 255, 255, 0.8);
        color: #173a23;
        font-size: 16px;
        line-height: 1.7;
        font-weight: 600;
    }

    .checkout-discount-message--success {
        background: rgba(237, 247, 240, 0.95);
        border-color: rgba(37, 138, 67, 0.12);
    }

    .checkout-discount-message--warning {
        background: rgba(252, 247, 241, 0.96);
        border-color: rgba(218, 139, 94, 0.16);
        color: #7c4b31;
    }

    .checkout-discount-message--neutral {
        color: #4e675a;
    }

    .checkout-discount-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .checkout-discount-detail {
        padding: 16px 18px;
        border-radius: 18px;
        border: 1px solid rgba(20, 54, 37, 0.08);
        background: rgba(255, 255, 255, 0.78);
        min-height: 96px;
    }

    .checkout-discount-detail-label {
        display: block;
        margin-bottom: 8px;
        color: #6a8174;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .checkout-discount-detail-value {
        color: #163826;
        font-size: 16px;
        line-height: 1.6;
        font-weight: 600;
    }

    .checkout-discount-detail-value--muted {
        color: #647b6f;
        font-weight: 500;
    }

    .checkout-discount-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 16px 18px;
        border-radius: 18px;
        border: 1px solid transparent;
        font-size: 14px;
        line-height: 1.7;
        font-weight: 600;
    }

    .checkout-discount-note[hidden] {
        display: none;
    }

    .checkout-discount-note--success {
        background: rgba(230, 245, 235, 0.96);
        border-color: rgba(37, 138, 67, 0.12);
        color: #1f6f3f;
    }

    .checkout-discount-note--error {
        background: rgba(252, 241, 240, 0.96);
        border-color: rgba(180, 35, 24, 0.14);
        color: #b42318;
    }

    .checkout-discount-note--muted {
        background: rgba(249, 251, 250, 0.95);
        border-color: rgba(98, 122, 107, 0.08);
        color: #607a6b;
    }

    .checkout-invitation-code {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        direction: ltr;
        unicode-bidi: isolate;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(20, 54, 37, 0.06);
        color: #163826;
        font-weight: 700;
        font-size: 14px;
        font-family: "Cascadia Mono", "DejaVu Sans Mono", monospace;
        letter-spacing: 0.03em;
    }

    @media (max-width: 991px) {
        .checkout-discount-panel {
            padding: 22px;
        }

        .checkout-discount-topbar {
            flex-direction: column;
        }

        .checkout-discount-badges {
            justify-content: flex-start;
        }
    }

    @media (max-width: 575px) {
        .checkout-discount-panel {
            padding: 18px;
            border-radius: 20px;
        }

        .checkout-discount-heading h4 {
            font-size: 18px;
        }

        .checkout-discount-message,
        .checkout-discount-detail,
        .checkout-discount-note {
            border-radius: 16px;
        }
    }
</style>

<div class="th-checkout-wrapper mt-5 space-extra-bottom container">
    <div class="container">
        <form action="#" class="woocommerce-checkout mt-4 p-4 bg-white shadow rounded">
            <div class="row">
                <div class="col-lg-12">
                    <h2 style="color: var(--title-color) !important;" class="h4 mb-3">Address Details</h2>

                    @php
                        $primaryAddress = Auth::check()
                            ? Auth::user()->addresses()->where('primary', true)->first()
                            : null;

                        $selectedCity = old('town_city') ?? ($primaryAddress->town_city ?? '');
                    @endphp

                    <div class="mt-4">
                        <div class="mb-3">
                            <select id="billing_address_city" name="town_city" class="form-control border-2 border-secondary rounded shadow-sm">
                                <option value="" disabled {{ $selectedCity === '' ? 'selected' : '' }}>Select a city</option>
                                <option value="Cairo" {{ $selectedCity === 'Cairo' ? 'selected' : '' }}>Cairo</option>
                                <option value="Giza" {{ $selectedCity === 'Giza' ? 'selected' : '' }}>Giza</option>
                                <option value="North Coast" {{ $selectedCity === 'North Coast' ? 'selected' : '' }}>North Coast</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="street_address" class="form-label fw-semibold text-secondary">Street Address</label>
                            <input
                                id="street_address"
                                name="street_address"
                                type="text"
                                value="{{ old('street_address') ?? ($primaryAddress->street_address ?? '') }}"
                                class="form-control border-2 border-secondary rounded shadow-sm"
                                placeholder="Street Address"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-12 mt-3">
                <h2 class="h4">Your Details</h2>
                <div class="row">
                    <input type="hidden" id="finalDataInput" name="final_data" />
                    <input type="hidden" id="checkoutInvitationJourneyToken" value="{{ $invitationState['token'] ?? '' }}" />
                    <input type="hidden" id="checkoutInvitationCodeHidden" value="{{ $invitationState['code'] ?? '' }}" />

                    <div class="col-md-6 form-group">
                        <input type="text" class="form-control" value="{{ Auth::user()->first_name ?? old('first_name') }}"
                               name="first_name" placeholder="First Name" {{ Auth::check() ? 'disabled' : '' }} />
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="text" class="form-control" value="{{ Auth::user()->last_name ?? old('last_name') }}"
                               name="last_name" placeholder="Last Name" {{ Auth::check() ? 'disabled' : '' }} />
                    </div>

                    <div class="col-12 form-group">
                        <input type="email" class="form-control" name="email"
                               value="{{ Auth::user()->email ?? old('email') }}"
                               placeholder="Email Address" {{ Auth::check() ? 'disabled' : '' }} />
                    </div>

                    <div class="col-12 form-group">
                        <input type="text" class="form-control" id="phone" name="phone"
                               value="{{ Auth::user()->phone ?? old('phone') }}"
                               pattern="^(01)[0-2,5]{1}[0-9]{8}$"
                               title="Enter a valid Egyptian phone number, e.g. 01012345678"
                               placeholder="Phone number"
                               {{ Auth::check() ? 'disabled' : '' }} />
                    </div>

                    @if(!Auth::check())
                        <div class="col-12 form-group">
                            <input type="password" class="form-control mb-4" id="password" name="password"
                                   placeholder="Password" required />
                            <input type="password" class="form-control mt-2" id="password_confirmation"
                                   name="password_confirmation" placeholder="Confirm Password" required />

                            <input type="checkbox" class="form-check-input rounded-2" id="showPassword" onclick="toggleAllPasswords()">
                            <label class="form-check-label my-3" for="showPassword">Show Password</label>
                        </div>

                        <div class="col-md-6 form-group">
                            <select id="referral_source" class="form-control">
                                <option value="">How did you hear about us?</option>
                                <option value="meta" {{ ($invitationState['referral_source'] ?? '') === 'meta' ? 'selected' : '' }}>Meta</option>
                                <option value="google" {{ ($invitationState['referral_source'] ?? '') === 'google' ? 'selected' : '' }}>Google</option>
                                <option value="direct_link" {{ ($invitationState['referral_source'] ?? '') === 'direct_link' ? 'selected' : '' }}>Direct Link</option>
                                <option value="captain_gym" {{ ($invitationState['referral_source'] ?? '') === 'captain_gym' ? 'selected' : '' }}>Captain / Gym</option>
                                <option value="other" {{ ($invitationState['referral_source'] ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group" id="referral_source_note_wrap" style="display:none;">
                            <input
                                type="text"
                                class="form-control"
                                id="referral_source_note"
                                placeholder="Other source note"
                                value="{{ $invitationState['referral_source_note'] ?? '' }}"
                            />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($discountEntryEnabled)
            <div class="checkout-discount-panel">
                <div class="checkout-discount-inner">
                    <div class="checkout-discount-topbar">
                        <div class="checkout-discount-heading">
                            <h4>Discount Code</h4>
                            <p>Choose the code you want this checkout to honor, with invitation truth and discount impact visible in one refined surface.</p>
                        </div>
                        <div class="checkout-discount-badges">
                            <span id="checkoutDiscountTypeBadge" class="checkout-discount-pill checkout-discount-pill--type-none">No Discount</span>
                            <span id="checkoutDiscountStateBadge" class="checkout-discount-pill checkout-discount-pill--state-neutral">Neutral</span>
                        </div>
                    </div>

                    <div class="row g-3 align-items-end checkout-discount-controls">
                        <div class="col-lg-9">
                            <label for="coupon_input" class="form-label">Invitation or coupon code</label>
                            <input type="text" id="coupon_input" class="form-control" placeholder="Write your invitation or coupon code" />
                            <input type="hidden" id="checkoutInvitationCodeInput" value="{{ $invitationState['code'] ?? '' }}">
                        </div>
                        <div class="col-lg-3">
                            <button type="button" id="apply_coupon_btn" class="th-btn rounded-2 w-100">Apply Code</button>
                        </div>
                    </div>

                    <div class="checkout-discount-story">
                        <p id="checkoutInvitationMessage" class="checkout-discount-message checkout-discount-message--neutral">
                            No invitation or coupon code is active yet. You can continue without one.
                        </p>
                        <div id="checkoutInvitationMeta" class="checkout-discount-details"></div>
                        <div id="coupon_status" class="checkout-discount-note checkout-discount-note--muted" hidden></div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-light border mb-4">
                Discount codes are currently unavailable in this release. Checkout will continue with standard pricing only.
            </div>
        @endif

        <h4 class="mt-4 pt-lg-2">Your Order</h4>
        <table class="cart_table mb-20">
            <thead>
                <tr style="background-color: var(--theme-color) !important">
                    <th class="cart-col-productname text-center">Your Plan</th>
                    <th class="cart-col-price text-center">Total</th>
                    <th class="cart-col-total text-center" colspan="4">Your Meals</th>
                </tr>
            </thead>
            <tbody id="checkout-order-body">
                <tr class="cart_item">
                    <td data-title="Name" class="text-center" colspan="3">Loading your subscription...</td>
                </tr>
            </tbody>
        </table>

        <table class="cart_table">
            <thead class="text-center">
                <tr style="background-color: var(--theme-color) !important">
                    <th class="cart-col-dropdown text-center" colspan="4">Details</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot class="checkout-ordertable">
                <tr class="cart-subtotal">
                    <th style="color: var(--title-color) !important;" class="text-center">Subtotal</th>
                    <td data-title="Subtotal" colspan="4" class="text-center">
                        <span class="woocommerce-Price-amount amount">
                            <bdi style="color: var(--title-color) !important;">
                                <span id="woocommercePrice_sub_total" class="woocommerce-Price-currencySymbol"></span>
                            </bdi>
                        </span>
                    </td>
                </tr>
                <tr class="cart-discount">
                    <th style="color: var(--title-color) !important;" class="text-center">Discount</th>
                    <td data-title="Discount" colspan="4" class="text-center">
                        <span class="woocommerce-Price-amount amount">
                            <bdi style="color: var(--title-color) !important;">
                                <span id="woocommercePrice_discount"></span>
                            </bdi>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th style="color: var(--title-color) !important;" class="text-center">VAT</th>
                    <td style="color: var(--title-color) !important;" class="text-center" data-title="VAT" colspan="4">
                        <span id="woocommercePrice_vat_label">{{ number_format(($vatRate ?? 0.14) * 100, 0) }}%</span>
                        <span class="d-block" id="woocommercePrice_vat"></span>
                    </td>
                </tr>
                <tr class="woocommerce-shipping-totals shipping">
                    <th style="color: var(--title-color) !important;" class="text-center">Shipping</th>
                    <td style="color: var(--title-color) !important;" class="text-center" data-title="Shipping" colspan="4">
                        Free shipping
                    </td>
                </tr>
                <tr class="order-total">
                    <th style="color: var(--title-color) !important;" class="text-center">Total</th>
                    <td data-title="Total" colspan="4" class="text-center">
                        <strong>
                            <span class="woocommerce-Price-amount amount">
                                <bdi>
                                    <span id="woocommercePrice" class="woocommerce-Price-currencySymbol_tax"></span>
                                </bdi>
                            </span>
                        </strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <button class="th-btn wc-forward" id="pay-now2">Subscribe</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@include('layouts.partial.invitation-awareness-orchestrator')

<script>
    window.userId = {!! auth()->check() ? auth()->id() : 'null' !!};
    window.checkoutPricingMatrix = @json($pricingMatrix ?? []);
    window.checkoutVatRate = Number(@json($vatRate ?? 0.14));
    const invitationAutoCaptured = @json($invitationAutoCaptured ?? false);
    const invitationPublicEnabled = @json($invitationPublicEnabled);
    const couponPublicEnabled = @json($couponPublicEnabled);
    const discountEntryEnabled = invitationPublicEnabled || couponPublicEnabled;

    const couponState = {
        version: null,
        id: null,
        code: null,
        discountType: null,
        discountValue: null,
        discountLabel: null,
        pricing: null,
    };

    const couponFeedbackState = {
        message: '',
        tone: 'muted',
    };
    const checkoutRecoveryState = {
        message: '',
    };

    const invitationState = @json($invitationState);

    function buildCheckoutAwarenessModel(context = {}) {
        return window.EasyCookInvitationAwareness.buildModel({
            surface: 'checkout',
            rawState: invitationState,
            context,
        });
    }

    function rememberCheckoutAwarenessChoice(model, action) {
        const key = window.EasyCookInvitationAwareness.choiceKey(model);
        const persistableActions = new Set([
            'keep',
            'use_another_coupon',
            'restore_invitation',
            'keep_override',
            'continue',
        ]);

        if (key && action && persistableActions.has(action)) {
            sessionStorage.setItem(key, action);
        }
    }

    function readCheckoutAwarenessChoice(model) {
        const key = window.EasyCookInvitationAwareness.choiceKey(model);
        return key ? sessionStorage.getItem(key) : null;
    }

    document.addEventListener('DOMContentLoaded', async () => {
        document.getElementById('pay-now2').addEventListener('click', async (event) => {
            event.preventDefault();
            await handlePayNowClick();
        });

        document.getElementById('apply_coupon_btn')?.addEventListener('click', () => applyCoupon());

        window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
        await loadCartTable();
        observeStorageChanges();
        bindInputSanitizers();
        syncInvitationFields(invitationState);
        if (discountEntryEnabled) {
            renderInvitationState(invitationState);
        }
        bindReferralFieldState();
        if (discountEntryEnabled) {
            await restorePersistedDiscountPricing(true);
        }
        if (invitationPublicEnabled) {
            await handleInvitationEntryFlow();
        }
    });

    function formatCurrency(value) {
        const numeric = Number(value || 0);
        return `${numeric.toFixed(2)} EGP`;
    }

    function setCouponStatus(message, isError = false, tone = null) {
        couponFeedbackState.message = message || '';
        couponFeedbackState.tone = tone || (message ? (isError ? 'error' : 'success') : 'muted');
        renderInvitationState(invitationState);
    }

    function showCouponAlert(icon, title, message) {
        Swal.fire({
            icon,
            title,
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#258a43',
        });
    }

    function resetCouponState() {
        couponState.version = null;
        couponState.id = null;
        couponState.code = null;
        couponState.discountType = null;
        couponState.discountValue = null;
        couponState.discountLabel = null;
        couponState.pricing = null;
    }

    function clearAppliedCoupon(statusMessage = '', isError = false, tone = null) {
        resetCouponState();
        renderTotals();
        setCouponStatus(statusMessage, isError, tone);
    }

    function bindReferralFieldState() {
        const referralSource = document.getElementById('referral_source');
        const referralNoteWrap = document.getElementById('referral_source_note_wrap');

        if (!referralSource || !referralNoteWrap) {
            return;
        }

        const toggle = () => {
            referralNoteWrap.style.display = referralSource.value === 'other' ? 'block' : 'none';
        };

        referralSource.addEventListener('change', toggle);
        toggle();
    }

    function syncInvitationFields(state) {
        document.getElementById('checkoutInvitationJourneyToken').value = state?.token || '';
        document.getElementById('checkoutInvitationCodeHidden').value = state?.code || '';
        const checkoutInvitationCodeInput = document.getElementById('checkoutInvitationCodeInput');
        if (checkoutInvitationCodeInput) {
            checkoutInvitationCodeInput.value = state?.code || '';
        }

        const couponInput = document.getElementById('coupon_input');
        if (couponInput && state?.has_override && state?.active_coupon?.code) {
            couponInput.value = state.active_coupon.code;
        }

        const referralSource = document.getElementById('referral_source');
        const referralNote = document.getElementById('referral_source_note');

        if (referralSource && state?.referral_source) {
            referralSource.value = state.referral_source;
        }

        if (referralNote && state?.referral_source_note) {
            referralNote.value = state.referral_source_note;
        }
    }

    function currentCheckoutCodeLabel(state) {
        if (couponState.code) {
            return couponState.code;
        }

        if (state?.has_override && state?.active_coupon?.code) {
            return state.active_coupon.code;
        }

        if (state?.active_coupon?.version === 'invitation' && state?.is_discount_eligible) {
            return state.active_coupon.code;
        }

        return null;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderCodeChip(value) {
        return `<span class="checkout-invitation-code">${escapeHtml(value || '-')}</span>`;
    }

    function resolveInvitationTruthSummary(state) {
        if (!state?.token) {
            return 'Not linked';
        }

        if (!state.is_valid) {
            return 'Invalid invitation saved';
        }

        if (state.has_override && state.active_coupon?.version !== 'invitation') {
            return state.code
                ? `Saved with ${renderCodeChip(state.code)} in invitation truth`
                : 'Saved with manual coupon override';
        }

        if (state.is_discount_eligible) {
            return state.code
                ? `Saved and eligible with ${renderCodeChip(state.code)}`
                : 'Saved and eligible';
        }

        return 'Locked until the same registered account signs in';
    }

    function renderDiscountDetails(items) {
        return items
            .filter((item) => item && item.label && item.value)
            .map((item) => `
                <div class="checkout-discount-detail">
                    <span class="checkout-discount-detail-label">${escapeHtml(item.label)}</span>
                    <div class="checkout-discount-detail-value${item.muted ? ' checkout-discount-detail-value--muted' : ''}">${item.value}</div>
                </div>
            `)
            .join('');
    }

    function renderDiscountNote() {
        const note = document.getElementById('coupon_status');

        if (!couponFeedbackState.message) {
            note.hidden = true;
            note.textContent = '';
            note.className = 'checkout-discount-note checkout-discount-note--muted';

            return;
        }

        note.hidden = false;
        note.textContent = couponFeedbackState.message;
        note.className = `checkout-discount-note checkout-discount-note--${couponFeedbackState.tone || 'muted'}`;
    }

    function buildDiscountPresentation(state) {
        const activeCode = currentCheckoutCodeLabel(state);
        const invitationDiscountLabel = state?.discount_label
            ? `${escapeHtml(state.discount_label)} off`
            : 'Discount configured';

        if (couponState.version && couponState.code) {
            return {
                typeLabel: 'Coupon',
                typeClass: 'checkout-discount-pill checkout-discount-pill--type-coupon',
                stateLabel: state?.has_override ? 'Override' : 'Active',
                stateClass: `checkout-discount-pill ${
                    state?.has_override
                        ? 'checkout-discount-pill--state-override'
                        : 'checkout-discount-pill--state-active'
                }`,
                message: state?.has_override
                    ? 'A manual coupon override is currently active for this checkout. Your totals already reflect the selected discount.'
                    : 'Coupon pricing is currently active for this checkout. Your totals already reflect the selected discount.',
                messageClass: 'checkout-discount-message checkout-discount-message--success',
                details: [
                    {
                        label: 'Active Type',
                        value: 'Coupon',
                    },
                    {
                        label: 'Code',
                        value: renderCodeChip(couponState.code),
                    },
                    {
                        label: 'Discount',
                        value: escapeHtml(couponState.discountLabel || `${couponState.discountValue || 0}`),
                    },
                    {
                        label: 'Invitation Truth',
                        value: resolveInvitationTruthSummary(state),
                        muted: true,
                    },
                ],
            };
        }

        if (!state?.token) {
            return {
                typeLabel: 'No Discount',
                typeClass: 'checkout-discount-pill checkout-discount-pill--type-none',
                stateLabel: 'Neutral',
                stateClass: 'checkout-discount-pill checkout-discount-pill--state-neutral',
                message: 'No invitation or coupon code is active yet. You can continue without one or apply a code below.',
                messageClass: 'checkout-discount-message checkout-discount-message--neutral',
                details: [
                    {
                        label: 'Checkout Status',
                        value: 'No discount code is active.',
                        muted: true,
                    },
                    {
                        label: 'Invitation Truth',
                        value: 'No linked invitation yet.',
                        muted: true,
                    },
                ],
            };
        }

        if (!state.is_valid) {
            return {
                typeLabel: 'Invitation',
                typeClass: 'checkout-discount-pill checkout-discount-pill--type-invitation',
                stateLabel: 'Invalid',
                stateClass: 'checkout-discount-pill checkout-discount-pill--state-invalid',
                message: state.message || 'This invitation code is invalid. You can continue without a discount.',
                messageClass: 'checkout-discount-message checkout-discount-message--warning',
                details: [
                    {
                        label: 'Last Tried',
                        value: renderCodeChip(state.last_invalid_code || state.code || '-'),
                    },
                    {
                        label: 'Status',
                        value: 'Invitation truth is stored, but no discount is available.',
                        muted: true,
                    },
                ],
            };
        }

        if (!state.is_discount_eligible) {
            return {
                typeLabel: 'Invitation',
                typeClass: 'checkout-discount-pill checkout-discount-pill--type-invitation',
                stateLabel: 'Registration Required',
                stateClass: 'checkout-discount-pill checkout-discount-pill--state-warning',
                message: state.eligibility_message || 'This invitation discount is available only for accounts registered with this invitation code.',
                messageClass: 'checkout-discount-message checkout-discount-message--warning',
                details: [
                    {
                        label: 'Invite Code',
                        value: renderCodeChip(state.code || '-'),
                    },
                    {
                        label: 'Discount',
                        value: invitationDiscountLabel,
                    },
                    {
                        label: 'Eligibility',
                        value: 'Register or sign in with the same invitation code before using this discount.',
                        muted: true,
                    },
                ],
            };
        }

        return {
            typeLabel: 'Invitation',
            typeClass: 'checkout-discount-pill checkout-discount-pill--type-invitation',
            stateLabel: couponState.version === 'invitation' || activeCode === state.code ? 'Active' : 'Ready',
            stateClass: `checkout-discount-pill ${
                couponState.version === 'invitation' || activeCode === state.code
                    ? 'checkout-discount-pill--state-active'
                    : 'checkout-discount-pill--state-ready'
            }`,
            message: couponState.version === 'invitation' || activeCode === state.code
                ? 'Invitation pricing is currently active for this checkout. Your totals already reflect the invitation discount.'
                : 'Your invitation discount is ready for checkout. You can keep it active or choose another coupon.',
            messageClass: 'checkout-discount-message checkout-discount-message--success',
            details: [
                {
                    label: 'Active Type',
                    value: 'Invitation',
                },
                {
                    label: 'Invite Code',
                    value: renderCodeChip(state.code || '-'),
                },
                {
                    label: 'Discount',
                    value: invitationDiscountLabel,
                },
                {
                    label: 'Override Status',
                    value: 'None',
                    muted: true,
                },
            ],
        };
    }

    function renderInvitationState(state) {
        const typeBadge = document.getElementById('checkoutDiscountTypeBadge');
        const stateBadge = document.getElementById('checkoutDiscountStateBadge');
        const message = document.getElementById('checkoutInvitationMessage');
        const meta = document.getElementById('checkoutInvitationMeta');

        if (!typeBadge || !stateBadge || !message || !meta) {
            return;
        }

        const presentation = buildDiscountPresentation(state);

        typeBadge.textContent = presentation.typeLabel;
        typeBadge.className = presentation.typeClass;

        stateBadge.textContent = presentation.stateLabel;
        stateBadge.className = presentation.stateClass;

        message.textContent = presentation.message;
        message.className = presentation.messageClass;

        meta.innerHTML = renderDiscountDetails(presentation.details || []);
        renderDiscountNote();
    }

    function clearInvitationCodeInputIfUsingOtherCoupon() {
        const couponInput = document.getElementById('coupon_input');

        if (couponInput && couponInput.value === (invitationState?.code || '')) {
            couponInput.value = '';
        }
    }

    async function applyInvitationCodeFromState(silent = false) {
        if (!invitationState?.code || !invitationState?.is_discount_eligible) {
            return;
        }

        document.getElementById('coupon_input').value = invitationState.code;
        await applyCoupon(false, silent, invitationState.code);
    }

    async function restorePersistedDiscountPricing(silent = true) {
        const activeCoupon = invitationState?.active_coupon;

        if (!activeCoupon?.code || collectSubscriptions().length !== 1) {
            return;
        }

        if (activeCoupon.version === 'invitation' && !invitationState?.is_discount_eligible) {
            return;
        }

        document.getElementById('coupon_input').value = activeCoupon.code;
        await applyCoupon(
            invitationState?.has_override && activeCoupon.version !== 'invitation',
            silent,
            activeCoupon.code
        );
    }

    function restoreManualCouponMode() {
        if (invitationState?.has_override && invitationState?.active_coupon?.code && invitationState.active_coupon.version !== 'invitation') {
            document.getElementById('coupon_input').value = invitationState.active_coupon.code;
            setCouponStatus(`You can keep using ${invitationState.active_coupon.code} or replace it with another coupon.`, false, 'muted');
            return;
        }

        clearInvitationCodeInputIfUsingOtherCoupon();

        if (couponState.version === 'invitation') {
            clearAppliedCoupon('You can enter another coupon code if you prefer.', false, 'muted');
            loadCartTable();
            return;
        }

        setCouponStatus('You can enter another coupon code if you prefer.', false, 'muted');
    }

    async function resolveCheckoutAwarenessAction(action) {
        switch (action) {
            case 'keep':
                await applyInvitationCodeFromState(false);
                return { canContinueCheckout: couponState.version === 'invitation' };
            case 'use_another_coupon':
                restoreManualCouponMode();
                return { canContinueCheckout: false };
            case 'restore_invitation':
                await applyInvitationCodeFromState(false);
                return { canContinueCheckout: couponState.version === 'invitation' };
            case 'keep_override':
                restoreManualCouponMode();
                return { canContinueCheckout: true };
            case 'sign_in':
                window.location.href = '{{ route('login_web') }}';
                return { canContinueCheckout: false };
            case 'continue':
            case 'keep_saved_code':
            default:
                return { canContinueCheckout: action === 'continue' };
        }
    }

    async function handleInvitationEntryFlow({ afterRegistration = false } = {}) {
        const model = buildCheckoutAwarenessModel({ afterRegistration });

        if (model.family === 'empty') {
            return { canContinueCheckout: true };
        }

        const storedChoice = readCheckoutAwarenessChoice(model);
        if (storedChoice) {
            return resolveCheckoutAwarenessAction(storedChoice);
        }

        const result = await window.EasyCookInvitationAwareness.prompt(model);
        if (result.action) {
            rememberCheckoutAwarenessChoice(model, result.action);
            return resolveCheckoutAwarenessAction(result.action);
        }

        if (model.family === 'eligible') {
            return { canContinueCheckout: couponState.version === 'invitation' };
        }

        return { canContinueCheckout: false };
    }

    function toggleAllPasswords() {
        document.querySelectorAll('#password, #password_confirmation').forEach((field) => {
            field.type = field.type === 'password' ? 'text' : 'password';
        });
    }

    function bindInputSanitizers() {
        const streetInput = document.getElementById('street_address');
        if (streetInput) {
            streetInput.addEventListener('input', function () {
                this.value = this.value.replace(/[^A-Za-z\u0600-\u06FF0-9\s.,\-/#'"،]/g, '');
            });
        }

        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '');
            });
        }
    }

    function observeStorageChanges() {
        window.addEventListener('storage', (event) => {
            if (event.key === 'customizeMealsSave' || event.key === 'standerdPackages') {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                loadCartTable();
            }
        });

        const originalSetItem = localStorage.setItem;
        localStorage.setItem = function (key, value) {
            originalSetItem.apply(this, arguments);

            if (key === 'customizeMealsSave' || key === 'standerdPackages') {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                loadCartTable();
            }
        };
    }

    function normalizePlanId(planId) {
        return String(planId || '').replace(/\D+/g, '');
    }

    function getSelectionStorage() {
        return window.EasyCookSelectionStorage || null;
    }

    function getStoredWeekKeys(planData) {
        return getSelectionStorage()?.extractWeekKeys?.(planData)
            || Object.keys(planData || {}).filter((key) => /^week\d+$/i.test(key));
    }

    function getStoredWeeksCount(planData) {
        return getSelectionStorage()?.resolveStoredWeeksCount?.(planData) || 0;
    }

    function buildSubscription(planId, planData, type) {
        const helper = getSelectionStorage();
        const helperSubscription = helper?.buildSubscription?.(planId, planData, type, window.userId);
        if (helperSubscription) {
            return helperSubscription;
        }

        const weekKeys = getStoredWeekKeys(planData);
        const daysPerWeek = helper?.resolveStoredDaysPerWeek?.(planData)
            || (weekKeys.length ? Object.keys(planData[weekKeys[0]] || {}).length : 0);

        if (!daysPerWeek) {
            return null;
        }

        return {
            user_id: window.userId,
            plan_id: planId,
            weeks_count: getStoredWeeksCount(planData),
            days_per_week: daysPerWeek,
            meals: extractMeals(planData),
            type,
            package_name: planData.packageName || `${normalizePlanId(planId)} Calories`,
        };
    }

    function collectSubscriptions() {
        const helperSubscriptions = getSelectionStorage()?.collectSubscriptions?.(window.userId);
        if (Array.isArray(helperSubscriptions)) {
            return helperSubscriptions;
        }

        const subscriptions = [];
        const storedCustomized = JSON.parse(localStorage.getItem('customizeMealsSave') || '{}');
        const storedStandard = JSON.parse(localStorage.getItem('standerdPackages') || '{}');

        Object.entries(storedCustomized).forEach(([planId, planData]) => {
            const subscription = buildSubscription(planId, planData, 'customized');
            if (subscription) {
                subscriptions.push(subscription);
            }
        });

        Object.entries(storedStandard).forEach(([planId, planData]) => {
            const subscription = buildSubscription(planId, planData, 'standard');
            if (subscription) {
                subscriptions.push(subscription);
            }
        });

        return subscriptions;
    }

    function getSingleSubscriptionContext() {
        const subscriptions = collectSubscriptions();

        if (subscriptions.length !== 1) {
            return null;
        }

        const [subscription] = subscriptions;

        return {
            plan_id: subscription.plan_id,
            weeks_count: subscription.weeks_count,
            days_per_week: subscription.days_per_week,
            type: subscription.type,
        };
    }

    function resolveWeeksCount(subscription) {
        const parsedWeeksCount = Number(subscription?.weeks_count || 4);

        if (!Number.isFinite(parsedWeeksCount) || parsedWeeksCount < 1) {
            return 4;
        }

        return parsedWeeksCount;
    }

    function resolveCheckoutTier(subscription) {
        const planId = normalizePlanId(subscription.plan_id);
        const daysPerWeek = String(subscription.days_per_week);
        const weeksCount = String(resolveWeeksCount(subscription));
        const planCatalog = window.checkoutPricingMatrix?.[planId]
            || window.checkoutPricingMatrix?.[Number(planId)];
        const daysCatalog = planCatalog?.[daysPerWeek]
            || planCatalog?.[Number(subscription.days_per_week)];

        return daysCatalog?.[weeksCount]
            || daysCatalog?.[Number(weeksCount)]
            || null;
    }

    function resolveBasePricing(subscription) {
        const planId = normalizePlanId(subscription.plan_id);
        const weeksCount = resolveWeeksCount(subscription);
        const tier = resolveCheckoutTier(subscription);

        if (!tier) {
            throw new Error(`Unsupported subscription pricing combination for ${planId} calories, ${subscription.days_per_week} delivery days, and ${weeksCount} weeks.`);
        }

        const totalBeforeDiscount = Number(tier.without_vat || 0);
        const vatAmount = Number((totalBeforeDiscount * window.checkoutVatRate).toFixed(2));

        return {
            total_before_discount: totalBeforeDiscount,
            discount_amount: 0,
            total_after_discount: totalBeforeDiscount,
            vat_amount: vatAmount,
            grand_total: Number((totalBeforeDiscount + vatAmount).toFixed(2)),
        };
    }

    function aggregateBasePricing(subscriptions) {
        return subscriptions.reduce((carry, subscription) => {
            const pricing = resolveBasePricing(subscription);

            carry.total_before_discount += Number(pricing.total_before_discount || 0);
            carry.discount_amount += Number(pricing.discount_amount || 0);
            carry.total_after_discount += Number(pricing.total_after_discount || 0);
            carry.vat_amount += Number(pricing.vat_amount || 0);
            carry.grand_total += Number(pricing.grand_total || 0);

            return carry;
        }, {
            total_before_discount: 0,
            discount_amount: 0,
            total_after_discount: 0,
            vat_amount: 0,
            grand_total: 0,
        });
    }

    function pricingForSubscription(subscription, index, subscriptions) {
        if (couponState.pricing && subscriptions.length === 1 && index === 0) {
            return couponState.pricing;
        }

        return resolveBasePricing(subscription);
    }

    function currentPricingEntries(subscriptions = collectSubscriptions()) {
        return subscriptions.map((subscription, index) => pricingForSubscription(subscription, index, subscriptions));
    }

    function buildPricingViewModel(subscriptions = collectSubscriptions()) {
        const entries = currentPricingEntries(subscriptions);

        return {
            subscriptions,
            entries,
            summary: aggregatePricingEntries(entries),
        };
    }

    function aggregatePricingEntries(entries) {
        return entries.reduce((carry, pricing) => {
            carry.total_before_discount += Number(pricing.total_before_discount || 0);
            carry.discount_amount += Number(pricing.discount_amount || 0);
            carry.total_after_discount += Number(pricing.total_after_discount || 0);
            carry.vat_amount += Number(pricing.vat_amount || 0);
            carry.grand_total += Number(pricing.grand_total || 0);

            return carry;
        }, {
            total_before_discount: 0,
            discount_amount: 0,
            total_after_discount: 0,
            vat_amount: 0,
            grand_total: 0,
        });
    }

    function currentPricingSummary() {
        return buildPricingViewModel().summary;
    }

    function renderTotals(summary = null) {
        try {
            const resolvedSummary = summary || currentPricingSummary();

            document.getElementById('woocommercePrice_sub_total').textContent = formatCurrency(resolvedSummary.total_before_discount);
            document.getElementById('woocommercePrice_discount').textContent = formatCurrency(resolvedSummary.discount_amount || 0);
            document.getElementById('woocommercePrice_vat').textContent = formatCurrency(resolvedSummary.vat_amount || 0);
            document.getElementById('woocommercePrice').textContent = formatCurrency(resolvedSummary.grand_total || 0);
        } catch (error) {
            console.error('renderTotals failed', error);
            document.getElementById('woocommercePrice_sub_total').textContent = '--';
            document.getElementById('woocommercePrice_discount').textContent = '--';
            document.getElementById('woocommercePrice_vat').textContent = '--';
            document.getElementById('woocommercePrice').textContent = '--';
            setCouponStatus(error.message || 'Pricing configuration error.', true);
        }
    }

    async function fetchMealData() {
        try {
            const response = await fetch('/customized.json');
            if (!response.ok) {
                throw new Error(response.statusText);
            }

            return await response.json();
        } catch (error) {
            console.error('fetchMealData failed', error);
            return {};
        }
    }

    async function fetchStanderdData() {
        try {
            const response = await fetch('/standred_weeks.json');
            if (!response.ok) {
                throw new Error(response.statusText);
            }

            return await response.json();
        } catch (error) {
            console.error('fetchStanderdData failed', error);
            return {};
        }
    }

    function extractMeals(planData) {
        const helperMeals = getSelectionStorage()?.extractMeals?.(planData);
        if (Array.isArray(helperMeals)) {
            return helperMeals.map((meal) => ({
                ...meal,
                day: meal.day.charAt(0).toUpperCase() + meal.day.slice(1).toLowerCase(),
            }));
        }

        return [];
    }

    function renderSubscriptionRow(tbody, subscription, pricing) {
        const row = document.createElement('tr');
        row.className = 'cart_item';
        row.dataset.packageId = subscription.plan_id;
        row.dataset.packageType = subscription.type === 'standard' ? 'stander' : 'meals';
        row.dataset.packageGrandTotal = String(Number(pricing.grand_total || 0));
        row.innerHTML = `
            <td data-title="Name" class="text-center">
                <span class="cart-productname text-center">${subscription.package_name}</span>
            </td>
            <td data-title="Total" class="text-center">
                <span class="amountstanderd text-center"><bdi class="text-center">${formatCurrency(pricing.grand_total)}</bdi></span>
            </td>
            <td data-title="Details" class="text-center">
                <button class="dropdown-btn text-center" data-package-id="${subscription.plan_id}" data-package-type="${subscription.type === 'standard' ? 'stander' : 'meals'}">▼</button>
            </td>
        `;
        tbody.appendChild(row);

        const detailsRow = document.createElement('tr');
        detailsRow.innerHTML = `
            <td colspan="6" class="text-center">
                <div class="meal-details-container text-center" id="details-${subscription.type === 'standard' ? 'stander' : 'meals'}-${subscription.plan_id}" style="display:none;"></div>
            </td>
        `;
        tbody.appendChild(detailsRow);
    }

    async function loadCartTable() {
        const tbody = document.getElementById('checkout-order-body');
        tbody.innerHTML = '';
        checkoutRecoveryState.message = window.EasyCookSelectionStorage?.consumeRecoveryMessage?.() || checkoutRecoveryState.message;

        const subscriptions = collectSubscriptions();

        if (subscriptions.length === 0) {
            tbody.innerHTML = `
                <tr class="cart_item">
                    <td data-title="Name" class="text-center" colspan="3">${checkoutRecoveryState.message || 'No subscription found in checkout.'}</td>
                </tr>
            `;
            couponState.version = null;
            couponState.id = null;
            couponState.code = null;
            couponState.discountType = null;
            couponState.discountValue = null;
            couponState.discountLabel = null;
            couponState.pricing = null;
            setCouponStatus('');
            renderTotals();
            return;
        }

        try {
            const pricingViewModel = buildPricingViewModel(subscriptions);
            pricingViewModel.subscriptions.forEach((subscription, index) => {
                renderSubscriptionRow(tbody, subscription, pricingViewModel.entries[index]);
            });
            renderTotals(pricingViewModel.summary);
            bindDetailButtons();
        } catch (error) {
            console.error('loadCartTable failed', error);
            tbody.innerHTML = `
                <tr class="cart_item">
                    <td data-title="Name" class="text-center" colspan="3">${error.message || 'Pricing configuration error.'}</td>
                </tr>
            `;
            clearAppliedCoupon(error.message || 'Pricing configuration error.', true);
            showCouponAlert('error', 'Pricing Error', error.message || 'Pricing configuration error.');
        }
    }

    function bindDetailButtons() {
        document.querySelectorAll('.dropdown-btn').forEach((button) => {
            button.onclick = async (event) => {
                event.preventDefault();

                const packageId = button.dataset.packageId;
                const type = button.dataset.packageType;
                const container = document.getElementById(`details-${type}-${packageId}`);
                const isOpen = container.style.display === 'block';

                document.querySelectorAll('.meal-details-container').forEach((element) => {
                    element.style.display = 'none';
                });
                document.querySelectorAll('.dropdown-btn').forEach((element) => {
                    element.textContent = '▼';
                });

                if (!isOpen) {
                    await populateDetails(type, packageId);
                    container.style.display = 'block';
                    button.textContent = '▾';
                }
            };
        });
    }

    async function populateDetails(type, packageId) {
        const container = document.getElementById(`details-${type}-${packageId}`);
        container.innerHTML = '';

        const helper = getSelectionStorage();
        const store = type === 'meals'
            ? (helper?.readCustomizeStore?.() || JSON.parse(localStorage.getItem('customizeMealsSave') || '{}'))
            : (helper?.readStandardStore?.() || JSON.parse(localStorage.getItem('standerdPackages') || '{}'));
        const packageData = store[packageId];
        if (!packageData) {
            return;
        }

        const data = type === 'meals' ? await fetchMealData() : await fetchStanderdData();
        const weekKeys = getStoredWeekKeys(packageData);

        const tabsWrapper = document.createElement('div');
        tabsWrapper.className = 'week-tabs text-center mb-4';

        const tableContainer = document.createElement('div');
        tableContainer.className = 'table-wrapper';

        weekKeys.forEach((weekKey, index) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = `Week ${weekKey.replace('week', '')}`;
            button.className = `week-tab px-3 py-1 mx-4 mb-3 border ${index === 0 ? 'bg-black text-white' : ''}`;
            button.style.marginRight = '10px';
            button.addEventListener('click', () => {
                document.querySelectorAll('.week-tab').forEach((tabButton) => {
                    tabButton.classList.remove('bg-black', 'text-white');
                });
                button.classList.add('bg-black', 'text-white');
                renderWeekTable(packageData[weekKey], data, tableContainer, packageId, weekKey);
            });
            tabsWrapper.appendChild(button);
        });

        container.appendChild(tabsWrapper);
        container.appendChild(tableContainer);

        if (weekKeys.length > 0) {
            renderWeekTable(packageData[weekKeys[0]], data, tableContainer, packageId, weekKeys[0]);
        }
    }

    function renderWeekTable(weekData, data, tableContainer, planId, weekKey) {
        tableContainer.innerHTML = '';

        const fixedOrder = ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2'];
        const table = document.createElement('table');
        table.classList.add('meal-table');

        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        headerRow.innerHTML = '<th class="text-center">Day</th>';

        fixedOrder.forEach((mealType) => {
            const exists = Object.values(weekData || {}).some((day) => day && Object.prototype.hasOwnProperty.call(day, mealType));
            if (exists) {
                const header = document.createElement('th');
                header.textContent = mealType.charAt(0).toUpperCase() + mealType.slice(1);
                headerRow.appendChild(header);
            }
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        Object.keys(weekData || {}).forEach((day) => {
            if (['weeks', 'days', 'numday5', 'numday6'].includes(day)) {
                return;
            }

            const row = document.createElement('tr');
            const dayCell = document.createElement('td');
            dayCell.textContent = day.charAt(0).toUpperCase() + day.slice(1);
            row.appendChild(dayCell);

            fixedOrder.forEach((mealType) => {
                const cell = document.createElement('td');
                const mealId = weekData[day]?.[mealType];

                if (mealId) {
                    const entries = data[planId]?.[weekKey]?.[day]?.[mealType]?.en || [];
                    const meal = entries.find((entry) => entry.id === mealId);
                    cell.innerHTML = meal ? `<div class="text-center"><span>${meal.title}</span></div>` : '-';
                } else {
                    cell.textContent = '-';
                }

                row.appendChild(cell);
            });

            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        tableContainer.appendChild(table);
    }

    async function applyCoupon(confirmOverride = false, silent = false, forcedCode = null) {
        const code = String(forcedCode || document.getElementById('coupon_input').value || '').trim();

        if (!code) {
            setCouponStatus('Please enter a coupon code before you click Apply Coupon.', true);
            if (!silent) {
                showCouponAlert('warning', 'Coupon Required', 'Please enter a coupon code before you click Apply Coupon.');
            }
            return;
        }

        const subscription = getSingleSubscriptionContext();
        if (!subscription) {
            if (!silent) {
                clearAppliedCoupon('Coupons can be applied only when exactly one subscription is in checkout.', true);
                showCouponAlert('warning', 'Coupon Scope', 'Coupons can be applied only when exactly one subscription is in checkout.');
            }
            return;
        }

        try {
            const response = await fetch('{{ route('coupon.apply') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    code,
                    subscription,
                    invitation_journey_token: document.getElementById('checkoutInvitationJourneyToken').value || null,
                    confirm_invitation_override: confirmOverride ? 1 : 0,
                }),
            });

            const data = await response.json();

            if (response.status === 409 && data.requires_confirmation) {
                const confirmation = await Swal.fire({
                    icon: 'warning',
                    title: 'Replace Invitation Default?',
                    text: data.message || 'Please confirm replacing the invitation default coupon.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, replace it',
                    cancelButtonText: 'Keep invitation default',
                    confirmButtonColor: '#258a43',
                });

                if (confirmation.isConfirmed) {
                    await applyCoupon(true, silent, code);
                }

                return;
            }

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Coupon could not be applied.');
            }

            couponState.version = data.version || null;
            couponState.id = data.id || null;
            couponState.code = data.code || code;
            couponState.discountType = data.discount_type || null;
            couponState.discountValue = data.discount_value || null;
            couponState.discountLabel = data.discount_label || null;
            couponState.pricing = data.pricing || null;

            if (data.invitation) {
                Object.assign(invitationState, data.invitation);
                syncInvitationFields(invitationState);
                renderInvitationState(invitationState);
            }

            await loadCartTable();
            const successMessage = data.version === 'invitation'
                ? `${data.code} invitation discount was applied successfully and your total was recalculated.`
                : (data.version === 'v2'
                    ? `${data.code} was applied successfully. The discount was added before VAT and your total was recalculated.`
                    : `${data.code} was applied successfully through the legacy coupon flow and your total was recalculated.`);

            setCouponStatus(successMessage);

            if (!silent) {
                showCouponAlert('success', data.version === 'invitation' ? 'Invitation Applied' : 'Coupon Applied', successMessage);
            }
        } catch (error) {
            if (!silent) {
                clearAppliedCoupon(error.message, true);
                await loadCartTable();
                showCouponAlert('error', 'Coupon Error', error.message);
                return;
            }

            clearAppliedCoupon(error.message, true);
            await loadCartTable();
        }
    }

    function buildAppliedCouponPayload() {
        if (!couponState.version || !couponState.id) {
            return null;
        }

        return {
            version: couponState.version,
            id: couponState.id,
            code: couponState.code,
        };
    }

    async function handlePayNowClick() {
        const validCity = document.getElementById('billing_address_city').value;
        const validStreet = document.getElementById('street_address').value;

        if (!validCity || !validStreet) {
            Swal.fire('Missing Info', 'Please select a city and enter a street address.', 'warning');
            return;
        }

        if (window.userId === null) {
            const referralSource = document.getElementById('referral_source')?.value;

            if (!referralSource) {
                Swal.fire('Missing Info', 'Please choose how you heard about Easy Cook before continuing.', 'warning');
                return;
            }
        }

        if (window.userId === null) {
            await handleGuestRegistration();
            return;
        }

        await processCheckout();
    }

    async function handleGuestRegistration() {
        const first_name = document.querySelector('input[name="first_name"]').value.trim();
        const last_name = document.querySelector('input[name="last_name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value.trim();
        const password = document.getElementById('password')?.value;
        const password_confirmation = document.getElementById('password_confirmation')?.value;
        const town_city = document.getElementById('billing_address_city').value;
        const street_address = document.getElementById('street_address').value;
        const final_data = localStorage.getItem('final_data') || document.getElementById('finalDataInput')?.value || '{}';
        const referral_source = document.getElementById('referral_source')?.value || '';
        const referral_source_note = document.getElementById('referral_source_note')?.value || '';
        const invitation_journey_token = document.getElementById('checkoutInvitationJourneyToken').value || '';
        const invitation_code = document.getElementById('checkoutInvitationCodeHidden').value || '';

        if (!email || !password || !first_name || !last_name || !phone || !referral_source) {
            Swal.fire('Missing Info', 'Please fill out all required fields.', 'warning');
            return;
        }

        try {
            const response = await fetch('{{ route('post_register_web') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    first_name,
                    last_name,
                    email,
                    phone,
                    password,
                    password_confirmation,
                    street_address,
                    town_city,
                    final_data,
                    invitation_journey_token,
                    invitation_code,
                    referral_source,
                    referral_source_note,
                }),
            });

            if (response.status === 422) {
                const data = await response.json();
                const errorText = data.errors
                    ? Object.values(data.errors).flat().join('<br>')
                    : (data.message || 'Please check the form fields.');
                Swal.fire('Validation Error', errorText, 'error');
                return;
            }

            if (!response.ok) {
                Swal.fire('Error', 'Unexpected server error. Please try again later.', 'error');
                return;
            }

            const data = await response.json();
            if (!data.user_id) {
                Swal.fire('Error', 'Registration successful, but user ID not returned.', 'error');
                return;
            }

            window.userId = data.user_id;
            if (data.invitation) {
                Object.assign(invitationState, data.invitation);
                syncInvitationFields(invitationState);
                renderInvitationState(invitationState);
            }

            const invitationFlow = await handleInvitationEntryFlow({ afterRegistration: true });

            if (invitationFlow.canContinueCheckout) {
                await processCheckout();
            }
        } catch (error) {
            console.error('Registration Error', error);
            Swal.fire('Error', 'Failed to register. Try again later.', 'error');
        }
    }

    async function processCheckout() {
        const subscriptions = collectSubscriptions().map((subscription) => ({
            user_id: window.userId,
            plan_id: subscription.plan_id,
            weeks_count: subscription.weeks_count,
            days_per_week: subscription.days_per_week,
            meals: subscription.meals,
            type: subscription.type,
        }));

        if (subscriptions.length === 0) {
            Swal.fire('Error', 'No plans selected.', 'warning');
            return;
        }

        if (couponState.version === 'v2' && subscriptions.length !== 1) {
            Swal.fire('Coupon Scope', 'Coupon V2 currently supports exactly one subscription per checkout.', 'warning');
            return;
        }

        if (couponState.version === 'invitation' && subscriptions.length !== 1) {
            Swal.fire('Invitation Scope', 'Invitation discounts currently support exactly one subscription per checkout.', 'warning');
            return;
        }

        const payload = {
            subscriptions,
            invitation_journey_token: document.getElementById('checkoutInvitationJourneyToken').value || null,
            confirm_invitation_override: invitationState?.has_override ? 1 : 0,
            billing_address: {
                town_city: document.getElementById('billing_address_city').value,
                street_address: document.getElementById('street_address').value.trim(),
            },
        };

        const appliedCoupon = buildAppliedCouponPayload();
        if (appliedCoupon) {
            payload.applied_coupon = appliedCoupon;
        }

        if (couponState.version === 'v1' && couponState.id) {
            payload.coupon_id = couponState.id;
        }

        try {
            const checkoutResponse = await sendCheckoutRequest(payload);

            if (checkoutResponse?.status !== 'success' || !checkoutResponse.subscriptions) {
                Swal.fire('Error', checkoutResponse.message || 'Could not process subscription.', 'error');
                return;
            }

            const payResponse = await fetch('{{ route('payment.checkout') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    subscription_ids: checkoutResponse.subscriptions,
                    invitation_journey_token: document.getElementById('checkoutInvitationJourneyToken').value || null,
                }),
            });

            const result = await payResponse.json();

            if (result.status !== 'success') {
                Swal.fire('Error', result.message || 'Payment failed.', 'error');
                return;
            }

            window.location.href = result.payment_url;
        } catch (error) {
            console.error('Checkout Error', error);
            Swal.fire('Error', `Failed to complete checkout: ${error.message}`, 'error');
        }
    }

    async function sendCheckoutRequest(payload) {
        const response = await fetch('{{ route('checkout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const text = await response.text();
        let parsed;

        try {
            parsed = JSON.parse(text);
        } catch (error) {
            throw new Error('Invalid server response format');
        }

        if (!response.ok) {
            throw new Error(parsed.message || `HTTP error ${response.status}`);
        }

        return parsed;
    }
</script>
