<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Profil Pasien — Klinik Dentist · Self-Diagnosis TMD</title>
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

            --red-800: #991B1B;
            --red-700: #B91C1C;
            --red-600: #DC2626;
            --red-200: #FECACA;
            --red-50:  #FEF2F2;

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

            --color-error-bg:         var(--red-50);
            --color-error-border:     var(--red-600);
            --color-error-soft:       var(--red-200);
            --color-error-text:       var(--red-800);
            --color-error-inline:     var(--red-700);

            --radius-card:   1.25rem; /* 20px */
            --radius-nested: 0.75rem; /* 12px */
            --radius-btn:    0.625rem;/* 10px */

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
            max-width: 42rem;
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
            max-width: 42rem;
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
            border-color: var(--color-error-soft);
            color: var(--color-error-text);
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
            border-color: var(--color-error-border);
        }

        .field-input[aria-invalid="true"]:focus-visible,
        .field-select[aria-invalid="true"]:focus-visible {
            border-color: var(--color-error-border);
            outline: 2px solid var(--color-error-border);
            outline-offset: 1px;
        }

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

        .form-actions {
            margin-top: 0.5rem;
            display: flex;
            flex-direction: column;
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
        .btn-secondary {
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
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (min-width: 520px) {
            .btn-primary,
            .btn-secondary {
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

        .btn-primary[aria-disabled="true"] .btn-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Langsung ke formulir profil</a>

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
        @if (session('error'))
            <div class="banner banner-error" role="alert">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="banner-title">Perhatian</p>
                    <p class="banner-body">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <section class="card card-enter" aria-labelledby="create-profile-heading">
            <header class="card-header">
                <h1 id="create-profile-heading" class="page-title">Buat profil pasien</h1>
                <p class="page-subtitle">Lengkapi data diri Anda sebagai identitas rekam konsultasi sebelum memulai skrining mandiri TMD.</p>
            </header>

            <form id="create-profile-form" class="profile-form" action="{{ route('patients.store') }}" method="POST" novalidate>
                @csrf

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
                        <label for="date_of_birth" class="field-label">
                            Tanggal lahir
                            <span class="optional-tag">(opsional)</span>
                        </label>
                        <input
                            type="date"
                            name="date_of_birth"
                            id="date_of_birth"
                            class="field-input"
                            value="{{ old('date_of_birth') }}"
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
                                <option value="" @selected(old('gender') === null || old('gender') === '')>Belum dipilih</option>
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
                    <label for="phone_number" class="field-label">
                        Nomor telepon
                        <span class="optional-tag">(opsional)</span>
                    </label>
                    <input
                        type="tel"
                        name="phone_number"
                        id="phone_number"
                        class="field-input"
                        value="{{ old('phone_number') }}"
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
                    <button type="submit" id="submit-btn" class="btn-primary" aria-disabled="false">
                        <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                        <span id="submit-label">Simpan profil pasien</span>
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const form = document.getElementById('create-profile-form');
            const nameInput = document.getElementById('name');
            const nameErrorEl = document.getElementById('name-error');
            const nameErrorText = document.getElementById('name-error-text');
            const phoneInput = document.getElementById('phone_number');
            const phoneErrorEl = document.getElementById('phone_number-error');
            const phoneErrorText = document.getElementById('phone_number-error-text');
            const submitBtn = document.getElementById('submit-btn');
            const submitLabel = document.getElementById('submit-label');

            let isSubmitting = false;

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

            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
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

                isSubmitting = true;
                submitBtn.setAttribute('aria-disabled', 'true');
                submitLabel.textContent = 'Menyimpan…';
            });

            window.addEventListener('pageshow', function () {
                isSubmitting = false;
                submitBtn.setAttribute('aria-disabled', 'false');
                submitLabel.textContent = 'Simpan profil pasien';
            });

            const firstServerInvalid = form.querySelector('[aria-invalid="true"]');
            if (firstServerInvalid) {
                firstServerInvalid.focus();
            }
        })();
    </script>
</body>
</html>
