<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use App\Services\ConsultationPhotoStorage;
use App\Services\ConsultationRecorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;
        if (! $patient) {
            return response()->json(['message' => 'Profil pasien belum dibuat.'], 404);
        }

        // `answers` hanya dimuat untuk menentukan e2_photo_url; tidak ikut di response (bentuk lama dipertahankan).
        $consultations = $patient->consultations()->with(['diagnoses', 'answers'])->latest()->get();

        $consultations->each(function (Consultation $consultation) {
            $this->withPhotoUrl($consultation);
            $consultation->makeHidden('answers');
        });

        return response()->json([
            'consultations' => $consultations,
        ]);
    }

    public function store(StoreConsultationRequest $request, ConsultationRecorder $recorder)
    {
        $patient = $request->user()->patient;
        if (! $patient) {
            return response()->json(['message' => 'Profil pasien belum dibuat.'], 404);
        }

        $consultation = $recorder->record(
            $patient,
            $request->validated(),
            $request->file('e2_photo_file'),
            $request->input('e2_photo'),
        );

        $consultation->load('answers');

        return response()->json([
            'message' => 'Konsultasi berhasil dibuat.',
            'consultation_id' => $consultation->id,
            'diagnoses' => $consultation->diagnoses()->pluck('name'),
            'e2_photo_url' => $this->photoUrl($consultation),
        ], 201);
    }

    public function show(Request $request, Consultation $consultation)
    {
        $patient = $request->user()->patient;
        if (! $patient) {
            return response()->json(['message' => 'Profil pasien belum dibuat.'], 404);
        }

        if ((int) $consultation->patient_id !== (int) $patient->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $consultation->load(['answers', 'diagnoses']);
        $this->withPhotoUrl($consultation);

        return response()->json([
            'consultation' => $consultation,
        ]);
    }

    /**
     * Foto E2 (butuh header Authorization; hanya pemilik).
     */
    public function photo(Request $request, Consultation $consultation, ConsultationPhotoStorage $photos)
    {
        Gate::forUser($request->user())->authorize('view', $consultation);

        $path = $consultation->photoPath();
        if ($path === null) {
            return response()->json(['message' => 'Foto tidak ditemukan.'], 404);
        }

        return $photos->response($path);
    }

    private function photoUrl(Consultation $consultation): ?string
    {
        return $consultation->photoPath() !== null
            ? route('api.consultations.photo', $consultation)
            : null;
    }

    private function withPhotoUrl(Consultation $consultation): void
    {
        $consultation->setAttribute('e2_photo_url', $this->photoUrl($consultation));
    }
}
