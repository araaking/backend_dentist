<?php

namespace App\Console\Commands;

use App\Services\ConsultationPhotoStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateConsultationPhotos extends Command
{
    protected $signature = 'app:migrate-consultation-photos
        {--keep-original : Jangan hapus file di disk public setelah disalin}';

    protected $description = 'Pindahkan foto konsultasi lama dari disk public ke disk privat (local) dengan path relatif yang sama';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk(ConsultationPhotoStorage::DISK);
        $directory = ConsultationPhotoStorage::DIRECTORY;

        $moved = 0;
        $alreadyPresent = 0;
        $failed = 0;

        foreach ($public->files($directory) as $path) {
            // Idempotent: bila sudah ada di disk privat, cukup bersihkan salinan publik.
            if ($private->exists($path)) {
                if ($private->size($path) === $public->size($path)) {
                    if (! $this->option('keep-original')) {
                        $public->delete($path);
                    }
                    $alreadyPresent++;
                } else {
                    $this->warn("Konflik (ukuran berbeda), dilewati: {$path}");
                    $failed++;
                }

                continue;
            }

            $stream = $public->readStream($path);

            if ($stream === null || $stream === false || ! $private->writeStream($path, $stream)) {
                $this->error("Gagal menyalin: {$path}");
                $failed++;

                continue;
            }

            if (is_resource($stream)) {
                fclose($stream);
            }

            if ($private->size($path) !== $public->size($path)) {
                $private->delete($path);
                $this->error("Ukuran tidak cocok setelah salin: {$path}");
                $failed++;

                continue;
            }

            if (! $this->option('keep-original')) {
                $public->delete($path);
            }

            $moved++;
        }

        $this->info("Selesai. Dipindahkan: {$moved}, sudah ada di disk privat: {$alreadyPresent}, gagal/dilewati: {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
