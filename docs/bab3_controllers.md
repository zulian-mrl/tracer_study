# Bab 3 — Pendefinisian Controller (app/Http/Controllers)

## 3.x.x Controller `Controller`

`Controller` merupakan kelas dasar (kelas abstrak) yang menjadi induk bagi seluruh controller pada sistem. Kelas ini didefinisikan pada berkas `app/Http/Controllers/Controller.php` di dalam *namespace* `App\Http\Controllers`. Seluruh controller lain yang dibangun pada sistem mewarisi kelas ini, sehingga struktur dasar *controller* pada aplikasi menjadi terpusat dan seragam. Kelas dasar ini sengaja didefinisikan tanpa berisi logika bisnis tertentu karena fungsinya hanya sebagai fondasi pewarisan kelas, sehingga setiap pengembangan *controller* baru dapat dimulai dengan menurunkan kelas `Controller` ini.

## 3.x.x Controller `AuthController`

Controller `AuthController` merupakan *controller* yang menangani proses autentikasi pengguna (admin) pada sistem, yang didefinisikan pada berkas `app/Http/Controllers/AuthController.php`. Controller ini berperan dalam mengatur proses masuk (*login*) dan keluar (*logout*) dari sistem.

Controller `AuthController` dilengkapi dengan tiga metode utama, yaitu:

a) Metode `showLoginForm()`, yaitu metode yang digunakan untuk menampilkan halaman formulir masuk (*login*). Metode ini hanya bertugas mengembalikan tampilan halaman login kepada pengguna.

b) Metode `login(Request $request)`, yaitu metode yang digunakan untuk memproses permintaan masuk ke dalam sistem. Sebelum dilakukan proses autentikasi, data yang dikirimkan pengguna terlebih dahulu divalidasi, yaitu kolom `email` wajib diisi dengan format surel yang benar dan kolom `password` wajib diisi. Apabila proses autentikasi berhasil, maka sesi pengguna akan diperbarui kembali dan pengguna diarahkan menuju halaman *dashboard* kuesioner. Sebaliknya, apabila autentikasi gagal, sistem akan menampilkan pesan kesalahan bahwa email atau kata sandi yang dimasukkan salah.

c) Metode `logout(Request $request)`, yaitu metode yang digunakan untuk mengakhiri sesi masuk pengguna. Proses ini mencakup penghapusan data autentikasi, pembatalan seluruh data sesi, serta pembuatan ulang *token* sesi agar aman dari serangan pemalsuan sesi (*CSRF*), kemudian pengguna diarahkan kembali menuju halaman login.

## 3.x.x Controller `AccountController`

Controller `AccountController` merupakan *controller* yang menangani seluruh proses pengelolaan akun admin pada sistem, yang didefinisikan pada berkas `app/Http/Controllers/AccountController.php`. Hampir seluruh metode pada *controller* ini diawali dengan pemanggilan metode privat `cekSuper()` yang berfungsi sebagai pengaman, yaitu memastikan bahwa pengguna yang mengakses telah masuk ke dalam sistem dan memiliki status *super admin*. Apabila ketentuan tersebut tidak terpenuhi, sistem akan menghentikan proses dan menampilkan kode kesalahan 403.

Metode-metode yang dimiliki oleh `AccountController` antara lain:

a) Metode `index()`, yaitu metode yang digunakan untuk menampilkan daftar seluruh akun admin yang tersimpan pada sistem. Data akun diurutkan berdasarkan status *super admin* terlebih dahulu kemudian diurutkan berdasarkan alamat surel.

b) Metode `store(Request $request)`, yaitu metode yang digunakan untuk menambahkan akun admin baru. Data yang dikirimkan divalidasi terlebih dahulu meliputi kolom `name`, `email` yang harus unik pada tabel pengguna, `password` yang minimal delapan karakter dan harus sesuai dengan konfirmasinya, serta `is_super` sebagai penanda status akun. Kata sandi yang disimpan terlebih dahulu dienkripsi menggunakan fungsi `Hash::make()` sebelum disimpan ke dalam tabel pengguna.

c) Metode `toggleSuper(Request $request, User $user)`, yaitu metode yang digunakan untuk mengubah status *super admin* suatu akun. Metode ini dilengkapi dengan beberapa ketentuan pengamanan, antara lain akun tidak dapat mengubah status akunnya sendiri, akun utama *super admin* tidak dapat diturunkan, serta status *super admin* terakhir tidak dapat diturunkan agar sistem selalu memiliki minimal satu akun *super admin*.

