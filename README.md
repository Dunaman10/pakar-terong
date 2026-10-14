# Sistem Pakar Diagnosis Penyakit & Hama Tanaman Terong (Certainty Factor)

Aplikasi berbasis web untuk mendiagnosis penyakit dan hama pada tanaman terong menggunakan metode Certainty Factor (CF).

## 🚀 Fitur Utama
- **Konsultasi & Diagnosis**: Pengguna dapat memilih gejala yang dialami tanaman terong dan mendapatkan hasil diagnosis tingkat keyakinan (persentase).
- **Riwayat Konsultasi**: Riwayat hasil konsultasi pengguna.
- **Daftar Penyakit & Hama**: Informasi detail tentang hama, penyakit, gejala, dan solusi penanganannya.
- **Panel Admin**:
  - Manajemen Data Penyakit & Hama
  - Manajemen Data Gejala
  - Manajemen Aturan & Nilai Bobot Pakar (MB/MD)
  - Laporan & Riwayat Diagnosa

## 🛠️ Prasyarat & Teknologi
- **PHP** >= 7.4 / 8.x
- **MySQL / MariaDB**
- **Web Server**: Apache / Nginx (Herd, XAMPP, Laragon, atau hosting cPanel)

## 📦 Panduan Instalasi Lokal

1. **Clone repository ini:**
   ```bash
   git clone <URL_REPOSITORY_GITHUB_ANDA>
   ```

2. **Database:**
   - Buat database baru di MySQL/phpMyAdmin dengan nama `db_sp_terong`.
   - Import file skema/database SQL ke dalam database tersebut.

3. **Konfigurasi Database:**
   - Buat file `admin/config.local.php` (sudah disediakan contohnya di `admin/config.example.php`):
     ```php
     <?php
     return [
       'host' => 'localhost',
       'user' => 'root',
       'pass' => '', // isi password database lokal Anda jika ada
       'name' => 'db_sp_terong',
     ];
     ```

4. **Jalankan Aplikasi:**
   - Akses melalui URL lokal Anda (misal `http://ds.test` atau `http://localhost/DS`).
   - Halaman Admin dapat diakses di `/admin/login.php`.
