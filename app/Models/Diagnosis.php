<?php

namespace App\Models;

use App\Services\TmdDiagnosisService as Tmd;
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
        Tmd::MYALGIA => 'Nyeri yang berasal dari otot-otot pengunyahan. Gejala khasnya adalah nyeri yang diperparah oleh fungsi rahang, seperti mengunyah atau berbicara.',
        Tmd::ARTHRALGIA => 'Nyeri yang berasal dari sendi temporomandibular (TMJ). Nyeri ini biasanya terlokalisasi di area sendi dan diperparah saat sendi digerakkan.',
        Tmd::HA_TMD => 'Sakit kepala yang disebabkan oleh gangguan pada sendi atau otot rahang. Nyeri biasanya terasa di area pelipis dan diperparah oleh aktivitas rahang. Dokter gigi tetap perlu memastikan bahwa sakit kepala ini tidak lebih tepat dijelaskan oleh diagnosis sakit kepala lain.',
        Tmd::DEGENERATIVE_JOINT_DISEASE => 'Perubahan degeneratif pada sendi rahang yang ditandai bunyi kasar seperti pasir (krepitasi) saat rahang digerakkan. Dapat disertai keterbatasan gerakan rahang.',
        Tmd::ACUTE_CLOSED_LOCK => 'Rahang terkunci dalam posisi tertutup sehingga mulut sulit dibuka (bukaan mulut kurang dari 35 mm), biasanya karena bantalan sendi bergeser dan tidak kembali ke posisinya (disc displacement without reduction with limited opening).',
        Tmd::OTHER_DISC_BASED => 'Gangguan pada bantalan (diskus) sendi rahang, ditandai bunyi klik saat membuka dan menutup mulut atau riwayat rahang terkunci. Dapat disertai keterbatasan gerakan rahang. Pencitraan (imaging) belum dipertimbangkan pada tahap evaluasi ini.',
        Tmd::SUBLUXATION => 'Rahang pernah terkunci dalam posisi terbuka dan perlu digeser/dimanipulasi agar mulut bisa menutup kembali. Bila terkunci terbuka lebih dari beberapa detik, kondisi ini disebut luksasi (dislokasi).',
        // LEGACY: tidak lagi dihasilkan; hanya untuk konsultasi lama yang tersimpan di database.
        Tmd::JOINT_RELATED => 'Gangguan yang terkait dengan struktur internal sendi rahang. Ini bisa mencakup bunyi sendi (klik), rahang terkunci (terbuka atau tertutup), atau perubahan degeneratif pada sendi.',
        Tmd::NONE => 'Tidak ada cukup bukti untuk salah satu diagnosis TMD spesifik berdasarkan jawaban yang diberikan.',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