d) Metode `gantiPassword(Request $request)`, yaitu metode yang digunakan oleh pengguna untuk mengganti kata sandi akunnya sendiri. Sebelum kata sandi diperbarui, sistem memverifikasi kebenaran kata sandi lama menggunakan fungsi `Hash::check()`. Apabila kata sandi lama tidak sesuai, sistem menampilkan pesan kesalahan dan proses penggantian tidak dilanjutkan.

e) Metode `simpanKodePemulihan(Request $request)`, yaitu metode yang digunakan oleh *super admin* untuk menyimpan kode pemulihan kata sandi. Apabila pengguna mencentang opsi penghapusan, maka kode pemulihan akan dihapus dari pengaturan sistem. Sebaliknya, apabila kode baru diisi, kode tersebut divalidasi minimal delapan karakter kemudian dienkripsi dan disimpan melalui model `Setting`.

f) Metode `reset(Request $request, User $user)`, yaitu metode yang digunakan oleh *super admin* untuk mereset kata sandi akun admin lainnya tanpa harus mengetahui kata sandi lama. Ketentuan pengamanan juga diberlakukan, yaitu akun tidak dapat mereset kata sandi akunnya sendiri melalui metode ini.

g) Metode `hapus(Request $request, User $user)`, yaitu metode yang digunakan untuk menghapus akun admin. Metode ini menerapkan pembatasan, antara lain akun tidak dapat menghapus akunnya sendiri, akun berstatus *super admin* tidak dapat dihapus, dan *super admin* terakhir tidak dapat dihapus.

h) Metode `uploadFoto(Request $request)`, yaitu metode yang digunakan oleh pengguna untuk mengunggah foto profil. Berkas foto divalidasi harus berupa gambar dengan format `jpeg`, `png`, `jpg`, `webp`, atau `gif` dengan ukuran maksimal dua megabyte. Berkas disimpan pada direktori `public/uploads/fotos` dengan nama yang dibentuk dari identitas pengguna dan waktu unggah, serta foto lama otomatis dihapus apabila diganti.

i) Metode `updateNama(Request $request)`, yaitu metode yang digunakan oleh pengguna untuk memperbarui nama akunnya sendiri. Data nama yang dikirimkan divalidasi wajib diisi, kemudian disimpan ke dalam tabel pengguna.

## 3.x.x Controller `RecoveryController`

Controller `RecoveryController` merupakan *controller* yang menangani proses pemulihan kata sandi akun *super admin*, yang didefinisikan pada berkas `app/Http/Controllers/RecoveryController.php`. Controller ini dirancang untuk membantu proses penggantian kata sandi *super admin* melalui halaman pemulihan yang tidak memerlukan autentikasi.

Controller `RecoveryController` dilengkapi dengan dua metode, yaitu:

a) Metode `index()`, yaitu metode yang digunakan untuk menampilkan halaman formulir pemulihan kata sandi kepada pengguna.

b) Metode `reset(Request $request)`, yaitu metode yang digunakan untuk memproses permintaan pemulihan kata sandi. Data yang dikirimkan divalidasi meliputi kolom `email` yang harus terdaftar pada tabel pengguna, `kode` sebagai kode pemulihan, serta `password` yang minimal delapan karakter dan sesuai dengan konfirmasinya. Proses pemulihan hanya diperbolehkan bagi akun berstatus *super admin*. Sistem kemudian membandingkan kode yang dimasukkan dengan kode pemulihan yang tersimpan pada model `Setting` menggunakan fungsi `Hash::check()`. Apabila seluruh verifikasi berhasil, kata sandi baru akan dienkripsi dan disimpan, kemudian pengguna diarahkan kembali ke halaman login.

## 3.x.x Controller `SettingsController`

Controller `SettingsController` merupakan *controller* yang menangani proses pengelolaan pengaturan sistem, yang didefinisikan pada berkas `app/Http/Controllers/SettingsController.php`. Controller ini menjadi penghubung antara halaman pengaturan dengan model `Setting`.

Controller `SettingsController` dilengkapi dengan dua metode, yaitu:

a) Metode `index()`, yaitu metode yang digunakan untuk menampilkan halaman pengaturan. Sebelum halaman ditampilkan, sistem terlebih dahulu menjalankan metode `syncDefaults()` pada model `Setting` untuk memastikan seluruh pengaturan *default* tersedia di dalam *database*. Selanjutnya seluruh data pengaturan diambil melalui metode `allCached()` dan nilai *default* diambil melalui metode `defaults()` untuk kemudian diteruskan ke halaman tampilan.

