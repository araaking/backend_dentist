@extends('layouts.app')

@section('title', 'Buat Profil Pasien')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Buat Profil Pasien</h1>
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nama</label>
            <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="date_of_birth" class="block text-gray-700">Tanggal Lahir</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div class="mb-4">
            <label for="gender" class="block text-gray-700">Jenis Kelamin</label>
            <select name="gender" id="gender" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Male">Laki-laki</option>
                <option value="Female">Perempuan</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="phone_number" class="block text-gray-700">Nomor Telepon</label>
            <input type="text" name="phone_number" id="phone_number" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Simpan</button>
    </form>
</div>
@endsection
