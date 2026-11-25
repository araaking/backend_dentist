# Backend Dentist (TMD Self-Diagnosis)

Aplikasi Laravel 13 untuk diagnosis mandiri gangguan sendi rahang (TMD):

- **Web (Blade)**: registrasi/login pasien, formulir self-diagnosis, riwayat, dan dashboard admin.
- **API JSON** (`/api`, Laravel Sanctum, token Bearer) untuk aplikasi mobile Flutter. Dokumentasi: [docs/API.md](docs/API.md).

Data yang diproses adalah **data kesehatan**. Foto bukaan mulut (E2) disimpan di disk privat dan hanya dapat diakses pemilik atau admin.

## Kebutuhan

- PHP **>= 8.3** (diuji di CI pada PHP 8.3 dan 8.4; ekstensi: `fileinfo`, `mbstring`, `pdo_mysql`/`pdo_sqlite`, `openssl`, `ctype`, `json`, `tokenizer`, `xml`)
- Composer 2
- MySQL/MariaDB (atau SQLite untuk pengembangan/test)
- Node.js **>= 22.12** (opsional, hanya untuk build aset Vite 8 / `npm ci && npm run build`)

## Setup lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
# sesuaikan DB_* di .env, lalu:
php artisan migrate --seed
php artisan serve
```

`--seed` mengisi tabel `questions` dan (hanya di environment `local`/`testing`) membuat user uji `test@example.com` / `password`.

Menjalankan test (Pest, SQLite in-memory):

```bash
php artisan test
```

## CI

GitHub Actions (`.github/workflows/tests.yml`) berjalan pada push/pull request ke `main` dan manual (`workflow_dispatch`):

- Job `tests`: matriks PHP 8.3 dan 8.4, `composer install`, `php artisan test`, `composer audit --locked`.
- Job `assets`: Node 22, `npm ci`, `npm run build`, `npm audit --audit-level=high`.

## Admin

Admin ditentukan oleh kolom `users.is_admin` (bukan lagi email tertentu). Tidak ada promosi otomatis.

```bash
# Jadikan user yang sudah ada sebagai admin
php artisan app:make-admin email@contoh.com

# Cabut hak admin (mis. akun squatter / mantan admin)
php artisan app:make-admin email@contoh.com --revoke

# Buat user admin baru (beserta profil pasien); password ditanyakan secara interaktif
php artisan app:make-admin admin@contoh.com --create --name="Administrator"
# atau non-interaktif (password akan tersimpan di shell history, hati-hati):
php artisan app:make-admin admin@contoh.com --create --password='...'
```

## Checklist deploy production

1. **Environment** (`.env`):
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`
   - `LOG_LEVEL=warning`
   - `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true`
   - `SANCTUM_EXPIRATION=43200` (menit; token API kedaluwarsa 30 hari)
   - Kredensial database khusus dengan password kuat
2. **Dependensi & cache**:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan optimize
   ```
3. **Migrasi**: `php artisan migrate --force`
   (menambah `users.is_admin` dan `answers.type`).
4. **Seed master pertanyaan** (aman diulang, tidak membuat user uji di production):
   `php artisan db:seed --class=QuestionSeeder --force`
5. **Periksa akun admin palsu SEBELUM membuat admin.** Versi lama menjadikan siapa pun yang mendaftar dengan `admin@dentist.com` sebagai admin.
   Cek apakah akun itu (atau akun tak dikenal lain) didaftarkan orang lain:
   ```bash
   php artisan tinker --execute="App\Models\User::where('email','admin@dentist.com')->first()"
   ```
   Jika akun tersebut bukan milik Anda (squatter): hapus akunnya (`User::where('email','admin@dentist.com')->delete()` di tinker) atau
   cukup gunakan email admin lain; jika sudah terlanjur memiliki hak admin, cabut dengan `php artisan app:make-admin email --revoke`.
6. **Buat admin pertama**: `php artisan app:make-admin email@anda.com [--create]`
   (cabut hak admin: `php artisan app:make-admin email@anda.com --revoke`).
7. **Pindahkan foto lama ke disk privat** (jika sebelumnya foto tersimpan publik):
   ```bash
   php artisan app:migrate-consultation-photos
   ```
   Perintah ini idempotent dan melaporkan jumlah file yang dipindahkan. Setelah itu hapus symlink `public/storage`
   bila tidak dipakai untuk hal lain (`php artisan storage:link` tidak diperlukan oleh aplikasi ini).
   Foto kini disimpan di `storage/app/private/consultation_photos` -- **sertakan folder ini dalam backup**.
8. **Scheduler** (membersihkan token Sanctum kedaluwarsa setiap hari). Tambahkan cron:
   ```cron
   * * * * * cd /path/ke/aplikasi && php artisan schedule:run >> /dev/null 2>&1
   ```
9. **Web server**: arahkan document root ke `public/`, paksa HTTPS, dan pastikan `storage/` & `bootstrap/cache/` dapat ditulis.
10. **Rate limiting**: login (5/menit per email+IP dan 20/menit per IP), register (10/jam per IP) sudah aktif. Jika di belakang proxy/load balancer,
   konfigurasikan trusted proxies agar IP klien terbaca benar.
11. Pastikan cache/queue/session driver sesuai infrastruktur (default `database`; jalankan `php artisan migrate` agar tabelnya ada).

## Struktur singkat

| Lokasi | Fungsi |
| --- | --- |
| `app/Support/TmdQuestionnaire.php` | Sumber tunggal definisi kuesioner SQ/E1-E4 (API, web, validasi, seeder) |
| `app/Services/TmdDiagnosisService.php` | Logika diagnosis (fungsi murni) |
| `app/Services/ConsultationRecorder.php` | Penyimpanan konsultasi (transaksi) dipakai API & web |
| `app/Services/ConsultationPhotoStorage.php` | Validasi & penyimpanan foto (disk privat) |
| `app/Policies/ConsultationPolicy.php` | Otorisasi lihat konsultasi/foto (pemilik atau admin) |
| `docs/API.md` | Dokumentasi API untuk developer mobile |

## Catatan klinis

Aturan diagnosis bersifat saling eksklusif dan berurutan (Myalgia, Arthralgia, HA-TMD, Joint-related TMD). Ini keputusan klinis yang
perlu dikonfirmasi dengan pakar; lihat komentar di `TmdDiagnosisService`. Hasil aplikasi bukan pengganti pemeriksaan dokter gigi.
