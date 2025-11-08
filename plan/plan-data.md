# Plan Dashboard Admin Klinik Dentist

## 📋 Overview
Dashboard admin untuk melihat statistik keseluruhan data klinik dentist dengan tampilan sederhana berupa tabel dan angka.

## 🎯 Tujuan
Membuat dashboard yang dapat menampilkan:
1. Data Pasien
2. Data Diagnosis (paling banyak)
3. Data Konsultasi (total dan rata-rata per pasien)
4. Analisis jawaban pertanyaan

## 🏗️ Arsitektur Sistem

### Database Schema Analysis
Berdasarkan migrasi yang ada:
- **users**: Tabel pengguna sistem
- **patients**: Data pasien (terkait dengan users)
- **consultations**: Data konsultasi (terkait dengan patients)
- **questions**: Bank pertanyaan dengan kode unik
- **answers**: Jawaban pasien per konsultasi
- **examination_results**: Hasil pemeriksaan per konsultasi
- **diagnoses**: Diagnosis per konsultasi (5 jenis utama)

### Jenis Diagnosis yang Tersedia
1. Myalgia - Nyeri otot pengunyahan
2. Arthralgia - Nyeri sendi temporomandibular
3. Headache attributed to TMD (HA-TMD) - Sakit kepara karena TMD
4. Joint-related TMD - Gangguan struktur internal sendi
5. No specific TMD diagnosis found - Tidak ada diagnosis spesifik

## 📊 Fitur Dashboard

### 1. Statistik Data Pasien
- **Total Pasien**: Jumlah keseluruhan pasien terdaftar
- **Pasien Baru Bulan Ini**: Jumlah pasien yang daftar dalam bulan berjalan
- **Tabel Pasien Terbaru**: 10 pasien terakhir yang mendaftar

### 2. Statistik Data Diagnosis
- **5 Diagnosis Terbanyak**: Daftar diagnosis dengan frekuensi tertinggi
- **Persentase Distribusi**: Persentase setiap jenis diagnosis
- **Visualisasi**: Progress bar sederhana untuk setiap diagnosis

### 3. Statistik Data Konsultasi
- **Total Konsultasi**: Jumlah keseluruhan sesi konsultasi
- **Rata-rata per Pasien**: Rata-rata jumlah konsultasi yang dilakukan setiap pasien
- **Pasien Teraktif**: 5 pasien dengan konsultasi terbanyak

### 4. Analisis Jawaban Pertanyaan
- **Pertanyaan Terpopuler**: 10 pertanyaan paling sering dijawab
- **Distribusi Jawaban**: Analisis jawaban untuk pertanyaan populer
- **Statistik per Tipe**: Jumlah jawaban untuk tipe Subjective vs Objective

## 🛠️ Implementasi Teknis

### Struktur File yang Akan Dibuat

#### Controllers
```
app/Http/Controllers/Admin/
└── DashboardController.php
```

#### Middleware
```
app/Http/Middleware/
└── AdminMiddleware.php
```

#### Views
```
resources/views/admin/
├── layouts/
│   └── app.blade.php
└── dashboard.blade.php
```

#### Routes
Penambahan route di `routes/web.php`

### Query Database yang Akan Digunakan

#### Statistik Pasien
```sql
-- Total pasien
SELECT COUNT(*) FROM patients;

-- Pasien baru bulan ini
SELECT COUNT(*) FROM patients 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH);

-- 10 pasien terbaru
SELECT * FROM patients 
ORDER BY created_at DESC 
LIMIT 10;
```

#### Statistik Diagnosis
```sql
-- 5 diagnosis terbanyak
SELECT name, COUNT(*) as count 
FROM diagnoses 
GROUP BY name 
ORDER BY count DESC 
LIMIT 5;

-- Total diagnosis untuk persentase
SELECT COUNT(*) as total FROM diagnoses;
```

#### Statistik Konsultasi
```sql
-- Total konsultasi
SELECT COUNT(*) FROM consultations;

-- Rata-rata konsultasi per pasien
SELECT AVG(consultation_count) 
FROM (
    SELECT COUNT(*) as consultation_count 
    FROM consultations 
    GROUP BY patient_id
) as patient_consultations;

-- 5 pasien dengan konsultasi terbanyak
SELECT p.name, COUNT(c.id) as consultation_count
FROM patients p
JOIN consultations c ON p.id = c.patient_id
GROUP BY p.id, p.name
ORDER BY consultation_count DESC
LIMIT 5;
```

