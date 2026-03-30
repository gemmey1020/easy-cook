<style>
    .ec-invite-awareness {
        display: grid;
        gap: 16px;
        padding: 20px;
        border-radius: 18px;
        border: 1px solid rgba(37, 138, 67, 0.12);
        background:
            radial-gradient(circle at top right, rgba(37, 138, 67, 0.08), transparent 30%),
            linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(247, 251, 248, 0.98));
    }

    .ec-invite-awareness[hidden] {
        display: none !important;
    }

    .ec-invite-awareness__top,
    .ec-invite-awareness__actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .ec-invite-awareness__kicker {
        color: #607a6b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .ec-invite-awareness__badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid rgba(98, 122, 107, 0.12);
        background: rgba(98, 122, 107, 0.08);
        color: #607a6b;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .ec-invite-awareness__badge--valid,
    .ec-invite-awareness__badge--ready,
    .ec-invite-awareness__badge--override {
        border-color: rgba(37, 138, 67, 0.16);
        background: rgba(37, 138, 67, 0.12);
        color: #1f6f3f;
    }

    .ec-invite-awareness__badge--warning,
    .ec-invite-awareness__badge--invalid,
    .ec-invite-awareness__badge--used {
        border-color: rgba(196, 114, 82, 0.18);
        background: rgba(196, 114, 82, 0.12);
        color: #9f4b30;
    }

    .ec-invite-awareness__body {
        display: grid;
        gap: 8px;
    }

    .ec-invite-awareness__body h3 {
        margin: 0;
        color: #173a23;
        font-size: 1.12rem;
        letter-spacing: -0.02em;
    }

    .ec-invite-awareness__body p,
    .ec-invite-awareness__detail {
        margin: 0;
        color: #4e675a;
        line-height: 1.75;
    }

    .ec-invite-awareness__details {
        display: grid;
        gap: 10px;
    }

    .ec-invite-awareness__detail {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ec-invite-awareness__detail strong {
        color: #173a23;
    }

    .ec-invite-awareness__detail--muted {
        color: #71877b;
    }

    .ec-invite-awareness__code {
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

    .ec-invite-awareness__actions {
        padding-top: 2px;
    }

    .ec-invite-awareness__action {
        min-height: 44px;
        padding: 0 18px;
        border-radius: 14px;
        border: 1px solid rgba(37, 138, 67, 0.16);
        background: #ffffff;
        color: #1c4e30;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.02em;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .ec-invite-awareness__action:hover,
    .ec-invite-awareness__action:focus {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(37, 138, 67, 0.12);
        border-color: rgba(37, 138, 67, 0.28);
        outline: none;
    }

    .ec-invite-awareness__action--primary {
        background: #258a43;
        color: #ffffff;
        border-color: #258a43;
        box-shadow: 0 14px 28px rgba(37, 138, 67, 0.18);
    }

    @media (max-width: 767px) {
        .ec-invite-awareness {
            padding: 18px;
        }

        .ec-invite-awareness__actions {
            display: grid;
        }

        .ec-invite-awareness__action {
            width: 100%;
        }
    }
</style>