b) Metode `update(Request $request)`, yaitu metode yang digunakan untuk menyimpan perubahan pengaturan yang dikirimkan melalui formulir. Sistem hanya mengambil data yang kuncinya sesuai dengan daftar kunci *default* pengaturan, kemudian setiap nilai yang terisi disimpan menggunakan metode `set()` pada model `Setting`. Setelah proses penyimpanan selesai, pengguna diarahkan kembali ke halaman pengaturan dengan pesan bahwa pengaturan berhasil disimpan.

## 3.x.x Controller `KuesionerController`

Controller `KuesionerController` merupakan *controller* utama pada sistem yang menangani proses pengisian kuesioner oleh alumni, pengelolaan data master alumni, serta penyajian analitik hasil tracer study. Controller ini didefinisikan pada berkas `app/Http/Controllers/KuesionerController.php`.

Metode-metode yang dimiliki oleh `KuesionerController` antara lain:

a) Metode `import(Request $request)`, yaitu metode yang digunakan untuk mengimpor data master alumni dari berkas Excel. Berkas divalidasi wajib diisi dengan format `xlsx`, `xls`, atau `csv` dan ukuran maksimal sepuluh megabyte. Proses pembacaan berkas dilakukan dengan memanfaatkan pustaka *Maatwebsite Excel* melalui kelas `AlumniImport`. Apabila terjadi kesalahan pada saat proses impor, sistem akan menampilkan pesan kesalahan yang sesuai.

b) Metode `index()`, yaitu metode yang digunakan untuk menampilkan halaman kuesioner kepada alumni.

c) Metode `store(Request $request)`, yaitu metode yang digunakan untuk memproses dan menyimpan jawaban kuesioner yang dikirimkan oleh alumni. Sebelum data disimpan, sistem melakukan validasi terhadap data identitas dan jawaban wajib, meliputi nomor mahasiswa, nama, email dengan domain `@gmail.com`, status saat ini, serta kapan mulai mencari pekerjaan. Sistem kemudian melakukan verifikasi data alumni dengan mencocokkan kombinasi nomor mahasiswa, nomor induk kependudukan, tahun lulus, dan kode program studi terhadap tabel `master_alumnis`. Apabila data tidak ditemukan atau nama yang dimasukkan tidak sesuai, proses penyimpanan ditolak dan sistem menampilkan pesan kesalahan. Setelah seluruh verifikasi berhasil, seluruh jawaban kuesioner disimpan ke dalam tabel `kuesioner_alumnis` melalui mekanisme `updateOrInsert` berdasarkan nomor mahasiswa, kemudian pengguna diarahkan kembali dengan pesan bahwa kuesioner berhasil dikirim.

d) Metode `dashboard(Request $request)`, yaitu metode yang digunakan untuk menampilkan halaman *dashboard* analitik hasil tracer study. Metode ini mengambil seluruh data alumni dari tabel `kuesioner_alumnis` dan menerapkan penyaringan berdasarkan tahun lulus serta kode program studi yang dikirim sebagai parameter. Data tersebut kemudian diolah menjadi berbagai bentuk agregasi untuk keperluan grafik, antara lain status kesibukan alumni, pendapatan per bulan, jenis perusahaan, sumber dana kuliah, posisi dan tingkat jabatan, sebaran lokasi kerja, destinasi studi lanjut, perbandingan kompetensi yang dikuasai dengan kompetensi yang diperlukan, penekanan metode pembelajaran, masa tunggu mendapatkan pekerjaan, saluran mencari pekerjaan, rasio proses lamaran kerja, keaktifan mencari kerja, serta alasan mengambil pekerjaan yang tidak sesuai bidang pendidikan. Seluruh hasil pengolahan data tersebut diteruskan ke halaman tampilan *dashboard* beserta daftar nama alumni yang terkait pada setiap kategori grafik agar dapat dilihat saat grafik diklik.

e) Metode `exportExcel(Request $request)`, yaitu metode yang digunakan untuk mengekspor data hasil tracer study ke dalam berkas Excel. Data alumni diambil dari tabel `kuesioner_alumnis` dengan penyaringan berdasarkan tahun lulus dan kode program studi, kemudian diurutkan berdasarkan tahun lulus, kode program studi, dan nama secara berurutan. Metode ini juga menghitung rekap keaktifan mencari kerja, saluran mencari pekerjaan, dan penekanan metode pembelajaran dengan logika yang sama seperti pada *dashboard*. Seluruh data rekap beserta data mentah alumni kemudian dibungkus ke dalam kelas `KuesionerAlumniExport` dan diunduh oleh pengguna sebagai berkas berformat `.xlsx` dengan nama berkas yang disesuaikan berdasarkan filter yang dipilih.
