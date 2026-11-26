# Logika Diagnosis TMD

Sumber aturan: **Brief DC/TMD (bDC/TMD) Diagnostic Decision Tree, Version 1, 6 June 2023**.
Implementasi: `app/Services/TmdDiagnosisService.php` (fungsi murni, `diagnose(array $sq, array $eq): array`).

Bagan terdiri dari dua jalur independen. **Keduanya selalu dievaluasi** dan hasilnya digabung, sehingga satu konsultasi
dapat memiliki beberapa diagnosis. Hasil unik dengan urutan tetap; bila kosong, hasilnya `["No specific TMD diagnosis found."]`.

## Pemetaan node bagan ke input aplikasi

| Node bagan | Kode | Input aplikasi |
| --- | --- | --- |
| Regional pain | SQ1 | `sq.SQ1 == "Ya"` |
| Headache | SQ5 | `sq.SQ5 == "Ya"` |
| Nyeri/sakit kepala dimodifikasi gerakan rahang | SQ4 atau SQ7 | `sq.SQ4 == "Ya"` atau `sq.SQ7 == "Ya"` |
| Examiner confirmation of pain location | E1 | salah satu `eq.E1.{Temporalis,Masseter,TMJ} >= 1` (area mana pun) |
| Familiar pain pada palpasi otot pengunyahan | E4 | `eq.E4.Temporalis >= 1` atau `eq.E4.Masseter >= 1` |
| Familiar pain pada palpasi TMJ | E4 | `eq.E4.TMJ >= 1` |
| Familiar pain saat membuka mulut | E2 | tidak dikumpulkan (selalu dianggap tidak ada) |
| Headache not better accounted for by another headache diagnosis | Symptom review | tidak dikumpulkan (dianggap terpenuhi) |
| Prior jaw locking in closed position | SQ9 | `sq.SQ9 == "Ya"` |
| Interference in mastication | SQ10 | `sq.SQ10 == "Ya"` |
| MUO < 35 mm | E2 | `eq.E2.opening_mm` numerik dan `< 35` (tepat 35 tidak termasuk) |
| Current limited opening | SQ12 | `sq.SQ12 == "Ya"` |
| Current TMJ noises by history | SQ8 | `sq.SQ8` = `Sesekali` atau `Sering` (tanpa bunyi E3 tidak mengubah hasil) |
| Noise detected during examination | E3 | `eq.E3` = `Kliking` atau `Krepitasi` |
| Current intermittent locking with limited opening | SQ11 | `sq.SQ11 == "Ya"` (tanpa bunyi E3 tidak mengubah hasil) |
| Crepitus detected by examiner | E3 | `eq.E3 == "Krepitasi"` |
| Opening & closing click by examiner | E3 | `eq.E3 == "Kliking"` |
| Lock in closed position by examiner | E3 | tidak dikumpulkan (selalu dianggap tidak ada) |
| History of open lock | SQ13 | `sq.SQ13 == "Ya"` |
| History of maneuver to close mouth | SQ14 | `sq.SQ14 == "Ya"` |
| Lock in open position if clinically present | E3 | tidak disyaratkan |

Nilai yang hilang atau tidak dikenal selalu dianggap negatif (SQ bukan "Ya", skor 0, E3 tanpa bunyi, `opening_mm` tidak diketahui).

## Jalur 1: Painful TMDs (halaman 1)

```
history   = (SQ1 ATAU SQ5) DAN (SQ4 ATAU SQ7)
lokasi    = E1 area mana pun >= 1
otot      = E4.Temporalis >= 1 ATAU E4.Masseter >= 1
sendi     = E4.TMJ >= 1
familiar  = otot ATAU sendi            (nyeri saat membuka mulut [E2] tidak dikumpulkan)

bila TIDAK (history DAN lokasi DAN familiar) -> tidak ada diagnosis dari jalur ini
Myalgia     bila otot
Arthralgia  bila sendi
HA-TMD      bila SQ5 = Ya DAN E4.Temporalis >= 1
```

