<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Penyimpanan foto E2 (bukaan mulut). Foto adalah data medis: disimpan di disk
 * privat `local` (bukan `public`) dengan nama UUID, dan hanya disajikan lewat
 * route yang memeriksa kepemilikan.
 */
class ConsultationPhotoStorage
{
    public const DISK = 'local';

    public const DIRECTORY = 'consultation_photos';

    public const MAX_BYTES = 5 * 1024 * 1024;

    /** @var array<string, string> mime => ekstensi */
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    /**
     * Simpan file hasil multipart. Mengembalikan path relatif pada disk.
     *
     * @throws ValidationException
     */
    public function storeUploadedFile(UploadedFile $file, string $field = 'e2_photo_file'): string
    {
        $binary = $file->get();

        if (! is_string($binary) || $binary === '') {
            throw $this->invalid($field);
        }

        return $this->storeBinary($binary, $field);
    }

    /**
     * Simpan gambar base64 (boleh berawalan data URI).
     *
     * @throws ValidationException
     */
    public function storeBase64(string $input, string $field = 'e2_photo'): string
    {
        return $this->storeBinary($this->decodeBase64($input, $field), $field);
    }

    /**
     * Decode base64 secara ketat; menolak input yang bukan base64 atau > 5 MB.
     *
     * @throws ValidationException
     */
    public function decodeBase64(string $input, string $field = 'e2_photo'): string
    {
        $input = trim($input);
        $input = preg_replace('/^data:[a-z0-9.+\-\/]+;base64,/i', '', $input) ?? $input;

        // Perkiraan batas awal agar tidak men-decode string raksasa.
        if (strlen($input) > (int) ceil(self::MAX_BYTES * 4 / 3) + 16) {
            throw $this->invalid($field, 'Ukuran foto maksimal 5 MB.');
        }

        $binary = base64_decode($input, true);

        if ($binary === false || $binary === '') {
            throw $this->invalid($field);
        }

        if (strlen($binary) > self::MAX_BYTES) {
            throw $this->invalid($field, 'Ukuran foto maksimal 5 MB.');
        }

        return $binary;
    }

    /**
     * Pastikan data benar-benar gambar JPEG/PNG; kembalikan ekstensi yang benar.
     *
     * @throws ValidationException
     */
    public function detectExtension(string $binary, string $field = 'e2_photo'): string
    {
        if (strlen($binary) > self::MAX_BYTES) {
            throw $this->invalid($field, 'Ukuran foto maksimal 5 MB.');
        }

        $info = @getimagesizefromstring($binary);
        if ($info === false) {
            throw $this->invalid($field);
        }

        $mime = $info['mime'] ?? null;

        if (class_exists(\finfo::class)) {
            $detected = (new \finfo(FILEINFO_MIME_TYPE))->buffer($binary);
            if (! is_string($detected) || $detected !== $mime) {
                throw $this->invalid($field);
            }
        }

        if (! is_string($mime) || ! isset(self::ALLOWED[$mime])) {
            throw $this->invalid($field, 'Foto harus berformat JPG atau PNG.');
        }

        return self::ALLOWED[$mime];
    }

    public function delete(?string $path): void
    {
        if ($path !== null && self::isValidPath($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Sajikan foto sebagai response (sudah harus lolos otorisasi).
     */
    public function response(string $path): Response
    {
        $disk = Storage::disk(self::DISK);

        if (! self::isValidPath($path) || ! $disk->exists($path)) {
            abort(404);
        }

        return $disk->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Path yang valid hanya consultation_photos/<nama-file-aman>. Data lama
     * bisa berisi path buatan pengguna, jadi selalu diperiksa sebelum dipakai.
     */
    public static function isValidPath(mixed $path): bool
    {
        return is_string($path)
            && preg_match('#^'.self::DIRECTORY.'/[A-Za-z0-9._-]+$#', $path) === 1
            && ! str_contains($path, '..');
    }

    private function storeBinary(string $binary, string $field): string
    {
        $extension = $this->detectExtension($binary, $field);
        $path = self::DIRECTORY.'/'.Str::uuid()->toString().'.'.$extension;

        Storage::disk(self::DISK)->put($path, $binary);

        return $path;
    }

    private function invalid(string $field, string $message = 'Foto tidak valid. Gunakan gambar JPG atau PNG yang benar.'): ValidationException
    {
        return ValidationException::withMessages([$field => [$message]]);
    }
}
