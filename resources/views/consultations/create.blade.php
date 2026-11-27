<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#FFFFFF">
    <title>Self-Diagnosis TMD — Klinik Dentist</title>
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
            --red-700: #B91C1C;
            --red-600: #DC2626;
            --red-200: #FECACA;
            --red-50:  #FEF2F2;

            --emerald-800: #065F46;
            --emerald-200: #A7F3D0;
            --emerald-50:  #ECFDF5;

            /* Semantic Tokens */
            --color-bg-page:          var(--slate-100);
            --color-bg-surface:       var(--white);
            --color-bg-subtle:        var(--slate-100);
            --color-bg-brand:         var(--teal-900);

            --color-text-primary:     var(--slate-900);
            --color-text-secondary:   var(--slate-600);
            --color-text-strong:      var(--slate-700);
            --color-text-placeholder: var(--slate-500);
            --color-text-on-brand:    var(--white);

            --color-accent:           var(--teal-600);
            --color-accent-hover:     var(--teal-700);
            --color-accent-active:    var(--teal-900);
            --color-accent-ink:       var(--white);
            --color-accent-soft:      var(--teal-50);
            --color-accent-soft-ink:  var(--teal-700);
            --color-focus:            var(--teal-600);

            --color-border-control:   var(--slate-400);
            --color-border-hover:     var(--slate-600);
            --color-border-divider:   var(--slate-200);

            --color-error-bg:         var(--red-50);
            --color-error-border:     var(--red-600);
            --color-error-soft:       var(--red-200);
            --color-error-text:       var(--red-800);
            --color-error-inline:     var(--red-700);

            --color-success-bg:       var(--emerald-50);
            --color-success-border:   var(--emerald-200);
            --color-success-text:     var(--emerald-800);

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
            -webkit-tap-highlight-color: transparent;
            text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            scroll-padding-block-start: 5.5rem;
        }

        button, a {
            touch-action: manipulation;
        }

        button {
            user-select: none;
            -webkit-user-select: none;
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
            z-index: 60;
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
           Sticky Top Header + Progress Bar
           ===================================================================== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            padding-top: env(safe-area-inset-top, 0px);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--color-border-divider);
        }

        .topbar-inner {
            max-width: 52rem;
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

        .btn-back {
            min-height: 2.75rem; /* 44px touch target */
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

        .step-counter {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-strong);
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .progress-track {
            width: 100%;
            height: 3px;
            background-color: var(--color-border-divider);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 100%;
            background-color: var(--color-accent);
            transform-origin: left center;
            transform: scaleX(0.2);
            transition-property: transform;
            transition-duration: 240ms;
            transition-timing-function: var(--ease-out-smooth);
        }

        @media (prefers-reduced-motion: reduce) {
            .progress-fill {
                transition-duration: 0ms;
            }
        }

        /* =====================================================================
           Main Workspace
           ===================================================================== */
        .page-main {
            flex: 1;
            width: 100%;
            max-width: 52rem;
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
                padding-block: 1.75rem 3rem;
                padding-inline: 1.5rem;
                gap: 1.5rem;
            }
        }

        .page-intro {
            min-width: 0;
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

        .page-subtitle {
            margin: 0.375rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            max-width: 60ch;
            text-wrap: pretty;
        }

        /* =====================================================================
           Step Navigation Strip (Horizontal Scroll on Narrow Mobile)
           ===================================================================== */
        .step-nav {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-block: 0.25rem;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .step-nav::-webkit-scrollbar {
            display: none;
        }

        .step-pill {
            min-height: 2.75rem; /* 44px hit target */
            padding-block: 0.5rem;
            padding-inline: 0.875rem;
            border-radius: var(--radius-pill);
            border: 1px solid var(--color-border-divider);
            background-color: var(--color-bg-surface);
            color: var(--color-text-secondary);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1.3;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4375rem;
            cursor: pointer;
            flex-shrink: 0;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .step-pill:hover {
                border-color: var(--color-border-control);
                color: var(--color-text-primary);
            }
        }

        .step-pill:active {
            transform: scale(0.96);
        }

        .step-pill:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .step-pill[aria-current="step"] {
            background-color: var(--color-bg-brand);
            border-color: var(--color-bg-brand);
            color: var(--color-text-on-brand);
        }

        .step-pill.is-done:not([aria-current="step"]) {
            background-color: var(--color-accent-soft);
            border-color: var(--teal-100);
            color: var(--color-accent-soft-ink);
        }

        .step-pill-num {
            font-variant-numeric: tabular-nums;
            opacity: 0.85;
        }

        /* =====================================================================
           Alert Banners
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
            border-color: var(--color-error-soft);
            color: var(--color-error-text);
        }

        .banner-title {
            margin: 0;
            font-weight: 600;
        }

        .banner-list {
            margin: 0.25rem 0 0;
            padding-inline-start: 1.125rem;
        }

        /* =====================================================================
           Main Step Card & Form Controls
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
                padding: 2rem;
            }
        }

        .step-panel[hidden] {
            display: none;
        }

        @media (prefers-reduced-motion: no-preference) {
            .step-panel:not([hidden]) {
                animation: panel-fade 200ms var(--ease-out-smooth) both;
            }
        }

        @keyframes panel-fade {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-header {
            padding-bottom: 1.125rem;
            margin-bottom: 1.375rem;
            border-bottom: 1px solid var(--color-border-divider);
        }

        .step-kicker {
            margin: 0 0 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--color-accent-soft-ink);
        }

        .step-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.3;
            color: var(--color-text-primary);
            text-wrap: balance;
        }

        .step-title:focus {
            outline: none;
        }

        .step-desc {
            margin: 0.375rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        .question-stack {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sub-divider {
            margin: 0.25rem 0;
            padding-top: 1.25rem;
            border-top: 1px solid var(--color-border-divider);
        }

        .sub-heading {
            margin: 0 0 0.25rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .sub-desc {
            margin: 0 0 1rem;
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        /* Question Fieldset */
        .q-fieldset {
            border: 0;
            margin: 0;
            padding: 0;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .q-legend {
            padding: 0;
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.45;
            color: var(--color-text-primary);
            margin-bottom: 0.25rem;
            text-wrap: pretty;
        }

        .q-code {
            display: inline-block;
            margin-inline-end: 0.375rem;
            padding: 0.125rem 0.4375rem;
            border-radius: 0.375rem;
            background-color: var(--color-bg-subtle);
            color: var(--color-text-strong);
            font-size: 0.75rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            vertical-align: 1px;
        }

        /* Touch-friendly Radio Option Cards */
        .radio-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.625rem;
        }

        @media (min-width: 520px) {
            .radio-grid--two {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            }
        }

        .radio-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 3rem; /* 48px touch target */
            padding: 0.75rem 1rem;
            border-radius: var(--radius-btn);
            border: 1px solid var(--color-border-control);
            background-color: var(--color-bg-surface);
            color: var(--color-text-primary);
            font-size: 0.9375rem;
            font-weight: 500;
            line-height: 1.4;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .radio-card:hover {
                border-color: var(--color-border-hover);
                background-color: var(--color-bg-subtle);
            }
        }

        .radio-card:has(input:checked) {
            background-color: var(--color-accent-soft);
            border-color: var(--color-accent);
            color: var(--color-text-primary);
        }

        .radio-card:has(input:focus-visible) {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .radio-input {
            width: 1.125rem;
            height: 1.125rem;
            margin: 0;
            accent-color: var(--color-accent);
            flex-shrink: 0;
            cursor: pointer;
        }

        /* E3 Option Cards with Audio */
        .e3-stack {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .e3-card {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: var(--radius-nested);
            border: 1px solid var(--color-border-control);
            background-color: var(--color-bg-surface);
            transition-property: background-color, border-color;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        .e3-card:has(input:checked) {
            background-color: var(--color-accent-soft);
            border-color: var(--color-accent);
        }

        .e3-card:has(input:focus-visible) {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .e3-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 2.25rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-text-primary);
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        .e3-audio-wrap {
            padding-inline-start: 1.875rem;
        }

        .e3-audio-caption {
            margin: 0 0 0.375rem;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }

        .e3-audio {
            width: 100%;
            max-width: 24rem;
            height: 2.5rem;
            display: block;
        }

        /* Select & Text Inputs (16px on all viewports for iOS) */
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-input,
        .field-select {
            width: 100%;
            min-height: 3rem; /* 48px */
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

        .field-input--unit {
            padding-inline-end: 3.25rem;
            font-variant-numeric: tabular-nums;
        }

        .input-unit {
            position: absolute;
            inset-inline-end: 0.875rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-secondary);
            pointer-events: none;
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

        .field-input[aria-invalid="true"] {
            border-color: var(--color-error-border);
        }

        .field-input[aria-invalid="true"]:focus-visible {
            border-color: var(--color-error-border);
            outline: 2px solid var(--color-error-border);
            outline-offset: 1px;
        }

        .field-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-primary);
            margin-bottom: 0.375rem;
        }

        .field-hint {
            margin: 0.375rem 0 0;
            font-size: 0.8125rem;
            line-height: 1.45;
            color: var(--color-text-secondary);
        }

        .field-error {
            display: flex;
            align-items: flex-start;
            gap: 0.375rem;
            margin: 0.375rem 0 0;
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

        /* 3-Area Grid for E1 & E4 */
        .area-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.875rem;
        }

        @media (min-width: 640px) {
            .area-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.875rem;
            }
        }

        /* Reference Image (E1) */
        .ref-figure {
            margin: 0 0 1.25rem;
            padding: 0.75rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.625rem;
        }

        .ref-img {
            width: 100%;
            max-width: 24rem;
            height: auto;
            border-radius: 0.5rem;
            display: block;
            outline: 1px solid rgba(0, 0, 0, 0.1);
            outline-offset: -1px;
        }

        .ref-caption {
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            text-align: center;
            text-wrap: pretty;
        }

        /* =====================================================================
           Camera, Upload & Interactive Opening Estimator (E2)
           ===================================================================== */
        .photo-box {
            padding: 1rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            border: 1px solid var(--color-border-divider);
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .photo-box-header {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .photo-box-title {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-text-primary);
            min-width: 0;
        }

        .optional-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.1875rem 0.5625rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .photo-viewport {
            position: relative;
            width: 100%;
            max-width: 24rem;
            margin-inline: auto;
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: #0F172A;
            display: none;
        }

        .photo-viewport.is-visible {
            display: block;
        }

        /* Explicit aspect ratio so iOS Safari fills 100% edge-to-edge without black letterbox bars */
        .photo-viewport--camera {
            aspect-ratio: 3 / 4;
            max-width: 100%;
        }

        @media (min-width: 640px) {
            .photo-viewport--camera {
                aspect-ratio: 4 / 3;
                max-width: 26rem;
            }
        }

        .photo-video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Live camera face framing guide */
        .camera-guide {
            position: absolute;
            inset: 0;
            pointer-events: none;
            display: grid;
            grid-template-rows: 1fr auto;
            align-items: center;
            justify-items: center;
            padding: 1rem 0.875rem 0.875rem;
            gap: 0.75rem;
        }

        .camera-guide-oval {
            width: 60%;
            height: 76%;
            max-width: 13.5rem;
            border: 2px dashed rgba(255, 255, 255, 0.82);
            border-radius: 9999px;
            box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.3);
        }

        .camera-guide-tip {
            position: relative;
            z-index: 1;
            padding: 0.3125rem 0.75rem;
            border-radius: var(--radius-pill);
            background-color: rgba(15, 23, 42, 0.8);
            color: #FFFFFF;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1.35;
            text-align: center;
            max-width: 92%;
        }

        /* Interactive measurement stage (1:1 aspect ratio, zero cropping) */
        .measure-stage {
            position: relative;
            width: 100%;
            line-height: 0;
        }

        .photo-preview {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 0.625rem;
            outline: 1px solid rgba(0, 0, 0, 0.1);
            outline-offset: -1px;
            user-select: none;
            -webkit-user-drag: none;
        }

        .measure-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .measure-overlay[hidden] {
            display: none;
        }

        .measure-band {
            position: absolute;
            inset-inline: 0;
            top: 0;
            height: 0;
            background-color: rgba(13, 110, 110, 0.2);
            pointer-events: none;
        }

        .measure-axis {
            position: absolute;
            top: 0;
            width: 2px;
            height: 0;
            margin-inline-start: -1px;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.55);
            pointer-events: none;
        }

        .measure-readout {
            position: absolute;
            top: 0;
            transform: translate(-50%, -50%);
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-pill);
            background-color: #0B3B3C;
            color: #FFFFFF;
            border: 1.5px solid #FFFFFF;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1.25;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
            pointer-events: none;
        }

        /* Draggable horizontal calipers (44px touch target, 1:1 pointer tracking) */
        .measure-handle {
            position: absolute;
            inset-inline: 0;
            top: 0;
            height: 44px;
            margin-top: -22px;
            padding: 0;
            border: 0;
            background: transparent;
            cursor: ns-resize;
            pointer-events: auto;
            touch-action: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            display: flex;
            align-items: center;
        }

        .measure-handle::before {
            content: '';
            position: absolute;
            inset-inline: 0;
            top: 50%;
            height: 2px;
            margin-top: -1px;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 1px rgba(11, 59, 60, 0.85);
        }

        .measure-handle:focus-visible {
            outline: none;
        }

        .measure-handle:focus-visible .measure-pill {
            outline: 2px solid #FFFFFF;
            outline-offset: 2px;
            box-shadow: 0 0 0 4px var(--color-focus);
        }

        .measure-handle--top {
            justify-content: flex-start;
            padding-inline-start: 0.5rem;
        }

        .measure-handle--bottom {
            justify-content: flex-end;
            padding-inline-end: 0.5rem;
        }

        .measure-pill {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5625rem;
            border-radius: var(--radius-pill);
            background-color: #0D6E6E;
            color: #FFFFFF;
            border: 1.5px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
            font-family: inherit;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
        }

        .measure-handle.is-dragging .measure-pill {
            background-color: #07292A;
        }

        .measure-pill svg {
            width: 0.875rem;
            height: 0.875rem;
            flex-shrink: 0;
        }

        /* Estimation result bar inside photo box */
        .estimate-panel {
            padding: 0.75rem 0.875rem;
            border-radius: 0.625rem;
            background-color: var(--color-bg-surface);
            border: 1px solid var(--color-border-divider);
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .estimate-panel[hidden] {
            display: none;
        }

        .estimate-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .estimate-value-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .estimate-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            font-size: 0.8125rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .estimate-help {
            margin: 0;
            font-size: 0.8125rem;
            line-height: 1.45;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        .estimate-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .btn-xs {
            min-height: 2.5rem;
            padding-block: 0.375rem;
            padding-inline: 0.75rem;
            font-size: 0.8125rem;
        }

        .opening-source-bar {
            margin-top: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            font-size: 0.8125rem;
            line-height: 1.4;
        }

        .opening-source-bar[hidden] {
            display: none;
        }

        .opening-source-btn {
            border: 0;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            background-color: var(--color-bg-surface);
            color: var(--color-accent-soft-ink);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            text-underline-offset: 0.15em;
            -webkit-tap-highlight-color: transparent;
        }

        .opening-source-btn[hidden] {
            display: none;
        }

        .opening-source-btn:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .photo-actions {
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .photo-actions-secondary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .photo-actions-secondary #clear-photo {
            grid-column: 1 / -1;
        }

        @media (min-width: 520px) {
            .photo-actions {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
            }
            .photo-actions-secondary {
                display: flex;
                flex-wrap: wrap;
                gap: 0.625rem;
            }
        }

        .file-input-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .photo-status {
            margin: 0;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .inline-spinner {
            width: 0.9375rem;
            height: 0.9375rem;
            flex-shrink: 0;
            color: var(--color-accent);
            animation: spin 750ms linear infinite;
        }

        .inline-spinner[hidden] {
            display: none;
        }

        /* =====================================================================
           Review Summary Card (Step 5)
           ===================================================================== */
        .summary-panel {
            margin-top: 0.5rem;
            padding: 1rem 1.125rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .summary-title {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .summary-dl {
            margin: 0;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.625rem;
        }

        @media (min-width: 520px) {
            .summary-dl {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .summary-dt {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--color-text-secondary);
        }

        .summary-dd {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-primary);
            font-variant-numeric: tabular-nums;
        }

        /* =====================================================================
           Buttons & Step Footer
           ===================================================================== */
        .step-footer {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--color-border-divider);
            display: flex;
            flex-direction: column-reverse;
            gap: 0.75rem;
        }

        @media (min-width: 520px) {
            .step-footer {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            min-height: 3rem; /* 48px touch target */
            padding-block: 0.75rem;
            padding-inline: 1.25rem;
            border-radius: var(--radius-btn);
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.4;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
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

        .btn-secondary[hidden],
        .btn-primary[hidden] {
            display: none;
        }

        .btn-sm {
            min-height: 2.75rem; /* still >= 44px */
            padding-block: 0.5625rem;
            padding-inline: 1rem;
            font-size: 0.875rem;
        }

        .btn-primary svg,
        .btn-secondary svg {
            width: 1.0625rem;
            height: 1.0625rem;
            flex-shrink: 0;
        }

        .btn-spinner {
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

        .page-footer {
            max-width: 52rem;
            width: 100%;
            margin-inline: auto;
            padding-block: 0 calc(2.5rem + env(safe-area-inset-bottom, 0px));
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
    <a href="#main-content" class="skip-link">Langsung ke formulir diagnosis</a>

    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('dashboard') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <span id="step-counter" class="step-counter" aria-live="polite">Langkah 1 dari 5</span>
        </div>
        <div class="progress-track" aria-hidden="true">
            <div id="progress-fill" class="progress-fill"></div>
        </div>
    </header>

    <main id="main-content" class="page-main">
        <header class="page-intro">
            <h1 class="page-title">Formulir self-diagnosis TMD</h1>
            <p class="page-subtitle">
                Jawab pertanyaan keluhan dan lakukan pemeriksaan mandiri secara bertahap. Anda dapat meninjau kembali langkah sebelumnya sebelum mengirim.
            </p>
        </header>

        <nav class="step-nav" aria-label="Tahapan self-diagnosis">
            <button type="button" class="step-pill" data-step-nav="1" aria-current="step">
                <span class="step-pill-num">1.</span>
                <span>Nyeri rahang</span>
            </button>
            <button type="button" class="step-pill" data-step-nav="2">
                <span class="step-pill-num">2.</span>
                <span>Bunyi &amp; kunci</span>
            </button>
            <button type="button" class="step-pill" data-step-nav="3">
                <span class="step-pill-num">3.</span>
                <span>Nyeri tekan</span>
            </button>
            <button type="button" class="step-pill" data-step-nav="4">
                <span class="step-pill-num">4.</span>
                <span>Bukaan mulut</span>
            </button>
            <button type="button" class="step-pill" data-step-nav="5">
                <span class="step-pill-num">5.</span>
                <span>Bunyi &amp; kirim</span>
            </button>
        </nav>

        @if (session('error'))
            <div class="banner banner-error" role="alert">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="banner-title">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="banner banner-error" role="alert" id="server-errors-banner">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="banner-title">Periksa kembali isian formulir Anda</p>
                    <ul class="banner-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form
            id="consultation-form"
            action="{{ route('consultations.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="card"
            novalidate
        >
            @csrf

            @php
                $sqGroup1 = ['SQ1', 'SQ2', 'SQ3', 'SQ4', 'SQ5', 'SQ6', 'SQ7'];
                $sqGroup2 = ['SQ8', 'SQ9', 'SQ10', 'SQ11', 'SQ12', 'SQ13', 'SQ14'];
            @endphp

            {{-- ===============================================================
                 Langkah 1: Pertanyaan Subjektif (SQ1–SQ7)
                 =============================================================== --}}
            <section class="step-panel" data-step="1" aria-labelledby="step-title-1">
                <header class="step-header">
                    <p class="step-kicker">Bagian 1 dari 5 · Pertanyaan Subjektif</p>
                    <h2 id="step-title-1" class="step-title" tabindex="-1">Keluhan nyeri rahang dan sakit kepala</h2>
                    <p class="step-desc">Pilih jawaban yang paling sesuai dengan keluhan yang Anda rasakan belakangan ini.</p>
                </header>

                <div class="question-stack">
                    @foreach ($sqGroup1 as $code)
                        @php
                            $question = $sq_questions[$code];
                            $optionLabels = \App\Support\TmdQuestionnaire::WEB_OPTION_LABELS[$code] ?? [];
                            $currentVal = old("sq.$code", $question['default']);
                        @endphp
                        @if ($question['type'] === 'select')
                            <div class="q-fieldset">
                                <label for="sq_{{ $code }}" class="q-legend">
                                    <span class="q-code">{{ $code }}</span>{{ $question['text'] }}
                                </label>
                                <div class="input-wrap">
                                    <select name="sq[{{ $code }}]" id="sq_{{ $code }}" class="field-select">
                                        @foreach ($question['options'] as $option)
                                            <option value="{{ $option }}" @selected($currentVal === $option)>{{ $optionLabels[$option] ?? $option }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <fieldset class="q-fieldset">
                                <legend class="q-legend">
                                    <span class="q-code">{{ $code }}</span>{{ $question['text'] }}
                                </legend>
                                <div class="radio-grid {{ count($question['options']) === 2 ? 'radio-grid--two' : '' }}">
                                    @foreach ($question['options'] as $optIdx => $option)
                                        <label for="sq_{{ $code }}_{{ $optIdx }}" class="radio-card">
                                            <input
                                                type="radio"
                                                name="sq[{{ $code }}]"
                                                id="sq_{{ $code }}_{{ $optIdx }}"
                                                value="{{ $option }}"
                                                class="radio-input"
                                                @checked($currentVal === $option)
                                            >
                                            <span>{{ $optionLabels[$option] ?? $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif
                    @endforeach
                </div>
            </section>

            {{-- ===============================================================
                 Langkah 2: Pertanyaan Subjektif (SQ8–SQ14)
                 =============================================================== --}}
            <section class="step-panel" data-step="2" aria-labelledby="step-title-2" hidden>
                <header class="step-header">
                    <p class="step-kicker">Bagian 2 dari 5 · Pertanyaan Subjektif</p>
                    <h2 id="step-title-2" class="step-title" tabindex="-1">Bunyi sendi dan rahang terkunci</h2>
                    <p class="step-desc">Ceritakan apakah Anda pernah mendengar bunyi pada rahang atau mengalami rahang sulit dibuka dan ditutup.</p>
                </header>

                <div class="question-stack">
                    @foreach ($sqGroup2 as $code)
                        @php
                            $question = $sq_questions[$code];
                            $optionLabels = \App\Support\TmdQuestionnaire::WEB_OPTION_LABELS[$code] ?? [];
                            $currentVal = old("sq.$code", $question['default']);
                        @endphp
                        @if ($question['type'] === 'select')
                            <div class="q-fieldset">
                                <label for="sq_{{ $code }}" class="q-legend">
                                    <span class="q-code">{{ $code }}</span>{{ $question['text'] }}
                                </label>
                                <div class="input-wrap">
                                    <select name="sq[{{ $code }}]" id="sq_{{ $code }}" class="field-select">
                                        @foreach ($question['options'] as $option)
                                            <option value="{{ $option }}" @selected($currentVal === $option)>{{ $optionLabels[$option] ?? $option }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <fieldset class="q-fieldset">
                                <legend class="q-legend">
                                    <span class="q-code">{{ $code }}</span>{{ $question['text'] }}
                                </legend>
                                <div class="radio-grid {{ count($question['options']) === 2 ? 'radio-grid--two' : '' }}">
                                    @foreach ($question['options'] as $optIdx => $option)
                                        <label for="sq_{{ $code }}_{{ $optIdx }}" class="radio-card">
                                            <input
                                                type="radio"
                                                name="sq[{{ $code }}]"
                                                id="sq_{{ $code }}_{{ $optIdx }}"
                                                value="{{ $option }}"
                                                class="radio-input"
                                                @checked($currentVal === $option)
                                            >
                                            <span>{{ $optionLabels[$option] ?? $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif
                    @endforeach
                </div>
            </section>

            {{-- ===============================================================
                 Langkah 3: Pemeriksaan Mandiri Nyeri Tekan (E1 & E4)
                 =============================================================== --}}
            <section class="step-panel" data-step="3" aria-labelledby="step-title-3" hidden>
                <header class="step-header">
                    <p class="step-kicker">Bagian 3 dari 5 · Pemeriksaan Mandiri</p>
                    <h2 id="step-title-3" class="step-title" tabindex="-1">Pemeriksaan nyeri tekan pada otot dan sendi</h2>
                    <p class="step-desc">Ikuti panduan lokasi pada gambar di bawah, lalu nilai tingkat nyeri saat menekan ringan dan saat menekan lebih dalam.</p>
                </header>

                <figure class="ref-figure">
                    <img
                        src="{{ asset('images/e1.png') }}"
                        alt="Ilustrasi lokasi titik tekan pada pelipis (temporalis), pipi (masseter), dan sendi rahang (TMJ)"
                        class="ref-img"
                    >
                    <figcaption class="ref-caption">
                        Titik pemeriksaan: Pelipis (Temporalis), Pipi (Masseter), dan area depan telinga / Sendi Rahang (TMJ).
                    </figcaption>
                </figure>

                <div class="question-stack">
                    <div>
                        <h3 class="sub-heading"><span class="q-code">E1</span>{{ $eq_titles['E1'] }}</h3>
                        <p class="sub-desc">Tekan ringan area berikut dengan ujung jari Anda selama sekitar 2 detik, lalu pilih tingkat nyeri yang dirasakan.</p>
                        <div class="area-grid">
                            @foreach (\App\Support\TmdQuestionnaire::AREAS as $area)
                                <div>
                                    <label for="e1_{{ strtolower($area) }}" class="field-label">
                                        {{ \App\Support\TmdQuestionnaire::AREA_LABELS[$area] }}
                                    </label>
                                    <div class="input-wrap">
                                        <select name="eq[E1][{{ $area }}]" id="e1_{{ strtolower($area) }}" class="field-select">
                                            @foreach (\App\Support\TmdQuestionnaire::SCORE_LABELS as $score => $label)
                                                <option value="{{ $score }}" @selected((string) old("eq.E1.$area", 0) === (string) $score)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="sub-divider">
                        <h3 class="sub-heading"><span class="q-code">E4</span>{{ $eq_titles['E4'] }}</h3>
                        <p class="sub-desc">Tekan sedikit lebih dalam pada tiga titik yang sama, lalu pilih tingkat nyeri yang paling sesuai.</p>
                        <div class="area-grid">
                            @foreach (\App\Support\TmdQuestionnaire::AREAS as $area)
                                <div>
                                    <label for="e4_{{ strtolower($area) }}" class="field-label">
                                        {{ \App\Support\TmdQuestionnaire::AREA_LABELS[$area] }}
                                    </label>
                                    <div class="input-wrap">
                                        <select name="eq[E4][{{ $area }}]" id="e4_{{ strtolower($area) }}" class="field-select">
                                            @foreach (\App\Support\TmdQuestionnaire::SCORE_LABELS as $score => $label)
                                                <option value="{{ $score }}" @selected((string) old("eq.E4.$area", 0) === (string) $score)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===============================================================
                 Langkah 4: Pengukuran Bukaan Mulut & Estimasi Foto (E2)
                 =============================================================== --}}
            <section class="step-panel" data-step="4" aria-labelledby="step-title-4" hidden>
                <header class="step-header">
                    <p class="step-kicker">Bagian 4 dari 5 · Pengukuran Bukaan Mulut</p>
                    <h2 id="step-title-4" class="step-title" tabindex="-1"><span class="q-code">E2</span>{{ $eq_titles['E2'] }}</h2>
                    <p class="step-desc">
                        Buka mulut Anda selebar mungkin tanpa memaksa nyeri. Anda bisa memotret wajah untuk mendapatkan estimasi otomatis (mm) yang dapat disesuaikan, atau langsung mengisi hasil pengukuran penggaris di kolom bawah.
                    </p>
                </header>

                <div class="question-stack">
                    <div class="photo-box">
                        <div class="photo-box-header">
                            <p class="photo-box-title">Foto &amp; estimasi bukaan mulut</p>
                            <span class="optional-pill">Opsional</span>
                        </div>

                        <div id="video-wrap" class="photo-viewport photo-viewport--camera">
                            <video id="video" class="photo-video" playsinline muted autoplay></video>
                            <div class="camera-guide" aria-hidden="true">
                                <div class="camera-guide-oval"></div>
                                <span class="camera-guide-tip">Hadap lurus ke kamera, pastikan mata dan mulut terlihat jelas</span>
                            </div>
                        </div>

                        <div id="photo-preview-container" class="photo-viewport">
                            <div id="measure-stage" class="measure-stage">
                                <img id="photo-preview" class="photo-preview" alt="Pratinjau foto mulut terbuka">
                                <div id="measure-overlay" class="measure-overlay" hidden>
                                    <div id="measure-band" class="measure-band"></div>
                                    <div id="measure-axis" class="measure-axis"></div>
                                    <div id="measure-readout" class="measure-readout" aria-hidden="true">~0 mm</div>

                                    <button
                                        type="button"
                                        id="measure-handle-top"
                                        class="measure-handle measure-handle--top"
                                        role="slider"
                                        aria-orientation="vertical"
                                        aria-label="Geser garis batas ujung gigi seri atas"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        aria-valuenow="0"
                                    >
                                        <span class="measure-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <polyline points="8 6 12 2 16 6"/>
                                                <polyline points="8 18 12 22 16 18"/>
                                                <line x1="12" y1="2" x2="12" y2="22"/>
                                            </svg>
                                            <span>Gigi atas</span>
                                        </span>
                                    </button>

                                    <button
                                        type="button"
                                        id="measure-handle-bottom"
                                        class="measure-handle measure-handle--bottom"
                                        role="slider"
                                        aria-orientation="vertical"
                                        aria-label="Geser garis batas ujung gigi seri bawah"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        aria-valuenow="0"
                                    >
                                        <span class="measure-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <polyline points="8 6 12 2 16 6"/>
                                                <polyline points="8 18 12 22 16 18"/>
                                                <line x1="12" y1="2" x2="12" y2="22"/>
                                            </svg>
                                            <span>Gigi bawah</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="estimate-panel" class="estimate-panel" hidden>
                            <div class="estimate-row">
                                <div class="estimate-value-wrap">
                                    <span id="estimate-badge" class="estimate-badge">Estimasi foto: ~0 mm</span>
                                </div>
                                <div class="estimate-actions">
                                    <button type="button" id="reset-calipers-btn" class="btn-secondary btn-xs">Reset garis</button>
                                </div>
                            </div>
                            <p id="estimate-help" class="estimate-help">
                                Geser garis <strong>Gigi atas</strong> dan <strong>Gigi bawah</strong> tepat ke ujung gigi seri atas dan bawah agar estimasi lebih mendekati ukuran asli. Anda tetap bisa merevisi angka di kolom bawah.
                            </p>
                        </div>

                        <canvas id="canvas" class="file-input-hidden" aria-hidden="true"></canvas>
                        <input type="hidden" name="e2_photo" id="e2_photo" value="{{ old('e2_photo') }}">
                        <input
                            type="file"
                            name="e2_photo_file"
                            id="e2_photo_file"
                            accept="image/jpeg,image/png"
                            hidden
                        >

                        <div class="photo-actions">
                            <button id="snap" type="button" class="btn-primary btn-sm" hidden>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/>
                                </svg>
                                <span>Ambil foto &amp; ukur</span>
                            </button>

                            <div class="photo-actions-secondary">
                                <button id="start-camera" type="button" class="btn-secondary btn-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                        <circle cx="12" cy="13" r="4"/>
                                    </svg>
                                    <span id="start-camera-label">Buka kamera</span>
                                </button>

                                <button id="upload-photo-btn" type="button" class="btn-secondary btn-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    <span>Unggah foto</span>
                                </button>

                                <button id="clear-photo" type="button" class="btn-secondary btn-sm" hidden>
                                    <span>Hapus foto</span>
                                </button>
                            </div>
                        </div>

                        <p id="photo-status" class="photo-status" role="status">
                            <svg id="photo-spinner" class="inline-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true" hidden>
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            <span id="photo-status-text">Ambil foto dengan kamera depan (atau unggah JPG/PNG maks 5 MB) untuk estimasi bukaan mulut otomatis. Foto disimpan secara privat.</span>
                        </p>

                        <p id="photo-error" class="field-error" @if (!$errors->has('e2_photo') && !$errors->has('e2_photo_file')) hidden @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span id="photo-error-text">{{ $errors->first('e2_photo') ?: $errors->first('e2_photo_file') }}</span>
                        </p>
                    </div>

                    <div>
                        <label for="e2_opening" class="field-label">Jarak bukaan mulut maksimal (dapat direvisi manual)</label>
                        <div class="input-wrap">
                            <input
                                type="number"
                                name="eq[E2][opening_mm]"
                                id="e2_opening"
                                class="field-input field-input--unit"
                                placeholder="40"
                                min="0"
                                max="100"
                                step="any"
                                inputmode="decimal"
                                value="{{ old('eq.E2.opening_mm') }}"
                                required
                                @if ($errors->has('eq.E2.opening_mm')) aria-invalid="true" aria-describedby="e2-opening-hint e2-opening-error" @else aria-describedby="e2-opening-hint" @endif
                            >
                            <span class="input-unit" aria-hidden="true">mm</span>
                        </div>

                        <div id="opening-source-bar" class="opening-source-bar" hidden>
                            <span id="opening-source-text"></span>
                            <button type="button" id="apply-estimate-btn" class="opening-source-btn" hidden>Gunakan estimasi foto</button>
                        </div>

                        <p id="e2-opening-hint" class="field-hint">
                            Ukur jarak ujung gigi seri atas ke bawah dalam milimeter (0–100 mm). Estimasi kamera memiliki kisaran toleransi ±3–5 mm; gunakan penggaris bila tersedia untuk hasil paling presisi.
                        </p>
                        <p id="e2-opening-error" class="field-error" @if (!$errors->has('eq.E2.opening_mm')) hidden @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span id="e2-opening-error-text">{{ $errors->first('eq.E2.opening_mm') }}</span>
                        </p>
                    </div>
                </div>
            </section>

            {{-- ===============================================================
                 Langkah 5: Bunyi Sendi (E3) & Ringkasan Kirim
                 =============================================================== --}}
            <section class="step-panel" data-step="5" aria-labelledby="step-title-5" hidden>
                <header class="step-header">
                    <p class="step-kicker">Bagian 5 dari 5 · Pemeriksaan Bunyi &amp; Tinjauan</p>
                    <h2 id="step-title-5" class="step-title" tabindex="-1"><span class="q-code">E3</span>{{ $eq_titles['E3'] }}</h2>
                    <p class="step-desc">Buka dan tutup mulut beberapa kali. Putar contoh suara berikut lalu pilih bunyi yang paling mendekati keluhan Anda.</p>
                </header>

                <div class="question-stack">
                    <fieldset class="q-fieldset">
                        <legend class="q-legend">Bunyi yang terdengar pada sendi rahang</legend>
                        <div class="e3-stack">
                            @foreach ($e3_options as $i => $option)
                                <div class="e3-card">
                                    <label for="e3_option_{{ $i }}" class="e3-label">
                                        <input
                                            type="radio"
                                            name="eq[E3]"
                                            value="{{ $option['value'] }}"
                                            id="e3_option_{{ $i }}"
                                            class="radio-input"
                                            @checked(old('eq.E3', \App\Support\TmdQuestionnaire::e3Default()) === $option['value'])
                                        >
                                        <span>{{ $option['label'] }}</span>
                                    </label>
                                    @isset($option['audio'])
                                        <div class="e3-audio-wrap">
                                            <p class="e3-audio-caption">Contoh rekaman {{ strtolower($option['label']) }}:</p>
                                            <audio controls preload="metadata" src="{{ asset($option['audio']) }}" class="e3-audio"></audio>
                                        </div>
                                    @endisset
                                </div>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="summary-panel" aria-labelledby="summary-heading">
                        <h3 id="summary-heading" class="summary-title">Ringkasan sebelum analisis</h3>
                        <dl class="summary-dl">
                            <div class="summary-item">
                                <dt class="summary-dt">Pertanyaan subjektif</dt>
                                <dd id="summary-sq" class="summary-dd">14 pertanyaan siap dikirim</dd>
                            </div>
                            <div class="summary-item">
                                <dt class="summary-dt">Bukaan mulut (E2)</dt>
                                <dd id="summary-opening" class="summary-dd">Belum diisi</dd>
                            </div>
                            <div class="summary-item">
                                <dt class="summary-dt">Foto bukaan mulut</dt>
                                <dd id="summary-photo" class="summary-dd">Tidak dilampirkan</dd>
                            </div>
                            <div class="summary-item">
                                <dt class="summary-dt">Bunyi sendi (E3)</dt>
                                <dd id="summary-e3" class="summary-dd">{{ old('eq.E3', \App\Support\TmdQuestionnaire::e3Default()) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>

            {{-- Footer Navigasi Langkah --}}
            <div class="step-footer">
                <button type="button" id="prev-btn" class="btn-secondary" hidden>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    <span>Sebelumnya</span>
                </button>

                <button type="button" id="next-btn" class="btn-primary">
                    <span id="next-btn-label">Lanjut ke langkah berikutnya</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>

                <button type="submit" id="submit-btn" class="btn-primary" aria-disabled="false" hidden>
                    <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span id="submit-label">Lihat hasil diagnosis</span>
                </button>
            </div>
        </form>
    </main>

    <footer class="page-footer">
        <p class="clinical-note">
            Hasil skrining mandiri bersifat informatif dan tidak menggantikan pemeriksaan langsung oleh dokter gigi.
        </p>
    </footer>

    @php
        $initialStep = 1;
        if ($errors->has('eq.E2.opening_mm') || $errors->has('e2_photo') || $errors->has('e2_photo_file')) {
            $initialStep = 4;
        } elseif ($errors->has('eq.E3')) {
            $initialStep = 5;
        } elseif ($errors->has('eq.E1.*') || $errors->has('eq.E4.*')) {
            $initialStep = 3;
        }
    @endphp

    <script>
        (function () {
            const totalSteps = 5;
            let currentStep = {{ $initialStep }};
            let isSubmitting = false;
            let isDirty = false;
            let stream = null;
            let previewObjectUrl = null;

            const form = document.getElementById('consultation-form');
            const panels = Array.from(document.querySelectorAll('.step-panel'));
            const stepPills = Array.from(document.querySelectorAll('[data-step-nav]'));
            const stepCounter = document.getElementById('step-counter');
            const progressFill = document.getElementById('progress-fill');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const submitLabel = document.getElementById('submit-label');

            const openingInput = document.getElementById('e2_opening');
            const openingErrorEl = document.getElementById('e2-opening-error');
            const openingErrorText = document.getElementById('e2-opening-error-text');

            const startCameraBtn = document.getElementById('start-camera');
            const startCameraLabel = document.getElementById('start-camera-label');
            const snapBtn = document.getElementById('snap');
            const uploadPhotoBtn = document.getElementById('upload-photo-btn');
            const clearPhotoBtn = document.getElementById('clear-photo');
            const videoWrap = document.getElementById('video-wrap');
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photoPreviewContainer = document.getElementById('photo-preview-container');
            const measureStage = document.getElementById('measure-stage');
            const photoPreview = document.getElementById('photo-preview');
            const measureOverlay = document.getElementById('measure-overlay');
            const measureBand = document.getElementById('measure-band');
            const measureAxis = document.getElementById('measure-axis');
            const measureReadout = document.getElementById('measure-readout');
            const handleTop = document.getElementById('measure-handle-top');
            const handleBottom = document.getElementById('measure-handle-bottom');
            const estimatePanel = document.getElementById('estimate-panel');
            const estimateBadge = document.getElementById('estimate-badge');
            const estimateHelp = document.getElementById('estimate-help');
            const resetCalipersBtn = document.getElementById('reset-calipers-btn');
            const openingSourceBar = document.getElementById('opening-source-bar');
            const openingSourceText = document.getElementById('opening-source-text');
            const applyEstimateBtn = document.getElementById('apply-estimate-btn');
            const hiddenPhotoInput = document.getElementById('e2_photo');
            const filePhotoInput = document.getElementById('e2_photo_file');
            const photoSpinner = document.getElementById('photo-spinner');
            const photoStatusText = document.getElementById('photo-status-text');
            const photoErrorEl = document.getElementById('photo-error');
            const photoErrorText = document.getElementById('photo-error-text');

            const summaryOpening = document.getElementById('summary-opening');
            const summaryPhoto = document.getElementById('summary-photo');
            const summaryE3 = document.getElementById('summary-e3');

            // =================================================================
            // MediaPipe Face Landmarker (Iris Calibration: ~11.7 mm HVID)
            // =================================================================
            const AVG_IRIS_DIAMETER_MM = 11.7;
            const MEDIAPIPE_BUNDLE_URL = 'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/vision_bundle.mjs';
            const MEDIAPIPE_WASM_URL = 'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm';
            const FACE_MODEL_URL = 'https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task';

            let faceLandmarkerPromise = null;
            let estimateRunId = 0;
            let measurement = null; // { mmPerPx, naturalHeight, xCenter, mouthHalfW, topNorm, bottomNorm, initTopNorm, initBottomNorm, estimatedMm, rollDeg }
            let photoAlreadyAnnotated = Boolean(hiddenPhotoInput.value);

            function setPhotoStatus(text, loading) {
                photoStatusText.textContent = text;
                photoSpinner.hidden = !loading;
            }

            function clamp(val, min, max) {
                return Math.min(max, Math.max(min, val));
            }

            function loadFaceLandmarker() {
                if (faceLandmarkerPromise) return faceLandmarkerPromise;
                faceLandmarkerPromise = (async function () {
                    const visionPkg = await import(MEDIAPIPE_BUNDLE_URL);
                    const fileset = await visionPkg.FilesetResolver.forVisionTasks(MEDIAPIPE_WASM_URL);
                    try {
                        return await visionPkg.FaceLandmarker.createFromOptions(fileset, {
                            baseOptions: {
                                modelAssetPath: FACE_MODEL_URL,
                                delegate: 'GPU'
                            },
                            runningMode: 'IMAGE',
                            numFaces: 1
                        });
                    } catch (gpuErr) {
                        return await visionPkg.FaceLandmarker.createFromOptions(fileset, {
                            baseOptions: {
                                modelAssetPath: FACE_MODEL_URL,
                                delegate: 'CPU'
                            },
                            runningMode: 'IMAGE',
                            numFaces: 1
                        });
                    }
                })().catch(function (err) {
                    faceLandmarkerPromise = null;
                    throw err;
                });
                return faceLandmarkerPromise;
            }

            function distPx(a, b, w, h) {
                return Math.hypot((a.x - b.x) * w, (a.y - b.y) * h);
            }

            function clearMeasurement() {
                estimateRunId++;
                measurement = null;
                measureOverlay.hidden = true;
                estimatePanel.hidden = true;
                openingSourceBar.hidden = true;
                applyEstimateBtn.hidden = true;
            }

            function computeMeasurementMm() {
                if (!measurement) return 0;
                const pxDist = Math.abs(measurement.bottomNorm - measurement.topNorm) * measurement.naturalHeight;
                return clamp(Math.round(pxDist * measurement.mmPerPx), 0, 100);
            }

            function updateSourceIndicator(isManualOverride) {
                if (!measurement) {
                    openingSourceBar.hidden = true;
                    return;
                }
                openingSourceBar.hidden = false;
                if (isManualOverride) {
                    openingSourceText.textContent = 'Nilai direvisi manual (estimasi foto saat ini: ~' + measurement.estimatedMm + ' mm).';
                    applyEstimateBtn.textContent = 'Pakai estimasi (~' + measurement.estimatedMm + ' mm)';
                    applyEstimateBtn.hidden = false;
                } else {
                    openingSourceText.textContent = 'Terisi otomatis dari estimasi foto (~' + measurement.estimatedMm + ' mm). Anda tetap dapat mengetik angka manual.';
                    applyEstimateBtn.hidden = true;
                }
            }

            function renderCalipers(syncInput) {
                if (!measurement) return;
                const stageRect = measureStage.getBoundingClientRect();
                const stageH = stageRect.height;
                const stageW = stageRect.width;
                if (!stageH || !stageW) return;

                const topPx = measurement.topNorm * stageH;
                const bottomPx = measurement.bottomNorm * stageH;
                const spanPx = Math.max(0, bottomPx - topPx);
                const halfW = (measurement.mouthHalfW || 0.14) * stageW;
                const caliperX = clamp(measurement.xCenter * stageW + halfW + 10, stageW * 0.22, stageW - 44);

                handleTop.style.transform = 'translateY(' + topPx + 'px)';
                handleBottom.style.transform = 'translateY(' + bottomPx + 'px)';
                handleTop.setAttribute('aria-valuenow', String(Math.round(measurement.topNorm * 100)));
                handleBottom.setAttribute('aria-valuenow', String(Math.round(measurement.bottomNorm * 100)));

                measureBand.style.transform = 'translateY(' + topPx + 'px)';
                measureBand.style.height = spanPx + 'px';

                measureAxis.style.left = caliperX + 'px';
                measureAxis.style.transform = 'translateY(' + topPx + 'px)';
                measureAxis.style.height = spanPx + 'px';

                const estMm = computeMeasurementMm();
                measurement.estimatedMm = estMm;

                const readoutText = '~' + estMm + ' mm';
                measureReadout.textContent = readoutText;
                measureReadout.style.left = clamp(caliperX, 46, stageW - 46) + 'px';
                measureReadout.style.top = (topPx + spanPx / 2) + 'px';

                estimateBadge.textContent = 'Estimasi foto: ' + readoutText;

                if (syncInput) {
                    openingInput.value = String(estMm);
                    setOpeningError('');
                    updateSourceIndicator(false);
                } else {
                    const currentVal = openingInput.value.trim();
                    updateSourceIndicator(currentVal !== String(estMm));
                }
            }

            function attachCaliperDrag(handleEl, which) {
                let activePointerId = null;

                handleEl.addEventListener('pointerdown', function (event) {
                    if (!measurement) return;
                    event.preventDefault();
                    activePointerId = event.pointerId;
                    handleEl.setPointerCapture(event.pointerId);
                    handleEl.classList.add('is-dragging');
                });

                handleEl.addEventListener('pointermove', function (event) {
                    if (activePointerId !== event.pointerId || !measurement) return;
                    const rect = measureStage.getBoundingClientRect();
                    if (!rect.height) return;
                    const rawNorm = clamp((event.clientY - rect.top) / rect.height, 0.02, 0.98);
                    const minGap = 0.004;
                    if (which === 'top') {
                        measurement.topNorm = Math.min(rawNorm, measurement.bottomNorm - minGap);
                    } else {
                        measurement.bottomNorm = Math.max(rawNorm, measurement.topNorm + minGap);
                    }
                    isDirty = true;
                    renderCalipers(true);
                });

                function endDrag(event) {
                    if (activePointerId !== event.pointerId) return;
                    activePointerId = null;
                    handleEl.classList.remove('is-dragging');
                    try {
                        handleEl.releasePointerCapture(event.pointerId);
                    } catch (e) {
                        // Ignore if capture already released
                    }
                }

                handleEl.addEventListener('pointerup', endDrag);
                handleEl.addEventListener('pointercancel', endDrag);

                // Keyboard support (WCAG 2.5.7 & APG slider keys)
                handleEl.addEventListener('keydown', function (event) {
                    if (!measurement) return;
                    const step = event.shiftKey ? 0.02 : 0.005;
                    let delta = 0;
                    if (event.key === 'ArrowUp') delta = -step;
                    else if (event.key === 'ArrowDown') delta = step;
                    else return;

                    event.preventDefault();
                    const minGap = 0.004;
                    if (which === 'top') {
                        measurement.topNorm = clamp(measurement.topNorm + delta, 0.02, measurement.bottomNorm - minGap);
                    } else {
                        measurement.bottomNorm = clamp(measurement.bottomNorm + delta, measurement.topNorm + minGap, 0.98);
                    }
                    isDirty = true;
                    renderCalipers(true);
                });
            }

            attachCaliperDrag(handleTop, 'top');
            attachCaliperDrag(handleBottom, 'bottom');

            window.addEventListener('resize', function () {
                if (measurement && !measureOverlay.hidden) {
                    renderCalipers(false);
                }
            });

            resetCalipersBtn.addEventListener('click', function () {
                if (!measurement) return;
                measurement.topNorm = measurement.initTopNorm;
                measurement.bottomNorm = measurement.initBottomNorm;
                renderCalipers(true);
            });

            applyEstimateBtn.addEventListener('click', function () {
                if (!measurement) return;
                openingInput.value = String(measurement.estimatedMm);
                setOpeningError('');
                updateSourceIndicator(false);
                openingInput.focus();
            });

            async function waitForImageReady(img) {
                if (img.complete && img.naturalWidth > 0) return;
                if (typeof img.decode === 'function') {
                    try {
                        await img.decode();
                        if (img.naturalWidth > 0) return;
                    } catch (e) {
                        // Fallback to onload listener
                    }
                }
                await new Promise(function (resolve, reject) {
                    img.addEventListener('load', resolve, { once: true });
                    img.addEventListener('error', reject, { once: true });
                });
            }

            async function runOpeningEstimation(autoFillInput) {
                const runId = ++estimateRunId;
                measurement = null;
                measureOverlay.hidden = true;
                estimatePanel.hidden = true;
                openingSourceBar.hidden = true;

                setPhotoStatus('Memuat deteksi wajah dan menghitung estimasi bukaan mulut…', true);

                try {
                    await waitForImageReady(photoPreview);
                    if (runId !== estimateRunId) return;

                    const landmarker = await loadFaceLandmarker();
                    if (runId !== estimateRunId) return;

                    const result = landmarker.detect(photoPreview);
                    if (runId !== estimateRunId) return;

                    const lm = result && result.faceLandmarks && result.faceLandmarks[0];
                    if (!lm || lm.length < 478) {
                        setPhotoStatus('Foto siap dikirim. Wajah atau iris mata belum terdeteksi jelas, silakan isi ukuran mm secara manual di bawah.', false);
                        return;
                    }

                    const w = photoPreview.naturalWidth;
                    const h = photoPreview.naturalHeight;

                    const leftH = distPx(lm[469], lm[471], w, h);
                    const leftV = distPx(lm[470], lm[472], w, h);
                    const rightH = distPx(lm[474], lm[476], w, h);
                    const rightV = distPx(lm[475], lm[477], w, h);
                    const irisPx = (leftH + leftV + rightH + rightV) / 4;

                    if (!irisPx || irisPx < 4) {
                        setPhotoStatus('Foto siap dikirim. Ukuran mata terlalu kecil untuk kalibrasi otomatis — isi ukuran mm secara manual di bawah.', false);
                        return;
                    }

                    const mmPerPx = AVG_IRIS_DIAMETER_MM / irisPx;
                    const eyeDx = Math.abs((lm[473].x - lm[468].x) * w);
                    const eyeDy = Math.abs((lm[473].y - lm[468].y) * h);
                    const rollDeg = Math.atan2(eyeDy, Math.max(1, eyeDx)) * (180 / Math.PI);

                    const rawTop = clamp(Math.min(lm[13].y, lm[14].y), 0.05, 0.92);
                    const rawBottom = clamp(Math.max(lm[13].y, lm[14].y), rawTop + 0.01, 0.96);
                    const xCenter = clamp((lm[13].x + lm[14].x) / 2, 0.2, 0.8);
                    const mouthHalfW = (lm[61] && lm[291])
                        ? clamp(Math.abs(lm[291].x - lm[61].x) / 2, 0.08, 0.28)
                        : 0.14;

                    measurement = {
                        mmPerPx: mmPerPx,
                        naturalHeight: h,
                        xCenter: xCenter,
                        mouthHalfW: mouthHalfW,
                        topNorm: rawTop,
                        bottomNorm: rawBottom,
                        initTopNorm: rawTop,
                        initBottomNorm: rawBottom,
                        estimatedMm: 0,
                        rollDeg: rollDeg
                    };

                    measureOverlay.hidden = false;
                    estimatePanel.hidden = false;

                    if (rollDeg > 15) {
                        estimateHelp.innerHTML = 'Kepala terlihat agak miring; geser garis <strong>Gigi atas</strong> dan <strong>Gigi bawah</strong> ke ujung gigi seri atau revisi angka manual di kolom bawah untuk hasil lebih akurat.';
                    } else {
                        estimateHelp.innerHTML = 'Geser garis <strong>Gigi atas</strong> dan <strong>Gigi bawah</strong> tepat ke ujung gigi seri atas dan bawah agar estimasi lebih mendekati ukuran asli. Anda tetap bisa merevisi angka di kolom bawah.';
                    }

                    requestAnimationFrame(function () {
                        if (runId !== estimateRunId) return;
                        renderCalipers(autoFillInput);
                        setPhotoStatus('Estimasi otomatis selesai (~' + measurement.estimatedMm + ' mm). Anda dapat menggeser garis ukur pada foto atau merevisi angkanya.', false);
                    });
                } catch (err) {
                    if (runId !== estimateRunId) return;
                    setPhotoStatus('Foto siap dikirim. Estimasi otomatis tidak tersedia (koneksi/browser), silakan isi jarak bukaan mulut secara manual di bawah.', false);
                }
            }

            function stopCameraStream() {
                if (stream) {
                    stream.getTracks().forEach(function (track) { track.stop(); });
                    stream = null;
                }
                video.srcObject = null;
                videoWrap.classList.remove('is-visible');
                snapBtn.hidden = true;
            }

            function setOpeningError(message) {
                if (message) {
                    openingInput.setAttribute('aria-invalid', 'true');
                    openingInput.setAttribute('aria-describedby', 'e2-opening-hint e2-opening-error');
                    openingErrorText.textContent = message;
                    openingErrorEl.hidden = false;
                } else {
                    openingInput.removeAttribute('aria-invalid');
                    openingInput.setAttribute('aria-describedby', 'e2-opening-hint');
                    openingErrorText.textContent = '';
                    openingErrorEl.hidden = true;
                }
            }

            function validateOpening() {
                const raw = openingInput.value.trim();
                if (!raw) {
                    return 'Masukkan jarak bukaan mulut Anda dalam milimeter (mm).';
                }
                const num = Number(raw);
                if (Number.isNaN(num) || num < 0 || num > 100) {
                    return 'Masukkan angka antara 0 sampai 100 mm.';
                }
                return '';
            }

            function setPhotoError(message) {
                if (message) {
                    photoErrorText.textContent = message;
                    photoErrorEl.hidden = false;
                } else {
                    photoErrorText.textContent = '';
                    photoErrorEl.hidden = true;
                }
            }

            function updateSummary() {
                const openingErr = validateOpening();
                const rawOpening = openingInput.value.trim();
                summaryOpening.textContent = (!openingErr && rawOpening !== '') ? rawOpening + ' mm' : 'Belum diisi (wajib)';

                if (hiddenPhotoInput.value) {
                    summaryPhoto.textContent = 'Terlampir dari kamera';
                } else if (filePhotoInput.files && filePhotoInput.files.length > 0) {
                    summaryPhoto.textContent = 'Terlampir (' + filePhotoInput.files[0].name + ')';
                } else {
                    summaryPhoto.textContent = 'Tidak dilampirkan (opsional)';
                }

                const checkedE3 = form.querySelector('input[name="eq[E3]"]:checked');
                summaryE3.textContent = checkedE3 ? checkedE3.value : 'Tidak ada';
            }

            function showStep(step, focusHeading) {
                if (step < 1 || step > totalSteps) return;
                if (currentStep === 4 && step !== 4) {
                    stopCameraStream();
                }

                currentStep = step;

                panels.forEach(function (panel) {
                    const pStep = Number(panel.getAttribute('data-step'));
                    panel.hidden = pStep !== currentStep;
                });

                stepPills.forEach(function (pill) {
                    const pStep = Number(pill.getAttribute('data-step-nav'));
                    if (pStep === currentStep) {
                        pill.setAttribute('aria-current', 'step');
                        pill.scrollIntoView({ block: 'nearest', inline: 'center' });
                    } else {
                        pill.removeAttribute('aria-current');
                    }
                    pill.classList.toggle('is-done', pStep < currentStep);
                });

                stepCounter.textContent = 'Langkah ' + currentStep + ' dari ' + totalSteps;
                progressFill.style.transform = 'scaleX(' + (currentStep / totalSteps) + ')';

                prevBtn.hidden = currentStep === 1;
                nextBtn.hidden = currentStep === totalSteps;
                submitBtn.hidden = currentStep !== totalSteps;

                if (currentStep === 4 && measurement && !measureOverlay.hidden) {
                    requestAnimationFrame(function () {
                        renderCalipers(false);
                    });
                }

                if (currentStep === totalSteps) {
                    updateSummary();
                }

                if (focusHeading) {
                    const heading = document.getElementById('step-title-' + currentStep);
                    if (heading) {
                        heading.focus({ preventScroll: true });
                        window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                    }
                }
            }

            // Initialize preview if old('e2_photo') exists after validation fail
            if (hiddenPhotoInput.value) {
                photoPreview.src = hiddenPhotoInput.value;
                photoPreviewContainer.classList.add('is-visible');
                clearPhotoBtn.hidden = false;
                startCameraLabel.textContent = 'Ambil ulang';
                setPhotoStatus('Foto dari kamera siap dikirim.', false);
                runOpeningEstimation(openingInput.value.trim() === '');
            }

            // Step pill navigation
            stepPills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    const target = Number(pill.getAttribute('data-step-nav'));
                    if (currentStep === 4 && target > 4) {
                        const err = validateOpening();
                        setOpeningError(err);
                        if (err) {
                            openingInput.focus();
                            return;
                        }
                    }
                    showStep(target, true);
                });
            });

            prevBtn.addEventListener('click', function () {
                showStep(currentStep - 1, true);
            });

            nextBtn.addEventListener('click', function () {
                if (currentStep === 4) {
                    const err = validateOpening();
                    setOpeningError(err);
                    if (err) {
                        openingInput.focus();
                        return;
                    }
                }
                showStep(currentStep + 1, true);
            });

            // Track dirty state
            form.addEventListener('change', function () {
                isDirty = true;
            });

            openingInput.addEventListener('input', function () {
                isDirty = true;
                if (openingInput.getAttribute('aria-invalid') === 'true') {
                    const err = validateOpening();
                    if (!err) {
                        setOpeningError('');
                    }
                }
                if (measurement) {
                    const currentVal = openingInput.value.trim();
                    updateSourceIndicator(currentVal !== String(measurement.estimatedMm));
                }
            });

            // Camera handling
            startCameraBtn.addEventListener('click', async function () {
                setPhotoError('');
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    setPhotoError('Browser ini tidak mendukung akses kamera langsung. Gunakan tombol Unggah dari perangkat.');
                    return;
                }

                try {
                    stopCameraStream();
                    clearMeasurement();
                    // Pre-warm Face Landmarker model in background while user frames face
                    loadFaceLandmarker().catch(function () {});

                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'user',
                            width: { ideal: 1080 },
                            height: { ideal: 1440 }
                        },
                        audio: false
                    });
                    video.srcObject = stream;
                    videoWrap.classList.add('is-visible');
                    photoPreviewContainer.classList.remove('is-visible');
                    snapBtn.hidden = false;
                    startCameraLabel.textContent = 'Ulang kamera';
                    setPhotoStatus('Posisikan wajah menghadap lurus ke kamera, buka mulut maksimal, lalu tekan Ambil foto & ukur.', false);
                    await video.play();
                } catch (err) {
                    setPhotoError('Kamera tidak dapat diakses. Berikan izin kamera di browser atau gunakan tombol Unggah foto.');
                }
            });

            snapBtn.addEventListener('click', function () {
                const vw = video.videoWidth || 640;
                const vh = video.videoHeight || 480;

                // Match the visible object-fit: cover crop of #video-wrap so the capture is WYSIWYG
                const wrapRect = videoWrap.getBoundingClientRect();
                const targetRatio = (wrapRect.width > 0 && wrapRect.height > 0)
                    ? (wrapRect.width / wrapRect.height)
                    : (vw / vh);
                const videoRatio = vw / vh;

                let srcX = 0;
                let srcY = 0;
                let srcW = vw;
                let srcH = vh;

                if (videoRatio > targetRatio) {
                    srcW = vh * targetRatio;
                    srcX = (vw - srcW) / 2;
                } else if (videoRatio < targetRatio) {
                    srcH = vw / targetRatio;
                    srcY = (vh - srcH) / 2;
                }

                const maxDim = 960;
                const scale = Math.max(srcW, srcH) > maxDim ? (maxDim / Math.max(srcW, srcH)) : 1;
                canvas.width = Math.round(srcW * scale);
                canvas.height = Math.round(srcH * scale);

                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, srcX, srcY, srcW, srcH, 0, 0, canvas.width, canvas.height);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.88);
                hiddenPhotoInput.value = dataUrl;
                filePhotoInput.value = '';
                if (previewObjectUrl) {
                    URL.revokeObjectURL(previewObjectUrl);
                    previewObjectUrl = null;
                }

                photoPreview.src = dataUrl;
                photoPreviewContainer.classList.add('is-visible');
                clearPhotoBtn.hidden = false;
                startCameraLabel.textContent = 'Ambil ulang';
                setPhotoError('');
                photoAlreadyAnnotated = false;
                isDirty = true;
                stopCameraStream();
                runOpeningEstimation(true);
            });

            uploadPhotoBtn.addEventListener('click', function () {
                filePhotoInput.click();
            });

            filePhotoInput.addEventListener('change', function () {
                setPhotoError('');
                const file = filePhotoInput.files && filePhotoInput.files[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    filePhotoInput.value = '';
                    setPhotoError('Format foto harus JPG atau PNG.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    filePhotoInput.value = '';
                    setPhotoError('Ukuran foto melebihi 5 MB. Pilih foto lain yang lebih kecil.');
                    return;
                }

                stopCameraStream();
                clearMeasurement();
                hiddenPhotoInput.value = '';
                if (previewObjectUrl) {
                    URL.revokeObjectURL(previewObjectUrl);
                }
                previewObjectUrl = URL.createObjectURL(file);
                photoPreview.src = previewObjectUrl;
                photoPreviewContainer.classList.add('is-visible');
                clearPhotoBtn.hidden = false;
                photoAlreadyAnnotated = false;
                isDirty = true;
                runOpeningEstimation(true);
            });

            clearPhotoBtn.addEventListener('click', function () {
                stopCameraStream();
                clearMeasurement();
                hiddenPhotoInput.value = '';
                filePhotoInput.value = '';
                if (previewObjectUrl) {
                    URL.revokeObjectURL(previewObjectUrl);
                    previewObjectUrl = null;
                }
                photoPreview.removeAttribute('src');
                photoPreviewContainer.classList.remove('is-visible');
                clearPhotoBtn.hidden = true;
                startCameraLabel.textContent = 'Buka kamera';
                setPhotoStatus('Ambil foto dengan kamera depan (atau unggah JPG/PNG maks 5 MB) untuk estimasi bukaan mulut otomatis. Foto disimpan secara privat.', false);
                setPhotoError('');
            });

            function drawRoundedRect(ctx, x, y, width, height, radius) {
                const r = Math.min(radius, width / 2, height / 2);
                ctx.beginPath();
                ctx.moveTo(x + r, y);
                ctx.arcTo(x + width, y, x + width, y + height, r);
                ctx.arcTo(x + width, y + height, x, y + height, r);
                ctx.arcTo(x, y + height, x, y, r);
                ctx.arcTo(x, y, x + width, y, r);
                ctx.closePath();
            }

            function renderAnnotatedPhotoDataUrl() {
                if (!photoPreview || !photoPreview.complete || !photoPreview.naturalWidth || !photoPreview.naturalHeight) {
                    return null;
                }

                const nw = photoPreview.naturalWidth;
                const nh = photoPreview.naturalHeight;
                const maxDim = 960;
                const scale = Math.max(nw, nh) > maxDim ? (maxDim / Math.max(nw, nh)) : 1;
                const w = Math.round(nw * scale);
                const h = Math.round(nh * scale);

                canvas.width = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(photoPreview, 0, 0, w, h);

                const u = Math.max(1, Math.min(w, h) / 480);
                const rawVal = openingInput.value.trim();
                const isManualRevised = Boolean(measurement && rawVal !== '' && rawVal !== String(measurement.estimatedMm));
                const displayVal = rawVal !== '' ? rawVal : (measurement ? String(measurement.estimatedMm) : '');

                if (measurement) {
                    const topY = measurement.topNorm * h;
                    const bottomY = measurement.bottomNorm * h;
                    const spanY = Math.max(2 * u, bottomY - topY);
                    const cx = clamp(measurement.xCenter, 0.2, 0.8) * w;
                    const halfW = clamp((measurement.mouthHalfW || 0.14) * w, w * 0.09, w * 0.26);
                    const leftX = clamp(cx - halfW, 14 * u, w - 40 * u);
                    const rightX = clamp(cx + halfW, leftX + 36 * u, w - 68 * u);
                    const caliperX = clamp(rightX + 14 * u, rightX + 8 * u, w - 54 * u);

                    // Subtle clinical measurement band across mouth opening
                    ctx.fillStyle = 'rgba(13, 110, 110, 0.16)';
                    ctx.fillRect(leftX, topY, rightX - leftX, spanY);

                    // Upper & lower incisal boundary lines (double-stroked for contrast on any skin/tooth tone)
                    [topY, bottomY].forEach(function (lineY) {
                        ctx.beginPath();
                        ctx.moveTo(leftX, lineY);
                        ctx.lineTo(caliperX + 6 * u, lineY);
                        ctx.lineWidth = 4 * u;
                        ctx.strokeStyle = 'rgba(7, 41, 42, 0.78)';
                        ctx.stroke();

                        ctx.beginPath();
                        ctx.moveTo(leftX, lineY);
                        ctx.lineTo(caliperX + 6 * u, lineY);
                        ctx.lineWidth = 2 * u;
                        ctx.strokeStyle = '#FFFFFF';
                        ctx.stroke();
                    });

                    // Vertical caliper bar beside mouth (keeps teeth unobstructed)
                    ctx.beginPath();
                    ctx.moveTo(caliperX, topY);
                    ctx.lineTo(caliperX, bottomY);
                    ctx.lineWidth = 4 * u;
                    ctx.strokeStyle = 'rgba(7, 41, 42, 0.78)';
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(caliperX, topY);
                    ctx.lineTo(caliperX, bottomY);
                    ctx.lineWidth = 2 * u;
                    ctx.strokeStyle = '#FFFFFF';
                    ctx.stroke();

                    // Small ticks at top and bottom of caliper
                    const tick = 5 * u;
                    [topY, bottomY].forEach(function (ty) {
                        ctx.beginPath();
                        ctx.moveTo(caliperX - tick, ty);
                        ctx.lineTo(caliperX + tick, ty);
                        ctx.lineWidth = 2.5 * u;
                        ctx.strokeStyle = '#FFFFFF';
                        ctx.stroke();
                    });

                    // Pill badge next to caliper
                    const pillLabel = (isManualRevised ? '' : '~') + displayVal + ' mm';
                    ctx.font = '600 ' + Math.round(14 * u) + 'px "Instrument Sans", system-ui, -apple-system, sans-serif';
                    const textW = ctx.measureText(pillLabel).width;
                    const pillPadX = 10 * u;
                    const pillW = textW + pillPadX * 2;
                    const pillH = 26 * u;
                    const preferRightX = caliperX + 10 * u;
                    const pillX = (preferRightX + pillW <= w - 8 * u)
                        ? preferRightX
                        : Math.max(8 * u, leftX - pillW - 10 * u);
                    const pillY = clamp(topY + spanY / 2 - pillH / 2, 8 * u, h - pillH - 54 * u);

                    drawRoundedRect(ctx, pillX, pillY, pillW, pillH, pillH / 2);
                    ctx.fillStyle = 'rgba(11, 59, 60, 0.94)';
                    ctx.fill();
                    ctx.lineWidth = 1.5 * u;
                    ctx.strokeStyle = '#FFFFFF';
                    ctx.stroke();

                    ctx.fillStyle = '#FFFFFF';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(pillLabel, pillX + pillW / 2, pillY + pillH / 2 + 0.5 * u);
                }

                // Bottom-left clinical measurement stamp (always shown on saved photo)
                if (displayVal !== '') {
                    const mainStamp = 'Bukaan mulut: ' + (measurement && !isManualRevised ? '~' : '') + displayVal + ' mm';
                    const subStamp = measurement
                        ? (isManualRevised ? 'Estimasi foto · direvisi manual' : 'Estimasi kamera · kalibrasi iris')
                        : 'Pengukuran mandiri E2';

                    const mainFont = '600 ' + Math.round(13 * u) + 'px "Instrument Sans", system-ui, -apple-system, sans-serif';
                    const subFont = '500 ' + Math.round(11 * u) + 'px "Instrument Sans", system-ui, -apple-system, sans-serif';

                    ctx.font = mainFont;
                    const w1 = ctx.measureText(mainStamp).width;
                    ctx.font = subFont;
                    const w2 = ctx.measureText(subStamp).width;

                    const padX = 12 * u;
                    const padY = 8 * u;
                    const boxW = Math.min(w - 20 * u, Math.max(w1, w2) + padX * 2);
                    const boxH = 38 * u;
                    const boxX = 10 * u;
                    const boxY = h - boxH - 10 * u;

                    drawRoundedRect(ctx, boxX, boxY, boxW, boxH, 9 * u);
                    ctx.fillStyle = 'rgba(11, 59, 60, 0.88)';
                    ctx.fill();
                    ctx.lineWidth = 1 * u;
                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.28)';
                    ctx.stroke();

                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'top';
                    ctx.font = mainFont;
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillText(mainStamp, boxX + padX, boxY + padY - 1 * u);

                    ctx.font = subFont;
                    ctx.fillStyle = '#CFE6E6';
                    ctx.fillText(subStamp, boxX + padX, boxY + padY + 15 * u);
                }

                return canvas.toDataURL('image/jpeg', 0.9);
            }

            // Final submit validation
            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
                    event.preventDefault();
                    return;
                }

                const openingErr = validateOpening();
                setOpeningError(openingErr);
                if (openingErr) {
                    event.preventDefault();
                    showStep(4, false);
                    openingInput.focus();
                    return;
                }

                // Bake measurement caliper + clinical stamp onto the submitted photo copy
                const hasAnyPhoto = Boolean(hiddenPhotoInput.value || (filePhotoInput.files && filePhotoInput.files.length > 0));
                if (hasAnyPhoto && !photoAlreadyAnnotated) {
                    try {
                        const annotated = renderAnnotatedPhotoDataUrl();
                        if (annotated) {
                            hiddenPhotoInput.value = annotated;
                            filePhotoInput.value = '';
                            photoAlreadyAnnotated = true;
                        }
                    } catch (e) {
                        // Fall back to submitting the original photo unchanged
                    }
                }

                isSubmitting = true;
                submitBtn.setAttribute('aria-disabled', 'true');
                submitLabel.textContent = 'Menganalisis hasil…';
            });

            window.addEventListener('beforeunload', function (event) {
                if (isDirty && !isSubmitting) {
                    event.preventDefault();
                    event.returnValue = '';
                }
            });

            window.addEventListener('pageshow', function () {
                isSubmitting = false;
                submitBtn.setAttribute('aria-disabled', 'false');
                submitLabel.textContent = 'Lihat hasil diagnosis';
            });

            // Auto-refresh CSRF token every 10 minutes to prevent 419 errors
            setInterval(async function () {
                try {
                    const response = await fetch('{{ route("consultations.create") }}', { credentials: 'same-origin' });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTokenInput = doc.querySelector('input[name="_token"]');
                    const currentTokenInput = form.querySelector('input[name="_token"]');
                    if (newTokenInput && currentTokenInput) {
                        currentTokenInput.value = newTokenInput.value;
                    }
                } catch (e) {
                    // Ignore transient offline errors
                }
            }, 600000);

            // Show initial step
            showStep(currentStep, false);
            const firstServerInvalid = form.querySelector('[aria-invalid="true"]');
            if (firstServerInvalid) {
                firstServerInvalid.focus();
            }
        })();
    </script>
</body>
</html>
