@extends('layouts.app')

@section('title', 'Riwayat Diagnosis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">Riwayat Diagnosis Anda</h1>

        @if($consultations->isEmpty())
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                <p class="font-bold">Belum ada riwayat diagnosis</p>
                <p>Anda belum melakukan diagnosis sebelumnya. <a href="{{ route('consultations.create') }}" class="underline">Mulai diagnosis sekarang</a>.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($consultations as $consultation)
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Konsultasi #{{ $consultation->id }}</h2>
                                <p class="text-gray-600">{{ $consultation->consultation_date->format('d F Y, H:i') }}</p>
                            </div>
                            <a href="{{ route('consultations.show', $consultation->id) }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                                Lihat Detail
                            </a>
                        </div>

                        <div class="border-t pt-4">
                            <h3 class="font-semibold text-gray-700 mb-2">Diagnosis:</h3>
                            @if($consultation->diagnoses->isEmpty())
                                <p class="text-gray-600">Tidak ada diagnosis spesifik ditemukan.</p>
                            @else
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($consultation->diagnoses as $diagnosis)
                                        <li class="text-gray-700">{{ $diagnosis->name }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition">Kembali ke Dashboard</a>
            @if(!$consultations->isEmpty())
                <a href="{{ route('consultations.create') }}" class="ml-4 bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition">Diagnosis Baru</a>
            @endif
        </div>
    </div>
</div>
@endsection
