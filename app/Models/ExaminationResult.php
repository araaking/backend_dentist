<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'question_code',
        'result',
        'score',
        'location',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
