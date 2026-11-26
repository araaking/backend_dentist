<?php

namespace App\Services;

/**
 * Logika diagnosis TMD (fungsi murni: jawaban masuk, daftar nama diagnosis keluar).
 *
 * Sumber aturan: "Brief DC/TMD Diagnostic Decision Tree, Version 1, 6 June 2023".
 * Bagan terdiri dari dua jalur independen yang selalu dievaluasi keduanya, lalu
 * hasilnya digabung (satu konsultasi bisa menghasilkan beberapa diagnosis):
 *   1. Painful TMDs (halaman 1): Myalgia, Arthralgia, HA-TMD.
 *   2. Common joint-related TMDs (halaman 2): Degenerative joint disease,
 *      Acute closed lock, Other Disc-based TMD, Subluxation.
 *
 * Node bagan yang TIDAK bisa dievaluasi aplikasi ini (pemeriksaan mandiri):
 *   - [E2] "familiar pain from jaw opening": tidak dikumpulkan -> dianggap tidak ada
 *     (nyeri familiar hanya dinilai dari palpasi E4).
 *   - [E3] "lock in closed position" / "lock in open position" oleh pemeriksa:
 *     tidak dikumpulkan -> dianggap tidak ada. Subluxation memakai riwayat
 *     SQ13 + SQ14 saja (kotak pemeriksaan "if clinically present" tidak disyaratkan).
 *   - "Headache not better accounted for by another headache diagnosis"
 *     [Symptom review]: tidak bisa dinilai lewat pemeriksaan mandiri -> dianggap
 *     terpenuhi. Dokter gigi tetap harus mengonfirmasinya.
 *
 * SQ2, SQ3, dan SQ6 tidak dipakai bagan (tetap dikumpulkan dan disimpan).
 * Nilai yang hilang/tidak dikenal selalu dianggap negatif.
 *
 * Literal opsi ('Ya', 'Sesekali', 'Sering', 'Kliking', 'Krepitasi', nama area) harus
 * tetap sama dengan TmdQuestionnaire; dijaga oleh tests/Unit/TmdDiagnosisQuestionnaireSyncTest.php.
 */
class TmdDiagnosisService
{
    public const MYALGIA = 'Myalgia';

    public const ARTHRALGIA = 'Arthralgia';

    public const HA_TMD = 'Headache attributed to TMD (HA-TMD)';

    public const DEGENERATIVE_JOINT_DISEASE = 'Degenerative joint disease';

    public const ACUTE_CLOSED_LOCK = 'Acute closed lock';

    public const OTHER_DISC_BASED = 'Other Disc-based TMD';

    public const SUBLUXATION = 'Subluxation';

    /**
     * LEGACY: nama diagnosis lama. Tidak lagi dihasilkan, tetapi konsultasi lama
     * di database masih memilikinya (dipertahankan untuk deskripsi/riwayat).
     */
    public const JOINT_RELATED = 'Joint-related TMD';

    public const NONE = 'No specific TMD diagnosis found.';

    /** Batas MUO (mm) pada bagan: "MUO < 35mm" (kurang dari, bukan sama dengan). */
    private const MUO_LIMIT_MM = 35;

    /**
     * @param  array<string, mixed>  $sq  jawaban SQ, mis. ['SQ1' => 'Ya', ...]
     * @param  array<string, mixed>  $eq  jawaban EQ, mis. ['E1' => ['TMJ' => 1], 'E2' => ['opening_mm' => 40], 'E3' => 'Kliking']
     * @return list<string> nama diagnosis unik (urutan tetap); berisi self::NONE bila tidak ada yang terpenuhi
     */
    public function diagnose(array $sq, array $eq): array
    {
        $diagnoses = [
            ...$this->painfulTmds($sq, $eq),
            ...$this->jointDisorders($sq, $eq),
            ...$this->subluxation($sq),
        ];

        return $diagnoses === [] ? [self::NONE] : array_values(array_unique($diagnoses));
    }

