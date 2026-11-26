<?php

namespace App\Models;

use App\Services\ConsultationPhotoStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'consultation_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'consultation_date' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function examinationResults()
    {
        return $this->hasMany(ExaminationResult::class);
    }

    public function diagnoses()
    {
        // Urutan diagnosis bermakna (yang pertama dianggap utama); `id` satu-satunya kunci yang andal
        // karena semua baris satu konsultasi memiliki timestamp yang sama.
        return $this->hasMany(Diagnosis::class)->orderBy('id');
    }

    /**
     * Path foto E2 pada disk privat, atau null bila tidak ada / path tidak aman.
     * Memakai relasi `answers` (di-load bila belum).
     */
    public function photoPath(): ?string
    {
        $answer = $this->answers->firstWhere('question_code', 'E2');
        if (! $answer) {
            return null;
        }

        $data = json_decode((string) $answer->answer, true);
        $path = is_array($data) ? ($data['photo_path'] ?? null) : null;

        return ConsultationPhotoStorage::isValidPath($path) ? $path : null;
    }

    /** Nilai bukaan mulut (mm) dari jawaban E2, atau null. */
    public function openingMm(): int|float|string|null
    {
        $answer = $this->answers->firstWhere('question_code', 'E2');
        $data = $answer ? json_decode((string) $answer->answer, true) : null;

        return is_array($data) ? ($data['opening_mm'] ?? null) : null;
    }
}
