<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Diagnosis;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Statistik Data Pasien
        $totalPatients = Patient::count();
        $newPatientsThisMonth = Patient::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count();
        $latestPatients = Patient::latest()->take(10)->get();

        // Statistik Data Diagnosis
        $diagnosisStats = Diagnosis::select('name', DB::raw('count(*) as count'))
            ->groupBy('name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $totalDiagnoses = Diagnosis::count();
        $diagnosisPercentages = [];
        
        foreach ($diagnosisStats as $stat) {
            $percentage = $totalDiagnoses > 0 ? ($stat->count / $totalDiagnoses) * 100 : 0;
            $diagnosisPercentages[] = [
                'name' => $stat->name,
                'count' => $stat->count,
                'percentage' => round($percentage, 2)
            ];
        }

        // Statistik Data Konsultasi
        $totalConsultations = Consultation::count();
        
        // Rata-rata konsultasi per pasien
        $avgConsultationsPerPatient = DB::table('consultations')
            ->select(DB::raw('AVG(consultation_count) as average'))
            ->from(function ($query) {
                $query->select(DB::raw('COUNT(*) as consultation_count'))
                      ->from('consultations')
                      ->groupBy('patient_id');
            }, 'patient_consultations')
            ->value('average');

        // Pasien dengan konsultasi terbanyak
        $mostActivePatients = DB::table('patients as p')
            ->join('consultations as c', 'p.id', '=', 'c.patient_id')
            ->select('p.id', 'p.name', DB::raw('COUNT(c.id) as consultation_count'))
            ->groupBy('p.id', 'p.name')
            ->orderByDesc('consultation_count')
            ->limit(5)
            ->get();

        // Analisis Jawaban Pertanyaan
        $mostAnsweredQuestions = DB::table('questions as q')
            ->join('answers as a', 'q.code', '=', 'a.question_code')
            ->select('q.question_text', 'q.code', DB::raw('COUNT(a.id) as answer_count'))
            ->groupBy('q.code', 'q.question_text')
            ->orderByDesc('answer_count')
            ->limit(10)
            ->get();

        // Distribusi jawaban per tipe pertanyaan
        $answerDistributionByType = DB::table('questions as q')
            ->join('answers as a', 'q.code', '=', 'a.question_code')
            ->select('q.type', DB::raw('COUNT(a.id) as answer_count'))
            ->groupBy('q.type')
            ->get();

        // Prepare data for view
        return view('admin.dashboard', compact(
            'totalPatients',
            'newPatientsThisMonth',
            'latestPatients',
            'diagnosisPercentages',
            'totalDiagnoses',
            'totalConsultations',
            'avgConsultationsPerPatient',
            'mostActivePatients',
            'mostAnsweredQuestions',
            'answerDistributionByType'
        ));
    }
}