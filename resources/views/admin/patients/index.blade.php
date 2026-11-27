@extends('admin.layouts.app')

@section('title', 'Daftar Pasien')

@section('content')
    <header class="page-header">
        <div>
            <h1 class="page-title">Daftar pasien</h1>
            <p class="page-subtitle">Kelola data pasien terdaftar serta tinjau riwayat konsultasi dan diagnosis masing-masing pasien.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            <span>Kembali ke dashboard</span>
        </a>
    </header>

    <section class="card card--flush" aria-labelledby="patients-list-heading">
        <div class="card-header-bar">
            <div>
                <h2 id="patients-list-heading" class="section-title">
                    Data pasien
                    <span class="pill pill--teal" style="margin-inline-start: 0.5rem;">{{ $patients->total() }}</span>
                </h2>
                <p class="section-subtitle">Cari berdasarkan nama, nomor telepon, atau email.</p>
            </div>

            <form method="GET" action="{{ route('admin.patients.index') }}" class="search-bar">
                <div class="search-input-wrap">
                    <label for="patients-search" class="sr-only">Cari nama, telepon, atau email pasien</label>
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="search"
                        id="patients-search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        maxlength="100"
                        placeholder="Cari nama, telepon, atau email…"
                        class="search-input"
                    >
                </div>
                <button type="submit" class="btn-secondary">Cari</button>
                @if (!empty($search))
                    <a href="{{ route('admin.patients.index') }}" class="btn-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID pasien</th>
                        <th>Nama</th>
                        <th>Tanggal lahir</th>
                        <th>Jenis kelamin</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Konsultasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="td-nowrap td-num">{{ $patient->patient_id ?: '-' }}</td>
                            <td class="td-nowrap">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="table-link">
                                    {{ $patient->name }}
                                </a>
                            </td>
                            <td class="td-nowrap td-muted td-num">{{ $patient->birth_date_label }}</td>
                            <td class="td-nowrap td-muted">{{ $patient->gender_label }}</td>
                            <td class="td-nowrap td-muted td-num">{{ $patient->phone_number ?: '-' }}</td>
                            <td class="td-nowrap td-muted">{{ $patient->user ? $patient->user->email : '-' }}</td>
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
                            <td colspan="8" class="empty-cell">
                                {{ !empty($search) ? 'Tidak ada pasien yang cocok dengan pencarian "' . $search . '".' : 'Belum ada data pasien.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($patients->hasPages())
            <div class="pagination-wrap">
                {{ $patients->links() }}
            </div>
        @endif
    </section>
@endsection
