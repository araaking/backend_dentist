<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Menghapus akun beserta seluruh data turunannya (pasien, konsultasi, jawaban,
 * diagnosis lewat cascade FK), file foto, dan token API.
 */
class AccountDeletion
{
    public function __construct(private readonly ConsultationPhotoStorage $photos) {}

    public function delete(User $user): void
    {
        $paths = $this->photoPaths($user);

        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->delete();
        });

        foreach ($paths as $path) {
            $this->photos->delete($path);
        }
    }

    /** @return list<string> */
    private function photoPaths(User $user): array
    {
        if (! $user->patient) {
            return [];
        }

        $paths = [];

        Answer::query()
            ->where('question_code', 'E2')
            ->whereHas('consultation', fn ($q) => $q->where('patient_id', $user->patient->id))
            ->pluck('answer')
            ->each(function ($raw) use (&$paths) {
                $data = json_decode((string) $raw, true);
                $path = is_array($data) ? ($data['photo_path'] ?? null) : null;
                if (ConsultationPhotoStorage::isValidPath($path)) {
                    $paths[] = $path;
                }
            });

        return $paths;
    }
}
