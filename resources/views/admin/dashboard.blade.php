@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
                    Admin Dashboard
                </h1>
                <p class="text-gray-600 mt-2">Statistik keseluruhan data klinik dentist</p>
            </div>
            <a href="{{ route('admin.patients.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                <i class="fas fa-users mr-2"></i>Lihat Semua Pasien
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Patients Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Pasien</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPatients }}</p>
                </div>
            </div>
        </div>

        <!-- New Patients This Month Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Pasien Baru Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $newPatientsThisMonth }}</p>
                </div>
            </div>
        </div>

        <!-- Total Consultations Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-comments text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Konsultasi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalConsultations }}</p>
                </div>
            </div>
        </div>

        <!-- Total Diagnoses Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-stethoscope text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Diagnosis</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalDiagnoses }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Latest Patients Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-users mr-2 text-blue-600"></i>
                    10 Pasien Terbaru
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($latestPatients as $patient)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $patient->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $patient->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Belum ada data pasien</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Diagnoses Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-stethoscope mr-2 text-red-600"></i>
                    5 Diagnosis Terbanyak
                </h2>
            </div>
            <div class="p-6">
                @forelse ($diagnosisPercentages as $diagnosis)
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $diagnosis['name'] }}</span>
                            <span class="text-sm text-gray-500">{{ $diagnosis['count'] }} ({{ $diagnosis['percentage'] }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $diagnosis['percentage'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">Belum ada data diagnosis</p>
                @endforelse
            </div>
        </div>

        <!-- Most Active Patients Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                    5 Pasien Teraktif
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Konsultasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($mostActivePatients as $patient)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $patient->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $patient->consultation_count }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Belum ada data konsultasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Most Answered Questions Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-question-circle mr-2 text-purple-600"></i>
                    10 Pertanyaan Terpopuler
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Jawaban</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($mostAnsweredQuestions as $question)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="font-medium">[{{ $question->code }}]</span> 
                                        {{ Str::limit($question->question_text, 80) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $question->answer_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-sm text-gray-500">Belum ada data jawaban</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Average Consultations -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-line mr-2 text-green-600"></i>
                Statistik Konsultasi
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Rata-rata Konsultasi per Pasien:</span>
                    <span class="text-lg font-bold text-gray-800">{{ round($avgConsultationsPerPatient, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Answer Distribution by Type -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-pie mr-2 text-indigo-600"></i>
                Distribusi Jawaban per Tipe
            </h2>
            <div class="space-y-3">
                @forelse ($answerDistributionByType as $distribution)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $distribution->type }}:</span>
                        <span class="text-lg font-bold text-gray-800">{{ $distribution->answer_count }}</span>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">Belum ada data distribusi jawaban</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection