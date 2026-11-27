<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil &amp; Akun Pasien — Klinik Dentist · Self-Diagnosis TMD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* =====================================================================
           Design Tokens — Primitives & Semantic Roles
           ===================================================================== */
        :root {
            --teal-900: #0B3B3C;
            --teal-700: #0A5858;
            --teal-600: #0D6E6E;
            --teal-50:  #E9F4F4;

            --slate-900: #0F172A;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748B;
            --slate-400: #78869B;
            --slate-200: #E2E8F0;
            --slate-100: #F4F7F8;
            --white:     #FFFFFF;

            --red-900: #7F1D1D;
            --red-800: #991B1B;
            --red-700: #B91C1C;
            --red-600: #DC2626;
            --red-200: #FECACA;
            --red-50:  #FEF2F2;

            --emerald-800: #065F46;
            --emerald-200: #A7F3D0;
            --emerald-50:  #ECFDF5;

            --color-bg-page:          var(--slate-100);
            --color-bg-surface:       var(--white);
            --color-bg-subtle:        var(--slate-100);
            --color-bg-brand:         var(--teal-900);

            --color-text-primary:     var(--slate-900);
            --color-text-secondary:   var(--slate-600);
            --color-text-placeholder: var(--slate-500);
            --color-text-on-brand:    var(--white);

            --color-accent:           var(--teal-600);
            --color-accent-hover:     var(--teal-700);
            --color-accent-active:    var(--teal-900);
            --color-accent-ink:       var(--white);
            --color-focus:            var(--teal-600);

            --color-border-control:   var(--slate-400);
            --color-border-hover:     var(--slate-600);
            --color-border-divider:   var(--slate-200);

            --color-danger:           var(--red-700);
            --color-danger-hover:     var(--red-800);
            --color-danger-active:    var(--red-900);
            --color-danger-bg:        var(--red-50);
            --color-danger-border:    var(--red-600);
            --color-danger-soft:      var(--red-200);
            --color-danger-text:      var(--red-800);

            --color-success-bg:       var(--emerald-50);
            --color-success-border:   var(--emerald-200);
            --color-success-text:     var(--emerald-800);

            --radius-card:   1.25rem; /* 20px */
            --radius-nested: 0.75rem; /* 12px */
            --radius-btn:    0.625rem;/* 10px */
            --radius-inner:  0.375rem;/* 6px = 10px input - 4px inset */

            --shadow-card:
                0px 0px 0px 1px rgba(0, 0, 0, 0.06),
                0px 1px 2px -1px rgba(0, 0, 0, 0.06),
                0px 8px 24px -6px rgba(0, 0, 0, 0.06);

            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --ease-out-smooth: cubic-bezier(0.2, 0, 0, 1);
        }

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

        /* Sticky Top Header */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background-color: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--color-border-divider);
        }

        .topbar-inner {
            max-width: 44rem;
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
        }

        .btn-back {
            min-height: 2.75rem;
            padding-block: 0.5rem;
            padding-inline-start: 0.625rem;
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
            flex-shrink: 0;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .btn-back:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-border-hover);
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

        /* Main */
        .page-main {
            flex: 1;
            width: 100%;
            max-width: 44rem;
            margin-inline: auto;
            padding-block: 1.25rem 3rem;
            padding-inline: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .page-main {
                padding-block: 2rem 3.5rem;
                padding-inline: 1.5rem;
                gap: 1.75rem;
            }
        }

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
            background-color: var(--color-danger-bg);
            border-color: var(--color-danger-soft);
            color: var(--color-danger-text);
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

        .card {
            background-color: var(--color-bg-surface);
            border-radius: var(--radius-card);
            border: 1px solid transparent;
            box-shadow: var(--shadow-card);
            padding: 1.5rem;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .card {
                padding: 2rem;
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

        .card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1.125rem;
            border-bottom: 1px solid var(--color-border-divider);
        }

        .page-title {
            margin: 0;
            font-size: clamp(1.375rem, 2.5vw, 1.625rem);
            font-weight: 600;
            line-height: 1.25;
            letter-spacing: -0.015em;
            color: var(--color-text-primary);
            text-wrap: balance;
        }

        .page-subtitle {
            margin: 0.375rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        /* Account email summary pill inside profile card */
        .account-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
        }

        .account-meta-label {
            color: var(--color-text-secondary);
        }

        .account-meta-val {
            font-weight: 600;
            color: var(--color-text-primary);
            overflow-wrap: anywhere;
        }

        /* Form */
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .field-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
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
            gap: 0.375rem;
            min-width: 0;
        }

        .field-label {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
            color: var(--color-text-primary);
            align-self: flex-start;
        }

        .optional-tag {
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--color-text-secondary);
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-input,
        .field-select {
            width: 100%;
            min-height: 3rem;
            padding-block: 0.6875rem;
            padding-inline: 0.875rem;
            font-family: inherit;
            font-size: 1rem; /* 16px prevents iOS zoom */
            line-height: 1.5;
            color: var(--color-text-primary);
            background-color: var(--color-bg-surface);
            border: 1px solid var(--color-border-control);
            border-radius: var(--radius-btn);
            appearance: none;
            transition-property: border-color, box-shadow;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        .field-input--password {
            padding-inline-end: 3.125rem;
        }

        .field-select {
            padding-inline-end: 2.75rem;
            cursor: pointer;
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
                border-color: var(--color-border-hover);
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
            border-color: var(--color-danger-border);
        }

        .field-input[aria-invalid="true"]:focus-visible,
        .field-select[aria-invalid="true"]:focus-visible {
            border-color: var(--color-danger-border);
            outline: 2px solid var(--color-danger-border);
            outline-offset: 1px;
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            inset-inline-end: 0.25rem;
            width: 2.5rem;
            height: 2.5rem;
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
                background-color: var(--color-bg-subtle);
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

        .field-error {
            display: flex;
            align-items: flex-start;
            gap: 0.375rem;
            margin: 0.125rem 0 0;
            font-size: 0.8125rem;
            line-height: 1.4;
            color: var(--color-danger);
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

        .form-actions {
            margin-top: 0.5rem;
            display: flex;
            flex-direction: column-reverse;
            gap: 0.75rem;
        }

        @media (min-width: 520px) {
            .form-actions {
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
            }
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger-outline,
        .btn-danger-solid {
            width: 100%;
            min-height: 3rem;
            padding-block: 0.75rem;
            padding-inline: 1.25rem;
            border-radius: var(--radius-btn);
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
            transition-property: background-color, border-color, color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (min-width: 520px) {
            .btn-primary,
            .btn-secondary,
            .btn-danger-outline,
            .btn-danger-solid {
                width: auto;
            }
        }

        .btn-primary {
            border: 1px solid transparent;
            background-color: var(--color-accent);
            color: var(--color-accent-ink);
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

        .btn-secondary {
            border: 1px solid var(--color-border-control);
            background-color: var(--color-bg-surface);
            color: var(--color-text-primary);
        }

        @media (hover: hover) {
            .btn-secondary:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-border-hover);
            }
        }

        .btn-secondary:active {
            transform: scale(0.96);
        }

        .btn-secondary:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-spinner {
            width: 1.125rem;
            height: 1.125rem;
            display: none;
            animation: spin 750ms linear infinite;
        }

        [aria-disabled="true"] .btn-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* =====================================================================
           Danger Zone Card
           ===================================================================== */
        .danger-card {
            border-color: var(--color-danger-soft);
        }

        .danger-header {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.125rem;
        }

        .danger-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5625rem;
            background-color: var(--color-danger-bg);
            color: var(--color-danger);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .danger-icon svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .danger-title {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.3;
            color: var(--color-danger-text);
        }

        .danger-desc {
            margin: 0.375rem 0 0;
            font-size: 0.875rem;
            line-height: 1.55;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        .btn-danger-outline {
            border: 1px solid var(--color-danger-border);
            background-color: var(--color-danger-bg);
            color: var(--color-danger-text);
        }

        @media (hover: hover) {
            .btn-danger-outline:hover:not([aria-disabled="true"]) {
                background-color: var(--color-danger);
                border-color: var(--color-danger);
                color: var(--white);
            }
        }

        .btn-danger-outline:not([aria-disabled="true"]):active {
            transform: scale(0.96);
        }

        .btn-danger-outline:focus-visible {
            outline: 2px solid var(--color-danger-border);
            outline-offset: 2px;
        }

        .btn-danger-solid {
            border: 1px solid transparent;
            background-color: var(--color-danger);
            color: var(--white);
        }

        @media (hover: hover) {
            .btn-danger-solid:hover:not([aria-disabled="true"]) {
                background-color: var(--color-danger-hover);
            }
        }

        .btn-danger-solid:not([aria-disabled="true"]):active {
            background-color: var(--color-danger-active);
            transform: scale(0.96);
        }

        .btn-danger-solid:focus-visible {
            outline: 2px solid var(--color-danger-border);
            outline-offset: 2px;
        }

        .btn-danger-solid[aria-disabled="true"] {
            opacity: 0.82;
            cursor: wait;
        }

        /* =====================================================================
           Confirmation Dialog (<dialog>)
           ===================================================================== */
        .confirm-dialog {
            width: min(100% - 2rem, 27rem);
            margin: auto;
            padding: 1.5rem;
            border: 1px solid transparent;
            border-radius: var(--radius-card);
            background-color: var(--color-bg-surface);
            color: var(--color-text-primary);
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.25);
            overscroll-behavior: contain;
        }

        .confirm-dialog::backdrop {
            background-color: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
        }

        .dialog-title {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.3;
            color: var(--color-text-primary);
            text-wrap: balance;
        }

        .dialog-body {
            margin: 0.625rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        .dialog-actions {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column-reverse;
            gap: 0.625rem;
        }

        @media (min-width: 480px) {
            .dialog-actions {
                flex-direction: row;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Langsung ke pengaturan profil</a>

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

            <a href="{{ route('dashboard') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                <span>Dashboard</span>
            </a>
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

        {{-- Kartu Edit Profil Pasien --}}
        <section class="card card-enter" aria-labelledby="edit-profile-heading">
            <header class="card-header">
                <h1 id="edit-profile-heading" class="page-title">Profil pasien</h1>
                <p class="page-subtitle">Perbarui informasi identitas pasien yang digunakan pada seluruh riwayat konsultasi TMD Anda.</p>
            </header>

            @if (auth()->user()?->email)
                <div class="account-meta">
                    <span class="account-meta-label">Email akun</span>
                    <span class="account-meta-val">{{ auth()->user()->email }}</span>
                </div>
            @endif

            <form id="edit-profile-form" class="profile-form" action="{{ route('patients.update', $patient->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="field-group">
                    <label for="name" class="field-label">Nama lengkap</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="field-input"
                        value="{{ old('name', $patient->name) }}"
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
                        <label for="date_of_birth" class="field-label">
                            Tanggal lahir
                            <span class="optional-tag">(opsional)</span>
                        </label>
                        <input
                            type="date"
                            name="date_of_birth"
                            id="date_of_birth"
                            class="field-input"
                            value="{{ old('date_of_birth', $patient->date_of_birth) }}"
                            autocomplete="bday"
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
                        <label for="gender" class="field-label">
                            Jenis kelamin
                            <span class="optional-tag">(opsional)</span>
                        </label>
                        <div class="input-wrap">
                            <select
                                name="gender"
                                id="gender"
                                class="field-select"
                                autocomplete="sex"
                                @if ($errors->has('gender')) aria-invalid="true" aria-describedby="gender-error" @endif
                            >
                                <option value="" @selected(old('gender', $patient->gender) === null || old('gender', $patient->gender) === '')>Belum dipilih</option>
                                <option value="Male" @selected(old('gender', $patient->gender) === 'Male')>Laki-laki</option>
                                <option value="Female" @selected(old('gender', $patient->gender) === 'Female')>Perempuan</option>
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
                    <label for="phone_number" class="field-label">
                        Nomor telepon
                        <span class="optional-tag">(opsional)</span>
                    </label>
                    <input
                        type="tel"
                        name="phone_number"
                        id="phone_number"
                        class="field-input"
                        value="{{ old('phone_number', $patient->phone_number) }}"
                        placeholder="081234567890"
                        autocomplete="tel"
                        inputmode="tel"
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

                <div class="form-actions">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
                    <button type="submit" id="save-btn" class="btn-primary" aria-disabled="false">
                        <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                        <span id="save-label">Simpan perubahan</span>
                    </button>
                </div>
            </form>
        </section>

        {{-- Zona Bahaya: Hapus Akun --}}
        <section class="card card-enter danger-card" aria-labelledby="delete-account-heading">
            <div class="danger-header">
                <span class="danger-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </span>
                <div>
                    <h2 id="delete-account-heading" class="danger-title">Hapus Akun</h2>
                    <p class="danger-desc">
                        Menghapus akun akan menghapus secara permanen profil pasien, seluruh riwayat diagnosis, jawaban kuesioner, dan foto pemeriksaan Anda. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <form id="delete-account-form" class="profile-form" action="{{ route('account.destroy') }}" method="POST" novalidate>
                @csrf
                @method('DELETE')

                <div class="field-group">
                    <label for="delete_password" class="field-label">Konfirmasi kata sandi</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            name="password"
                            id="delete_password"
                            class="field-input field-input--password"
                            autocomplete="current-password"
                            spellcheck="false"
                            required
                            @if ($errors->has('password')) aria-invalid="true" aria-describedby="delete_password-error" @endif
                        >
                        <button
                            type="button"
                            id="toggle-delete-password"
                            class="password-toggle"
                            aria-label="Tampilkan kata sandi"
                            aria-pressed="false"
                            aria-controls="delete_password"
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
                    <p id="delete_password-error" class="field-error" @if (!$errors->has('password')) hidden @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span id="delete_password-error-text">{{ $errors->first('password') }}</span>
                    </p>
                </div>

                <div class="form-actions">
                    <button type="button" id="open-delete-dialog" class="btn-danger-outline">
                        Hapus Akun Saya
                    </button>
                </div>
            </form>
        </section>
    </main>

    {{-- Dialog Konfirmasi Penghapusan Akun --}}
    <dialog id="delete-confirm-dialog" class="confirm-dialog" aria-labelledby="dialog-title" aria-describedby="dialog-desc">
        <h3 id="dialog-title" class="dialog-title">Hapus akun secara permanen?</h3>
        <p id="dialog-desc" class="dialog-body">
            Profil pasien, seluruh riwayat konsultasi, jawaban kuesioner, dan foto pemeriksaan Anda akan dihapus permanen dan tidak dapat dipulihkan.
        </p>
        <div class="dialog-actions">
            <button type="button" id="cancel-delete-btn" class="btn-secondary">Batal</button>
            <button type="button" id="confirm-delete-btn" class="btn-danger-solid" aria-disabled="false">
                <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                <span id="confirm-delete-label">Hapus akun</span>
            </button>
        </div>
    </dialog>

    <script>
        (function () {
            const editForm = document.getElementById('edit-profile-form');
            const nameInput = document.getElementById('name');
            const nameErrorEl = document.getElementById('name-error');
            const nameErrorText = document.getElementById('name-error-text');
            const phoneInput = document.getElementById('phone_number');
            const phoneErrorEl = document.getElementById('phone_number-error');
            const phoneErrorText = document.getElementById('phone_number-error-text');
            const saveBtn = document.getElementById('save-btn');
            const saveLabel = document.getElementById('save-label');

            const deleteForm = document.getElementById('delete-account-form');
            const deletePasswordInput = document.getElementById('delete_password');
            const deletePasswordErrorEl = document.getElementById('delete_password-error');
            const deletePasswordErrorText = document.getElementById('delete_password-error-text');
            const toggleDeletePasswordBtn = document.getElementById('toggle-delete-password');
            const openDeleteDialogBtn = document.getElementById('open-delete-dialog');
            const deleteDialog = document.getElementById('delete-confirm-dialog');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
            const confirmDeleteLabel = document.getElementById('confirm-delete-label');

            let isSaving = false;
            let isDeleting = false;

            function setFieldError(input, errorEl, errorTextEl, message) {
                if (message) {
                    input.setAttribute('aria-invalid', 'true');
                    input.setAttribute('aria-describedby', errorEl.id);
                    errorTextEl.textContent = message;
                    errorEl.hidden = false;
                } else {
                    input.removeAttribute('aria-invalid');
                    input.removeAttribute('aria-describedby');
                    errorTextEl.textContent = '';
                    errorEl.hidden = true;
                }
            }

            function validateName(val) {
                const trimmed = val.trim();
                if (!trimmed) return 'Masukkan nama lengkap Anda.';
                if (trimmed.length > 255) return 'Nama maksimal 255 karakter.';
                return '';
            }

            function validatePhone(val) {
                const trimmed = val.trim();
                if (trimmed.length > 15) return 'Nomor telepon maksimal 15 karakter.';
                return '';
            }

            nameInput.addEventListener('input', function () {
                if (nameInput.getAttribute('aria-invalid') === 'true' && !validateName(nameInput.value)) {
                    setFieldError(nameInput, nameErrorEl, nameErrorText, '');
                }
            });

            phoneInput.addEventListener('input', function () {
                if (phoneInput.getAttribute('aria-invalid') === 'true' && !validatePhone(phoneInput.value)) {
                    setFieldError(phoneInput, phoneErrorEl, phoneErrorText, '');
                }
            });

            editForm.addEventListener('submit', function (event) {
                if (isSaving) {
                    event.preventDefault();
                    return;
                }

                nameInput.value = nameInput.value.trim();
                phoneInput.value = phoneInput.value.trim();

                const nameErr = validateName(nameInput.value);
                const phoneErr = validatePhone(phoneInput.value);

                setFieldError(nameInput, nameErrorEl, nameErrorText, nameErr);
                setFieldError(phoneInput, phoneErrorEl, phoneErrorText, phoneErr);

                if (nameErr || phoneErr) {
                    event.preventDefault();
                    (nameErr ? nameInput : phoneInput).focus();
                    return;
                }

                isSaving = true;
                saveBtn.setAttribute('aria-disabled', 'true');
                saveLabel.textContent = 'Menyimpan…';
            });

            // Toggle password visibility for account deletion
            toggleDeletePasswordBtn.addEventListener('click', function () {
                const isRevealed = toggleDeletePasswordBtn.getAttribute('aria-pressed') === 'true';
                const nextRevealed = !isRevealed;
                deletePasswordInput.type = nextRevealed ? 'text' : 'password';
                toggleDeletePasswordBtn.setAttribute('aria-pressed', String(nextRevealed));
                toggleDeletePasswordBtn.setAttribute(
                    'aria-label',
                    nextRevealed ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
                );
            });

            deletePasswordInput.addEventListener('input', function () {
                if (deletePasswordInput.getAttribute('aria-invalid') === 'true' && deletePasswordInput.value) {
                    setFieldError(deletePasswordInput, deletePasswordErrorEl, deletePasswordErrorText, '');
                }
            });

            function requestDeleteConfirmation() {
                if (!deletePasswordInput.value) {
                    setFieldError(
                        deletePasswordInput,
                        deletePasswordErrorEl,
                        deletePasswordErrorText,
                        'Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun.'
                    );
                    deletePasswordInput.focus();
                    return;
                }

                if (typeof deleteDialog.showModal === 'function') {
                    deleteDialog.showModal();
                    cancelDeleteBtn.focus();
                } else if (window.confirm('Hapus akun Anda secara permanen? Tindakan ini tidak dapat dibatalkan.')) {
                    submitDeleteAccount();
                }
            }

            function submitDeleteAccount() {
                if (isDeleting) return;
                isDeleting = true;
                confirmDeleteBtn.setAttribute('aria-disabled', 'true');
                confirmDeleteLabel.textContent = 'Menghapus…';
                deleteForm.submit();
            }

            openDeleteDialogBtn.addEventListener('click', requestDeleteConfirmation);

            deleteForm.addEventListener('submit', function (event) {
                event.preventDefault();
                requestDeleteConfirmation();
            });

            cancelDeleteBtn.addEventListener('click', function () {
                deleteDialog.close();
            });

            deleteDialog.addEventListener('close', function () {
                if (!isDeleting) {
                    openDeleteDialogBtn.focus();
                }
            });

            confirmDeleteBtn.addEventListener('click', submitDeleteAccount);

            window.addEventListener('pageshow', function () {
                isSaving = false;
                isDeleting = false;
                saveBtn.setAttribute('aria-disabled', 'false');
                saveLabel.textContent = 'Simpan perubahan';
                confirmDeleteBtn.setAttribute('aria-disabled', 'false');
                confirmDeleteLabel.textContent = 'Hapus akun';
            });

            const firstServerInvalid = document.querySelector('[aria-invalid="true"]');
            if (firstServerInvalid) {
                firstServerInvalid.focus();
            }
        })();
    </script>
</body>
</html>
