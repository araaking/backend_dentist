<?php

namespace App\Services;

use App\Support\TmdQuestionnaire;

/**
 * Logika diagnosis TMD (fungsi murni: jawaban masuk, daftar nama diagnosis keluar).
 *
 * Aturan klinis dipindahkan apa adanya dari controller lama.
 */
class TmdDiagnosisService
{
    public const MYALGIA = 'Myalgia';

    public const ARTHRALGIA = 'Arthralgia';

    public const HA_TMD = 'Headache attributed to TMD (HA-TMD)';

    public const JOINT_RELATED = 'Joint-related TMD';

    public const NONE = 'No specific TMD diagnosis found.';

    /**
     * @param  array<string, mixed>  $sq  jawaban SQ, mis. ['SQ1' => 'Ya', ...]
     * @param  array<string, mixed>  $eq  jawaban EQ, mis. ['E1' => ['TMJ' => 1], 'E2' => ['opening_mm' => 40], 'E3' => 'Kliking']
     * @return list<string> nama diagnosis; berisi self::NONE bila tidak ada yang terpenuhi
     */
    public function diagnose(array $sq, array $eq): array
    {
        // CATATAN KLINIS: aturan di bawah saling eksklusif dan berurutan
        // (Myalgia -> Arthralgia -> HA-TMD -> Joint-related). Begitu satu
        // aturan terpenuhi, aturan berikutnya tidak dievaluasi, sehingga satu
        // konsultasi paling banyak menghasilkan satu diagnosis. Ini adalah
        // keputusan klinis yang perlu dikonfirmasi dengan pakar/dokter gigi;
        // perilaku ini dipertahankan sama persis dengan implementasi lama.
        $diagnoses = [];

        // Myalgia
        if (
            ($sq['SQ1'] ?? 'Tidak') === 'Ya' &&
            ($sq['SQ2'] ?? '< 1 minggu') === '>= 1 minggu' &&
            $this->hasPainPattern($sq['SQ3'] ?? 'Tidak') &&
            ($sq['SQ4'] ?? 'Tidak') === 'Ya' &&
            (
                $this->score($eq, 'E1', 'Temporalis') >= 1 ||
                $this->score($eq, 'E1', 'Masseter') >= 1 ||
                $this->score($eq, 'E4', 'Temporalis') >= 1 ||
                $this->score($eq, 'E4', 'Masseter') >= 1
            )
        ) {
            $diagnoses[] = self::MYALGIA;
        }

        // Arthralgia
        if (empty($diagnoses) &&
            ($sq['SQ1'] ?? 'Tidak') === 'Ya' &&
            ($sq['SQ4'] ?? 'Tidak') === 'Ya' &&
            (
                $this->score($eq, 'E1', 'TMJ') >= 1 ||
                $this->score($eq, 'E4', 'TMJ') >= 1
            )
        ) {
            $diagnoses[] = self::ARTHRALGIA;
        }

        // Headache attributed to TMD (HA-TMD)
        if (empty($diagnoses) &&
            ($sq['SQ5'] ?? 'Tidak') === 'Ya' &&
            ($sq['SQ6'] ?? 'Tidak') === '>= 1x/minggu' &&
            ($sq['SQ7'] ?? 'Tidak') === 'Ya'
        ) {
            $diagnoses[] = self::HA_TMD;
        }

        // Joint-related TMD: minimal 2 gejala sendi
        if (empty($diagnoses)) {
            $jointSymptoms = 0;

            if (($sq['SQ8'] ?? 'Tidak ada') !== 'Tidak ada') {
                $jointSymptoms++;
            }
            if (($sq['SQ9'] ?? 'Tidak') === 'Ya') {
                $jointSymptoms++;
            }
            if (($sq['SQ10'] ?? 'Tidak') === 'Ya') {
                $jointSymptoms++;
            }
            if (($sq['SQ11'] ?? 'Tidak') === 'Ya') {
                $jointSymptoms++;
            }
            if (($sq['SQ12'] ?? 'Tidak') === 'Ya') {
                $jointSymptoms++;
            }

            // Perbaikan: opening_mm kosong/null/non-numerik TIDAK dihitung sebagai "<= 35".
            $openingMm = is_array($eq['E2'] ?? null) ? ($eq['E2']['opening_mm'] ?? null) : null;
            if (is_numeric($openingMm) && $openingMm <= 35) {
                $jointSymptoms++;
            }

            if (($sq['SQ13'] ?? 'Tidak') === 'Ya') {
                $jointSymptoms++;
            }
            if (($sq['SQ14'] ?? 'Tidak') === 'Ya') {
                $jointSymptoms++;
            }
            if (($eq['E3'] ?? 'Tidak ada') === 'Krepitasi') {
                $jointSymptoms++;
            }

            if ($jointSymptoms >= 2) {
                $diagnoses[] = self::JOINT_RELATED;
            }
        }

        return $diagnoses === [] ? [self::NONE] : array_values(array_unique($diagnoses));
    }

    /**
     * SQ3: nyeri persisten maupun kambuhan sama-sama memenuhi kriteria
     * (urutan opsi tidak memengaruhi hasil diagnosis).
     */
    private function hasPainPattern(mixed $value): bool
    {
        return in_array($value, [TmdQuestionnaire::SQ3_PERSISTENT, TmdQuestionnaire::SQ3_RECURRENT], true);
    }

    /**
     * Skor nyeri (default 0). Nilai non-numerik dianggap 0.
     *
     * @param  array<string, mixed>  $eq
     */
    private function score(array $eq, string $group, string $area): int|float
    {
        $value = is_array($eq[$group] ?? null) ? ($eq[$group][$area] ?? 0) : 0;

        return is_numeric($value) ? $value + 0 : 0;
    }
}
