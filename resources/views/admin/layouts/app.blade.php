<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Klinik Dentist</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* =====================================================================
           Design Tokens — Primitives & Semantic Roles
           ===================================================================== */
        :root {
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

            --color-bg-page:         var(--slate-100);
            --color-bg-surface:      var(--white);
            --color-bg-subtle:       var(--slate-100);
            --color-bg-brand:        var(--teal-900);

            --color-text-primary:    var(--slate-900);
            --color-text-secondary:  var(--slate-600);
            --color-text-strong:     var(--slate-700);
            --color-text-on-brand:   var(--white);
            --color-text-placeholder: var(--slate-500);

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

            --color-error-bg:        var(--red-50);
            --color-error-border:    var(--red-200);
            --color-error-text:      var(--red-800);

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
            scroll-padding-block-start: 6.5rem;
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

        .sr-only {
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
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }

        /* =====================================================================
           Sticky Admin Shell Header (Desktop + Scrollable Mobile Sub-nav)
           ===================================================================== */
        .admin-header {
            position: sticky;
            top: 0;
            z-index: 40;
            background-color: var(--color-bg-brand);
            color: var(--color-text-on-brand);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .admin-header-inner {
            max-width: 78rem;
            margin-inline: auto;
            padding-block: 0.75rem;
            padding-inline: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .admin-header-inner {
                padding-inline: 1.5rem;
            }
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            min-width: 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            min-width: 0;
            text-decoration: none;
            color: var(--color-text-on-brand);
            border-radius: var(--radius-btn);
        }

        .brand:focus-visible {
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }

        .brand-badge {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5625rem;
            background-color: var(--teal-800);
            border: 1px solid rgba(255, 255, 255, 0.16);
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-sub {
            font-size: 0.75rem;
            line-height: 1.3;
            color: var(--teal-100);
        }

        .desktop-nav {
            display: none;
            align-items: center;
            gap: 0.375rem;
        }

        @media (min-width: 768px) {
            .desktop-nav {
                display: flex;
            }
        }

        .nav-link {
            min-height: 2.75rem;
            padding-block: 0.5rem;
            padding-inline: 0.875rem;
            border-radius: var(--radius-btn);
            color: var(--teal-100);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4375rem;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, color;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
        }

        @media (hover: hover) {
            .nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: var(--white);
            }
        }

        .nav-link[aria-current="page"] {
            background-color: rgba(255, 255, 255, 0.16);
            color: var(--white);
            font-weight: 600;
        }

        .nav-link:focus-visible {
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }

        .nav-link svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .admin-identity {
            display: none;
            font-size: 0.8125rem;
            color: var(--teal-100);
            max-width: 15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (min-width: 1024px) {
            .admin-identity {
                display: inline-block;
            }
        }

        .btn-logout {
            min-height: 2.75rem;
            padding-block: 0.5rem;
            padding-inline: 0.875rem;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: var(--radius-btn);
            background-color: rgba(255, 255, 255, 0.08);
            color: var(--white);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
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
                background-color: rgba(255, 255, 255, 0.18);
                border-color: rgba(255, 255, 255, 0.45);
            }
        }

        .btn-logout:active {
            transform: scale(0.96);
        }

        .btn-logout:focus-visible {
            outline: 2px solid var(--white);
            outline-offset: 2px;
        }

        .btn-logout svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        /* Mobile Sub-navigation (always accessible on touch viewports < 768px) */
        .mobile-nav-bar {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            overflow-x: auto;
            padding: 0 1rem 0.625rem;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .mobile-nav-bar::-webkit-scrollbar {
            display: none;
        }

        @media (min-width: 768px) {
            .mobile-nav-bar {
                display: none;
            }
        }

        /* =====================================================================
           Main Workspace Container & Shared Components
           ===================================================================== */
        .admin-main {
            flex: 1;
            width: 100%;
            max-width: 78rem;
            margin-inline: auto;
            padding-block: 1.25rem 2.5rem;
            padding-inline: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .admin-main {
                padding-block: 1.75rem 3rem;
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

        .banner svg {
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

        /* Cards */
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
                padding: 1.5rem;
            }
        }

        .card--flush {
            padding: 0;
            overflow: hidden;
        }

        .card-header-bar {
            padding: 1.125rem 1.25rem;
            border-bottom: 1px solid var(--color-border-divider);
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        @media (min-width: 640px) {
            .card-header-bar {
                padding: 1.25rem 1.5rem;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .card-body {
            padding: 1.25rem;
        }

        @media (min-width: 640px) {
            .card-body {
                padding: 1.5rem;
            }
        }

        /* Page Title & Action Header */
        .page-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .page-header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
            }
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
            margin: 0.25rem 0 0;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--color-text-secondary);
            text-wrap: pretty;
        }

        .section-title {
            margin: 0;
            font-size: 1.0625rem;
            font-weight: 600;
            line-height: 1.35;
            color: var(--color-text-primary);
        }

        .section-subtitle {
            margin: 0.1875rem 0 0;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }

        /* Buttons & Links */
        .btn-primary,
        .btn-secondary {
            min-height: 2.75rem;
            padding-block: 0.625rem;
            padding-inline: 1.125rem;
            border-radius: var(--radius-btn);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.4;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
            transition-property: background-color, border-color, transform;
            transition-duration: 150ms;
            transition-timing-function: ease-out;
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

        .btn-primary svg,
        .btn-secondary svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .table-link {
            color: var(--color-accent-hover);
            font-weight: 600;
            text-decoration: underline;
            text-decoration-thickness: from-font;
            text-underline-offset: 0.2em;
            border-radius: 0.375rem;
            padding: 0.375rem 0.5rem;
            margin-inline: -0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            -webkit-tap-highlight-color: transparent;
        }

        @media (hover: hover) {
            .table-link:hover {
                color: var(--color-accent-active);
                background-color: var(--color-accent-soft);
            }
        }

        .table-link:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        /* KPI Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.875rem;
        }

        @media (min-width: 480px) {
            .kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .kpi-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 1.25rem;
            }
        }

        .kpi-card {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .kpi-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: var(--radius-nested);
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-icon svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .kpi-label {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--color-text-secondary);
        }

        .kpi-value {
            margin: 0.125rem 0 0;
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.2;
            color: var(--color-text-primary);
            font-variant-numeric: tabular-nums;
            overflow-wrap: break-word;
        }

        /* Two Column Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
        }

        @media (min-width: 1024px) {
            .grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem;
            }
        }

        /* Tables */
        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: start;
        }

        .data-table th {
            padding: 0.75rem 1.25rem;
            background-color: var(--color-bg-subtle);
            border-bottom: 1px solid var(--color-border-divider);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--color-text-strong);
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid var(--color-border-divider);
            font-size: 0.875rem;
            color: var(--color-text-primary);
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 0;
        }

        @media (hover: hover) {
            .data-table tbody tr:hover td {
                background-color: #FAFBFB;
            }
        }

        .td-nowrap {
            white-space: nowrap;
        }

        .td-muted {
            color: var(--color-text-secondary);
        }

        .td-num {
            font-variant-numeric: tabular-nums;
        }

        .empty-cell {
            text-align: center;
            padding: 2rem 1.25rem;
            color: var(--color-text-secondary);
        }

        /* Search Bar */
        .search-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
        }

        @media (min-width: 640px) {
            .search-bar {
                width: auto;
            }
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 12rem;
        }

        @media (min-width: 640px) {
            .search-input-wrap {
                min-width: 17rem;
            }
        }

        .search-icon {
            position: absolute;
            inset-inline-start: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            color: var(--color-text-secondary);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            min-height: 2.75rem;
            padding-block: 0.5rem;
            padding-inline-start: 2.375rem;
            padding-inline-end: 0.875rem;
            font-family: inherit;
            font-size: 1rem; /* 16px prevents iOS zoom */
            line-height: 1.4;
            color: var(--color-text-primary);
            background-color: var(--color-bg-surface);
            border: 1px solid var(--color-border-control);
            border-radius: var(--radius-btn);
        }

        .search-input::placeholder {
            color: var(--color-text-placeholder);
            opacity: 1;
        }

        .search-input:focus-visible {
            border-color: var(--color-focus);
            outline: 2px solid var(--color-focus);
            outline-offset: 1px;
        }

        /* Pills & Progress Bars */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-pill);
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.3;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .pill--teal {
            background-color: var(--color-accent-soft);
            color: var(--color-accent-soft-ink);
        }

        .pill--success {
            background-color: var(--color-success-bg);
            color: var(--color-success-text);
        }

        .pill--warning {
            background-color: var(--color-warning-bg);
            color: var(--color-warning-text);
        }

        .pill--neutral {
            background-color: var(--color-bg-subtle);
            color: var(--color-text-strong);
        }

        .bar-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .bar-meta {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.375rem;
            font-size: 0.875rem;
        }

        .bar-label {
            font-weight: 500;
            color: var(--color-text-primary);
            overflow-wrap: break-word;
        }

        .bar-count {
            color: var(--color-text-secondary);
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .bar-track {
            width: 100%;
            height: 0.5rem;
            border-radius: var(--radius-pill);
            background-color: var(--color-bg-subtle);
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: var(--radius-pill);
            background-color: var(--color-accent);
        }

        /* =====================================================================
           Laravel Default Pagination Styling (compatible with tailwind.blade.php)
           ===================================================================== */
        .pagination-wrap {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--color-border-divider);
        }

        @media (min-width: 640px) {
            .pagination-wrap {
                padding: 1rem 1.5rem;
            }
        }

        .pagination-wrap nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .pagination-wrap .flex {
            display: flex;
        }

        .pagination-wrap .flex-1 {
            flex: 1 1 0%;
        }

        .pagination-wrap .items-center {
            align-items: center;
        }

        .pagination-wrap .justify-between {
            justify-content: space-between;
        }

        .pagination-wrap .hidden {
            display: none;
        }

        @media (min-width: 640px) {
            .pagination-wrap .sm\:hidden {
                display: none;
            }
            .pagination-wrap .sm\:flex {
                display: flex;
            }
            .pagination-wrap .sm\:flex-1 {
                flex: 1 1 0%;
            }
            .pagination-wrap .sm\:items-center {
                align-items: center;
            }
            .pagination-wrap .sm\:justify-between {
                justify-content: space-between;
            }
        }

        .pagination-wrap p {
            margin: 0;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            font-variant-numeric: tabular-nums;
        }

        .pagination-wrap .inline-flex {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .pagination-wrap a,
        .pagination-wrap span[aria-current="page"] > span,
        .pagination-wrap span[aria-disabled="true"] > span {
            min-width: 2.5rem;
            min-height: 2.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid var(--color-border-divider);
            background-color: var(--color-bg-surface);
            color: var(--color-text-primary);
            font-size: 0.8125rem;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-wrap span[aria-current="page"] > span {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: var(--white);
            font-weight: 600;
        }

        .pagination-wrap span[aria-disabled="true"] > span {
            opacity: 0.5;
            cursor: default;
        }

        @media (hover: hover) {
            .pagination-wrap a:hover {
                background-color: var(--color-bg-subtle);
                border-color: var(--color-border-control);
            }
        }

        .pagination-wrap a:focus-visible {
            outline: 2px solid var(--color-focus);
            outline-offset: 2px;
        }

        .pagination-wrap svg {
            width: 1rem;
            height: 1rem;
        }

        /* Footer */
        .admin-footer {
            border-top: 1px solid var(--color-border-divider);
            background-color: var(--color-bg-surface);
            padding: 1rem;
            text-align: center;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }
    </style>
    @stack('styles')
</head>
<body>
    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    <header class="admin-header">
        <div class="admin-header-inner">
            <div class="header-left">
                <a href="{{ route('admin.dashboard') }}" class="brand">
                    <span class="brand-badge" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 4c-2.2 0-4 1.8-4 4.2 0 2.6 1.2 4.3 2 7 .5 1.8 1.1 4.8 2.4 4.8 1.1 0 1.4-2.1 2.1-4.1.4-1.2 1.2-1.9 2.5-1.9s2.1.7 2.5 1.9c.7 2 1 4.1 2.1 4.1 1.3 0 1.9-3 2.4-4.8.8-2.7 2-4.4 2-7C21 5.8 19.2 4 17 4c-1.8 0-3 .8-5 .8S8.8 4 7 4Z"/>
                        </svg>
                    </span>
                    <span class="brand-text">
                        <span class="brand-name">Klinik Dentist</span>
                        <span class="brand-sub">Panel Administrator</span>
                    </span>
                </a>

                <nav class="desktop-nav" aria-label="Navigasi admin">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link"
                        @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="9" rx="1"/>
                            <rect x="14" y="3" width="7" height="5" rx="1"/>
                            <rect x="14" y="12" width="7" height="9" rx="1"/>
                            <rect x="3" y="16" width="7" height="5" rx="1"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a
                        href="{{ route('admin.patients.index') }}"
                        class="nav-link"
                        @if (request()->routeIs('admin.patients.*')) aria-current="page" @endif
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Pasien</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <span>Portal Pasien</span>
                    </a>
                </nav>
            </div>

            <div class="header-right">
                <span class="admin-identity" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
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

        <nav class="mobile-nav-bar" aria-label="Navigasi admin mobile">
            <a
                href="{{ route('admin.dashboard') }}"
                class="nav-link"
                @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
            >
                <span>Dashboard</span>
            </a>
            <a
                href="{{ route('admin.patients.index') }}"
                class="nav-link"
                @if (request()->routeIs('admin.patients.*')) aria-current="page" @endif
            >
                <span>Pasien</span>
            </a>
            <a href="{{ route('dashboard') }}" class="nav-link">
                <span>Portal Pasien</span>
            </a>
        </nav>
    </header>

    <main id="main-content" class="admin-main">
        @if (session('error'))
            <div class="banner banner-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="banner banner-success" role="status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="admin-footer">
        &copy; {{ date('Y') }} Klinik Dentist · Panel Administrasi TMD
    </footer>
</body>
</html>
