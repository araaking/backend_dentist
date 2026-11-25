@extends('layouts.app')

@section('title', 'Self Diagnosis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Formulir Self Diagnosis TMD</h1>

    <form action="{{ route('consultations.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf

        <h2 class="text-2xl font-semibold mb-6 border-b pb-2">Pertanyaan Subjektif (SQ)</h2>

        @foreach ($sq_questions as $code => $question)
        @php($optionLabels = \App\Support\TmdQuestionnaire::WEB_OPTION_LABELS[$code] ?? [])
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $question['text'] }}</label>
            @if ($question['type'] === 'select')
                <select name="sq[{{ $code }}]" class="w-full px-3 py-2 border rounded-lg">
                    @foreach ($question['options'] as $option)
                        <option value="{{ $option }}" @selected(old("sq.$code", $question['default']) === $option)>{{ $optionLabels[$option] ?? $option }}</option>
                    @endforeach
                </select>
            @else
                <div class="flex items-center">
                    @foreach ($question['options'] as $option)
                        <input type="radio" name="sq[{{ $code }}]" value="{{ $option }}" class="{{ $loop->first ? 'mr-2' : 'ml-4 mr-2' }}" @checked(old("sq.$code", $question['default']) === $option)> {{ $optionLabels[$option] ?? $option }}
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach


        <h2 class="text-2xl font-semibold mt-10 mb-6 border-b pb-2">Pemeriksaan Mandiri (EQ)</h2>
        <p class="text-gray-600 mb-6">Lakukan instruksi berikut dan catat hasilnya.</p>

        {{-- E1 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_titles['E1'] }}</label>
            <p class="text-sm text-gray-600 mb-4">Tekan ringan area berikut dengan jari Anda sesuai dengan gambar di bawah. Pilih tingkat nyeri yang Anda rasakan:</p>
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/e1.png') }}" alt="Referensi Pemeriksaan Nyeri Tekan" class="w-full max-w-md rounded-lg shadow-md">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach (\App\Support\TmdQuestionnaire::AREAS as $area)
                <div>
                    <label for="e1_{{ strtolower($area) }}" class="block text-sm font-medium text-gray-600">{{ \App\Support\TmdQuestionnaire::AREA_LABELS[$area] }}</label>
                    <select name="eq[E1][{{ $area }}]" id="e1_{{ strtolower($area) }}" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        @foreach (\App\Support\TmdQuestionnaire::SCORE_LABELS as $score => $label)
                            <option value="{{ $score }}" @selected((string) old("eq.E1.$area", 0) === (string) $score)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

        {{-- E2 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_titles['E2'] }}</label>
            <div class="space-y-4">
                <div>
                    <input type="number" name="eq[E2][opening_mm]" id="e2_opening" class="w-full px-3 py-2 border rounded-lg" placeholder="Contoh: 40" min="0" max="100" step="any" value="{{ old('eq.E2.opening_mm') }}" required>
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
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_titles['E3'] }}</label>
            <p class="text-sm text-gray-600 mb-4">Buka dan tutup mulut Anda beberapa kali. Dengarkan contoh bunyi berikut dan pilih yang paling sesuai dengan yang Anda dengar.</p>
            <div class="space-y-4">
                @foreach ($e3_options as $i => $option)
                <div class="flex items-center">
                    <input type="radio" name="eq[E3]" value="{{ $option['value'] }}" id="e3_option_{{ $i }}" class="mr-3" @checked(old('eq.E3', \App\Support\TmdQuestionnaire::e3Default()) === $option['value'])>
                    <label for="e3_option_{{ $i }}" class="text-gray-700 {{ isset($option['audio']) ? 'mr-4' : '' }}">{{ $option['label'] }}</label>
                    @isset($option['audio'])
                        <audio controls src="{{ asset($option['audio']) }}" class="h-8"></audio>
                    @endisset
                </div>
                @endforeach
            </div>
        </div>

        {{-- E4 --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">{{ $eq_titles['E4'] }}</label>
            <p class="text-sm text-gray-600 mb-4">Tekan lebih dalam pada area berikut. Beri nilai nyeri yang Anda rasakan:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach (\App\Support\TmdQuestionnaire::AREAS as $area)
                <div>
                    <label for="e4_{{ strtolower($area) }}" class="block text-sm font-medium text-gray-600">{{ \App\Support\TmdQuestionnaire::AREA_LABELS[$area] }}</label>
                    <select name="eq[E4][{{ $area }}]" id="e4_{{ strtolower($area) }}" class="w-full mt-1 px-3 py-2 border rounded-lg">
                        @foreach (\App\Support\TmdQuestionnaire::SCORE_LABELS as $score => $label)
                            <option value="{{ $score }}" @selected((string) old("eq.E4.$area", 0) === (string) $score)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
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

    // Auto-refresh CSRF token every 10 minutes to prevent 419 errors
    setInterval(async () => {
        try {
            const response = await fetch('{{ route("consultations.create") }}');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('input[name="_token"]').value;
            document.querySelector('input[name="_token"]').value = newToken;
            console.log('CSRF token refreshed');
        } catch (error) {
            console.error('Failed to refresh CSRF token:', error);
        }
    }, 600000); // 10 minutes

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
