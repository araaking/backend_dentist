<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index(Request $request)
    {
        // SQ questions metadata (consistent with Web\ConsultationController and Blade views)
        $sq = [
            [
                'code' => 'SQ1',
                'text' => 'Apakah Anda merasakan nyeri di rahang, pelipis, atau telinga?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ2',
                'text' => 'Berapa lama nyeri di rahang/pelipis/telinga berlangsung? (Jawaban: < 1 minggu atau >= 1 minggu)',
                'input' => [
                    'type' => 'radio',
                    'options' => ['< 1 minggu', '>= 1 minggu'],
                    'default' => '< 1 minggu',
                ],
            ],
            [
                'code' => 'SQ3',
                'text' => 'Bagaimana sifat nyeri? Apakah persisten atau kambuhan?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ4',
                'text' => 'Apakah aktivitas (mengunyah makanan keras, membuka mulut lebar, kebiasaan rahang, aktivitas rahang lain) memengaruhi rasa sakit?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ5',
                'text' => 'Apakah Anda mengalami sakit kepala di pelipis?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ6',
                'text' => 'Berapa lama sakit kepala itu berlangsung? (Jawaban: < 1x/minggu atau >= 1x/minggu)',
                'input' => [
                    'type' => 'radio',
                    'options' => ['< 1x/minggu', '>= 1x/minggu', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ7',
                'text' => 'Apakah aktivitas rahang mengubah sakit kepala Anda?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ8',
                'text' => 'Apakah Anda mendengar bunyi pada rahang? (Jawaban: Tidak ada, Sesekali, Sering)',
                'input' => [
                    'type' => 'select',
                    'options' => ['Tidak ada', 'Sesekali', 'Sering'],
                    'default' => 'Tidak ada',
                ],
            ],
            [
                'code' => 'SQ9',
                'text' => 'Apakah rahang Anda pernah terkunci sehingga tidak bisa terbuka penuh?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ10',
                'text' => 'Apakah rahang pernah terkunci parah sehingga mulut tidak bisa terbuka dan makan terganggu?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ11',
                'text' => 'Apakah rahang pernah terkunci sehingga tidak bisa terbuka walau sebentar?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ12',
                'text' => 'Apakah sekarang rahang terkunci sehingga pembukaan terbatas?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ13',
                'text' => 'Apakah pernah mengalami rahang terkunci dalam posisi terbuka sehingga tidak bisa menutup?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
            [
                'code' => 'SQ14',
                'text' => 'Apakah Anda harus memanipulasi/ menggeser rahang agar bisa menutup kembali setelah terbuka?',
                'input' => [
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                    'default' => 'Tidak',
                ],
            ],
        ];

        // EQ questions metadata
        $eScoreLabels = [
            '0' => '0 - Tidak Nyeri',
            '1' => '1 - Nyeri Ringan',
            '2' => '2 - Nyeri Sedang',
            '3' => '3 - Nyeri Berat',
        ];

        $eq = [
            [
                'code' => 'E1',
                'text' => 'Pemeriksaan Nyeri Tekan Ringan',
                'input' => [
                    'type' => 'group_select_scores',
                    'areas' => ['Temporalis', 'Masseter', 'TMJ'],
                    'min' => 0,
                    'max' => 3,
                    'labels' => $eScoreLabels,
                ],
                'assets' => [
                    'image' => asset('images/e1.png'),
                ],
            ],
            [
                'code' => 'E2',
                'text' => 'Pengukuran Bukaan Mulut Maksimal',
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
                'text' => 'Pemeriksaan Bunyi Sendi Rahang',
                'input' => [
                    'type' => 'radio',
                    'options' => [
                        ['value' => 'Tidak ada', 'label' => 'Tidak ada bunyi'],
                        ['value' => 'Kliking', 'label' => 'Bunyi kliking', 'audio' => asset('sounds/kliktunggaldanganda.wav')],
                        ['value' => 'Krepitasi', 'label' => 'Bunyi kasar seperti pasir (krepitasi)', 'audio' => asset('sounds/krepitasi.wav')],
                    ],
                    'default' => 'Tidak ada',
                ],
            ],
            [
                'code' => 'E4',
                'text' => 'Pemeriksaan Nyeri Tekan Lebih Dalam',
                'input' => [
                    'type' => 'group_select_scores',
                    'areas' => ['Temporalis', 'Masseter', 'TMJ'],
                    'min' => 0,
                    'max' => 3,
                    'labels' => $eScoreLabels,
                ],
            ],
        ];

        return response()->json([
            'sq' => $sq,
            'eq' => $eq,
        ]);
    }
}