@extends('layouts.app')

@section('title', 'Edit Profile')
@section('has_inline_errors', '1')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

    <form action="{{ route('patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $patient->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="date_of_birth" class="block text-gray-700 font-bold mb-2">Tanggal Lahir</label>
            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date_of_birth') border-red-500 @enderror">
            @error('date_of_birth')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="gender" class="block text-gray-700 font-bold mb-2">Jenis Kelamin</label>
            <select name="gender" id="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('gender') border-red-500 @enderror">
                <option value="">-</option>
                <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="phone_number" class="block text-gray-700 font-bold mb-2">Nomor Telepon</label>
            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $patient->phone_number) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone_number') border-red-500 @enderror">
            @error('phone_number')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Profile
            </button>
            <a href="{{ route('dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Kembali ke Dashboard
            </a>
        </div>
    </form>
</div>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-6 border border-red-200">
    <h2 class="text-xl font-bold text-red-700 mb-2">Hapus Akun</h2>
    <p class="text-sm text-gray-600 mb-4">
        Menghapus akun akan menghapus secara permanen profil, seluruh riwayat diagnosis, jawaban, dan foto Anda.
        Tindakan ini tidak dapat dibatalkan.
    </p>

    <form action="{{ route('account.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun Anda secara permanen? Tindakan ini tidak dapat dibatalkan.');">
        @csrf
        @method('DELETE')

        <div class="mb-4">
            <label for="delete_password" class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
            <input type="password" name="password" id="delete_password" required autocomplete="current-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Hapus Akun Saya
        </button>
    </form>
</div>
@endsection
