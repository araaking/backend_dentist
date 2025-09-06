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
        'E1' => 'Pemeriksaan Nyeri Tekan Ringan',
        'E2' => 'Pengukuran Bukaan Mulut Maksimal',
        'E3' => 'Pemeriksaan Bunyi Sendi Rahang',
        'E4' => 'Pemeriksaan Nyeri Tekan Lebih Dalam',
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
            'e2_photo' => 'nullable|string', // Now expecting a base64 string
        ]);

        $sq_answers = $request->input('sq');
        $eq_answers = $request->input('eq');
        $diagnoses = [];

        // Handle photo from base64 for E2
        $photoPath = null;
        if ($request->filled('e2_photo')) {
            $imageData = $request->input('e2_photo');
            // Remove the data URL scheme and get the raw base64 data
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
            $imageData = base64_decode($imageData);
            
            $filename = 'consultation_photos/' . uniqid() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $imageData);
            $photoPath = $filename;
        }

        // Myalgia Diagnosis
        if (
            ($sq_answers['SQ1'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ2'] ?? '< 1 minggu') === '>= 1 minggu' &&
            ($sq_answers['SQ3'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ4'] ?? 'Tidak') === 'Ya' &&
            (
                ($eq_answers['E1']['Temporalis'] ?? 0) >= 1 ||
                ($eq_answers['E1']['Masseter'] ?? 0) >= 1 ||
                ($eq_answers['E4']['Temporalis'] ?? 0) >= 1 ||
                ($eq_answers['E4']['Masseter'] ?? 0) >= 1
            )
        ) {
            $diagnoses[] = 'Myalgia';
        }

        // Arthralgia Diagnosis
        if (
            ($sq_answers['SQ1'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ4'] ?? 'Tidak') === 'Ya' &&
            (
                ($eq_answers['E1']['TMJ'] ?? 0) >= 1 ||
                ($eq_answers['E4']['TMJ'] ?? 0) >= 1
            )
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

        // Joint-related TMD - Lebih spesifik berdasarkan kriteria
        $isJointRelated = false;
        $jointSymptoms = 0;

        // Hitung gejala sendi yang ada
        if (($sq_answers['SQ8'] ?? 'Tidak ada') !== 'Tidak ada') $jointSymptoms++;
        if (($sq_answers['SQ9'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
        if (($sq_answers['SQ10'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
        if (($sq_answers['SQ11'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
        if (($sq_answers['SQ12'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
        if (($eq_answers['E2']['opening_mm'] ?? 40) <= 35) $jointSymptoms++;
        if (($sq_answers['SQ13'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
        if (($sq_answers['SQ14'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
        if (($eq_answers['E3'] ?? 'Tidak ada') === 'Krepitasi kasar') $jointSymptoms++;

        // Joint-related TMD hanya jika ada minimal 2 gejala sendi
        if ($jointSymptoms >= 2) {
            $isJointRelated = true;
        }

        if ($isJointRelated) {
            $diagnoses[] = 'Joint-related TMD';
        }


        $consultation = null;
        DB::transaction(function () use ($request, $diagnoses, $photoPath, &$consultation) {
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
                if ($key === 'E2') {
                    // Special handling for E2 with photo
                    $e2Data = [
                        'opening_mm' => $value['opening_mm'] ?? null,
                        'photo_path' => $photoPath
                    ];
                    $consultation->answers()->create([
                        'question_code' => $key,
                        'answer' => json_encode($e2Data),
                        'type' => 'EQ'
                    ]);
                } else {
                    $consultation->answers()->create([
                        'question_code' => $key,
                        'answer' => is_array($value) ? json_encode($value) : $value,
                        'type' => 'EQ'
                    ]);
                }
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

        if ($consultation) {
            return redirect()->route('consultations.show', $consultation->id);
        } else {
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat menyimpan diagnosis.');
        }
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