## Jalur 2: Common joint-related TMDs (halaman 2)

```
START B  riwayatKunciTertutup = SQ9 DAN SQ10
  bila riwayatKunciTertutup:
      Acute closed lock   bila opening_mm numerik < 35 DAN SQ12 = Ya
      Other Disc-based TMD selain itu
  bila tidak (panah "No" menuju kotak bunyi sendi):
      bunyi = SQ8 (Sesekali/Sering) ATAU E3 (Kliking/Krepitasi) ATAU SQ11
      bila bunyi:                      (hasil akhir tetap ditentukan E3; tanpa bunyi E3 tidak ada diagnosis)
          E3 = Krepitasi -> Degenerative joint disease
          E3 = Kliking   -> Other Disc-based TMD
START C  Subluxation bila SQ13 DAN SQ14        (independen dari START B)
```

## Daftar nama diagnosis

Urutan hasil: Myalgia, Arthralgia, HA-TMD, hasil START B, Subluxation.

- `Myalgia`
- `Arthralgia`
- `Headache attributed to TMD (HA-TMD)`
- `Acute closed lock`
- `Degenerative joint disease`
- `Other Disc-based TMD`
- `Subluxation`
- `No specific TMD diagnosis found.` (bila tidak ada yang terpenuhi)

`Joint-related TMD` adalah nama lama; tidak lagi dihasilkan, tetapi masih mungkin muncul pada konsultasi lama.

## Kode yang tidak dipakai bagan

SQ2, SQ3, dan SQ6 **tidak ada di bagan** sehingga tidak memengaruhi diagnosis. Jawabannya tetap dikumpulkan dan disimpan.

SQ8 dan SQ11 **ada di bagan**, tetapi di aplikasi ini tidak mengubah hasil (lihat keterbatasan tentang E3 di bawah); keduanya tetap dikumpulkan.

## Keterbatasan / asumsi

Poin-poin berikut adalah keputusan interpretasi saat menyesuaikan bagan untuk pemeriksaan mandiri, dan perlu dikonfirmasi dengan dokter gigi/pakar.

- **E3 diisi pasien**, sehingga "noise detected by patient during examination" dan "crepitus/click detected by examiner" adalah input yang sama. Kotak bunyi sendi dimasuki bila E3 berisi bunyi; tanpa bunyi E3 hasilnya tidak ada diagnosis. Akibatnya SQ8 dan SQ11 tidak pernah mengubah hasil di aplikasi ini (tetap dikumpulkan).
- **E2 familiar pain saat membuka mulut** tidak dikumpulkan; nyeri familiar hanya dinilai dari palpasi E4.
- **E3 lock (tertutup/terbuka) oleh pemeriksa** tidak dikumpulkan; dianggap tidak ada. Subluxation hanya memakai riwayat SQ13 + SQ14.
- **Headache symptom review** (sakit kepala tidak lebih tepat dijelaskan diagnosis sakit kepala lain) tidak bisa dinilai pemeriksaan mandiri dan dianggap terpenuhi. Dokter gigi tetap perlu mengonfirmasi HA-TMD.
- **E1** di aplikasi ini adalah nyeri tekan ringan dan dipakai sebagai "konfirmasi lokasi nyeri" (area mana pun, tidak harus sama dengan area E4).
- **Gerbang dibaca literal** sesuai bagan: (SQ1 atau SQ5) dan (SQ4 atau SQ7), bukan pasangan SQ1+SQ4 / SQ5+SQ7.
- **Kotak bunyi sendi hanya dicapai bila START B = "No"**, sesuai panah pada bagan.
- **Konsultasi lama tidak didiagnosis ulang**; diagnosis yang tersimpan tetap apa adanya.
- Hasil aplikasi bukan pengganti pemeriksaan dokter gigi; pencitraan tidak dipertimbangkan pada tahap ini.
