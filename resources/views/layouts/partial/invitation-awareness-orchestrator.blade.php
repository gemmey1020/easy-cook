<script>
    if (!window.EasyCookInvitationAwareness) {
        window.EasyCookInvitationAwareness = (() => {
            const JOURNEY_SCOPED_FAMILIES = new Set(['invalid', 'used', 'override']);
            const SURFACE_SCOPED_FAMILIES = new Set(['captured', 'eligible', 'ineligible']);

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function codeChip(code) {
                return `<span class="ec-invite-awareness__code">${escapeHtml(code || '-')}</span>`;
            }

            function normalizeState(rawState) {
                return Object.assign({
                    token: null,
                    state: null,
                    is_valid: false,
                    code: null,
                    message: null,
                    referral_source: null,
                    referral_source_note: null,
                    default_coupon: null,
                    active_coupon: null,
                    has_override: false,
                    owner_type: null,
                    owner_user_id: null,
                    registered_with_same_code: false,
                    is_discount_eligible: false,
                    eligibility_message: null,
                    discount_label: null,
                    invalid_reason: null,
                    last_invalid_code: null,
                }, rawState || {});
            }

            function familyFromState(rawState) {
                const state = normalizeState(rawState);

                if (!state.token) {
                    return 'empty';
                }

                if (!state.is_valid || state.state === 'invalid_code') {
                    return 'invalid';
                }

                if (/already used/i.test(state.eligibility_message || '') || state.state === 'paid') {
                    return 'used';
                }

                if (state.has_override && state.active_coupon?.version && state.active_coupon.version !== 'invitation') {
                    return 'override';
                }

                if (state.is_discount_eligible) {
                    return 'eligible';
                }

                if (state.state === 'captured') {
                    return 'captured';
                }

                return 'ineligible';
            }

            function badgeForFamily(family) {
                switch (family) {
                    case 'captured':
                        return { text: 'Saved', tone: 'valid' };
                    case 'eligible':
                        return { text: 'Ready', tone: 'ready' };
                    case 'override':
                        return { text: 'Override', tone: 'override' };
                    case 'invalid':
                        return { text: 'Invalid', tone: 'invalid' };
                    case 'used':
                        return { text: 'Used', tone: 'used' };
                    case 'ineligible':
                        return { text: 'Different account', tone: 'warning' };
                    default:
                        return { text: 'No saved code', tone: 'neutral' };
                }
            }

            function defaultDetailsForState(family, state) {
                const details = [];
                const code = state.code || state.last_invalid_code || null;

                if (code) {
                    details.push({
                        label: family === 'invalid' ? 'Last tried' : 'Saved code',
                        value: codeChip(code),
                    });
                }

                if (state.discount_label) {
                    details.push({
                        label: 'Discount',
                        value: escapeHtml(`${state.discount_label} off`),
                    });
                }

                if (family === 'override' && state.active_coupon?.code) {
                    details.push({
                        label: 'Active coupon',
                        value: codeChip(state.active_coupon.code),
                    });
                }

                const truthMessage = {
                    empty: 'No invitation truth is linked in this browser yet.',
                    captured: 'This invitation truth is stored on the server and can be kept or replaced.',
                    eligible: 'The invitation truth is active and can be honored at checkout.',
                    invalid: 'Invalid invitation truth is still stored on the server until you replace it.',
                    ineligible: 'The invitation truth is stored, but another account path is required before discount use.',
                    override: 'The invitation truth remains saved while another coupon is active.',
                    used: 'The invitation truth is stored, but the discount has already reached a terminal state.',
                }[family];

                details.push({
                    label: 'Truth',
                    value: escapeHtml(truthMessage),
                    muted: true,
                });

                return details;
            }

            function buildModel({ surface, rawState, context = {} }) {
                const state = normalizeState(rawState);
                const family = familyFromState(state);
                const badge = badgeForFamily(family);
                const discountLabel = state.discount_label ? `${state.discount_label} off` : 'the saved invitation discount';

                const base = {
                    family,
                    badge,
                    kicker: 'Server-backed truth',
                    title: 'Invitation awareness',
                    message: 'Invitation and coupon awareness will stay visible here.',
                    details: defaultDetailsForState(family, state),
                    actions: [],
                    modal: { show: false },
                };

                if (family === 'empty') {
                    base.title = surface === 'login' ? 'No invitation is linked yet' : 'No invitation linked yet';
                    base.message = surface === 'register'
                        ? 'You can continue without an invitation, or capture one before you create your account.'
                        : (surface === 'login'
                            ? 'You can sign in normally. If this browser already has a saved invitation, it will appear here.'
                            : 'No invitation or coupon awareness is active yet. You can continue normally.');
                    return base;
                }

                if (family === 'captured') {
                    if (surface === 'checkout') {
                        base.title = 'This saved invitation still needs the right account path';
                        base.message = 'The code is already saved on the server, but the discount unlocks only after the same account registers or signs in with it.';
                        base.actions = [
                            { type: 'sign_in', label: 'Sign in with linked account', primary: true },
                            { type: 'use_another_coupon', label: 'Use another coupon' },
                        ];
                        base.modal = {
                            show: true,
                            icon: 'info',
                            title: 'Invitation Discount Locked',
                            text: 'This invitation is already saved, but the discount unlocks only after the same account registers or signs in with it.',
                            primaryAction: 'sign_in',
                            primaryLabel: 'Sign in with linked account',
                            secondaryAction: 'use_another_coupon',
                            secondaryLabel: 'Use another coupon',
                        };

                        return decorateModalScope(base, surface, state);
                    }

                    base.title = surface === 'login'
                        ? 'A saved invitation is waiting in this browser'
                        : 'Your invitation is already saved';
                    base.message = surface === 'login'
                        ? 'Keep it if this is the right path. If you already used this invitation to create an account, sign in with that account. Otherwise, create your account first.'
                        : 'This code is already saved on the server. Keep it if it is correct, or replace it before you continue.';
                    base.actions = surface === 'login'
                        ? [
                            { type: 'sign_in', label: 'Sign in with your account', primary: true },
                            { type: 'create_account', label: 'Create account instead' },
                        ]
                        : [
                            { type: 'keep', label: 'Keep current code', primary: true },
                            { type: 'replace_invitation', label: 'Replace code' },
                        ];
                    base.modal = {
                        show: true,
                        icon: 'info',
                        title: 'Invitation saved',
                        text: surface === 'login'
                            ? 'This invitation is already saved in this browser. Sign in with the right account or create a new account with this code.'
                            : 'This invitation is already saved. You can keep it or replace it before you continue.',
                        primaryAction: surface === 'login' ? 'sign_in' : 'keep',
                        primaryLabel: surface === 'login' ? 'Sign in' : 'Keep this code',
                        secondaryAction: surface === 'login' ? 'create_account' : 'replace_invitation',
                        secondaryLabel: surface === 'login' ? 'Create account instead' : 'Replace code',
                    };
                    return decorateModalScope(base, surface, state);
                }

                if (family === 'invalid') {
                    base.title = 'This saved invitation is invalid';
                    base.message = state.message || 'The saved invitation code is invalid. You can continue without it or replace it.';
                    base.actions = surface === 'login'
                        ? [
                            { type: 'sign_in', label: 'Continue to sign in', primary: true },
                            { type: 'create_account', label: 'Go to registration' },
                        ]
                        : [
                            { type: 'replace_invitation', label: 'Replace code', primary: true },
                            { type: 'continue', label: 'Continue without it' },
                        ];
                    base.modal = {
                        show: true,
                        icon: 'warning',
                        title: 'Invalid Invitation Code',
                        text: state.message || 'This invitation code is invalid. You can continue without it.',
                        primaryAction: surface === 'login' ? 'sign_in' : 'continue',
                        primaryLabel: surface === 'login' ? 'Continue to sign in' : 'Continue',
                        secondaryAction: surface === 'login' ? 'create_account' : 'replace_invitation',
                        secondaryLabel: surface === 'login' ? 'Go to registration' : 'Replace code',
                    };
                    return decorateModalScope(base, surface, state);
                }

                if (family === 'eligible') {
                    base.title = 'Your invitation discount is ready';
                    base.message = surface === 'checkout'
                        ? 'This invitation can be kept for this checkout, or replaced with another coupon if you prefer.'
                        : `The saved invitation is ready to continue with ${discountLabel}.`;
                    base.actions = surface === 'checkout'
                        ? [
                            { type: 'keep', label: 'Keep invitation', primary: true },
                            { type: 'use_another_coupon', label: 'Use another coupon' },
                        ]
                        : [
                            { type: 'continue', label: 'Continue', primary: true },
                        ];
                    base.modal = {
                        show: surface === 'checkout',
                        icon: 'info',
                        title: 'Unused Invitation Discount',
                        text: state.discount_label
                            ? `You have an unused invitation code worth ${state.discount_label} off.`
                            : 'You have an unused invitation code ready for this checkout.',
                        primaryAction: 'keep',
                        primaryLabel: 'Use invitation code',
                        secondaryAction: 'use_another_coupon',
                        secondaryLabel: 'Use another coupon',
                    };
                    return decorateModalScope(base, surface, state);
                }

                if (family === 'override') {
                    base.title = 'Another coupon is active right now';
                    base.message = 'Your invitation truth is still saved, but this checkout is currently using another coupon.';
                    base.actions = surface === 'checkout'
                        ? [
                            { type: 'keep_override', label: 'Keep current coupon', primary: true },
                            { type: 'restore_invitation', label: 'Restore invitation' },
                        ]
                        : [
                            { type: 'continue', label: surface === 'login' ? 'Continue to sign in' : 'Continue', primary: true },
                        ];
                    base.modal = {
                        show: true,
                        icon: 'info',
                        title: 'Another Coupon Is Active',
                        text: state.active_coupon?.code
                            ? `${state.active_coupon.code} is currently active. Your saved invitation can still be restored later.`
                            : 'Another coupon is currently active. Your saved invitation can still be restored later.',
                        primaryAction: surface === 'checkout' ? 'keep_override' : 'continue',
                        primaryLabel: surface === 'checkout' ? 'Keep current coupon' : (surface === 'login' ? 'Continue to sign in' : 'Continue'),
                        secondaryAction: surface === 'checkout' ? 'restore_invitation' : null,
                        secondaryLabel: surface === 'checkout' ? 'Restore invitation' : null,
                    };
                    return decorateModalScope(base, surface, state);
                }

                if (family === 'used') {
                    base.title = 'This invitation has already been used';
                    base.message = state.eligibility_message || state.message || 'The saved invitation has already reached its paid or used state.';
                    base.actions = surface === 'checkout'
                        ? [
                            { type: 'continue', label: 'Continue without it', primary: true },
                            { type: 'use_another_coupon', label: 'Use another coupon' },
                        ]
                        : [
                            { type: surface === 'login' ? 'sign_in' : 'continue', label: surface === 'login' ? 'Continue to sign in' : 'Continue', primary: true },
                            { type: surface === 'login' ? 'create_account' : 'replace_invitation', label: surface === 'login' ? 'Go to registration' : 'Replace code' },
                        ];
                    base.modal = {
                        show: true,
                        icon: 'info',
                        title: 'Invitation Already Used',
                        text: base.message,
                        primaryAction: surface === 'checkout' ? 'continue' : (surface === 'login' ? 'sign_in' : 'continue'),
                        primaryLabel: surface === 'checkout' ? 'Continue' : (surface === 'login' ? 'Continue to sign in' : 'Continue'),
                        secondaryAction: surface === 'checkout' ? 'use_another_coupon' : (surface === 'login' ? 'create_account' : 'replace_invitation'),
                        secondaryLabel: surface === 'checkout' ? 'Use another coupon' : (surface === 'login' ? 'Go to registration' : 'Replace code'),
                    };
                    return decorateModalScope(base, surface, state);
                }

                base.title = surface === 'login'
                    ? 'Sign in with the account linked to this invitation'
                    : 'This invitation needs the correct account path';
                base.message = state.eligibility_message || 'This invitation discount is available only for accounts registered with this invitation code.';
                base.actions = surface === 'checkout'
                    ? [
                        { type: 'sign_in', label: 'Sign in with linked account', primary: true },
                        { type: 'use_another_coupon', label: 'Use another coupon' },
                    ]
                    : (surface === 'login'
                        ? [
                            { type: 'sign_in', label: 'Sign in with linked account', primary: true },
                            { type: 'create_account', label: 'Go to registration' },
                        ]
                        : [
                            { type: 'sign_in', label: 'Sign in instead', primary: true },
                            { type: 'replace_invitation', label: 'Replace code' },
                        ]);
                base.modal = {
                    show: true,
                    icon: context.afterRegistration ? 'success' : 'info',
                    title: context.afterRegistration ? 'Account Created' : 'Invitation Discount Locked',
                    text: base.message,
                    primaryAction: surface === 'checkout' ? 'sign_in' : (surface === 'login' ? 'sign_in' : 'keep'),
                    primaryLabel: surface === 'checkout' ? 'Sign in with linked account' : (surface === 'login' ? 'Continue to sign in' : 'Keep saved code'),
                    secondaryAction: surface === 'checkout' ? 'use_another_coupon' : (surface === 'login' ? 'create_account' : 'replace_invitation'),
                    secondaryLabel: surface === 'checkout' ? 'Use another coupon' : (surface === 'login' ? 'Go to registration' : 'Replace code'),
                };

                return decorateModalScope(base, surface, state);
            }

            function decorateModalScope(model, surface, state) {
                if (!model.modal.show) {
                    return model;
                }

                const scope = JOURNEY_SCOPED_FAMILIES.has(model.family)
                    ? 'journey'
                    : (SURFACE_SCOPED_FAMILIES.has(model.family) ? 'surface' : 'surface');

                const fingerprint = [
                    state.token || 'none',
                    model.family,
                    state.state || 'none',
                    state.code || 'none',
                    state.active_coupon?.version || 'none',
                    state.active_coupon?.id || '0',
                    state.is_discount_eligible ? 'eligible' : 'blocked',
                    state.has_override ? 'override' : 'default',
                    scope === 'surface' ? surface : 'journey',
                ].join(':');

                model.modal.scope = scope;
                model.modal.key = `easycook-awareness:${fingerprint}`;

                return model;
            }

            function shouldShowModal(model) {
                if (!model?.modal?.show || !model?.modal?.key) {
                    return false;
                }

                return !sessionStorage.getItem(model.modal.key);
            }

            function markModalShown(model) {
                if (model?.modal?.key) {
                    sessionStorage.setItem(model.modal.key, '1');
                }
            }

            function choiceKey(model) {
                return model?.modal?.key ? `${model.modal.key}:choice` : null;
            }

            async function prompt(model) {
                if (!model?.modal?.show || typeof Swal === 'undefined') {
                    return { action: null, shown: false };
                }

                if (!shouldShowModal(model)) {
                    return { action: null, shown: false };
                }

                const swalConfig = {
                    icon: model.modal.icon || 'info',
                    title: model.modal.title || 'Invitation awareness',
                    text: model.modal.text || model.message || '',
                    confirmButtonText: model.modal.primaryLabel || 'Continue',
                    confirmButtonColor: '#258a43',
                };

                if (model.modal.secondaryLabel) {
                    swalConfig.showDenyButton = true;
                    swalConfig.denyButtonText = model.modal.secondaryLabel;
                    swalConfig.denyButtonColor = '#d2673a';
                }

                const result = await Swal.fire(swalConfig);
                markModalShown(model);

                if (result.isConfirmed) {
                    markModalShown(model);
                    return { action: model.modal.primaryAction || null, shown: true };
                }

                if (result.isDenied) {
                    markModalShown(model);
                    return { action: model.modal.secondaryAction || null, shown: true };
                }

                return { action: null, shown: true };
            }

            function syncInlineSurface({ prefix, model }) {
                const badge = document.getElementById(`${prefix}InlineBadge`);
                const kicker = document.getElementById(`${prefix}InlineKicker`);
                const title = document.getElementById(`${prefix}InlineTitle`);
                const message = document.getElementById(`${prefix}InlineMessage`);
                const details = document.getElementById(`${prefix}InlineDetails`);
                const actions = document.getElementById(`${prefix}InlineActions`);

                if (!badge || !kicker || !title || !message || !details || !actions) {
                    return;
                }

                badge.textContent = model.badge.text;
                badge.className = `ec-invite-awareness__badge ec-invite-awareness__badge--${model.badge.tone || 'neutral'}`;
                kicker.textContent = model.kicker;
                title.textContent = model.title;
                message.textContent = model.message;

                details.innerHTML = (model.details || []).map((detail) => `
                    <div class="ec-invite-awareness__detail${detail.muted ? ' ec-invite-awareness__detail--muted' : ''}">
                        ${detail.label ? `<strong>${escapeHtml(detail.label)}:</strong>` : ''}
                        <span>${detail.value || ''}</span>
                    </div>
                `).join('');

                actions.innerHTML = (model.actions || []).map((action) => `
                    <button
                        type="button"
                        class="ec-invite-awareness__action${action.primary ? ' ec-invite-awareness__action--primary' : ''}"
                        data-awareness-action="${escapeHtml(action.type)}"
                    >${escapeHtml(action.label)}</button>
                `).join('');
            }

            return {
                normalizeState,
                familyFromState,
                buildModel,
                shouldShowModal,
                markModalShown,
                choiceKey,
                prompt,
                syncInlineSurface,
            };
        })();
    }
</script>
