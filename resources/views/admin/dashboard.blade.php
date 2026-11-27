@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <header class="page-header">
        <div>
            <h1 class="page-title">Admin Dashboard</h1>
            <p class="page-subtitle">Ringkasan statistik pasien, konsultasi, jawaban kuesioner, dan temuan diagnosis klinik.</p>
        </div>

        <a href="#all-patients" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Lihat semua pasien</span>
        </a>
    </header>

    {{-- KPI Ringkasan --}}
    <section class="kpi-grid" aria-label="Ringkasan metrik klinik">
        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </span>
            <div>
                <p class="kpi-label">Total pasien</p>
                <p class="kpi-value">{{ $totalPatients }}</p>
            </div>
        </article>

        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
            </span>
            <div>
                <p class="kpi-label">Pasien baru bulan ini</p>
                <p class="kpi-value">{{ $newPatientsThisMonth }}</p>
            </div>
        </article>

        <article class="card kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
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
                <p class="kpi-label">Total diagnosis</p>
                <p class="kpi-value">{{ $totalDiagnoses }}</p>
            </div>
        </article>
    </section>

    {{-- Statistik & Tabel Ringkas --}}
    <div class="grid-2">
        <section class="card card--flush" aria-labelledby="latest-patients-heading">
            <div class="card-header-bar">
                <div>
                    <h2 id="latest-patients-heading" class="section-title">10 pasien terbaru</h2>
                    <p class="section-subtitle">Pendaftaran pasien paling baru di klinik.</p>
                </div>
            </div>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestPatients as $patient)
                            <tr>
                                <td class="td-nowrap">{{ $patient->name }}</td>
                                <td class="td-nowrap td-muted td-num">{{ $patient->created_at->format('d/m/Y') }}</td>
                                <td class="td-nowrap">
                                    <a
                                        href="{{ route('admin.patients.show', $patient->id) }}"
                                        class="table-link"
                                        aria-label="Lihat detail pasien {{ $patient->name }}"
                                    >Lihat detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-cell">Belum ada data pasien.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="top-diagnoses-heading">
            <div class="card-header-bar">
                <div>
                    <h2 id="top-diagnoses-heading" class="section-title">5 diagnosis terbanyak</h2>
                    <p class="section-subtitle">Proporsi temuan diagnosis dari seluruh konsultasi.</p>
                </div>
            </div>
            <div class="card-body">
                @if (count($diagnosisPercentages) > 0)
                    <div class="bar-list">
                        @foreach ($diagnosisPercentages as $diagnosis)
                            <div>
                                <div class="bar-meta">
                                    <span class="bar-label">{{ $diagnosis['name'] }}</span>
                                    <span class="bar-count">{{ $diagnosis['count'] }} ({{ $diagnosis['percentage'] }}%)</span>
                                </div>
                                <div class="bar-track" aria-hidden="true">
                                    <div class="bar-fill" style="width: {{ min(100, max(0, (float) $diagnosis['percentage'])) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="empty-cell" style="padding: 0.75rem 0;">Belum ada data diagnosis.</p>
                @endif
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="active-patients-heading">
            <div class="card-header-bar">
                <div>
                    <h2 id="active-patients-heading" class="section-title">5 pasien teraktif</h2>
                    <p class="section-subtitle">Pasien dengan frekuensi konsultasi terbanyak.</p>
                </div>
            </div>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama pasien</th>
                            <th>Konsultasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mostActivePatients as $patient)
                            <tr>
                                <td class="td-nowrap">{{ $patient->name }}</td>
                                <td class="td-nowrap td-num">
                                    <span class="pill pill--teal">{{ $patient->consultation_count }}</span>
                                </td>
                                <td class="td-nowrap">
                                    <a
                                        href="{{ route('admin.patients.show', $patient->id) }}"
                                        class="table-link"
                                        aria-label="Lihat detail pasien {{ $patient->name }}"
                                    >Lihat detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-cell">Belum ada data konsultasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card card--flush" aria-labelledby="popular-questions-heading">
            <div class="card-header-bar">
                <div>
                    <h2 id="popular-questions-heading" class="section-title">10 pertanyaan terpopuler</h2>
                    <p class="section-subtitle">Pertanyaan yang paling banyak tercatat pada jawaban pasien.</p>
                </div>
            </div>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mostAnsweredQuestions as $question)
                            <tr>
                                <td>
                                    <span class="pill pill--neutral" style="margin-inline-end: 0.375rem;">{{ $question->code }}</span>
                                    <span>{{ Str::limit($question->question_text, 80) }}</span>
                                </td>
                                <td class="td-nowrap td-muted td-num">{{ $question->answer_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="empty-cell">Belum ada data jawaban.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- Daftar Semua Pasien (Search + Pagination) --}}
    <section id="all-patients" class="card card--flush" aria-labelledby="all-patients-heading">
        <div class="card-header-bar">
            <div>
                <h2 id="all-patients-heading" class="section-title">Daftar semua pasien</h2>
                <p class="section-subtitle">Cari berdasarkan nama, nomor telepon, atau email pasien.</p>
            </div>

            <form method="GET" action="{{ route('admin.dashboard') }}#all-patients" class="search-bar">
                <div class="search-input-wrap">
                    <label for="dashboard-search" class="sr-only">Cari nama, telepon, atau email pasien</label>
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="search"
                        id="dashboard-search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        maxlength="100"
                        placeholder="Cari nama, telepon, atau email…"
                        class="search-input"
                    >
                </div>
                <button type="submit" class="btn-secondary">Cari</button>
                @if (!empty($search))
                    <a href="{{ route('admin.dashboard') }}#all-patients" class="btn-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama pasien</th>
                        <th>ID pasien</th>
                        <th>Tanggal daftar</th>
                        <th>Email</th>
                        <th>No. telepon</th>
                        <th>Konsultasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allPatients as $patient)
                        <tr>
                            <td class="td-nowrap">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="table-link">
                                    {{ $patient->name }}
                                </a>
                            </td>
                            <td class="td-nowrap td-num">{{ $patient->patient_id ?: '-' }}</td>
                            <td class="td-nowrap td-muted td-num">{{ $patient->created_at->format('d/m/Y') }}</td>
                            <td class="td-nowrap td-muted">{{ $patient->user ? $patient->user->email : '-' }}</td>
                            <td class="td-nowrap td-muted td-num">{{ $patient->phone_number ?: '-' }}</td>
                            <td class="td-nowrap">
                                <span class="pill pill--teal">{{ $patient->consultations_count }}</span>
                            </td>
                            <td class="td-nowrap">
                                <a
                                    href="{{ route('admin.patients.show', $patient->id) }}"
                                    class="table-link"
                                    aria-label="Lihat detail pasien {{ $patient->name }}"
                                >Lihat detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-cell">
                                {{ !empty($search) ? 'Tidak ada pasien yang cocok dengan pencarian "' . $search . '".' : 'Belum ada data pasien.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($allPatients->hasPages())
            <div class="pagination-wrap">
                {{ $allPatients->links() }}
            </div>
        @endif
    </section>

    {{-- Statistik Tambahan --}}
    <div class="grid-2">
        <section class="card" aria-labelledby="avg-consultations-heading">
            <h2 id="avg-consultations-heading" class="section-title">Statistik konsultasi</h2>
            <p class="section-subtitle" style="margin-bottom: 1rem;">Rata-rata aktivitas skrining per pasien.</p>
            <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: 0.875rem 1rem; border-radius: var(--radius-nested); background-color: var(--color-bg-subtle);">
                <span style="font-size: 0.875rem; color: var(--color-text-secondary);">Rata-rata konsultasi per pasien</span>
                <span style="font-size: 1.25rem; font-weight: 600; font-variant-numeric: tabular-nums; color: var(--color-text-primary);">
                    {{ round((float) $avgConsultationsPerPatient, 2) }}
                </span>
            </div>
        </section>

        <section class="card" aria-labelledby="answer-distribution-heading">
            <h2 id="answer-distribution-heading" class="section-title">Distribusi jawaban per tipe</h2>
            <p class="section-subtitle" style="margin-bottom: 1rem;">Perbandingan jawaban subjektif (SQ) dan objektif (EQ).</p>
            @if ($answerDistributionByType->isNotEmpty())
                <div style="display: grid; gap: 0.625rem;">
                    @foreach ($answerDistributionByType as $distribution)
                        <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: 0.75rem 1rem; border-radius: var(--radius-nested); background-color: var(--color-bg-subtle);">
                            <span style="font-size: 0.875rem; font-weight: 500; color: var(--color-text-strong);">{{ $distribution->type }}</span>
                            <span style="font-size: 1.125rem; font-weight: 600; font-variant-numeric: tabular-nums; color: var(--color-text-primary);">{{ $distribution->answer_count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="empty-cell" style="padding: 0.75rem 0;">Belum ada data distribusi jawaban.</p>
            @endif
        </section>
    </div>
@endsection
