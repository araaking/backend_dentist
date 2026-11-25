<?php

namespace App\Support;

use Illuminate\Validation\Rule;

/**
 * Sumber kebenaran tunggal untuk kuesioner TMD (SQ1-SQ14 dan E1-E4).
 *
 * Dipakai oleh API (/api/questions), form web, validasi input, seeder, dan
 * layanan diagnosis. Jangan menduplikasi definisi pertanyaan di tempat lain.
 */
final class TmdQuestionnaire
{
    public const SCORE_MIN = 0;

    public const SCORE_MAX = 3;

    public const E2_MAX_OPENING_MM = 100;

    /** Area pemeriksaan untuk E1 dan E4. */
    public const AREAS = ['Temporalis', 'Masseter', 'TMJ'];

    /** Label area untuk tampilan web. */
    public const AREA_LABELS = [
        'Temporalis' => 'Pelipis (Temporalis)',
        'Masseter' => 'Pipi (Masseter)',
        'TMJ' => 'Sendi Rahang (TMJ)',
    ];

    /** Kode pertanyaan EQ yang berisi skor per area. */
    public const SCORE_GROUPS = ['E1', 'E4'];

    /** Label skor 0-3 (indeks = skor). */
    public const SCORE_LABELS = [
        0 => '0 - Tidak Nyeri',
        1 => '1 - Nyeri Ringan',
        2 => '2 - Nyeri Sedang',
        3 => '3 - Nyeri Berat',
    ];

    /**
     * Label opsi khusus untuk tampilan web (nilai yang disimpan tetap nilai opsi).
     * Format: kode => [nilai opsi => label tampilan].
     */
    public const WEB_OPTION_LABELS = [
        'SQ2' => ['< 1 minggu' => 'Kurang dari 1 minggu', '>= 1 minggu' => '1 minggu atau lebih'],
        'SQ3' => ['Ya' => 'Ya (Persisten/Kambuhan)'],
        'SQ6' => ['< 1x/minggu' => 'Kurang dari 1x/minggu', '>= 1x/minggu' => '1x/minggu atau lebih'],
    ];