    /**
     * Halaman 1: Painful TMDs (Myalgia, Arthralgia, HA-TMD).
     *
     * @param  array<string, mixed>  $sq
     * @param  array<string, mixed>  $eq
     * @return list<string>
     */
    private function painfulTmds(array $sq, array $eq): array
    {
        // HISTORY: (Regional pain [SQ1] OR Headache [SQ5]) AND (modified by jaw movement [SQ4] OR [SQ7]).
        $history = ($this->yes($sq, 'SQ1') || $this->yes($sq, 'SQ5'))
            && ($this->yes($sq, 'SQ4') || $this->yes($sq, 'SQ7'));

        // EXAMINATION: "Examiner confirmation of pain location [E1]" (otot atau TMJ, area mana pun).
        $locationConfirmed = $this->score($eq, 'E1', 'Temporalis') >= 1
            || $this->score($eq, 'E1', 'Masseter') >= 1
            || $this->score($eq, 'E1', 'TMJ') >= 1;

        // EXAMINATION: "Familiar pain ... masticatory muscle palpation [E4] OR TMJ palpation [E4]".
        $musclePalpationPain = $this->score($eq, 'E4', 'Temporalis') >= 1
            || $this->score($eq, 'E4', 'Masseter') >= 1;
        $jointPalpationPain = $this->score($eq, 'E4', 'TMJ') >= 1;
        // "Familiar pain ... from jaw opening [E2]" tidak dikumpulkan aplikasi -> selalu false.
        $openingPain = false;
        $familiarPain = $openingPain || $musclePalpationPain || $jointPalpationPain;

        // Gagal di salah satu node -> "Investigate other pain diagnoses" (tidak ada diagnosis).
        if (! ($history && $locationConfirmed && $familiarPain)) {
            return [];
        }

        // DIAGNOSIS
        $diagnoses = [];

        if ($musclePalpationPain) {
            $diagnoses[] = self::MYALGIA;
        }

        if ($jointPalpationPain) {
            $diagnoses[] = self::ARTHRALGIA;
        }

        // "If headache is a symptom" [SQ5] + nyeri familiar pada palpasi temporalis [E4].
        // Node "Headache not better accounted for by another headache diagnosis
        // [Symptom review]" tidak dapat dinilai -> dianggap terpenuhi.
        if ($this->yes($sq, 'SQ5') && $this->score($eq, 'E4', 'Temporalis') >= 1) {
            $diagnoses[] = self::HA_TMD;
        }

        return $diagnoses;
    }

    /**
     * Halaman 2, kotak START "Prior jaw locking in closed position [SQ9] AND
     * interference in mastication [SQ10]", beserta kotak bunyi sendi.
     *
     * @param  array<string, mixed>  $sq
     * @param  array<string, mixed>  $eq
     * @return list<string>
     */
    private function jointDisorders(array $sq, array $eq): array
    {
        // HISTORY (START B)
        $closedLockHistory = $this->yes($sq, 'SQ9') && $this->yes($sq, 'SQ10');

        if ($closedLockHistory) {
            // EXAMINATION: "MUO < 35mm [E2] AND current limited opening [SQ12=yes]".
            $openingMm = $this->openingMm($eq);
            $limitedOpening = $openingMm !== null && $openingMm < self::MUO_LIMIT_MM && $this->yes($sq, 'SQ12');

            // DIAGNOSIS: ya -> Acute closed lock; tidak -> Other Disc-based TMD.
            return [$limitedOpening ? self::ACUTE_CLOSED_LOCK : self::OTHER_DISC_BASED];
        }

        // Panah "No" dari START B menuju kotak bunyi sendi.
        // HISTORY: TMJ noise by history [SQ8] OR noise during examination [E3]
        // OR current intermittent locking with limited opening [SQ11].
        $e3 = $eq['E3'] ?? null;
        $noiseOrIntermittentLock = in_array($sq['SQ8'] ?? null, ['Sesekali', 'Sering'], true)
            || in_array($e3, ['Kliking', 'Krepitasi'], true)
            || $this->yes($sq, 'SQ11');

        if (! $noiseOrIntermittentLock) {
            return [];
        }

        // EXAMINATION + DIAGNOSIS: crepitus [E3] -> DJD; click [E3] -> Other Disc-based.
        // ("Lock in closed position [E3]" oleh pemeriksa tidak dikumpulkan -> tidak pernah true.)
        if ($e3 === 'Krepitasi') {
            return [self::DEGENERATIVE_JOINT_DISEASE];
        }

        if ($e3 === 'Kliking') {
            return [self::OTHER_DISC_BASED];
        }

        return [];
    }

    /**
     * Halaman 2, kotak START "History of open lock [SQ13] AND history of maneuver
     * to close mouth [SQ14]". Independen dari START B. Kotak pemeriksaan
     * "Lock in open position if clinically present [E3]" tidak disyaratkan.
     *
     * @param  array<string, mixed>  $sq
     * @return list<string>
     */
    private function subluxation(array $sq): array
    {
        return $this->yes($sq, 'SQ13') && $this->yes($sq, 'SQ14') ? [self::SUBLUXATION] : [];
    }

    /**
     * Jawaban SQ bernilai 'Ya'. Kode yang hilang dianggap bukan 'Ya'.
     *
     * @param  array<string, mixed>  $sq
     */
    private function yes(array $sq, string $code): bool
    {
        return ($sq[$code] ?? null) === 'Ya';
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

    /**
     * Bukaan mulut (mm) dari E2; null bila hilang/kosong/non-numerik
     * (tidak pernah dihitung sebagai "< 35").
     *
     * @param  array<string, mixed>  $eq
     */
    private function openingMm(array $eq): int|float|null
    {
        $value = is_array($eq['E2'] ?? null) ? ($eq['E2']['opening_mm'] ?? null) : null;

        return is_numeric($value) ? $value + 0 : null;
    }
}
