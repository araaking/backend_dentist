@extends('layouts.app')

@section('title', 'Hasil Diagnosis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center mb-4">Hasil Diagnosis Anda</h1>
        <p class="text-center text-gray-600 mb-8">Konsultasi pada: {{ $consultation->consultation_date->format('d F Y, H:i') }}</p>

        <div class="bg-gray-50 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Detail Pemeriksaan</h2>
            
            @php
                $e2Answer = $consultation->answers->where('question_code', 'E2')->first();
                $e2Data = $e2Answer ? json_decode($e2Answer->answer, true) : null;
            @endphp
            
            @if($e2Data && isset($e2Data['photo_path']))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Foto Mulut Terbuka</h3>
                    <img src="{{ asset('storage/' . $e2Data['photo_path']) }}" alt="Foto Mulut Terbuka" class="max-w-md rounded-lg shadow-md">
                </div>
            @endif
            
            @if($e2Data && isset($e2Data['opening_mm']))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Bukaan Mulut Maksimal</h3>
                    <p class="text-gray-700">{{ $e2Data['opening_mm'] }} mm</p>
                </div>
            @endif
            
            <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Diagnosis yang Ditemukan</h2>
            
            @if($consultation->diagnoses->isEmpty() || $consultation->diagnoses->first()->name === 'No specific TMD diagnosis found.')
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                    <p class="font-bold">Informasi</p>
                    <p>Berdasarkan jawaban Anda, tidak ditemukan diagnosis spesifik terkait Temporomandibular Disorders (TMD). Jika Anda masih merasakan gejala, disarankan untuk berkonsultasi dengan dokter gigi spesialis.</p>
                </div>
            @else
                <ul class="list-disc list-inside space-y-4">
                    @foreach ($consultation->diagnoses as $diagnosis)
                        <li>
                            <strong class="text-xl text-indigo-700">{{ $diagnosis->name }}</strong>
                            <p class="text-gray-700 ml-5">{{ \App\Models\Diagnosis::DESCRIPTIONS[$diagnosis->name] ?? 'Tidak ada deskripsi.' }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition">Kembali ke Dashboard</a>
            <a href="{{ route('consultations.index') }}" class="ml-4 bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition">Lihat Riwayat</a>
        </div>
    </div>
</div>
@endsection
