# Tracer Study LPKM UMMY Solok

Aplikasi **Tracer Study** berbasis web untuk melacak kondisi kerja/lanjutan studi alumni
Lembaga Penelitian dan Pengabdian Masyarakat (LPKM) Universitas Muhammadiyah Muara Bungo (UMMY) Solok.
Dibangun dengan **Laravel 12** (PHP 8.2), **Tailwind CSS v4**, **Chart.js**, dan **Maatwebsite/Excel**.

## Fitur

- **Form kuesioner alumni** (`/kuesioner`)
  - Autentikasi data otomatis terhadap tabel master alumni (NIM + NIK + tahun lulus + kode prodi).
  - Dropdown provinsi → kabupaten/kota dinamis (data bersumber dari `config/wilayah.php`).
  - Validasi lengkap di sisi server (tidak bergantung JavaScript), termasuk NIK 16 digit & email sesuai domain kampus.
  - Kuesioner dapat ditutup/dibuka dan pesan disesuaikan dari menu Pengaturan.
- **Dashboard analitik admin** (`/dashboard-kurva`)
  - Grafik status bekerja, pendapatan, jenis instansi, sumber dana, posisi jabatan, lokasi kerja,
    kompetensi, metode belajar, waktu cari kerja, cara cari kerja, rata-rata lamaran, keaktifan, dan alasan tidak sesuai.
  - Filter tahun lulus & kode prodi; kartu & grafik bisa diklik untuk melihat daftar nama alumni.
- **Impor & ekspor Excel**
  - Impor data master alumni (`.xlsx`/`.xls`/`.csv`) dengan deteksi duplikat NIM/NIK dan batch insert.
  - Ekspor laporan kuesioner (`.xlsx`) dengan filter tahun/prodi, diurutkan Tahun → Prodi → Nama.
- **Manajemen akun admin** (`/admin/akun`)
  - Hanya super admin yang dapat membuka. Kelola akun admin biasa, jadikan super, reset/hapus password.
  - Fitur kode pemulihan super admin & riwayat login/password (`/admin/riwayat`).
- **Pengaturan aplikasi** (`/admin/pengaturan`)
  - Nama item grafik, pesan sukses/tutup, domain email, kode PT default, dll. (disimpan di tabel `settings`).

## Kebutuhan

- PHP >= 8.2 (disarankan pakai XAMPP PHP 8.2)
- Composer
- MySQL/MariaDB
- Node.js & npm (opsional, hanya untuk build asset Vite)

## Instalasi

```bash
# 1. Pindahkan/clone proyek ke htdocs (mis. C:\xampp\htdocs\tracer-lpkm)
cd tracer-lpkm

# 2. Install dependency PHP
composer install

# 3. Siapkan konfigurasi
copy .env.example .env        # Windows
cp .env.example .env          # Linux
php artisan key:generate

# 4. Buat database MySQL lalu sesuaikan .env
#    DB_DATABASE, DB_USERNAME, DB_PASSWORD (default: db_tracer_lpkm / root / kosong)

# 5. Jalankan migrasi
php artisan migrate

# 6. Buat akun super admin awal (opsional, lihat bagian "Akun Awal")
php artisan db:seed

# 7. (Opsional) build asset Vite
npm install
npm run build
```

Akses aplikasi: `http://localhost/tracer-lpkm/public` (atau konfigurasi virtual host),
halaman kuesioner: `http://localhost/tracer-lpkm/public/kuesioner`,
halaman login admin: `http://localhost/tracer-lpkm/public/login`.

> Catatan: seluruh tampilan memakai Tailwind CSS v4 lewat CDN, sehingga build Vite tidak
> diperlukan untuk menjalankan aplikasi.

## Akun Awal

`AdminUserSeeder` membuat super admin dari variabel env:

```env
ADMIN_EMAIL=admin@umm.ac.id
ADMIN_PASSWORD=ganti-password-minimal-8-karakter
```

```bash
php artisan db:seed
```

> UBAH `ADMIN_PASSWORD` sebelum dipakai di lingkungan nyata. Kode pemulihan super admin
> bisa diatur dari menu `Pengaturan` → halaman `Kelola Akun Admin`.

## Route Utama

| Method | URL | Nama | Fungsi |
|---|---|---|---|
| GET | `/kuesioner` | `kuesioner.index` | Form kuesioner publik |
| POST | `/kuesioner` | `kuesioner.store` | Simpan jawaban kuesioner |
| GET | `/login` | `login` | Login admin |
| POST | `/logout` | `logout` | Logout admin |
| GET | `/dashboard-kurva` | `kuesioner.dashboard` | Dashboard analitik |
| GET | `/export-kuesioner-excel` | `kuesioner.export` | Ekspor Excel |
| POST | `/admin/alumni/import` | `alumni.import` | Impor master alumni |
| GET | `/admin/pengaturan` | `pengaturan.index` | Pengaturan aplikasi |
| GET | `/admin/akun` | `akun.index` | Kelola akun admin |
| GET | `/admin/riwayat` | `akun.riwayat` | Riwayat login & password |
| GET | `/pemulihan` | `pemulihan.index` | Pemulihan password super admin |

## Struktur Penting

```
app/Http/Controllers/
├── KuesionerController.php   # index + store (form kuesioner publik)
├── DashboardController.php   # dashboard + export + import (admin)
├── AuthController.php        # login/logout
├── AccountController.php     # kelola akun & riwayat
├── SettingsController.php    # pengaturan
└── RecoveryController.php    # pemulihan password

config/wilayah.php            # data provinsi/kab-kota (kode & daftar untuk dropdown)
resources/views/
├── layouts/admin.blade.php   # layout halaman admin
├── layouts/auth.blade.php    # layout halaman login/pemulihan
├── kuesioner.blade.php       # form kuesioner
└── dashboard_kurva.blade.php # dashboard analitik
```

## Pengujian

```bash
php artisan test
```

Terdapat 12 test (45 assertions) yang mencakup penyimpanan kuesioner, validasi,
autentikasi alumni, impor Excel, dan pengaturan visibilitas grafik dashboard.

## Lisensi

Proyek internal LPKM UMMY Solok.
