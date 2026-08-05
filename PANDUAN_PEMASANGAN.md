# PANDUAN PEMASANGAN — Aplikasi Tracer Study LPKM

Panduan ini untuk memasang aplikasi Tracer Study ke hosting di mana situs **WordPress tracerstudy lama sudah berjalan**. Tujuannya agar kedua sistem berjalan berdampingan **tanpa mengganggu** situs WordPress, dan data kuesioner **tidak tercampur** dengan database WordPress.

> Siapa yang memasang? Aplikasi ini **berdiri sendiri** (bukan plugin WordPress). Pemasangnya adalah **dosen / penanggung jawab website** yang memiliki akses ke panel hosting (cPanel/plesk/dll.) karena memerlukan: pembuatan database, pembuatan subdomain, dan akses terminal/SSH (jika tersedia). Untuk hal yang tidak bisa dikerjakan sendiri, lampirkan bagian **Checklist Pertanyaan untuk Penanggung Jawab Web** di bagian akhir.

---

## 1. Prinsip Dasar

1. **Aplikasi dipasang di folder sendiri** — JANGAN dicampur ke dalam folder WordPress (`public_html` langsung) dan JANGAN diimpor ke database WordPress. Gunakan folder/database baru yang khusus.
2. **Disarankan: subdomain baru** (contoh `kuesioner.tracerstudy.univ.ac.id`). Cara ini paling aman karena aturan `.htaccess` WordPress di folder induk tidak mengganggu aplikasi.
3. **Alternatif: subfolder** (contoh `https://tracerstudy.univ.ac.id/kuesioner/`). Bisa tetapi lebih rumit — lihat Lampiran B.
4. Aplikasi ini **tidak memerlukan build Node.js** (CSS memakai Tailwind CDN). Cukup `composer install`.
5. Isi form kuesioner sudah **disesuaikan dengan standar data LLDIKTI** — jangan mengubah kode yang terkait validasi/pertanyaan kuesioner.

---

## 2. Kebutuhan Minimum Hosting

| Kebutuhan | Nilai |
|---|---|
| PHP | **≥ 8.2** (cek di panel hosting, mis. bagian "MultiPHP Manager" / "Select PHP Version") |
| Database | MySQL / MariaDB (versi apapun yang umum di hosting) |
| Ekstensi PHP | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd` (umumnya sudah aktif) |
| Akses | Panel hosting (cPanel/plesk). **SSH/terminal sangat disarankan** agar mudah; tanpa SSH tetap bisa (lihat Langkah 7) |
| Folder | Folder tempat aplikasi (`public/` jadi docroot) harus bisa ditulisi (`writeable`) |

**Cara cek PHP di cPanel:** cPanel → **Select PHP Version** atau **MultiPHP Manager** → pastikan versi ≥ 8.2.

---

## 3. Skenario yang Disarankan: Subdomain Baru (docroot ke `public/`)

Aplikasi Laravel harus diakses lewat folder `public/`-nya (bukan folder root aplikasi). Subdomain memudahkan ini.

1. **Upload folder proyek** ke hosting, misalnya ke:
   ```
   /home/USER/aplikasi/tracer-lpkm/
   ```
   (di luar `public_html` jika memungkinkan — aman dari akses langsung). Jika hanya ada `public_html`, upload ke `public_html/tracer-lpkm/` dan lindungi lewat langkah `.htaccess` di Lampiran A.

2. **Buat subdomain** di panel hosting, misal `kuesioner.tracerstudy.univ.ac.id`:
   - **Document Root** diarahkan ke folder: `tracer-lpkm/public` (atau `public_html/tracer-lpkm/public`)
   - Aktifkan **HTTPS** (SSL) untuk subdomain itu.

3. **Selesai** — kini `kuesioner.tracerstudy.univ.ac.id` langsung menuju `public/` aplikasi, terpisah dari WordPress.

---

## 4. Instalasi Dependensi

### Jika ada SSH / terminal
```bash
cd /home/USER/aplikasi/tracer-lpkm
composer install --no-dev --optimize-autoloader
```

### Jika tidak ada SSH
Gunakan fitur **Terminal** bawaan cPanel (cPanel → **Terminal**), atau minta penanggung jawab web menjalankan perintah di atas. Sebagai pengganti, salin folder `vendor/` dari komputer Anda ke hosting (via File Manager/ZIP), kemudian hapus folder `vendor/` milik Windows jika ada perbedaan.

> Karena tidak ada build Node, **tidak perlu** `npm install` atau `npm run build`.

---

## 5. Konfigurasi `.env`

1. Salin `.env.example` menjadi `.env` (di folder root aplikasi).
2. Isi nilai-nilai penting:

```env
APP_NAME="Tracer Study LPKM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kuesioner.tracerstudy.univ.ac.id   # URL akhir aplikasi (dengan https)
APP_KEY=                                           # diisi via php artisan key:generate

