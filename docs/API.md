# Dokumentasi API (untuk aplikasi mobile Flutter)

API JSON untuk aplikasi self-diagnosis TMD. Semua endpoint berada di bawah prefix `/api`.

- **Base URL**: `https://<domain-anda>/api` (lokal: `http://localhost:8000/api`)
- **Format**: JSON. Kirim `Accept: application/json` (dan `Content-Type: application/json` untuk body JSON).
  Seluruh error di `/api/*` selalu berupa JSON, bahkan tanpa header `Accept`.
- **Autentikasi**: token Bearer (Laravel Sanctum). Kirim `Authorization: Bearer <token>` pada semua endpoint selain register/login.

## Daftar isi

1. [Autentikasi, masa berlaku token, dan rate limit](#autentikasi-masa-berlaku-token-dan-rate-limit)
2. [Format error](#format-error)
3. [Endpoint](#endpoint)
4. [Kontrak metadata pertanyaan](#kontrak-metadata-pertanyaan)
5. [Mengirim konsultasi (JSON vs multipart)](#mengirim-konsultasi)
6. [Mengambil foto](#mengambil-foto)
7. [Perubahan untuk Mobile](#perubahan-untuk-mobile)

---

## Autentikasi, masa berlaku token, dan rate limit

1. Panggil `POST /api/auth/register` atau `POST /api/auth/login` untuk mendapat `token`.
2. Simpan token di penyimpanan aman (mis. `flutter_secure_storage`), kirim di header `Authorization: Bearer <token>`.
3. **Token berlaku 30 hari** sejak dibuat (bukan sejak terakhir dipakai). Setelah kedaluwarsa, request mengembalikan **401**.
4. **Tangani 401**: hapus token lokal lalu arahkan pengguna login ulang. Jangan mencoba ulang otomatis tanpa login.
5. Logout: `POST /api/auth/logout` (token saat ini) atau `POST /api/auth/logout-all` (semua perangkat).

### Rate limit (HTTP 429)

| Endpoint | Batas |
| --- | --- |
| `POST /api/auth/login` | 5 percobaan per menit per kombinasi email + IP, dan maks 20 per menit per IP (berapa pun emailnya) |
| `POST /api/auth/register` | 10 per jam per IP |
| `DELETE /api/account` | 5 per menit per user |

Saat terlampaui, server membalas **429** dengan header `Retry-After` (detik) dan body:

```json
{ "message": "Too Many Attempts." }
```

Tampilkan pesan "Terlalu banyak percobaan, coba lagi nanti" dan tunggu sesuai `Retry-After`.

---

## Format error

| Status | Arti | Body |
| --- | --- | --- |
| 401 | Token tidak ada / tidak valid / kedaluwarsa | `{"message": "Unauthenticated."}` |
| 403 | Tidak berhak mengakses resource | `{"message": "Akses ditolak."}` (atau pesan standar Laravel, mis. `"This action is unauthorized."`) |
| 404 | Resource/profil tidak ditemukan | `{"message": "Profil pasien belum dibuat."}` atau `{"message": "..."}` |
| 409 | Konflik (profil pasien sudah ada) | `{"message": "..."}` |
| 422 | Validasi gagal | lihat di bawah |
| 429 | Terlalu banyak request | `{"message": "Too Many Attempts."}` + header `Retry-After` |
| 500 | Kesalahan server | `{"message": "Server Error"}` |

### Bentuk 422

```json
{
  "message": "The email field is required. (and 1 more error)",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field must be at least 8 characters."]
  }
}
```

Key di `errors` adalah nama field (untuk field bersarang memakai titik, mis. `sq.SQ1`, `eq.E1.TMJ`, `eq.E2.opening_mm`).
Tampilkan pesan pertama dari tiap field.

---

## Endpoint

### POST `/api/auth/register`

Tanpa autentikasi. Membuat user + profil pasien (dalam satu transaksi) dan mengembalikan token.

| Field | Wajib | Aturan |
| --- | --- | --- |
| `name` | ya | string, maks 255 |
| `email` | ya | email, unik |
| `password` | ya | string, min 8 |
| `date_of_birth` | tidak | tanggal (`YYYY-MM-DD`) |
| `gender` | tidak | `Male` atau `Female` |
| `phone_number` | tidak | string, maks 15 |

**201**

```json
{
  "user": { "id": 1, "email": "budi@example.com", "is_admin": false, "created_at": "...", "updated_at": "..." },
  "patient": { "id": 1, "name": "Budi", "date_of_birth": "1995-03-20", "gender": "Male", "phone_number": "08123456789", "user_id": 1, "created_at": "...", "updated_at": "..." },
  "token": "1|abcdef...",
  "token_type": "Bearer"
}
```

Field opsional yang tidak dikirim akan bernilai `null` pada `patient`. Abaikan `user.is_admin` di aplikasi mobile.

### POST `/api/auth/login`

| Field | Wajib |
| --- | --- |
| `email` | ya |
| `password` | ya |

**200**

```json
{
  "user": { "id": 1, "email": "budi@example.com", "is_admin": false, "...": "..." },
  "token": "2|abcdef...",
  "token_type": "Bearer"
}
```

Kredensial salah: **422** `{"message": "The provided credentials are incorrect.", "errors": {"email": ["The provided credentials are incorrect."]}}`.
Terlalu sering: **429**.

### GET `/api/auth/me`

**200**: objek user (`id`, `email`, `is_admin`, ...).

### POST `/api/auth/logout`

Mencabut token yang sedang dipakai. **200** `{"message": "Logged out"}`.

### POST `/api/auth/logout-all` (baru)

Mencabut **semua** token user (semua perangkat). **200** `{"message": "Logged out from all devices"}`.

### DELETE `/api/account` (baru)

Menghapus akun **permanen** (profil pasien, seluruh konsultasi, jawaban, diagnosis, foto, dan semua token).
Wajib untuk kebijakan App Store / Google Play. Tampilkan konfirmasi sebelum memanggil.

Body: `{"password": "<password saat ini>"}`

**200** `{"message": "Akun berhasil dihapus."}`.
Password salah/kosong: **422** `{"errors": {"password": ["Password salah."]}}`. Setelah sukses, hapus token lokal dan kembali ke layar login.

### GET `/api/patient`

**200** `{"patient": { ... }}`. **404** `{"message": "Profil pasien belum dibuat."}` bila belum ada.

### POST `/api/patient`

Membuat profil (jika belum ada; register sudah membuatnya otomatis).

| Field | Wajib | Aturan |
| --- | --- | --- |
| `name` | ya | maks 255 |
| `date_of_birth` | tidak | tanggal |
| `gender` | tidak | `Male` / `Female` |
| `phone_number` | tidak | maks 15 |

**201** `{"message": "Profil pasien berhasil dibuat.", "patient": {...}}`. **409** bila profil sudah ada.

### PUT `/api/patient`

Field sama seperti di atas, semuanya opsional (`sometimes`); hanya field yang dikirim yang diubah.
**200** `{"message": "Profil pasien berhasil diperbarui.", "patient": {...}}`.

### GET `/api/questions`

Metadata kuesioner. Lihat [Kontrak metadata pertanyaan](#kontrak-metadata-pertanyaan).

### GET `/api/consultations`

Daftar konsultasi milik user (terbaru dulu).

```json
{
  "consultations": [
    {
      "id": 12,
      "patient_id": 1,
      "consultation_date": "2026-01-10T08:30:00.000000Z",
      "notes": null,
      "created_at": "...",
      "updated_at": "...",
      "diagnoses": [
        { "id": 20, "consultation_id": 12, "name": "Myalgia", "is_confirmed": true, "created_at": "...", "updated_at": "..." }
      ],
      "e2_photo_url": "https://<domain>/api/consultations/12/photo"
    }
  ]
}
```

`e2_photo_url` (baru) berisi URL absolut foto atau `null` bila konsultasi tanpa foto. **404** bila profil pasien belum dibuat.

### POST `/api/consultations`

Lihat [Mengirim konsultasi](#mengirim-konsultasi).

**201**

```json
{
  "message": "Konsultasi berhasil dibuat.",
  "consultation_id": 12,
  "diagnoses": ["Myalgia"],
  "e2_photo_url": "https://<domain>/api/consultations/12/photo"
}
```

`diagnoses` berisi **satu atau lebih** nama dengan urutan tetap, mengikuti Brief DC/TMD Diagnostic Decision Tree
(lihat [DIAGNOSIS.md](DIAGNOSIS.md)). Nama yang mungkin: `Myalgia`, `Arthralgia`, `Headache attributed to TMD (HA-TMD)`,
`Acute closed lock` / `Degenerative joint disease` / `Other Disc-based TMD` (salah satu dari hasil jalur sendi), dan `Subluxation`.
Bila tidak ada kriteria yang terpenuhi, `diagnoses` = `["No specific TMD diagnosis found."]`.

### GET `/api/consultations/{id}`

```json
{
  "consultation": {
    "id": 12,
    "patient_id": 1,
    "consultation_date": "2026-01-10T08:30:00.000000Z",
    "notes": null,
    "answers": [
      { "id": 1, "consultation_id": 12, "question_code": "SQ1", "type": "SQ", "answer": "Ya", "value": "Ya", "created_at": "...", "updated_at": "..." },
      { "id": 15, "consultation_id": 12, "question_code": "E1", "type": "EQ", "answer": "{\"Temporalis\":1,\"Masseter\":0,\"TMJ\":0}", "value": { "Temporalis": 1, "Masseter": 0, "TMJ": 0 }, "created_at": "...", "updated_at": "..." },
      { "id": 16, "consultation_id": 12, "question_code": "E2", "type": "EQ", "answer": "{\"opening_mm\":40,\"photo_path\":\"consultation_photos/....png\"}", "value": { "opening_mm": 40, "photo_path": "consultation_photos/....png" }, "created_at": "...", "updated_at": "..." }
    ],
    "diagnoses": [ { "id": 20, "name": "Myalgia", "is_confirmed": true, "...": "..." } ],
    "e2_photo_url": "https://<domain>/api/consultations/12/photo"
  }
}
```

- `answer` tetap string seperti sebelumnya. `value` (baru) adalah hasil decode JSON bila `answer` berupa JSON valid, selain itu string asli. Disarankan memakai `value`.
- `photo_path` adalah path internal di server dan **tidak dapat dibuka langsung**; gunakan `e2_photo_url`.
- `type` (baru) bernilai `SQ` atau `EQ` (bisa `null` hanya untuk data yang sangat lama).
- **403** `{"message": "Akses ditolak."}` bila konsultasi milik pasien lain; **404** bila tidak ada.

### GET `/api/consultations/{id}/photo` (baru)

Mengembalikan gambar (biner `image/jpeg` atau `image/png`). Lihat [Mengambil foto](#mengambil-foto).

---

## Kontrak metadata pertanyaan

`GET /api/questions` mengembalikan `{ "sq": [...], "eq": [...] }`. Struktur ini **tidak berubah** dari versi sebelumnya.

**SQ** (SQ1-SQ14), tiap item:

```json
{
  "code": "SQ1",
  "text": "Apakah Anda merasakan nyeri di rahang, pelipis, atau telinga?",
  "input": { "type": "radio", "options": ["Ya", "Tidak"], "default": "Tidak" }
}
```

`input.type` bernilai `radio` atau `select` (SQ8). Nilai yang dikirim ke server **harus persis** salah satu `options`
(contoh: `"< 1 minggu"`, `">= 1 minggu"`, `"< 1x/minggu"`, `">= 1x/minggu"`, `"Tidak ada"`, `"Sesekali"`, `"Sering"`).
SQ3 (sifat nyeri) memakai opsi berurutan `"Persisten (terus-menerus)"`, `"Kambuhan (hilang timbul)"`, `"Tidak"`
(default `"Tidak"`). Jawaban SQ3 disimpan tetapi tidak dipakai oleh diagnosis; nilai lama `"Ya"` tidak lagi diterima (422).

**EQ**:

| Kode | `input.type` | Keterangan |
| --- | --- | --- |
| `E1` | `group_select_scores` | `areas`: `["Temporalis","Masseter","TMJ"]`, skor `min` 0 .. `max` 3. `labels` berupa array berindeks skor (indeks 0 = `"0 - Tidak Nyeri"`, dst). `assets.image`: URL gambar referensi. |
| `E2` | `composite` | `fields[0]` = `opening_mm` (angka, mm, min 0; server menerima 0-100). `photo`: `accepted` `["base64","multipart"]`, `base64_field` = `e2_photo`, `multipart_field` = `e2_photo_file`, `accept_mime` `["image/jpeg","image/png"]`, `max_size_mb` 5. |
| `E3` | `radio` | `options[]` berisi `value`, `label`, dan opsional `audio` (URL contoh suara). Nilai valid: `Tidak ada`, `Kliking`, `Krepitasi`. `default`: `Tidak ada`. |
| `E4` | `group_select_scores` | Sama seperti E1 (tanpa `assets`). |

---

## Mengirim konsultasi

`POST /api/consultations` menerima **JSON** atau **multipart/form-data**.

### Field

| Field | Wajib | Keterangan |
| --- | --- | --- |
| `sq` | ya | objek `{ "SQ1": "Ya", ... }`. Tiap kode opsional (kode yang tidak dikirim dianggap negatif/"tidak ada gejala" oleh diagnosis); nilai harus salah satu `options`. |
| `eq` | ya | objek: `E1`/`E4` = `{ "Temporalis": 0-3, "Masseter": 0-3, "TMJ": 0-3 }` (integer), `E2` = `{ "opening_mm": angka 0-100 }`, `E3` = salah satu opsi E3. |
| `e2_photo` | tidak | foto base64 (JSON atau multipart). Boleh diawali `data:image/jpeg;base64,`. Maks ~7.000.000 karakter; hasil decode maks 5 MB. |
| `e2_photo_file` | tidak | foto sebagai file multipart (`jpg`/`jpeg`/`png`, maks 5 MB). Jika `e2_photo_file` dan `e2_photo` sama-sama ada, file multipart yang dipakai. |

Key lain di `sq`/`eq` (kode/area yang tidak dikenal) **diabaikan diam-diam**, tidak menyebabkan error.
Foto harus gambar JPEG/PNG yang benar-benar valid (diperiksa isinya, bukan hanya ekstensi); jika tidak, server membalas **422** pada field `e2_photo` / `e2_photo_file`.

### Contoh JSON

```http
POST /api/consultations
Authorization: Bearer <token>
Content-Type: application/json
Accept: application/json

{
  "sq": { "SQ1": "Ya", "SQ2": ">= 1 minggu", "SQ3": "Persisten (terus-menerus)", "SQ4": "Ya", "SQ5": "Tidak", "SQ6": "Tidak", "SQ7": "Tidak",
          "SQ8": "Tidak ada", "SQ9": "Tidak", "SQ10": "Tidak", "SQ11": "Tidak", "SQ12": "Tidak", "SQ13": "Tidak", "SQ14": "Tidak" },
  "eq": {
    "E1": { "Temporalis": 1, "Masseter": 0, "TMJ": 0 },
    "E2": { "opening_mm": 42 },
    "E3": "Tidak ada",
    "E4": { "Temporalis": 0, "Masseter": 0, "TMJ": 0 }
  },
  "e2_photo": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQ..."
}
```

### Contoh multipart (Dart / `dio`)

Pada multipart, kirim field bersarang dengan notasi kurung:

```dart
final form = FormData.fromMap({
  'sq[SQ1]': 'Ya',
  'sq[SQ2]': '>= 1 minggu',
  // ... SQ3..SQ14
  'eq[E1][Temporalis]': 1,
  'eq[E1][Masseter]': 0,
  'eq[E1][TMJ]': 0,
  'eq[E2][opening_mm]': 42,
  'eq[E3]': 'Tidak ada',
  'eq[E4][Temporalis]': 0,
  'eq[E4][Masseter]': 0,
  'eq[E4][TMJ]': 0,
  'e2_photo_file': await MultipartFile.fromFile(path, filename: 'mulut.jpg'),
});
await dio.post('/api/consultations', data: form,
    options: Options(headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'}));
```

### Contoh 422 (foto tidak valid)

```json
{
  "message": "Foto tidak valid. Gunakan gambar JPG atau PNG yang benar.",
  "errors": { "e2_photo": ["Foto tidak valid. Gunakan gambar JPG atau PNG yang benar."] }
}
```

---

## Mengambil foto

Foto adalah data kesehatan dan **tidak lagi publik**. URL `e2_photo_url` hanya dapat dibuka dengan header `Authorization`
dan hanya oleh pemilik konsultasi. Membuka URL di browser/`Image.network` **tanpa header akan 401**.

Di Flutter, kirim header saat memuat gambar:

```dart
Image.network(
  consultation['e2_photo_url'],
  headers: {'Authorization': 'Bearer $token'},
)
```

(atau `CachedNetworkImage(httpHeaders: {...})`, atau unduh lewat `dio` dengan `ResponseType.bytes` lalu `Image.memory`).

| Status | Arti |
| --- | --- |
| 200 | Biner gambar (`Content-Type: image/jpeg` / `image/png`), `Cache-Control: private, no-store` |
| 401 | Token tidak ada/kedaluwarsa |
| 403 | Konsultasi milik pasien lain |
| 404 | Konsultasi tidak ada, atau konsultasi tersebut tidak memiliki foto |

---

## Perubahan untuk Mobile

Perubahan perilaku dibanding API lama. Tidak ada endpoint atau key JSON yang dihapus/diganti; yang lain hanya penambahan.

### Perubahan perilaku (perlu diperhatikan)

1. **Token kedaluwarsa setelah 30 hari** (sebelumnya selamanya). Token lama juga mengikuti aturan ini (dihitung dari tanggal pembuatan token).
   Aplikasi harus menangani **401** dengan login ulang.
2. **Rate limit login/register**: login 5/menit per email+IP (dan 20/menit per IP), register 10/jam per IP. Melebihi batas -> **429** (JSON, header `Retry-After`).
3. **401 selalu JSON**: request tanpa/dengan token tidak valid ke `/api/*` mendapat `401 {"message":"Unauthenticated."}`
   (sebelumnya bisa berupa redirect ke `/login` bila header `Accept` tidak diset). Semua error `/api/*` kini JSON.
4. **Validasi lebih ketat pada `POST /api/consultations`** (sebelumnya hampir apa saja diterima -> bisa 500):
   - Nilai `sq.*` harus salah satu `options`; skor E1/E4 integer 0-3; `eq.E2.opening_mm` angka 0-100; `eq.E3` salah satu opsi E3. Nilai di luar itu -> **422**.
   - Key tak dikenal di `sq`/`eq` diabaikan (tidak disimpan, tidak error).
   - Foto base64/multipart divalidasi: harus JPEG/PNG asli, maks 5 MB; base64 maks ~7.000.000 karakter. Tidak valid -> **422** (bukan 500).
     Format foto kini ditentukan dari isinya (`.png` atau `.jpg`), bukan selalu `.jpg`.
5. **Foto tidak lagi publik**: foto E2 disimpan privat. Tidak ada lagi URL `/storage/consultation_photos/...`.
   Gunakan `e2_photo_url` + header `Authorization` ([Mengambil foto](#mengambil-foto)). `photo_path` di dalam jawaban E2 hanyalah path internal.
6. **Format penyimpanan jawaban E1/E4/E2**: nilai skor E1/E4 kini disimpan sebagai integer (`{"TMJ":1}` bukan `{"TMJ":"1"}`) dan `opening_mm`
   sebagai angka. String `answer` tetap JSON; gunakan field `value` untuk hasil decode. Jawaban E2 hanya dibuat bila `opening_mm` atau foto ada.
7. **`opening_mm` kosong/non-numerik tidak dihitung sebagai "< 35 mm"** pada diagnosis Acute closed lock (sebelumnya string kosong dianggap <= 35). Batasnya kini tegas kurang dari 35 mm.
8. **Login/register**: `user` kini memuat field `is_admin` (boolean). Abaikan di mobile.
9. **Logika diagnosis mengikuti Brief DC/TMD Diagnostic Decision Tree** ([DIAGNOSIS.md](DIAGNOSIS.md)).
   `diagnoses` kini bisa berisi **beberapa** nama sekaligus (aturan lama "maksimal satu" dihapus), dengan urutan tetap:
   Myalgia, Arthralgia, HA-TMD, hasil jalur sendi, Subluxation. Fallback `["No specific TMD diagnosis found."]` tidak berubah.
   Nama baru: `Acute closed lock`, `Degenerative joint disease`, `Other Disc-based TMD`, `Subluxation`.
   `Joint-related TMD` **tidak lagi dihasilkan**, tetapi masih bisa muncul pada riwayat konsultasi lama (tidak didiagnosis ulang);
   tampilkan sebagai nama biasa. Tiga nama pertama dan fallback tidak berubah. SQ2, SQ3, SQ6 tidak lagi memengaruhi diagnosis.
   Myalgia kini membutuhkan nyeri tekan otot pada E4 (bukan hanya E1).

### Penambahan (aditif)

- `POST /api/auth/register`: field opsional `date_of_birth`, `gender` (`Male`/`Female`), `phone_number` (maks 15). Pemanggilan lama tanpa field ini tetap berfungsi.
  Pembuatan user + profil kini atomik (transaksi).
- `POST /api/auth/logout-all`: cabut semua token.
- `DELETE /api/account`: hapus akun permanen (butuh `password`).
- `GET /api/consultations/{id}/photo`: ambil foto (butuh Authorization).
- Field `e2_photo_url` pada item `GET /api/consultations`, pada `consultation` di `GET /api/consultations/{id}`, dan pada response `POST /api/consultations`.
- Field `value` dan `type` pada setiap objek `answers`.
