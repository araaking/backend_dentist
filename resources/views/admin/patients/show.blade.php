@extends('admin.layouts.app')

@section('title', 'Detail Pasien')

@push('styles')
<style>
    .info-dl {
        margin: 0;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 0.875rem;
    }

    @media (min-width: 640px) {
        .info-dl {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .info-dl {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    .info-item {
        padding: 0.875rem 1rem;
        border-radius: var(--radius-nested);
        background-color: var(--color-bg-subtle);
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .info-dt {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--color-text-secondary);
    }

    .info-dd {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--color-text-primary);
        font-variant-numeric: tabular-nums;
        overflow-wrap: anywhere;
    }

    .kpi-value--text {
        font-size: 1rem;
        line-height: 1.35;
    }

    .consult-card-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .consult-item {
        padding: 1.125rem;
        border-radius: var(--radius-nested);
        border: 1px solid var(--color-border-divider);
        background-color: var(--color-bg-surface);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .consult-item-header {
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
        padding-bottom: 0.875rem;
        border-bottom: 1px solid var(--color-border-divider);
    }

    @media (min-width: 640px) {
        .consult-item-header {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .consult-item-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--color-text-primary);
        font-variant-numeric: tabular-nums;
    }

    .consult-item-id {
        margin: 0.125rem 0 0;
        font-size: 0.8125rem;
        color: var(--color-text-secondary);
        font-variant-numeric: tabular-nums;
    }

    .consult-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
    }

    .sub-block-title {
        margin: 0 0 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--color-text-strong);
    }

    .patient-photo-link {
        display: inline-block;
        border-radius: var(--radius-nested);
    }

    .patient-photo-link:focus-visible {
        outline: 2px solid var(--color-focus);
        outline-offset: 2px;
    }

    .patient-photo {
        width: 8.5rem;
        height: 8.5rem;
        object-fit: cover;
        display: block;
        border-radius: var(--radius-nested);
        outline: 1px solid rgba(0, 0, 0, 0.1);
        outline-offset: -1px;
    }

    .diag-list {
        display: grid;
        gap: 0.5rem;
    }

    .diag-row {
        padding: 0.625rem 0.875rem;
        border-radius: 0.5rem;
        background-color: var(--color-bg-subtle);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .exam-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 0.625rem;
    }

    @media (min-width: 640px) {
        .exam-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .exam-card {
        padding: 0.75rem 0.875rem;
        border-radius: 0.5rem;
        background-color: var(--color-bg-subtle);
        font-size: 0.875rem;
    }

    .answers-details {
        border-top: 1px solid var(--color-border-divider);
        padding-top: 0.75rem;
    }

    .answers-summary {
        list-style: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-height: 2.5rem;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--color-accent-hover);
        border-radius: 0.375rem;
        -webkit-tap-highlight-color: transparent;
    }

    .answers-summary::-webkit-details-marker {
        display: none;
    }

    .answers-summary:focus-visible {
        outline: 2px solid var(--color-focus);
        outline-offset: 2px;
    }

    .answers-grid {
        margin-top: 0.75rem;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 0.5rem;
    }

    @media (min-width: 640px) {
        .answers-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .answer-item {
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        background-color: var(--color-bg-subtle);
        font-size: 0.8125rem;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }
</style>
@endpush

@section('content')
    <header class="page-header">
        <div>
            <h1 class="page-title">{{ $patient->name }}</h1>
            <p class="page-subtitle">Detail informasi pasien, ringkasan statistik, dan riwayat konsultasi TMD.</p>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 0.625rem;">
            <a href="{{ route('admin.patients.index') }}" class="btn-secondary">Daftar pasien</a>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </header>

    {{-- Informasi Pasien --}}
    <section class="card" aria-labelledby="patient-info-heading">
        <h2 id="patient-info-heading" class="section-title" style="margin-bottom: 1rem;">Informasi pasien</h2>
        <dl class="info-dl">
            <div class="info-item">
                <dt class="info-dt">ID pasien</dt>
                <dd class="info-dd">{{ $patient->patient_id ?: '-' }}</dd>
            </div>
            <div class="info-item">
                <dt class="info-dt">Nama lengkap</dt>
                <dd class="info-dd">{{ $patient->name }}</dd>
            </div>
            <div class="info-item">
                <dt class="info-dt">Tanggal lahir</dt>
                <dd class="info-dd">{{ $patient->birth_date_label }}</dd>
            </div>
            <div class="info-item">
                <dt class="info-dt">Jenis kelamin</dt>
                <dd class="info-dd">{{ $patient->gender_label }}</dd>
            </div>
            <div class="info-item">
                <dt class="info-dt">Nomor telepon</dt>
                <dd class="info-dd">{{ $patient->phone_number ?: '-' }}</dd>
            </div>
            <div class="info-item">
                <dt class="info-dt">Email pengguna</dt>
                <dd class="info-dd">{{ $patient->user ? $patient->user->email : '-' }}</dd>
            </div>
        </dl>
    </section>

    {{-- Statistik Pasien --}}
    <section class="kpi-grid" aria-label="Statistik konsultasi pasien">
        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </span>
            <div>
                <p class="kpi-label">Total konsultasi</p>
                <p class="kpi-value">{{ $totalConsultations }}</p>
            </div>
        </article>

        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </span>
            <div>
                <p class="kpi-label">Rata-rata jawaban</p>
                <p class="kpi-value">{{ round($avgAnswersPerConsultation, 1) }}</p>
            </div>
        </article>

        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </span>
            <div style="min-width: 0;">
                <p class="kpi-label">Jawaban tersering</p>
                <p class="kpi-value kpi-value--text">{{ $mostCommonAnswers->first()->answer ?? '-' }}</p>
            </div>
        </article>

        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
            </span>
            <div style="min-width: 0;">
                <p class="kpi-label">Diagnosis terbanyak</p>
                <p class="kpi-value kpi-value--text">{{ $mostCommonDiagnoses->first()->name ?? '-' }}</p>
            </div>
        </article>
    </section>

    {{-- 5 Jawaban & 5 Diagnosis Tersering --}}
    <div class="grid-2">
        <section class="card card--flush" aria-labelledby="common-answers-heading">
            <div class="card-header-bar">
                <div>
                    <h2 id="common-answers-heading" class="section-title">5 jawaban paling sering</h2>
                    <p class="section-subtitle">Distribusi jawaban kuesioner pada konsultasi pasien ini.</p>
                </div>
            </div>
            <div class="card-body">
                @if ($mostCommonAnswers->isNotEmpty())
                    @php $maxAnswerCount = max(1, (int) $mostCommonAnswers->first()->count); @endphp
                    <div class="bar-list">
                        @foreach ($mostCommonAnswers as $answer)
                            <div>
                                <div class="bar-meta">
                                    <span class="bar-label">{{ $answer->answer }}</span>
                                    <span class="bar-count">{{ $answer->count }} kali</span>
                                </div>
                                <div class="bar-track" aria-hidden="true">
                                    <div class="bar-fill" style="width: {{ min(100, ($answer->count / $maxAnswerCount) * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="empty-cell" style="padding: 0.75rem 0;">Belum ada data jawaban.</p>
                @endif
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="common-diagnoses-heading">
            <div class="card-header-bar">
                <div>
                    <h2 id="common-diagnoses-heading" class="section-title">5 diagnosis paling sering</h2>
                    <p class="section-subtitle">Frekuensi temuan diagnosis pada konsultasi pasien ini.</p>
                </div>
            </div>
            <div class="card-body">
                @if ($mostCommonDiagnoses->isNotEmpty())
                    @php $maxDiagnosisCount = max(1, (int) $mostCommonDiagnoses->first()->count); @endphp
                    <div class="bar-list">
                        @foreach ($mostCommonDiagnoses as $diagnosis)
                            <div>
                                <div class="bar-meta">
                                    <span class="bar-label">{{ $diagnosis->name }}</span>
                                    <span class="bar-count">{{ $diagnosis->count }} kali</span>
                                </div>
                                <div class="bar-track" aria-hidden="true">
                                    <div class="bar-fill" style="width: {{ min(100, ($diagnosis->count / $maxDiagnosisCount) * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="empty-cell" style="padding: 0.75rem 0;">Belum ada data diagnosis.</p>
                @endif
            </div>
        </section>
    </div>

    {{-- Riwayat Konsultasi --}}
    <section class="card card--flush" aria-labelledby="patient-history-heading">
        <div class="card-header-bar">
            <div>
                <h2 id="patient-history-heading" class="section-title">Riwayat konsultasi</h2>
                <p class="section-subtitle">Daftar seluruh sesi evaluasi beserta foto, diagnosis, dan jawabannya.</p>
            </div>
        </div>

        <div class="card-body">
            @if ($consultations->isNotEmpty())
                <div class="consult-card-list">
                    @foreach ($consultations as $consultation)
                        <article class="consult-item" aria-labelledby="consult-title-{{ $consultation->id }}">
                            <div class="consult-item-header">
                                <div>
                                    <h3 id="consult-title-{{ $consultation->id }}" class="consult-item-title">
                                        Konsultasi — {{ optional($consultation->consultation_date)->format('d/m/Y') ?? '-' }}
                                    </h3>
                                    <p class="consult-item-id">ID Konsultasi #{{ $consultation->id }}</p>
                                </div>
                                <div class="consult-badges">
                                    <span class="pill pill--teal">{{ $consultation->answers->count() }} jawaban</span>
                                    <span class="pill pill--neutral">{{ $consultation->diagnoses->count() }} diagnosis</span>
                                    @if ($consultation->examinationResults->isNotEmpty())
                                        <span class="pill pill--neutral">{{ $consultation->examinationResults->count() }} pemeriksaan</span>
                                    @endif
                                </div>
                            </div>

                            @if ($consultation->notes)
                                <p style="margin: 0; font-size: 0.875rem; color: var(--color-text-strong);">
                                    <strong>Catatan:</strong> {{ $consultation->notes }}
                                </p>
                            @endif

                            @if ($consultation->photoPath())
                                <div>
                                    <p class="sub-block-title">Foto mulut terbuka</p>
                                    <a
                                        href="{{ route('consultations.photo', $consultation) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="patient-photo-link"
                                        aria-label="Buka foto mulut terbuka untuk konsultasi #{{ $consultation->id }} di tab baru"
                                    >
                                        <img
                                            src="{{ route('consultations.photo', $consultation) }}"
                                            alt="Foto mulut terbuka konsultasi #{{ $consultation->id }}"
                                            class="patient-photo"
                                            loading="lazy"
                                        >
                                    </a>
                                </div>
                            @endif

                            @if ($consultation->diagnoses->isNotEmpty())
                                <div>
                                    <p class="sub-block-title">Diagnosis</p>
                                    <div class="diag-list">
                                        @foreach ($consultation->diagnoses as $diagnosis)
                                            <div class="diag-row">
                                                <span>{{ $diagnosis->name }}</span>
                                                @if ($diagnosis->is_confirmed)
                                                    <span class="pill pill--success">Dikonfirmasi</span>
                                                @else
                                                    <span class="pill pill--warning">Belum dikonfirmasi</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($consultation->examinationResults->isNotEmpty())
                                <div>
                                    <p class="sub-block-title">Hasil pemeriksaan</p>
                                    <div class="exam-grid">
                                        @foreach ($consultation->examinationResults as $exam)
                                            <div class="exam-card">
                                                <div style="display: flex; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.25rem;">
                                                    <strong>[{{ $exam->question_code }}]</strong>
                                                    <span style="font-weight: 600; font-variant-numeric: tabular-nums;">{{ $exam->score }}</span>
                                                </div>
                                                <div>{{ $exam->result }}</div>
                                                @if ($exam->location)
                                                    <div style="font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 0.125rem;">
                                                        Lokasi: {{ $exam->location }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($consultation->answers->isNotEmpty())
                                <details class="answers-details">
                                    <summary class="answers-summary">
                                        <span>Lihat rincian {{ $consultation->answers->count() }} jawaban kuesioner</span>
                                    </summary>
                                    <div class="answers-grid">
                                        @foreach ($consultation->answers as $answer)
                                            <div class="answer-item">
                                                <strong>[{{ $answer->question_code }}]</strong>
                                                {{ $answer->answer }}
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p class="empty-cell" style="padding: 1rem 0;">Pasien ini belum memiliki riwayat konsultasi.</p>
            @endif
        </div>
    </section>
@endsection
