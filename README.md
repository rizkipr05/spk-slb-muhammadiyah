# SPK Kebutuhan Layanan Pendidikan SLB ABCD MUHAMMADIYAH PALU

Sistem Pendukung Keputusan (SPK) ini dikembangkan menggunakan kerangka kerja (framework) **Laravel** untuk membantu dalam pengelolaan layanan pendidikan inklusi dan analisis data siswa berkebutuhan khusus.

## 📋 Persyaratan Sistem (*Prerequisites*)

Sebelum menginstal dan menjalankan aplikasi ini, pastikan sistem Anda memiliki lingkungan berikut:
- **PHP** >= 8.1
- **Composer** (untuk manajemen dependensi PHP)
- **Node.js** & **NPM** (untuk *asset bundling* dengan Vite)
- **MySQL / MariaDB** (bisa menggunakan XAMPP, Laragon, dsb.)

---

## 🛠️ Panduan Instalasi (*Installation Guide*)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi secara lokal:

### 1. Ekstrak / Clone Direktori
Pastikan Anda mengekstrak atau *clone* direktori (folder) proyek ini ke dalam direktori lokal server Anda (misalnya `htdocs` untuk XAMPP atau `www` untuk Laragon).

### 2. Instalasi Dependensi PHP
Buka terminal / *command prompt*, arahkan ke dalam *folder* proyek (misal: `cd C:\xampp\htdocs\spk-disabilitas`), lalu jalankan:
```bash
composer install
```

### 3. Instalasi dan Build Dependensi Frontend (Node.js)
Jalankan perintah berikut untuk mengunduh modul NPM dan memproses *assets* (CSS/JS):
```bash
npm install
npm run build
```
*(Catatan: Jika Anda sedang dalam tahap *development* aktif, Anda bisa menggunakan `npm run dev`)*.

### 4. Konfigurasi Environment (Database)
Gandakan (copy) file `.env.example` menjadi `.env`.
```bash
cp .env.example .env
```
Lalu buka file `.env` di *text editor* Anda, dan sesuaikan pengaturan *database* Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spk_disabilitas  # (Sesuaikan dengan nama database yang Anda buat di phpMyAdmin)
DB_USERNAME=root             # (Standarnya root)
DB_PASSWORD=                 # (Kosongkan jika Anda memakai setup standar XAMPP)
```
**Penting:** Pastikan Anda telah membuat *database* kosong dengan nama `spk_disabilitas` melalui *phpMyAdmin* atau *Database Client* lainnya.

### 5. Buat Application Key
Jalankan perintah ini untuk menciptakan *key* keamanan aplikasi:
```bash
php artisan key:generate
```

### 6. Migrasi dan Seeding Database
Lakukan migrasi tabel beserta *dummy data* awal (*Kriteria*, *User*, dll) dengan perintah:
```bash
php artisan migrate --seed
```

### 7. Hubungkan Storage Lokal (Opsional namun disarankan)
Agar fitur *upload* gambar profil dapat berjalan dan terakses sempurna, jalankan:
```bash
php artisan storage:link
```

### 8. Jalankan Server Development Laravel
Terakhir, aplikasi siap dijalankan:
```bash
php artisan serve
```
Buka browser dan akses aplikasi Anda di: **[http://localhost:8000](http://localhost:8000)** atau sesuai konfigurasi virtual host Anda.

---

## 🔐 Kredensial Login Default

Sistem ini didukung dengan *Role-Based Access Control* (RBAC) (Hak Akses Berbasis Peran). Setelah Anda menjalankan *seeder* (`--seed`), Anda bisa login menggunakan akun berikut:

**1. Administrator**
- **Username:** `admin`
- **Password:** `password`

**2. Guru / Penilai**
- **Username:** `guru`
- **Password:** `password`

**3. Kepala Sekolah (Monitoring)**
- **Username:** `kepsek`
- **Password:** `password`

---

## ✨ Fitur Utama
- **CRUD Terintegrasi:** Manajemen data Kriteria, Subkriteria, Alternatif, dan Siswa secara dinamis.
- **Rekomendasi AHP:** Implementasi *Analytical Hierarchy Process* secara terprogram untuk penentuan prioritas siswa berdasarkan bobot penilaian.
- **Manajemen Biodata:** Pengguna *(termasuk pengguna dengan Role)* bisa memperbarui profil serta unggah foto profil mereka.
- **Dashboard Analitik:** Visualisasi data berbasis *Chart.js* untuk kemudahan *monitoring*.
