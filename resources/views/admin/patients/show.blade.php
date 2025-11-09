@extends('admin.layouts.app')

@section('title', 'Detail Pasien')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Detail Pasien
                </h1>
                <p class="text-gray-600 mt-2">Informasi lengkap dan riwayat pasien</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-id-card mr-2 text-blue-600"></i>
            Informasi Pasien
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">ID Pasien</p>
                <p class="font-medium text-gray-800">{{ $patient->patient_id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nama Lengkap</p>
                <p class="font-medium text-gray-800">{{ $patient->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Lahir</p>
                <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jenis Kelamin</p>
                <p class="font-medium text-gray-800">{{ $patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nomor Telepon</p>
                <p class="font-medium text-gray-800">{{ $patient->phone_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email Pengguna</p>
                <p class="font-medium text-gray-800">{{ $patient->user ? $patient->user->email : '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-comments text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Konsultasi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalConsultations }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Rata-rata Jawaban</p>
                    <p class="text-2xl font-bold text-gray-800">{{ round($avgAnswersPerConsultation, 1) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-comment-dots text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jawaban Terpopuler</p>
                    <p class="text-lg font-bold text-gray-800 truncate">{{ $mostCommonAnswers->first()->answer ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-stethoscope text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Diagnosis Terbanyak</p>
                    <p class="text-lg font-bold text-gray-800 truncate">{{ $mostCommonDiagnoses->first()->name ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Common Answers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-comment-dots mr-2 text-purple-600"></i>
                    5 Jawaban Paling Sering Diberikan
                </h2>
            </div>
            <div class="p-6">
                @forelse ($mostCommonAnswers as $answer)
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $answer->answer }}</span>
                            <span class="text-sm text-gray-500">{{ $answer->count }} kali</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($answer->count / $mostCommonAnswers->first()->count) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">Belum ada data jawaban</p>
                @endforelse
            </div>
        </div>

        <!-- Most Common Diagnoses -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-stethoscope mr-2 text-red-600"></i>
                    5 Diagnosis Paling Sering Diberikan
                </h2>
            </div>
            <div class="p-6">
                @forelse ($mostCommonDiagnoses as $diagnosis)
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $diagnosis->name }}</span>
                            <span class="text-sm text-gray-500">{{ $diagnosis->count }} kali</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ ($diagnosis->count / $mostCommonDiagnoses->first()->count) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">Belum ada data diagnosis</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Consultations History -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history mr-2 text-blue-600"></i>
                Riwayat Konsultasi
            </h2>
        </div>
        <div class="p-6">
            @forelse ($consultations as $consultation)
                <div class="mb-6 border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">
                                Konsultasi - {{ \Carbon\Carbon::parse($consultation->consultation_date)->format('d/m/Y') }}
                            </h3>
                            <p class="text-sm text-gray-500">ID: {{ $consultation->id }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $consultation->answers->count() }} Jawaban
                            </span>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $consultation->diagnoses->count() }} Diagnosis
                            </span>
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $consultation->examinationResults->count() }} Pemeriksaan
                            </span>
                        </div>
                    </div>

                    @if($consultation->notes)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600"><strong>Catatan:</strong> {{ $consultation->notes }}</p>
                        </div>
                    @endif

                    <!-- Answers -->
                    @if($consultation->answers->count() > 0)
                        <div class="mb-3">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Jawaban:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($consultation->answers as $answer)
                                    <div class="bg-gray-50 p-2 rounded text-sm">
                                        <span class="font-medium">[{{ $answer->question_code }}]</span> 
                                        {{ $answer->answer }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Diagnoses -->
                    @if($consultation->diagnoses->count() > 0)
                        <div class="mb-3">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Diagnosis:</h4>
                            <div class="space-y-1">
                                @foreach($consultation->diagnoses as $diagnosis)
                                    <div class="bg-red-50 p-2 rounded text-sm flex justify-between items-center">
                                        <span>{{ $diagnosis->name }}</span>
                                        @if($diagnosis->is_confirmed)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">Dikonfirmasi</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded">Belum Dikonfirmasi</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Examination Results -->
                    @if($consultation->examinationResults->count() > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Hasil Pemeriksaan:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($consultation->examinationResults as $exam)
                                    <div class="bg-purple-50 p-2 rounded text-sm">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium">[{{ $exam->question_code }}]</span>
                                            <span class="text-purple-600 font-bold">{{ $exam->score }}</span>
                                        </div>
                                        <div>{{ $exam->result }}</div>
                                        @if($exam->location)
                                            <div class="text-xs text-gray-500">Lokasi: {{ $exam->location }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-sm text-gray-500">Pasien ini belum memiliki riwayat konsultasi</p>
            @endforelse
        </div>
    </div>
</div>
@endsection