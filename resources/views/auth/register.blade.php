<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Klinik Dentist · Self-Diagnosis TMD</title>
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
            --teal-500: #0F7F7F;
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
            --red-700: #B91C1C;
            --red-600: #DC2626;
            --red-200: #FECACA;
            --red-50:  #FEF2F2;

            --emerald-800: #065F46;
            --emerald-700: #047857;
            --emerald-200: #A7F3D0;
            --emerald-50:  #ECFDF5;

            /* Semantic Tokens */
            --color-bg-page:          var(--slate-100);
            --color-bg-surface:       var(--white);
            --color-bg-brand:         var(--teal-900);
            --color-bg-brand-card:    var(--teal-800);

            --color-text-primary:     var(--slate-900);
            --color-text-secondary:   var(--slate-600);
            --color-text-section:     var(--slate-700);
            --color-text-placeholder: var(--slate-500);
            --color-text-brand:       var(--white);
            --color-text-brand-muted: var(--teal-100);

            --color-accent:           var(--teal-600);
            --color-accent-hover:     var(--teal-700);
            --color-accent-active:    var(--teal-900);
            --color-accent-ink:       var(--white);
            --color-link:             var(--teal-700);
            --color-focus:            var(--teal-600);

            --color-border-input:       var(--slate-400);
            --color-border-input-hover: var(--slate-600);
            --color-border-divider:     var(--slate-200);

            --color-error-bg:           var(--red-50);
            --color-error-border:       var(--red-600);
            --color-error-soft-border:  var(--red-200);
            --color-error-text:         var(--red-800);
            --color-error-inline:       var(--red-700);

            --color-success-bg:          var(--emerald-50);
            --color-success-border:      var(--emerald-700);
            --color-success-soft-border: var(--emerald-200);
            --color-success-text:        var(--emerald-800);

            --radius-card:  1.25rem; /* 20px */
            --radius-panel: 1rem;    /* 16px */
            --radius-input: 0.625rem;/* 10px */
            --radius-inner: 0.375rem;/* 6px = 10px input - 4px inset */

            --shadow-card:
                0px 0px 0px 1px rgba(0, 0, 0, 0.06),
                0px 1px 2px -1px rgba(0, 0, 0, 0.06),
                0px 12px 32px -8px rgba(0, 0, 0, 0.08);

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
        }

        body {
            margin: 0;
            min-height: 100dvh;
            background-color: var(--color-bg-page);
            color: var(--color-text-primary);
            line-height: 1.5;
        }

        /* =====================================================================
           Page Shell (Mobile-first, 320px safe, 100dvh)
           ===================================================================== */
        .page-shell {
            min-height: 100dvh;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
        }

        @media (min-width: 1024px) {
            .page-shell {
                grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
            }
        }

        /* =====================================================================
           Leading Brand / Clinical Context Panel (Desktop >= 1024px)
           ===================================================================== */
        .brand-panel {
            display: none;
            background-color: var(--color-bg-brand);
            color: var(--color-text-brand);
            position: relative;
            overflow: hidden;
            padding: 3rem;
        }

        @media (min-width: 1024px) {
            .brand-panel {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
        }

        .brand-backdrop {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                radial-gradient(circle at 85% 15%, rgba(207, 230, 230, 0.14) 0%, transparent 45%),
                radial-gradient(circle at 15% 90%, rgba(15, 127, 127, 0.28) 0%, transparent 50%);
        }

        .brand-inner {
            position: relative;
            z-index: 1;
            max-width: 30rem;
            display: flex;
            flex-direction: column;
            gap: 2.25rem;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: -0.01em;
            color: var(--color-text-brand);
        }

        .brand-badge {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.625rem;
            background-color: var(--color-bg-brand-card);
            border: 1px solid rgba(255, 255, 255, 0.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--color-text-brand);
        }

        .brand-headline {
            margin: 0;
            font-size: clamp(1.75rem, 2.4vw, 2.25rem);
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -0.02em;
            text-wrap: balance;
        }

        .brand-copy {
            margin: 0.875rem 0 0;
            font-size: 1rem;
            line-height: 1.6;
            color: var(--color-text-brand-muted);
            max-width: 42ch;
            text-wrap: pretty;
        }

        .clinical-steps {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 1rem;
        }

        .clinical-step {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 1rem 1.125rem;
            border-radius: var(--radius-panel);
            background-color: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .clinical-step-num {
            width: 1.625rem;
            height: 1.625rem;
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.12);
            color: var(--color-text-brand);
            font-size: 0.8125rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .clinical-step-title {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-brand);
        }

        .clinical-step-desc {
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--color-text-brand-muted);
            text-wrap: pretty;
        }

        .brand-footer {
            position: relative;
            z-index: 1;
            font-size: 0.8125rem;
            line-height: 1.5;
            color: var(--color-text-brand-muted);
            max-width: 46ch;
        }

        /* =====================================================================
           Main Auth Column
           ===================================================================== */
        .auth-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem 2.5rem;
        }

        @media (min-width: 640px) {
            .auth-main {
                padding: 2.5rem 2rem;
            }
        }

        .auth-container {
            width: 100%;
            max-width: 32rem; /* ~512px for comfortable 2-col row on desktop */
            min-width: 0;
        }

        /* Mobile-only compact brand header */
        .mobile-brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 1.25rem;
            color: var(--color-text-primary);
        }

        @media (min-width: 1024px) {
            .mobile-brand {
                display: none;
            }
        }

        .mobile-brand-badge {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            background-color: var(--color-bg-brand);
            color: var(--color-text-brand);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mobile-brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .mobile-brand-name {
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.25;
            color: var(--color-text-primary);
        }

        .mobile-brand-sub {
            font-size: 0.8125rem;
            line-height: 1.3;
            color: var(--color-text-secondary);
        }

        /* Auth Card */
        .auth-card {
            background-color: var(--color-bg-surface);
            border-radius: var(--radius-card);
            border: 1px solid transparent; /* preserved in forced-colors mode */
            box-shadow: var(--shadow-card);
            padding: 1.5rem;
        }

        @media (min-width: 640px) {
            .auth-card {
                padding: 2rem;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .auth-card {
                animation: card-enter 280ms var(--ease-out-smooth) both;
            }
        }

        @keyframes card-enter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            margin-bottom: 1.5rem;
        }

        .auth-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.25;
            letter-spacing: -0.015em;
            color: var(--color-text-primary);
            text-wrap: balance;
        }

        @media (min-width: 640px) {
            .auth-title {
                font-size: 1.625rem;
            }
        }

        .auth-subtitle {
            margin: 0.5rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        /* =====================================================================
           Status & Alert Banners
           ===================================================================== */
        .banner {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
            border-radius: var(--radius-input);
            border: 1px solid transparent;
            margin-bottom: 1.25rem;
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
            border-color: var(--color-error-soft-border);
            color: var(--color-error-text);
        }

        .banner-success {
            background-color: var(--color-success-bg);
            border-color: var(--color-success-soft-border);
            color: var(--color-success-text);
        }

        .banner-title {
            margin: 0;
            font-weight: 600;
        }

        .banner-body {
            margin: 0.125rem 0 0;
        }

        .banner-list {
            margin: 0.25rem 0 0;
            padding-inline-start: 1.125rem;
        }

        /* =====================================================================
           Form Sections & Controls
           ===================================================================== */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.75rem; /* 28px between logical sections */
        }

        .form-section {
            border: 0;
            margin: 0;
            padding: 0;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1.125rem; /* 18px between fields (>= 2x 6px inner gap) */
        }

        .section-heading {
            margin: 0 0 0.125rem;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--color-text-section);
        }

        .field-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.125rem;
        }

        @media (min-width: 640px) {
            .field-row {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 1rem;
            }
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 0.375rem; /* 6px within a field group */
            min-width: 0;
        }

        .field-label {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
            color: var(--color-text-primary);
            align-self: flex-start;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-input,
        .field-select {
            width: 100%;
            min-height: 3rem; /* 48px touch-friendly target */
            padding-block: 0.6875rem;
            padding-inline: 0.875rem;
            font-family: inherit;
            font-size: 1rem; /* 16px prevents iOS Safari auto-zoom */
            line-height: 1.5;
            color: var(--color-text-primary);
            background-color: var(--color-bg-surface);
            border: 1px solid var(--color-border-input);
            border-radius: var(--radius-input);
            appearance: none;
            transition-property: border-color, box-shadow;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        .field-input--password {
            padding-inline-end: 3.125rem; /* room for 40px toggle button */
        }

        .field-select {
            padding-inline-end: 2.75rem;
            cursor: pointer;
        }

        .field-select.is-placeholder {
            color: var(--color-text-placeholder);
        }

        .select-chevron {
            position: absolute;
            inset-inline-end: 0.875rem;
            width: 1.125rem;
            height: 1.125rem;
            color: var(--color-text-secondary);
            pointer-events: none;
        }

        .field-input::placeholder {
            color: var(--color-text-placeholder);
            opacity: 1;
        }

        @media (hover: hover) {
            .field-input:hover:not(:focus-visible):not([aria-invalid="true"]),
            .field-select:hover:not(:focus-visible):not([aria-invalid="true"]) {
                border-color: var(--color-border-input-hover);
            }
        }

        .field-input:focus-visible,
        .field-select:focus-visible {
            border-color: var(--color-focus);
            outline: 2px solid var(--color-focus);
            outline-offset: 1px;
        }

        .field-input[aria-invalid="true"],
        .field-select[aria-invalid="true"] {
            border-color: var(--color-error-border);
        }

        .field-input[aria-invalid="true"]:focus-visible,
        .field-select[aria-invalid="true"]:focus-visible {
            border-color: var(--color-error-border);
            outline: 2px solid var(--color-error-border);
            outline-offset: 1px;
        }

        .field-hint {
            margin: 0;
            font-size: 0.8125rem;
            line-height: 1.45;
            color: var(--color-text-secondary);
        }

        /* Password visibility toggle button */
        .password-toggle {
            position: absolute;
            inset-inline-end: 0.25rem; /* 4px inset inside 10px input -> 6px radius */
            width: 2.5rem;  /* 40px hit target */
            height: 2.5rem; /* 40px hit target */
            padding: 0;
            border: 0;
            border-radius: var(--radius-inner);
            background: transparent;
            color: var(--color-text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: color, background-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .password-toggle:hover {
                color: var(--color-text-primary);
                background-color: var(--color-bg-page);
            }
        }

        .password-toggle:active {
            transform: scale(0.96);
        }

        .password-toggle:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 1px;
            color: var(--color-text-primary);
        }

        .password-toggle svg {
            position: absolute;
            width: 1.25rem;
            height: 1.25rem;
            pointer-events: none;
            transition-property: opacity, transform;
            transition-duration: 200ms;
            transition-timing-function: var(--ease-out-smooth);
        }

        .password-toggle .icon-eye-off {
            opacity: 0;
            transform: scale(0.25);
        }

        .password-toggle[aria-pressed="true"] .icon-eye {
            opacity: 0;
            transform: scale(0.25);
        }

        .password-toggle[aria-pressed="true"] .icon-eye-off {
            opacity: 1;
            transform: scale(1);
        }

        @media (prefers-reduced-motion: reduce) {
            .password-toggle svg {
                transition-property: opacity;
                transition-duration: 120ms;
            }
            .password-toggle .icon-eye-off,
            .password-toggle[aria-pressed="true"] .icon-eye {
                transform: none;
            }
        }

        /* Inline field errors */
        .field-error {
            display: flex;
            align-items: flex-start;
            gap: 0.375rem;
            margin: 0.125rem 0 0;
            font-size: 0.8125rem;
            line-height: 1.4;
            color: var(--color-error-inline);
        }

        .field-error[hidden] {
            display: none;
        }

        .field-error svg {
            width: 1rem;
            height: 1rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        /* Submit Button */
        .submit-wrap {
            margin-top: 0.25rem;
        }

        .btn-primary {
            width: 100%;
            min-height: 3rem; /* 48px */
            padding-block: 0.75rem;
            padding-inline: 1.25rem;
            border: 1px solid transparent;
            border-radius: var(--radius-input);
            background-color: var(--color-accent);
            color: var(--color-accent-ink);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, transform, box-shadow;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .btn-primary:hover:not([aria-disabled="true"]) {
                background-color: var(--color-accent-hover);
            }
        }

        .btn-primary:not([aria-disabled="true"]):active {
            background-color: var(--color-accent-active);
            transform: scale(0.96);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-primary[aria-disabled="true"] {
            opacity: 0.82;
            cursor: wait;
        }

        .btn-spinner {
            width: 1.125rem;
            height: 1.125rem;
            display: none;
            animation: spin 750ms linear infinite;
        }

        .btn-primary[aria-disabled="true"] .btn-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Card Footer / Login Link */
        .auth-footer {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--color-border-divider);
            text-align: center;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
        }

        .auth-link {
            color: var(--color-link);
            font-weight: 600;
            text-decoration: underline;
            text-decoration-thickness: from-font;
            text-underline-offset: 0.2em;
            border-radius: 0.25rem;
            padding: 0.25rem 0.375rem;
            margin-inline: -0.125rem;
            display: inline-block;
            -webkit-tap-highlight-color: transparent;
            transition-property: color, background-color;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .auth-link:hover {
                color: var(--color-accent-active);
                background-color: var(--teal-50);
            }
        }

        .auth-link:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        /* Clinical note under card */
        .clinical-note {
            margin: 1.25rem 0 0;
            text-align: center;
            font-size: 0.8125rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <aside class="brand-panel" aria-label="Informasi Pendaftaran Klinik Dentist">
            <div class="brand-backdrop" aria-hidden="true"></div>
            <div class="brand-inner">
                <div class="brand-mark">
                    <span class="brand-badge" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 4c-2.2 0-4 1.8-4 4.2 0 2.6 1.2 4.3 2 7 .5 1.8 1.1 4.8 2.4 4.8 1.1 0 1.4-2.1 2.1-4.1.4-1.2 1.2-1.9 2.5-1.9s2.1.7 2.5 1.9c.7 2 1 4.1 2.1 4.1 1.3 0 1.9-3 2.4-4.8.8-2.7 2-4.4 2-7C21 5.8 19.2 4 17 4c-1.8 0-3 .8-5 .8S8.8 4 7 4Z"/>
                        </svg>
                    </span>
                    <span>Klinik Dentist · TMD Care</span>
                </div>

                <div>
                    <p class="brand-headline">Mulai profil pasien untuk skrining mandiri rahang</p>
                    <p class="brand-copy">
                        Data diri Anda membantu dokter gigi memahami konteks keluhan sebelum menyimpulkan hasil pemeriksaan DC/TMD.
                    </p>
                </div>

                <ol class="clinical-steps">
                    <li class="clinical-step">
                        <span class="clinical-step-num" aria-hidden="true">1</span>
                        <div>
                            <p class="clinical-step-title">Lengkapi profil dasar pasien</p>
                            <p class="clinical-step-desc">Nama, tanggal lahir, jenis kelamin, dan kontak digunakan sebagai identitas rekam konsultasi Anda.</p>
                        </div>
                    </li>
                    <li class="clinical-step">
                        <span class="clinical-step-num" aria-hidden="true">2</span>
                        <div>
                            <p class="clinical-step-title">Isi kuesioner & pemeriksaan mandiri</p>
                            <p class="clinical-step-desc">Setelah terdaftar, Anda langsung masuk ke dashboard untuk memulai evaluasi keluhan sendi rahang.</p>
                        </div>
                    </li>
                    <li class="clinical-step">
                        <span class="clinical-step-num" aria-hidden="true">3</span>
                        <div>
                            <p class="clinical-step-title">Pantau riwayat hasil kapan saja</p>
                            <p class="clinical-step-desc">Seluruh riwayat konsultasi dan foto pemeriksaan tersimpan privat dalam akun Anda.</p>
                        </div>
                    </li>
                </ol>
            </div>

            <p class="brand-footer">
                Hasil skrining mandiri bersifat informatif dan tidak menggantikan pemeriksaan langsung oleh dokter gigi.
            </p>
        </aside>

        <main id="main-content" class="auth-main">
            <div class="auth-container">
                <div class="mobile-brand">
                    <span class="mobile-brand-badge" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 4c-2.2 0-4 1.8-4 4.2 0 2.6 1.2 4.3 2 7 .5 1.8 1.1 4.8 2.4 4.8 1.1 0 1.4-2.1 2.1-4.1.4-1.2 1.2-1.9 2.5-1.9s2.1.7 2.5 1.9c.7 2 1 4.1 2.1 4.1 1.3 0 1.9-3 2.4-4.8.8-2.7 2-4.4 2-7C21 5.8 19.2 4 17 4c-1.8 0-3 .8-5 .8S8.8 4 7 4Z"/>
                        </svg>
                    </span>
                    <div class="mobile-brand-text">
                        <span class="mobile-brand-name">Klinik Dentist</span>
                        <span class="mobile-brand-sub">Pendaftaran Pasien TMD</span>
                    </div>
                </div>

                <div class="auth-card">
                    <header class="auth-header">
                        <h1 class="auth-title">Buat akun pasien</h1>
                        <p class="auth-subtitle">Isi data diri dan informasi akun untuk mulai menggunakan layanan self-diagnosis TMD.</p>
                    </header>

                    @if (session('error'))
                        <div class="banner banner-error" role="alert">
                            <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <div>
                                <p class="banner-title">Pendaftaran gagal</p>
                                <p class="banner-body">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @php
                        $fieldKeys = ['name', 'date_of_birth', 'gender', 'phone_number', 'email', 'password', 'password_confirmation'];
                        $otherErrors = collect($errors->getMessages())
                            ->reject(fn ($msgs, $key) => in_array($key, $fieldKeys, true))
                            ->flatten();
                    @endphp

                    @if ($otherErrors->isNotEmpty())
                        <div class="banner banner-error" role="alert">
                            <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <div>
                                <p class="banner-title">Periksa kembali data pendaftaran</p>
                                <ul class="banner-list">
                                    @foreach ($otherErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form id="register-form" class="auth-form" action="{{ route('register') }}" method="POST" novalidate>
                        @csrf

                        <section class="form-section" aria-labelledby="section-patient-heading">
                            <h2 id="section-patient-heading" class="section-heading">Data diri pasien</h2>

                            <div class="field-group">
                                <label for="name" class="field-label">Nama lengkap</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="field-input"
                                    value="{{ old('name') }}"
                                    placeholder="Budi Santoso"
                                    autocomplete="name"
                                    required
                                    @if ($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif
                                >
                                <p id="name-error" class="field-error" @if (!$errors->has('name')) hidden @endif>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span id="name-error-text">{{ $errors->first('name') }}</span>
                                </p>
                            </div>

                            <div class="field-row">
                                <div class="field-group">
                                    <label for="date_of_birth" class="field-label">Tanggal lahir</label>
                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        id="date_of_birth"
                                        class="field-input"
                                        value="{{ old('date_of_birth') }}"
                                        autocomplete="bday"
                                        required
                                        @if ($errors->has('date_of_birth')) aria-invalid="true" aria-describedby="date_of_birth-error" @endif
                                    >
                                    <p id="date_of_birth-error" class="field-error" @if (!$errors->has('date_of_birth')) hidden @endif>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        <span id="date_of_birth-error-text">{{ $errors->first('date_of_birth') }}</span>
                                    </p>
                                </div>

                                <div class="field-group">
                                    <label for="gender" class="field-label">Jenis kelamin</label>
                                    <div class="input-wrap">
                                        <select
                                            name="gender"
                                            id="gender"
                                            class="field-select {{ old('gender') ? '' : 'is-placeholder' }}"
                                            autocomplete="sex"
                                            required
                                            @if ($errors->has('gender')) aria-invalid="true" aria-describedby="gender-error" @endif
                                        >
                                            <option value="" disabled @selected(!old('gender'))>Pilih salah satu</option>
                                            <option value="Male" @selected(old('gender') === 'Male')>Laki-laki</option>
                                            <option value="Female" @selected(old('gender') === 'Female')>Perempuan</option>
                                        </select>
                                        <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </div>
                                    <p id="gender-error" class="field-error" @if (!$errors->has('gender')) hidden @endif>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        <span id="gender-error-text">{{ $errors->first('gender') }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="field-group">
                                <label for="phone_number" class="field-label">Nomor telepon</label>
                                <input
                                    type="tel"
                                    name="phone_number"
                                    id="phone_number"
                                    class="field-input"
                                    value="{{ old('phone_number') }}"
                                    placeholder="081234567890"
                                    autocomplete="tel"
                                    inputmode="tel"
                                    required
                                    @if ($errors->has('phone_number')) aria-invalid="true" aria-describedby="phone_number-error" @endif
                                >
                                <p id="phone_number-error" class="field-error" @if (!$errors->has('phone_number')) hidden @endif>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span id="phone_number-error-text">{{ $errors->first('phone_number') }}</span>
                                </p>
                            </div>
                        </section>

                        <section class="form-section" aria-labelledby="section-account-heading">
                            <h2 id="section-account-heading" class="section-heading">Detail akun</h2>

                            <div class="field-group">
                                <label for="email" class="field-label">Alamat email</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="field-input"
                                    value="{{ old('email') }}"
                                    placeholder="nama@contoh.com"
                                    autocomplete="email"
                                    inputmode="email"
                                    spellcheck="false"
                                    autocapitalize="none"
                                    required
                                    @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                                >
                                <p id="email-error" class="field-error" @if (!$errors->has('email')) hidden @endif>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span id="email-error-text">{{ $errors->first('email') }}</span>
                                </p>
                            </div>

                            <div class="field-group">
                                <label for="password" class="field-label">Kata sandi</label>
                                <div class="input-wrap">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="field-input field-input--password"
                                        autocomplete="new-password"
                                        spellcheck="false"
                                        required
                                        aria-describedby="{{ $errors->has('password') ? 'password-hint password-error' : 'password-hint' }}"
                                        @if ($errors->has('password')) aria-invalid="true" @endif
                                    >
                                    <button
                                        type="button"
                                        class="password-toggle"
                                        data-toggle-target="password"
                                        aria-label="Tampilkan kata sandi"
                                        aria-pressed="false"
                                        aria-controls="password"
                                    >
                                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
                                            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/>
                                            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
                                            <path d="m2 2 20 20"/>
                                        </svg>
                                    </button>
                                </div>
                                <p id="password-hint" class="field-hint">Gunakan minimal 8 karakter.</p>
                                <p id="password-error" class="field-error" @if (!$errors->has('password')) hidden @endif>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span id="password-error-text">{{ $errors->first('password') }}</span>
                                </p>
                            </div>

                            <div class="field-group">
                                <label for="password_confirmation" class="field-label">Konfirmasi kata sandi</label>
                                <div class="input-wrap">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="field-input field-input--password"
                                        autocomplete="new-password"
                                        spellcheck="false"
                                        required
                                        @if ($errors->has('password_confirmation')) aria-invalid="true" aria-describedby="password_confirmation-error" @endif
                                    >
                                    <button
                                        type="button"
                                        class="password-toggle"
                                        data-toggle-target="password_confirmation"
                                        aria-label="Tampilkan konfirmasi kata sandi"
                                        aria-pressed="false"
                                        aria-controls="password_confirmation"
                                    >
                                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
                                            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/>
                                            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
                                            <path d="m2 2 20 20"/>
                                        </svg>
                                    </button>
                                </div>
                                <p id="password_confirmation-error" class="field-error" @if (!$errors->has('password_confirmation')) hidden @endif>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span id="password_confirmation-error-text">{{ $errors->first('password_confirmation') }}</span>
                                </p>
                            </div>
                        </section>

                        <div class="submit-wrap">
                            <button type="submit" id="submit-btn" class="btn-primary" aria-disabled="false">
                                <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                </svg>
                                <span id="submit-label">Buat akun</span>
                            </button>
                        </div>
                    </form>

                    <div class="auth-footer">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="auth-link">Masuk ke akun Anda</a>
                    </div>
                </div>

                <p class="clinical-note">
                    Data profil dan konsultasi Anda disimpan secara privat dan hanya digunakan untuk evaluasi klinis.
                </p>
            </div>
        </main>
    </div>

    <script>
        (function () {
            const form = document.getElementById('register-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitLabel = document.getElementById('submit-label');
            const genderSelect = document.getElementById('gender');

            let isSubmitting = false;

            const fields = [
                {
                    input: document.getElementById('name'),
                    errorEl: document.getElementById('name-error'),
                    errorTextEl: document.getElementById('name-error-text'),
                    baseDescribedBy: '',
                    trimOnSubmit: true,
                    validate: function (val) {
                        const trimmed = val.trim();
                        if (!trimmed) return 'Masukkan nama lengkap Anda.';
                        if (trimmed.length > 255) return 'Nama maksimal 255 karakter.';
                        return '';
                    }
                },
                {
                    input: document.getElementById('date_of_birth'),
                    errorEl: document.getElementById('date_of_birth-error'),
                    errorTextEl: document.getElementById('date_of_birth-error-text'),
                    baseDescribedBy: '',
                    trimOnSubmit: false,
                    validate: function (val) {
                        if (!val) return 'Pilih tanggal lahir Anda.';
                        return '';
                    }
                },
                {
                    input: genderSelect,
                    errorEl: document.getElementById('gender-error'),
                    errorTextEl: document.getElementById('gender-error-text'),
                    baseDescribedBy: '',
                    trimOnSubmit: false,
                    validate: function (val) {
                        if (val !== 'Male' && val !== 'Female') return 'Pilih jenis kelamin Anda.';
                        return '';
                    }
                },
                {
                    input: document.getElementById('phone_number'),
                    errorEl: document.getElementById('phone_number-error'),
                    errorTextEl: document.getElementById('phone_number-error-text'),
                    baseDescribedBy: '',
                    trimOnSubmit: true,
                    validate: function (val) {
                        const trimmed = val.trim();
                        if (!trimmed) return 'Masukkan nomor telepon Anda.';
                        if (trimmed.length > 15) return 'Nomor telepon maksimal 15 karakter.';
                        return '';
                    }
                },
                {
                    input: document.getElementById('email'),
                    errorEl: document.getElementById('email-error'),
                    errorTextEl: document.getElementById('email-error-text'),
                    baseDescribedBy: '',
                    trimOnSubmit: true,
                    validate: function (val) {
                        const trimmed = val.trim();
                        if (!trimmed) return 'Masukkan alamat email Anda.';
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(trimmed)) {
                            return 'Masukkan alamat email dengan format yang benar, misalnya nama@contoh.com.';
                        }
                        if (trimmed.length > 255) return 'Alamat email maksimal 255 karakter.';
                        return '';
                    }
                },
                {
                    input: document.getElementById('password'),
                    errorEl: document.getElementById('password-error'),
                    errorTextEl: document.getElementById('password-error-text'),
                    baseDescribedBy: 'password-hint',
                    trimOnSubmit: false,
                    validate: function (val) {
                        if (!val) return 'Masukkan kata sandi Anda.';
                        if (val.length < 8) return 'Gunakan kata sandi minimal 8 karakter.';
                        return '';
                    }
                },
                {
                    input: document.getElementById('password_confirmation'),
                    errorEl: document.getElementById('password_confirmation-error'),
                    errorTextEl: document.getElementById('password_confirmation-error-text'),
                    baseDescribedBy: '',
                    trimOnSubmit: false,
                    validate: function (val) {
                        const pw = document.getElementById('password').value;
                        if (!val) return 'Masukkan konfirmasi kata sandi Anda.';
                        if (val !== pw) return 'Konfirmasi kata sandi harus sama dengan kata sandi di atas.';
                        return '';
                    }
                }
            ];

            function setFieldError(field, message) {
                if (message) {
                    field.input.setAttribute('aria-invalid', 'true');
                    const describedBy = field.baseDescribedBy
                        ? field.baseDescribedBy + ' ' + field.errorEl.id
                        : field.errorEl.id;
                    field.input.setAttribute('aria-describedby', describedBy);
                    field.errorTextEl.textContent = message;
                    field.errorEl.hidden = false;
                } else {
                    field.input.removeAttribute('aria-invalid');
                    if (field.baseDescribedBy) {
                        field.input.setAttribute('aria-describedby', field.baseDescribedBy);
                    } else {
                        field.input.removeAttribute('aria-describedby');
                    }
                    field.errorTextEl.textContent = '';
                    field.errorEl.hidden = true;
                }
            }

            // Update select placeholder styling
            function syncSelectStyle() {
                genderSelect.classList.toggle('is-placeholder', !genderSelect.value);
            }
            syncSelectStyle();
            genderSelect.addEventListener('change', syncSelectStyle);

            // Password visibility toggles
            document.querySelectorAll('[data-toggle-target]').forEach(function (btn) {
                const targetId = btn.getAttribute('data-toggle-target');
                const input = document.getElementById(targetId);
                const isConfirm = targetId === 'password_confirmation';

                btn.addEventListener('click', function () {
                    const isRevealed = btn.getAttribute('aria-pressed') === 'true';
                    const nextRevealed = !isRevealed;
                    input.type = nextRevealed ? 'text' : 'password';
                    btn.setAttribute('aria-pressed', String(nextRevealed));
                    btn.setAttribute(
                        'aria-label',
                        isConfirm
                            ? (nextRevealed ? 'Sembunyikan konfirmasi kata sandi' : 'Tampilkan konfirmasi kata sandi')
                            : (nextRevealed ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi')
                    );
                });
            });

            // Re-check invalid fields on input/change so errors clear once fixed
            fields.forEach(function (field) {
                const eventName = field.input.tagName === 'SELECT' || field.input.type === 'date' ? 'change' : 'input';
                field.input.addEventListener(eventName, function () {
                    if (field.input.getAttribute('aria-invalid') === 'true') {
                        const err = field.validate(field.input.value);
                        if (!err) {
                            setFieldError(field, '');
                        }
                    }
                    if (field.input.id === 'password') {
                        const confirmField = fields[fields.length - 1];
                        if (confirmField.input.getAttribute('aria-invalid') === 'true') {
                            const confirmErr = confirmField.validate(confirmField.input.value);
                            if (!confirmErr) {
                                setFieldError(confirmField, '');
                            }
                        }
                    }
                });
            });

            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
                    event.preventDefault();
                    return;
                }

                let firstInvalid = null;

                fields.forEach(function (field) {
                    if (field.trimOnSubmit) {
                        field.input.value = field.input.value.trim();
                    }
                    const err = field.validate(field.input.value);
                    setFieldError(field, err);
                    if (err && !firstInvalid) {
                        firstInvalid = field.input;
                    }
                });

                if (firstInvalid) {
                    event.preventDefault();
                    firstInvalid.focus();
                    return;
                }

                isSubmitting = true;
                submitBtn.setAttribute('aria-disabled', 'true');
                submitLabel.textContent = 'Mendaftarkan akun…';
            });

            window.addEventListener('pageshow', function () {
                isSubmitting = false;
                submitBtn.setAttribute('aria-disabled', 'false');
                submitLabel.textContent = 'Buat akun';
            });

            const firstServerInvalid = form.querySelector('[aria-invalid="true"]');
            if (firstServerInvalid) {
                firstServerInvalid.focus();
            }
        })();
    </script>
</body>
</html>
