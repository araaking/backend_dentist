<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Support\TmdQuestionnaire;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Isi tabel `questions` dari definisi kuesioner (idempotent).
     */
    public function run(): void
    {
        foreach (TmdQuestionnaire::seedRows() as $row) {
            Question::updateOrCreate(
                ['code' => $row['code']],
                [
                    'question_text' => $row['question_text'],
                    'type' => $row['type'],
                    'options' => $row['options'],
                ],
            );
        }
    }
}