#### Analisis Jawaban
```sql
-- 10 pertanyaan paling sering dijawab
SELECT q.question_text, COUNT(a.id) as answer_count
FROM questions q
JOIN answers a ON q.code = a.question_code
GROUP BY q.code, q.question_text
ORDER BY answer_count DESC
LIMIT 10;

-- Distribusi jawaban per tipe pertanyaan
SELECT q.type, COUNT(a.id) as answer_count
FROM questions q
JOIN answers a ON q.code = a.question_code
GROUP BY q.type;
```

### Desain Dashboard

#### Layout Structure
```
┌─────────────────────────────────────────┐
│ Header (Admin Dashboard)               │
├─────────────────────────────────────────┤
│ Statistics Cards Row                   │
│ ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐       │
│ │Pat  │ │Diag │ │Cons │ │Answ │       │
│ └─────┘ └─────┘ └─────┘ └─────┘       │
├─────────────────────────────────────────┤
│ Detailed Statistics Tables              │
│ ┌─────────────────┐ ┌─────────────────┐ │
│ │ Patient Data    │ │ Diagnosis Data  │ │
│ └─────────────────┘ └─────────────────┘ │
│ ┌─────────────────┐ ┌─────────────────┐ │
│ │ Consultation    │ │ Answer Analysis │ │
│ └─────────────────┘ └─────────────────┘ │
└─────────────────────────────────────────┘
```

#### Komponen UI
- **Cards**: Bootstrap cards untuk statistik utama
- **Tables**: Bootstrap tables untuk data detail
- **Progress Bars**: Untuk visualisasi persentase diagnosis
- **Color Coding**: Warna berbeda untuk setiap jenis diagnosis
- **Icons**: Bootstrap Icons untuk visualisasi data

### Keamanan

#### Middleware Implementation
```php
// AdminMiddleware.php
public function handle($request, Closure $next)
{
    if (auth()->user() && auth()->user()->is_admin) {
        return $next($request);
    }
    
    return redirect('/dashboard')->with('error', 'Access denied');
}
```

#### Route Protection
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});
```

## 📝 Implementation Steps

### Phase 1: Basic Structure
1. Membuat AdminMiddleware
2. Membuat DashboardController
3. Membuat route admin dashboard
4. Membuat layout admin dashboard

### Phase 2: Data Collection
1. Implementasi statistik Data Pasien
2. Implementasi statistik Data Diagnosis
3. Implementasi statistik Data Konsultasi
4. Implementasi analisis jawaban pertanyaan

### Phase 3: UI Implementation
1. Membuat view dashboard admin
2. Implementasi statistik cards
3. Implementasi data tables
4. Implementasi progress bars

### Phase 4: Integration & Testing
1. Menambahkan navigasi ke admin dashboard
2. Testing akses admin
3. Validasi data yang ditampilkan
4. Testing responsif layout

## 🔧 Technical Requirements

### Dependencies
- Laravel (already installed)
- Bootstrap (already available)
- Laravel Eloquent ORM (already available)

### Performance Considerations
- Query optimization untuk data besar
- Caching untuk statistik yang sering diakses
- Pagination untuk data tables jika needed

### Future Enhancements
- Export data to CSV/PDF
- Date range filtering
- Real-time updates dengan WebSocket
- Advanced charts dengan Chart.js/ApexCharts

## 📋 Checklist

- [ ] Membuat DashboardController untuk mengelola logika dashboard
- [ ] Membuat route khusus untuk dashboard admin
- [ ] Membuat view dashboard admin dengan layout yang berbeda
- [ ] Implementasi statistik Data Pasien (total pasien, pasien baru bulan ini)
- [ ] Implementasi statistik Data Diagnosis (diagnosis terbanyak dengan persentase)
- [ ] Implementasi statistik Data Konsultasi (total konsultasi, rata-rata per pasien)
- [ ] Implementasi analisis jawaban pertanyaan (pertanyaan paling sering dijawab)
- [ ] Membuat middleware untuk membatasi akses hanya admin
- [ ] Menambahkan link/navigasi ke dashboard admin dari menu utama
- [ ] Testing dan validasi data yang ditampilkan

---

*Last Updated: 2025-11-08*
*Author: Kilo Code*