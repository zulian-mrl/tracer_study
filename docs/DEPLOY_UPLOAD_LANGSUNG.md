# Panduan Deploy Upload Langsung (cPanel / Shared Hosting)

Panduan ini untuk menaruh aplikasi **Tracer Study UMMY** ke hosting shared (Niagahoster,
IDCloudHost, Dewaweb, dll.) dengan cara **upload langsung** melalui cPanel — tanpa Git/GitHub.

> Ringkas: upload proyek → import database → buat `.env` → arahkan halaman utama ke folder
> `public/` → cek ekstensi PHP. Kalau cPanel punya Terminal, jalankan juga perintah tambahan
> di bagian 9a.

---

## 1. Kebutuhan hosting (pastikan terpenuhi)

- **PHP 8.2 atau 8.3** (Laravel 12 butuh minimal PHP 8.2). Atur lewat cPanel → **MultiPHP Manager / Select PHP Version**.
- **Ekstensi PHP wajib aktif**:
  - `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, `json`
  - **`zip`, `xmlreader`/`xmlwriter`, `gd`** — khusus fitur Export/Import Excel. Tanpa ini, download Excel gagal.
- **Database MySQL** (biasanya otomatis dari paket hosting, lewat cPanel → MySQL Databases).
- Folder berikut **harus bisa ditulis** aplikasi:
  - `storage/` (dan isinya: `framework/sessions`, `logs`, `framework/cache`)
  - `bootstrap/cache/`
  - `public/uploads/` (untuk foto profil admin)

---

## 2. Siapkan backup database (penting, lakukan di komputer kamu)

Di phpMyAdmin lokal, **Export** database `db_tracer_lpkm`:

- Metode: **Cepat / Quick** sudah cukup.
- Format: **SQL**.
- Simpan sebagai `db_tracer_lpkm.sql`.

Kenapa ini penting: database lokal sudah berisi **semua tabel + data + status migrasi + akun
admin**, jadi di hosting kamu **tidak wajib** menjalankan `php artisan migrate`. Cukup import file
SQL ini.

---

## 3. Buat file ZIP proyek

Di komputer, buat ZIP dari **seluruh isi folder proyek** `tracer-lpkm` (termasuk folder `vendor/`).
Catatan:

- Folder `vendor/` ikut di-zip supaya tidak perlu `composer install` di server.
- File `.env` boleh ikut atau tidak — nanti kita buat ulang di server. **Jangan** kirim `.env`
  berisi password lokal ke mana pun jika tidak perlu.
- Folder `storage/logs`, `storage/framework/*` boleh dibiarkan (folder akan dibuat ulang).

---

## 4. Upload & ekstrak di hosting

1. Buka **cPanel → File Manager**.
2. Masuk ke folder **home** (di luar `public_html`), misalnya `~/tracer-lpkm`.
   - Mengapa di luar `public_html`? Agar kode aplikasi (controller, config, `.env`) tidak bisa
     dibuka dari browser — hanya folder `public/` yang terlihat publik.
3. **Upload** file ZIP, lalu klik kanan → **Extract**.

Hasil akhir (contoh):

```
~/tracer-lpkm/
  ├─ app/
  ├─ bootstrap/
  ├─ config/
  ├─ database/
  ├─ public/        <-- ini yang jadi halaman utama
  ├─ storage/
  ├─ vendor/
  └─ ...
```

---

## 5. Buat database di hosting & import data

1. cPanel → **MySQL Databases** → buat database baru, misal `ummu_tracer`, beserta **1 user** dan
   beri **ALL PRIVILEGES** pada database itu. Catat nama db, user, dan password.
2. cPanel → **phpMyAdmin** → pilih database barusan → tab **Import** → pilih file
   `db_tracer_lpkm.sql` → **Go**.
3. Pastikan muncul pesan sukses dan tabel muncul semua (users, kuesioner_alumnis,
   master_alumnis, settings, audit_logs, dll).

---

## 6. Buat file `.env` produksi

Di server, buka `~/tracer-lpkm/.env` (File Manager → *Show Hidden Files* jika perlu). Salin
template di bawah, sesuaikan isinya:

```env
APP_NAME="Tracer Study UMMY"
APP_ENV=production
APP_KEY=SALIN_DARI_ENV_LOKAL
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://tracer.ummu.ac.id

# Database (isi dari langkah 5)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ummu_tracer
DB_USERNAME=ummu_tracer_user
DB_PASSWORD=password_user_db_kamu

SESSION_DRIVER=file
QUEUE_CONNECTION=database
CACHE_STORE=database

# Akun admin awal (dipakai hanya kalau menjalankan db:seed --class=AdminUserSeeder)
ADMIN_EMAIL=admin@umm.ac.id
ADMIN_PASSWORD=ganti_dengan_password_kuat
```

**Tentang `APP_KEY`:**
- Isi `APP_KEY` dengan nilai dari file `.env` **lokal** kamu (yang panjang, awalan `base64:...`).
- Atau, jika cPanel kamu punya **Terminal** (bagian 9a), cukup tulis `APP_KEY=` kosong lalu
  jalankan `php artisan key:generate`.

**PENTING soal SESSION_DRIVER:** pakai `file`, **jangan** `database` — di aplikasi ini belum ada
tabel `sessions`, jadi sesi `database` akan error.

---

## 7. Arahkan halaman utama ke folder `public/`

Laravel hanya aman jika **document root** menunjuk ke folder `public/`. Dua cara:

### Cara A — Subdomain (paling disarankan)
1. cPanel → **Subdomains** → buat `tracer` (mis. `tracer.ummu.ac.id`).
2. **Document Root** diarahkan ke `~/tracer-lpkm/public` (pakai tombol folder saat isi form).
3. Nanti `APP_URL` di `.env` = `https://tracer.ummu.ac.id`.

### Cara B — Domain utama di `public_html` (metode "naikkan index.php")
1. Biarkan proyek di `~/tracer-lpkm`.
2. **Salin** (bukan pindah) isi `~/tracer-lpkm/public/` (file `index.php`, `.htaccess`, dll.)
   ke `public_html/`.
3. Buka `public_html/index.php`, ubah dua baris `require` yang menunjuk `../vendor/autoload.php`
   dan `../bootstrap/app.php` menjadi `../tracer-lpkm/vendor/autoload.php` dan
   `../tracer-lpkm/bootstrap/app.php`.
4. Cek `public_html/.htaccess` tetap ada (dibutuhkan untuk routing).

---

## 8. Izin folder (permission)

Di File Manager, set folder berikut agar **writable** (biasanya `755` untuk folder, `644` file;
jika perlu tulis penuh karena user PHP berbeda, gunakan `775`/`777` — banyak hosting Indonesia
mengandalkan `777` untuk folder `storage`):

```
storage/            → 755 atau 775/777
storage/framework/* → 755 atau 775/777
storage/logs/       → 755 atau 775/777
bootstrap/cache/    → 755 atau 775/777
public/uploads/     → 755 atau 775/777
```

> Tips: jika muncul error "Permission denied" saat mengakses web, naikkan ke `777` lalu uji ulang.

---

## 9. Perintah tambahan

### 9a. Kalau cPanel punya Terminal / SSH (disarankan jika tersedia)

Masuk **cPanel → Terminal**, lalu di folder proyek:

```bash
cd ~/tracer-lpkm

php artisan key:generate          # isi APP_KEY otomatis (skip jika sudah diisi manual)
php artisan migrate --force       # aman dijalankan, akan melewati tabel yang sudah ada
php artisan db:seed --class=AdminUserSeeder
php artisan config:cache          # perkecil beban produksi
php artisan route:cache
```

Perintah boleh gagal sebagian jika tidak tersedia fitur — lanjut ke bagian 10 selama web sudah
terbuka.

### 9b. Kalau tidak ada Terminal (umum di paket murah)

**Tidak wajib** menjalankan apa pun karena:
- Database sudah di-import lengkap (semua tabel + akun admin sudah ikut).
- `APP_KEY` diisi manual dari `.env` lokal.
- Yang wajib hanya memastikan bagian 4–8 benar.

---

## 10. Uji coba setelah deploy

1. Buka domain (mis. `https://tracer.ummu.ac.id`) → seharusnya langsung tampil **Form Kuesioner**.
2. Buka `/login` → login pakai akun admin yang ada di database backup.
3. Buka **Dashboard** → cek grafik tampil.
4. Klik **Export Excel** → file `.xlsx` harus terdownload (ini butuh ekstensi `zip`).
5. Coba **Import** file master alumni → harus sukses.
6. Ganti password admin dari menu **Kelola Akun**, dan set **kode pemulihan** untuk keamanan.

---

## 11. Checklist keamanan

- [ ] `APP_ENV=production` dan `APP_DEBUG=false` (jangan `true` di hosting).
- [ ] `APP_URL` sesuai domain asli.
- [ ] Ganti password default admin.
- [ ] Set kode pemulihan (menu Kelola Akun → Kode Pemulihan) agar tidak terkunci.
- [ ] Pastikan `.env` tidak bisa diakses browser (posisi di luar `public_html`).
- [ ] Jika pakai HTTPS, aktifkan **SSL** di cPanel dan pastikan `APP_URL` memakai `https`.

---

## 12. Troubleshooting umum

| Gejala | Kemungkinan penyebab | Solusi |
|---|---|---|
| Halaman putih / error 500 | `APP_DEBUG` masih `true` tapi ada error, atau folder tidak writable | Set izin `storage` & `bootstrap/cache`; periksa `storage/logs/laravel.log` |
| Error `The stream or file ... could not be opened` | `storage/logs` tidak writable | Naikkan permission folder `storage` |
| Error `Table 'xxx' already exists` saat migrate | Import SQL + migrate jalan bersamaan | Tidak masalah menjalankan `migrate --force` (guard sudah dibuat); atau lewati migrate |
| Download Excel gagal / "zip" error | Ekstensi `zip` (dan `xml`) tidak aktif | Aktifkan di **Select PHP Version** → extension |
| Upload foto gagal | Folder `public/uploads` tidak writable | Buat folder & naikkan permission |
| Error 419 saat submit form | Token CSRF/`SESSION_DRIVER` masalah | Pastikan `SESSION_DRIVER=file` dan `storage/framework/sessions` writable |
| Login berulang kehalaman login | `url.intended` tersimpan | Buka `/login?kembali=1` lalu login kembali |