    /**
     * Pertanyaan subjektif, berurutan.
     *
     * @return array<string, array{text: string, type: string, options: list<string>, default: string}>
     */
    public static function sq(): array
    {
        $yesNo = ['Ya', 'Tidak'];

        return [
            'SQ1' => ['text' => 'Apakah Anda merasakan nyeri di rahang, pelipis, atau telinga?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ2' => ['text' => 'Berapa lama nyeri di rahang/pelipis/telinga berlangsung? (Jawaban: < 1 minggu atau >= 1 minggu)', 'type' => 'radio', 'options' => ['< 1 minggu', '>= 1 minggu'], 'default' => '< 1 minggu'],
            'SQ3' => ['text' => 'Bagaimana sifat nyeri? Apakah persisten atau kambuhan?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ4' => ['text' => 'Apakah aktivitas (mengunyah makanan keras, membuka mulut lebar, kebiasaan rahang, aktivitas rahang lain) memengaruhi rasa sakit?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ5' => ['text' => 'Apakah Anda mengalami sakit kepala di pelipis?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ6' => ['text' => 'Berapa lama sakit kepala itu berlangsung? (Jawaban: < 1x/minggu atau >= 1x/minggu)', 'type' => 'radio', 'options' => ['< 1x/minggu', '>= 1x/minggu', 'Tidak'], 'default' => 'Tidak'],
            'SQ7' => ['text' => 'Apakah aktivitas rahang mengubah sakit kepala Anda?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ8' => ['text' => 'Apakah Anda mendengar bunyi pada rahang? (Jawaban: Tidak ada, Sesekali, Sering)', 'type' => 'select', 'options' => ['Tidak ada', 'Sesekali', 'Sering'], 'default' => 'Tidak ada'],
            'SQ9' => ['text' => 'Apakah rahang Anda pernah terkunci sehingga tidak bisa terbuka penuh?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ10' => ['text' => 'Apakah rahang pernah terkunci parah sehingga mulut tidak bisa terbuka dan makan terganggu?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ11' => ['text' => 'Apakah rahang pernah terkunci sehingga tidak bisa terbuka walau sebentar?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ12' => ['text' => 'Apakah sekarang rahang terkunci sehingga pembukaan terbatas?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ13' => ['text' => 'Apakah pernah mengalami rahang terkunci dalam posisi terbuka sehingga tidak bisa menutup?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
            'SQ14' => ['text' => 'Apakah Anda harus memanipulasi/ menggeser rahang agar bisa menutup kembali setelah terbuka?', 'type' => 'radio', 'options' => $yesNo, 'default' => 'Tidak'],
        ];
    }

    /** @return list<string> */
    public static function sqCodes(): array
    {
        return array_keys(self::sq());
    }

    /** @return array<string, string> kode => judul */
    public static function eqTitles(): array
    {
        return [
            'E1' => 'Pemeriksaan Nyeri Tekan Ringan',
            'E2' => 'Pengukuran Bukaan Mulut Maksimal',
            'E3' => 'Pemeriksaan Bunyi Sendi Rahang',
            'E4' => 'Pemeriksaan Nyeri Tekan Lebih Dalam',
        ];
    }

    /**
     * Opsi E3. 'audio' berupa path relatif terhadap public/ (opsional).
     *
     * @return list<array{value: string, label: string, audio?: string}>
     */
    public static function e3Options(): array
    {
        return [
            ['value' => 'Tidak ada', 'label' => 'Tidak ada bunyi'],
            ['value' => 'Kliking', 'label' => 'Bunyi kliking', 'audio' => 'sounds/kliktunggaldanganda.wav'],
            ['value' => 'Krepitasi', 'label' => 'Bunyi kasar seperti pasir (krepitasi)', 'audio' => 'sounds/krepitasi.wav'],
        ];
    }

    /** @return list<string> */
    public static function e3Values(): array
    {
        return array_column(self::e3Options(), 'value');
    }

    public static function e3Default(): string
    {
        return 'Tidak ada';
    }

    /**
     * Struktur JSON untuk GET /api/questions. Struktur ini adalah kontrak
     * dengan aplikasi mobile: jangan ubah/hapus key yang sudah ada.
     *
     * @return array{sq: list<array<string, mixed>>, eq: list<array<string, mixed>>}
     */
    public static function apiPayload(): array
    {
        $sq = [];
        foreach (self::sq() as $code => $q) {
            $sq[] = [
                'code' => $code,
                'text' => $q['text'],
                'input' => [
                    'type' => $q['type'],
                    'options' => $q['options'],
                    'default' => $q['default'],
                ],
            ];
        }

        $titles = self::eqTitles();

        $e3Options = array_map(function (array $option) {
            if (isset($option['audio'])) {
                $option['audio'] = asset($option['audio']);
            }

            return $option;
        }, self::e3Options());

        $eq = [
            [
                'code' => 'E1',
                'text' => $titles['E1'],
                'input' => [
                    'type' => 'group_select_scores',
                    'areas' => self::AREAS,
                    'min' => self::SCORE_MIN,
                    'max' => self::SCORE_MAX,
                    'labels' => self::SCORE_LABELS,
                ],
                'assets' => [
                    'image' => asset('images/e1.png'),
                ],
            ],
            [
                'code' => 'E2',
                'text' => $titles['E2'],
                'input' => [
                    'type' => 'composite',
                    'fields' => [
                        [
                            'name' => 'opening_mm',
                            'type' => 'number',
                            'placeholder' => 'Contoh: 40',
                            'required' => true,
                            'unit' => 'mm',
                            'min' => 0,
                        ],
                    ],
                    'photo' => [
                        'accepted' => ['base64', 'multipart'],
                        'base64_field' => 'e2_photo',
                        'multipart_field' => 'e2_photo_file',
                        'accept_mime' => ['image/jpeg', 'image/png'],
                        'max_size_mb' => 5,
                    ],
                ],
            ],
            [
                'code' => 'E3',
                'text' => $titles['E3'],
                'input' => [
                    'type' => 'radio',
                    'options' => $e3Options,
                    'default' => self::e3Default(),
                ],
            ],
            [
                'code' => 'E4',
                'text' => $titles['E4'],
                'input' => [
                    'type' => 'group_select_scores',
                    'areas' => self::AREAS,
                    'min' => self::SCORE_MIN,
                    'max' => self::SCORE_MAX,
                    'labels' => self::SCORE_LABELS,
                ],
            ],
        ];

        return ['sq' => $sq, 'eq' => $eq];
    }

    /**
     * Aturan validasi (whitelist) untuk input sq/eq. Hanya kode & area yang
     * dikenal yang punya aturan, sehingga hanya itu yang muncul di validated().
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        $rules = [
            'sq' => ['required', 'array'],
            'eq' => ['required', 'array'],
            'eq.E1' => ['nullable', 'array'],
            'eq.E2' => ['nullable', 'array'],
            'eq.E4' => ['nullable', 'array'],
            'eq.E2.opening_mm' => ['nullable', 'numeric', 'min:0', 'max:'.self::E2_MAX_OPENING_MM],
            'eq.E3' => ['nullable', Rule::in(self::e3Values())],
        ];

        foreach (self::sq() as $code => $q) {
            $rules["sq.$code"] = ['nullable', Rule::in($q['options'])];
        }

        foreach (self::SCORE_GROUPS as $group) {
            foreach (self::AREAS as $area) {
                $rules["eq.$group.$area"] = ['nullable', 'integer', 'between:'.self::SCORE_MIN.','.self::SCORE_MAX];
            }
        }

        return $rules;
    }

    /**
     * Ambil hanya kode/area yang dikenal dan normalkan tipenya.
     * Nilai null/kosong/tidak valid dibuang.
     *
     * @param  array<string, mixed>  $sq
     * @param  array<string, mixed>  $eq
     * @return array{sq: array<string, string>, eq: array<string, mixed>}
     */
    public static function sanitize(array $sq, array $eq): array
    {
        $cleanSq = [];
        foreach (self::sq() as $code => $q) {
            $value = $sq[$code] ?? null;
            if (is_string($value) && in_array($value, $q['options'], true)) {
                $cleanSq[$code] = $value;
            }
        }

        $cleanEq = [];

        foreach (self::SCORE_GROUPS as $group) {
            $scores = [];
            $input = is_array($eq[$group] ?? null) ? $eq[$group] : [];
            foreach (self::AREAS as $area) {
                $value = $input[$area] ?? null;
                if (is_numeric($value) && (int) $value == $value
                    && (int) $value >= self::SCORE_MIN && (int) $value <= self::SCORE_MAX) {
                    $scores[$area] = (int) $value;
                }
            }
            if ($scores !== []) {
                $cleanEq[$group] = $scores;
            }
        }

        $opening = is_array($eq['E2'] ?? null) ? ($eq['E2']['opening_mm'] ?? null) : null;
        if (is_numeric($opening) && $opening >= 0 && $opening <= self::E2_MAX_OPENING_MM) {
            $cleanEq['E2'] = ['opening_mm' => $opening + 0];
        }

        $e3 = $eq['E3'] ?? null;
        if (is_string($e3) && in_array($e3, self::e3Values(), true)) {
            $cleanEq['E3'] = $e3;
        }

        return ['sq' => $cleanSq, 'eq' => $cleanEq];
    }

    /**
     * Data untuk tabel `questions` (dipakai QuestionSeeder).
     *
     * @return list<array{code: string, question_text: string, type: string, options: mixed}>
     */
    public static function seedRows(): array
    {
        $rows = [];
        foreach (self::sq() as $code => $q) {
            $rows[] = [
                'code' => $code,
                'question_text' => $q['text'],
                'type' => 'Subjective',
                'options' => $q['options'],
            ];
        }

        $titles = self::eqTitles();
        $scoreOptions = ['areas' => self::AREAS, 'min' => self::SCORE_MIN, 'max' => self::SCORE_MAX];

        $rows[] = ['code' => 'E1', 'question_text' => $titles['E1'], 'type' => 'Objective', 'options' => $scoreOptions];
        $rows[] = ['code' => 'E2', 'question_text' => $titles['E2'], 'type' => 'Objective', 'options' => null];
        $rows[] = ['code' => 'E3', 'question_text' => $titles['E3'], 'type' => 'Objective', 'options' => self::e3Values()];
        $rows[] = ['code' => 'E4', 'question_text' => $titles['E4'], 'type' => 'Objective', 'options' => $scoreOptions];

        return $rows;
    }
}
