<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'name',
        'is_confirmed',
    ];

    public const DESCRIPTIONS = [
        'Myalgia' => 'Nyeri yang berasal dari otot-otot pengunyahan. Gejala khasnya adalah nyeri yang diperparah oleh fungsi rahang, seperti mengunyah atau berbicara.',
        'Arthralgia' => 'Nyeri yang berasal dari sendi temporomandibular (TMJ). Nyeri ini biasanya terlokalisasi di area sendi dan diperparah saat sendi digerakkan.',
        'Headache attributed to TMD (HA-TMD)' => 'Sakit kepala yang disebabkan oleh gangguan pada sendi atau otot rahang. Nyeri biasanya terasa di area pelipis dan diperparah oleh aktivitas rahang.',
        'Joint-related TMD' => 'Gangguan yang terkait dengan struktur internal sendi rahang. Ini bisa mencakup bunyi sendi (klik), rahang terkunci (terbuka atau tertutup), atau perubahan degeneratif pada sendi.',
        'No specific TMD diagnosis found.' => 'Tidak ada cukup bukti untuk salah satu diagnosis TMD spesifik berdasarkan jawaban yang diberikan.'
    ];


    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
