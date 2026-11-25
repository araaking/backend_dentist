<?php

namespace App\Http\Requests;

use App\Support\TmdQuestionnaire;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi konsultasi, dipakai bersama oleh API dan web.
 * Hanya kode pertanyaan dan area yang dikenal yang divalidasi/dipersist;
 * key tak dikenal diabaikan begitu saja.
 */
class StoreConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge(TmdQuestionnaire::validationRules(), [
            // Base64 (mis. dari web / Flutter). ~5 MB biner = ~6,7 juta karakter base64.
            'e2_photo' => ['nullable', 'string', 'max:7000000'],
            // Multipart.
            'e2_photo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);
    }

    public function messages(): array
    {
        return [
            'sq.required' => 'Jawaban pertanyaan subjektif (SQ) wajib diisi.',
            'eq.required' => 'Hasil pemeriksaan (EQ) wajib diisi.',
            'e2_photo.max' => 'Data foto terlalu besar. Ukuran foto maksimal 5 MB.',
            'e2_photo_file.mimes' => 'Foto harus berformat JPG atau PNG.',
            'e2_photo_file.max' => 'Ukuran foto maksimal 5 MB.',
        ];
    }
}
