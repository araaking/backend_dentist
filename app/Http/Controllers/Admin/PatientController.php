<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Answer;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::with('user')
            ->withCount('consultations')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        // Load patient with user relationship
        $patient->load('user');
        
        // Get all consultations with their relationships
        $consultations = Consultation::with(['answers', 'diagnoses', 'examinationResults'])
            ->where('patient_id', $patient->id)
            ->orderBy('consultation_date', 'desc')
            ->get();
        
        // Calculate statistics
        $totalConsultations = $consultations->count();
        
        // Get most common answers for this patient
        $mostCommonAnswers = Answer::select('answer', DB::raw('count(*) as count'))
            ->whereHas('consultation', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->groupBy('answer')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        // Get most common diagnoses for this patient
        $mostCommonDiagnoses = Diagnosis::select('name', DB::raw('count(*) as count'))
            ->whereHas('consultation', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->groupBy('name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        // Calculate average answers per consultation
        $avgAnswersPerConsultation = $totalConsultations > 0
            ? Answer::whereHas('consultation', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })->count() / $totalConsultations
            : 0;
        
        return view('admin.patients.show', compact(
            'patient',
            'consultations',
            'totalConsultations',
            'mostCommonAnswers',
            'mostCommonDiagnoses',
            'avgAnswersPerConsultation'
        ));
    }
}