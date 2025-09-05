@extends('layouts.app')

@section('title', 'Self Diagnosis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Formulir Self Diagnosis TMD</h1>

    <form action="{{ route('consultations.store') }}" method="POST" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf

        <h2 class="text-2xl font-semibold mb-6 border-b pb-2">Pertanyaan Subjektif (SQ)</h2>

        {{-- SQ1 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ1'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ1]" value="Ya" class="mr-2"> Ya
                <input type="radio" name="sq[SQ1]" value="Tidak" class="ml-4 mr-2" checked> Tidak
            </div>
        </div>

        {{-- SQ2 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ2'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ2]" value="< 1 minggu" class="mr-2" checked> Kurang dari 1 minggu
                <input type="radio" name="sq[SQ2]" value=">= 1 minggu" class="ml-4 mr-2"> 1 minggu atau lebih
            </div>
        </div>

        {{-- SQ3 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ3'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ3]" value="Ya" class="mr-2"> Ya (Persisten/Kambuhan)
                <input type="radio" name="sq[SQ3]" value="Tidak" class="ml-4 mr-2" checked> Tidak
            </div>
        </div>

        {{-- SQ4 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ4'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ4]" value="Ya" class="mr-2"> Ya
                <input type="radio" name="sq[SQ4]" value="Tidak" class="ml-4 mr-2" checked> Tidak
            </div>
        </div>

        {{-- SQ5 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ5'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ5]" value="Ya" class="mr-2"> Ya
                <input type="radio" name="sq[SQ5]" value="Tidak" class="ml-4 mr-2" checked> Tidak
            </div>
        </div>

        {{-- SQ6 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ6'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ6]" value="< 1x/minggu" class="mr-2" checked> Kurang dari 1x/minggu
                <input type="radio" name="sq[SQ6]" value=">= 1x/minggu" class="ml-4 mr-2"> 1x/minggu atau lebih
            </div>
        </div>

        {{-- SQ7 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ7'] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ7]" value="Ya" class="mr-2"> Ya
                <input type="radio" name="sq[SQ7]" value="Tidak" class="ml-4 mr-2" checked> Tidak
            </div>
        </div>

        {{-- SQ8 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ8'] }}</label>
            <select name="sq[SQ8]" class="w-full px-3 py-2 border rounded-lg">
                <option value="Tidak ada">Tidak ada</option>
                <option value="Sesekali">Sesekali</option>
                <option value="Sering">Sering</option>
            </select>
        </div>

        {{-- SQ9 to SQ14 --}}
        @foreach (range(9, 14) as $i)
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $sq_questions['SQ'.$i] }}</label>
            <div class="flex items-center">
                <input type="radio" name="sq[SQ{{$i}}]" value="Ya" class="mr-2"> Ya
                <input type="radio" name="sq[SQ{{$i}}]" value="Tidak" class="ml-4 mr-2" checked> Tidak
            </div>
        </div>
        @endforeach


        <h2 class="text-2xl font-semibold mt-10 mb-6 border-b pb-2">Pemeriksaan Mandiri (EQ)</h2>
        <p class="text-gray-600 mb-6">Lakukan instruksi berikut dan catat hasilnya.</p>

        {{-- E1 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_questions['E1'] }}</label>
            <div class="grid grid-cols-3 gap-4">
                <div><input type="checkbox" name="eq[E1][Temporalis]" value="1" class="mr-2"> Temporalis</div>
                <div><input type="checkbox" name="eq[E1][Masseter]" value="1" class="mr-2"> Masseter</div>
                <div><input type="checkbox" name="eq[E1][TMJ]" value="1" class="mr-2"> Sendi TMJ</div>
            </div>
        </div>

        {{-- E2 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2" for="e2_opening">{{ $eq_questions['E2'] }}</label>
            <input type="number" name="eq[E2]" id="e2_opening" class="w-full px-3 py-2 border rounded-lg" placeholder="Contoh: 40" required>
            <p class="text-sm text-gray-500 mt-1">Ukur bukaan mulut maksimal Anda dalam milimeter (mm).</p>
        </div>

        {{-- E3 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_questions['E3'] }}</label>
            <select name="eq[E3]" class="w-full px-3 py-2 border rounded-lg">
                <option value="Tidak ada">Tidak ada bunyi</option>
                <option value="Klik tunggal">Klik tunggal</option>
                <option value="Klik ganda">Klik ganda</option>
                <option value="Krepitasi kasar">Krepitasi kasar</option>
            </select>
        </div>

        {{-- E4 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_questions['E4'] }}</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="e4_temporalis" class="block text-sm font-medium text-gray-600">Temporalis</label>
                    <select name="eq[E4][Temporalis]" id="e4_temporalis" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
                <div>
                    <label for="e4_masseter" class="block text-sm font-medium text-gray-600">Masseter</label>
                    <select name="eq[E4][Masseter]" id="e4_masseter" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
                <div>
                    <label for="e4_tmj" class="block text-sm font-medium text-gray-600">Sendi TMJ</label>
                    <select name="eq[E4][TMJ]" id="e4_tmj" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <button type="submit" class="bg-blue-500 text-white py-3 px-8 rounded-lg hover:bg-blue-600 transition font-bold">Lihat Hasil Diagnosis</button>
        </div>
    </form>
</div>
@endsection
