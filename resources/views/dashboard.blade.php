<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Klinik Dentist · Self-Diagnosis TMD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* =====================================================================
           Design Tokens — Primitives & Semantic Roles
           ===================================================================== */
        :root {
            /* Primitives */
            --teal-950: #07292A;
            --teal-900: #0B3B3C;
            --teal-800: #124E50;
            --teal-700: #0A5858;
            --teal-600: #0D6E6E;
            --teal-100: #CFE6E6;
            --teal-50:  #E9F4F4;

            --slate-900: #0F172A;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748B;
            --slate-400: #78869B;
            --slate-200: #E2E8F0;
            --slate-100: #F4F7F8;
            --white:     #FFFFFF;

            --red-800: #991B1B;
            --red-200: #FECACA;
            --red-50:  #FEF2F2;

            --emerald-800: #065F46;
            --emerald-200: #A7F3D0;
            --emerald-50:  #ECFDF5;

            --amber-800: #92400E;
            --amber-200: #FDE68A;
            --amber-50:  #FFFBEB;

            /* Semantic Tokens */
            --color-bg-page:        var(--slate-100);
            --color-bg-surface:     var(--white);
            --color-bg-subtle:      var(--slate-100);
            --color-bg-brand:       var(--teal-900);

            --color-text-primary:   var(--slate-900);
            --color-text-secondary: var(--slate-600);
            --color-text-strong:    var(--slate-700);
            --color-text-on-brand:  var(--white);

            --color-accent:         var(--teal-600);
            --color-accent-hover:   var(--teal-700);
            --color-accent-active:  var(--teal-900);
            --color-accent-ink:     var(--white);
            --color-accent-soft:    var(--teal-50);
            --color-accent-soft-ink: var(--teal-700);
            --color-focus:          var(--teal-600);

            --color-border-control: var(--slate-400);
            --color-border-divider: var(--slate-200);

            --color-error-bg:       var(--red-50);
            --color-error-border:   var(--red-200);
            --color-error-text:     var(--red-800);

            --color-success-bg:     var(--emerald-50);
            --color-success-border: var(--emerald-200);
            --color-success-text:   var(--emerald-800);

            --color-warning-bg:     var(--amber-50);
            --color-warning-border: var(--amber-200);
            --color-warning-text:   var(--amber-800);

            --radius-card:   1.25rem; /* 20px */
            --radius-nested: 0.75rem; /* 12px */
            --radius-btn:    0.625rem;/* 10px */
            --radius-pill:   9999px;

            --shadow-card:
                0px 0px 0px 1px rgba(0, 0, 0, 0.06),
                0px 1px 2px -1px rgba(0, 0, 0, 0.06),
                0px 8px 24px -6px rgba(0, 0, 0, 0.06);

            --shadow-card-hover:
                0px 0px 0px 1px rgba(0, 0, 0, 0.09),
                0px 2px 4px -1px rgba(0, 0, 0, 0.07),
                0px 12px 28px -6px rgba(0, 0, 0, 0.09);

            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --ease-out-smooth: cubic-bezier(0.2, 0, 0, 1);
        }

        /* =====================================================================
           Base & Reset
           ===================================================================== */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html {
            font-family: var(--font-sans);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-size-adjust: 100%;
            scroll-padding-block-start: 4.5rem;
        }

        body {
            margin: 0;
            min-height: 100dvh;
            background-color: var(--color-bg-page);
            color: var(--color-text-primary);
            line-height: 1.5;
            display: flex;
            flex-direction: column;
        }

        /* Skip link */
        .skip-link {
            position: absolute;
            inset-inline-start: 1rem;
            inset-block-start: -4rem;
            z-index: 50;
            padding: 0.625rem 1rem;
            border-radius: var(--radius-btn);
            background-color: var(--color-bg-brand);
            color: var(--color-text-on-brand);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
        }

        .skip-link:focus-visible {
            inset-block-start: 0.75rem;
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        /* =====================================================================
           Sticky Top Header (Mobile-first)
           ===================================================================== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background-color: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--color-border-divider);
        }

        .topbar-inner {
            max-width: 64rem;
            margin-inline: auto;
            padding-block: 0.75rem;
            padding-inline: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .topbar-inner {
                padding-inline: 1.5rem;
            }
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            min-width: 0;
            text-decoration: none;
            color: var(--color-text-primary);
            border-radius: var(--radius-btn);
        }

        .brand:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .brand-badge {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5625rem;
            background-color: var(--color-bg-brand);
            color: var(--color-text-on-brand);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .brand-name {
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.25;
            color: var(--color-text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-sub {
            font-size: 0.75rem;
            line-height: 1.3;
            color: var(--color-text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .btn-logout {
            min-height: 2.75rem; /* 44px touch target */
            padding-block: 0.5rem;
            padding-inline-start: 0.75rem; /* 2px less on icon side */
            padding-inline-end: 0.875rem;
            border: 1px solid var(--color-border-control);
            border-radius: var(--radius-btn);
            background-color: var(--color-bg-surface);
            color: var(--color-text-primary);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.3;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .btn-logout:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-text-secondary);
            }
        }

        .btn-logout:active {
            transform: scale(0.96);
        }

        .btn-logout:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-logout svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        /* =====================================================================
           Main Workspace Container
           ===================================================================== */
        .page-main {
            flex: 1;
            width: 100%;
            max-width: 64rem;
            margin-inline: auto;
            padding-block: 1.25rem 2.5rem;
            padding-inline: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .page-main {
                padding-block: 2rem 3rem;
                padding-inline: 1.5rem;
                gap: 1.5rem;
            }
        }

        /* =====================================================================
           Banners / Flash Messages
           ===================================================================== */
        .banner {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
            border-radius: var(--radius-nested);
            border: 1px solid transparent;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .banner-icon {
            width: 1.125rem;
            height: 1.125rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .banner-error {
            background-color: var(--color-error-bg);
            border-color: var(--color-error-border);
            color: var(--color-error-text);
        }

        .banner-success {
            background-color: var(--color-success-bg);
            border-color: var(--color-success-border);
            color: var(--color-success-text);
        }

        .banner-warning {
            background-color: var(--color-warning-bg);
            border-color: var(--color-warning-border);
            color: var(--color-warning-text);
        }

        .banner-title {
            margin: 0;
            font-weight: 600;
        }

        .banner-body {
            margin: 0.125rem 0 0;
        }

        /* =====================================================================
           Cards & Surfaces
           ===================================================================== */
        .card {
            background-color: var(--color-bg-surface);
            border-radius: var(--radius-card);
            border: 1px solid transparent; /* preserved in forced-colors mode */
            box-shadow: var(--shadow-card);
            padding: 1.25rem;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .card {
                padding: 1.75rem;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .card-enter {
                animation: card-enter 260ms var(--ease-out-smooth) both;
            }
            .card-enter-delayed {
                animation: card-enter 260ms var(--ease-out-smooth) 60ms both;
            }
        }

        @keyframes card-enter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hero Welcome & Primary Action Card */
        .hero-card {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        @media (min-width: 768px) {
            .hero-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 2rem;
            }
        }

        .hero-copy {
            min-width: 0;
            flex: 1;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-pill);
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }

        .status-pill--ready {
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
        }

        .status-pill--warning {
            background-color: var(--color-warning-bg);
            color: var(--color-warning-text);
            border: 1px solid var(--color-warning-border);
        }

        .status-pill svg {
            width: 0.875rem;
            height: 0.875rem;
            flex-shrink: 0;
        }

        .hero-title {
            margin: 0;
            font-size: clamp(1.375rem, 2.5vw, 1.75rem);
            font-weight: 600;
            line-height: 1.25;
            letter-spacing: -0.015em;
            color: var(--color-text-primary);
            text-wrap: balance;
            overflow-wrap: break-word;
        }

        .hero-subtitle {
            margin: 0.5rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: var(--color-text-secondary);
            max-width: 56ch;
            text-wrap: pretty;
        }

        .hero-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        @media (min-width: 640px) {
            .hero-actions {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
            }
        }

        /* Primary CTA (the single filled action on the page) */
        .btn-primary {
            width: 100%;
            min-height: 3rem; /* 48px touch target */
            padding-block: 0.75rem;
            padding-inline-start: 1.25rem;
            padding-inline-end: 1.125rem; /* 2px less on trailing icon side */
            border: 1px solid transparent;
            border-radius: var(--radius-btn);
            background-color: var(--color-accent);
            color: var(--color-accent-ink);
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.4;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (min-width: 640px) {
            .btn-primary {
                width: auto;
            }
        }

        @media (hover: hover) {
            .btn-primary:hover {
                background-color: var(--color-accent-hover);
            }
        }

        .btn-primary:active {
            background-color: var(--color-accent-active);
            transform: scale(0.96);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-primary svg {
            width: 1.125rem;
            height: 1.125rem;
            flex-shrink: 0;
        }

        /* Secondary neutral action */
        .btn-secondary {
            width: 100%;
            min-height: 2.75rem; /* 44px */
            padding-block: 0.625rem;
            padding-inline-start: 1rem;
            padding-inline-end: 0.875rem;
            border: 1px solid var(--color-border-control);
            border-radius: var(--radius-btn);
            background-color: var(--color-bg-surface);
            color: var(--color-text-primary);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.4;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (min-width: 640px) {
            .btn-secondary {
                width: auto;
            }
        }

        @media (hover: hover) {
            .btn-secondary:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-text-secondary);
            }
        }

        .btn-secondary:active {
            transform: scale(0.96);
        }

        .btn-secondary:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-secondary svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        /* =====================================================================
           Dashboard Grid (2 Columns on Tablet/Desktop, Stacked on Mobile)
           ===================================================================== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
            align-items: start;
        }

        @media (min-width: 768px) {
            .dashboard-grid {
                grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
                gap: 1.5rem;
            }
        }

        .stack-col {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .stack-col {
                gap: 1.5rem;
            }
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .section-title {
            margin: 0;
            font-size: 1.0625rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-primary);
        }

        .section-desc {
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
        }

        .metric-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-bg-subtle);
            color: var(--color-text-strong);
            font-size: 0.8125rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Latest consultation inset panel (concentric radius: 20px card - 20px/28px padding -> 12px) */
        .inset-panel {
            background-color: var(--color-bg-subtle);
            border-radius: var(--radius-nested);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .inset-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            font-variant-numeric: tabular-nums;
        }

        .diagnosis-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .diagnosis-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.3125rem 0.625rem;
            border-radius: 0.5rem;
            background-color: var(--color-bg-surface);
            border: 1px solid var(--color-border-divider);
            color: var(--color-text-primary);
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.35;
        }

        .diagnosis-chip-dot {
            width: 0.4375rem;
            height: 0.4375rem;
            border-radius: 9999px;
            background-color: var(--color-accent);
            flex-shrink: 0;
        }

        .empty-state {
            background-color: var(--color-bg-subtle);
            border-radius: var(--radius-nested);
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .empty-state-title {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .empty-state-desc {
            margin: 0;
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        .card-actions {
            margin-top: 1.125rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        /* Quick navigation links inside cards */
        .nav-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .nav-tile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.875rem;
            min-height: 3.5rem;
            padding: 0.875rem 1rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            border: 1px solid transparent;
            text-decoration: none;
            color: var(--color-text-primary);
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .nav-tile:hover {
                background-color: var(--color-accent-soft);
                border-color: var(--teal-100);
            }
        }

        .nav-tile:active {
            transform: scale(0.985);
        }

        .nav-tile:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .nav-tile-leading {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .nav-tile-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            background-color: var(--color-bg-surface);
            color: var(--color-accent-soft-ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid var(--color-border-divider);
        }

        .nav-tile-icon svg {
            width: 1.125rem;
            height: 1.125rem;
        }

        .nav-tile-title {
            display: block;
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-primary);
        }

        .nav-tile-sub {
            display: block;
            font-size: 0.8125rem;
            line-height: 1.4;
            color: var(--color-text-secondary);
        }

        .nav-tile-arrow {
            width: 1.125rem;
            height: 1.125rem;
            color: var(--color-text-secondary);
            flex-shrink: 0;
        }

        /* Patient profile summary definition list */
        .profile-dl {
            margin: 0;
            display: grid;
            gap: 0.75rem;
        }

        .profile-row {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--color-border-divider);
        }

        .profile-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .profile-dt {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--color-text-secondary);
        }

        .profile-dd {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--color-text-primary);
            overflow-wrap: break-word;
            font-variant-numeric: tabular-nums;
        }

        /* Admin badge card */
        .admin-card {
            background-color: var(--color-bg-brand);
            color: var(--color-text-on-brand);
        }

        .admin-card .section-title {
            color: var(--color-text-on-brand);
        }

        .admin-card .section-desc {
            color: var(--teal-100);
        }

        .admin-link {
            width: 100%;
            min-height: 2.75rem;
            padding-block: 0.625rem;
            padding-inline-start: 1rem;
            padding-inline-end: 0.875rem;
            border-radius: var(--radius-btn);
            background-color: var(--color-bg-surface);
            color: var(--color-bg-brand);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .admin-link:hover {
                background-color: var(--teal-50);
            }
        }

        .admin-link:active {
            transform: scale(0.96);
        }

        .admin-link:focus-visible {
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }

        .admin-link svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        /* Footer clinical note */
        .page-footer {
            max-width: 64rem;
            width: 100%;
            margin-inline: auto;
            padding-block: 0 1.75rem;
            padding-inline: 1rem;
        }

        @media (min-width: 640px) {
            .page-footer {
                padding-inline: 1.5rem;
            }
        }

        .clinical-note {
            margin: 0;
            font-size: 0.8125rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-align: center;
            text-wrap: pretty;
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $patient = $user?->patient;
        $displayName = $patient?->name ?: ($user?->email ?? 'Pasien');
        $consultationCount = $patient ? $patient->consultations()->count() : 0;
        $latestConsultation = $patient
            ? $patient->consultations()->with('diagnoses')->latest('consultation_date')->latest('id')->first()
            : null;
    @endphp

    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-badge" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 4c-2.2 0-4 1.8-4 4.2 0 2.6 1.2 4.3 2 7 .5 1.8 1.1 4.8 2.4 4.8 1.1 0 1.4-2.1 2.1-4.1.4-1.2 1.2-1.9 2.5-1.9s2.1.7 2.5 1.9c.7 2 1 4.1 2.1 4.1 1.3 0 1.9-3 2.4-4.8.8-2.7 2-4.4 2-7C21 5.8 19.2 4 17 4c-1.8 0-3 .8-5 .8S8.8 4 7 4Z"/>
                    </svg>
                </span>
                <span class="brand-text">
                    <span class="brand-name">Klinik Dentist</span>
                    <span class="brand-sub">Portal Pasien TMD</span>
                </span>
            </a>

            <div class="topbar-actions">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main id="main-content" class="page-main">
        @if (session('success'))
            <div class="banner banner-success" role="status">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div>
                    <p class="banner-title">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="banner banner-error" role="alert">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="banner-title">Terjadi kendala</p>
                    <p class="banner-body">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="banner banner-error" role="alert">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="banner-title">Periksa data Anda</p>
                    <p class="banner-body">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- Hero Card: satu aksi utama per tampilan --}}
        <section class="card card-enter hero-card" aria-labelledby="hero-heading">
            <div class="hero-copy">
                @if ($patient)
                    <span class="status-pill status-pill--ready">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Profil pasien siap
                    </span>
                    <h1 id="hero-heading" class="hero-title">Halo, {{ $displayName }}</h1>
                    <p class="hero-subtitle">
                        Lakukan skrining mandiri gangguan sendi rahang (TMD) untuk mengetahui kondisi awal keluhan Anda sebelum konsultasi langsung.
                    </p>
                @else
                    <span class="status-pill status-pill--warning">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Profil pasien belum lengkap
                    </span>
                    <h1 id="hero-heading" class="hero-title">Halo, {{ $displayName }}</h1>
                    <p class="hero-subtitle">
                        Lengkapi data diri pasien terlebih dahulu agar hasil kuesioner dan riwayat pemeriksaan TMD Anda dapat tercatat dengan benar.
                    </p>
                @endif
            </div>

            <div class="hero-actions">
                @if ($patient)
                    <a href="{{ route('consultations.create') }}" class="btn-primary">
                        <span>Mulai self-diagnosis</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('patients.create') }}" class="btn-primary">
                        <span>Lengkapi profil pasien</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                @endif
            </div>
        </section>

        <div class="dashboard-grid">
            {{-- Kolom utama: Ringkasan riwayat & menu layanan --}}
            <div class="stack-col">
                <section class="card card-enter-delayed" aria-labelledby="history-heading">
                    <div class="section-header">
                        <div>
                            <h2 id="history-heading" class="section-title">Riwayat diagnosis</h2>
                            <p class="section-desc">Ringkasan evaluasi sendi rahang yang pernah Anda kirim.</p>
                        </div>
                        <span class="metric-badge">{{ $consultationCount }} konsultasi</span>
                    </div>

                    @if ($latestConsultation)
                        <div class="inset-panel">
                            <div class="inset-meta">
                                <span>Konsultasi terakhir #{{ $latestConsultation->id }}</span>
                                <span>{{ optional($latestConsultation->consultation_date)->format('d/m/Y, H:i') }}</span>
                            </div>

                            @if ($latestConsultation->diagnoses->isNotEmpty())
                                <ul class="diagnosis-list">
                                    @foreach ($latestConsultation->diagnoses as $diagnosis)
                                        <li class="diagnosis-chip">
                                            <span class="diagnosis-chip-dot" aria-hidden="true"></span>
                                            <span>{{ $diagnosis->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="empty-state-desc">Tidak ada diagnosis spesifik yang ditemukan pada konsultasi terakhir.</p>
                            @endif
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('consultations.show', $latestConsultation->id) }}" class="btn-secondary">
                                <span>Lihat hasil terakhir</span>
                            </a>
                            <a href="{{ route('consultations.index') }}" class="btn-secondary">
                                <span>Semua riwayat diagnosis</span>
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <p class="empty-state-title">Belum ada riwayat diagnosis</p>
                            <p class="empty-state-desc">
                                Hasil skrining mandiri Anda akan muncul di sini setelah Anda menyelesaikan formulir pemeriksaan TMD.
                            </p>
                        </div>

                        @if ($patient)
                            <div class="card-actions">
                                <a href="{{ route('consultations.index') }}" class="btn-secondary">
                                    <span>Buka halaman riwayat</span>
                                </a>
                            </div>
                        @endif
                    @endif
                </section>

                <section class="card card-enter-delayed" aria-labelledby="services-heading">
                    <div class="section-header">
                        <div>
                            <h2 id="services-heading" class="section-title">Layanan pasien</h2>
                            <p class="section-desc">Akses cepat ke fitur skrining dan pengelolaan data Anda.</p>
                        </div>
                    </div>

                    <ul class="nav-list">
                        <li>
                            <a href="{{ route('consultations.create') }}" class="nav-tile">
                                <span class="nav-tile-leading">
                                    <span class="nav-tile-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 11l3 3L22 4"/>
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="nav-tile-title">Formulir self-diagnosis TMD</span>
                                        <span class="nav-tile-sub">Kuesioner keluhan dan pemeriksaan mandiri rahang</span>
                                    </span>
                                </span>
                                <svg class="nav-tile-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('consultations.index') }}" class="nav-tile">
                                <span class="nav-tile-leading">
                                    <span class="nav-tile-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="nav-tile-title">Riwayat diagnosis</span>
                                        <span class="nav-tile-sub">Lihat detail konsultasi dan catatan hasil sebelumnya</span>
                                    </span>
                                </span>
                                <svg class="nav-tile-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </section>
            </div>

            {{-- Kolom samping: Profil pasien & panel admin --}}
            <div class="stack-col">
                <section class="card card-enter-delayed" aria-labelledby="profile-heading">
                    <div class="section-header">
                        <div>
                            <h2 id="profile-heading" class="section-title">Profil pasien</h2>
                            <p class="section-desc">Identitas yang terhubung dengan konsultasi Anda.</p>
                        </div>
                    </div>

                    @if ($patient)
                        <dl class="profile-dl">
                            <div class="profile-row">
                                <dt class="profile-dt">Nama lengkap</dt>
                                <dd class="profile-dd">{{ $patient->name }}</dd>
                            </div>
                            <div class="profile-row">
                                <dt class="profile-dt">Tanggal lahir</dt>
                                <dd class="profile-dd">{{ $patient->birth_date_label }}</dd>
                            </div>
                            <div class="profile-row">
                                <dt class="profile-dt">Jenis kelamin</dt>
                                <dd class="profile-dd">{{ $patient->gender_label }}</dd>
                            </div>
                            <div class="profile-row">
                                <dt class="profile-dt">Nomor telepon</dt>
                                <dd class="profile-dd">{{ $patient->phone_number ?: '-' }}</dd>
                            </div>
                        </dl>

                        <div class="card-actions">
                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn-secondary">
                                <span>Perbarui profil pasien</span>
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <p class="empty-state-title">Data pasien belum tersedia</p>
                            <p class="empty-state-desc">
                                Buat profil pasien agar Anda bisa memulai formulir self-diagnosis TMD.
                            </p>
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('patients.create') }}" class="btn-secondary">
                                <span>Buat profil pasien</span>
                            </a>
                        </div>
                    @endif
                </section>

                @can('admin')
                    <section class="card card-enter-delayed admin-card" aria-labelledby="admin-heading">
                        <div class="section-header">
                            <div>
                                <h2 id="admin-heading" class="section-title">Panel administrator</h2>
                                <p class="section-desc">Kelola data pasien, statistik kuesioner, dan ringkasan diagnosis klinik.</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.dashboard') }}" class="admin-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <span>Buka dashboard admin</span>
                        </a>
                    </section>
                @endcan
            </div>
        </div>
    </main>

    <footer class="page-footer">
        <p class="clinical-note">
            Hasil skrining mandiri bersifat informatif dan tidak menggantikan pemeriksaan langsung oleh dokter gigi.
        </p>
    </footer>
</body>
</html>
