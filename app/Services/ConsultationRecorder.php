<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Patient;
use App\Support\TmdQuestionnaire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Menyimpan satu konsultasi lengkap (jawaban, foto, diagnosis) dalam satu
 * transaksi. Dipakai bersama oleh API dan web.
 */
class ConsultationRecorder
{
    public function __construct(
        private readonly TmdDiagnosisService $diagnosis,
        private readonly ConsultationPhotoStorage $photos,
    ) {}

    /**
     * @param  array<string, mixed>  $input  berisi 'sq' dan 'eq' (sudah divalidasi)
     * @param  UploadedFile|null  $photoFile  foto multipart (diprioritaskan)
     * @param  string|null  $photoBase64  foto base64
     *
     * @throws \Illuminate\Validation\ValidationException bila foto tidak valid
     */
    public function record(Patient $patient, array $input, ?UploadedFile $photoFile = null, ?string $photoBase64 = null): Consultation
    {
        $answers = TmdQuestionnaire::sanitize(
            is_array($input['sq'] ?? null) ? $input['sq'] : [],
            is_array($input['eq'] ?? null) ? $input['eq'] : [],
        );

        $photoPath = null;
        if ($photoFile !== null) {
            $photoPath = $this->photos->storeUploadedFile($photoFile);
        } elseif ($photoBase64 !== null && $photoBase64 !== '') {
            $photoPath = $this->photos->storeBase64($photoBase64);
        }

        $diagnoses = $this->diagnosis->diagnose($answers['sq'], $answers['eq']);

        try {
            return DB::transaction(function () use ($patient, $answers, $photoPath, $diagnoses) {
                $consultation = $patient->consultations()->create([
                    'consultation_date' => now(),
                ]);

                foreach ($answers['sq'] as $code => $value) {
                    $consultation->answers()->create([
                        'question_code' => $code,
                        'answer' => $value,
                        'type' => 'SQ',
                    ]);
                }

                foreach (TmdQuestionnaire::SCORE_GROUPS as $group) {
                    if (isset($answers['eq'][$group])) {
                        $consultation->answers()->create([
                            'question_code' => $group,
                            'answer' => json_encode($answers['eq'][$group]),
                            'type' => 'EQ',
                        ]);
                    }
                }

                $openingMm = $answers['eq']['E2']['opening_mm'] ?? null;
                if ($openingMm !== null || $photoPath !== null) {
                    $consultation->answers()->create([
                        'question_code' => 'E2',
                        'answer' => json_encode([
                            'opening_mm' => $openingMm,
                            'photo_path' => $photoPath,
                        ]),
                        'type' => 'EQ',
                    ]);
                }

                if (isset($answers['eq']['E3'])) {
                    $consultation->answers()->create([
                        'question_code' => 'E3',
                        'answer' => $answers['eq']['E3'],
                        'type' => 'EQ',
                    ]);
                }

                foreach ($diagnoses as $name) {
                    $consultation->diagnoses()->create(['name' => $name]);
                }

                return $consultation;
            });
        } catch (Throwable $e) {
            // Jangan tinggalkan file yatim bila penyimpanan gagal.
            $this->photos->delete($photoPath);

            throw $e;
        }
    }
}
