<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'question_code',
        'answer',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