# Database (buat database baru khusus aplikasi ini)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_baru
DB_USERNAME=user_database
DB_PASSWORD=password_database

SESSION_DRIVER=file
CACHE_STORE=database
QUEUE_CONNECTION=database

# Akun Super Admin pertama (lihat Langkah 6)
ADMIN_EMAIL=email_admin@kampus.ac.id
ADMIN_PASSWORD=ganti-dengan-password-kuat-min-8-karakter
```

> **Catatan SSL/HTTPS:** `APP_URL` harus memakai `https://`. Jika di belakang Cloudflare, pastikan SSL mode "Full" (bukan "Flexible") supaya tidak terjadi pengulangan redirect.
> **Catatan folder:** `SESSION_DRIVER=file` dan `CACHE_STORE=database` adalah pengaturan yang paling aman untuk hosting bersama.

3. Generate key aplikasi:
```bash
php artisan key:generate
```

---

## 6. Buat Database + Isi Tabel (Migrasi & Seeder)

1. **Buat database baru** di panel hosting (misal `db_tracer_lpkm`), lengkap dengan user + password. **JANGAN** memakai database WordPress.
2. Jalankan migrasi dan seeder (dari folder aplikasi):
```bash
php artisan migrate --force
php artisan db:seed --class=WilayahSeeder
php artisan db:seed
```
   - `migrate` → membuat semua tabel aplikasi.
   - `WilayahSeeder` → data 35 provinsi + 523 kabupaten/kota (wajib, agar dropdown wilayah terisi).
   - `db:seed` → membuat akun Super Admin sesuai `ADMIN_EMAIL`/`ADMIN_PASSWORD` di `.env`.

3. **PENTING — ganti kata sandi super admin segera** setelah berhasil login pertama kali:
   - Login di `APP_URL/login` → menu **Akun** → **Ganti Password**.
   - Sembunyikan `ADMIN_EMAIL`/`ADMIN_PASSWORD` di `.env` setelahnya (atau hapus), agar password admin awal tidak terlihat di file.

---

## 7. Jika Tidak Ada SSH/Artisan Sama Sekali

Minta penanggung jawab web melakukan salah satu:

**Opsi A — Sediakan terminal sekali pakai:** minta menjalankan 4 perintah pada Langkah 6 lewat cPanel → Terminal. Selesai.

**Opsi B — Impor lewat phpMyAdmin:** dari komputer lokal Anda yang sudah menjalankan aplikasi:
1. Buat file SQL dari database lokal yang sudah di-migrasi + di-seed:
   ```bash
   mysqldump -u root -p db_tracer_lpkm > tracer.sql
   ```
2. Di cPanel → **phpMyAdmin** → pilih database baru yang kosong → **Import** → unggah `tracer.sql`.
3. Pastikan nama database, user, password di `.env` sesuai database hosting.

> Opsi B hanya cocok untuk instalasi pertama. Untuk pemutakhiran di kemudian hari tetap perlu `migrate` (terminal), jadi sediakan akses terminal sejak awal.

---

## 8. Finalisasi & Uji

1. **Pastikan folder dapat ditulisi** (writable). Folder yang harus writable:
   - `storage/` (log, cache, file sesi, dan file sementara ekspor/import Excel)
   - `bootstrap/cache/`
   - `public/uploads/fotos/` (tempat foto admin)
   > Di cPanel: klik kanan folder → **Change Permissions** → beri nilai **755** (atau 775/777 sesuai kebijakan hosting).
2. (Opsional) Buat symlink storage:
   ```bash
   php artisan storage:link
   ```
3. Bersihkan cache konfigurasi:
   ```bash
   php artisan config:cache
   php artisan view:cache
   php artisan route:cache
   ```
4. Buka di browser:
   - `https://kuesioner.tracerstudy.univ.ac.id` → halaman kuesioner
   - `.../login` → login admin (akun dari Langkah 6)
   - `.../dashboard-kurva` → dashboard grafik
   - `.../admin/pengaturan` → pengaturan (pesan, opsi jawaban, wilayah)

---

## 9. URL Penting Aplikasi

| Fitur | URL |
|---|---|
| Halaman kuesioner (pengunjung) | `/kuesioner` |
| Login admin | `/login` |
| Dashboard / grafik | `/dashboard-kurva` |
| Pengaturan | `/admin/pengaturan` |
| Import data alumni Excel | `/admin/alumni/import` |
| Manajemen akun admin | `/admin/akun` |
| Riwayat aktivitas admin | `/admin/riwayat` |
| Pemulihan password super admin | `/pemulihan` |

