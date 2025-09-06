@extends('layouts.app')

@section('title', 'Self Diagnosis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Formulir Self Diagnosis TMD</h1>

    <form action="{{ route('consultations.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
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
            <label class="block text-gray-700 font-bold mb-2">Pemeriksaan Nyeri Tekan Ringan</label>
            <p class="text-sm text-gray-600 mb-4">Tekan ringan area berikut dengan jari Anda. Pilih tingkat nyeri yang Anda rasakan:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="e1_temporalis" class="block text-sm font-medium text-gray-600">Pelipis (Temporalis)</label>
                    <select name="eq[E1][Temporalis]" id="e1_temporalis" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
                <div>
                    <label for="e1_masseter" class="block text-sm font-medium text-gray-600">Pipis (Masseter)</label>
                    <select name="eq[E1][Masseter]" id="e1_masseter" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
                <div>
                    <label for="e1_tmj" class="block text-sm font-medium text-gray-600">Sendi Rahang (TMJ)</label>
                    <select name="eq[E1][TMJ]" id="e1_tmj" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- E2 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Pengukuran Bukaan Mulut Maksimal</label>
            <div class="space-y-4">
                <div>
                    <input type="number" name="eq[E2][opening_mm]" id="e2_opening" class="w-full px-3 py-2 border rounded-lg" placeholder="Contoh: 40" required>
                    <p class="text-sm text-gray-500 mt-1">Buka mulut Anda selebar mungkin dan ukur jarak antara gigi atas dan bawah dalam milimeter (mm).</p>
                </div>
                <div>
                    <label for="e2_photo" class="block text-sm font-medium text-gray-600 mb-2">Ambil Foto Selfie Mulut Terbuka</label>
                    <div class="mt-2 p-4 border rounded-lg bg-gray-50">
                        <div class="flex justify-center">
                            <video id="video" width="320" height="240" autoplay class="border rounded-lg bg-black"></video>
                        </div>
                        <div class="flex justify-center mt-4">
                             <button id="start-camera" type="button" class="bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition font-bold mr-2">Mulai Kamera</button>
                            <button id="snap" type="button" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition font-bold" disabled>Ambil Foto</button>
                        </div>
                        <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
                        <input type="hidden" name="e2_photo" id="e2_photo">
                        <div id="photo-preview-container" class="mt-4 text-center" style="display: none;">
                            <p class="text-sm font-medium text-gray-600 mb-2">Hasil Foto:</p>
                            <img id="photo-preview" class="border rounded-lg inline-block" />
                            <button id="retake-photo" type="button" class="mt-2 text-sm text-blue-500 hover:underline">Ulangi</button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Posisikan wajah Anda di depan kamera dan klik "Ambil Foto".</p>
                </div>
            </div>
        </div>

        {{-- E3 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Pemeriksaan Bunyi Sendi Rahang</label>
            <p class="text-sm text-gray-600 mb-4">Buka dan tutup mulut Anda beberapa kali. Dengarkan apakah ada bunyi klik atau bunyi kasar:</p>
            <select name="eq[E3]" class="w-full px-3 py-2 border rounded-lg">
                <option value="Tidak ada">Tidak ada bunyi</option>
                <option value="Klik tunggal">Ada bunyi klik sekali</option>
                <option value="Klik ganda">Ada bunyi klik berulang</option>
                <option value="Krepitasi kasar">Ada bunyi kasar seperti pasir</option>
            </select>
        </div>

        {{-- E4 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Pemeriksaan Nyeri Tekan Lebih Dalam</label>
            <p class="text-sm text-gray-600 mb-4">Tekan lebih dalam pada area berikut. Beri nilai nyeri yang Anda rasakan:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="e4_temporalis" class="block text-sm font-medium text-gray-600">Pelipis (Temporalis)</label>
                    <select name="eq[E4][Temporalis]" id="e4_temporalis" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
                <div>
                    <label for="e4_masseter" class="block text-sm font-medium text-gray-600">Pipis (Masseter)</label>
                    <select name="eq[E4][Masseter]" id="e4_masseter" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        <option value="0">0 - Tidak Nyeri</option>
                        <option value="1">1 - Nyeri Ringan</option>
                        <option value="2">2 - Nyeri Sedang</option>
                        <option value="3">3 - Nyeri Berat</option>
                    </select>
                </div>
                <div>
                    <label for="e4_tmj" class="block text-sm font-medium text-gray-600">Sendi Rahang (TMJ)</label>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const startCameraButton = document.getElementById('start-camera');
    const snapButton = document.getElementById('snap');
    const retakeButton = document.getElementById('retake-photo');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photo-preview');
    const photoPreviewContainer = document.getElementById('photo-preview-container');
    const hiddenInput = document.getElementById('e2_photo');
    const context = canvas.getContext('2d');
    let stream;

    startCameraButton.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            video.srcObject = stream;
            video.style.display = 'block';
            snapButton.disabled = false;
            startCameraButton.style.display = 'none';
            photoPreviewContainer.style.display = 'none';
            video.play();
        } catch (err) {
            console.error("Error accessing camera: ", err);
            alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.");
        }
    });

    snapButton.addEventListener('click', () => {
        context.drawImage(video, 0, 0, 320, 240);
        const dataUrl = canvas.toDataURL('image/jpeg');
        
        photoPreview.src = dataUrl;
        photoPreviewContainer.style.display = 'block';
        hiddenInput.value = dataUrl;

        // Stop video stream and hide video element
        stream.getTracks().forEach(track => track.stop());
        video.style.display = 'none';
        snapButton.style.display = 'none';
        startCameraButton.style.display = 'block'; // Show start camera button again to retake
        startCameraButton.textContent = 'Ambil Ulang';
    });

    retakeButton.addEventListener('click', () => {
        hiddenInput.value = '';
        photoPreviewContainer.style.display = 'none';
        snapButton.style.display = 'block';
        startCameraButton.click(); // Re-start the camera flow
    });
});
</script>
@endpush
