@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    <p>Welcome, {{ auth()->user()->patient->name }}!</p>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="mt-4 bg-red-500 text-white py-2 px-4 rounded-lg">Logout</button>
    </form>
</div>
@endsection
