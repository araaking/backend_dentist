<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use App\Services\ConsultationPhotoStorage;
use App\Services\ConsultationRecorder;
use App\Support\TmdQuestionnaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ConsultationController extends Controller
{
    private function missingPatientRedirect()
    {
        return redirect()->route('patients.create')
            ->with('error', 'Lengkapi profil pasien Anda terlebih dahulu.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patient = Auth::user()->patient;
        if (! $patient) {
            return $this->missingPatientRedirect();
        }

        $consultations = $patient->consultations()->with('diagnoses', 'answers')->latest()->get();

        return view('consultations.index', compact('consultations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! Auth::user()->patient) {
            return $this->missingPatientRedirect();
        }

        return view('consultations.create', [
            'sq_questions' => TmdQuestionnaire::sq(),
            'eq_titles' => TmdQuestionnaire::eqTitles(),
            'e3_options' => TmdQuestionnaire::e3Options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsultationRequest $request, ConsultationRecorder $recorder)
    {
        $patient = Auth::user()->patient;
        if (! $patient) {
            return $this->missingPatientRedirect();
        }

        $consultation = $recorder->record(
            $patient,
            $request->validated(),
            $request->file('e2_photo_file'),
            $request->input('e2_photo'),
        );

        return redirect()->route('consultations.show', $consultation->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        Gate::authorize('view', $consultation);

        $consultation->load(['answers', 'diagnoses']);

        return view('consultations.show', compact('consultation'));
    }

    /**
     * Foto E2 milik konsultasi (pemilik atau admin).
     */
    public function photo(Consultation $consultation, ConsultationPhotoStorage $photos)
    {
        Gate::authorize('view', $consultation);

        $path = $consultation->photoPath();
        if ($path === null) {
            abort(404);
        }

        return $photos->response($path);
    }
}
