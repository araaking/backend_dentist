<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosis #{{ $consultation->id }} — Klinik Dentist · Self-Diagnosis TMD</title>
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
            --teal-100: #CFE6E6;
            --teal-50:  #E9F4F4;

            --slate-900: #0F172A;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-400: #78869B;
            --slate-200: #E2E8F0;
            --slate-100: #F4F7F8;
            --white:     #FFFFFF;

            --emerald-800: #065F46;
            --emerald-200: #A7F3D0;
            --emerald-50:  #ECFDF5;

            --amber-800: #92400E;
            --amber-200: #FDE68A;
            --amber-50:  #FFFBEB;

            --color-bg-page:         var(--slate-100);
            --color-bg-surface:      var(--white);
            --color-bg-subtle:       var(--slate-100);
            --color-bg-brand:        var(--teal-900);

            --color-text-primary:    var(--slate-900);
            --color-text-secondary:  var(--slate-600);
            --color-text-strong:     var(--slate-700);
            --color-text-on-brand:   var(--white);

            --color-accent:          var(--teal-600);
            --color-accent-hover:    var(--teal-700);
            --color-accent-active:   var(--teal-900);
            --color-accent-ink:      var(--white);
            --color-accent-soft:     var(--teal-50);
            --color-accent-soft-ink: var(--teal-700);
            --color-focus:           var(--teal-600);

            --color-border-control:  var(--slate-400);
            --color-border-hover:    var(--slate-600);
            --color-border-divider:  var(--slate-200);

            --color-success-bg:      var(--emerald-50);
            --color-success-border:  var(--emerald-200);
            --color-success-text:    var(--emerald-800);

            --color-warning-bg:      var(--amber-50);
            --color-warning-border:  var(--amber-200);
            --color-warning-text:    var(--amber-800);

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
           Sticky Top Header
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
            font-variant-numeric: tabular-nums;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .btn-topbar {
            min-height: 2.75rem;
            padding-block: 0.5rem;
            padding-inline: 0.875rem;
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
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        .btn-topbar--back {
            padding-inline-start: 0.625rem;
        }

        @media (hover: hover) {
            .btn-topbar:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-border-hover);
            }
        }

        .btn-topbar:active {
            transform: scale(0.96);
        }

        .btn-topbar:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .btn-topbar svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .print-label {
            display: none;
        }

        @media (min-width: 520px) {
            .print-label {
                display: inline;
            }
        }

        /* =====================================================================
           Main Workspace
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

        .card {
            background-color: var(--color-bg-surface);
            border-radius: var(--radius-card);
            border: 1px solid transparent;
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

        /* =====================================================================
           Hero Result Summary
           ===================================================================== */
        .result-header {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--color-border-divider);
        }

        .result-meta-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem 1rem;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.6875rem;
            border-radius: var(--radius-pill);
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .status-pill svg {
            width: 0.9375rem;
            height: 0.9375rem;
            flex-shrink: 0;
        }

        .status-pill--clear {
            background-color: var(--color-success-bg);
            border: 1px solid var(--color-success-border);
            color: var(--color-success-text);
        }

        .status-pill--found {
            background-color: var(--color-accent-soft);
            border: 1px solid var(--teal-100);
            color: var(--color-accent-soft-ink);
        }

        .consult-date {
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            font-variant-numeric: tabular-nums;
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
            margin: 0;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: var(--color-text-secondary);
            max-width: 62ch;
            text-wrap: pretty;
        }

        /* Diagnosis Findings List */
        .findings-wrap {
            padding-top: 1.25rem;
        }

        .findings-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.875rem;
        }

        .finding-item {
            padding: 1rem 1.125rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            border: 1px solid var(--color-border-divider);
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .finding-item--primary {
            background-color: var(--color-accent-soft);
            border-color: var(--teal-100);
        }

        .finding-title-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .finding-name {
            margin: 0;
            font-size: 1.0625rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-primary);
        }

        .finding-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-bg-surface);
            border: 1px solid var(--teal-100);
            color: var(--color-accent-soft-ink);
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.35;
        }

        .finding-desc {
            margin: 0;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: var(--color-text-strong);
            text-wrap: pretty;
        }

        .clear-box {
            padding: 1rem 1.125rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-success-bg);
            border: 1px solid var(--color-success-border);
            color: var(--color-success-text);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .clear-box svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .clear-title {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 600;
        }

        .clear-desc {
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
            line-height: 1.55;
            text-wrap: pretty;
        }

        /* =====================================================================
           2-Column Examination Details
           ===================================================================== */
        .detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
            align-items: start;
        }

        @media (min-width: 768px) {
            .detail-grid {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 1.5rem;
            }
        }

        .section-title {
            margin: 0 0 0.25rem;
            font-size: 1.0625rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-primary);
        }

        .section-desc {
            margin: 0 0 1.125rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
        }

        .metrics-dl {
            margin: 0;
            display: grid;
            gap: 0.875rem;
        }

        .metric-box {
            padding: 0.875rem 1rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            display: flex;
            flex-direction: column;
            gap: 0.1875rem;
        }

        .metric-dt {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--color-text-secondary);
        }

        .metric-dd {
            margin: 0;
            font-size: 1.0625rem;
            font-weight: 600;
            color: var(--color-text-primary);
            font-variant-numeric: tabular-nums;
        }

        .metric-note {
            margin: 0.125rem 0 0;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }

        .photo-wrap {
            margin-top: 1rem;
        }

        .photo-label {
            margin: 0 0 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--color-text-strong);
        }

        .photo-img {
            width: 100%;
            max-width: 22rem;
            height: auto;
            max-height: 18rem;
            object-fit: cover;
            display: block;
            border-radius: var(--radius-nested);
            outline: 1px solid rgba(0, 0, 0, 0.1);
            outline-offset: -1px;
        }

        .photo-caption {
            margin: 0.375rem 0 0;
            font-size: 0.75rem;
            color: var(--color-text-secondary);
        }

        /* Palpation Score Blocks (E1 & E4) */
        .score-groups {
            display: flex;
            flex-direction: column;
            gap: 1.125rem;
        }

        .score-group-title {
            margin: 0 0 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .score-list {
            margin: 0;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-bg-subtle);
            display: grid;
            gap: 0.5rem;
        }

        .score-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--color-border-divider);
            font-size: 0.875rem;
        }

        .score-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .score-dt {
            color: var(--color-text-secondary);
        }

        .score-dd {
            margin: 0;
            font-weight: 600;
            color: var(--color-text-primary);
            font-variant-numeric: tabular-nums;
            text-align: end;
        }

        /* =====================================================================
           Collapsible Subjective Answers (<details>)
           ===================================================================== */
        .sq-details {
            border-radius: var(--radius-card);
        }

        .sq-summary {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 2.75rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
            border-radius: var(--radius-btn);
            -webkit-tap-highlight-color: transparent;
        }

        .sq-summary::-webkit-details-marker {
            display: none;
        }

        .sq-summary:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 4px;
        }

        .sq-summary-meta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--color-text-secondary);
            flex-shrink: 0;
        }

        .sq-chevron {
            width: 1.125rem;
            height: 1.125rem;
            transition-property: transform;
            transition-duration: 200ms;
            transition-timing-function: var(--ease-out-smooth);
        }

        .sq-details[open] .sq-chevron {
            transform: rotate(180deg);
        }

        @media (prefers-reduced-motion: reduce) {
            .sq-chevron {
                transition-duration: 0ms;
            }
        }

        .sq-list {
            margin: 1rem 0 0;
            padding: 1rem 0 0;
            border-top: 1px solid var(--color-border-divider);
            display: grid;
            gap: 0.75rem;
        }

        .sq-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--color-border-divider);
        }

        .sq-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        @media (min-width: 640px) {
            .sq-item {
                flex-direction: row;
                align-items: baseline;
                justify-content: space-between;
                gap: 1.5rem;
            }
        }

        .sq-q {
            font-size: 0.875rem;
            line-height: 1.45;
            color: var(--color-text-secondary);
            flex: 1;
        }

        .sq-code {
            display: inline-block;
            margin-inline-end: 0.375rem;
            font-weight: 600;
            color: var(--color-text-strong);
            font-variant-numeric: tabular-nums;
        }

        .sq-a {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-primary);
            flex-shrink: 0;
        }

        /* =====================================================================
           Bottom Action Bar & Clinical Note
           ===================================================================== */
        .action-bar {
            display: flex;
            flex-direction: column-reverse;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .action-bar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .action-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .action-group {
                flex-direction: row;
                align-items: center;
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

        @media (min-width: 640px) {
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

        @media print {
            .topbar,
            .action-bar,
            .skip-link {
                display: none;
            }
            body {
                background-color: #FFFFFF;
            }
            .card {
                box-shadow: none;
                border: 1px solid #CBD5E1;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @php
        $hasNoDiagnosis = $consultation->diagnoses->isEmpty()
            || $consultation->diagnoses->first()->name === \App\Services\TmdDiagnosisService::NONE;
        $answersByCode = $consultation->answers->keyBy('question_code');
        $openingMm = $consultation->openingMm();
        $photoPath = $consultation->photoPath();
        $e1Scores = $answersByCode->get('E1')?->value;
        $e4Scores = $answersByCode->get('E4')?->value;
        $e3Answer = $answersByCode->get('E3')?->value;
        $sqDefinitions = \App\Support\TmdQuestionnaire::sq();
        $scoreLabels = \App\Support\TmdQuestionnaire::SCORE_LABELS;
        $areaLabels = \App\Support\TmdQuestionnaire::AREA_LABELS;
    @endphp

    <a href="#main-content" class="skip-link">Langsung ke hasil diagnosis</a>

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
                    <span class="brand-sub">Konsultasi #{{ $consultation->id }}</span>
                </span>
            </a>

            <div class="topbar-actions">
                <button type="button" class="btn-topbar" onclick="window.print()" aria-label="Cetak hasil konsultasi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    <span class="print-label">Cetak</span>
                </button>

                <a href="{{ route('consultations.index') }}" class="btn-topbar btn-topbar--back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    <span>Riwayat</span>
                </a>
            </div>
        </div>
    </header>

    <main id="main-content" class="page-main">
        {{-- Kartu Utama: Ringkasan Diagnosis --}}
        <section class="card card-enter" aria-labelledby="result-heading">
            <div class="result-header">
                <div class="result-meta-row">
                    @if ($hasNoDiagnosis)
                        <span class="status-pill status-pill--clear">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Tidak ada indikasi TMD spesifik
                        </span>
                    @else
                        <span class="status-pill status-pill--found">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $consultation->diagnoses->count() }} temuan diagnosis
                        </span>
                    @endif

                    <span class="consult-date">
                        {{ optional($consultation->consultation_date)->format('d/m/Y, H:i') ?? '-' }}
                    </span>
                </div>

                <h1 id="result-heading" class="page-title">Hasil diagnosis Anda</h1>
                <p class="page-subtitle">
                    @if ($hasNoDiagnosis)
                        Evaluasi mandiri terhadap keluhan dan pemeriksaan rahang Anda telah selesai diproses.
                    @else
                        Ringkasan di bawah disusun dari jawaban kuesioner dan pemeriksaan mandiri Anda. Bawa hasil ini saat berkonsultasi dengan dokter gigi.
                    @endif
                </p>
            </div>

            <div class="findings-wrap">
                @if ($hasNoDiagnosis)
                    <div class="clear-box" role="status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <div>
                            <p class="clear-title">Tidak ditemukan diagnosis TMD spesifik</p>
                            <p class="clear-desc">
                                Berdasarkan jawaban Anda, tidak ditemukan indikasi spesifik terkait Temporomandibular Disorders (TMD). Jika Anda masih merasakan nyeri atau gangguan fungsi rahang, tetap disarankan untuk memeriksakan diri ke dokter gigi.
                            </p>
                        </div>
                    </div>
                @else
                    <ul class="findings-list">
                        @foreach ($consultation->diagnoses as $diagnosis)
                            <li class="finding-item {{ $loop->first ? 'finding-item--primary' : '' }}">
                                <div class="finding-title-row">
                                    <h2 class="finding-name">{{ $diagnosis->name }}</h2>
                                    @if ($loop->first && $consultation->diagnoses->count() > 1)
                                        <span class="finding-badge">Temuan utama</span>
                                    @endif
                                </div>
                                <p class="finding-desc">
                                    {{ \App\Models\Diagnosis::DESCRIPTIONS[$diagnosis->name] ?? 'Tidak ada deskripsi tambahan.' }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        {{-- Detail Pemeriksaan Mandiri --}}
        <div class="detail-grid">
            <section class="card card-enter" aria-labelledby="objective-heading">
                <h2 id="objective-heading" class="section-title">Pengukuran &amp; foto pemeriksaan</h2>
                <p class="section-desc">Data bukaan mulut, bunyi sendi, dan dokumentasi foto yang Anda lampirkan.</p>

                <dl class="metrics-dl">
                    @if ($openingMm !== null)
                        <div class="metric-box">
                            <dt class="metric-dt">Bukaan mulut maksimal (E2)</dt>
                            <dd class="metric-dd">{{ $openingMm }} mm</dd>
                            <p class="metric-note">
                                {{ is_numeric($openingMm) && (float) $openingMm < 35 ? 'Di bawah batas referensi 35 mm (keterbatasan bukaan mulut).' : 'Dalam rentang referensi >= 35 mm.' }}
                            </p>
                        </div>
                    @endif

                    @if (is_string($e3Answer) && $e3Answer !== '')
                        <div class="metric-box">
                            <dt class="metric-dt">Bunyi sendi rahang (E3)</dt>
                            <dd class="metric-dd">{{ $e3Answer }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($openingMm === null && (!is_string($e3Answer) || $e3Answer === '') && !$photoPath)
                    <p class="section-desc" style="margin-bottom: 0;">Tidak ada data pengukuran atau foto pada konsultasi ini.</p>
                @endif

                @if ($photoPath)
                    <div class="photo-wrap">
                        <p class="photo-label">Foto mulut terbuka</p>
                        <img
                            src="{{ route('consultations.photo', $consultation) }}"
                            alt="Foto mulut terbuka untuk konsultasi #{{ $consultation->id }}"
                            class="photo-img"
                        >
                        <p class="photo-caption">Foto disimpan di penyimpanan privat dan hanya dapat diakses oleh Anda serta admin klinik.</p>
                    </div>
                @endif
            </section>

            <section class="card card-enter" aria-labelledby="palpation-heading">
                <h2 id="palpation-heading" class="section-title">Skor nyeri tekan</h2>
                <p class="section-desc">Hasil evaluasi nyeri saat menekan titik otot dan sendi rahang.</p>

                <div class="score-groups">
                    <div>
                        <h3 class="score-group-title">E1 · Nyeri tekan ringan</h3>
                        <dl class="score-list">
                            @foreach (\App\Support\TmdQuestionnaire::AREAS as $area)
                                @php
                                    $val = is_array($e1Scores) ? ($e1Scores[$area] ?? null) : null;
                                    $label = ($val !== null && isset($scoreLabels[(int) $val])) ? $scoreLabels[(int) $val] : '—';
                                @endphp
                                <div class="score-row">
                                    <dt class="score-dt">{{ $areaLabels[$area] ?? $area }}</dt>
                                    <dd class="score-dd">{{ $label }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>

                    <div>
                        <h3 class="score-group-title">E4 · Nyeri tekan lebih dalam</h3>
                        <dl class="score-list">
                            @foreach (\App\Support\TmdQuestionnaire::AREAS as $area)
                                @php
                                    $val = is_array($e4Scores) ? ($e4Scores[$area] ?? null) : null;
                                    $label = ($val !== null && isset($scoreLabels[(int) $val])) ? $scoreLabels[(int) $val] : '—';
                                @endphp
                                <div class="score-row">
                                    <dt class="score-dt">{{ $areaLabels[$area] ?? $area }}</dt>
                                    <dd class="score-dd">{{ $label }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </section>
        </div>

        {{-- Rincian Jawaban Kuesioner Subjektif (Collapsible) --}}
        <section class="card card-enter" aria-label="Rincian jawaban pertanyaan subjektif">
            <details class="sq-details">
                <summary class="sq-summary">
                    <span>Rincian jawaban kuesioner (SQ1–SQ14)</span>
                    <span class="sq-summary-meta">
                        <span>Tampilkan semua</span>
                        <svg class="sq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </span>
                </summary>

                <dl class="sq-list">
                    @foreach ($sqDefinitions as $code => $question)
                        @php
                            $ans = $answersByCode->get($code)?->value;
                            $optionLabels = \App\Support\TmdQuestionnaire::WEB_OPTION_LABELS[$code] ?? [];
                            $displayAns = is_string($ans) && $ans !== '' ? ($optionLabels[$ans] ?? $ans) : '—';
                        @endphp
                        <div class="sq-item">
                            <dt class="sq-q">
                                <span class="sq-code">{{ $code }}</span>{{ $question['text'] }}
                            </dt>
                            <dd class="sq-a">{{ $displayAns }}</dd>
                        </div>
                    @endforeach
                </dl>
            </details>
        </section>

        {{-- Aksi Lanjutan --}}
        <div class="action-bar">
            <div class="action-group">
                <a href="{{ route('consultations.index') }}" class="btn-secondary">
                    <span>Lihat semua riwayat</span>
                </a>
                <a href="{{ route('consultations.create') }}" class="btn-secondary">
                    <span>Diagnosis baru</span>
                </a>
            </div>

            <a href="{{ route('dashboard') }}" class="btn-primary">
                <span>Kembali ke dashboard</span>
            </a>
        </div>
    </main>

    <footer class="page-footer">
        <p class="clinical-note">
            Hasil skrining mandiri bersifat informatif dan tidak menggantikan pemeriksaan langsung oleh dokter gigi.
        </p>
    </footer>
</body>
</html>
