<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Diagnosis;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    private $sq_questions = [
        'SQ1' => 'Apakah Anda merasakan nyeri di rahang, pelipis, atau telinga?',
        'SQ2' => 'Berapa lama nyeri di rahang/pelipis/telinga berlangsung? (Jawaban: < 1 minggu atau >= 1 minggu)',
        'SQ3' => 'Bagaimana sifat nyeri? Apakah persisten atau kambuhan?',
        'SQ4' => 'Apakah aktivitas (mengunyah makanan keras, membuka mulut lebar, kebiasaan rahang, aktivitas rahang lain) memengaruhi rasa sakit?',
        'SQ5' => 'Apakah Anda mengalami sakit kepala di pelipis?',
        'SQ6' => 'Berapa lama sakit kepala itu berlangsung? (Jawaban: < 1x/minggu atau >= 1x/minggu)',
        'SQ7' => 'Apakah aktivitas rahang mengubah sakit kepala Anda?',
        'SQ8' => 'Apakah Anda mendengar bunyi pada rahang? (Jawaban: Tidak ada, Sesekali, Sering)',
        'SQ9' => 'Apakah rahang Anda pernah terkunci sehingga tidak bisa terbuka penuh?',
        'SQ10' => 'Apakah rahang pernah terkunci parah sehingga mulut tidak bisa terbuka dan makan terganggu?',
        'SQ11' => 'Apakah rahang pernah terkunci sehingga tidak bisa terbuka walau sebentar?',
        'SQ12' => 'Apakah sekarang rahang terkunci sehingga pembukaan terbatas?',
        'SQ13' => 'Apakah pernah mengalami rahang terkunci dalam posisi terbuka sehingga tidak bisa menutup?',
        'SQ14' => 'Apakah Anda harus memanipulasi/ menggeser rahang agar bisa menutup kembali setelah terbuka?',
    ];

    private $eq_questions = [
        'E1' => 'Nyeri tekan ringan (Temporalis, Masseter, TMJ). Pilih area yang nyeri.',
        'E2' => 'Gerakan membuka mulut (maksimal, dalam mm).',
        'E3' => 'Bunyi sendi saat membuka & menutup mulut. (Jawaban: Tidak ada, Klik tunggal, Klik ganda, Krepitasi kasar)',
        'E4' => 'Palpasi nyeri otot & sendi (skor 0-3). Beri skor untuk Temporalis, Masseter, dan Sendi TMJ.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Auth::user()->patient->consultations()->with('diagnoses')->latest()->get();
        return view('consultations.index', compact('consultations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sq_questions = $this->sq_questions;
        $eq_questions = $this->eq_questions;
        return view('consultations.create', compact('sq_questions', 'eq_questions'));
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Basic validation
        $request->validate([
            'sq' => 'required|array',
            'eq' => 'required|array',
        ]);

        $sq_answers = $request->input('sq');
        $eq_answers = $request->input('eq');
        $diagnoses = [];

        // Myalgia Diagnosis
        if (
            ($sq_answers['SQ1'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ2'] ?? '< 1 minggu') === '>= 1 minggu' &&
            ($sq_answers['SQ3'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ4'] ?? 'Tidak') === 'Ya' &&
            (isset($eq_answers['E1']['Temporalis']) || isset($eq_answers['E1']['Masseter']) || ($eq_answers['E4']['Temporalis'] ?? 0) >= 1 || ($eq_answers['E4']['Masseter'] ?? 0) >= 1)
        ) {
            $diagnoses[] = 'Myalgia';
        }

        // Arthralgia Diagnosis
        if (
            ($sq_answers['SQ1'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ4'] ?? 'Tidak') === 'Ya' &&
            (isset($eq_answers['E1']['TMJ']) || ($eq_answers['E4']['TMJ'] ?? 0) >= 1)
        ) {
            $diagnoses[] = 'Arthralgia';
        }

        // HA-TMD Diagnosis
        if (
            ($sq_answers['SQ5'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ6'] ?? '< 1x/minggu') === '>= 1x/minggu' &&
            ($sq_answers['SQ7'] ?? 'Tidak') === 'Ya'
        ) {
            $diagnoses[] = 'Headache attributed to TMD (HA-TMD)';
        }

        // Joint-related TMD
        $isJointRelated = false;
        if (
            ($sq_answers['SQ8'] ?? 'Tidak ada') !== 'Tidak ada' ||
            ($sq_answers['SQ9'] ?? 'Tidak') === 'Ya' ||
            ($sq_answers['SQ10'] ?? 'Tidak') === 'Ya' ||
            ($sq_answers['SQ11'] ?? 'Tidak') === 'Ya' ||
            ($sq_answers['SQ12'] ?? 'Tidak') === 'Ya' ||
            ($eq_answers['E2'] ?? 40) <= 35 ||
            ($sq_answers['SQ13'] ?? 'Tidak') === 'Ya' ||
            ($sq_answers['SQ14'] ?? 'Tidak') === 'Ya' ||
            ($eq_answers['E3'] ?? 'Tidak ada') === 'Krepitasi kasar'
        ) {
             $isJointRelated = true;
        }
        
        if ($isJointRelated) {
            $diagnoses[] = 'Joint-related TMD';
        }


        $consultation = null;
        DB::transaction(function () use ($request, $diagnoses, &$consultation) {
            $patient = Auth::user()->patient;
            $consultation = $patient->consultations()->create([
                'consultation_date' => now(),
            ]);

            // Save all answers
            foreach ($request->input('sq') as $key => $value) {
                $consultation->answers()->create([
                    'question_code' => $key,
                    'answer' => is_array($value) ? json_encode($value) : $value,
                    'type' => 'SQ'
                ]);
            }
            foreach ($request->input('eq') as $key => $value) {
                 $consultation->answers()->create([
                    'question_code' => $key,
                    'answer' => is_array($value) ? json_encode($value) : $value,
                    'type' => 'EQ'
                ]);
            }

            // Save all diagnoses
            if (!empty($diagnoses)) {
                foreach (array_unique($diagnoses) as $diagnosisName) {
                    $consultation->diagnoses()->create(['name' => $diagnosisName]);
                }
            } else {
                 $consultation->diagnoses()->create(['name' => 'No specific TMD diagnosis found.']);
            }
        });

        return redirect()->route('consultations.show', $consultation->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        // Ensure the user is authorized to see this consultation
        if ($consultation->patient->user_id !== Auth::id()) {
            abort(403);
        }
        return view('consultations.show', compact('consultation'));
    }
}
