# Mapping Soal – Ujian & Kuis

Dokumentasi cara penggunaan fitur **Mapping Soal** untuk mengaitkan soal dari Bank Soal ke **Ujian** (Try Out) atau **Kuis**.

---

## Struktur Folder & File

```
app/
├── Http/Controllers/Admin/
│   └── MappingQuestionController.php   # Semua aksi mapping (ujian + kuis)
├── Models/
│   ├── MappingQuestion.php             # id_exam (nullable), id_quiz (nullable)
│   ├── Exam.php                         # hasMany mappingQuestions (id_exam)
│   └── Quiz.php                         # hasMany mappingQuestions (id_quiz)

resources/views/admin/mapping-question/
├── index-exam.blade.php                 # Daftar: tab Ujian + tab Kuis
├── create.blade.php                     # Pilih tipe (Ujian/Kuis) → pilih item → mapping
├── index.blade.php                      # Kelola mapping per ujian/kuis (satu view)
└── show.blade.php                       # Detail satu soal ter-mapping (satu view)

routes/web.php                           # Route exam + quiz (lihat di bawah)
```

---

## Route

| Method | URL | Name | Keterangan |
|--------|-----|------|------------|
| GET | `/mapping-questions` | `mapping-questions.index` | Daftar ujian & kuis yang punya mapping |
| GET | `/mapping-questions/create` | `mapping-questions.create` | Form pilih Ujian/Kuis lalu mapping |
| GET | `/exams/{exam}/mapping-questions` | `mapping-questions.manage` | Kelola mapping untuk satu ujian |
| POST | `/exams/{exam}/mapping-questions` | `mapping-questions.store` | Tambah soal ke ujian |
| POST | `/exams/{exam}/mapping-questions/random` | `mapping-questions.random` | Tambah soal acak ke ujian |
| GET | `/exams/{exam}/mapping-questions/{mapping}` | `mapping-questions.show` | Detail soal di ujian |
| DELETE | `/exams/{exam}/mapping-questions/{mapping}` | `mapping-questions.destroy` | Hapus soal dari ujian |
| GET | `/quizzes/{quiz}/mapping-questions` | `mapping-questions.quiz.manage` | Kelola mapping untuk satu kuis |
| POST | `/quizzes/{quiz}/mapping-questions` | `mapping-questions.quiz.store` | Tambah soal ke kuis |
| POST | `/quizzes/{quiz}/mapping-questions/random` | `mapping-questions.quiz.random` | Tambah soal acak ke kuis |
| GET | `/quizzes/{quiz}/mapping-questions/{mapping}` | `mapping-questions.quiz.show` | Detail soal di kuis |
| DELETE | `/quizzes/{quiz}/mapping-questions/{mapping}` | `mapping-questions.quiz.destroy` | Hapus soal dari kuis |

---

## Cara Penggunaan

### 1. Lihat Daftar Mapping

- Menu: **Pelatihan → Mapping Soal**.
- Halaman menampilkan **tab Ujian** dan **tab Kuis**.
- Masing-masing tab berisi tabel ujian/kuis yang **sudah punya mapping soal**.
- Kolom: nama ujian/kuis, paket, total soal terpilih, tombol **Lihat Mapping**.

### 2. Tambah Mapping Baru

- Klik **Tambah Mapping Soal**.
- Pilih **Tipe**: **Ujian** atau **Kuis**.
- Pilih **Ujian** atau **Kuis** dari dropdown (bisa filter paket dari nama).
- Klik **Pilih**.
- Di halaman berikutnya:
  - **Pilih Soal dari Bank**: filter mapel (opsional), centang soal yang mau ditambah, atau pakai **Tambah Acak** / **Pilih Otomatis**.
  - **Tambah ke Ujian** / **Tambah ke Kuis**: kirim form.
- Di kanan: daftar **Soal di Ujian/Kuis Ini** (bisa lihat detail, hapus).

### 3. Kelola Mapping dari Daftar

- Di halaman **Mapping Soal** (tab Ujian atau Kuis), klik **Lihat Mapping** pada satu baris.
- Masuk ke halaman kelola mapping untuk ujian/kuis tersebut: sama seperti setelah “Tambah Mapping”, tapi langsung ke ujian/kuis yang dipilih.

### 4. Detail & Hapus Soal

- Di tabel “Soal di Ujian/Kuis Ini”, tombol **mata** = detail soal, tombol **hapus** = hapus dari mapping.
- Halaman detail menampilkan soal lengkap (teks/gambar, opsi, jawaban, pembahasan). Tombol **Hapus dari Ujian/Kuis** menghapus mapping saja (soal tetap di Bank Soal).

---

## Ringkasan

- **Satu fitur** mapping dipakai untuk **Ujian** dan **Kuis**.
- **Satu view** `index.blade.php` dan `show.blade.php` dipakai untuk keduanya dengan variabel `$mappable` dan `$mappableType` (`exam` | `quiz`).
- **Route** terpisah: `exams/{exam}/mapping-questions/...` dan `quizzes/{quiz}/mapping-questions/...`.
- Pastikan migrasi `add_id_quiz_to_mapping_questions` sudah dijalankan (`php artisan migrate`).
