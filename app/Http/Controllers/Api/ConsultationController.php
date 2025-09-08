<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;
        if (! $patient) {
            return response()->json(['message' => 'Profil pasien belum dibuat.'], 404);
        }

        $consultations = $patient->consultations()->with('diagnoses')->latest()->get();

        return response()->json([
            'consultations' => $consultations,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;
        if (! $patient) {
            return response()->json(['message' => 'Profil pasien belum dibuat.'], 404);
        }

        $validated = $request->validate([
            'sq' => 'required|array',
            'eq' => 'required|array',
            'e2_photo' => 'nullable|string', // base64
            'e2_photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120', // multipart
        ]);

        $sq_answers = $validated['sq'];
        $eq_answers = $validated['eq'];
        $diagnoses = [];

        // Handle photo for E2 (prefer multipart over base64 if both present)
        $photoPath = null;
        if ($request->hasFile('e2_photo_file')) {
            $photoPath = $request->file('e2_photo_file')->store('consultation_photos', 'public');
        } elseif (!empty($validated['e2_photo'])) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['e2_photo']);
            $imageData = base64_decode($imageData);
            $filename = 'consultation_photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, $imageData);
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
        if (empty($diagnoses) &&
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
        if (empty($diagnoses) &&
            ($sq_answers['SQ5'] ?? 'Tidak') === 'Ya' &&
            ($sq_answers['SQ6'] ?? 'Tidak') === '>= 1x/minggu' &&
            ($sq_answers['SQ7'] ?? 'Tidak') === 'Ya'
        ) {
            $diagnoses[] = 'Headache attributed to TMD (HA-TMD)';
        }

        // Joint-related TMD
        if (empty($diagnoses)) {
            $isJointRelated = false;
            $jointSymptoms = 0;

            if (($sq_answers['SQ8'] ?? 'Tidak ada') !== 'Tidak ada') $jointSymptoms++;
            if (($sq_answers['SQ9'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
            if (($sq_answers['SQ10'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
            if (($sq_answers['SQ11'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
            if (($sq_answers['SQ12'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
            if (($eq_answers['E2']['opening_mm'] ?? 40) <= 35) $jointSymptoms++;
            if (($sq_answers['SQ13'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
            if (($sq_answers['SQ14'] ?? 'Tidak') === 'Ya') $jointSymptoms++;
            if (($eq_answers['E3'] ?? 'Tidak ada') === 'Krepitasi') $jointSymptoms++;

            if ($jointSymptoms >= 2) {
                $isJointRelated = true;
            }

            if ($isJointRelated) {
                $diagnoses[] = 'Joint-related TMD';
            }
        }

        $consultation = null;
        DB::transaction(function () use ($request, $diagnoses, $photoPath, &$consultation) {
            $patient = $request->user()->patient;
            $consultation = $patient->consultations()->create([
                'consultation_date' => now(),
            ]);

            foreach ($request->input('sq') as $key => $value) {
                $consultation->answers()->create([
                    'question_code' => $key,
                    'answer' => is_array($value) ? json_encode($value) : $value,
                    'type' => 'SQ'
                ]);
            }

            foreach ($request->input('eq') as $key => $value) {
                if ($key === 'E2') {
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

            if (!empty($diagnoses)) {
                foreach (array_unique($diagnoses) as $diagnosisName) {
                    $consultation->diagnoses()->create(['name' => $diagnosisName]);
                }
            } else {
                $consultation->diagnoses()->create(['name' => 'No specific TMD diagnosis found.']);
            }
        });

        if ($consultation) {
            return response()->json([
                'message' => 'Konsultasi berhasil dibuat.',
                'consultation_id' => $consultation->id,
                'diagnoses' => $consultation->diagnoses()->pluck('name'),
            ], 201);
        }

        return response()->json(['message' => 'Terjadi kesalahan saat menyimpan diagnosis.'], 500);
    }

    public function show(Request $request, Consultation $consultation)
    {
        $user = $request->user();
        $patient = $user->patient;
        if (! $patient) {
            return response()->json(['message' => 'Profil pasien belum dibuat.'], 404);
        }

        if ($consultation->patient_id !== $patient->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $consultation->load(['answers', 'diagnoses']);

        return response()->json([
            'consultation' => $consultation,
        ]);
    }
}