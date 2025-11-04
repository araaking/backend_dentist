@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold">Selamat Datang, {{ auth()->user()->patient->name ?? 'User' }}!</h1>
        <p class="text-gray-600">Apa yang ingin Anda lakukan hari ini?</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
        <!-- Self Diagnosis Card -->
        <a href="{{ route('consultations.create') }}" class="block p-6 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition text-center">
            <h2 class="text-xl font-bold mb-2">Self Diagnosis</h2>
            <p>Mulai diagnosis mandiri untuk mengetahui kondisi gigi Anda.</p>
        </a>

        <!-- Riwayat Diagnosis Card -->
        <a href="{{ route('consultations.index') }}" class="block p-6 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition text-center">
            <h2 class="text-xl font-bold mb-2">Riwayat Diagnosis</h2>
            <p>Lihat riwayat hasil diagnosis dan konsultasi Anda.</p>
        </a>

        <!-- Edit Profile Card -->
        @if(auth()->user()->patient)
        <a href="{{ route('patients.edit', auth()->user()->patient->id) }}" class="block p-6 bg-yellow-500 text-white rounded-lg shadow-md hover:bg-yellow-600 transition text-center">
            <h2 class="text-xl font-bold mb-2">Edit Profile</h2>
            <p>Perbarui informasi profil dan data diri Anda.</p>
        </a>
        @else
        <a href="{{ route('patients.create') }}" class="block p-6 bg-yellow-500 text-white rounded-lg shadow-md hover:bg-yellow-600 transition text-center">
            <h2 class="text-xl font-bold mb-2">Create Profile</h2>
            <p>Lengkapi data profil Anda terlebih dahulu.</p>
        </a>
        @endif
    </div>

    <div class="mt-8 text-center">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 text-white py-2 px-6 rounded-lg hover:bg-red-600 transition">Logout</button>
        </form>
    </div>
</div>
@endsection
