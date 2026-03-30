@push('css')
    <style>
        .coupon-v2-page {
            width: 100%;
            max-width: 1380px;
            padding: 0 12px 36px;
        }

        .coupon-v2-shell {
            width: 100%;
        }

        .coupon-v2-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .coupon-v2-header-copy h3 {
            color: #243b68;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
        }

        .coupon-v2-header-copy p {
            max-width: 720px;
            color: #6f7d96;
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .coupon-v2-header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .coupon-v2-shell .card {
            border: 1px solid #e4eaf3;
            border-radius: 22px;
            box-shadow: 0 16px 40px rgba(32, 53, 90, 0.08);
            overflow: hidden;
        }

        .coupon-v2-shell .card-header {
            border-bottom: 1px solid #edf1f7;
            background: linear-gradient(180deg, #fbfcfe 0%, #f3f7fc 100%);
            color: #243b68;
            font-size: 1rem;
            font-weight: 700;
            padding: 18px 24px;
        }

        .coupon-v2-shell .card-body,
        .coupon-v2-shell .card-footer {
            padding: 24px;
        }

        .coupon-v2-form-grid .form-label {
            color: #5f6f89;
            font-size: 0.92rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .coupon-v2-form-grid .form-control {
            min-height: 48px;
            border: 1px solid #d9e3f2;
            border-radius: 14px;
            color: #243b68;
            font-size: 0.96rem;
            padding: 0.75rem 0.95rem;
            box-shadow: none;
            transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
        }

        .coupon-v2-form-grid textarea.form-control {
            min-height: 132px;
            resize: vertical;
        }

        .coupon-v2-form-grid .form-control:focus {
            border-color: #5c7cfa;
            box-shadow: 0 0 0 4px rgba(92, 124, 250, 0.12);
        }

        .coupon-v2-field {
            transition: opacity .18s ease;
        }

        .coupon-v2-field--disabled {
            opacity: 0.62;
        }

        .coupon-v2-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 100%;
            padding: 18px 18px 16px;
            border: 1px dashed #d7deea;
            border-radius: 16px;
            background: #f9fbfe;
        }

        .coupon-v2-toggle .form-check {
            margin: 0;
        }

        .coupon-v2-toggle .form-check-label {
            color: #334767;
            font-weight: 600;
        }

        .coupon-v2-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .coupon-v2-actions .btn,
        .coupon-v2-header-actions .btn,
        .coupon-v2-table-actions .btn {
            border-radius: 12px;
            font-weight: 700;
            min-height: 42px;
            padding: 0.65rem 1rem;
        }

        .coupon-v2-filter-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }

        .coupon-v2-filter-copy {
            color: #72829d;
            font-size: 0.94rem;
            line-height: 1.6;
            margin: 0;
        }

        .coupon-v2-filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .coupon-v2-filter-group .form-control {
            min-width: 190px;
            border-radius: 12px;
        }

        .coupon-v2-table-code {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .coupon-v2-table-code strong {
            color: #203869;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .coupon-v2-owner {
            display: grid;
            gap: 4px;
        }

        .coupon-v2-owner small,
        .coupon-v2-partner-meta,
        .coupon-v2-empty-copy,
        .coupon-v2-kicker {
            color: #7d8ba5;
        }

        .coupon-v2-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .coupon-v2-kicker::before {
            content: '';
            width: 34px;
            height: 1px;
            background: rgba(125, 139, 165, 0.65);
        }

        .coupon-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .coupon-status--active {
            background: rgba(22, 163, 74, 0.12);
            color: #15803d;
            border-color: rgba(21, 128, 61, 0.18);
        }

        .coupon-status--suspended {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
            border-color: rgba(71, 85, 105, 0.18);
        }

        .coupon-status--exhausted {
            background: rgba(245, 158, 11, 0.16);
            color: #b45309;
            border-color: rgba(180, 83, 9, 0.18);
        }

        .coupon-status--expired {
            background: rgba(239, 68, 68, 0.12);
            color: #c2410c;
            border-color: rgba(220, 38, 38, 0.16);
        }

        .coupon-usage {
            min-width: 124px;
        }

        .coupon-usage__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
            color: #304766;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .coupon-usage__track {
            position: relative;
            height: 7px;
            border-radius: 999px;
            background: #e8eef7;
            overflow: hidden;
        }

        .coupon-usage__fill {
            display: block;
            height: 100%;
            border-radius: inherit;
            transition: width .2s ease;
        }

        .coupon-usage__fill--idle {
            background: linear-gradient(90deg, #cbd5e1 0%, #94a3b8 100%);
        }

        .coupon-usage__fill--active {
            background: linear-gradient(90deg, #60a5fa 0%, #2563eb 100%);
        }

        .coupon-usage__fill--near-full {
            background: linear-gradient(90deg, #fbbf24 0%, #f97316 100%);
        }

        .coupon-usage__fill--full {
            background: linear-gradient(90deg, #f59e0b 0%, #c2410c 100%);
        }

        .coupon-v2-copy-btn {
            min-width: 92px;
            white-space: nowrap;
        }

        .coupon-v2-copy-btn.btn-success {
            border-color: #16a34a;
            background: #16a34a;
            color: #fff;
        }

        .coupon-v2-table-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .coupon-v2-table-actions form {
            margin: 0;
        }

        .coupon-v2-shell .table {
            width: 100% !important;
            margin-bottom: 0;
        }

        .coupon-v2-shell .table th {
            color: #51627f;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-top: none;
            white-space: nowrap;
        }

        .coupon-v2-shell .table td {
            vertical-align: middle;
            color: #203456;
            font-size: 0.94rem;
        }

        .coupon-v2-shell .dataTables_wrapper .dataTables_filter input,
        .coupon-v2-shell .dataTables_wrapper .dataTables_length select {
            min-height: 42px;
            border: 1px solid #d9e3f2;
            border-radius: 12px;
            color: #304766;
            padding: 0.55rem 0.85rem;
        }

        .coupon-v2-shell .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5rem;
        }

        .coupon-v2-shell .dataTables_wrapper .dataTables_info {
            color: #74839d;
        }

        .coupon-v2-shell .paginate_button.page-item .page-link {
            border-radius: 10px !important;
        }

        .coupon-v2-header-stat {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .coupon-v2-header-stat strong {
            color: #203456;
        }

        .coupon-v2-empty-state {
            padding: 36px 18px;
            text-align: center;
            color: #7d8ba5;
        }

        .discount-ops-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .discount-ops-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0.72rem 1rem;
            border-radius: 999px;
            border: 1px solid #d9e3f2;
            background: #f7faff;
            color: #48617f;
            font-weight: 700;
            text-decoration: none;
            transition: all .18s ease;
        }

        .discount-ops-tab:hover,
        .discount-ops-tab.is-active {
            border-color: #2b6cb0;
            background: linear-gradient(180deg, #f0f7ff 0%, #e4f0ff 100%);
            color: #1d4b88;
            text-decoration: none;
        }

        .discount-ops-grid {
            display: grid;
            gap: 20px;
        }

        .discount-ops-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .discount-ops-metric {
            padding: 18px;
            border-radius: 18px;
            border: 1px solid #e2eaf5;
            background: #f9fbff;
        }

        .discount-ops-metric--active {
            background: linear-gradient(180deg, #eefcf3 0%, #def6e7 100%);
            border-color: #b6e2c4;
        }

        .discount-ops-metric--warning {
            background: linear-gradient(180deg, #fff8ec 0%, #fdf0d5 100%);
            border-color: #f4d49a;
        }

        .discount-ops-metric--muted {
            background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%);
            border-color: #d6e0eb;
        }

        .discount-ops-metric__label {
            display: block;
            color: #6d7d96;
            font-size: 0.84rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .discount-ops-metric__value {
            color: #203456;
            font-size: 1.8rem;
            line-height: 1;
        }

        .discount-ops-note {
            margin: 18px 0 0;
            color: #74839d;
            line-height: 1.7;
        }

        .discount-ops-attention-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .discount-ops-list-card {
            border: 1px solid #ebf0f7;
            border-radius: 18px;
            background: #fbfcff;
            padding: 18px;
        }

        .discount-ops-list-card h4 {
            color: #203456;
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 12px;
        }

        .discount-ops-list-item {
            display: grid;
            gap: 12px;
            padding: 14px 0;
            border-top: 1px solid #edf2f7;
        }

        .discount-ops-list-item:first-of-type {
            border-top: 0;
            padding-top: 0;
        }

        .discount-ops-list-item__content {
            display: grid;
            gap: 4px;
        }

        .discount-ops-list-item__title {
            display: block;
            color: #27426b;
            font-weight: 700;
        }

        .discount-ops-list-item small {
            color: #74839d;
            line-height: 1.6;
        }

        .discount-ops-action-rail {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .discount-ops-action-rail form {
            margin: 0;
        }

        .discount-ops-action-rail .btn {
            min-height: 38px;
            border-radius: 12px;
            font-weight: 700;
            padding: 0.55rem 0.9rem;
        }

        .discount-ops-empty-state {
            padding: 18px 0;
            color: #7d8ba5;
            line-height: 1.7;
        }

        .discount-ops-inline-hint {
            margin-bottom: 16px;
            padding: 14px 18px;
            border-radius: 16px;
            border: 1px solid #dce6f4;
            background: #f7fbff;
            color: #61738f;
        }

        .discount-ops-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .discount-ops-pill {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            background: #eef4fb;
            color: #35557e;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .discount-ops-pill--warning {
            background: #fff1df;
            color: #b56717;
        }

        .discount-ops-pill--success {
            background: #eaf8ef;
            color: #1c7a43;
        }

        .discount-ops-link-stack {
            display: grid;
            gap: 4px;
        }

        .discount-ops-link-stack a {
            color: #295c9a;
            font-weight: 600;
            text-decoration: none;
        }

        .discount-ops-link-stack a:hover {
            text-decoration: underline;
        }

        .dashboard-launchpad {
            width: 100%;
            max-width: 1280px;
            display: grid;
            gap: 22px;
        }

        .dashboard-launchpad__hero,
        .dashboard-launchpad__card {
            border: 1px solid #e3ebf5;
            border-radius: 24px;
            background: linear-gradient(180deg, #fbfdff 0%, #f4f8fc 100%);
            box-shadow: 0 18px 42px rgba(32, 53, 90, 0.08);
            padding: 24px;
        }

        .dashboard-launchpad__hero h1,
        .dashboard-launchpad__card h3 {
            color: #203456;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .dashboard-launchpad__hero p,
        .dashboard-launchpad__card p {
            color: #6f7d96;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .dashboard-launchpad__stats,
        .dashboard-launchpad__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .dashboard-launchpad__stat {
            border: 1px solid #e3ebf5;
            border-radius: 18px;
            background: #ffffff;
            padding: 16px;
        }

        .dashboard-launchpad__stat strong {
            display: block;
            color: #203456;
            font-size: 1.6rem;
        }

        .dashboard-launchpad__stat span {
            color: #72829d;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .dashboard-launchpad__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        @media (max-width: 991.98px) {
            .coupon-v2-shell .card-header,
            .coupon-v2-shell .card-body,
            .coupon-v2-shell .card-footer {
                padding: 20px;
            }

            .coupon-v2-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 767.98px) {
            .coupon-v2-page {
                padding-left: 0;
                padding-right: 0;
            }

            .coupon-v2-header-copy h3 {
                font-size: 1.65rem;
            }

            .coupon-v2-header,
            .coupon-v2-header-actions,
            .coupon-v2-filter-group,
            .coupon-v2-actions,
            .discount-ops-tabs,
            .dashboard-launchpad__actions,
            .discount-ops-action-rail {
                width: 100%;
            }

            .coupon-v2-header-actions .btn,
            .coupon-v2-actions .btn,
            .coupon-v2-filter-group .form-control,
            .dashboard-launchpad__actions .btn,
            .discount-ops-action-rail .btn {
                width: 100%;
            }

            .coupon-v2-shell .card-header,
            .coupon-v2-shell .card-body,
            .coupon-v2-shell .card-footer {
                padding: 16px;
            }

            .coupon-v2-shell .dataTables_wrapper .dataTables_filter,
            .coupon-v2-shell .dataTables_wrapper .dataTables_length,
            .coupon-v2-shell .dataTables_wrapper .dataTables_info,
            .coupon-v2-shell .dataTables_wrapper .dataTables_paginate {
                width: 100%;
                display: flex;
                justify-content: flex-start !important;
                text-align: left !important;
            }

            .coupon-v2-shell .dataTables_wrapper .dataTables_filter label,
            .coupon-v2-shell .dataTables_wrapper .dataTables_length label {
                width: 100%;
            }

            .coupon-v2-shell .dataTables_wrapper .dataTables_filter input,
            .coupon-v2-shell .dataTables_wrapper .dataTables_length select {
                width: 100%;
                margin-left: 0;
                margin-top: 8px;
            }
        }
    </style>
@endpush
