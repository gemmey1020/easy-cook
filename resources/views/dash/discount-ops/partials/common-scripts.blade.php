@push('scripts')
    <script>
        window.CouponV2Admin = window.CouponV2Admin || {
            escapeRegex: function(value) {
                return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            },
            applyMidnightFallback: function(input) {
                if (!input || !input.value) {
                    return;
                }

                if (/^\d{4}-\d{2}-\d{2}$/.test(input.value)) {
                    input.value = input.value + 'T00:00';
                }
            },
            bindOwnerForms: function(root) {
                const scope = root || document;
                scope.querySelectorAll('[data-coupon-owner-form]').forEach(function(form) {
                    const ownerType = form.querySelector('[data-coupon-owner-type]');
                    const partnerSelect = form.querySelector('[data-partner-select]');
                    const partnerField = form.querySelector('[data-partner-field]');
                    const expiresAt = form.querySelector('[data-expires-at]');

                    if (ownerType && partnerSelect) {
                        const syncPartnerState = function() {
                            const isPartner = ownerType.value === 'partner';
                            partnerSelect.disabled = !isPartner;

                            if (!isPartner) {
                                partnerSelect.value = '';
                            }

                            if (partnerField) {
                                partnerField.classList.toggle('coupon-v2-field--disabled', !isPartner);
                            }
                        };

                        ownerType.addEventListener('change', syncPartnerState);
                        syncPartnerState();
                    }

                    if (expiresAt) {
                        this.applyMidnightFallback(expiresAt);
                        expiresAt.addEventListener('change', function() {
                            window.CouponV2Admin.applyMidnightFallback(expiresAt);
                        });
                    }
                }, this);
            },
            fallbackCopy: function(text) {
                const helper = document.createElement('textarea');
                helper.value = text;
                helper.setAttribute('readonly', '');
                helper.style.position = 'absolute';
                helper.style.left = '-9999px';
                document.body.appendChild(helper);
                helper.select();
                document.execCommand('copy');
                document.body.removeChild(helper);
            },
            setCopyButtonState: function(button, label, className) {
                if (!button) {
                    return;
                }

                button.textContent = label;
                button.classList.remove('btn-outline-secondary', 'btn-success', 'btn-danger');
                button.classList.add(className);
            },
            bindCopyButtons: function(root) {
                const scope = root || document;

                scope.querySelectorAll('[data-copy-text]').forEach(function(button) {
                    if (button.dataset.copyBound === 'true') {
                        return;
                    }

                    button.dataset.copyBound = 'true';
                    const originalLabel = button.dataset.copyLabel || button.textContent.trim();

                    button.addEventListener('click', async function() {
                        const text = button.dataset.copyText || '';

                        try {
                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                await navigator.clipboard.writeText(text);
                            } else {
                                window.CouponV2Admin.fallbackCopy(text);
                            }

                            window.CouponV2Admin.setCopyButtonState(button, 'Copied', 'btn-success');
                        } catch (error) {
                            window.CouponV2Admin.setCopyButtonState(button, 'Retry', 'btn-danger');
                        }

                        window.setTimeout(function() {
                            window.CouponV2Admin.setCopyButtonState(button, originalLabel, 'btn-outline-secondary');
                        }, 1400);
                    });
                });
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.CouponV2Admin.bindOwnerForms(document);
            window.CouponV2Admin.bindCopyButtons(document);
        });
    </script>
@endpush
