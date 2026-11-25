<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'question_code',
        'answer',
        'type',
    ];

    /**
     * `value` = jawaban yang sudah di-decode bila berupa JSON valid, selain itu string asli.
     * (Tambahan; field `answer` tetap ada.)
     */
    protected $appends = ['value'];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    protected function value(): Attribute
    {
        return Attribute::get(function () {
            $raw = $this->attributes['answer'] ?? null;

            if (! is_string($raw)) {
                return $raw;
            }

            $decoded = json_decode($raw, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $raw;
        });
    }
}
