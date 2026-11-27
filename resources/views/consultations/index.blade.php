<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosis — Klinik Dentist · Self-Diagnosis TMD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* =====================================================================
           Design Tokens — Primitives & Semantic Roles
           ===================================================================== */
        :root {
            /* Primitives */
            --teal-900: #0B3B3C;
            --teal-700: #0A5858;
            --teal-600: #0D6E6E;
            --teal-100: #CFE6E6;
            --teal-50:  #E9F4F4;

            --slate-900: #0F172A;
            --slate-700: #334155;
            --slate-600: #475569;
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

            /* Semantic Tokens */
            --color-bg-page:        var(--slate-100);
            --color-bg-surface:     var(--white);
            --color-bg-subtle:      var(--slate-100);
            --color-bg-brand:       var(--teal-900);

            --color-text-primary:   var(--slate-900);
            --color-text-secondary: var(--slate-600);
            --color-text-strong:    var(--slate-700);
            --color-text-on-brand:  var(--white);

            --color-accent:          var(--teal-600);
            --color-accent-hover:    var(--teal-700);
            --color-accent-active:   var(--teal-900);
            --color-accent-ink:      var(--white);
            --color-accent-soft:     var(--teal-50);
            --color-accent-soft-ink: var(--teal-700);
            --color-focus:           var(--teal-600);

            --color-border-control: var(--slate-400);
            --color-border-divider: var(--slate-200);

            --color-error-bg:       var(--red-50);
            --color-error-border:   var(--red-200);
            --color-error-text:     var(--red-800);

            --color-success-bg:     var(--emerald-50);
            --color-success-border: var(--emerald-200);
            --color-success-text:   var(--emerald-800);

            --radius-card:   1.25rem; /* 20px */
            --radius-nested: 0.75rem; /* 12px */
            --radius-btn:    0.625rem;/* 10px */
            --radius-pill:   9999px;

            --shadow-card:
                0px 0px 0px 1px rgba(0, 0, 0, 0.06),
                0px 1px 2px -1px rgba(0, 0, 0, 0.06),
                0px 8px 24px -6px rgba(0, 0, 0, 0.06);

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
            max-width: 58rem;
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

        .btn-back {
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .btn-back:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-text-secondary);
            }
        }

        .btn-back:active {
            transform: scale(0.96);
        }

        .btn-back:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-back svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        /* =====================================================================
           Main Content
           ===================================================================== */
        .page-main {
            flex: 1;
            width: 100%;
            max-width: 58rem;
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

        /* Flash banners */
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

        .banner-title {
            margin: 0;
            font-weight: 600;
        }

        .banner-body {
            margin: 0.125rem 0 0;
        }

        /* Page Header */
        .page-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .page-header {
                flex-direction: row;
                align-items: flex-end;
                justify-content: space-between;
                gap: 1.5rem;
            }
        }

        .page-header-copy {
            min-width: 0;
        }

        .title-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.625rem;
        }

        .page-title {
            margin: 0;
            font-size: clamp(1.375rem, 2.5vw, 1.75rem);
            font-weight: 600;
            line-height: 1.25;
            letter-spacing: -0.015em;
            color: var(--color-text-primary);
            text-wrap: balance;
        }

        .count-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            font-size: 0.8125rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .page-subtitle {
            margin: 0.375rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            max-width: 56ch;
            text-wrap: pretty;
        }

        .page-header-action {
            flex-shrink: 0;
        }

        /* Primary CTA */
        .btn-primary {
            width: 100%;
            min-height: 3rem; /* 48px */
            padding-block: 0.75rem;
            padding-inline-start: 1.25rem;
            padding-inline-end: 1.125rem;
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

        /* Secondary Button */
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
           Cards & Consultation List
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
                padding: 1.625rem;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .card-enter {
                animation: card-enter 260ms var(--ease-out-smooth) both;
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

        .consultation-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .consultation-list {
                gap: 1.25rem;
            }
        }

        .consultation-header {
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-border-divider);
        }

        @media (min-width: 640px) {
            .consultation-header {
                flex-direction: row;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
            }
        }

        .consultation-title-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .consultation-title {
            margin: 0;
            font-size: 1.0625rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-primary);
            font-variant-numeric: tabular-nums;
        }

        .latest-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.1875rem 0.5rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .consultation-meta {
            margin: 0.25rem 0 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.375rem 0.875rem;
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            font-variant-numeric: tabular-nums;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .meta-item svg {
            width: 0.9375rem;
            height: 0.9375rem;
            flex-shrink: 0;
        }

        .consultation-body {
            padding-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .consultation-body--with-photo {
                flex-direction: row;
                align-items: flex-start;
                gap: 1.25rem;
            }
        }

        .photo-frame {
            width: 6.5rem;
            height: 6.5rem;
            border-radius: var(--radius-nested);
            overflow: hidden;
            flex-shrink: 0;
            background-color: var(--color-bg-subtle);
        }

        @media (min-width: 640px) {
            .photo-frame {
                width: 7.5rem;
                height: 7.5rem;
            }
        }

        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: var(--radius-nested);
            outline: 1px solid rgba(0, 0, 0, 0.1);
            outline-offset: -1px;
        }

        .diagnosis-block {
            flex: 1;
            min-width: 0;
        }

        .diagnosis-heading {
            margin: 0 0 0.625rem;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--color-text-strong);
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
            gap: 0.4375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            background-color: var(--color-bg-subtle);
            border: 1px solid var(--color-border-divider);
            color: var(--color-text-primary);
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .diagnosis-chip-dot {
            width: 0.4375rem;
            height: 0.4375rem;
            border-radius: 9999px;
            background-color: var(--color-accent);
            flex-shrink: 0;
        }

        .diagnosis-none {
            margin: 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
        }

        .consultation-footer {
            margin-top: 1.125rem;
            padding-top: 1rem;
            border-top: 1px solid var(--color-border-divider);
            display: flex;
            justify-content: flex-end;
        }

        /* Empty State */
        .empty-card {
            padding: 2rem 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .empty-card {
                padding: 3rem 2rem;
            }
        }

        .empty-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.875rem;
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .empty-title {
            margin: 0.25rem 0 0;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.3;
            color: var(--color-text-primary);
        }

        .empty-desc {
            margin: 0;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: var(--color-text-secondary);
            max-width: 44ch;
            text-wrap: pretty;
        }

        .empty-action {
            margin-top: 0.5rem;
            width: 100%;
        }

        @media (min-width: 640px) {
            .empty-action {
                width: auto;
            }
        }

        /* Footer */
        .page-footer {
            max-width: 58rem;
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
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
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

        <header class="page-header">
            <div class="page-header-copy">
                <div class="title-row">
                    <h1 class="page-title">Riwayat diagnosis</h1>
                    <span class="count-pill">{{ $consultations->count() }} konsultasi</span>
                </div>
                <p class="page-subtitle">
                    Lihat hasil evaluasi sendi rahang yang pernah Anda kirim beserta detail keluhan dan pemeriksaannya.
                </p>
            </div>

            @if ($consultations->isNotEmpty())
                <div class="page-header-action">
                    <a href="{{ route('consultations.create') }}" class="btn-primary">
                        <span>Mulai diagnosis baru</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                </div>
            @endif
        </header>

        @if ($consultations->isEmpty())
            <section class="card card-enter empty-card" aria-labelledby="empty-heading">
                <span class="empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </span>
                <h2 id="empty-heading" class="empty-title">Belum ada riwayat diagnosis</h2>
                <p class="empty-desc">
                    Anda belum pernah mengisi skrining mandiri TMD. Mulai pemeriksaan pertama untuk mengetahui kondisi awal sendi rahang Anda.
                </p>
                <div class="empty-action">
                    <a href="{{ route('consultations.create') }}" class="btn-primary">
                        <span>Mulai self-diagnosis pertama</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                </div>
            </section>
        @else
            <section class="consultation-list" aria-label="Daftar riwayat konsultasi">
                @foreach ($consultations as $consultation)
                    @php
                        $photoPath = $consultation->photoPath();
                        $openingMm = $consultation->openingMm();
                    @endphp
                    <article class="card card-enter" aria-labelledby="consultation-heading-{{ $consultation->id }}">
                        <div class="consultation-header">
                            <div>
                                <div class="consultation-title-row">
                                    <h2 id="consultation-heading-{{ $consultation->id }}" class="consultation-title">
                                        Konsultasi #{{ $consultation->id }}
                                    </h2>
                                    @if ($loop->first)
                                        <span class="latest-badge">Terbaru</span>
                                    @endif
                                </div>
                                <div class="consultation-meta">
                                    <span class="meta-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        <span>{{ optional($consultation->consultation_date)->format('d/m/Y, H:i') ?? '-' }}</span>
                                    </span>
                                    @if ($openingMm !== null && $openingMm !== '')
                                        <span class="meta-item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M21 6H3"/>
                                                <path d="M10 12H3"/>
                                                <path d="M10 18H3"/>
                                                <circle cx="17" cy="15" r="3"/>
                                            </svg>
                                            <span>Bukaan mulut: {{ $openingMm }} mm</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="consultation-body {{ $photoPath ? 'consultation-body--with-photo' : '' }}">
                            @if ($photoPath)
                                <div class="photo-frame">
                                    <img
                                        src="{{ route('consultations.photo', $consultation) }}"
                                        alt="Foto mulut terbuka untuk konsultasi #{{ $consultation->id }}"
                                        class="photo-img"
                                        loading="lazy"
                                    >
                                </div>
                            @endif

                            <div class="diagnosis-block">
                                <p class="diagnosis-heading">Hasil diagnosis</p>
                                @if ($consultation->diagnoses->isEmpty())
                                    <p class="diagnosis-none">Tidak ada diagnosis spesifik yang ditemukan.</p>
                                @else
                                    <ul class="diagnosis-list">
                                        @foreach ($consultation->diagnoses as $diagnosis)
                                            <li class="diagnosis-chip">
                                                <span class="diagnosis-chip-dot" aria-hidden="true"></span>
                                                <span>{{ $diagnosis->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="consultation-footer">
                            <a
                                href="{{ route('consultations.show', $consultation->id) }}"
                                class="btn-secondary"
                                aria-label="Lihat detail konsultasi #{{ $consultation->id }}"
                            >
                                <span>Lihat detail</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </main>

    <footer class="page-footer">
        <p class="clinical-note">
            Hasil skrining mandiri bersifat informatif dan tidak menggantikan pemeriksaan langsung oleh dokter gigi.
        </p>
    </footer>
</body>
</html>
