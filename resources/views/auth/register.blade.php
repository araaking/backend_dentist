@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Register</h1>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded-lg" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label for="date_of_birth" class="block text-gray-700">Tanggal Lahir</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="w-full px-3 py-2 border rounded-lg" value="{{ old('date_of_birth') }}" required>
        </div>
        <div class="mb-4">
            <label for="gender" class="block text-gray-700">Jenis Kelamin</label>
            <select name="gender" id="gender" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="Male">Laki-laki</option>
                <option value="Female">Perempuan</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="phone_number" class="block text-gray-700">Nomor Telepon</label>
            <input type="text" name="phone_number" id="phone_number" class="w-full px-3 py-2 border rounded-lg" value="{{ old('phone_number') }}" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg" value="{{ old('email') }}" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Register</button>
    </form>
</div>
@endsection