---

## 10. Catatan Penting (Data LLDIKTI)

- **Isi kuesioner sudah disesuaikan dengan standar data LLDIKTI.** Jangan mengubah kode validasi atau pertanyaan kuesioner.
- Di **Pengaturan → opsi jawaban** (`opsi_f8_status`, `opsi_f11_instansi`, `opsi_f12_dana`):
  - **Mengubah teks/label** opsi = aman; grafik dashboard mengikuti teks baru.
  - **MENAMBAH NILAI opsi baru** (mis. `6|Nilai Baru`) pada pertanyaan `f8/f11/f12` = **DITOLAK oleh server** (validasi tetap sesuai LLDIKTI). Jangan menambah nilai baru pada 3 pertanyaan tersebut.
- Untuk mengubah warna/format grafik, ikuti konvensi kode yang sudah ada di `DashboardController.php` dan `dashboard_kurva.blade.php`.

---

## 11. Checklist Pertanyaan untuk Penanggung Jawab Web

Gunakan daftar ini bila pemasangan dilakukan pihak lain.

- [ ] Versi PHP hosting **≥ 8.2**? (cek di MultiPHP/Select PHP Version)
- [ ] Tersedia akses **SSH / Terminal** (`composer`, `php artisan`)? Jika tidak, apakah boleh dibuatkan terminal sekali pakai?
- [ ] Bisa **buat database baru** (nama, user, password) khusus aplikasi ini?
- [ ] Bisa **buat subdomain** + docroot ke folder `public/` aplikasi, lengkap dengan **SSL/HTTPS**?
- [ ] Nama URL akhir aplikasi apa? (mis. `https://kuesioner.tracerstudy.univ.ac.id`) → isikan ke `APP_URL`
- [ ] Apakah folder aplikasi (`storage/`, `bootstrap/cache/`, `public/uploads/fotos/`) dapat diberi izin tulis?
- [ ] Siapa pemegang `ADMIN_EMAIL`/`ADMIN_PASSWORD` awal? (harus segera diganti setelah login pertama)
- [ ] Apakah ada Cloudflare? Jika ya, pastikan SSL mode **Full**.

---

## Lampiran A — Proteksi Folder Aplikasi Jika Di-upload di Bawah `public_html`

Jika aplikasi terpaksa diletakkan di `public_html/tracer-lpkm/`, tambahkan file `.htaccess` di folder `tracer-lpkm` (folder root aplikasi) agar folder internal tidak bisa diakses langsung:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(?:public)/?$ - [F,L]
</IfModule>
```

Selain itu, pastikan URL yang dipakai orang tetap menuju `public/` (misalnya lewat subdomain dengan docroot `.../tracer-lpkm/public`).

---

## Lampiran B — Alternatif Subfolder di Dalam Domain WordPress

Jika tidak memungkinkan membuat subdomain, aplikasi dapat dijalankan di subfolder (contoh `https://tracerstudy.univ.ac.id/kuesioner/`). Ini **lebih rumit** dan hanya disarankan bagi yang sudah berpengalaman.

1. Upload seluruh aplikasi ke folder **di luar** docroot WordPress, misal `/home/USER/aplikasi/tracer-lpkm`.
2. Di dalam folder WordPress (misal `public_html/kuesioner/`), buat file **`index.php`** (penghubung):
   ```php
   <?php
   require __DIR__ . '/../../aplikasi/tracer-lpkm/public/index.php';
   ```
   (sesuaikan path relatif ke folder aplikasi).
3. Buat file **`.htaccess`** di folder tersebut:
   ```apache
   RewriteEngine On
   RewriteBase /kuesioner/
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^ index.php [L]
   ```
4. Salin isi `public/uploads/fotos/` (jika sudah ada) ke folder `kuesioner/uploads/fotos/` karena folder ini tidak ikut di-shim.
5. Aplikasi akan mendeteksi URL dasar `/kuesioner/` secara otomatis. Set `APP_URL=https://tracerstudy.univ.ac.id/kuesioner/`.

> Risiko: aturan rewrite WordPress bisa berinteraksi; `APP_URL` harus mengandung `/kuesioner/`. Jika ada masalah, kembali ke skenario subdomain (disarankan).

---

## Lampiran C — Ringkasan Perintah (dengan SSH)

```bash
# di folder aplikasi
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
# isi .env (APP_URL https, DB_*, ADMIN_EMAIL, ADMIN_PASSWORD)
php artisan migrate --force
php artisan db:seed --class=WilayahSeeder
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan view:cache
php artisan route:cache
```

Selesai — buka `APP_URL`. Jangan lupa ganti kata sandi super admin setelah login pertama.
