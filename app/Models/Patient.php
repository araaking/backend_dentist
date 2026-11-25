<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * `user_id` dan `patient_id` sengaja TIDAK mass-assignable.
     * Buat lewat relasi: $user->patient()->create([...]).
     */
    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    /** Label jenis kelamin untuk tampilan ('-' bila kosong). */
    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'Male' => 'Laki-laki',
            'Female' => 'Perempuan',
            default => '-',
        };
    }

    /** Tanggal lahir terformat d/m/Y untuk tampilan ('-' bila kosong). */
    public function getBirthDateLabelAttribute(): string
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->format('d/m/Y') : '-';
    }

    /**
     * Pencarian admin: nama pasien, nomor telepon, atau email user.
     * Karakter wildcard LIKE di-escape (ESCAPE '!').
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_substr($term, 0, 100)).'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->whereRaw("patients.name LIKE ? ESCAPE '!'", [$like])
                ->orWhereRaw("patients.phone_number LIKE ? ESCAPE '!'", [$like])
                ->orWhereHas('user', fn (Builder $u) => $u->whereRaw("users.email LIKE ? ESCAPE '!'", [$like]));
        });
    }
}
